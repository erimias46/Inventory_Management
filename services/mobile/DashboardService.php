<?php

declare(strict_types=1);

final class DashboardService
{
    private mysqli $con;

    public function __construct(mysqli $con)
    {
        $this->con = $con;
    }

    public function dailySales(int $month, int $year): array
    {
        if ($month < 1 || $month > 12) {
            ApiResponse::error('invalid_date', 'Invalid month', 422);
        }
        $daysInMonth = (int) date('t', mktime(0, 0, 0, $month, 1, $year));
        $dailySales = array_fill(1, $daysInMonth, 0);

        $parts = [];
        foreach ($this->salesSources() as $src) {
            $parts[] = "SELECT sales_date, quantity FROM `{$src['sales_table']}` WHERE status = 'active'";
        }
        if ($parts === []) {
            return ['categories' => range(1, $daysInMonth), 'series' => array_values($dailySales)];
        }

        $union = implode(' UNION ALL ', $parts);
        $query = "SELECT DAY(sales_date) AS day, SUM(quantity) AS total 
          FROM ($union) AS combined
          WHERE MONTH(sales_date) = ? AND YEAR(sales_date) = ?
          GROUP BY DAY(sales_date)";

        $stmt = mysqli_prepare($this->con, $query);
        mysqli_stmt_bind_param($stmt, 'ii', $month, $year);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        while ($row = mysqli_fetch_assoc($result)) {
            $dailySales[(int) $row['day']] = (int) $row['total'];
        }
        mysqli_stmt_close($stmt);

        return [
            'categories' => range(1, $daysInMonth),
            'series' => array_values($dailySales),
        ];
    }

    public function summary(string $period = '30'): array
    {
        $overview = $this->overview($period);
        return [
            'period' => $period,
            'revenue' => $overview['kpis']['earnings'],
            'cash' => $overview['kpis']['cash'],
            'bank' => $overview['kpis']['bank'],
            'units_sold' => $overview['kpis']['quantity_sold'],
        ];
    }

    /**
     * Full dashboard payload (web index2.php parity).
     */
    public function overview(string $period = '30', ?int $year = null): array
    {
        $year = $year ?? (int) date('Y');
        $cond = $this->dateCondition($period);

        $kpis = [
            'profit' => round($this->totalProfit($cond), 2),
            'earnings' => round($this->totalEarnings($cond), 2),
            'quantity_sold' => $this->totalQuantity($cond),
            'transactions' => $this->totalTransactions($cond),
            'cash' => round($this->totalCash($cond), 2),
            'bank' => round($this->totalBank($cond), 2),
        ];

        return [
            'period' => $period,
            'period_label' => $this->periodLabel($period),
            'year' => $year,
            'kpis' => $kpis,
            'today' => $this->todaySnapshot(),
            'activity' => $this->activityCounts($cond),
            'by_category' => $this->salesByCategory($cond),
            'banks' => $this->bankBreakdown($period),
            'top_products' => $this->topProducts($period),
            'stock' => $this->stockSummary(),
            'monthly' => $this->monthlyBreakdown($year),
        ];
    }

    /** @return list<array{slug: string, label: string, sales_table: string, product_table: string, fk: string, name_col: string}> */
    private function salesSources(): array
    {
        $out = [];
        foreach (stock_get_categories($this->con) as $cat) {
            $slug = $cat['slug'];
            $meta = $this->metaForSlug($slug);
            if ($meta !== null) {
                $meta['label'] = $cat['label'];
                $out[] = $meta;
            }
        }
        return $out;
    }

    private function metaForSlug(string $slug): ?array
    {
        $productTable = $slug;
        $salesTable = $slug === 'jeans' ? 'sales' : $slug . '_sales';
        $fk = $slug . '_id';
        $nameCol = $slug . '_name';

        if (!stock_table_exists($this->con, $salesTable)) {
            return null;
        }

        return [
            'slug' => $slug,
            'label' => ucfirst($slug),
            'sales_table' => $salesTable,
            'product_table' => $productTable,
            'fk' => $fk,
            'name_col' => $nameCol,
        ];
    }

    private function dateCondition(string $period): string
    {
        return match ($period) {
            'today' => 'sales_date >= CURDATE()',
            'yesterday' => 'sales_date >= CURDATE() - INTERVAL 1 DAY AND sales_date < CURDATE()',
            '7' => 'sales_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)',
            '60' => 'sales_date >= DATE_SUB(CURDATE(), INTERVAL 60 DAY)',
            '180' => 'sales_date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)',
            '365' => 'sales_date >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR)',
            default => 'sales_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)',
        };
    }

    private function periodLabel(string $period): string
    {
        return match ($period) {
            'today' => 'Today',
            'yesterday' => 'Yesterday',
            '7' => 'Last 7 days',
            '60' => 'Last 60 days',
            '180' => 'Last 6 months',
            '365' => 'Last year',
            default => 'Last 30 days',
        };
    }

    private function scalarQuery(string $sql): float
    {
        $result = mysqli_query($this->con, $sql);
        if ($result && ($row = mysqli_fetch_assoc($result))) {
            return (float) ($row['v'] ?? $row['total'] ?? $row['cnt'] ?? 0);
        }
        return 0.0;
    }

    private function intQuery(string $sql): int
    {
        return (int) $this->scalarQuery($sql);
    }

    private function unionSalesSelect(string $expr, string $cond, string $status = "status = 'active'"): string
    {
        $parts = [];
        foreach ($this->salesSources() as $src) {
            $t = $src['sales_table'];
            $parts[] = "SELECT $expr AS v FROM `$t` WHERE $status AND $cond";
        }
        return $parts === [] ? 'SELECT 0 AS v' : implode(' UNION ALL ', $parts);
    }

    private function totalEarnings(string $cond): float
    {
        $parts = [];
        foreach ($this->salesSources() as $src) {
            $t = $src['sales_table'];
            $parts[] = "SELECT COALESCE(SUM(price),0) AS v FROM `$t` WHERE status IN ('active','Exchange Sells','Exchange Sellss') AND $cond";
        }
        if ($parts === []) {
            return 0.0;
        }
        return $this->scalarQuery('SELECT COALESCE(SUM(v),0) AS v FROM (' . implode(' UNION ALL ', $parts) . ') x');
    }

    private function totalCash(string $cond): float
    {
        return $this->scalarQuery('SELECT COALESCE(SUM(v),0) AS v FROM (' . $this->unionSalesSelect('cash', $cond) . ') x');
    }

    private function totalBank(string $cond): float
    {
        return $this->scalarQuery('SELECT COALESCE(SUM(v),0) AS v FROM (' . $this->unionSalesSelect('bank', $cond) . ') x');
    }

    private function totalQuantity(string $cond): int
    {
        return $this->intQuery('SELECT COALESCE(SUM(v),0) AS v FROM (' . $this->unionSalesSelect('quantity', $cond) . ') x');
    }

    private function totalTransactions(string $cond): int
    {
        $parts = [];
        foreach ($this->salesSources() as $src) {
            $t = $src['sales_table'];
            $idCol = stock_column_exists($this->con, $t, 'sales_id') ? 'sales_id' : 'id';
            $parts[] = "SELECT COUNT($idCol) AS v FROM `$t` WHERE status = 'active' AND $cond";
        }
        if ($parts === []) {
            return 0;
        }
        return $this->intQuery('SELECT COALESCE(SUM(v),0) AS v FROM (' . implode(' UNION ALL ', $parts) . ') x');
    }

    private function totalProfit(string $cond): float
    {
        $total = 0.0;
        foreach ($this->salesSources() as $src) {
            if (!stock_table_exists($this->con, $src['product_table'])) {
                continue;
            }
            if (!stock_column_exists($this->con, $src['product_table'], 'buy_price')) {
                continue;
            }
            $sql = "SELECT COALESCE(SUM((s.price - p.buy_price) * s.quantity),0) AS v
                FROM `{$src['sales_table']}` s
                INNER JOIN `{$src['product_table']}` p ON s.{$src['fk']} = p.id
                WHERE s.status = 'active' AND $cond";
            $total += $this->scalarQuery($sql);
        }
        return $total;
    }

    private function todaySnapshot(): array
    {
        $cond = 'sales_date >= CURDATE()';
        return [
            'earnings' => round($this->totalEarnings($cond), 2),
            'quantity' => $this->totalQuantity($cond),
            'transactions' => $this->totalTransactions($cond),
            'cash' => round($this->totalCash($cond), 2),
            'bank' => round($this->totalBank($cond), 2),
        ];
    }

    private function activityCounts(string $cond): array
    {
        $shop = 0;
        $delivery = 0;
        $exchange = 0;
        $refund = 0;

        foreach ($this->salesSources() as $src) {
            $t = $src['sales_table'];
            if (stock_column_exists($this->con, $t, 'method')) {
                $shop += $this->intQuery("SELECT COUNT(*) AS v FROM `$t` WHERE method = 'shop' AND $cond");
                $delivery += $this->intQuery("SELECT COUNT(*) AS v FROM `$t` WHERE method = 'delivery' AND $cond");
            }
            $exchange += $this->intQuery("SELECT COUNT(*) AS v FROM `$t` WHERE status = 'exchange' AND $cond");
            $refund += $this->intQuery("SELECT COUNT(*) AS v FROM `$t` WHERE status = 'refund' AND $cond");
        }

        return [
            'shop' => $shop,
            'delivery' => $delivery,
            'exchange' => $exchange,
            'refund' => $refund,
        ];
    }

    private function salesByCategory(string $cond): array
    {
        $rows = [];
        foreach ($this->salesSources() as $src) {
            $t = $src['sales_table'];
            $rev = $this->scalarQuery("SELECT COALESCE(SUM(price),0) AS v FROM `$t` WHERE status = 'active' AND $cond");
            $qty = $this->intQuery("SELECT COALESCE(SUM(quantity),0) AS v FROM `$t` WHERE status = 'active' AND $cond");
            $profit = 0.0;
            if (stock_table_exists($this->con, $src['product_table']) && stock_column_exists($this->con, $src['product_table'], 'buy_price')) {
                $profit = $this->scalarQuery("SELECT COALESCE(SUM((s.price - p.buy_price) * s.quantity),0) AS v
                    FROM `{$src['sales_table']}` s
                    INNER JOIN `{$src['product_table']}` p ON s.{$src['fk']} = p.id
                    WHERE s.status = 'active' AND $cond");
            }
            if ($rev > 0 || $qty > 0) {
                $rows[] = [
                    'slug' => $src['slug'],
                    'label' => $src['label'],
                    'revenue' => round($rev, 2),
                    'quantity' => $qty,
                    'profit' => round($profit, 2),
                ];
            }
        }
        usort($rows, fn($a, $b) => $b['revenue'] <=> $a['revenue']);
        return $rows;
    }

    private function bankBreakdown(string $period): array
    {
        $interval = match ($period) {
            '60' => '60 DAY',
            '180' => '6 MONTH',
            '365' => '1 YEAR',
            '7' => '7 DAY',
            default => '30 DAY',
        };

        $parts = [];
        foreach ($this->salesSources() as $src) {
            $t = $src['sales_table'];
            if (!stock_column_exists($this->con, $t, 'bank')) {
                continue;
            }
            $nameExpr = stock_column_exists($this->con, $t, 'bank_name') ? 'bank_name' : "'Bank'";
            $parts[] = "SELECT bank_id, $nameExpr AS bank_name, bank FROM `$t`
                WHERE sales_date >= DATE_SUB(CURDATE(), INTERVAL $interval) AND bank > 0";
        }
        if ($parts === []) {
            return [];
        }

        $sql = 'SELECT bank_name, SUM(bank) AS total FROM (' . implode(' UNION ALL ', $parts) . ') x
            GROUP BY bank_name ORDER BY total DESC LIMIT 12';
        $result = mysqli_query($this->con, $sql);
        $rows = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $rows[] = [
                    'name' => $row['bank_name'] ?? 'Unknown',
                    'total' => round((float) $row['total'], 2),
                ];
            }
        }
        return $rows;
    }

    private function topProducts(string $period): array
    {
        $days = match ($period) {
            'today' => 1,
            'yesterday' => 1,
            '7' => 7,
            '60' => 60,
            '180' => 180,
            '365' => 365,
            default => 30,
        };
        $cond = "sales_date >= CURDATE() - INTERVAL $days DAY";

        $parts = [];
        foreach ($this->salesSources() as $src) {
            if (!stock_column_exists($this->con, $src['sales_table'], $src['name_col'])) {
                continue;
            }
            $t = $src['sales_table'];
            $n = $src['name_col'];
            $parts[] = "SELECT `$n` AS product_name, quantity, price, status FROM `$t` WHERE $cond";
        }
        if ($parts === []) {
            return [];
        }

        $sql = "SELECT product_name, SUM(quantity) AS total_sold, MAX(price) AS price
            FROM (" . implode(' UNION ALL ', $parts) . ") combined
            WHERE status = 'active' AND product_name IS NOT NULL AND product_name != ''
            GROUP BY product_name ORDER BY total_sold DESC LIMIT 10";

        $result = mysqli_query($this->con, $sql);
        $rows = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $rows[] = [
                    'name' => $row['product_name'],
                    'quantity' => (int) $row['total_sold'],
                    'price' => round((float) $row['price'], 2),
                ];
            }
        }
        return $rows;
    }

    private function stockSummary(): array
    {
        $total = 0;
        $recent = [];
        if (!stock_table_exists($this->con, 'products')) {
            return ['total_added' => 0, 'recent' => []];
        }

        $r = mysqli_query($this->con, 'SELECT COALESCE(SUM(quantity),0) AS t FROM products');
        if ($r && ($row = mysqli_fetch_assoc($r))) {
            $total = (int) $row['t'];
        }

        $r2 = mysqli_query($this->con, 'SELECT product_name, quantity, source_table, created_at FROM products ORDER BY created_at DESC LIMIT 8');
        if ($r2) {
            while ($row = mysqli_fetch_assoc($r2)) {
                $recent[] = [
                    'name' => $row['product_name'],
                    'quantity' => (int) $row['quantity'],
                    'category' => ucfirst((string) $row['source_table']),
                    'created_at' => $row['created_at'] ?? '',
                ];
            }
        }

        return ['total_added' => $total, 'recent' => $recent];
    }

    private function monthlyBreakdown(int $year): array
    {
        $months = [];
        for ($m = 1; $m <= 12; $m++) {
            $key = sprintf('%04d-%02d', $year, $m);
            $months[$key] = [
                'month' => $key,
                'label' => date('F', mktime(0, 0, 0, $m, 1)),
                'quantity' => 0,
                'revenue' => 0.0,
                'profit' => 0.0,
            ];
        }

        foreach ($this->salesSources() as $src) {
            if (!stock_table_exists($this->con, $src['product_table'])) {
                $sql = "SELECT DATE_FORMAT(sales_date,'%Y-%m') AS m,
                    SUM(quantity) AS qty, SUM(price) AS rev
                    FROM `{$src['sales_table']}` WHERE status='active' AND YEAR(sales_date)=$year
                    GROUP BY m";
                $profitSql = null;
            } else {
                $sql = "SELECT DATE_FORMAT(s.sales_date,'%Y-%m') AS m,
                    SUM(s.quantity) AS qty, SUM(s.price * s.quantity) AS rev,
                    SUM((s.price - p.buy_price) * s.quantity) AS prof
                    FROM `{$src['sales_table']}` s
                    INNER JOIN `{$src['product_table']}` p ON s.{$src['fk']} = p.id
                    WHERE s.status='active' AND YEAR(s.sales_date)=$year
                    GROUP BY m";
            }

            $result = mysqli_query($this->con, $sql);
            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $m = $row['m'];
                    if (!isset($months[$m])) {
                        continue;
                    }
                    $months[$m]['quantity'] += (int) $row['qty'];
                    $months[$m]['revenue'] += (float) $row['rev'];
                    if (isset($row['prof'])) {
                        $months[$m]['profit'] += (float) $row['prof'];
                    }
                }
            }
        }

        $out = [];
        foreach ($months as $data) {
            $qty = $data['quantity'];
            $out[] = [
                'month' => $data['month'],
                'label' => $data['label'],
                'quantity' => $qty,
                'revenue' => round($data['revenue'], 2),
                'profit' => round($data['profit'], 2),
                'avg_sale' => $qty > 0 ? round($data['revenue'] / $qty, 2) : 0.0,
                'avg_profit' => $qty > 0 ? round($data['profit'] / $qty, 2) : 0.0,
            ];
        }
        return $out;
    }
}

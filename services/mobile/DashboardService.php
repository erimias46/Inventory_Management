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

        $query = "SELECT DAY(sales_date) AS day, SUM(quantity) AS total 
          FROM (
              SELECT sales_date, quantity FROM sales
              UNION ALL SELECT sales_date, quantity FROM shoes_sales
              UNION ALL SELECT sales_date, quantity FROM accessory_sales
              UNION ALL SELECT sales_date, quantity FROM complete_sales
              UNION ALL SELECT sales_date, quantity FROM top_sales
              UNION ALL SELECT sales_date, quantity FROM wig_sales
              UNION ALL SELECT sales_date, quantity FROM cosmetics_sales
          ) AS combined
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
        $dateCondition = match ($period) {
            'today' => '{column} >= CURDATE()',
            'yesterday' => '{column} >= CURDATE() - INTERVAL 1 DAY AND {column} < CURDATE()',
            '7' => '{column} >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)',
            '60' => '{column} >= DATE_SUB(CURDATE(), INTERVAL 60 DAY)',
            '180' => '{column} >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)',
            '365' => '{column} >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR)',
            default => '{column} >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)',
        };

        $totals = ['revenue' => 0.0, 'cash' => 0.0, 'bank' => 0.0, 'units' => 0];
        $tables = [
            ['sales', 'jeans_name', 'jeans'],
            ['shoes_sales', 'shoes_name', 'shoes'],
            ['top_sales', 'top_name', 'top'],
            ['complete_sales', 'complete_name', 'complete'],
            ['accessory_sales', 'accessory_name', 'accessory'],
            ['wig_sales', 'wig_name', 'wig'],
            ['cosmetics_sales', 'cosmetics_name', 'cosmetics'],
        ];

        foreach ($tables as [$table, ,]) {
            $cond = str_replace('{column}', 'sales_date', $dateCondition);
            $sql = "SELECT COALESCE(SUM(price),0) AS rev, COALESCE(SUM(cash),0) AS cash, COALESCE(SUM(bank),0) AS bank, COALESCE(SUM(quantity),0) AS units
                    FROM `$table` WHERE status = 'active' AND $cond";
            $result = mysqli_query($this->con, $sql);
            if ($result && $row = mysqli_fetch_assoc($result)) {
                $totals['revenue'] += (float) $row['rev'];
                $totals['cash'] += (float) $row['cash'];
                $totals['bank'] += (float) $row['bank'];
                $totals['units'] += (int) $row['units'];
            }
        }

        return [
            'period' => $period,
            'revenue' => round($totals['revenue'], 2),
            'cash' => round($totals['cash'], 2),
            'bank' => round($totals['bank'], 2),
            'units_sold' => $totals['units'],
        ];
    }
}

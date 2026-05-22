<?php
$redirect_link = "";
include 'partials/main.php';


include 'include/db.php';



$id = $_SESSION['user_id'];

$result = mysqli_query($con, "SELECT * FROM user WHERE user_id = $id");


if ($result) {

    $row = mysqli_fetch_assoc($result);


    if ($row) {

        $user_id = $row['user_id'];
        $user_name = $row['user_name'];
        $password = $row['password'];
        $privileged = $row['previledge'];
        $module = json_decode($row['module'], true);
    } else {
        echo "No user found with the specified ID";
    }

    // Free the result set
    mysqli_free_result($result);
} else {
    // Handle the case where the query failed
    echo "Error executing query: " . mysqli_error($con);
}
?>

<head>
    <?php $title = "Dashboard";
    include 'partials/title-meta.php'; ?>

    <?php include 'partials/head-css.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

</head>

<?php
$from_date = empty($_GET['from']) ? "1000-01-01" : $_GET['from'];
$to_date = empty($_GET['to']) ? "3000-01-01" : $_GET['to'];
?>

<body>

    <!-- Begin page -->
    <div class="flex wrapper">

        <?php include 'partials/menu.php'; ?>

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="page-content">

            <?php include 'partials/topbar.php'; ?>

            <main class="flex-grow p-6">

                <?php
                $subtitle = "Menu";
                $pagetitle = "Dashboard";
                include 'partials/page-title.php'; ?>

                <div class="grid 2xl:grid-cols-4 gap-6 mb-6">

                    <div class="2xl:col-span-3">
                        <div class="grid xl:grid-cols-4 md:grid-cols-2 gap-6 mb-6">


                            <div class="card">
                                <div class="p-6">
                                    <div class="flex justify-between items-center">
                                        <div
                                            class="w-20 h-20 rounded-full inline-flex items-center justify-center bg-primary/25">
                                            <i class="mgc_bill_line text-4xl text-primary"></i>
                                        </div>

                                        <div class="text-right">
                                            <!-- Filter Dropdown -->
                                            <div>
                                                <form method="get">
                                                    <select name="period" onchange="this.form.submit()"
                                                        class="px-2 py-1 border rounded-md text-sm focus:ring-2 focus:ring-blue-500">
                                                        <option value="today" <?= ($_GET['period'] ?? '30') == 'today' ? 'selected' : '' ?>>Today</option>
                                                        <option value="yesterday" <?= ($_GET['period'] ?? '30') == 'yesterday' ? 'selected' : '' ?>>Yesterday</option>
                                                        <option value="7" <?= ($_GET['period'] ?? '30') == '7' ? 'selected' : '' ?>>7 Days</option>
                                                        <option value="30" <?= ($_GET['period'] ?? '30') == '30' ? 'selected' : '' ?>>30 Days</option>
                                                        <option value="60" <?= ($_GET['period'] ?? '30') == '60' ? 'selected' : '' ?>>60 Days</option>
                                                        <option value="180" <?= ($_GET['period'] ?? '30') == '180' ? 'selected' : '' ?>>6 Months</option>
                                                        <option value="365" <?= ($_GET['period'] ?? '30') == '365' ? 'selected' : '' ?>>1 Year</option>
                                                    </select>
                                                </form>
                                            </div>

                                            <h3 class="text-gray-700 mt-1 text-2xl font-bold mb-5 dark:text-gray-300">
                                                <?php
                                                $selectedPeriod = $_GET['period'] ?? '30';

                                                // Define date conditions
                                                switch ($selectedPeriod) {
                                                    case 'today':
                                                        $dateCondition = "{column} >= CURDATE()";
                                                        break;
                                                    case 'yesterday':
                                                        $dateCondition = "{column} >= CURDATE() - INTERVAL 1 DAY AND {column} < CURDATE()";
                                                        break;
                                                    case '7':
                                                        $dateCondition = "{column} >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
                                                        break;
                                                    case '30':
                                                        $dateCondition = "{column} >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
                                                        break;
                                                    case '60':
                                                        $dateCondition = "{column} >= DATE_SUB(CURDATE(), INTERVAL 60 DAY)";
                                                        break;
                                                    case '180':
                                                        $dateCondition = "{column} >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)";
                                                        break;
                                                    case '365':
                                                        $dateCondition = "{column} >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR)";
                                                        break;
                                                    default:
                                                        $dateCondition = "{column} >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
                                                        break;
                                                }

                                                $sql = "SELECT SUM(total_profit) AS grand_total
        FROM (
            SELECT SUM((s.price - j.buy_price) * s.quantity) AS total_profit
            FROM sales s
            INNER JOIN jeans j ON s.jeans_id = j.id
            WHERE s.status = 'active' AND " . str_replace('{column}', 's.sales_date', $dateCondition) . "
            
            UNION ALL
            
            SELECT SUM((ss.price - sh.buy_price) * ss.quantity)
            FROM shoes_sales ss
            INNER JOIN shoes sh ON ss.shoes_id = sh.id
            WHERE ss.status = 'active' AND " . str_replace('{column}', 'ss.sales_date', $dateCondition) . "
            
            UNION ALL
            
            SELECT SUM((acs.price - a.buy_price) * acs.quantity)
            FROM accessory_sales acs
            INNER JOIN accessory a ON acs.accessory_id = a.id
            WHERE acs.status = 'active' AND " . str_replace('{column}', 'acs.sales_date', $dateCondition) . "
            
            UNION ALL
            
            SELECT SUM((cs.price - c.buy_price) * cs.quantity)
            FROM complete_sales cs
            INNER JOIN complete c ON cs.complete_id = c.id
            WHERE cs.status = 'active' AND " . str_replace('{column}', 'cs.sales_date', $dateCondition) . "
        ) AS combined_profits";


                                                $result = mysqli_query($con, $sql);
                                                $row = mysqli_fetch_array($result);
                                                echo number_format($row['grand_total'] ?? 0, 2);
                                                ?>
                                            </h3>

                                            <h3 class="text-gray-500 mb-1 truncate dark:text-gray-400">
                                                <?php
                                                $periodLabels = [
                                                    'today' => 'Today',
                                                    'yesterday' => 'Yesterday',
                                                    '7' => '7 Days',
                                                    '30' => '30 Days',
                                                    '60' => '60 Days',
                                                    '180' => '6 Months',
                                                    '365' => '1 Year'
                                                ];
                                                $displayPeriod = $periodLabels[$selectedPeriod] ?? '30 Days';
                                                ?>
                                                Total Profit (<?= $displayPeriod ?>)
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                            </div>



                            <div class="card">
                                <div class="p-6">
                                    <div class="flex justify-between items-center">
                                        <div
                                            class="w-20 h-20 mx-2 rounded-full inline-flex items-center justify-center bg-yellow-100">
                                            <i class="mgc_currency_dollar_fill text-5xl text-yellow-500"></i>
                                        </div>
                                        <div class="text-right">
                                            <!-- Date Filter Dropdown -->
                                            <div>
                                                <form method="get">
                                                    <select name="earnings_period" onchange="this.form.submit()"
                                                        class="px-2 py-1 border rounded-md text-sm focus:ring-2 focus:ring-blue-500">
                                                        <option value="today" <?= ($_GET['earnings_period'] ?? '30') == 'today' ? 'selected' : '' ?>>Today</option>
                                                        <option value="yesterday" <?= ($_GET['earnings_period'] ?? '30') == 'yesterday' ? 'selected' : '' ?>>Yesterday</option>
                                                        <option value="7" <?= ($_GET['earnings_period'] ?? '30') == '7' ? 'selected' : '' ?>>7 Days</option>
                                                        <option value="30" <?= ($_GET['earnings_period'] ?? '30') == '30' ? 'selected' : '' ?>>30 Days</option>
                                                        <option value="60" <?= ($_GET['earnings_period'] ?? '30') == '60' ? 'selected' : '' ?>>60 Days</option>
                                                        <option value="180" <?= ($_GET['earnings_period'] ?? '30') == '180' ? 'selected' : '' ?>>6 Months</option>
                                                        <option value="365" <?= ($_GET['earnings_period'] ?? '30') == '365' ? 'selected' : '' ?>>1 Year</option>
                                                    </select>
                                                </form>
                                            </div>

                                            <h4 class="text-gray-700 mt-1 text-xl font-bold mb-5 dark:text-gray-300">
                                                <?php
                                                $selectedEarningsPeriod = $_GET['earnings_period'] ?? '30';

                                                // Define date conditions
                                                switch ($selectedEarningsPeriod) {
                                                    case 'today':
                                                        $dateCondition = "{column} >= CURDATE()";
                                                        break;
                                                    case 'yesterday':
                                                        $dateCondition = "{column} >= CURDATE() - INTERVAL 1 DAY AND {column} < CURDATE()";
                                                        break;
                                                    case '7':
                                                        $dateCondition = "{column} >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
                                                        break;
                                                    case '30':
                                                        $dateCondition = "{column} >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
                                                        break;
                                                    case '60':
                                                        $dateCondition = "{column} >= DATE_SUB(CURDATE(), INTERVAL 60 DAY)";
                                                        break;
                                                    case '180':
                                                        $dateCondition = "{column} >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)";
                                                        break;
                                                    case '365':
                                                        $dateCondition = "{column} >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR)";
                                                        break;
                                                    default:
                                                        $dateCondition = "{column} >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
                                                        break;
                                                }

                                                $sql = "SELECT SUM(price) AS total_price 
                            FROM sales 
                            WHERE status IN ('active', 'Exchange Sells')
                            AND " . str_replace('{column}', 'sales_date', $dateCondition) . "
                            UNION ALL
                            SELECT SUM(price) AS total_price
                            FROM shoes_sales
                            WHERE status IN ('active', 'Exchange Sellss')
                            AND " . str_replace('{column}', 'sales_date', $dateCondition) . "
                            UNION ALL
                            SELECT SUM(price) AS total_price
                            FROM accessory_sales
                            WHERE status IN ('active', 'Exchange Sellss')
                            AND " . str_replace('{column}', 'sales_date', $dateCondition) . "
                            UNION ALL
                            SELECT SUM(price) AS total_price
                            FROM complete_sales
                            WHERE status IN ('active', 'Exchange Sellss')
                            AND " . str_replace('{column}', 'sales_date', $dateCondition);

                                                $result = mysqli_query($con, $sql);
                                                $totalPrice = 0;
                                                if ($result) {
                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                        $totalPrice += $row['total_price'] ?? 0;
                                                    }
                                                }
                                                echo number_format($totalPrice, 2);
                                                ?>
                                            </h4>
                                            <p class="text-gray-500 mb-1 truncate dark:text-gray-400">
                                                <?php
                                                $periodLabels = [
                                                    'today' => 'Today',
                                                    'yesterday' => 'Yesterday',
                                                    '7' => '7 Days',
                                                    '30' => '30 Days',
                                                    '60' => '60 Days',
                                                    '180' => '6 Months',
                                                    '365' => '1 Year'
                                                ];
                                                $displayPeriod = $periodLabels[$selectedEarningsPeriod] ?? '30 Days';
                                                ?>
                                                Total Earnings (<?= $displayPeriod ?>)
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>









                            <div class="card">
                                <div class="p-6">
                                    <div class="flex justify-between items-center">
                                        <div
                                            class="w-20 h-20 rounded-full inline-flex items-center justify-center bg-red-100">
                                            <i class="mgc_warning_line text-4xl text-red-500"></i>
                                        </div>
                                        <div class="text-right">
                                            <!-- Date Filter Dropdown -->
                                            <div>
                                                <form method="get">
                                                    <select name="quantity_period" onchange="this.form.submit()"
                                                        class="px-2 py-1 border rounded-md text-sm focus:ring-2 focus:ring-blue-500">
                                                        <option value="today" <?= ($_GET['quantity_period'] ?? '30') == 'today' ? 'selected' : '' ?>>Today</option>
                                                        <option value="yesterday" <?= ($_GET['quantity_period'] ?? '30') == 'yesterday' ? 'selected' : '' ?>>Yesterday</option>
                                                        <option value="7" <?= ($_GET['quantity_period'] ?? '30') == '7' ? 'selected' : '' ?>>7 Days</option>
                                                        <option value="30" <?= ($_GET['quantity_period'] ?? '30') == '30' ? 'selected' : '' ?>>30 Days</option>
                                                        <option value="60" <?= ($_GET['quantity_period'] ?? '30') == '60' ? 'selected' : '' ?>>60 Days</option>
                                                        <option value="180" <?= ($_GET['quantity_period'] ?? '30') == '180' ? 'selected' : '' ?>>6 Months</option>
                                                        <option value="365" <?= ($_GET['quantity_period'] ?? '30') == '365' ? 'selected' : '' ?>>1 Year</option>
                                                    </select>
                                                </form>
                                            </div>

                                            <?php
                                            $selectedQuantityPeriod = $_GET['quantity_period'] ?? '30';

                                            // Define date conditions
                                            switch ($selectedQuantityPeriod) {
                                                case 'today':
                                                    $dateCondition = "{column} >= CURDATE()";
                                                    break;
                                                case 'yesterday':
                                                    $dateCondition = "{column} >= CURDATE() - INTERVAL 1 DAY AND {column} < CURDATE()";
                                                    break;
                                                case '7':
                                                    $dateCondition = "{column} >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
                                                    break;
                                                case '30':
                                                    $dateCondition = "{column} >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
                                                    break;
                                                case '60':
                                                    $dateCondition = "{column} >= DATE_SUB(CURDATE(), INTERVAL 60 DAY)";
                                                    break;
                                                case '180':
                                                    $dateCondition = "{column} >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)";
                                                    break;
                                                case '365':
                                                    $dateCondition = "{column} >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR)";
                                                    break;
                                                default:
                                                    $dateCondition = "{column} >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
                                                    break;
                                            }

                                            $query = "SELECT SUM(quantity) AS total_quantity 
          FROM (
              SELECT quantity 
              FROM sales
              WHERE status = 'active' 
              AND " . str_replace('{column}', 'sales_date', $dateCondition) . "
              
              UNION ALL
              
              SELECT quantity 
              FROM shoes_sales
              WHERE status = 'active' 
              AND " . str_replace('{column}', 'sales_date', $dateCondition) . "
              
              UNION ALL
              
              SELECT quantity 
              FROM accessory_sales
              WHERE status = 'active' 
              AND " . str_replace('{column}', 'sales_date', $dateCondition) . "
              
              UNION ALL
              
              SELECT quantity 
              FROM complete_sales
              WHERE status = 'active' 
              AND " . str_replace('{column}', 'sales_date', $dateCondition) . "
              
              UNION ALL
              
              SELECT quantity 
              FROM top_sales
              WHERE status = 'active' 
              AND " . str_replace('{column}', 'sales_date', $dateCondition) . "
          ) AS combined_sales";


                                            $result = mysqli_query($con, $query);
                                            $row = mysqli_fetch_assoc($result);
                                            $totalQuantity = $row['total_quantity'] ?? 0;
                                            ?>

                                            <h3 class="text-gray-700 mt-1 text-2xl font-bold mb-5 dark:text-gray-300">
                                                <?= number_format($totalQuantity) ?>
                                            </h3>
                                            <p class="text-gray-500 mb-1 truncate dark:text-gray-400">
                                                <?php
                                                $periodLabels = [
                                                    'today' => 'Today',
                                                    'yesterday' => 'Yesterday',
                                                    '7' => '7 Days',
                                                    '30' => '30 Days',
                                                    '60' => '60 Days',
                                                    '180' => '6 Months',
                                                    '365' => '1 Year'
                                                ];
                                                $displayPeriod = $periodLabels[$selectedQuantityPeriod] ?? '30 Days';
                                                ?>
                                                Total Quantity Sold (<?= $displayPeriod ?>)
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>










                            <div class="card">
                                <div class="p-6">
                                    <div class="flex justify-between items-center">
                                        <div
                                            class="w-20 h-20 rounded-full inline-flex items-center justify-center bg-green-100">
                                            <i class="mgc_check_circle_line text-4xl text-green-500"></i>
                                        </div>
                                        <div class="text-right">
                                            <!-- Date Filter Dropdown -->
                                            <div>
                                                <form method="get">
                                                    <select name="sales_period" onchange="this.form.submit()"
                                                        class="px-2 py-1 border rounded-md text-sm focus:ring-2 focus:ring-blue-500">
                                                        <option value="today" <?= ($_GET['sales_period'] ?? '30') == 'today' ? 'selected' : '' ?>>Today</option>
                                                        <option value="yesterday" <?= ($_GET['sales_period'] ?? '30') == 'yesterday' ? 'selected' : '' ?>>Yesterday</option>
                                                        <option value="7" <?= ($_GET['sales_period'] ?? '30') == '7' ? 'selected' : '' ?>>7 Days</option>
                                                        <option value="30" <?= ($_GET['sales_period'] ?? '30') == '30' ? 'selected' : '' ?>>30 Days</option>
                                                        <option value="60" <?= ($_GET['sales_period'] ?? '30') == '60' ? 'selected' : '' ?>>60 Days</option>
                                                        <option value="180" <?= ($_GET['sales_period'] ?? '30') == '180' ? 'selected' : '' ?>>6 Months</option>
                                                        <option value="365" <?= ($_GET['sales_period'] ?? '30') == '365' ? 'selected' : '' ?>>1 Year</option>
                                                    </select>
                                                </form>
                                            </div>

                                            <?php
                                            $selectedPeriod = $_GET['sales_period'] ?? '30';

                                            // Define date conditions
                                            switch ($selectedPeriod) {
                                                case 'today':
                                                    $dateCondition = "{column} >= CURDATE()";
                                                    break;
                                                case 'yesterday':
                                                    $dateCondition = "{column} >= CURDATE() - INTERVAL 1 DAY AND {column} < CURDATE()";
                                                    break;
                                                case '7':
                                                    $dateCondition = "{column} >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
                                                    break;
                                                case '30':
                                                    $dateCondition = "{column} >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
                                                    break;
                                                case '60':
                                                    $dateCondition = "{column} >= DATE_SUB(CURDATE(), INTERVAL 60 DAY)";
                                                    break;
                                                case '180':
                                                    $dateCondition = "{column} >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)";
                                                    break;
                                                case '365':
                                                    $dateCondition = "{column} >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR)";
                                                    break;
                                                default:
                                                    $dateCondition = "{column} >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
                                                    break;
                                            }

                                            $query = "SELECT SUM(quantity) AS total_sales 
          FROM (
              SELECT quantity 
              FROM sales 
              WHERE status = 'active' 
              AND " . str_replace('{column}', 'sales_date', $dateCondition) . "
              
              UNION ALL
              
              SELECT quantity 
              FROM shoes_sales 
              WHERE status = 'active' 
              AND " . str_replace('{column}', 'sales_date', $dateCondition) . "
              
              UNION ALL
              
              SELECT quantity 
              FROM accessory_sales 
              WHERE status = 'active' 
              AND " . str_replace('{column}', 'sales_date', $dateCondition) . "
              
              UNION ALL
              
              SELECT quantity 
              FROM complete_sales 
              WHERE status = 'active' 
              AND " . str_replace('{column}', 'sales_date', $dateCondition) . "
              
              UNION ALL
              
              SELECT quantity 
              FROM top_sales 
              WHERE status = 'active' 
              AND " . str_replace('{column}', 'sales_date', $dateCondition) . "
          ) AS combined_sales";


                                            $result = mysqli_query($con, $query);
                                            $row = mysqli_fetch_assoc($result);
                                            $totalSales = $row['total_sales'] ?? 0;
                                            ?>

                                            <h3 class="text-gray-700 mt-1 text-2xl font-bold mb-5 dark:text-gray-300">
                                                <?= number_format($totalSales) ?>
                                            </h3>
                                            <p class="text-gray-500 mb-1 truncate dark:text-gray-400">
                                                <?php
                                                $periodLabels = [
                                                    'today' => 'Today',
                                                    'yesterday' => 'Yesterday',
                                                    '7' => 'Last 7 Days',
                                                    '30' => 'Last 30 Days',
                                                    '60' => 'Last 60 Days',
                                                    '180' => 'Last 6 Months',
                                                    '365' => 'Last Year'
                                                ];
                                                $displayPeriod = $periodLabels[$selectedPeriod] ?? 'Last 30 Days';
                                                ?>
                                                Total Sales (<?= $displayPeriod ?>)
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>







                        <div class="grid lg:grid-cols-2 gap-6">
                            <div class="col-span-1">
                                <div class="card">
                                    <div class="p-6">
                                        <div class="flex flex-row  justify-between">
                                            <h4 class="card-title">Total Sales</h4>



                                        </div>


                                        <?php
                                        // ... [Keep the database connection and function definitions same] ...

                                        // Fetch data from all sales tables (remove month filter from SQL)
                                        function fetchCategorySales($conn, $salesTable, $productTable, $foreignKey)
                                        {
                                            $data = [];
                                            $sql = "SELECT 
            DATE_FORMAT(s.sales_date, '%Y-%m') AS month,
            SUM(s.quantity) AS total_quantity,
            SUM(s.price * s.quantity) AS total_sales,
            SUM((s.price - p.buy_price) * s.quantity) AS total_profit
        FROM $salesTable s
        INNER JOIN $productTable p ON s.$foreignKey = p.id
        WHERE s.status = 'active'
        GROUP BY DATE_FORMAT(s.sales_date, '%Y-%m')";


                                            $result = $conn->query($sql);
                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    $data[] = $row;
                                                }
                                            }
                                            return $data;
                                        }

                                        // Fetch and merge data from all sales tables
                                        $allSales = array_merge(
                                            fetchCategorySales($con, 'sales', 'jeans', 'jeans_id'),
                                            fetchCategorySales($con, 'shoes_sales', 'shoes', 'shoes_id'),
                                            fetchCategorySales($con, 'accessory_sales', 'accessory', 'accessory_id'),
                                            fetchCategorySales($con, 'complete_sales', 'complete', 'complete_id')
                                        );

                                        // Process and aggregate data
                                        $monthlyData = [];
                                        foreach ($allSales as $sale) {
                                            $month = $sale['month'];
                                            if (!isset($monthlyData[$month])) {
                                                $monthlyData[$month] = [
                                                    'total_quantity' => 0,
                                                    'total_sales' => 0,
                                                    'total_profit' => 0
                                                ];
                                            }
                                            $monthlyData[$month]['total_quantity'] += $sale['total_quantity'];
                                            $monthlyData[$month]['total_sales'] += $sale['total_sales'];
                                            $monthlyData[$month]['total_profit'] += $sale['total_profit'];
                                        }

                                        // Get selected year
                                        $selectedYear = $_GET['year'] ?? date('Y');

                                        // Create complete year structure
                                        $yearData = [];
                                        for ($m = 1; $m <= 12; $m++) {
                                            $month = str_pad($m, 2, '0', STR_PAD_LEFT);
                                            $yearData["$selectedYear-$month"] = [
                                                'total_quantity' => 0,
                                                'total_sales' => 0,
                                                'total_profit' => 0
                                            ];
                                        }

                                        // Merge actual data into year structure
                                        foreach ($monthlyData as $month => $data) {
                                            if (substr($month, 0, 4) == $selectedYear) {
                                                $yearData[$month] = $data;
                                            }
                                        }
                                        ?>

                                        <div class="max-w-7xl mx-auto px-0 sm:px-0 lg:px-0 ">
                                            <div class="bg-white rounded-lg shadow-sm p-6">
                                                <!-- Header and Year Filter -->
                                                <div
                                                    class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">


                                                    <form method="get" class="flex gap-3">
                                                        <select name="year"
                                                            class="px-4 py-2 border rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                            <?php for ($y = 2020; $y <= date('Y'); $y++): ?>
                                                                <option value="<?= $y ?>" <?= $y == $selectedYear ? 'selected' : '' ?>><?= $y ?></option>
                                                            <?php endfor; ?>
                                                        </select>
                                                        <button type="submit"
                                                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                                                            Filter Year
                                                        </button>
                                                    </form>
                                                </div>

                                                <!-- Sales Table -->
                                                <div class="overflow-x-auto rounded-lg border">
                                                    <table class="min-w-full divide-y divide-gray-200">
                                                        <thead class="bg-blue-600">
                                                            <tr>
                                                                <th
                                                                    class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                                                                    Month</th>
                                                                <th
                                                                    class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                                                                    Items Sold</th>
                                                                <th
                                                                    class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                                                                    Avg. Sale Price</th>
                                                                <th
                                                                    class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                                                                    Avg. Profit</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="bg-white divide-y divide-gray-200">
                                                            <?php foreach ($yearData as $monthKey => $data):
                                                                $date = DateTime::createFromFormat('Y-m', $monthKey);
                                                                $monthName = $date->format('F Y');

                                                                $avgSale = $data['total_quantity'] > 0
                                                                    ? $data['total_sales'] / $data['total_quantity']
                                                                    : 0;

                                                                $avgProfit = $data['total_quantity'] > 0
                                                                    ? $data['total_profit'] / $data['total_quantity']
                                                                    : 0;
                                                            ?>
                                                                <tr class="hover:bg-gray-50 transition-colors">
                                                                    <td
                                                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                                        <?= $date->format('F') ?>
                                                                    </td>
                                                                    <td
                                                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                                        <?= number_format($data['total_quantity']) ?>
                                                                    </td>
                                                                    <td
                                                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                                        <span class="font-medium text-green-600">
                                                                            <?= number_format($avgSale, 2) ?>
                                                                        </span> Birr
                                                                    </td>
                                                                    <td
                                                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                                        <span class="font-medium text-green-600">
                                                                            <?= number_format($avgProfit, 2) ?>
                                                                        </span> Birr
                                                                    </td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>


























                                        <div class="flex justify-center">
                                            <div class="w-1/2 text-center">
                                                <h5></h5>
                                                <p class="fw-semibold text-muted">
                                                    <i class="mgc_round_fill text-primary"></i> Sales
                                                </p>
                                            </div>
                                            <div class="w-1/2 text-center">
                                                <h5></h5>
                                                <p class="fw-semibold text-muted">
                                                    <i class="mgc_round_fill text-success"></i> Purchase
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="lg:col-span-1">
                                <div class="card">
                                    <div class="p-6">
                                        <!-- Include ApexCharts -->
                                        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

                                        <div class="flex flex-row justify-between items-center">
                                            <h4 class="card-title">Daily Sales Quantity</h4>
                                            <div class="flex gap-2">
                                                <!-- Month Dropdown -->
                                                <select id="monthSelect" class="form-select dark:form-select-dark">
                                                    <?php
                                                    $months = [
                                                        1 => 'January',
                                                        2 => 'February',
                                                        3 => 'March',
                                                        4 => 'April',
                                                        5 => 'May',
                                                        6 => 'June',
                                                        7 => 'July',
                                                        8 => 'August',
                                                        9 => 'September',
                                                        10 => 'October',
                                                        11 => 'November',
                                                        12 => 'December'
                                                    ];
                                                    $currentMonth = date('n');
                                                    foreach ($months as $num => $name) {
                                                        $selected = ($num == $currentMonth) ? 'selected' : '';
                                                        echo "<option value='$num' $selected>$name</option>";
                                                    }
                                                    ?>
                                                </select>

                                                <!-- Year Dropdown -->
                                                <select id="yearSelect" class="form-select dark:form-select-dark">
                                                    <?php
                                                    $currentYear = date('Y');
                                                    for ($year = $currentYear; $year >= 2020; $year--) {
                                                        $selected = ($year == $currentYear) ? 'selected' : '';
                                                        echo "<option value='$year' $selected>$year</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div dir="ltr" class="mt-2">
                                            <div id="dailySalesChart" class="apex-charts" data-colors="#3073F1"></div>
                                        </div>

                                        <script>
                                            let chart;

                                            // Initial configuration
                                            const initChart = () => {
                                                return new ApexCharts(document.querySelector("#dailySalesChart"), {
                                                    chart: {
                                                        height: 350,
                                                        type: 'bar',
                                                        toolbar: {
                                                            show: false
                                                        },
                                                        animations: {
                                                            enabled: true
                                                        }
                                                    },
                                                    series: [{
                                                        name: 'Daily Quantity Sold',
                                                        data: []
                                                    }],
                                                    noData: {
                                                        text: 'Loading data...',
                                                        align: 'center',
                                                        verticalAlign: 'middle',
                                                        style: {
                                                            color: '#3073F1',
                                                            fontSize: '14px'
                                                        }
                                                    },
                                                    xaxis: {
                                                        categories: [],
                                                        title: {
                                                            text: 'Days of Month'
                                                        }
                                                    },
                                                    yaxis: {
                                                        title: {
                                                            text: 'Quantity Sold'
                                                        },
                                                        labels: {
                                                            formatter: (val) => Math.round(val),
                                                            style: {
                                                                fontSize: '12px'
                                                            }
                                                        }
                                                    },
                                                    colors: ['#3073F1'],
                                                    dataLabels: {
                                                        enabled: false
                                                    },
                                                    stroke: {
                                                        curve: 'smooth',
                                                        width: 2
                                                    }
                                                });
                                            };

                                            // Initialize chart after DOM loads
                                            document.addEventListener('DOMContentLoaded', async () => {
                                                chart = initChart();
                                                await chart.render();
                                                await fetchData();
                                            });

                                            // Event listeners
                                            document.getElementById('monthSelect').addEventListener('change', fetchData);
                                            document.getElementById('yearSelect').addEventListener('change', fetchData);

                                            async function fetchData() {
                                                try {
                                                    const month = document.getElementById('monthSelect').value;
                                                    const year = document.getElementById('yearSelect').value;

                                                    const response = await fetch(`get_daily_sales.php?month=${month}&year=${year}&t=${Date.now()}`);

                                                    if (!response.ok) {
                                                        throw new Error(`HTTP error! Status: ${response.status}`);
                                                    }

                                                    const data = await response.json();

                                                    if (!data.categories || !data.series) {
                                                        throw new Error('Invalid data format from server');
                                                    }

                                                    // Update chart
                                                    chart.updateOptions({
                                                        xaxis: {
                                                            categories: data.categories
                                                        }
                                                    }, false, true);

                                                    chart.updateSeries([{
                                                        name: 'Daily Quantity Sold',
                                                        data: data.series
                                                    }], true);

                                                } catch (error) {
                                                    chart.updateOptions({
                                                        noData: {
                                                            text: error.message
                                                        }
                                                    });
                                                }
                                            }
                                        </script>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>






                    <div class="col-span-1">


                        <div class="card mb-6">
                            <div class="px-6 py-5 flex justify-between items-center">
                                <h4 class="header-title">Stock Summary</h4>
                                <div>
                                    <button class="text-gray-600 dark:text-gray-400" data-fc-type="dropdown"
                                        data-fc-placement="left-start" type="button">
                                        <i class="mgc_more_1_fill text-xl"></i>
                                    </button>

                                    <div
                                        class="hidden fc-dropdown fc-dropdown-open:opacity-100 opacity-0 w-36 z-50 mt-2 transition-[margin,opacity] duration-300 bg-white dark:bg-gray-800 shadow-lg border border-gray-200 dark:border-gray-700 rounded-lg p-2">
                                        <a href="pages/sale/products_log.php"
                                            class="flex items-center gap-1.5 py-1.5 px-3.5 rounded text-sm transition-all duration-300 bg-transparent text-gray-800 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-200"
                                            href="javascript:void(0)">
                                            <i class="mgc_info_circle_line"></i> Info
                                        </a>

                                    </div>
                                </div>
                                <?php
                                // Query to count records from both stock and office_stock tables
                                $query = "
    SELECT COALESCE(SUM(quantity), 0) AS total_quantity 
FROM products;
";

                                // Execute the query
                                $result = mysqli_query($con, $query);

                                // Fetch the result
                                $row = mysqli_fetch_assoc($result);

                                // Get the total danger zone count
                                $total_danger = $row['total_quantity'];

                                ?>
                            </div>
                            <div class="px-4 py-2 bg-warning/20 text-warning" role="alert">
                                <i class="mgc_folder_star_line me-1 text-lg align-baseline"></i> <b>
                                    <?= $total_danger ?></b> Total Added Products

                            </div>

                            <div class="p-3" data-simplebar style="max-height: 304px;">

                                <?php
                                // Define the query to include both stock and office_stock tables
                                $query = "Select * from products order by created_at desc";

                                // Execute the query
                                $result = mysqli_query($con, $query);

                                // Check if there are any records
                                if ($result->num_rows > 0) {
                                    $int = 1; // Initialize counter or any other variable if needed

                                    // Loop through the results
                                    while ($row = $result->fetch_assoc()) {
                                        $source = $row['source_table'];
                                        $stock = $row['product_name'];
                                        $qty = $row['quantity'];
                                        $dangerzone = 1;

                                ?>

                                        <div
                                            class="flex items-center border border-gray-200 dark:border-gray-700 rounded px-3 py-2">
                                            <div class="flex-shrink-0 me-2">
                                                <div
                                                    class="w-12 h-12 flex justify-center items-center rounded-full text-warning bg-warning/25">
                                                    <i class="mgc_tag_fill text-xl"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow">
                                                <h5 class="font-semibold mb-1"><?php echo $stock; ?></h5>
                                                <p class="text-gray-400">Quantity: <?php echo $qty; ?></p>
                                                <p class="text-gray-400">Catagory: <?php echo ucfirst($source); ?></p>
                                            </div>
                                            <div>
                                                <a href="pages/sale/products_log.php"><button class="text-gray-400"
                                                        data-fc-type="tooltip" data-fc-placement="top">
                                                        <i class="mgc_information_line text-xl"></i>
                                                    </button>
                                                </a>
                                                <div class="bg-slate-700 hidden px-2 py-1 rounded transition-all text-white opacity-0 z-50"
                                                    role="tooltip">

                                                    Info <div class="bg-slate-700 w-2.5 h-2.5 rotate-45 -z-10 rounded-[1px]"
                                                        data-fc-arrow> </div>

                                                </div>
                                            </div>
                                        </div>
                                <?php $int++;
                                    }
                                } ?>

                            </div>



                        </div>














                        <div class="card p-6" data-simplebar style="max-height: 304px;">
    <div class="flex flex-row justify-between">
        <h4 class="card-title">Store Activity Summary</h4>
        <div class="card-title">
            <!-- Date Filter Dropdown -->
            <form method="get">
                <select name="activity_period" onchange="this.form.submit()"
                    class="px-2 py-1 border rounded-md text-sm focus:ring-2 focus:ring-blue-500">
                    <option value="today" <?= ($_GET['activity_period'] ?? '30') == 'today' ? 'selected' : '' ?>>Today</option>
                    <option value="yesterday" <?= ($_GET['activity_period'] ?? '30') == 'yesterday' ? 'selected' : '' ?>>Yesterday</option>
                    <option value="7" <?= ($_GET['activity_period'] ?? '30') == '7' ? 'selected' : '' ?>>7 Days</option>
                    <option value="30" <?= ($_GET['activity_period'] ?? '30') == '30' ? 'selected' : '' ?>>30 Days</option>
                    <option value="60" <?= ($_GET['activity_period'] ?? '30') == '60' ? 'selected' : '' ?>>60 Days</option>
                    <option value="180" <?= ($_GET['activity_period'] ?? '30') == '180' ? 'selected' : '' ?>>6 Months</option>
                    <option value="365" <?= ($_GET['activity_period'] ?? '30') == '365' ? 'selected' : '' ?>>1 Year</option>
                </select>
            </form>
        </div>
    </div>

    <?php
    $selectedPeriod = $_GET['activity_period'] ?? '30';
    
    // Define date conditions
    switch ($selectedPeriod) {
        case 'today':
            $dateCondition = "sales_date >= CURDATE()";
            break;
        case 'yesterday':
            $dateCondition = "sales_date >= CURDATE() - INTERVAL 1 DAY AND sales_date < CURDATE()";
            break;
        case '7':
            $dateCondition = "sales_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
            break;
        case '30':
            $dateCondition = "sales_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
            break;
        case '60':
            $dateCondition = "sales_date >= DATE_SUB(CURDATE(), INTERVAL 60 DAY)";
            break;
        case '180':
            $dateCondition = "sales_date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)";
            break;
        case '365':
            $dateCondition = "sales_date >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR)";
            break;
        default:
            $dateCondition = "sales_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
            break;
    }

    // Count different methods and statuses across all sales tables with date filter
    $queries = [
        'shop' => "SELECT COUNT(*) AS count FROM (
            SELECT method FROM sales WHERE method = 'shop' AND $dateCondition
            UNION ALL SELECT method FROM shoes_sales WHERE method = 'shop' AND $dateCondition
            UNION ALL SELECT method FROM accessory_sales WHERE method = 'shop' AND $dateCondition
            UNION ALL SELECT method FROM complete_sales WHERE method = 'shop' AND $dateCondition
            UNION ALL SELECT method FROM top_sales WHERE method = 'shop' AND $dateCondition
        ) AS combined",

        'delivery' => "SELECT COUNT(*) AS count FROM (
            SELECT method FROM sales WHERE method = 'delivery' AND $dateCondition
            UNION ALL SELECT method FROM shoes_sales WHERE method = 'delivery' AND $dateCondition
            UNION ALL SELECT method FROM accessory_sales WHERE method = 'delivery' AND $dateCondition
            UNION ALL SELECT method FROM complete_sales WHERE method = 'delivery' AND $dateCondition
            UNION ALL SELECT method FROM top_sales WHERE method = 'delivery' AND $dateCondition
        ) AS combined",

        'exchange' => "SELECT COUNT(*) AS count FROM (
            SELECT status FROM sales WHERE status = 'exchange' AND $dateCondition
            UNION ALL SELECT status FROM shoes_sales WHERE status = 'exchange' AND $dateCondition
            UNION ALL SELECT status FROM accessory_sales WHERE status = 'exchange' AND $dateCondition
            UNION ALL SELECT status FROM complete_sales WHERE status = 'exchange' AND $dateCondition
            UNION ALL SELECT status FROM top_sales WHERE status = 'exchange' AND $dateCondition
        ) AS combined",

        'refund' => "SELECT COUNT(*) AS count FROM (
            SELECT status FROM sales WHERE status = 'refund' AND $dateCondition
            UNION ALL SELECT status FROM shoes_sales WHERE status = 'refund' AND $dateCondition
            UNION ALL SELECT status FROM accessory_sales WHERE status = 'refund' AND $dateCondition
            UNION ALL SELECT status FROM complete_sales WHERE status = 'refund' AND $dateCondition
            UNION ALL SELECT status FROM top_sales WHERE status = 'refund' AND $dateCondition
        ) AS combined"
    ];

    $counts = [];
    foreach ($queries as $key => $sql) {
        $result = mysqli_query($con, $sql);
        $row = mysqli_fetch_assoc($result);
        $counts[$key] = $row['count'] ?? 0;
    }
    ?>

    <div class="grid grid-cols-2 gap-4 mt-4">
        <!-- Shop Count -->
        <div class="flex items-center p-3 bg-blue-50 rounded-lg">
            <div class="flex-shrink-0 w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                <i class="fas fa-store fa-lg text-blue-600"></i>
            </div>
            <div class="ml-3">
                <h5 class="text-sm font-semibold text-blue-600">In-Store Sales</h5>
                <p class="text-2xl font-bold"><?= number_format($counts['shop']) ?></p>
            </div>
        </div>

        <!-- Delivery Count -->
        <div class="flex items-center p-3 bg-green-50 rounded-lg">
            <div class="flex-shrink-0 w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                <i class="fas fa-truck fa-lg text-green-600"></i>
            </div>
            <div class="ml-3">
                <h5 class="text-sm font-semibold text-green-600 px-2">Deliveries</h5>
                <p class="text-2xl font-bold px-2"><?= number_format($counts['delivery']) ?></p>
            </div>
        </div>

        <!-- Exchanges -->
        <div class="flex items-center p-3 bg-orange-50 rounded-lg">
            <div class="flex-shrink-0 w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                <i class="fas fa-exchange-alt fa-lg text-orange-600"></i>
            </div>
            <div class="ml-3">
                <h5 class="text-sm font-semibold text-orange-600 px-2">Exchanges</h5>
                <p class="text-2xl font-bold px-2"><?= number_format($counts['exchange']) ?></p>
            </div>
        </div>

        <!-- Refunds -->
        <div class="flex items-center p-3 bg-red-50 rounded-lg">
            <div class="flex-shrink-0 w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                <i class="fas fa-undo fa-lg text-red-600"></i>
            </div>
            <div class="ml-3">
                <h5 class="text-sm font-semibold text-red-600 px-2">Refunds</h5>
                <p class="text-2xl font-bold px-4"><?= number_format($counts['refund']) ?></p>
            </div>
        </div>
    </div>
</div>



                    </div>
                </div> <!-- Grid End -->




                <!-- Grid End -->





                <div class="grid 2xl:grid-cols-2 md:grid-cols-2 gap-6">
                    <div class="2xl:col-span-2 md:col-span-2">
                        <div class="card">
                            <div class="p-6">
                                <div class="flex justify-between items-center">
                                    <h4 class="card-title px-10">Bank Transactions Summary</h4>
                                    <form method="get" class="flex gap-2">
                                        <select name="bank_period" onchange="this.form.submit()"
                                            class="px-2 py-1 border rounded-md text-sm focus:ring-2 focus:ring-blue-500">
                                            <option value="30" <?= ($_GET['bank_period'] ?? '30') == '30' ? 'selected' : '' ?>>30 Days</option>
                                            <option value="60" <?= ($_GET['bank_period'] ?? '30') == '60' ? 'selected' : '' ?>>60 Days</option>
                                            <option value="90" <?= ($_GET['bank_period'] ?? '30') == '90' ? 'selected' : '' ?>>90 Days</option>
                                            <option value="180" <?= ($_GET['bank_period'] ?? '30') == '180' ? 'selected' : '' ?>>6 Months</option>
                                        </select>
                                    </form>
                                </div>

                                <div class="grid md:grid-cols-1 items-center gap-4">
                                    <div class="md:order-1 order-2">
                                        <div class="flex flex-col gap-6">
                                            <?php
                                            $period = $_GET['bank_period'] ?? '30';
                                            $interval = match ($period) {
                                                '30' => '30 DAY',
                                                '60' => '60 DAY',
                                                '90' => '90 DAY',
                                                '180' => '6 MONTH',
                                                default => '30 DAY'
                                            };

                                            $sql = "SELECT 
                                    bank_id,
                                    bank_name,
                                    SUM(bank) AS total_bank_amount
                                FROM (
                                    SELECT bank_id, bank_name, bank 
                                    FROM sales 
                                    WHERE sales_date >= DATE_SUB(CURDATE(), INTERVAL $interval)
                                    AND bank_id IS NOT NULL
                                    
                                    UNION ALL
                                    
                                    SELECT bank_id, bank_name, bank 
                                    FROM shoes_sales 
                                    WHERE sales_date >= DATE_SUB(CURDATE(), INTERVAL $interval)
                                    AND bank_id IS NOT NULL
                                    
                                    UNION ALL
                                    
                                    SELECT bank_id, bank_name, bank 
                                    FROM accessory_sales 
                                    WHERE sales_date >= DATE_SUB(CURDATE(), INTERVAL $interval)
                                    AND bank_id IS NOT NULL
                                    
                                    UNION ALL
                                    
                                    SELECT bank_id, bank_name, bank 
                                    FROM complete_sales 
                                    WHERE sales_date >= DATE_SUB(CURDATE(), INTERVAL $interval)
                                    AND bank_id IS NOT NULL
                                    
                                    UNION ALL
                                    
                                    SELECT bank_id, bank_name, bank 
                                    FROM top_sales 
                                    WHERE sales_date >= DATE_SUB(CURDATE(), INTERVAL $interval)
                                    AND bank_id IS NOT NULL
                                ) AS combined_sales
                                GROUP BY bank_id, bank_name
                                ORDER BY total_bank_amount DESC";

                                            $result = mysqli_query($con, $sql);
                                            $bankData = [];
                                            $grandTotal = 0; // Add grand total counter

                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    $amount = (float) $row['total_bank_amount'];
                                                    $grandTotal += $amount;

                                                    $bankData[] = [
                                                        'name' => $row['bank_name'],
                                                        'total' => $amount
                                                    ];
                                            ?>
                                                    <div class="flex items-center">
                                                        <div class="flex-shrink-0">
                                                            <i
                                                                class="mgc_bank_card_line h-10 w-10 flex justify-center items-center rounded-full bg-blue-100 text-blue-500"></i>
                                                        </div>
                                                        <div class="flex-grow ms-3">
                                                            <h5 class="font-semibold mb-1"><?= $row['bank_name'] ?></h5>
                                                            <div class="text-sm text-gray-600">
                                                                <?= number_format($amount, 2) ?> Birr
                                                            </div>
                                                        </div>
                                                    </div>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>

                                    <div class="md:order-2 order-1">
                                        <div id="bank-transactions-chart" class="apex-charts"
                                            data-colors="#3073F1,#0acf97,#ffbc00,#6f42c1,#ff679b"></div>

                                        <script>
                                            var bankNames = <?= json_encode(array_column($bankData, 'name')) ?>;
                                            var bankAmounts = <?= json_encode(array_column($bankData, 'total')) ?>;

                                            var options = {
                                                chart: {
                                                    height: 320,
                                                    type: 'radialBar'
                                                },
                                                colors: ["#3073F1", "#0acf97", "#ffbc00", "#6f42c1", "#ff679b"],
                                                series: bankAmounts,
                                                labels: bankNames,
                                                plotOptions: {
                                                    radialBar: {
                                                        track: {
                                                            margin: 15,
                                                        },
                                                        dataLabels: {
                                                            name: {
                                                                fontSize: '14px'
                                                            },
                                                            value: {
                                                                fontSize: '20px',
                                                                fontWeight: 600,
                                                                formatter: function(val) {
                                                                    // Use parseFloat for accurate decimal handling
                                                                    return 'Birr ' + parseFloat(val).toLocaleString('en-US', {
                                                                        minimumFractionDigits: 2,
                                                                        maximumFractionDigits: 2
                                                                    });
                                                                }
                                                            },
                                                            total: {
                                                                show: true,
                                                                label: 'Total Bank Transactions',
                                                                formatter: function(w) {
                                                                    // Calculate sum using reducer with initial value 0
                                                                    const total = bankAmounts.reduce((acc, curr) => acc + curr, 0);
                                                                    return 'Birr ' + total.toLocaleString('en-US', {
                                                                        minimumFractionDigits: 2,
                                                                        maximumFractionDigits: 2
                                                                    });
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            };

                                            var charts = new ApexCharts(
                                                document.querySelector("#bank-transactions-chart"),
                                                options
                                            );
                                            charts.render();
                                        </script>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-span-1">
                        <div class="card">
                            <div class="card-header">
                                <div class="flex justify-between items-center">
                                    <h4 class="card-title"><span class="font-bold">Top 10 Best Selling </span></h4>
                                    <div>
                                        <button class="text-gray-600 dark:text-gray-400" data-fc-type="dropdown"
                                            data-fc-placement="left-start" type="button">
                                            <i class="mgc_more_2_fill text-xl"></i>
                                        </button>
                                        <div
                                            class="hidden fc-dropdown fc-dropdown-open:opacity-100 opacity-0 w-36 z-50 mt-2 transition-[margin,opacity] duration-300 bg-white dark:bg-gray-800 shadow-lg border border-gray-200 dark:border-gray-700 rounded-lg p-2">
                                            <!-- Date Filter Dropdown -->
                                            <span class="text-xs text-gray-500 px-2">Filter by:</span>
                                            <a href="?period=7"
                                                class="flex items-center gap-1.5 py-1.5 px-3.5 rounded text-sm transition-all duration-300 bg-transparent text-gray-800 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-200">
                                                Last 7 Days
                                            </a>
                                            <a href="?period=30"
                                                class="flex items-center gap-1.5 py-1.5 px-3.5 rounded text-sm transition-all duration-300 bg-transparent text-gray-800 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-200">
                                                Last 30 Days
                                            </a>
                                            <a href="?period=90"
                                                class="flex items-center gap-1.5 py-1.5 px-3.5 rounded text-sm transition-all duration-300 bg-transparent text-gray-800 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-200">
                                                Last 90 Days
                                            </a>
                                            <a href="?period=180"
                                                class="flex items-center gap-1.5 py-1.5 px-3.5 rounded text-sm transition-all duration-300 bg-transparent text-gray-800 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-200">
                                                Last 6 Months
                                            </a>
                                            <a href="?period=365"
                                                class="flex items-center gap-1.5 py-1.5 px-3.5 rounded text-sm transition-all duration-300 bg-transparent text-gray-800 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-200">
                                                Last Year
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="py-6">
                                <div class="px-6" data-simplebar style="max-height: 304px;">
                                    <div class="space-y-3">
                                        <?php
                                        // Get selected period from URL parameter
                                        $selectedPeriod = isset($_GET['period']) ? intval($_GET['period']) : 30;
                                        $dateCondition = "";

                                        if ($selectedPeriod > 0) {
                                            $dateCondition = "WHERE sales_date >= CURDATE() - INTERVAL $selectedPeriod DAY";
                                        }

                                        $sql = "SELECT product_name, SUM(quantity) AS total_sold, MAX(price) AS price
                            FROM (
                                SELECT jeans_name AS product_name, quantity, sales_date, price, status FROM sales
                                $dateCondition
                                UNION ALL
                                SELECT shoes_name AS product_name, quantity, sales_date, price, status FROM shoes_sales
                                $dateCondition
                                UNION ALL
                                SELECT accessory_name AS product_name, quantity, sales_date, price, status FROM accessory_sales
                                $dateCondition
                                UNION ALL
                                SELECT complete_name AS product_name, quantity, sales_date, price, status FROM complete_sales
                                $dateCondition
                                UNION ALL
                                SELECT top_name AS product_name, quantity, sales_date, price, status FROM top_sales
                                $dateCondition
                            ) AS combined_sales
                            WHERE status = 'active'
                            GROUP BY product_name
                            ORDER BY total_sold DESC
                            LIMIT 10";

                                        $result = mysqli_query($con, $sql);
                                        $int = 1;
                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                $client = $row['product_name'];
                                                $job = $row['total_sold'];
                                                $status = $row['price'];
                                        ?>
                                                <div
                                                    class="flex items-center w-full border border-gray-200 dark:border-gray-700 rounded p-2">
                                                    <p class="me-3 text-lg text-gray-300 dark:text-gray-500"><?= $int ?></p>
                                                    <div
                                                        class="w-full overflow-hidden border-l border-gray-200 dark:border-gray-700 ps-3">
                                                        <div class="flex justify-between align-center">
                                                            <h5
                                                                class="font-semibold uppercase truncate text-gray-600 dark:text-gray-400">
                                                                <?php echo $client ?>
                                                            </h5>
                                                            <div class="font-semibold"><?= $status ?></div>
                                                        </div>
                                                        <p class="text-xs truncate"><?php echo $job ?></p>
                                                    </div>
                                                </div>
                                        <?php
                                                $int++;
                                            }
                                        } else {
                                            echo "<p class='text-gray-500 dark:text-gray-400'>No sales found for selected period</p>";
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>






                </div> <!-- Grid End -->



            </main>

            <?php include 'partials/footer.php'; ?>

        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->

    </div>


    <?php include 'partials/customizer.php'; ?>

    <?php include 'partials/footer-scripts.php'; ?>

    <!-- Apexcharts js -->


    <!-- Dashboard Project Page js -->
    <script src="assets/js/pages/dashboard.js"></script>

</body>

</html>
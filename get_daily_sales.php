<?php
$redirect_link = "";
include 'partials/main.php';


include 'include/db.php';

header('Content-Type: application/json');

// Validate and sanitize inputs
$month = isset($_GET['month']) ? intval($_GET['month']) : date('n');
$year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');

// Validate month/year range
if ($month < 1 || $month > 12 || $year < 2020 || $year > date('Y')) {
    echo json_encode(['error' => 'Invalid date parameters']);
    exit;
}

// Calculate days in month
$daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
$dailySales = array_fill(1, $daysInMonth, 0);

// Prepare and execute query
$query = "SELECT DAY(sales_date) AS day, SUM(quantity) AS total 
          FROM (
              SELECT sales_date, quantity FROM sales
              UNION ALL
              SELECT sales_date, quantity FROM shoes_sales
              UNION ALL
              SELECT sales_date, quantity FROM accessory_sales
              UNION ALL
              SELECT sales_date, quantity FROM complete_sales
              UNION ALL
              SELECT sales_date, quantity FROM top_sales
          ) AS combined
          WHERE MONTH(sales_date) = ? AND YEAR(sales_date) = ?
          GROUP BY DAY(sales_date)";

$stmt = $con->prepare($query);
$stmt->bind_param("ii", $month, $year);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $dailySales[$row['day']] = (int)$row['total'];
}

echo json_encode([
    'categories' => range(1, $daysInMonth),
    'series' => array_values($dailySales)
]);

$stmt->close();
$con->close();
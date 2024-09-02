<?php
include_once('../../include/db.php');

function exportData($data, $file_name)
{
    $date = date_create();
    $date = date_format($date, 'Y-m-d H:i:s');

    $filename = $file_name . '_' . $date . '.xls';

    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=\"$filename\"");

    $isPrintHeader = false;
    foreach ($data as $row) {
        if (!$isPrintHeader) {
            echo implode("\t", array_keys($row)) . "\n";
            $isPrintHeader = true;
        }
        echo implode("\t", array_values($row)) . "\n";
    }
    exit();
}

$client = $_GET['client'];
$selected_ids = isset($_POST['update']) ? $_POST['update'] : [];

if (!empty($selected_ids)) {
    // Export selected rows
    $id_list = implode(',', array_map('intval', $selected_ids));
    $sql = "SELECT * FROM payment WHERE payment_id IN ($id_list)";
} else {
    // Export all rows for the client
    $sql = empty($client) ? 'SELECT * FROM payment' : "SELECT * FROM payment WHERE client = '$client'";
}

$result = mysqli_query($con, $sql);
$total_advance = 0;
$total_remained = 0;
$total_total = 0;
$int = 0;
$new_summary = [];

while ($row = mysqli_fetch_assoc($result)) {
    $total_remained += floatval($row['remained']);
    $total_advance += floatval($row['advance']);
    $total_total += floatval($row['total']);
    $new_summary[$int] = array(
        '#' => $int + 1,
        'Client' => $row['client'],
        'Job Number' => $row['job_number'],
        'Date' => $row['date'],
        'Job Description' => $row['job_description'],
        'Size' => $row['size'],
        'Quantity' => $row['quantity'],
        'Unit Price' => $row['unit_price'],
        'Advance' => $row['advance'],
        'Remained' => $row['remained'],
        'Total' => $row['total']
    );
    $int++;
}

$total_arr = array(
    '#' => '',
    'Client' => '',
    'Job Number' => '',
    'Date' => '',
    'Job Description' => '',
    'Size' => '',
    'Quantity' => '',
    'Unit Price' => '',
    'Advance' => $total_advance,
    'Remained' => $total_remained,
    'Total' => $total_total
);
array_push($new_summary, $total_arr);

exportData($new_summary, 'payment_report');
echo "<script>window.location='show_payment.php';</script>";

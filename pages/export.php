<?php
include_once('../include/db.php');

function logMessage($message)
{
    $logFile = 'debug_log.txt';
    $currentDateTime = date('Y-m-d H:i:s');
    $formattedMessage = "[$currentDateTime] $message" . PHP_EOL;
    file_put_contents($logFile, $formattedMessage, FILE_APPEND);
}

function exportData($data, $file_name)
{
    $date = date_create();
    $date = date_format($date, 'Ymd_His'); // Safe date format for file names

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

$type = $_GET['type'] ?? '';
$from_date = $_GET['from_date'] ?? '';
$to_date = $_GET['to_date'] ?? '';

if (empty($type)) {
    logMessage('Error: Type parameter is missing.');
    die('Error: Type parameter is missing.');
}

if (!empty($from_date) && !empty($to_date)) {
    $from_date = mysqli_real_escape_string($con, $from_date);
    $to_date = mysqli_real_escape_string($con, $to_date);
    $sql = "SELECT * FROM `$type` WHERE `date` BETWEEN '$from_date' AND '$to_date'";
} else {
    $sql = "SELECT * FROM `$type`";
}

// Debugging: Log SQL query
logMessage("SQL Query: $sql");

$result = mysqli_query($con, $sql);

if (!$result) {
    logMessage('Error: ' . mysqli_error($con));
    die('Error: ' . mysqli_error($con));
}

$new_summary = [];
$int = 0;
while ($row = mysqli_fetch_assoc($result)) {
    $new_summary[$int] = array_merge(array('#' => $int + 1), $row);
    $int++;
}

if (empty($new_summary)) {
    logMessage('No data found for the given criteria.');
    die('No data found for the given criteria.');
}

exportData($new_summary, "{$type}_report");

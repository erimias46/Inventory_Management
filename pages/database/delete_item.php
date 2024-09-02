<?php
include_once '../../include/db.php';

// Define a function to log messages to a text file
function logToFile($message)
{
    $logFile = 'debug_log.txt'; // Change the path as needed
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($logFile, "$timestamp - $message\n", FILE_APPEND);
}

// Capture and decode incoming data
$data = json_decode(file_get_contents('php://input'), true);

// Extracting individual variables
$id = $data['id'] ?? null;
$type = $data['type'] ?? null;
$item_id = $data['item_id'] ?? null;

// Log received data
logToFile("Received data: ID = $id, Type = $type, Item ID = $item_id");

// Check if required data is present
if (is_null($id) || is_null($type) || is_null($item_id)) {
    logToFile("Error: Missing data - ID = $id, Type = $type, Item ID = $item_id");
    echo json_encode(['success' => false, 'error' => 'Missing data']);
    exit;
}

// Query the database to get the JSON data
$sql = "SELECT data FROM brocher_group WHERE id = '$id' AND type = '$type'";
$result = mysqli_query($con, $sql);
$row = mysqli_fetch_assoc($result);

// Log the fetched row
logToFile("Fetched row: " . json_encode($row));

if (!$row) {
    logToFile("Row not found for ID = $id and Type = $type.");
    echo json_encode(['success' => false, 'error' => 'Item not found']);
    exit;
}

$json_data = json_decode($row['data'], true);

// Log the JSON data before modification
logToFile("JSON data before modification: " . json_encode($json_data));

if (!is_array($json_data)) {
    logToFile("Invalid JSON data: " . $row['data']);
    echo json_encode(['success' => false, 'error' => 'Invalid JSON data']);
    exit;
}

// Attempt to remove the item from the JSON array
if (($key = array_search($item_id, $json_data)) !== false) {
    unset($json_data[$key]);
    logToFile("Item with ID $item_id removed from JSON data.");
} else {
    logToFile("Item with ID $item_id not found in JSON data: " . json_encode($json_data));
    echo json_encode(['success' => false, 'error' => "Item with ID $item_id not found in JSON data"]);
    exit;
}

// Log the JSON data after modification
logToFile("JSON data after modification: " . json_encode($json_data));

// Reindex the array and update the database
$json_data = array_values($json_data);

if (count($json_data) > 0) {
    $new_json_data = json_encode($json_data);
    $update_sql = "UPDATE brocher_group SET data = '$new_json_data' WHERE id = '$id' AND type = '$type'";
    if (mysqli_query($con, $update_sql)) {
        logToFile("Updated row with new JSON data.");
        echo json_encode(['success' => true]);
    } else {
        logToFile("Failed to update data: " . mysqli_error($con));
        echo json_encode(['success' => false, 'error' => 'Failed to update data']);
    }
} else {
    $delete_sql = "DELETE FROM brocher_group WHERE id = '$id' AND type = '$type'";
    if (mysqli_query($con, $delete_sql)) {
        logToFile("Row deleted because JSON array was empty.");
        echo json_encode(['success' => true]);
    } else {
        logToFile("Failed to delete row: " . mysqli_error($con));
        echo json_encode(['success' => false, 'error' => 'Failed to delete row']);
    }
}

mysqli_close($con);
?>
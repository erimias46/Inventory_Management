<?php
// Database configuration for Database A
$servernameA = "localhost";
$usernameA = "habeshyq_erimias";
$passwordA = "+s3wY8jnE@kJ";
$dbnameA = "habeshyq_management";

// Database configuration for Database B
$servernameB = "localhost";
$usernameB = "habeshyq_erimias";
$passwordB = "+s3wY8jnE@kJ";
$dbnameB = "habeshyq_fgsystemnet_elegant";

// Create connections to both databases
$connA = new mysqli($servernameA, $usernameA, $passwordA, $dbnameA);
$connB = new mysqli($servernameB, $usernameB, $passwordB, $dbnameB);

// Check connections
if ($connA->connect_error) {
    die("Connection to Database A failed: " . $connA->connect_error);
}
if ($connB->connect_error) {
    die("Connection to Database B failed: " . $connB->connect_error);
}

// Status ID to status text mapping
function mapStatusId($status_id) {
    switch ($status_id) {
        case 2:
            return 'complete';
        case 3:
            return 'hold';
        case 5:
            return 'progress';
        case 6: 
            return 'delivered';
        default:
            return 'unknown'; // Handle any unexpected status_id values
    }
}

// Check for status changes in status_log
$sqlA = "SELECT project_id, status FROM status_log WHERE processed = 0";
$resultA = $connA->query($sqlA);

if ($resultA->num_rows > 0) {
    while($rowA = $resultA->fetch_assoc()) {
        $id = $rowA["project_id"];
        $status_id = $rowA["status"];
        
        // Map status_id to status text
        $mappedStatus = mapStatusId($status_id);
        
        // Update the status in payment
        $sqlB = "UPDATE payment SET status = ? WHERE project_id = ?";
        $stmtB = $connB->prepare($sqlB);
        $stmtB->bind_param("si", $mappedStatus, $id);
        
        if ($stmtB->execute()) {
            echo "Record updated successfully for ID: $id\n";
        } else {
            echo "Error updating record for ID: $id - " . $stmtB->error . "\n";
        }
        
        // Mark as processed in the log table
        $sqlUpdateA = "UPDATE status_log SET processed = 1 WHERE project_id = ?";
        $stmtUpdateA = $connA->prepare($sqlUpdateA);
        $stmtUpdateA->bind_param("i", $id);
        $stmtUpdateA->execute();
    }
} else {
    echo "No status changes found.\n";
}

// Close connections
$connA->close();
$connB->close();
?>

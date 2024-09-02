<?php
include '../../include/db.php';

if (isset($_GET['group']) && isset($_GET['update'])) {
    $update_ids = explode(',', $_GET['update']);
    $combinedIds = []; // Initialize array to hold the IDs

    foreach ($update_ids as $update_id) {
        $type = $_GET['type'];
        $primary_key = empty($_GET['primary_key']) ? $type . '_id' : $_GET['primary_key'];
        $sql = "SELECT * FROM $type WHERE $primary_key = $update_id";
        $result = mysqli_query($con, $sql);
        $row = mysqli_fetch_assoc($result);


        if ($type == 'book') {
            $common_var = json_decode($row['common_var'], true);
           
            $customer = $common_var['customer'];
            $combinedIds[] = $update_id; // Add the ID to the array
        }

        else{
            $customer = $row['customer'];
            $combinedIds[] = $update_id; // Add the ID to the array
        }

        
    }

    // Convert the IDs array to JSON
    $jsonData = json_encode($combinedIds);

    // Insert into brocher_group table with the JSON of IDs, type, and customer
    $sql = "INSERT INTO brocher_group (`data`, `type`, `customer`) VALUES ('$jsonData', '$type', '$customer')";
    $result = mysqli_query($con, $sql);

    if ($result) {
        header("Location: grouppage.php");
        exit(); 

    } else {
        echo "Error inserting data: " . mysqli_error($con);
    }
} else {
    http_response_code(400);
    echo "Update must not be empty";
}

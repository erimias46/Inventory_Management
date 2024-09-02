<?php


// Include your database connection
$redirect_link = "../../";
$side_link = "../../";

include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php';
$current_date = date('Y-m-d');

$file_name = $_POST['file_name'];
$extension = $_POST['extension'];
$date = $_POST['date'];

// Insert the data into the database
$sql = "INSERT INTO file (file_name, extension, date) VALUES ('$file_name', '$extension', '$date')";

if ($con->query($sql) === TRUE) {
    echo "Record inserted successfully";
} else {
    echo "Error: " . $sql . "<br>" . $con->error;
}


?>
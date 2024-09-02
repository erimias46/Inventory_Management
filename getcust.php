<?php


$redirect_link = "../../";
$side_link = "../../";

include_once 'include/db.php';
$current_date = date('Y-m-d');

$query = "SELECT customer_name FROM customer";
$result = mysqli_query($con, $query);

$customer_names = array();
while ($row = mysqli_fetch_assoc($result)) {
    $customer_names[] = $row['customer_name'];
}

echo json_encode($customer_names);


?>
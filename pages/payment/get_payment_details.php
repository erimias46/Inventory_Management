<?php
// Assume $con is your database connection

$redirect_link = "../../";
$side_link = "../../";

include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php';


if (isset($_POST['payment_id'])) {
    $payment_id = $_POST['payment_id'];

    $sql = "SELECT job_number, client,remained FROM payment WHERE payment_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $payment_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode($row);
    } else {
        echo json_encode(['error' => 'No data found']);
    }
}

?>

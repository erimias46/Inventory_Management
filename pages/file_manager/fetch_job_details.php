<?php
// Start the session
//session_start();

// Include your database connection
$redirect_link = "../../";
$side_link = "../../";

include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php';
$current_date = date('Y-m-d');

if (isset($_POST['job_number'])) {
    $jobNumber = $_POST['job_number'];

    // Retrieve the user_id from the session
    if (isset($_SESSION['user_id'])) {
        $session_user_id = $_SESSION['user_id'];

        // Query to get the name of the currently signed-in user
        $user_sql = "SELECT user_name FROM user WHERE user_id = '$session_user_id'";
        $user_result = mysqli_query($con, $user_sql);

        if ($user_row = mysqli_fetch_assoc($user_result)) {
            $employee_name = $user_row['user_name'];
        } else {
            // Handle case where user is not found in the database
            $employee_name = 'Unknown User';
        }
    } else {
        // Handle case where user_id is not set in the session
        $employee_name = 'Unknown User';
    }

    $sql = "
    SELECT p.client, p.date, p.size, p.quantity
    FROM payment p
    WHERE p.job_number = '$jobNumber'
    ";
    $result = mysqli_query($con, $sql);

    if ($row = mysqli_fetch_assoc($result)) {
        echo json_encode([
            'status' => 'success',
            'data' => [
                'client' => $row['client'],
                'date' => $row['date'],
                'quantity' => $row['quantity'],
                'employee_name' => $employee_name, // Use the employee name from the session
                'size' => $row['size'],
            ]
        ]);
    } else {
        echo json_encode(['status' => 'error']);
    }
}

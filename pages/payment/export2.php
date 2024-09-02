<?php
$redirect_link = "../../";
include_once $redirect_link . 'include/db.php';



if (isset($_POST['verify'])) {
    if (isset($_POST['update'])) {
        foreach ($_POST['update'] as $update_id) {
            $update_verify = "UPDATE `bank` SET `verified`= '1' WHERE bank_id = $update_id";
            $result_update = mysqli_query($con, $update_verify);
            if ($result_update) {
                echo "<script>window.location = 'action.php?status=success&redirect=payment_filter.php'; </script>";
            } else {
                echo "<script>window.location = 'action.php?status=error&redirect=payment_filter.php'; </script>";
            }
        }
    }
}


if(isset($_POST['export'])){

// Check if any data is selected for export
$selectedIds = isset($_POST['update']) ? $_POST['update'] : [];

// Build the SQL query based on the selected data
if (!empty($selectedIds)) {
    $ids = implode(',', array_map('intval', $selectedIds));
    $sql = "SELECT * FROM bank WHERE bank_id IN ($ids)";
} else {
    $sql = "SELECT * FROM bank WHERE verified = '0'"; // Export all data if none selected
}

$result = mysqli_query($con, $sql);

if ($result) {
    // Set the header for Excel file
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=export_" . date('Y-m-d_H-i-s') . ".xls");

    // Start the table and add the headers
    echo "<table border='1'>";
    echo "<tr>
            <th>#</th>
            <th>Client Name</th>
            <th>Bank Name</th>
            <th>Ref</th>
            <th>Amount</th>
            <th>Job Number</th>
            <th>Tax</th>
            <th>Status</th>
            <th>Date</th>
          </tr>";

    // Populate the table with data from the database
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>{$row['bank_id']}</td>
                <td>{$row['client']}</td>
                <td>{$row['name']}</td>
                <td>{$row['reference_number']}</td>
                <td>{$row['amount']}</td>
                <td>{$row['jobnumber']}</td>
                <td>" . number_format($row['taxwithholding'], 2) . "</td>
                <td>" . ($row['verified'] ? 'Verified' : 'Unverified') . "</td>
                <td>{$row['date']}</td>
              </tr>";
    }

    // End the table
    echo "</table>";
} else {
    echo "Error executing query: " . mysqli_error($con);
}

}
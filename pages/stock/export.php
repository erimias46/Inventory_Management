<?php
include '../../include/db.php';

if (!$con) {
    echo "connection error";
} else {
    header('Content-Type:application/xls');
    header('Content-Disposition:attachment;filename=report.xls');
    $sql = "SELECT * FROM stock_log WHERE 1=1";  // Base query

    // Handle stock type filter
    if (isset($_GET['type']) && $_GET['type'] != 'all') {
        $type = $_GET['type'];
        $sql .= " AND stock_id = '$type'";
    }

    // Handle date range filter
    if (isset($_GET['from_date']) && isset($_GET['to_date'])) {
        $from_date = $_GET['from_date'];
        $to_date = $_GET['to_date'];
        $sql .= " AND DATE(created_at) BETWEEN '$from_date' AND '$to_date'";
    }

    $html = '
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>User</th>
                <th>Stock Type</th>
                <th>Stock Quantity</th>
                <th>Last Quantity</th>
                <th>Job Number</th>
                <th>Add/Remove</th>
                <th>Log Type</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>';
        $result = mysqli_query($con, $sql);
            while($row=mysqli_fetch_assoc($result)){
            // username
            $user_id = $row['user_id'];
            $sql0 = "SELECT * FROM user WHERE user_id = '$user_id'";
            $result0 = mysqli_query($con, $sql0);
            $row0 = mysqli_fetch_assoc($result0); 
            if (isset($row0['user_name'])) {
                $user_name = $row0['user_name'];
            } else {
                $user_name = "";
            }

            // stock type
            $stock_id = $row['stock_id'];
            $sql1 = "SELECT * FROM stock WHERE stock_id = '$stock_id'";
            $result1 = mysqli_query($con, $sql1);
            $row1 = mysqli_fetch_assoc($result1); 
            if (isset($row1['stock_type'])) {
                $stock_type = $row1['stock_type'];
            } else {
                $stock_type = "";
            }

            // stock quantity
            $status = $row['status'];
            $last_quantity = $row['last_quantity'];
            $added_removed = $row['added_removed'];
            if (isset($row['stock_quantity'])) {
                $stock_quantity = $row['stock_quantity'];
            } else {
                $stock_quantity = "";
            }            
            if ($status == "add_quantity") {
                $net = $last_quantity + $added_removed;
            } elseif ($status == "remove_quantity"){
                $net = $last_quantity - $added_removed;
            } else {
                $net = "";
            }

            // add or remove
            $status = $row['status'];
            $added_removed0 = $row['added_removed'];
            if ($status == "add_quantity") {
                $added_removed = "+$added_removed0";
            } else {
                $added_removed = "-$added_removed0";
            }

            $html.='<tr>
                        <td>'.$row['log_id'].'</td>
                        <td>'.$user_name.'</td>
                        <td>'.$stock_type.'</td>
                        <td>'.$net.'</td>
                        <td>'.$row['last_quantity'].'</td>
                        <td>'.$row['jobnumber'].'</td>
                        <td>'.$added_removed.'</td>
                        <td>'.$row['status'].'</td>
                        <td>'.$row['created_at'].'</td>
                    </tr>'; 
        }
        $html.='</tbody></table>';       
        echo $html;
}
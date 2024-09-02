<?php
include("../../../include/db.php");

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $id = $_GET['id'];
        $result = mysqli_query($con, "SELECT * FROM sales_withoutvat WHERE sales_id = $id");
        $item = mysqli_fetch_assoc($result);
        echo json_encode($item);
        break;
}
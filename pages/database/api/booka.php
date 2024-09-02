<?php


include("../../../include/db.php");


$lamination_type = $_POST['lamination_type_a'];
$required_quantity = $_POST['quantity'];

if($lamination_type!=='none'){


$sql4 = "SELECT * FROM `laminationdb` WHERE `lam_id`='$lamination_type'";
$result4 = mysqli_query($con, $sql4);
$row4 = mysqli_fetch_assoc($result4);
$care_lamination = $row4['care_lamination'];
$stock_lam_id = $row4['stock_id'];
$banner_id =$_POST['book_id'];


if ($care_lamination != 0) {

    $sql3 = "SELECT * FROM `stock` WHERE `stock_id`='$stock_lam_id'";
    $result3 = mysqli_query($con, $sql3);
    $row3 = mysqli_fetch_assoc($result3);
    $stock_id = $row3['stock_id'];
    $stock_quantity = $row3['stock_quantity2'];
    $ratio = $row3['ratio'];
    $catagory = $row3['catagory'];
    $care_lamination2 = $row3['care_lamination'];
    $total_lamination = $row3['total_lamination'];

    if ($catagory == 'lamination') {


        $reduction = $care_lamination * $required_quantity;

        $net_care = $total_lamination - ($care_lamination * $required_quantity);
       


        $job_number = $new_value;
        $reason = $_POST['description'];



        $lam_id = $lamination_type;  // Assign your lam_id and care_lamination variables
        $care_lamination = (float)$care_lamination;  // Ensure the new care_lamination is treated as a float


        $check_query = "SELECT care_lamination FROM check_lamination WHERE lam_id = '$lamination_type'";
        $result = mysqli_query($con, $check_query);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $existing_care_lamination = (float)$row['care_lamination'];

            $updated_care_lamination = $existing_care_lamination + ($care_lamination * $required_quantity);

            $update_query = "UPDATE check_lamination SET care_lamination = $updated_care_lamination WHERE lam_id = '$lamination_type'";
            $update_result = mysqli_query($con, $update_query);
        } else {

            $care_lamination = $care_lamination * $required_quantity;
            $insert_query = "INSERT INTO check_lamination(lam_id, care_lamination,base_care) VALUES ('$lamination_type', $care_lamination,$care_lamination2)";
            $insert_result = mysqli_query($con, $insert_query);
        }
    }

    if ($update_result || $insert_result) {




        $sql = "SELECT * FROM check_lamination WHERE lam_id = '$lamination_type'";
        $result = mysqli_query($con, $sql);
        $row = mysqli_fetch_assoc($result);
        $care_lamination_check = $row['care_lamination'];
        $base_care = $row['base_care'];


        $sql2 = "SELECT * FROM stock WHERE stock_id = '$stock_lam_id'";
        $result2 = mysqli_query($con, $sql2);
        $row = mysqli_fetch_assoc($result2);
        $care_lamination_stock = $row['care_lamination'];
        $stock_quantity = $row['stock_quantity'];
        $stock_quantity2 = $row['stock_quantity2'];
        $ratio = $row['ratio'];

        $stock_quantity_log = $stock_quantity2;

        if ($care_lamination_check >= $care_lamination_stock) {
            // Calculate the number of rolls used
            $rolls_used = intdiv($care_lamination_check, $care_lamination_stock);

            // Calculate the remaining amount to fulfill the order
            $remaining_check = $care_lamination_check % $care_lamination_stock;

            // Update the care_lamination_check with the remaining amount
            $care_lamination_check = $remaining_check;

            $stock_quantity2 = $stock_quantity2 - $rolls_used;
            $stock_quantity = $stock_quantity2 / $ratio;
            $stock_update = "UPDATE `stock` SET `stock_quantity2`='$stock_quantity2', `stock_quantity`='$stock_quantity' WHERE `stock_id` = '$stock_id'";
            $result_update = mysqli_query($con, $stock_update);

                $result_update = mysqli_query($con, $stock_update);
                $check_update = "UPDATE check_lamination SET care_lamination = $remaining_check WHERE lam_id = '$lamination_type'";
                $result_update2 = mysqli_query($con, $check_update);

                if ($result_update  && $result_update2) {
                    echo "<script>window.location = 'action.php?status=success&redirect=stock_managment.php'; </script>";
                } else {
                    echo "<script>window.location = 'action.php?status=error&redirect=stock_managment.php'; </script>";
                }



                // $insert_log = "INSERT INTO stock_log(user_id, stock_id, status, last_quantity, added_removed, reason, jobnumber,customer) 
                //         VALUES ('$user_id', '$stock_id', 'remove_quantity', '$stock_quantity_log', '$stock_quantity2', '$reason', '$job_number','$customer')";
                // $result_log = mysqli_query($con, $insert_log);


                $insert_log = "INSERT INTO stock_log(user_id, stock_id, status, last_quantity, added_removed, reason, jobnumber,customer) 
                        VALUES ('$user_id', '$stock_id', 'remove_quantity', '$total_lamination', '$reduction', '$reason', '$job_number','$customer')";
                $result_log = mysqli_query($con, $insert_log);




                if ($result_log) {
                    $stock_update = "UPDATE `stock` SET `total_lamination`='$net_care' WHERE `stock_id` = '$stock_id'";

                    $result_update = mysqli_query($con, $stock_update);
                   
                    if ($result_update) {
                        echo "<script>window.location = 'action.php?status=success&redirect=stock_managment.php'; </script>";
                    } else {
                        echo "<script>window.location = 'action.php?status=error&redirect=stock_managment.php'; </script>";
                    }
                } else {
                    echo "<script>window.location = 'action.php?status=error&redirect=stock_managment.php'; </script>";
                }
            } else {

                echo "<script>window.location = 'action.php?status=error&redirect=stock_managment.php'; </script>";
            }
        }
    }

}
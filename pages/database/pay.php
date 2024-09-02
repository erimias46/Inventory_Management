

<?php
include("../../../include/db.php");

$type = $_GET['type'];
$types = array('book', 'brocher', 'digital', 'otherdigital', 'banner', 'design','multi_page','single_page','banner_out');
if (!in_array($type, $types)) {
    http_response_code(400);
    echo "invalid type";
    die();
}

$primary_key = $type . "_id";

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        //$job_number = $_POST['job_number'];
        $customer = $_POST['customer'];
        $date = $_POST['date'];
        $description = $_POST['description'];
        //$lamination = $_POST['lamination_type'];
        $size = $_POST['size'];
        $unit_price = $_POST['unit_price'];
        $quantity = $_POST['quantity'];
        $advance = $_POST['advance'];
        $vat = $_POST['vat'];
        $user_id = $_POST['user_id'];
        $enddate = $_POST['enddate'];

        $machine_run=$_POST['machine_run'];
        $total = $unit_price * $quantity * (1 + $vat / 100);
        $remained = $total; // remained will be total until the payment is verfied

        // job number
        $sql = "SELECT MAX(SUBSTRING(job_number, 4)) as max_value FROM payment WHERE job_number LIKE 'jn_%'";
        $result = mysqli_query($con, $sql);
        $row = mysqli_fetch_assoc($result);
        $max_value = $row['max_value'];
        // Generate the new increment value
        $prefix = "jn_";
        $increment_value = str_pad((int) $max_value + 1, 4, "0", STR_PAD_LEFT);
        $new_value = $prefix . $increment_value;



        
        
        

        



        $add_payment = "INSERT INTO `payment`(`job_number`, `user_id`, `client`, `date`,`enddate`, `job_description`, `size`, `quantity`, `unit_price`, `advance`, `remained`, `total`, `status`) 
        VALUES ('$new_value', '$user_id', '$customer', '$date','$enddate', '$description', '$size', '$quantity', '$unit_price', '$advance', '$remained', '$total', 'start')";
        $result_add = mysqli_query($con, $add_payment);




        $type = $_GET['type'];
        $new_number = $_POST['machine_run'];

        $sql = "SELECT calc_count FROM machine_run WHERE type = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("s", $type);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $current_calc_count = $row['calc_count'];

            // Step 3: Add the New Number to the Current calc_count
            $new_calc_count = $current_calc_count + $new_number;

            // Step 4: Update the calc_count in the Database
            $update_sql = "UPDATE machine_run SET calc_count = ? WHERE type = ?";
            $update_stmt = $con->prepare($update_sql);
            $update_stmt->bind_param("is", $new_calc_count, $type);

            if ($update_stmt->execute()) {
                echo "calc_count updated successfully!";
            } else {
                echo "Error updating calc_count: " . $con->error;
            }
        } else {
            echo "Type not found in the machine_run table.";
        }


        if($type== 'otherdigital'){

            $unit_digital_id=$_POST['unit_price'];
            $required_quantity=$_POST['quantity'];
            $required_quantity=$_POST['quantity'];
            $sql="SELECT * FROM `unitdigital` WHERE `unitdigital_id`='$unit_digital_id'";
            $result = mysqli_query($con, $sql);
            $row = mysqli_fetch_assoc($result);
            $unit_digital_stock = $row['stock_id'];
            $unit_digital_print_side=$row['print_side'];


            $deduct_quantity = $required_quantity * $unit_digital_print_side;

            $sql2="SELECT * FROM `office_stock` WHERE `stock_id`='$unit_digital_stock'";
            $result2 = mysqli_query($con, $sql2);
            $row2 = mysqli_fetch_assoc($result2);
            $stock_id = $row2['stock_id'];
            $stock_quantity = $row2['stock_quantity2'];
            $ratio = $row2['ratio'];

            $net_quantity = $stock_quantity - $deduct_quantity;
            $set_stock_quantity = $net_quantity / $ratio;
            


            $job_number = $new_value;
            $reason = $_POST['description'];

            $insert_log = "INSERT INTO office_stock_log(user_id, stock_id, status, last_quantity, added_removed, reason, jobnumber) 
                    VALUES ('$user_id', '$stock_id', 'remove_quantity', '$stock_quantity', '$deduct_quantity', '$reason', '$job_number')";
            $result_log = mysqli_query($con, $insert_log);

            if ($result_log) {
                $stock_update = "UPDATE `office_stock` SET `stock_quantity2`='$net_quantity',`stock_quantity`='$set_stock_quantity' WHERE `stock_id` = '$stock_id'";
                $update_payment = "UPDATE `$type` SET `payment_status`=1 WHERE `$primary_key` = '$primary_key '";
		   $update = mysqli_query($con, $update_payment);
                $result_update = mysqli_query($con, $stock_update);
                if ($result_update) {
                    echo "<script>window.location = 'action.php?status=success&redirect=stock_managment.php'; </script>";
                } else {
                    echo "<script>window.location = 'action.php?status=error&redirect=stock_managment.php'; </script>";
                }
            } else {
                echo "<script>window.location = 'action.php?status=error&redirect=stock_managment.php'; </script>";
            }






        }


        if($type=='book'){

            include('book.php');




        }



        if($type=='single_page'){

            

            $page_id=$_POST['page_id'];
            $required_quantity=$_POST['quantity'];

            $required_paper_full = $required_quantity;

            $sql="SELECT * FROM `pagedb` WHERE `page_id`='$page_id'";
            $result = mysqli_query($con, $sql);
            $row = mysqli_fetch_assoc($result);
            $stock_id = $row['stock_id'];





            $user = "masteradmin";

            $sql2 = "SELECT * FROM `office_stock` WHERE `stock_id`='$stock_id'";
            $result2 = mysqli_query($con, $sql2);
            $row2 = mysqli_fetch_assoc($result2);
            $stock_id = $row2['stock_id'];
            $stock_quantity = $row2['stock_quantity2'];
            $ratio = $row2['ratio'];


            $net_quantity = $stock_quantity - $required_paper_full;
            $set_stock_quantity = $net_quantity / $ratio;
            


            $job_number = $new_value;
            $reason = $_POST['description'];

            $insert_log = "INSERT INTO office_stock_log(user_id, stock_id, status, last_quantity, added_removed, reason, jobnumber) 
                    VALUES ('$user_id', '$stock_id', 'remove_quantity', '$stock_quantity', '$required_paper_full', '$reason', '$job_number')";
            $result_log = mysqli_query($con, $insert_log);

            if ($result_log) {
                $stock_update = "UPDATE `office_stock` SET `stock_quantity2`='$net_quantity',`stock_quantity`='$set_stock_quantity' WHERE `stock_id` = '$stock_id'";
                $result_update = mysqli_query($con, $stock_update);
                $update_payment = "UPDATE `$type` SET `payment_status`=1 WHERE `$primary_key` = '$primary_key '";
		   $update = mysqli_query($con, $update_payment);
                if ($result_update) {
                    echo "<script>window.location = 'action.php?status=success&redirect=stock_managment.php'; </script>";
                } else {
                    echo "<script>window.location = 'action.php?status=error&redirect=stock_managment.php'; </script>";
                }
            } else {
                echo "<script>window.location = 'action.php?status=error&redirect=stock_managment.php'; </script>";
            }





        }

        if($type=='multi_page'){
        
            $page_id_a=$_POST['page_id_a'];
            $page_id_b=$_POST['page_id_b'];
            $page_id_c=$_POST['page_id_c'];
            $page_id_d=$_POST['page_id_d'];
            $nopage_a=$_POST['nopage_a'];
            $nopage_b=$_POST['nopage_b'];
            $nopage_c=$_POST['nopage_c'];
            $nopage_d=$_POST['nopage_d'];

            $required_quantity=$_POST['quantity'];

            $required_paper_full = $required_quantity*$nopage_a;

            $sql="SELECT * FROM `pagedb` WHERE `page_id`='$page_id_a'";
            $result = mysqli_query($con, $sql);
            $row = mysqli_fetch_assoc($result);
            $stock_id = $row['stock_id'];

            $user = "masteradmin";

            $sql2 = "SELECT * FROM `office_stock` WHERE `stock_id`='$stock_id'";
            $result2 = mysqli_query($con, $sql2);
            $row2 = mysqli_fetch_assoc($result2);
            $stock_id = $row2['stock_id'];
            $stock_quantity = $row2['stock_quantity2'];
            $ratio = $row2['ratio'];


            $net_quantity = $stock_quantity - $required_paper_full;
            $set_stock_quantity = $net_quantity / $ratio;
           


            $job_number = $new_value;
            $reason = $_POST['description'];

            $insert_log = "INSERT INTO office_stock_log(user_id, stock_id, status, last_quantity, added_removed, reason, jobnumber) 
                    VALUES ('$user_id', '$stock_id', 'remove_quantity', '$stock_quantity', '$required_paper_full', '$reason', '$job_number')";
            $result_log = mysqli_query($con, $insert_log);

            if ($result_log) {
                $stock_update = "UPDATE `office_stock` SET `stock_quantity2`='$net_quantity',`stock_quantity`='$set_stock_quantity' WHERE `stock_id` = '$stock_id'";
                $result_update = mysqli_query($con, $stock_update);
                $update_payment = "UPDATE `$type` SET `payment_status`=1 WHERE `$primary_key` = '$primary_key '";
		   $update = mysqli_query($con, $update_payment);
                if ($result_update) {
                    echo "<script>window.location = 'action.php?status=success&redirect=stock_managment.php'; </script>";
                } else {
                    echo "<script>window.location = 'action.php?status=error&redirect=stock_managment.php'; </script>";
                }
            } else {
                echo "<script>window.location = 'action.php?status=error&redirect=stock_managment.php'; </script>";
            }



            $sql="SELECT * FROM `pagedb` WHERE `page_id`='$page_id_b'";
            $result = mysqli_query($con, $sql);
            $row = mysqli_fetch_assoc($result);
            $stock_id = $row['stock_id'];

            $user = "masteradmin";

            $sql2 = "SELECT * FROM `office_stock` WHERE `stock_id`='$stock_id'";
            $result2 = mysqli_query($con, $sql2);
            $row2 = mysqli_fetch_assoc($result2);
            $stock_id = $row2['stock_id'];
            $stock_quantity = $row2['stock_quantity2'];
            $ratio = $row2['ratio'];

            $required_paper_full = $required_quantity*$nopage_b;

            $net_quantity = $stock_quantity - $required_paper_full;
            $set_stock_quantity = $net_quantity / $ratio;
           


            $job_number = $new_value;
            $reason = $_POST['description'];

            $insert_log = "INSERT INTO office_stock_log(user_id, stock_id, status, last_quantity, added_removed, reason, jobnumber) 
                    VALUES ('$user_id', '$stock_id', 'remove_quantity', '$stock_quantity', '$required_paper_full', '$reason', '$job_number')";
            $result_log = mysqli_query($con, $insert_log);

            if ($result_log) {
                $stock_update = "UPDATE `office_stock` SET `stock_quantity2`='$net_quantity',`stock_quantity`='$set_stock_quantity' WHERE `stock_id` = '$stock_id'";
                $result_update = mysqli_query($con, $stock_update);
                $update_payment = "UPDATE `$type` SET `payment_status`=1 WHERE `$primary_key` = '$primary_key '";
		   $update = mysqli_query($con, $update_payment);
                if ($result_update) {
                    echo "<script>window.location = 'action.php?status=success&redirect=stock_managment.php'; </script>";
                } else {
                    echo "<script>window.location = 'action.php?status=error&redirect=stock_managment.php'; </script>";
                }
            } else {
                echo "<script>window.location = 'action.php?status=error&redirect=stock_managment.php'; </script>";
            }




            $sql = "SELECT * FROM `pagedb` WHERE `page_id`='$page_id_c'";
            $result = mysqli_query($con, $sql);
            $row = mysqli_fetch_assoc($result);
            $stock_id = $row['stock_id'];

            $user = "masteradmin";

            $sql2 = "SELECT * FROM `office_stock` WHERE `stock_id`='$stock_id'";
            $result2 = mysqli_query($con, $sql2);
            $row2 = mysqli_fetch_assoc($result2);
            $stock_id = $row2['stock_id'];
            $stock_quantity = $row2['stock_quantity2'];
            $ratio = $row2['ratio'];

            $required_paper_full = $required_quantity * $nopage_c;

            $net_quantity = $stock_quantity - $required_paper_full;
            $set_stock_quantity = $net_quantity / $ratio;
            


            $job_number = $new_value;
            $reason = $_POST['description'];

            $insert_log = "INSERT INTO office_stock_log(user_id, stock_id, status, last_quantity, added_removed, reason, jobnumber) 
                    VALUES ('$user_id', '$stock_id', 'remove_quantity', '$stock_quantity', '$required_paper_full', '$reason', '$job_number')";
            $result_log = mysqli_query($con, $insert_log);

            if ($result_log) {
                $stock_update = "UPDATE `office_stock` SET `stock_quantity2`='$net_quantity',`stock_quantity`='$set_stock_quantity' WHERE `stock_id` = '$stock_id'";
                $result_update = mysqli_query($con, $stock_update);
                $update_payment = "UPDATE `$type` SET `payment_status`=1 WHERE `$primary_key` = '$primary_key '";
		   $update = mysqli_query($con, $update_payment);
                if ($result_update) {
                    echo "<script>window.location = 'action.php?status=success&redirect=stock_managment.php'; </script>";
                } else {
                    echo "<script>window.location = 'action.php?status=error&redirect=stock_managment.php'; </script>";
                }
            } else {
                echo "<script>window.location = 'action.php?status=error&redirect=stock_managment.php'; </script>";
            }




            $sql = "SELECT * FROM `pagedb` WHERE `page_id`='$page_id_d'";
            $result = mysqli_query($con, $sql);
            $row = mysqli_fetch_assoc($result);
            $stock_id = $row['stock_id'];

            $user = "masteradmin";

            $sql2 = "SELECT * FROM `office_stock` WHERE `stock_id`='$stock_id'";
            $result2 = mysqli_query($con, $sql2);
            $row2 = mysqli_fetch_assoc($result2);
            $stock_id = $row2['stock_id'];
            $stock_quantity = $row2['stock_quantity2'];
            $ratio = $row2['ratio'];

            $required_paper_full = $required_quantity * $nopage_d;

            $net_quantity = $stock_quantity - $required_paper_full;
            $set_stock_quantity = $net_quantity / $ratio;
           


            $job_number = $new_value;
            $reason = $_POST['description'];

            $insert_log = "INSERT INTO office_stock_log(user_id, stock_id, status, last_quantity, added_removed, reason, jobnumber) 
                    VALUES ('$user_id', '$stock_id', 'remove_quantity', '$stock_quantity', '$required_paper_full', '$reason', '$job_number')";
            $result_log = mysqli_query($con, $insert_log);

            if ($result_log) {
                $stock_update = "UPDATE `office_stock` SET `stock_quantity2`='$net_quantity',`stock_quantity`='$set_stock_quantity' WHERE `stock_id` = '$stock_id'";
                $result_update = mysqli_query($con, $stock_update);
                $update_payment = "UPDATE `$type` SET `payment_status`=1 WHERE `$primary_key` = '$primary_key '";
		   $update = mysqli_query($con, $update_payment);
                if ($result_update) {
                    echo "<script>window.location = 'action.php?status=success&redirect=stock_managment.php'; </script>";
                } else {
                    echo "<script>window.location = 'action.php?status=error&redirect=stock_managment.php'; </script>";
                }
            } else {
                echo "<script>window.location = 'action.php?status=error&redirect=stock_managment.php'; </script>";
            }




            

include('multipage.php');
            






        }




        if($type=='brocher'){


            include('brocher.php');








            
        }


        if($type=='banner'){

            $unitbanner_type=$_POST['unitbanner_type'];
            $width=$_POST['width'];
            $length=$_POST['lengths'];
            $commitonprice=$_POST['commitonprice'];
            $cvat=$_POST['cvat'];
            $product=$_POST['product'];
		    $banner_id =$_POST['banner_id'];

            $sql5="SELECT * FROM `unitbanner` WHERE `unitbanner_id`='$unitbanner_type'";
            $result5 = mysqli_query($con, $sql5);
            $row5 = mysqli_fetch_assoc($result5);
            // $care_lamination = $row5['care_banner'];
            $stock_ban_id = $row5['stock_id'];
            $unitbanner_outprice = $row5['outprice'];


            


            $total_care_banner = $quantity * $width * $length;
    $total_price = $unitbanner_outprice * $quantity * $width * $length + $commitonprice+ (($cvat /100)*$commitonprice);
    $total_price_vat = (($vat / 100) * $total_price) + $total_price;

    $unit_price=$total_price/$quantity;

    $current_date = date("Y-m-d");

if($product=='outsource'){
                $add_banner_out = "INSERT INTO banner_out(customer, job_type, size, required_quantity, total_price, unit_price, total_price_vat, vat,cvat, width, lengths, commitonprice, date,totalkare,payment_status) 
        VALUES ('$customer', '$description', '$size', '$quantity', '$total_price', '$unit_price', '$total_price_vat', '$vat','$cvat', '$width', '$length', '$commitonprice', '$current_date','$unitbanner_outprice',1)";
                $result_add_out = mysqli_query($con, $add_banner_out);

}else{
    $sql2 = "SELECT * FROM stock WHERE stock_id = '$stock_ban_id'";
    $result2 = mysqli_query($con, $sql2);
    $row6 = mysqli_fetch_assoc($result2);
    $care_lamination_stock = $row6['care_lamination'];
    $stock_quantity = $row6['stock_quantity'];
    $stock_quantity2 = $row6['stock_quantity2'];
    $ratio = $row6['ratio'];
    $stock_quantity_log = $stock_quantity2;
    $stock_quantity2 = $width * $length;
    $total_lamination = $row6['total_lamination'];
    $job_number = $new_value;
    $net_care = $total_lamination - $total_care_banner;
       

        $insert_log = "INSERT INTO stock_log(user_id, stock_id, status, last_quantity, added_removed, reason, jobnumber) 
                        VALUES ('$user_id', '$stock_id', 'remove_quantity', '$stock_quantity_log', '$stock_quantity2', '$description', '$job_number')";
                $result_log = mysqli_query($con, $insert_log);



                if ($result_log) {
                    $stock_update = "UPDATE `stock` SET `total_lamination`='$net_care' WHERE `stock_id` = '$stock_id'";

                    $result_update = mysqli_query($con, $stock_update);
                    $update_payment = "UPDATE `banner` SET `payment_status`=1 WHERE `banner_id` = '$banner_id'";
		   $update = mysqli_query($con, $update_payment);

                    if ($result_update) {
                        echo "<script>window.location = 'action.php?status=success&redirect=stock_managment.php'; </script>";
                    } else {
                        echo "<script>window.location = 'action.php?status=error&redirect=stock_managment.php'; </script>";
                    }
                } else {
                    echo "<script>window.location = 'action.php?status=error&redirect=stock_managment.php'; </script>";
                }
   
            
        }

    }
        mysqli_close($con);





//         $hostname2 = "localhost";
// $username2 = "root";
// $password2 = "";
// $database2 = "elegantprintsolu_project";

// $con2 = mysqli_connect($hostname2, $username2, $password2, $database2);


// if (!$con2) {
//     die("Connection failed: " . mysqli_connect_error());
// }






        


        
// $add_payment_second_db = "INSERT INTO `projects`( `saas_id`, `created_by`, `title`, `description`, `starting_date`, `ending_date`, `status`) 
// VALUES ( '2', '$user_id','$new_value' , '$description', '$date', '$enddate', '1')";
// $result_add_second_db = mysqli_query($con2, $add_payment_second_db);


// if ($result_add_second_db) {

// $project_id = mysqli_insert_id($con2);


// $projectusers = "INSERT INTO `project_users`(`user_id`, `project_id`) VALUES ('$user_id', '$project_id')";
// $result_projectusers = mysqli_query($con2, $projectusers);


// if ($result_projectusers) {
//     echo "Data inserted into both tables.";
// } else {
//     echo "Error in the second query: " . mysqli_error($con2);
// }
// } else {
// echo "Error in the first query: " . mysqli_error($con2);
// }





//     mysqli_close($con2);


        


    
    
    if ($result_add  ) {
            
            echo 'Job Number: ' . $new_value;
        } else {
            http_response_code(400);
            echo 'Unable to add payment';
            die();
        }
        break;
}
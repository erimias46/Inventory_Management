

<?php
include("../../../include/db.php");

include("../../../include/mdb.php");


$type = $_GET['type'];
$types = array('book', 'brocher', 'digital', 'otherdigital', 'banner', 'design', 'multi_page', 'single_page', 'banner_out','bag');
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

        

        $type = $_GET['type'];

        if ($type == "book" || $type == "multi_page" || $type == "single_page" || $type == "brocher") {

            $machine_run = $_POST['machine_run'];
        }
        $total = $unit_price * $quantity * (1 + $vat / 100);
        $remained = $total; // remained will be total until the payment is verfied

        // job number
        $filename = 'job_numbers.txt';

        // Step 1: Read the text file to check existing job numbers
        $existing_numbers = file_exists($filename) ? file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];

        // Step 2: Get the max value from the database
        $sql = "SELECT MAX(SUBSTRING(job_number, 4)) as max_value FROM payment WHERE job_number LIKE 'jn_%'";
        $result = mysqli_query($con, $sql);
        $row = mysqli_fetch_assoc($result);
        $max_value = $row['max_value'];

        // Step 3: Generate a new job number
        $prefix = "jn_";
        $increment_value = (int) $max_value + 1;

        // Keep incrementing until we find an unused job number
        do {
            $new_value = $prefix . str_pad($increment_value, 4, "0", STR_PAD_LEFT);
            $increment_value++;
        } while (in_array($new_value, $existing_numbers));

        // Step 4: Write the new job number to the text file
        file_put_contents($filename, $new_value . PHP_EOL, FILE_APPEND | LOCK_EX);

        // Now you can use $new_value as the new job number
        echo "New Job Number: " . $new_value;
















        $created_date = date("Y-m-d");


        $sql = "SELECT * FROM `customer` WHERE `customer_name`='$customer'";
        $result = mysqli_query($con, $sql);
        $row = mysqli_fetch_assoc($result);
        $managment_id = $row['management_id'];





        $add_management = "INSERT INTO `oli_projects`(`title`,`description`,`project_type`,`start_date`,`deadline`,`created_date`,`created_by`,`status`, `client_id`) 
        VALUES ('$description','$description','client_project','$date','$enddate','$created_date','$user_id','open','$managment_id')";

        $result_add_management = mysqli_query($conn, $add_management);

        $project_id = mysqli_insert_id($conn);


        $add_payment = "INSERT INTO `payment`(`job_number`, `user_id`, `client`, `date`,`enddate`, `job_description`, `size`, `quantity`, `unit_price`, `advance`, `remained`, `total`, `status`,`project_id`,`updated_at`) 
        VALUES ('$new_value', '$user_id', '$customer', '$date','$enddate', '$description', '$size', '$quantity', '$unit_price', '$advance', '$remained', '$total', 'start','$project_id', NOW())";
        $result_add = mysqli_query($con, $add_payment);


        $payment_id = mysqli_insert_id($con);



        if ($result_add_management) {
            // Get the last inserted project_id
            $project_id = mysqli_insert_id($conn);


            $sql="SELECT * FROM oli_users WHERE user_type='staff'";
            $result = mysqli_query($conn, $sql);
            while ($row = mysqli_fetch_assoc($result)) {
                $user_id = $row['id'];
                $add_user_id="INSERT INTO oli_project_members(project_id,user_id) values ('$project_id','$user_id')";
                $result_add_user_id = mysqli_query($conn, $add_user_id);
            }


           


            include('order.php');



            $add_task= "INSERT INTO `oli_tasks`(`title`,`description`,`project_id`,`assigned_to`,`deadline`,`start_date`,`created_date`,`context`,`status_id`) 
                                     VALUES ('$description','$order_desc','$project_id','1','$enddate','$date','$created_date','project',1)";

            $result_add_task = mysqli_query($conn, $add_task);




            $display_id = $new_value;
            $tax_id = 0;
            $tax_id2 = 0;
            $tax_id3 = 0;
            $discount_amount = 0;
            $invoice_total = $total;
            $invoice_subtotal = $total;
            $company_id = 0;
            $discount_total = 0;
            $tax = 0;
            $tax2 = 0;
            $tax3 = 0;




            $add_invoice = "INSERT INTO `oli_invoices` ( `type`,
    `client_id`,
    `project_id`,
    `bill_date`,
    `due_date`,
    `status`,
    `tax_id`,
    `tax_id2`,
    `tax_id3`,
    `recurring`,
    `recurring_invoice_id`,
    `repeat_every`,
    `repeat_type`,
    `no_of_cycles`,
    `no_of_cycles_completed`,
    `discount_amount`,
    `discount_amount_type`,
    `discount_type`,
    `company_id`,
    `estimate_id`,
    `main_invoice_id`,
    `subscription_id`,
    `invoice_total`,
    `invoice_subtotal`,
    `discount_total`,
    `tax`,
    `tax2`,
    `tax3`,
    `order_id`,
    `deleted`,
    `display_id`
) VALUES (
    'invoice',   
    '$managment_id',
    '$project_id',
    '$date',
    '$enddate',
    'draft', 
    '$tax_id',
    '$tax_id2',
    '$tax_id3',
    0,
    0,
    0,
    NULL,
    0,
    0,
    '$discount_amount',
    'fixed_amount',
    'before_tax',
    '$company_id',
    0,
    0,
    0,
    '$invoice_total',
    '$invoice_subtotal',
    '$discount_total',
    '$tax',
    '$tax2',
    '$tax3',
    0,
    0,
    '$display_id'
)";

            $result_add_invoice = mysqli_query($conn, $add_invoice);

            if ($result_add_invoice) {
                echo "Invoice added successfully!";



                $invoice_id = mysqli_insert_id($conn);

                $user_id_managmenet = 1;


                $sql = "INSERT INTO oli_items (title, description, unit_type, rate) 
                         VALUES ('$new_value', '$description', '$type', '$total')";
                $result = mysqli_query($conn, $sql);

                if ($result) {
                    echo "Data inserted into oli_items";

                    $item_id = mysqli_insert_id($conn);

                    $unit_price_vat = $unit_price * (1 + $vat / 100);

                    $sql = "INSERT INTO oli_invoice_items (title, description, quantity, unit_type, rate, total, invoice_id, item_id)
                    VALUES ('$new_value', '$description', '$quantity', '$type', '$unit_price_vat', '$total', '$invoice_id', '$item_id')";

                    $result = mysqli_query($conn, $sql);

                    if ($result) {
                        echo "Data inserted into oli_invoice_items";
                    } else {
                        echo "Error inserting data into oli_invoice_items";
                    }
                } else {
                    echo "Error inserting data into oli_items";
                }







                $add_pay_to_manage = "INSERT INTO  project_connect(project_id,invoice_id,management_id,payment_id) VALUES ('$project_id','$invoice_id','$managment_id','$payment_id')";
                $result_pay_to_manage = mysqli_query($con, $add_pay_to_manage);

                if ($result_pay_to_manage) {
                    echo "Payment added to management successfully!";
                } else {
                    echo "Error adding payment to management: " . mysqli_error($con);
                }
            } else {
                echo "Error adding invoice: " . mysqli_error($conn);
            }
        } else {
            echo "Error in first insert: " . mysqli_error($conn);
        }











        $type = $_GET['type'];

        if ($type == "multi_page" || $type == "single_page" || $type == "brocher"  || $type=='bag') {

            $machine_type = $_POST['machine_type'];

            //  $new_number =
            $new_number = ceil(floatval($_POST['machine_run']));;

            $sql = "SELECT calc_count FROM machine_run WHERE id = ?";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("s", $machine_type);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $current_calc_count = $row['calc_count'];

                // Step 3: Add the New Number to the Current calc_count
                $new_calc_count = $current_calc_count + $new_number;

                // Step 4: Update the calc_count in the Database
                $update_sql = "UPDATE machine_run SET calc_count = ? WHERE id = ?";
                $update_stmt = $con->prepare($update_sql);
                $update_stmt->bind_param("is", $new_calc_count, $machine_type);

                if ($update_stmt->execute()) {
                    echo "calc_count updated successfully!";


                    $sql = "INSERT INTO `machine_run_log`(`machine_id`, `count`, `type`,`job_number`) VALUES ('$machine_type','$new_number','$type','$new_value')";
                    $result = mysqli_query($con, $sql);

                    if ($result) {
                        echo "Data inserted into machine_run_log";
                    } else {
                        echo "Error inserting data into machine_run_log";
                    }
                } else {
                    echo "Error updating calc_count: " . $con->error;
                }
            } else {
                echo "Type not found in the machine_run table.";
            }
        }


        if ($type == 'digital') {

            $digital_id = $_POST['digital_id'];


            $update_payment = "UPDATE $type SET `payment_status`=1 WHERE digital_id = '$digital_id'";
            $update = mysqli_query($con, $update_payment);

            if ($update) {
                echo "<script>window.location = 'action.php?status=success&redirect=stock_managment.php'; </script>";
            } else {
                echo "<script>window.location = 'action.php?status=error&redirect=stock_managment.php'; </script>";
            }
        }


        if ($type == 'design') {

            $digital_id = $_POST['design_id'];


            $update_payment = "UPDATE $type SET `payment_status`=1 WHERE digital_id = '$digital_id'";
            $update = mysqli_query($con, $update_payment);

            if ($update) {
                echo "<script>window.location = 'action.php?status=success&redirect=stock_managment.php'; </script>";
            } else {
                echo "<script>window.location = 'action.php?status=error&redirect=stock_managment.php'; </script>";
            }
        }



        if ($type == 'otherdigital') {

            $unit_digital_id = $_POST['unit_digital_type'];
            $required_quantity = $_POST['quantity'];

            $sql = "SELECT * FROM `unitdigital` WHERE `unitdigital_id`='$unit_digital_id'";
            $result = mysqli_query($con, $sql);
            $row = mysqli_fetch_assoc($result);
            $unit_digital_stock = $row['stock_id'];


            $otherdigital_id = $_POST['otherdigital_id'];



            $deduct_quantity = $required_quantity;

            $sql2 = "SELECT * FROM `stock` WHERE `stock_id`='$unit_digital_stock'";
            $result2 = mysqli_query($con, $sql2);
            $row2 = mysqli_fetch_assoc($result2);
            $stock_id = $row2['stock_id'];
            $stock_quantity = $row2['stock_quantity2'];
            $ratio = $row2['ratio'];

            $net_quantity = $stock_quantity - $deduct_quantity;
            $set_stock_quantity = $net_quantity / $ratio;



            $job_number = $new_value;
            $reason = $_POST['description'];

            $insert_log = "INSERT INTO stock_log(user_id, stock_id, status, last_quantity, added_removed, reason, jobnumber,customer) 
                    VALUES ('$user_id', '$stock_id', 'remove_quantity', '$stock_quantity', '$deduct_quantity', '$reason', '$job_number','$customer')";
            $result_log = mysqli_query($con, $insert_log);

            if ($result_log) {
                $stock_update = "UPDATE `stock` SET `stock_quantity2`='$net_quantity',`stock_quantity`='$set_stock_quantity' WHERE `stock_id` = '$stock_id'";

                $result_update = mysqli_query($con, $stock_update);

                $update_payment = "UPDATE $type SET `payment_status`=1 WHERE otherdigital_id = '$otherdigital_id'";
                $update = mysqli_query($con, $update_payment);

                if ($result_update && $update) {
                    echo "<script>window.location = 'action.php?status=success&redirect=stock_managment.php'; </script>";
                } else {
                    echo "<script>window.location = 'action.php?status=error&redirect=stock_managment.php'; </script>";
                }
            } else {
                echo "<script>window.location = 'action.php?status=error&redirect=stock_managment.php'; </script>";
            }
        }


        if ($type == 'book') {

            include('book.php');
        }



        if ($type == 'single_page') {



            $page_id = $_POST['page_id'];
            $required_quantity = $_POST['quantity'];

            $required_paper_full = $required_quantity;

            $sql = "SELECT * FROM `pagedb` WHERE `page_id`='$page_id'";
            $result = mysqli_query($con, $sql);
            $row = mysqli_fetch_assoc($result);
            $stock_id = $row['stock_id'];
            $print_side = $row['print_side'];





            $user = "masteradmin";

            $sql2 = "SELECT * FROM `office_stock` WHERE `stock_id`='$stock_id'";
            $result2 = mysqli_query($con, $sql2);
            $row2 = mysqli_fetch_assoc($result2);
            $stock_id = $row2['stock_id'];
            $stock_quantity = $row2['stock_quantity2'];
            $ratio = $row2['ratio'];

            $deduct_quantity = $required_paper_full * $print_side;


            $net_quantity = $stock_quantity - $deduct_quantity;
            $set_stock_quantity = $net_quantity / $ratio;



            $job_number = $new_value;
            $reason = $_POST['description'];

            $insert_log = "INSERT INTO office_stock_log(user_id, stock_id, status, last_quantity, added_removed, reason, jobnumber,customer) 
                    VALUES ('$user_id', '$stock_id', 'remove_quantity', '$stock_quantity', '$deduct_quantity', '$reason', '$job_number','$customer')";
            $result_log = mysqli_query($con, $insert_log);

            if ($result_log) {
                $stock_update = "UPDATE `office_stock` SET `stock_quantity2`='$net_quantity',`stock_quantity`='$set_stock_quantity' WHERE `stock_id` = '$stock_id'";
                $result_update = mysqli_query($con, $stock_update);
                $banner_id = $_POST['single_page_id'];


                $update_payment = "UPDATE single_page SET `payment_status`=1 WHERE `single_page_id` = '$banner_id'";
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

        if ($type == 'multi_page') {


            $banner_id = $_POST['multi_page_id'];

            $user_id=$_POST['user_id'];


            $update_payment = "UPDATE multi_page SET `payment_status`=1 WHERE `multi_page_id` = '$banner_id'";
            $update = mysqli_query($con, $update_payment);



            $page_id_a = $_POST['page_id_a'];
            $page_id_b = $_POST['page_id_b'];
            $page_id_c = $_POST['page_id_c'];
            $page_id_d = $_POST['page_id_d'];
            $nopage_a = $_POST['nopage_a'];
            $nopage_b = $_POST['nopage_b'];
            $nopage_c = $_POST['nopage_c'];
            $nopage_d = $_POST['nopage_d'];

            $required_quantity = $_POST['quantity'];

            $required_paper_full = $required_quantity * $nopage_a;

            $sql = "SELECT * FROM `pagedb` WHERE `page_id`='$page_id_a'";
            $result = mysqli_query($con, $sql);
            $row = mysqli_fetch_assoc($result);
            $stock_id = $row['stock_id'];
            $print_side = $row['print_side'];

            $user = "masteradmin";

            $sql2 = "SELECT * FROM `office_stock` WHERE `stock_id`='$stock_id'";
            $result2 = mysqli_query($con, $sql2);
            $row2 = mysqli_fetch_assoc($result2);
            $stock_id = $row2['stock_id'];
            $stock_quantity = $row2['stock_quantity2'];
            $ratio = $row2['ratio'];






            $deduct_quantity = $required_paper_full * $print_side;

            $net_quantity = $stock_quantity - $deduct_quantity;
            $set_stock_quantity = $net_quantity / $ratio;



            $job_number = $new_value;
            $reason = $_POST['description'];

            $insert_log = "INSERT INTO office_stock_log(user_id, stock_id, status, last_quantity, added_removed, reason, jobnumber,customer) 
                    VALUES ('$user_id', '$stock_id', 'remove_quantity', '$stock_quantity', '$deduct_quantity', '$reason', '$job_number','$customer')";
            $result_log = mysqli_query($con, $insert_log);

            if ($result_log) {
                $stock_update = "UPDATE `office_stock` SET `stock_quantity2`='$net_quantity',`stock_quantity`='$set_stock_quantity' WHERE `stock_id` = '$stock_id'";
                $result_update = mysqli_query($con, $stock_update);
                if ($result_update) {
                    echo "<script>window.location = 'action.php?status=success&redirect=stock_managment.php'; </script>";
                } else {
                    echo "<script>window.location = 'action.php?status=error&redirect=stock_managment.php'; </script>";
                }
            } else {
                echo "<script>window.location = 'action.php?status=error&redirect=stock_managment.php'; </script>";
            }



            $sql = "SELECT * FROM `pagedb` WHERE `page_id`='$page_id_b'";
            $result = mysqli_query($con, $sql);
            $row = mysqli_fetch_assoc($result);
            $stock_id = $row['stock_id'];
            $print_side = $row['print_side'];

            $user = "masteradmin";

            $sql2 = "SELECT * FROM `office_stock` WHERE `stock_id`='$stock_id'";
            $result2 = mysqli_query($con, $sql2);
            $row2 = mysqli_fetch_assoc($result2);
            $stock_id = $row2['stock_id'];
            $stock_quantity = $row2['stock_quantity2'];
            $ratio = $row2['ratio'];

            $required_paper_full = $required_quantity * $nopage_b;

            $deduct_quantity = $required_paper_full * $print_side;

            $net_quantity = $stock_quantity - $deduct_quantity;
            $set_stock_quantity = $net_quantity / $ratio;



            $job_number = $new_value;
            $reason = $_POST['description'];

            $insert_log = "INSERT INTO office_stock_log(user_id, stock_id, status, last_quantity, added_removed, reason, jobnumber,customer) 
                    VALUES ('$user_id', '$stock_id', 'remove_quantity', '$stock_quantity', '$deduct_quantity', '$reason', '$job_number','$customer')";
            $result_log = mysqli_query($con, $insert_log);

            if ($result_log) {
                $stock_update = "UPDATE `office_stock` SET `stock_quantity2`='$net_quantity',`stock_quantity`='$set_stock_quantity' WHERE `stock_id` = '$stock_id'";
                $result_update = mysqli_query($con, $stock_update);
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
            $print_side = $row['print_side'];

            $user = "masteradmin";

            $sql2 = "SELECT * FROM `office_stock` WHERE `stock_id`='$stock_id'";
            $result2 = mysqli_query($con, $sql2);
            $row2 = mysqli_fetch_assoc($result2);
            $stock_id = $row2['stock_id'];
            $stock_quantity = $row2['stock_quantity2'];
            $ratio = $row2['ratio'];

            $required_paper_full = $required_quantity * $nopage_c;

            $deduct_quantity = $required_paper_full * $print_side;

            $net_quantity = $stock_quantity - $deduct_quantity;
            $set_stock_quantity = $net_quantity / $ratio;



            $job_number = $new_value;
            $reason = $_POST['description'];

            $insert_log = "INSERT INTO office_stock_log(user_id, stock_id, status, last_quantity, added_removed, reason, jobnumber,customer) 
                    VALUES ('$user_id', '$stock_id', 'remove_quantity', '$stock_quantity', '$deduct_quantity', '$reason', '$job_number','$customer')";
            $result_log = mysqli_query($con, $insert_log);

            if ($result_log) {
                $stock_update = "UPDATE `office_stock` SET `stock_quantity2`='$net_quantity',`stock_quantity`='$set_stock_quantity' WHERE `stock_id` = '$stock_id'";
                $result_update = mysqli_query($con, $stock_update);
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
            $print_side = $row['print_side'];



            $user = "masteradmin";

            $sql2 = "SELECT * FROM `office_stock` WHERE `stock_id`='$stock_id'";
            $result2 = mysqli_query($con, $sql2);
            $row2 = mysqli_fetch_assoc($result2);
            $stock_id = $row2['stock_id'];
            $stock_quantity = $row2['stock_quantity2'];
            $ratio = $row2['ratio'];

            $required_paper_full = $required_quantity * $nopage_d;

            $deduct_quantity = $required_paper_full * $print_side;

            $net_quantity = $stock_quantity - $deduct_quantity;
            $set_stock_quantity = $net_quantity / $ratio;



            $job_number = $new_value;
            $reason = $_POST['description'];

            $insert_log = "INSERT INTO office_stock_log(user_id, stock_id, status, last_quantity, added_removed, reason, jobnumber,customer) 
                    VALUES ('$user_id', '$stock_id', 'remove_quantity', '$stock_quantity', '$deduct_quantity', '$reason', '$job_number','$customer')";
            $result_log = mysqli_query($con, $insert_log);

            if ($result_log) {
                $stock_update = "UPDATE `office_stock` SET `stock_quantity2`='$net_quantity',`stock_quantity`='$set_stock_quantity' WHERE `stock_id` = '$stock_id'";
                $result_update = mysqli_query($con, $stock_update);
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




        if ($type == 'brocher') {


            include('brocher.php');
        }

        if($type == 'bag'){

            include('bag.php');
        }


        if ($type == 'banner') {

            $unitbanner_type = $_POST['unitbanner_type'];
            $width = $_POST['width'];
            $length = $_POST['lengths'];
            $commitonprice = $_POST['commitonprice'];
            $cvat = $_POST['cvat'];
            $product = $_POST['product'];
            $banner_id = $_POST['banner_id'];

            $sql5 = "SELECT * FROM `unitbanner` WHERE `unitbanner_id`='$unitbanner_type'";
            $result5 = mysqli_query($con, $sql5);
            $row5 = mysqli_fetch_assoc($result5);
            // $care_lamination = $row5['care_banner'];
            $stock_ban_id = $row5['stock_id'];
            $unitbanner_outprice = $row5['outprice'];





            $total_care_banner = $quantity * $width * $length;
            $total_price = $unitbanner_outprice * $quantity * $width * $length + $commitonprice + (($cvat / 100) * $commitonprice);
            $total_price_vat = (($vat / 100) * $total_price) + $total_price;

            $unit_price = $total_price / $quantity;

            $current_date = date("Y-m-d");

            if ($product == 'outsource') {
                $add_banner_out = "INSERT INTO banner_out(customer, job_type, size, required_quantity, total_price, unit_price, total_price_vat, vat,cvat, width, lengths, commitonprice, date,totalkare,payment_status) 
        VALUES ('$customer', '$description', '$size', '$quantity', '$total_price', '$unit_price', '$total_price_vat', '$vat','$cvat', '$width', '$length', '$commitonprice', '$current_date','$unitbanner_outprice',1)";
                $result_add_out = mysqli_query($con, $add_banner_out);


                $update_payment = "UPDATE $type SET `payment_status`=1 WHERE `$primary_key` = '$banner_id'";
                $update = mysqli_query($con, $update_payment);
            } else {
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



                $insert_log = "INSERT INTO stock_log(user_id, stock_id, status, last_quantity, added_removed, reason, jobnumber,customer) 
                        VALUES ('$user_id', '$stock_ban_id', 'remove_quantity', '$total_lamination', '$total_care_banner', '$description', '$job_number','$customer')";
                $result_log = mysqli_query($con, $insert_log);



                if ($result_log) {
                    $stock_update = "UPDATE `stock` SET `total_lamination`='$net_care' WHERE `stock_id` = '$stock_ban_id'";

                    $result_update = mysqli_query($con, $stock_update);
                    //             $update_payment = "UPDATE `banner` SET `payment_status`=1 WHERE `banner_id` = '$banner_id'";
                    //    $update = mysqli_query($con, $update_payment);
                    echo $type;
                    echo $primary_key;





                    $update_payment = "UPDATE $type SET `payment_status`=1 WHERE `$primary_key` = '$banner_id'";
                    $update = mysqli_query($con, $update_payment);


                    $banner_metal = $_POST['banner_metal'];



                    if ($banner_metal == 'yes') {

                        $banner_metal_type = $_POST['banner_metal_type'];



                        $sql = "SELECT * FROM `banner_metal` WHERE `banner_metal_id`='$banner_metal_type'";
                        $result = mysqli_query($con, $sql);
                        $row = mysqli_fetch_assoc($result);
                        $stock_id = $row['stock_id'];


                        $user = "masteradmin";

                        $sql2 = "SELECT * FROM `stock` WHERE `stock_id`='$stock_id'";
                        $result2 = mysqli_query($con, $sql2);
                        $row2 = mysqli_fetch_assoc($result2);
                        $stock_id = $row2['stock_id'];
                        $stock_quantity = $row2['stock_quantity2'];
                        $ratio = $row2['ratio'];



                        $deduct_quantity = $quantity;

                        $net_quantity = $stock_quantity - $deduct_quantity;
                        $set_stock_quantity = $net_quantity / $ratio;



                        $job_number = $new_value;
                        $reason = $_POST['description'];

                        $insert_log = "INSERT INTO stock_log(user_id, stock_id, status, last_quantity, added_removed, reason, jobnumber,customer) 
                    VALUES ('$user_id', '$stock_id', 'remove_quantity', '$stock_quantity', '$deduct_quantity', '$reason', '$job_number','$customer')";
                        $result_log = mysqli_query($con, $insert_log);

                        if ($result_log) {
                            $stock_update = "UPDATE `stock` SET `stock_quantity2`='$net_quantity',`stock_quantity`='$set_stock_quantity' WHERE `stock_id` = '$stock_id'";
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




        $sql = "INSERT into `oli_invoice`(`invoice_no`,`project_id`,`customer_id`,`invoice_date`,`due_date`,`total_amount`,`status`) VALUES ('$new_value','$project_id','$managment_id','$date','$enddate','$total','1')";







        if ($result_add) {

            echo 'Job Number: ' . $new_value;
        } else {
            http_response_code(400);
            echo 'Unable to add payment';
            die();
        }
        break;
}

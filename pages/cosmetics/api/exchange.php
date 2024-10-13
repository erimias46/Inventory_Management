
<?php 

$current_date = date('Y-m-d');
$redirect_link = "../../../";
$side_link = "../../../";



include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php'; // Include your database connection
include_once $redirect_link . 'include/email.php';
include_once $redirect_link . 'include/bot.php';

    $user_id = $_SESSION['user_id']; 
    $sales_id = $_POST['sales_id'];

    
    $cosmetics_name = $_POST['cosmetics_name'];
    $size = $_POST['size'];
    $price = $_POST['price'];
    $cash = $_POST['cash'];
    $bank = $_POST['bank'];
    $method = $_POST['method'];
    $date = $_POST['date'];
    $quantity = $_POST['quantity'];
    if($bank == 0){
        $bank_name = null; 
        $bank_id = null;
    } else {
        $bank_name = $_POST['bank_name'];
        $sql="SELECT * FROM bankdb WHERE bankname = '$bank_name'";
        $result = mysqli_query($con, $sql);
        $row = mysqli_fetch_assoc($result);
        $bank_id = $row['id'];
    }
    
    
    

    if (!empty($cosmetics_name) && !empty($size)) {
        $sql = "SELECT * FROM cosmetics WHERE cosmetics_name = '$cosmetics_name' AND size = '$size'";
        $result = mysqli_query($con, $sql);
        $row = mysqli_fetch_assoc($result);

        if(!$row) {
            $sql="SELECT * FROM cosmetics WHERE cosmetics_name = '$cosmetics_name'";
            $result = mysqli_query($con, $sql);
            $row = mysqli_fetch_assoc($result);
            $price = $row['price'];
            $image = $row['image'];
            $type = $row['type'];
            $type_id = $row['type_id'];


            $sql2="SELECT * FROM cosmeticsdb WHERE size = '$size'";
            $result2 = mysqli_query($con, $sql2);
            $row2 = mysqli_fetch_assoc($result2);
            $size_id = $row2['id'];

            $quantity=$_POST['quantity'];

            $add_cosmetics = "INSERT INTO `cosmetics_verify`(`cosmetics_name`, `size`, `price`, `quantity`, `image`, `type`, `type_id`, `size_id`, `active`, `error`) 
                          VALUES ('$cosmetics_name', '$size', '$price', '$quantity', '$image', '$type', '$type_id', '$size_id', '0','1')";
            $result_add = mysqli_query($con, $add_cosmetics);

            if ($result_add) {
                echo "<script>window.location = 'action.php?status=error&redirect=sale_cosmetics.php'; </script>";
                
            }

            exit;

           

        }



            $cosmetics_id = $row['id'];
            $size_id = $row['size_id'];
            $current_quantity = $row['quantity'];

            if ($current_quantity < $quantity) {

                $sql="SELECT * FROM cosmetics WHERE cosmetics_name = '$cosmetics_name' AND size = '$size'";
                $result = mysqli_query($con, $sql);
                $row = mysqli_fetch_assoc($result);
                $price = $row['price'];
                $image = $row['image'];
                $type = $row['type'];
                $type_id = $row['type_id'];
                $size_id = $row['size_id'];

                $add_cosmetics = "INSERT INTO `cosmetics_verify`(`cosmetics_name`, `size`, `price`, `quantity`, `image`, `type`, `type_id`, `size_id`, `active`, `error`) 
                              VALUES ('$cosmetics_name', '$size', '$price', '$quantity', '$image', '$type', '$type_id', '$size_id', '0','2')";
                $result_add = mysqli_query($con, $add_cosmetics);

                if ($result_add) {
                    echo "<script>window.location = 'action.php?status=error&redirect=sale_cosmetics.php'; </script>";
                    exit;
                }

                exit;


            }




            $add_sales = "INSERT INTO `cosmetics_sales`(`cosmetics_id`, `size_id`, `cosmetics_name`, `size`, `price`, `cash`, `bank`, `method`, `sales_date`, `update_date`, `quantity`, `user_id`,`bank_id`,`bank_name`,`status`) 
                          VALUES ('$cosmetics_id', '$size_id', '$cosmetics_name', '$size', '$price', '$cash', '$bank', '$method', '$date', '$date', '$quantity', '$user_id','$bank_id','$bank_name','active')";
            $result_add = mysqli_query($con, $add_sales);

            $sales_id_new = mysqli_insert_id($con);



            $update_sales = "UPDATE `cosmetics_sales` SET `status` = 'Exchange Sell' WHERE sales_id = '$sales_id'";
            $result_update = mysqli_query($con, $update_sales);

            if($result_add && $result_update) {
                echo "<script>window.location = 'action.php?status=success&redirect=sale_cosmetics.php'; </script>";
            } else {
                echo "<script>window.location = 'action.php?status=error&redirect=sale_cosmetics.php'; </script>";
            }

            $status = "Exchange Sell";

            $add_cosmetics_log = "INSERT INTO `cosmetics_sales_log`(`cosmetics_id`, `size_id`, `cosmetics_name`, `size`, `price`, `cash`, `bank`, `method`, `sales_date`, `update_date`, `quantity`, `user_id`, `status`) 
                              VALUES ('$cosmetics_id', '$size_id', '$cosmetics_name', '$size', '$price', '$cash', '$bank', '$method', '$date', '$date', '$quantity', '$user_id', '$status')";
            $result_adds = mysqli_query($con, $add_cosmetics_log);

            $new_quantity = $current_quantity - $quantity;
            $update_quantity = "UPDATE cosmetics SET quantity = '$new_quantity' WHERE id = '$cosmetics_id' AND size = '$size'";
            $result_update = mysqli_query($con, $update_quantity);




    $message = "Exchange To cosmetics\n";
    $message .= "cosmetics Name: $cosmetics_name\n";
    $message .= "Size: $size\n";
    $message .= "Quantity: $quantity\n";
    $message .= "Price: $price\n";
    $message .= "Cash: $cash\n";
    $message .= "Bank: $bank\n";
    $message .= "Method: $method\n";
    $message .= "Date: $date\n";








            $sql="SELECT * from cosmetics_sales where sales_id = '$sales_id'";
            $result = mysqli_query($con, $sql);
            $row = mysqli_fetch_assoc($result);
            $cosmetics_id = $row['cosmetics_id'];
            $size_id = $row['size_id'];
            $quantity = $row['quantity'];
            $price = $row['price'];
            $cosmetics_name = $row['cosmetics_name'];
            $size = $row['size'];
            $cash = $row['cash'];
            $bank = $row['bank'];
            $method = $row['method'];
            $date = $row['sales_date'];
            $user_id = $row['user_id'];





            $sql2="SELECT * from cosmetics where id = '$cosmetics_id' AND size_id = '$size_id'";
            $result2 = mysqli_query($con, $sql2);
            $row2 = mysqli_fetch_assoc($result2);
            $current_quantity = $row2['quantity'];

            $new_quantity = $current_quantity + $quantity;
            $update_quantity = "UPDATE cosmetics SET quantity = '$new_quantity' WHERE id = '$cosmetics_id' AND size_id = '$size_id'";
            $result_update = mysqli_query($con, $update_quantity);


            $status = "Exchange Back";


            $add_cosmetics_log = "INSERT INTO `cosmetics_sales_log`(`cosmetics_id`, `size_id`, `cosmetics_name`, `size`, `price`, `cash`, `bank`, `method`, `sales_date`, `update_date`, `quantity`, `user_id`, `status`) 
                              VALUES ('$cosmetics_id', '$size_id', '$cosmetics_name', '$size', '$price', '$cash', '$bank', '$method', '$date', '$date', '$quantity', '$user_id', '$status')";

            $result_add = mysqli_query($con, $add_cosmetics_log);


            $sql="INSERT into exchange_cosmetics (before_sale_id, after_sale_id) VALUES ('$sales_id', '$sales_id_new')";
            $result = mysqli_query($con, $sql);




    $message .= "Exchange  From cosmetics\n";
    $message .= "cosmetics Name: $cosmetics_name\n";
    $message .= "Size: $size\n";
    $message .= "Quantity: $quantity\n";
    $message .= "Price: $price\n";
    $message .= "Cash: $cash\n";
    $message .= "Bank: $bank\n";
    $message .= "Method: $method\n";
    $message .= "Date: $date\n";


    $subject = "Exchange cosmetics";

    sendMessageToSubscribers($message, $con);
    sendEmailToSubscribers($message, $subject, $con);







            // if ($result_add && $result_update) {
            //     echo "<script>window.location = 'action.php?status=success&redirect=sale_cosmetics.php'; </script>";
            // } else {
            //     echo "<script>window.location = 'action.php?status=error&redirect=sale_cosmetics.php'; </script>";
            // }

    } 





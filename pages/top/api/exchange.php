
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

    
    $top_name = $_POST['top_name'];
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
    
    
    

    if (!empty($top_name) && !empty($size)) {
        $sql = "SELECT * FROM top WHERE top_name = '$top_name' AND size = '$size'";
        $result = mysqli_query($con, $sql);
        $row = mysqli_fetch_assoc($result);

        if(!$row) {
            $sql="SELECT * FROM top WHERE top_name = '$top_name'";
            $result = mysqli_query($con, $sql);
            $row = mysqli_fetch_assoc($result);
            $price = $row['price'];
            $image = $row['image'];
            $type = $row['type'];
            $type_id = $row['type_id'];


            $sql2="SELECT * FROM topdb WHERE size = '$size'";
            $result2 = mysqli_query($con, $sql2);
            $row2 = mysqli_fetch_assoc($result2);
            $size_id = $row2['id'];

            $quantity=$_POST['quantity'];

            $add_top = "INSERT INTO `top_verify`(`top_name`, `size`, `price`, `quantity`, `image`, `type`, `type_id`, `size_id`, `active`, `error`) 
                          VALUES ('$top_name', '$size', '$price', '$quantity', '$image', '$type', '$type_id', '$size_id', '0','1')";
            $result_add = mysqli_query($con, $add_top);

            if ($result_add) {
                echo "<script>window.location = 'action.php?status=error&redirect=sale_top.php'; </script>";
                
            }

            exit;

           

        }



            $top_id = $row['id'];
            $size_id = $row['size_id'];
            $current_quantity = $row['quantity'];

            if ($current_quantity < $quantity) {

                $sql="SELECT * FROM top WHERE top_name = '$top_name' AND size = '$size'";
                $result = mysqli_query($con, $sql);
                $row = mysqli_fetch_assoc($result);
                $price = $row['price'];
                $image = $row['image'];
                $type = $row['type'];
                $type_id = $row['type_id'];
                $size_id = $row['size_id'];

                $add_top = "INSERT INTO `top_verify`(`top_name`, `size`, `price`, `quantity`, `image`, `type`, `type_id`, `size_id`, `active`, `error`) 
                              VALUES ('$top_name', '$size', '$price', '$quantity', '$image', '$type', '$type_id', '$size_id', '0','2')";
                $result_add = mysqli_query($con, $add_top);

                if ($result_add) {
                    echo "<script>window.location = 'action.php?status=error&redirect=sale_top.php'; </script>";
                    exit;
                }

                exit;


            }




            $add_sales = "INSERT INTO `top_sales`(`top_id`, `size_id`, `top_name`, `size`, `price`, `cash`, `bank`, `method`, `sales_date`, `update_date`, `quantity`, `user_id`,`bank_id`,`bank_name`,`status`) 
                          VALUES ('$top_id', '$size_id', '$top_name', '$size', '$price', '$cash', '$bank', '$method', '$date', '$date', '$quantity', '$user_id','$bank_id','$bank_name','active')";
            $result_add = mysqli_query($con, $add_sales);

            $sales_id_new = mysqli_insert_id($con);



            $update_sales = "UPDATE `top_sales` SET `status` = 'Exchange Sell' WHERE sales_id = '$sales_id'";
            $result_update = mysqli_query($con, $update_sales);

            if($result_add && $result_update) {
                echo "<script>window.location = 'action.php?status=success&redirect=sale_top.php'; </script>";
            } else {
                echo "<script>window.location = 'action.php?status=error&redirect=sale_top.php'; </script>";
            }

            $status = "Exchange Sell";

            $add_top_log = "INSERT INTO `top_sales_log`(`top_id`, `size_id`, `top_name`, `size`, `price`, `cash`, `bank`, `method`, `sales_date`, `update_date`, `quantity`, `user_id`, `status`) 
                              VALUES ('$top_id', '$size_id', '$top_name', '$size', '$price', '$cash', '$bank', '$method', '$date', '$date', '$quantity', '$user_id', '$status')";
            $result_adds = mysqli_query($con, $add_top_log);

            $new_quantity = $current_quantity - $quantity;
            $update_quantity = "UPDATE top SET quantity = '$new_quantity' WHERE id = '$top_id' AND size = '$size'";
            $result_update = mysqli_query($con, $update_quantity);


    $message = "Exchange To top\n";
    $message .= "top Name: $top_name\n";
    $message .= "Size: $size\n";
    $message .= "Quantity: $quantity\n";
    $message .= "Price: $price\n";
    $message .= "Cash: $cash\n";
    $message .= "Bank: $bank\n";
    $message .= "Method: $method\n";
    $message .= "Date: $date\n";


            $sql="SELECT * from top_sales where sales_id = '$sales_id'";
            $result = mysqli_query($con, $sql);
            $row = mysqli_fetch_assoc($result);
            $top_id = $row['top_id'];
            $size_id = $row['size_id'];
            $quantity = $row['quantity'];
            $price = $row['price'];
            $top_name = $row['top_name'];
            $size = $row['size'];
            $cash = $row['cash'];
            $bank = $row['bank'];
            $method = $row['method'];
            $date = $row['sales_date'];
            $user_id = $row['user_id'];





            $sql2="SELECT * from top where id = '$top_id' AND size_id = '$size_id'";
            $result2 = mysqli_query($con, $sql2);
            $row2 = mysqli_fetch_assoc($result2);
            $current_quantity = $row2['quantity'];

            $new_quantity = $current_quantity + $quantity;
            $update_quantity = "UPDATE top SET quantity = '$new_quantity' WHERE id = '$top_id' AND size_id = '$size_id'";
            $result_update = mysqli_query($con, $update_quantity);


            $status = "Exchange Back";


            $add_top_log = "INSERT INTO `top_sales_log`(`top_id`, `size_id`, `top_name`, `size`, `price`, `cash`, `bank`, `method`, `sales_date`, `update_date`, `quantity`, `user_id`, `status`) 
                              VALUES ('$top_id', '$size_id', '$top_name', '$size', '$price', '$cash', '$bank', '$method', '$date', '$date', '$quantity', '$user_id', '$status')";

            $result_add = mysqli_query($con, $add_top_log);


            $sql="INSERT into exchange_top (before_sale_id, after_sale_id) VALUES ('$sales_id', '$sales_id_new')";
            $result = mysqli_query($con, $sql);



    $message .= "Exchange  From top\n";
    $message .= "top Name: $top_name\n";
    $message .= "Size: $size\n";
    $message .= "Quantity: $quantity\n";
    $message .= "Price: $price\n";
    $message .= "Cash: $cash\n";
    $message .= "Bank: $bank\n";
    $message .= "Method: $method\n";
    $message .= "Date: $date\n";


    $subject = "Exchange top";

    sendMessageToSubscribers($message, $con);
    sendEmailToSubscribers($message, $subject, $con);







            // if ($result_add && $result_update) {
            //     echo "<script>window.location = 'action.php?status=success&redirect=sale_top.php'; </script>";
            // } else {
            //     echo "<script>window.location = 'action.php?status=error&redirect=sale_top.php'; </script>";
            // }

    } 





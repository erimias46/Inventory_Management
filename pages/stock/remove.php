<?php 
    $redirect_link = "../../";
    $side_link = "../../";
    include_once '../../include/db.php';
    $current_date = date('Y-m-d');


    $id = $_GET['id'];
    $from = $_GET['from'];

    if (isset($id)) {

        if($from == 'stock'){

            $stock_id=$id;

        $sql = "SELECT `db` FROM `d_constants`";
        $result = $con->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $table_name = $row['db'];

                // Check if the table has the stock ID
                $check_sql = "SELECT * FROM `$table_name` WHERE `stock_id` = '$stock_id'";
                $check_result = $con->query($check_sql);

                if ($check_result->num_rows > 0) {
                    // Delete the stock ID from the table
                    $delete_sql = "DELETE FROM `$table_name` WHERE `stock_id` = '$stock_id'";
                    $con->query($delete_sql);
                }
            }
        }
            


        $remove = "DELETE FROM stock WHERE stock_id ='$id'";

        $remove_res = mysqli_query($con, $remove);
        if ($remove_res) {


            echo "<script>window.location.href='stock_managment.php?status=success';</script>";
        }
    
    }
        else if($from =='office_stock'){


        $stock_id = $id;

        $sql = "SELECT `db` FROM `d_constants`";
        $result = $con->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $table_name = $row['db'];

                // Check if the table has the stock ID
                $check_sql = "SELECT * FROM `$table_name` WHERE `stock_id` = '$stock_id'";
                $check_result = $con->query($check_sql);

                if ($check_result->num_rows > 0) {
                    // Delete the stock ID from the table
                    $delete_sql = "DELETE FROM `$table_name` WHERE `stock_id` = '$stock_id'";
                    $con->query($delete_sql);
                }
            }
        }
            $remove = "DELETE FROM office_stock WHERE stock_id ='$id'";

        $remove_res = mysqli_query($con, $remove);
        if ($remove_res) {


            echo "<script>window.location.href='office_stock.php?status=success';</script>";
        }
        }
        
    } else {
        echo "<script>window.location.href='../../index.php';</script>";
    }





?>
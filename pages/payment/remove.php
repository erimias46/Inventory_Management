<?php 
    $redirect_link = '../../';
   include_once $redirect_link . 'include/db.php';
include_once $redirect_link . 'include/mdb.php';
   $current_date = date('Y-m-d');

   session_start();
   $user_id = $_SESSION['user_id'];




    $id = $_GET['id'];
    $from = $_GET['from'];


    echo $id;
    echo $from;

    if (isset($id) && isset($from)) {
        if ($from == 'payment') {


        $sql = "SELECT * FROM payment WHERE payment_id='$id'";
        $res = mysqli_query($con, $sql);
        if (!$res) {
            die("Error in SELECT payment query: " . mysqli_error($con));
        }
        $row = mysqli_fetch_assoc($res);
        $job_number = $row['job_number'];
        $project_id = $row['project_id'];



        $sql="SELECT * FROM machine_run_log WHERE job_number='$job_number'";
        $res = mysqli_query($con, $sql);
        if (!$res) {
            die("Error in SELECT machine_run_log query: " . mysqli_error($con));
        }

        while ($row = mysqli_fetch_assoc($res)) {
            $machine_id = $row['machine_id'];
            $run_time = $row['count'];

            $sql = "SELECT * FROM machine_run WHERE id='$machine_id'";
            $machine_res = mysqli_query($con, $sql);
            if (!$machine_res) {
                die("Error in SELECT machine query: " . mysqli_error($con));
            }
            $machine_row = mysqli_fetch_assoc($machine_res);
            $machine_run_time = $machine_row['calc_count'];

            $machine_run_time -= $run_time;

            $sql = "UPDATE machine_run SET calc_count='$machine_run_time' WHERE id='$machine_id'";
            $update_res = mysqli_query($con, $sql);
            if (!$update_res) {
                die("Error in UPDATE machine query: " . mysqli_error($con));
            }


            $sql = "INSERT INTO `machine_run_log`(`machine_id`, `count`, `type`,`job_number`) VALUES ('$machine_id','$run_time','Reversed','$job_number')";
            $result = mysqli_query($con, $sql);

            if ($result) {
                echo "Data inserted into machine_run_log";
            } else {
                echo "Error inserting data into machine_run_log";
            }


        }


        $sql = "SELECT * FROM stock_log WHERE jobnumber='$job_number'";
        $res = mysqli_query($con, $sql);
        if (!$res) {
            die("Error in SELECT stock_log query: " . mysqli_error($con));
        }

        while ($row = mysqli_fetch_assoc($res)) {
            $stock_id = $row['stock_id'];
            $added_removed = $row['added_removed'];

            $sql = "SELECT * FROM stock WHERE stock_id='$stock_id'";
            $stock_res = mysqli_query($con, $sql);
            if (!$stock_res) {
                die("Error in SELECT stock query: " . mysqli_error($con));
            }
            $stock_row = mysqli_fetch_assoc($stock_res);
            $category = $stock_row['catagory'];

            if ($category == 'lamination') {
                $total_lamination = $stock_row['total_lamination'];

                
                $insert_log = "INSERT INTO stock_log(user_id, stock_id, status, last_quantity, added_removed, reason, jobnumber) 
                        VALUES ('$user_id', '$stock_id', 'add_quantity', '$total_lamination', '$added_removed', 'Reversed','')";
                $result_log = mysqli_query($con, $insert_log);

                if (!$result_log) {
                    die("Error in INSERT stock_log (lamination) query: " . mysqli_error($con));
                }
                $total_lamination += $added_removed;

                $sql = "UPDATE stock SET total_lamination='$total_lamination' WHERE stock_id='$stock_id'";
                $update_res = mysqli_query($con, $sql);
                if (!$update_res) {
                    die("Error in UPDATE stock (lamination) query: " . mysqli_error($con));
                }               
            } else {
                $stock_quantity2 = $stock_row['stock_quantity2'];
                $stock_quantity = $stock_row['stock_quantity'];
                $ratio = $stock_row['ratio'];

                $insert_log = "INSERT INTO stock_log(user_id, stock_id, status, last_quantity, added_removed, reason, jobnumber) 
                        VALUES ('$user_id', '$stock_id', 'add_quantity', '$stock_quantity2', '$added_removed', 'Reversed','')";
                $result_log = mysqli_query($con, $insert_log);

                if (!$result_log) {
                    die("Error in INSERT stock_log (general) query: " . mysqli_error($con));
                }
                $stock_quantity2 += $added_removed;
                $stock_quantity =$stock_quantity2/$ratio;

                $sql = "UPDATE stock SET stock_quantity2='$stock_quantity2', stock_quantity='$stock_quantity' WHERE stock_id='$stock_id'";
                $update_res = mysqli_query($con, $sql);
                if (!$update_res) {
                    die("Error in UPDATE stock (general) query: " . mysqli_error($con));
                }

               
            }
        }




        $sql = "SELECT * FROM office_stock_log WHERE jobnumber='$job_number'";
        $res = mysqli_query($con, $sql);
        if ($res) {



            $user_id=$_SESSION['user_id'];
           
        while ($row = mysqli_fetch_assoc($res)) {
            $stock_id = $row['stock_id'];
            $added_removed = $row['added_removed'];

            $sql = "SELECT * FROM office_stock WHERE stock_id='$stock_id'";
            $stock_res = mysqli_query($con, $sql);
            if (!$stock_res) {
                die("Error in SELECT stock query: " . mysqli_error($con));
            }
            $stock_row = mysqli_fetch_assoc($stock_res);
            
                $stock_quantity2 = $stock_row['stock_quantity2'];
                $stock_quantity = $stock_row['stock_quantity'];
                $ratio = $stock_row['ratio'];

                $insert_log = "INSERT INTO office_stock_log(user_id, stock_id, status, last_quantity, added_removed, reason, jobnumber) 
                        VALUES ('$user_id', '$stock_id', 'add_quantity', '$stock_quantity2', '$added_removed', 'Reversed','')";
                $result_log = mysqli_query($con, $insert_log);

                if (!$result_log) {
                    die("Error in INSERT office_stock_log (general) query: " . mysqli_error($con));
                }
                $stock_quantity2 += $added_removed;
                $stock_quantity = $stock_quantity2 / $ratio;

                $sql = "UPDATE office_stock SET stock_quantity2='$stock_quantity2', stock_quantity='$stock_quantity' WHERE stock_id='$stock_id'";
                $update_res = mysqli_query($con, $sql);
                if (!$update_res) {
                    die("Error in UPDATE stock (general) query: " . mysqli_error($con));
                }
            
        }
    }









        

        $delete_project="DELETE FROM oli_projects WHERE id='$project_id'";
        $delete_project_res = mysqli_query($conn, $delete_project);
        if (!$delete_project_res) {
            die("Error in DELETE project query: " . mysqli_error($con));
        }

        $delete_task="DELETE FROM oli_tasks WHERE project_id='$project_id'";
        $delete_task_res = mysqli_query($conn, $delete_task);
        if (!$delete_task_res) {
            die("Error in DELETE task query: " . mysqli_error($con));
        }



        $remove = "DELETE FROM payment WHERE payment_id='$id'";
        $remove_res = mysqli_query($con, $remove);
        if (!$remove_res) {
            die("Error in DELETE payment query: " . mysqli_error($con));
        }

        if ($remove_res && $res) {
            header("Location: record.php?id={$_GET['client']}&status=success &user_id=$ids");
            exit();
        }

        } elseif ($from == 'bank') {
            
            $remove = "DELETE FROM bank WHERE bank_id ='$id'";
            $remove_res = mysqli_query($con, $remove);
            if ($remove_res) {
                header("Location: bank_statment.php?status=success");
            }
        } else if($from=='bankdb'){
            $remove = "DELETE FROM bankdb WHERE id ='$id'";
            $remove_res = mysqli_query($con, $remove);
            if ($remove_res) {
                header("Location: add_bank.php?status=success");
            }
        }
        else if($from=='vat_dates'){
            $remove = "DELETE FROM compare WHERE id ='$id'";
            $remove_res = mysqli_query($con, $remove);
            if ($remove_res) {
                header("Location: vat_status.php?status=success");
            }
        }

        
    } else {

        echo "error";
        header("Location: ../../index.php");
    }



?>
<?php


include("../../../include/db.php");






$machine_type = $_POST['machine_type_page_a'];
$new_number = $_POST['machine_run_page_a'];
$new_number = ceil(floatval($new_number));

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


$machine_type= $_POST['machine_type_page_b'];
$new_number = $_POST['machine_run_page_b'];

$new_number = ceil(floatval($new_number));

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


$machine_type = $_POST['machine_type_page_c'];
$new_number = $_POST['machine_run_page_c'];

$new_number = ceil(floatval($new_number));

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



$machine_type = $_POST['machine_type_page_d'];
$new_number = $_POST['machine_run_page_d'];

$new_number = ceil(floatval($new_number));


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





?>
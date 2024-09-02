<?php
include '../../include/db.php';
$current_date = date('Y-m-d');
$id = $_GET['id'];

if (isset($_POST['add_data'])) {
    $db_name = mysqli_real_escape_string($con, $_POST['db_name']);
    $data = array();
    foreach ($_POST as $key => $value) {
        if ($key != 'add_data' && $key != 'db_name') {
            $data[$key] = mysqli_real_escape_string($con, $value);
        }
    }

    $fields = implode(',', array_keys($data));
    $values = "'" . implode("','", array_values($data)) . "'";
    $sql = "INSERT INTO $db_name ($fields) VALUES ($values)";

    $result = mysqli_query($con, $sql);

    if ($result) {
        echo "<script>window.location = 'action.php?id=$id&status=success&redirect=constant.php'; </script>";
    } else {
        echo mysqli_error($con);
    }
}

if (isset($_POST['update_data'])) {
    $db_name = mysqli_real_escape_string($con, $_POST['db_name']);
    $edit_key = mysqli_real_escape_string($con, $_POST['edit_id']);
    $data = array();
    foreach ($_POST as $key => $value) {
        if ($key != 'update_data' && $key != 'db_name' && $key != 'edit_id') {
            $data[$key] = mysqli_real_escape_string($con, $value);
        }
    }
    $set = '';
    foreach ($data as $key => $value) {
        $set .= $key . "='" . $value . "',";
    }
    $set = rtrim($set, ',');

    // Get primary key column name of the specified table
    $sql = "SHOW KEYS FROM $db_name WHERE Key_name = 'PRIMARY'";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);
    $primary_key = $row['Column_name'];

    $sql = "UPDATE $db_name SET $set WHERE $primary_key = '$edit_key'";
    $result = mysqli_query($con, $sql);

    if ($result) {
        echo "<script>window.location = 'action.php?id=$id&status=success&redirect=constant.php'; </script>";
    } else {
        echo mysqli_error($con);
    }
}

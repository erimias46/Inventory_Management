<?php
$redirect_link = "../../";
$side_link = "../../";
include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php';

$current_date = date('Y-m-d');



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $table = $_POST['table'];
    $code_name = $_POST['code_name'];

    // Mapping table names to their corresponding size databases
    $size_tables = [
        'jeans' => 'jeansdb',
        'shoes' => 'shoesdb',
        'complete' => 'completedb',
        'accessory' => 'accessorydb',
        'top' => 'topdb'
    ];

    if (array_key_exists($table, $size_tables)) {
        $size_table = $size_tables[$table];

        // Query to get sizes for the selected product
        $sql = "SELECT DISTINCT size FROM $size_table";
        $stmt = $con->prepare($sql);
       
        $stmt->execute();
        $result = $stmt->get_result();

        $sizes = [];
        while ($row = $result->fetch_assoc()) {
            $sizes[] = $row['size'];
        }

        echo json_encode($sizes);
    }
}

?>

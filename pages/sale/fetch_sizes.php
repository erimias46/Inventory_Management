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

        // Check if the table is 'jeans' to include the 'type' filter
        // Define the log function
        

        if ($table === 'jeans') {
            $size_table = $size_tables[$table];

           

            // Query to get type for selected code_name
            $type_sql = "SELECT * FROM jeans WHERE jeans_name = '$code_name'";
            $type_result = $con->query($type_sql);
            
          
            



            if ($type_result && $type_row = $type_result->fetch_assoc()) {
                $type = (int) $type_row['size_t'];
                

                // Query to get sizes for jeans with the specified type
                $sql = "SELECT DISTINCT size FROM $size_table WHERE type = $type";
                $result = $con->query($sql);

                $sizes = [];
                while ($row = $result->fetch_assoc()) {
                    $sizes[] = $row['size'];
                }

               

                echo json_encode($sizes);
            } else {

                echo json_encode(['error'=> 'No type found']);
            }
        }

 else {
            // Query to get sizes for other products without the type filter
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
}

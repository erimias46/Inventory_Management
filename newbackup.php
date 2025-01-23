<?php
$redirect_link = "";
$side_link = "";

include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php'; // Make sure this contains your database connection

// Handle backup request
if (isset($_POST['backup'])) {
    // Database connection parameters from your db.php
    $host = "localhost";
    $user = "root";
    $pass = "";
    $name = "inventory";
    
    // Create connection
    $conn = new mysqli($host, $user, $pass, $name);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Set headers for download
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . $name . '_backup_' . date('Y-m-d_H-i') . '.sql"');
    
    // Get all table names
    $tables = array();
    $result = $conn->query("SHOW TABLES");
    while ($row = $result->fetch_row()) {
        $tables[] = $row[0];
    }
    
    // Initialize output
    $output = '';
    
    foreach ($tables as $table) {
        // Add table structure
        $output .= "--\n-- Table structure for table `$table`\n--\n\n";
        $output .= "DROP TABLE IF EXISTS `$table`;\n";
        $create = $conn->query("SHOW CREATE TABLE `$table`")->fetch_row();
        $output .= $create[1] . ";\n\n";
        
        // Add table data
        $output .= "--\n-- Dumping data for table `$table`\n--\n\n";
        $rows = $conn->query("SELECT * FROM `$table`");
        $numColumns = $rows->field_count;
        
        while ($row = $rows->fetch_row()) {
            $output .= "INSERT INTO `$table` VALUES(";
            for ($i = 0; $i < $numColumns; $i++) {
                $row[$i] = addslashes($row[$i]);
                $row[$i] = preg_replace("/\n/", "\\n", $row[$i]);
                if (isset($row[$i])) {
                    $output .= "'" . $row[$i] . "'";
                } else {
                    $output .= "NULL";
                }
                if ($i < ($numColumns - 1)) {
                    $output .= ",";
                }
            }
            $output .= ");\n";
        }
        $output .= "\n";
    }
    
    // Close connection
    $conn->close();
    
    // Output the backup content and exit
    echo $output;
    exit();
}

header('Access-Control-Allow-Origin: *');
?>

<head>
    <?php
    $title = 'Database Backup';
    include $redirect_link . 'partials/title-meta.php'; ?>
    <?php include $redirect_link . 'partials/head-css.php'; ?>
</head>

<body>
    <div class="flex wrapper">
        <?php include $redirect_link . 'partials/menu.php'; ?>

        <div class="page-content">
            <?php include $redirect_link . 'partials/topbar.php'; ?>

            <main class="flex-grow p-6">
                <div class="card">
                    <div class="card-header">
                        <div class="flex justify-between items-center">
                            <h4 class="text-slate-900 dark:text-slate-200 text-lg font-medium">Database Backup</h4>
                            <form method="post">
                                <button type="submit" class="btn" name="backup">
                                    <span class="menu-icon">
                                        <i class="msr">download</i>
                                    </span>
                                    Generate Backup
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="text-gray-600 dark:text-gray-300">
                            Click the button below to generate and download a full database backup.
                        </p>
                    </div>
                </div>
            </main>

            <?php include $redirect_link . 'partials/footer.php'; ?>
        </div>
    </div>

    <?php include $redirect_link . 'partials/customizer.php'; ?>
    <?php include $redirect_link . 'partials/footer-scripts.php'; ?>
</body>
</html>
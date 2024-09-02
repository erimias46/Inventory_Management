<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */

// DB table to use
$table = 'banner';

// Table's primary key
$primaryKey = 'banner_id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
    array('db' => 'banner_id', 'dt' => 0),
    array('db' => 'customer', 'dt' => 1),
    array('db' => 'job_type', 'dt' => 2),
    array('db' => 'size', 'dt' => 3),
    array(
        'db' => 'date',
        'dt' => 4,
        'formatter' => function ($d, $row) {
            return date('jS M y', strtotime($d));
        }
    ),
    array(
        'db' => 'total_price',
        'dt' => 5,
        'formatter' => function ($d, $row) {
            return '$' . number_format($d);
        }
    )
);

// SQL server connection information
$sql_details = array(
    'user' => 'root',
    'pass' => '',
    'db' => 'fgsystemnet_elegant',
    'host' => 'localhost'
    // ,'charset' => 'utf8' // Depending on your PHP and MySQL config, you may need this
);


/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */

require('../ssp.class.php');

echo json_encode(
    SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns)
);
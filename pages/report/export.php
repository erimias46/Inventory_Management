<?php
include '../../include/db.php';

if (!$con) {
    echo "connection error";
} else {
    header('Content-Type:application/xls');
    header('Content-Disposition:attachment;filename=report.xls');
    $sql = "";
    if (isset($_GET['file'])) {
        $file = $_GET['file'];
        if ($file == "stock") {
            $from_date = !empty($_GET['from']) ? $_GET['from'] : null;
            $to_date = !empty($_GET['to']) ? $_GET['to'] : null;

            $type = '';
            if (!empty($_GET['type'])) {
                $get_type = $_GET['type'];
                $type = "stock_id = '$get_type'";
            }

            // Build the SQL query based on the presence of dates and type
            $sql = "SELECT * FROM stock_log";
            $conditions = [];

            if ($from_date && $to_date) {
                $conditions[] = "DATE(created_at) >= '$from_date' AND DATE(created_at) <= '$to_date'";
            }

            if ($type) {
                $conditions[] = $type;
            }

            if (count($conditions) > 0) {
                $sql .= " WHERE " . implode(' AND ', $conditions);
            }

            file_put_contents('debug.txt', $sql . PHP_EOL, FILE_APPEND);

            $html = '
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Type</th>
                        <th>User</th>
                        <th>Last Quantity</th>
                        <th>Add/Remove</th>
                        <th>Reason</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
        ';

            $result = mysqli_query($con, $sql);
            while ($row = mysqli_fetch_assoc($result)) {
                // Fetch username
                $user_id = $row['user_id'];
                $sql0 = "SELECT * FROM user WHERE user_id = '$user_id'";
                $result0 = mysqli_query($con, $sql0);
                $row0 = mysqli_fetch_assoc($result0);
                $user_name = isset($row0['user_name']) ? $row0['user_name'] : "";

                // Fetch stock type
                $stock_id = $row['stock_id'];
                $sql1 = "SELECT * FROM stock WHERE stock_id = '$stock_id'";
                $result1 = mysqli_query($con, $sql1);
                $row1 = mysqli_fetch_assoc($result1);
                $stock_type = isset($row1['stock_type']) ? $row1['stock_type'] : "";

                // Determine add/remove status
                $status = $row['status'];
                $added_removed0 = $row['added_removed'];
                $added_removed = ($status == "add_quantity") ? "+$added_removed0" : "-$added_removed0";

                // Build the table row
                $html .= '
                <tr>
                    <td>' . $row['log_id'] . '</td>
                    <td>' . $stock_type . '</td>
                    <td>' . $user_name . '</td>
                    <td>' . $row['last_quantity'] . '</td>
                    <td>' . $added_removed . '</td>
                    <td>' . $row['reason'] . '</td>
                    <td>' . $row['created_at'] . '</td>
                </tr>
            ';
            }

            $html .= '</tbody></table>';
            echo $html;
        } elseif ($file == "payment") {
            // Initialize $from_date and $to_date variables
            $from_date = '';
            $to_date = '';

            // Check if from_date and to_date are provided
            if (!empty($_GET['from']) && !empty($_GET['to'])) {
                $from_date = $_GET['from'];
                $to_date = $_GET['to'];
            }

            $client = '';
            if (!empty($_GET['client'])) {
                $get_client = $_GET['client'];
                $client = "client = '$get_client'";
            } else {
                $client = '';
            }

            // Build the SQL query based on whether date filters are provided
            if (empty($from_date) || empty($to_date)) {
                // No date filter provided
                if (!$client) {
                    $sql = "SELECT * FROM payment";
                } else {
                    $sql = "SELECT * FROM payment WHERE {$client}";
                }
            } else {
                // Date filter provided
                if (!$client) {
                    $sql = "SELECT * FROM payment WHERE DATE(date) >= '$from_date' AND DATE(date) <= '$to_date'";
                } else {
                    $sql = "SELECT * FROM payment WHERE DATE(date) >= '$from_date' AND DATE(date) <= '$to_date' AND {$client}";
                }
            }

            // Initialize HTML table structure
            $html = '
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Job Number</th>
                    <th>User</th>
                    <th>Client</th>
                    <th>Job Description</th>
                    <th>Size</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Advance</th>
                    <th>Remainder</th>
                    <th>Total</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
    ';

            // Execute the query and populate the table
            $result = mysqli_query($con, $sql);
            while ($row = mysqli_fetch_assoc($result)) {
                // Get username based on user_id
                $user_id = $row['user_id'];
                $sql0 = "SELECT * FROM user WHERE user_id = '$user_id'";
                $result0 = mysqli_query($con, $sql0);
                $row0 = mysqli_fetch_assoc($result0);
                $user_name = isset($row0['user_name']) ? $row0['user_name'] : '';

                // Add table row
                $html .= '
            <tr>
                <td>' . $row['payment_id'] . '</td>
                <td>' . $row['job_number'] . '</td>
                <td>' . $user_name . '</td>
                <td>' . $row['client'] . '</td>
                <td>' . $row['job_description'] . '</td>
                <td>' . $row['size'] . '</td>
                <td>' . $row['quantity'] . '</td>
                <td>' . $row['unit_price'] . '</td>
                <td>' . $row['advance'] . '</td>
                <td>' . $row['remained'] . '</td>
                <td>' . $row['total'] . '</td>
                <td>' . $row['date'] . '</td>
            </tr>
        ';
            }

            $html .= '</tbody>
        </table>
    ';

        echo $html;
        } elseif ($file == "bank") {
            // Get the client type
           

            // Initialize conditions array
            $conditions = [];

            // Check if from_date and to_date are set and not empty
            if (!empty($_GET['from']) && !empty($_GET['to'])) {
                $from_date = $_GET['from'];
                $to_date = $_GET['to'];
                $conditions[] = "DATE(date) >= '$from_date' AND DATE(date) <= '$to_date'";
            }

            // Check if type (client) is set and not empty
            if (!empty($_GET['client'])) {
                $get_type = $_GET['client'];
                $conditions[] = "client = '$get_type'";

                
            file_put_contents('debug.txt', "Client type selected: $get_type\n  , From date   : $from_date  ", FILE_APPEND);

            
            }

            // Build the base SQL query
            $sql = "SELECT * FROM bank";

            // Add conditions to the SQL query if any exist
            if (!empty($conditions)) {
                $sql .= " WHERE " . implode(' AND ', $conditions);
            }

            // Start generating the HTML table
            $html = '
                 <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Client</th>
                    <th>Bank Name</th>
                    <th>Reference</th>
                    <th>Amount</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
    ';

            // Execute the query
            $result = mysqli_query($con, $sql);

            // Check if the query returned any results
            if (mysqli_num_rows($result) > 0) {
                // Loop through the results and add rows to the table
                while ($row = mysqli_fetch_assoc($result)) {
                    $html .= '
                <tr>
                    <td>' . $row['bank_id'] . '</td>
                    <td>' . $row['client'] . '</td>
                    <td>' . $row['name'] . '</td>
                    <td>' . $row['reference_number'] . '</td>
                    <td>' . $row['amount'] . '</td>
                    <td>' . $row['date'] . '</td>
                </tr>
            ';
                }
            } else {
                // If no results are found, display a message
                $html .= '
            <tr>
                <td colspan="6">No records found for the specified criteria.</td>
            </tr>
        ';
            }

            // Close the table and output the HTML
            $html .= '</tbody>
        </table>
    ';
            echo $html;



        } elseif ($file == "expense") {
        }
    }
}

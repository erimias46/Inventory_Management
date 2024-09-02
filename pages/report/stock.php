<?php
$redirect_link = "../../";
$side_link = "../../";

include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php';
?>

<head>
    <?php
    $title = 'Stock Report';
    include $redirect_link . 'partials/title-meta.php'; ?>

    <?php include $redirect_link . 'partials/head-css.php'; ?>
</head>

<body>

    <!-- Begin page -->
    <div class="flex wrapper">

        <?php include $redirect_link . 'partials/menu.php'; ?>

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="page-content">

            <?php include $redirect_link . 'partials/topbar.php'; ?>

            <main class="flex-grow p-6">
                <div class="card">
                    <div class="card-header">
                        <p class="text-sm text-gray-500 dark:text-gray-500">
                            Filter
                        </p>
                    </div>

                    <div class="p-6">
                        <form method="POST">
                            <div class="flex flex-wrap">
                                <div class="me-3">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2">
                                        From Date </label>
                                    <input type="date" name="from_date" class="form-input" value="<?php if (isset($_GET['from'])) echo $_GET['from']; ?>">

                                </div>
                                <div class="me-3">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2">
                                        To Date </label>
                                    <input type="date" name="to_date" class="form-input" value="<?php if (isset($_GET['to'])) echo $_GET['to']; ?>">

                                </div>
                                <div class="me-3">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2">
                                        Stock Type
                                    </label>
                                    <select class="search-select" name="type">
                                        <option value="">Select Stock Type</option>
                                        <?php
                                        $sql = "SELECT * FROM stock GROUP BY stock_type";
                                        $result = mysqli_query($con, $sql);
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            // Check if the current stock type should be selected
                                            $selected = isset($_GET['type']) && $_GET['type'] == $row['stock_id'] ? 'selected' : '';
                                        ?>
                                            <option value="<?php echo $row['stock_id']; ?>" <?php echo $selected; ?>>
                                                <?php echo $row['stock_type']; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>

                            </div>
                            <div class="flex justify-end">
                                <a href="stock.php" class="btn btn-sm bg-danger text-white rounded-full me-2">
                                    <i class="msr text-base me-2">restart_alt</i>
                                    Reset</a>
                                <button name="filter" type="submit" class="btn btn-sm bg-success text-white rounded-full">
                                    <i class="msr text-base me-2">filter_list</i>
                                    Filter</button>
                            </div>
                        </form>
                    </div>
                </div>
                <?php
                $from = isset($_GET['from']) ? $_GET['from'] : "1000-00-01";
                $to = isset($_GET['to']) ? $_GET['to'] : "3000-01-01";
                $type = isset($_GET['type']) ? $_GET['type'] : '';




                $link = "export.php?file=stock";
                if ($from && $to) {
                    $link .= "&from=$from&to=$to";
                }
                if ($type) {
                    $link .= "&type=$type";
                }


                ?>

                <div class="card mt-3">
                    <div class="card-header">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-bold text-gray-800 dark:text-white">
                                Stock Report
                            </h3>
                            <a href="<?php echo $link; ?>" class="btn btn-sm rounded-full bg-success/25 text-success hover:bg-success hover:text-white">
                                <i class="msr text-base me-2">picture_as_pdf</i> Export</a>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <div class="min-w-full inline-block align-middle">
                                <div class="overflow-hidden">
                                    <table id="myTable" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead>
                                            <tr>
                                                <th>Log ID</th>
                                                <th>User</th>
                                                <th>Stock Type</th>
                                                <th>Last Quantity</th>
                                                <th>Added / Removed</th>
                                                <th>Status</th>
                                                <th>Reason</th>
                                                <th>Date</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $from_date = empty($_GET['from']) ? "1000-01-01" : $_GET['from'];
                                            $to_date = empty($_GET['to']) ? "3000-01-01" : $_GET['to'];

                                            $type = '';
                                            if (!empty($_GET['type'])) {
                                                $get_type = $_GET['type'];
                                                $type = "stock_id = '$get_type'";
                                            } else {
                                                $type = '';
                                            }

                                            if (!$type) {
                                                $sql = "
        (SELECT *, 'stock_log' AS source_table FROM stock_log WHERE created_at >= '$from_date' AND created_at <= '$to_date')
        UNION
        (SELECT *, 'office_stock_log' AS source_table FROM office_stock_log WHERE created_at >= '$from_date' AND created_at <= '$to_date')
        ORDER BY log_id DESC";
                                            } else {
                                                $sql = "
        (SELECT *, 'stock_log' AS source_table FROM stock_log WHERE created_at >= '$from_date' AND created_at <= '$to_date' AND {$type})
        UNION
        (SELECT *, 'office_stock_log' AS source_table FROM office_stock_log WHERE created_at >= '$from_date' AND created_at <= '$to_date' AND {$type})
        ORDER BY log_id DESC";
                                            }

                                            $result = mysqli_query($con, $sql);
                                            while ($row = mysqli_fetch_assoc($result)) {
                                            ?>
                                                <tr class="odd:bg-white even:bg-gray-100 dark:odd:bg-slate-700 dark:even:bg-slate-800">
                                                    <td class="px-6 90-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['log_id']; ?>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php
                                                        $user_id = $row['user_id'];
                                                        $sql0 = "SELECT * FROM user WHERE user_id = '$user_id'";
                                                        $result0 = mysqli_query($con, $sql0);
                                                        $row0 = mysqli_fetch_assoc($result0);
                                                        echo $row0['user_name'];
                                                        ?>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php
                                                        $stock_id = $row['stock_id'];
                                                        $source_table = $row['source_table'];
                                                        if ($source_table == 'stock_log') {
                                                            $sql0 = "SELECT * FROM stock WHERE stock_id = '$stock_id'";
                                                        } else {
                                                            $sql0 = "SELECT * FROM office_stock WHERE stock_id = '$stock_id'";
                                                        }
                                                        $result0 = mysqli_query($con, $sql0);
                                                        $row0 = mysqli_fetch_assoc($result0);
                                                        echo $row0['stock_type'];
                                                        ?>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['last_quantity']; ?>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php
                                                        $status = $row['status'];
                                                        $added_removed0 = $row['added_removed'];
                                                        if ($status == "add_quantity") {
                                                            $added_removed = "+$added_removed0";
                                                        } else {
                                                            $added_removed = "-$added_removed0";
                                                        }
                                                        echo $added_removed;
                                                        ?>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php
                                                        if ($status == "add_quantity") {
                                                        ?>
                                                            <span class="text-success font-bold uppercase">Qty Added</span>
                                                        <?php
                                                        } else {
                                                        ?>
                                                            <span class="text-danger font-bold uppercase">Qty Removed</span>
                                                        <?php
                                                        }
                                                        ?>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['reason']; ?>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['created_at']; ?>
                                                    </td>
                                                </tr>
                                            <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </main>

            <?php include $redirect_link . 'partials/footer.php'; ?>

        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->

    </div>

    <?php include $redirect_link . 'partials/customizer.php'; ?>

    <?php include $redirect_link . 'partials/footer-scripts.php'; ?>

    <link href="https://cdn.datatables.net/v/dt/jq-3.7.0/dt-1.13.6/datatables.min.css" rel="stylesheet">

    <script src="https://cdn.datatables.net/v/dt/jq-3.7.0/dt-1.13.6/datatables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({
                order: []

            });
        });
    </script>

</body>

</html>

<?php






if (isset($_POST['filter'])) {
    $from = $_POST['from_date'];
    $to = $_POST['to_date'];
    $type = $_POST['type'];
    echo "<script>window.location = 'stock.php?from=$from&to=$to&type=$type'; </script>";
}



?>
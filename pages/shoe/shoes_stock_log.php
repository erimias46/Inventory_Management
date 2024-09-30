<?php
$redirect_link = "../../";
$side_link = "../../";

include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php';


$from_date = '';
$to_date = '';
$stock = '';
?>

<head>
    <?php
    $title = 'Stock Log';
    include $redirect_link . 'partials/title-meta.php'; ?>

    <?php include $redirect_link . 'partials/head-css.php'; ?>
    <link href="../../assets/libs/nice-select2/css/nice-select2.css" rel="stylesheet" type="text/css">
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
                            <p class="mt-2 text-gray-800 dark:text-gray-400">
                            <div class="flex gap-1 ">
                                <div class="mb-2">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-1">
                                        From Date
                                    </label>
                                    <input type="date" name="from_date" class="form-input">
                                </div>
                                <div class="mb-2">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-1">
                                        To Date
                                    </label>
                                    <input type="date" name="to_date" class="form-input">
                                </div>
                                <div class="mb-2">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-1">
                                        Select Stock Type
                                    </label>
                                    <select class="form-input" name="stock_type">
                                        <option value="all">All</option>
                                        <?php
                                        $sql = "SELECT * FROM shoes_sales_log GROUP BY shoes_name";
                                        $result = mysqli_query($con, $sql);
                                        while ($row = mysqli_fetch_assoc($result)) {
                                        ?>
                                            <option value="<?php echo $row['shoes_name']; ?>">
                                                <?php echo $row['shoes_name']; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            </p>
                            <a class="inline-flex items-center gap-2 mt-5 text-sm font-medium text-primary hover:text-sky-700" href="#">
                                <a href="shoes_stock_log.php" class="btn bg-danger text-white"><i class="fas fa-undo-alt"></i> Reset</a>
                                <button name="filter" type="submit" class="btn bg-success text-white"><i class="fab fa-gitter"></i> Filter</button>
                            </a>
                        </form>
                    </div>
                </div>
                <div class="card mt-3">
                    <div class="card-header">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-bold text-gray-800 dark:text-white">
                                Stock Log
                            </h3>
                            <div>
                                <form method="post">
                                    <?php
                                    $stock = isset($_GET['stock']) ? $_GET['stock'] : '';
                                    $from_date = isset($_GET['from_date']) ? $_GET['from_date'] : '';
                                    $to_date = isset($_GET['to_date']) ? $_GET['to_date'] : '';

                                    $link = "export.php";

                                    // If a stock type is selected, add it to the link
                                    if (!empty($stock)) {
                                        $link .= "?type=$stock";
                                    }

                                    // If "From Date" and "To Date" are set, add them to the link
                                    if (!empty($from_date) && !empty($to_date)) {
                                        // Check if the link already contains a query parameter
                                        $link .= (strpos($link, '?') !== false) ? "&" : "?";
                                        $link .= "from_date=$from_date&to_date=$to_date";
                                    }

                                    // Generate the export link only if there's at least one filter

                                    ?>
                                    <a href="<?php echo $link; ?>" class="btn btn-sm rounded-full bg-success/25 text-success hover:bg-success hover:text-white">
                                        <i class="msr text-base me-2">picture_as_pdf</i> Export</a>
                                    <?php  ?>

                                </form>

                            </div>

                            <div>

                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <div class="min-w-full inline-block align-middle">
                                <div class="overflow-hidden">
                                    <table id="myTable" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead>
                                            <tr>

                                                <th>#</th>
                                                <th>User</th>
                                                <th>shoes Name</th>
                                                <th>Size</th>
                                                <th>Total Price</th>
                                                <th>Method</th>

                                                <th>Add/Remove</th>
                                                <th>Log Type</th>

                                                <th>Date</th>
                                                <th>Status</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $from_date = isset($_GET['from_date']) ? $_GET['from_date'] : '';
                                            $to_date = isset($_GET['to_date']) ? $_GET['to_date'] : '';
                                            $stock_type = isset($_GET['stock']) ? $_GET['stock'] : 'all';

                                            $conditions = [];

                                            if ($from_date && $to_date) {
                                                // Ensure that the date format in the condition matches the database format
                                                $conditions[] = "DATE(created_at) BETWEEN '$from_date' AND '$to_date'";
                                            }

                                            if ($stock_type != 'all') {
                                                $conditions[] = "shoes_name = '$stock_type'";
                                            }

                                            $whereClause = '';
                                            if (count($conditions) > 0) {
                                                $whereClause = 'WHERE ' . implode(' AND ', $conditions);
                                            }

                                            $sql = "SELECT * FROM shoes_sales_log $whereClause ORDER BY sales_id DESC";
                                            $result = mysqli_query($con, $sql);
                                            while ($row = mysqli_fetch_assoc($result)) {
                                            ?>
                                                <tr class="odd:bg-white even:bg-gray-100 dark:odd:bg-slate-700 dark:even:bg-slate-800">


                                                    <td class="px-6 90-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['sales_id']; ?></td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php
                                                        $user_id = $row['user_id'];
                                                        $sql0 = "SELECT * FROM user WHERE user_id = '$user_id'";
                                                        $result0 = mysqli_query($con, $sql0);
                                                        $row0 = mysqli_fetch_assoc($result0);
                                                        echo isset($row0['user_name']) ? $row0['user_name'] : 'unknown';

                                                        ?></td>

                                                    <td class="px-6 90-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['shoes_name']; ?></td>

                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php

                                                        echo $row['size'];
                                                        ?></td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php
                                                        echo $row['price'];


                                                        ?></td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['method']; ?></td>

                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php
                                                        $status = $row['status'];
                                                        $added_removed0 = $row['quantity'];
                                                        if ($status == "add_quantity" || $status == "Refund"  || $status == "Exchange Back"  || $status == "DELIVERY CANCELED" || $status =="SELL DELETED") {
                                                            $added_removed = "+$added_removed0";
                                                        } else {
                                                            $added_removed = "-$added_removed0";
                                                        }
                                                        echo $added_removed;
                                                        ?></td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php
                                                        $status = $row['status'];
                                                        if ($status == "add_quantity" || $status == "Refund"  || $status == "Exchange Back"  || $status == "DELIVERY CANCELED"  || $status =="SELL DELETED") {
                                                        ?>
                                                            <span class="btn bg-success">Quantity Added</span>
                                                        <?php
                                                        } else {
                                                        ?>
                                                            <span class="btn bg-danger">Quantity Removed</span>
                                                        <?php
                                                        }
                                                        ?>
                                                    </td>

                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['sales_date']; ?></td>

                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['status']; ?></td>






                                                </tr>
                                                <!-- Edit modal -->

                                            <?php } ?>
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
    $stock_type = $_POST['stock_type'];
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];

    echo "<script>window.location = 'shoes_stock_log.php?from_date=$from_date&to_date=$to_date&stock=$stock_type'; </script>";
}





?>

<script src="../../assets/libs/nice-select2/js/nice-select2.js"></script>

<!-- Choices Demo js -->
<script src="../../assets/js/pages/form-select.js"></script>
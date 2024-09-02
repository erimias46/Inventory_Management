<?php
$redirect_link = "";
$side_link = "";

include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php';


$from_date = '';
$to_date = '';
$stock = '';
?>

<head>
    <?php
    $title = 'Machine Run Log';
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
                <!-- <div class="card">
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
                                        $sql = "SELECT * FROM stock GROUP BY stock_type";
                                        $result = mysqli_query($con, $sql);
                                        while ($row = mysqli_fetch_assoc($result)) {
                                        ?>
                                            <option value="<?php echo $row['stock_id']; ?>">
                                                <?php echo $row['stock_type']; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            </p>
                            <a class="inline-flex items-center gap-2 mt-5 text-sm font-medium text-primary hover:text-sky-700" href="#">
                                <a href="stock_log.php" class="btn bg-danger text-white"><i class="fas fa-undo-alt"></i> Reset</a>
                                <button name="filter" type="submit" class="btn bg-success text-white"><i class="fab fa-gitter"></i> Filter</button>
                            </a>
                        </form>
                    </div>
                </div> -->
                <div class="card mt-3">
                    <div class="card-header">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-bold text-gray-800 dark:text-white">
                                Machine Run Log
                            </h3>
                            <div>
                                <!-- <form method="post">
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

                                </form> -->

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
                                                <th>Machine Name</th>
                                                <th>Type</th>
                                                <th>Count</th>
                                                <th>Job Number</th>
                                                <th>Date</th>
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
                                                $conditions[] = "stock_id = '$stock_type'";
                                            }

                                            $whereClause = '';
                                            if (count($conditions) > 0) {
                                                $whereClause = 'WHERE ' . implode(' AND ', $conditions);
                                            }

                                            $sql = "SELECT * FROM machine_run_log $whereClause ORDER BY id DESC";
                                            $result = mysqli_query($con, $sql);
                                            while ($row = mysqli_fetch_assoc($result)) {
                                            ?>
                                                <tr class="odd:bg-white even:bg-gray-100 dark:odd:bg-slate-700 dark:even:bg-slate-800">


                                                    <td class="px-6 90-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['id']; ?></td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php
                                                        $machine_id = $row['machine_id'];
                                                        $sql0 = "SELECT * FROM machine_run WHERE id = '$machine_id'";
                                                        $result0 = mysqli_query($con, $sql0);
                                                        $row0 = mysqli_fetch_assoc($result0);
                                                        echo isset($row0['type']) ? $row0['type'] : 'unknown';

                                                        ?></td>

                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php

                                                        echo $row['type'];
                                                        ?></td>

                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['count']; ?></td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['job_number']; ?></td>

                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['date']; ?></td>




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

                <div id="addModal" class="w-full h-full fixed top-0 left-0 z-50 transition-all duration-500 hidden">
                    <div class="fc-modal-open:mt-7 fc-modal-open:opacity-100 fc-modal-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto  bg-white border shadow-sm rounded-md dark:bg-slate-800 dark:border-gray-700">
                        <div class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
                            <h3 class="font-medium text-gray-800 dark:text-white text-lg">
                                Add Purchase
                            </h3>
                            <button class="inline-flex flex-shrink-0 justify-center items-center h-8 w-8 dark:text-gray-200" data-fc-dismiss type="button">
                                <span class="material-symbols-rounded">close</span>
                            </button>
                        </div>
                        <form method="POST">
                            <div class="px-4 py-8 overflow-y-auto">


                                <div class="flex gap-2 justify-between">
                                    <div class="mb-3">
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">
                                            Customer Name </label>
                                        <input type="text" name="customer" class="form-input" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">
                                            Recipt Number </label>
                                        <input type="text" name="receipt_number" class="form-input" required>
                                    </div>
                                </div>



                                <div class="flex gap-2 justify-between">
                                    <div class="mb-3">
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">Tin
                                            Number</label>
                                        <input type="text" name="tin_number" class="form-input" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">Price
                                            Before Vat</label>
                                        <input type="text" name="price_before_vat" class="form-input" required>
                                    </div>

                                </div>
                                <div class="flex gap-2 justify-between">
                                    <div class="mb-3">
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">
                                            Date </label>
                                        <input type="date" name="date" class="form-input" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">
                                            Vat </label>
                                        <input type="text" name="vat" class="form-input" required>
                                    </div>
                                </div>

                                <div class="flex gap-2 justify-between">
                                    <div class="mb-3">
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">
                                            Machine Number </label>
                                        <input type="text" name="machine_number" class="form-input" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">
                                            Holding Tax </label>
                                        <input type="text" name="holding_tax" class="form-input" required>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-end items-center gap-4 p-4 border-t dark:border-slate-700">
                                <button class="py-2 px-5 inline-flex justify-center items-center gap-2 rounded dark:text-gray-200 border dark:border-slate-700 font-medium hover:bg-slate-100 hover:dark:bg-slate-700 transition-all" data-fc-dismiss type="button">Close
                                </button>
                                <button name="add_data" type="submit" class="py-2.5 px-4 inline-flex justify-center items-center gap-2 rounded bg-success hover:bg-success-600 text-white">Add
                                    Purchase</button>
                            </div>
                        </form>
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

    echo "<script>window.location = 'stock_log.php?from_date=$from_date&to_date=$to_date&stock=$stock_type'; </script>";
}





?>

<script src="../../assets/libs/nice-select2/js/nice-select2.js"></script>

<!-- Choices Demo js -->
<script src="../../assets/js/pages/form-select.js"></script>
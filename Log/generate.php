<?php
$redirect_link = "../";
$side_link = "../";

include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php';


$from_date = '';
$to_date = '';
$stock = '';
?>

<head>
    <?php
    $title = 'Generate Log';
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

                <div class="card mt-3">
                    <div class="card-header">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-bold text-gray-800 dark:text-white">
                                Generate Log
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
                                                <th>Customer</th>
                                                <th>Job Description</th>
                                                <th>Size</th>
                                                <th>Quantity</th>
                                                <th>Total Price</th>
                                                <th>Unit Price</th>
                                                <th>Total Price VAT</th>
                                                <th>Type</th>
                                                <th>Refernce</th>
                                                <th>Validity</th>
                                                <th>Quote Sender</th>
                                                <th>Requested By</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php

                                            $sql = "SELECT * FROM performa_logs ORDER BY generate_id DESC";
                                            $result = mysqli_query($con, $sql);
                                            while ($row = mysqli_fetch_assoc($result)) {
                                            ?>
                                                <tr class="odd:bg-white even:bg-gray-100 dark:odd:bg-slate-700 dark:even:bg-slate-800">


                                                    <td class="px-6 90-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['generate_id']; ?></td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php
                                                        echo $row['customer'];

                                                        ?></td>

                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php

                                                        echo $row['job_description'];
                                                        ?></td>

                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['size']; ?></td>

                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['quantity']; ?></td>

                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['total_price']; ?></td>

                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['unit_price']; ?></td>

                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['price_vat']; ?></td>

                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['types']; ?></td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['ref']; ?></td>

                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['validity']; ?></td>

                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['quote_sender']; ?></td>

                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['requested_by']; ?></td>





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
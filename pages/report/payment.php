<?php
$redirect_link = "../../";
$side_link = "../../";

include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php';
?>

<head>
    <?php
    $title = 'Purchase';
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
                                        Select Client
                                    </label>
                                    <select class="search-select" name="client">
                                        <option value="">Select Client</option>
                                        <option value="" <?php echo isset($_GET['client']) && $_GET['client'] === '' ? 'selected' : ''; ?>>All Client</option>
                                        <?php
                                        $sql = "SELECT * FROM payment GROUP BY client";
                                        $result = mysqli_query($con, $sql);
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $selected = isset($_GET['client']) && $_GET['client'] === $row['client'] ? 'selected' : '';
                                        ?>
                                            <option value="<?php echo $row['client']; ?>" <?php echo $selected; ?>>
                                                <?php echo $row['client']; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>

                            </div>
                            <div class="flex justify-end">
                                <a href="payment.php" class="btn btn-sm bg-danger text-white rounded-full me-2">
                                    <i class="msr text-base me-2">restart_alt</i>
                                    Reset</a>
                                <button name="filter" type="submit" class="btn btn-sm bg-success text-white rounded-full">
                                    <i class="msr text-base me-2">filter_list</i>
                                    Filter</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card mt-3">
                    <div class="card-header">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-bold text-gray-800 dark:text-white">
                                Payment Report
                            </h3>
                            <div>
                                <?php
                                $from = empty($_GET['from']) ? "1000-01-01" : $_GET['from'];
                                $to = empty($_GET['to']) ? "3000-01-01" : $_GET['to'];
                                if (isset($_GET['client'])) $client = $_GET['client'];
                                else $client = '';

                                $link = "";
                                if (isset($from) and isset($to))
                                    $link = "export.php?file=payment&from=$from&to=$to&client=$client";
                                else
                                    $link = "export.php?file=payment&client=$client";
                                ?>
                                <a href="<?php echo $link; ?>" class="btn btn-sm rounded-full bg-success/25 text-success hover:bg-success hover:text-white">
                                    <i class="msr text-base me-2">picture_as_pdf</i> Export</a>
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
                                                <th>Job Number</th>
                                                <th>User</th>
                                                <th>Client</th>
                                                <th>Job Description</th>
                                                <th>Size</th>
                                                <th>Quantity</th>
                                                <th>Unit Price</th>
                                                <th>Advance</th>
                                                <th>Remained</th>
                                                <th>Total</th>
                                                <th>Date</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $from_date = empty($_GET['from']) ? "1000-01-01" : $_GET['from'];
                                            $to_date = empty($_GET['to']) ? "3000-01-01" : $_GET['to'];


                                            $client = '';
                                            if (!empty($_GET['client'])) {
                                                $get_client = $_GET['client'];
                                                $client = "client = '$get_client'";
                                            } else {
                                                $client = '';
                                            }

                                            if (!$client) {
                                                $sql = "SELECT * FROM payment WHERE DATE(date) >= '$from_date' AND DATE(date) <= '$to_date' order by payment_id desc";
                                            } else {
                                                $sql = "SELECT * FROM payment WHERE DATE(date) >= '$from_date' AND DATE(date) <= '$to_date' AND {$client} order by payment_id desc";
                                            }
                                            $result = mysqli_query($con, $sql);
                                            while ($row = mysqli_fetch_assoc($result)) {
                                            ?>
                                                <tr class="odd:bg-white even:bg-gray-100 dark:odd:bg-slate-700 dark:even:bg-slate-800">

                                                    <td class="px-6 90-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['payment_id']; ?></td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['job_number']; ?></td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php
                                                        $user_id = $row['user_id'];
                                                        $sql0 = "SELECT * FROM user WHERE user_id = '$user_id'";
                                                        $result0 = mysqli_query($con, $sql0);
                                                        $row0 = mysqli_fetch_assoc($result0);
                                                        echo $row0['user_name'];
                                                        ?></td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['client']; ?></td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['job_description']; ?></td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['size']; ?></td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['quantity']; ?></td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['unit_price']; ?></td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['advance']; ?></td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['remained']; ?></td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['total']; ?></td>

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




if (isset($_POST['client'])) {
    $from = $_POST['from_date'];
    $to = $_POST['to_date'];
    $client = $_POST['client'];
    echo "<script>window.location = 'payment.php?from=$from&to=$to&client=$client'; </script>";
}




?>
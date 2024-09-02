<?php
$redirect_link = "../../";
$side_link = "../../";

include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php';


$from_date = $_GET['from'] ?? '';
$to_date = $_GET['to'] ?? '';

?>


<?php
$id = $_SESSION['user_id'];

$result = mysqli_query($con, "SELECT * FROM user WHERE user_id = $id");


if ($result) {
    
    $row = mysqli_fetch_assoc($result);

    
    if ($row) {
        
        $user_id = $row['user_id'];
        $user_name = $row['user_name'];
        $password = $row['password'];
        $privileged = $row['previledge'];
        $module = json_decode($row['module'], true);

        
        $calculateButtonVisible = ($module['saleview'] == 1) ? true : false;

        
        $addButtonVisible = ($module['saleadd'] == 1) ? true : false;

        $deleteButtonVisible = ($module['saledelete'] == 1) ? true : false;

        
        $updateButtonVisible = ($module['saleedit'] == 1) ? true : false;

        
        $generateButtonVisible = ($module['salegenerate'] == 1) ? true : false;
    } else {
        echo "No user found with the specified ID";
    }

    // Free the result set
    mysqli_free_result($result);
} else {
    // Handle the case where the query failed
    echo "Error executing query: " . mysqli_error($con);
}
?>

<head>
    <?php
    $title = 'Sale Without Vat';
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
                            Filter Sale without Vat
                        </p>
                    </div>

                    <div class="p-6">
                        <form method="POST">
                            <p class="mt-2 text-gray-800 dark:text-gray-400">
                                <div class="flex gap-2">
                                    <div class="mb-3">
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">
                                            From Date </label>
                                        <input type="date" name="from_date" class="form-input" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">
                                            To Date </label>
                                        <input type="date" name="to_date" class="form-input" required>
                                    </div>
                                </div>
                            </p>
                            <div class="flex justify-end">
                                <a href="?" class="btn btn-sm bg-danger text-white rounded-full me-2">
                                    <i class="msr text-base me-2">restart_alt</i>
                                    Reset
                                </a>
                                <button name="filter" type="submit" class="btn btn-sm bg-success text-white rounded-full">
                                    <i class="msr text-base me-2">filter_list</i>
                                    Filter
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card mt-3">
                    <div class="card-header">
                        <div class="flex justify-between items-center">
                            <h4 class="text-slate-900 dark:text-slate-200 text-lg font-medium">Sale Without Vat</h4>
                            <div>

                            <?php if ($addButtonVisible) : ?>
                                <button type="button" data-fc-type="modal" data-fc-target="addModal"
                                    class="btn btn-sm rounded-full bg-success/25 text-success hover:bg-success hover:text-white">
                                    <i class="msr text-base me-2">add</i>
                                    Add Sale
                                </button>
                                <?php endif; ?>
                                <?php if ($generateButtonVisible) : ?>
                                <a href="export.php?type=sales_withoutvat&from_date=<?php echo $from_date; ?>&to_date=<?php echo $to_date; ?>"
                                    class="btn btn-sm rounded-full bg-success/25 text-success hover:bg-success hover:text-white">
                                    <i class="msr text-base me-2">picture_as_pdf</i>
                                    Export
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>


                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <div class="min-w-full inline-block align-middle">
                                <div class="overflow-hidden">
                                    <table id="myTable"
                                        class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead>
                                            <tr>
                                                <th>Action</th>
                                                <th>#</th>
                                                <th>Customer</th>
                                                <th>Description</th>
                                                <th>Sales Price</th>
                                                <th>Date</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                    if (!empty($_GET['from']) && !empty($_GET['to'])) {
                                        $from_date = $_GET['from'];
                                        $to_date = $_GET['to'];
                                    } else {
                                        $from_date = "0000-00-00";
                                        $to_date = "3000-01-01";
                                    }

                                    $customer = '';
                                    if (!empty($_GET['customer'])) {
                                        $get_customer = $_GET['customer'];
                                        $customer = "customer = '$get_customer'";
                                    } else {
                                        $customer = '';
                                    }

                                    if (!$customer) {
                                        $sql = "SELECT * 
                                        FROM sales_withoutvat 
                                        WHERE DATE(sales_date) >= '$from_date' 
                                          AND DATE(sales_date) <= '$to_date'
                                        ORDER BY sales_date DESC";
                                    } else {
                                        $sql = "SELECT * FROM sales_withoutvat WHERE DATE(sales_date) >= '$from_date' AND DATE(sales_date) <= '$to_date' AND {$customer} ORDER BY sales_date DESC";
                                    }
                                    $result = mysqli_query($con, $sql);
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        ?>
                                            <tr
                                                class="odd:bg-white even:bg-gray-100 dark:odd:bg-slate-700 dark:even:bg-slate-800">
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">

                                                    <?php if ($deleteButtonVisible) : ?>
                                                    <a id="del-btn" href="remove.php?id=<?php echo $row['sales_id'];?>&from=sales_withoutvat"
                                                        class="btn bg-danger/25 text-danger hover:bg-danger hover:text-white btn-sm rounded-full"><i
                                                            class="mgc_delete_2_line text-base me-2"></i> Delete</a>

                                                            <?php endif; ?>



                                                            <?php if ($updateButtonVisible) : ?>
                                                    <button type="button" class="btn bg-warning/25 text-warning hover:bg-warning hover:text-white btn-sm rounded-full"
                                                        data-fc-type="modal" data-fc-target="edit<?= $row['sales_id'] ?>">
                                                        <i class="mgc_pencil_line text-base me-2"></i> 
                                                        Edit
                                                    </button>

                                                    <?php endif; ?>

                                                </td>
                                                <td
                                                    class="px-6 90-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                    <?php echo $row['sales_id']; ?></td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                    <?php echo $row['customer']; ?></td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                    <?php echo $row['description']; ?></td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                    <?php echo $row['sale_price']; ?></td>



                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                    <?php echo $row['sales_date']; ?></td>

                                            </tr>
                                            <!-- Edit modal -->
                                            <div id="edit<?= $row['sales_id'] ?>"
                                                class="w-full h-full fixed top-0 left-0 z-50 transition-all duration-500 hidden">
                                                <div
                                                    class="fc-modal-open:mt-7 fc-modal-open:opacity-100 fc-modal-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto  bg-white border shadow-sm rounded-md dark:bg-slate-800 dark:border-gray-700">
                                                    <div
                                                        class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
                                                        <h3 class="font-medium text-gray-800 dark:text-white text-lg">
                                                            Edit Sales
                                                        </h3>
                                                        <button
                                                            class="inline-flex flex-shrink-0 justify-center items-center h-8 w-8 dark:text-gray-200"
                                                            data-fc-dismiss type="button">
                                                            <span class="material-symbols-rounded">close</span>
                                                        </button>
                                                    </div>
                                                    <form method="POST">
                                                        <div class="px-4 py-8 overflow-y-auto">
                                                            <input type="hidden" name="sales_id"
                                                                value="<?php echo $row['sales_id'] ?>">
                                                            <input type="hidden" name="customer"
                                                                value="<?php echo $row['customer']; ?>">


                                                            <div class="mb-3">
                                                                <label
                                                                    class="text-gray-800 text-sm font-medium inline-block mb-2">
                                                                    Description </label>
                                                                <input type="text" name="description" class="form-input"
                                                                    value="<?= $row['description'] ?>" required>
                                                            </div>

                                                            <div class="flex gap-2 justify-between">
                                                                <div class="mb-3">
                                                                    <label
                                                                        class="text-gray-800 text-sm font-medium inline-block mb-2">Sale price
                                                                        </label>
                                                                    <input type="text" name="sale_price"
                                                                        class="form-input"
                                                                        value="<?= $row['sale_price'] ?>" required>
                                                                </div>



                                                            </div>
                                                            <div class="flex gap-2 justify-between">
                                                                <div class="mb-3">
                                                                    <label
                                                                        class="text-gray-800 text-sm font-medium inline-block mb-2">
                                                                        Date </label>
                                                                    <input type="date" name="sales_date" class="form-input"
                                                                        value="<?= $row['sales_date'] ?>" required>
                                                                </div>

                                                            </div>





                                                            <div
                                                                class="flex justify-end items-center gap-4 p-4 border-t dark:border-slate-700">
                                                                <button
                                                                    class="py-2 px-5 inline-flex justify-center items-center gap-2 rounded dark:text-gray-200 border dark:border-slate-700 font-medium hover:bg-slate-100 hover:dark:bg-slate-700 transition-all"
                                                                    data-fc-dismiss type="button">Close
                                                                </button>
                                                                <button name="update_purchase" type="submit"
                                                                    class="btn bg-success text-white">Edit
                                                                    Purchase</button>
                                                            </div>
                                                    </form>
                                                </div>
                                            </div>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="addModal" class="w-full h-full fixed top-0 left-0 z-50 transition-all duration-500 hidden">
                    <div
                        class="fc-modal-open:mt-7 fc-modal-open:opacity-100 fc-modal-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto  bg-white border shadow-sm rounded-md dark:bg-slate-800 dark:border-gray-700">
                        <div class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
                            <h3 class="font-medium text-gray-800 dark:text-white text-lg">
                                Add Sales
                            </h3>
                            <button
                                class="inline-flex flex-shrink-0 justify-center items-center h-8 w-8 dark:text-gray-200"
                                data-fc-dismiss type="button">
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
                                            Description </label>
                                        <input type="text" name="description" class="form-input" required>
                                    </div>

                                </div>



                                <div class="flex gap-2 justify-between">


                                    <div class="mb-3">
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">Sales
                                            Price</label>
                                        <input type="text" name="sales_price" class="form-input" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">
                                            Date </label>
                                        <input type="date" name="date" class="form-input" required>
                                    </div>

                                </div>



                            </div>
                            <div class="flex justify-end items-center gap-4 p-4 border-t dark:border-slate-700">
                                <button
                                    class="py-2 px-5 inline-flex justify-center items-center gap-2 rounded dark:text-gray-200 border dark:border-slate-700 font-medium hover:bg-slate-100 hover:dark:bg-slate-700 transition-all"
                                    data-fc-dismiss type="button">Close
                                </button>
                                <button name="add_data" type="submit"
                                    class="py-2.5 px-4 inline-flex justify-center items-center gap-2 rounded bg-success hover:bg-success-600 text-white">Add
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
            responsive: true


        });
    });
    </script>

</body>

</html>

<?php


if (isset($_POST['update_purchase'])) {
    $customer = $_POST['customer'];
    $description = $_POST['description'];
    $date = $_POST['sales_date'];
    $sales_price = $_POST['sale_price'];
    $sales_id = $_POST['sales_id'];

    
    $purchase_update = "UPDATE `sales_withoutvat` SET `customer`='$customer',`sale_price`='$sales_price' , `sales_date`='$date', `update_date`='$date' ,`description`='$description'
                 WHERE `sales_id` = '$sales_id'"; 
    $result_add = mysqli_query($con, $purchase_update);

    if ($result_add) {
        echo "<script>window.location = 'action.php?status=success&redirect=sales_without.php'; </script>";
    } else {
        echo "<script>window.location = 'action.php?status=error&redirect=sales_without.php'; </script>";
    }
}

if (isset($_POST['add_data'])) {
    $customer = $_POST['customer'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $sales_price = $_POST['sales_price'];

    $add_data = "INSERT INTO `sales_withoutvat`(`customer`, `sale_price`, `sales_date`, `update_date`, `description`) 
                    VALUES ('$customer','$sales_price','$date','$date','$description')";
    $result_add = mysqli_query($con, $add_data);

    if ($result_add) {
        echo "<script>window.location = 'action.php?status=success&redirect=sales_without.php'; </script>";
    } else {
        echo "<script>window.location = 'action.php?status=error&redirect=sales_without.php'; </script>";
    }
}

if (isset($_POST['filter'])) {
    $from = $_POST['from_date'];
    $to = $_POST['to_date'];
    $customer = $_POST['customer'];
    echo "<script>window.location = 'sales_without.php?from=$from&to=$to&customer=$customer'; </script>";
}




?>
<?php
$redirect_link = "../../";
$side_link = "../../";
include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php';
$current_date = date('Y-m-d');
$type = $_GET['type'];
if (!isset($type)) {
    echo "<script>window.location = 'index.php'; </script>";
}

$from_date = $_GET['from_date'] ?? '';
$to_date = $_GET['to_date'] ?? '';

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
        $payment = $row['payment'];
        if ($payment == 'yes') {
            $payment = true;
        } else {
            $payment = false;
        }



        $module = json_decode($row['module'], true);


        $calculateButtonVisible = ($module['dataview'] == 1) ? true : false;


        $addButtonVisible = ($module['dataadd'] == 1) ? true : false;





        $updateButtonVisible = ($module['dataedit'] == 1) ? true : false;
        $deleteButtonVisible = ($module['datadelete'] == 1) ? true : false;


        $generateButtonVisible = ($module['datagenerate'] == 1) ? true : false;
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
    $title = ucfirst($type);
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
                                    <input type="date" name="from_date" class="form-input" required>
                                </div>
                                <div class="me-3">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2">
                                        To Date </label>
                                    <input type="date" name="to_date" class="form-input" required>
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
                            <h4 class="text-slate-900 dark:text-slate-200 text-lg font-medium"><?= $title ?></h4>
                            <div>
                                <?php if ($generateButtonVisible) : ?>
                                    <a href='<?= $redirect_link . "pages/export.php?type=$type&from_date=$from_date&to_date=$to_date" ?>' class="btn btn-sm rounded-full bg-success/25 text-success hover:bg-success hover:text-white">
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
                                    <?php if ($type == 'book') { ?>
                                        <table id="zero_config" data-order='[[ 2, "dsc" ]]' class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                            <thead>
                                                <tr>
                                                    <th scope="col" class="py-3 ps-4" data-searchable="false" data-orderable="false">
                                                        <div class="flex items-center h-5">
                                                            <input id="checkAll" type="checkbox" class="form-checkbox rounded">
                                                            <label for="table-checkbox-all" class="sr-only">Checkbox</label>
                                                        </div>
                                                    </th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Job Type</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Size</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Constant Cost</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Required Quantity</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Bind Type</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Profit Margin</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Vat</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Total Cost</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Profit</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Unit Price</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Unit Price (VAT)</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Total Price</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Total Price (VAT)</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Commition Price</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Page Machine Run</th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $primary_key = 'book_id';

                                                if ($_SESSION['username'] == 'masteradmin') {

                                                    $sql = "SELECT * FROM book ORDER BY book_id DESC";
                                                } else {
                                                    $sql = "SELECT * FROM book WHERE private != 'yes' ORDER BY book_id DESC";
                                                }

                                                $result22 = mysqli_query($con, $sql);
                                                while ($row = mysqli_fetch_assoc($result22)) {
                                                    $common_var = json_decode($row['common_var'], true);
                                                    $total_output = json_decode($row['total_output'], true);
                                                    $page_output = json_decode($row['page_output'], true);
                                                    $cover_output = json_decode($row['cover_output'], true);
                                                    $page_input = json_decode($row['page_input'], true);
                                                    $cover_input = json_decode($row['cover_input'], true);




                                                ?>
                                                    <tr class="odd:bg-white even:bg-gray-100 dark:odd:bg-slate-700 dark:even:bg-slate-800">
                                                        <td class="py-3 ps-4">
                                                            <div class="flex items-center h-5">
                                                                <input id="table-checkbox-5" name="update[]" type="checkbox" class="box form-checkbox rounded" value="<?php echo $row['book_id'] ?>">
                                                                <label for="table-checkbox-5" class="sr-only">Checkbox</label>
                                                            </div>
                                                        </td>
                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">

                                                            <?php if ($deleteButtonVisible) : ?>
                                                                <a id="del-btn" href="remove.php?type=<?= $type ?>&key=<?php echo $row['book_id']; ?>&from=<?= $type ?>" class="btn bg-danger/25 text-danger hover:bg-danger hover:text-white btn-sm rounded-full"><i class="mgc_delete_2_line text-base me-2"></i> Delete</a>
                                                            <?php endif; ?>

                                                            <?php if ($updateButtonVisible) : ?>
                                                                <button type="button" class="btn bg-warning/25 text-warning hover:bg-warning hover:text-white btn-sm rounded-full" data-fc-type="modal" data-fc-target="edit" data-fc-id="<?= $row['book_id'] ?>">
                                                                    <i class="mgc_pencil_line text-base me-2"></i>
                                                                    Edit
                                                                </button>

                                                            <?php endif; ?>




                                                            <?php if ($addButtonVisible && (!$row['payment_status'] || $payment)) : ?>
                                                                <button type="button" class="btn bg-primary/25 text-primary hover:bg-primary hover:text-white btn-sm rounded-full" data-fc-type="modal" data-fc-target="payModal" data-fc-id="<?= $row['book_id'] ?>">
                                                                    <i class="mgc_currency_dollar_2_fill text-base me-2"></i>
                                                                    Add payment
                                                                </button>
                                                            <?php endif; ?>


                                                        </td>
                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"> <?php echo $row['book_id']; ?> </td>

                                                        </td>
                                                        <td> <?php echo $common_var['customer']; ?> </td>
                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"> <?php echo $common_var['job_type']; ?> </td>
                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"> <?php echo $common_var['size']; ?> </td>
                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"> <?php echo $common_var['constant_cost']; ?> </td>
                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"> <?php echo $common_var['required_quantity']; ?> </td>
                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"> <?php
                                                                                                                                                        $bind_id = $common_var['bind_id'];
                                                                                                                                                        $sql0 = "SELECT * FROM bind WHERE bind_id = '$bind_id'";
                                                                                                                                                        $result0 = mysqli_query($con, $sql0);
                                                                                                                                                        $row0 = mysqli_fetch_assoc($result0);
                                                                                                                                                        echo $row0['bind_type'];
                                                                                                                                                        ?>
                                                        </td>
                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"> <?php echo $common_var['profit_margin']; ?> </td>
                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"> <?php echo $common_var['vat']; ?> </td>
                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"> <?php echo $total_output['total_cost']; ?> </td>
                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"> <?php echo $total_output['profit_margin']; ?> </td>
                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"> <?php echo $total_output['unit_price']; ?> </td>
                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"> <?php echo $total_output['unit_price_vat']; ?> </td>
                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"> <?php echo $total_output['total_price']; ?> </td>
                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"> <?php echo $total_output['total_price_vat']; ?> </td>
                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"> <?php echo $common_var['commitonprice']; ?> </td>
                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"> <?php echo $row['date']; ?> </td>
                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"> <?php echo $page_output['page_machine_run']  + $cover_output['cover_machine_run']; ?> </td>

                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    <?php } else { ?>
                                        <table id="zero_config" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                            <thead>
                                                <tr>
                                                    <th scope="col" class="py-3 ps-4" data-searchable="false" data-orderable="false">
                                                        <div class="flex items-center h-5">
                                                            <input id="checkAll" type="checkbox" class="form-checkbox rounded">
                                                            <label for="table-checkbox-all" class="sr-only">Checkbox</label>
                                                        </div>
                                                    </th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                                    <?php
                                                    $columns_query = "SHOW COLUMNS FROM $type";
                                                    $result = mysqli_query($con, $columns_query);
                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                        // change snake_case to Capitalized string
                                                        $field_name = ucfirst(join(' ', explode('_', $row['Field'])));
                                                    ?>
                                                        <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">
                                                            <?= $field_name ?>
                                                        </th>
                                                    <?php } ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php

                                                $columns_query = "SHOW COLUMNS FROM $type";

                                                $result54 = mysqli_query($con, $columns_query);
                                                $primary_key = '';
                                                while ($row = mysqli_fetch_assoc($result54)) {
                                                    if ($row['Key'] == 'PRI') {
                                                        $primary_key = $row['Field'];
                                                        break;
                                                    }
                                                }
                                                ?>
                                                <?php


                                                $from_date = empty($_GET['from_date']) ? "1000-01-01" : $_GET['from_date'];
                                                $to_date = empty($_GET['to_date']) ? "3000-01-01" : $_GET['to_date'];

                                                if ($_SESSION['username'] == 'masteradmin') {


                                                    $sql = "SELECT * FROM $type  WHERE DATE(date) >= '$from_date' AND DATE(date) <= '$to_date' ORDER BY $primary_key DESC  ";
                                                } else {

                                                    $sql = "SELECT * FROM $type WHERE DATE(date) >= '$from_date' AND DATE(date) <= '$to_date' AND private != 'yes' ORDER BY $primary_key DESC";
                                                }
                                                $result99 = mysqli_query($con, $sql);
                                                while ($row = mysqli_fetch_assoc($result99)) {
                                                    error_log(json_encode($row));
                                                ?>
                                                    <tr class="odd:bg-white even:bg-gray-100 dark:odd:bg-slate-700 dark:even:bg-slate-800">
                                                        <td class="py-3 ps-4">
                                                            <div class="flex items-center h-5">
                                                                <input id="table-checkbox-5" name="update[]" type="checkbox" class="box form-checkbox rounded" value="<?php echo $row[$primary_key] ?>">
                                                                <label for="table-checkbox-5" class="sr-only">Checkbox</label>
                                                            </div>
                                                        </td>
                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">

                                                            <?php if ($deleteButtonVisible) : ?>

                                                                <a id="del-btn" href="remove.php?type=<?= $type ?>&key=<?php echo $row[$primary_key]; ?>&from=<?= $type ?>" class="btn bg-danger/25 text-danger hover:bg-danger hover:text-white btn-sm rounded-full"><i class="mgc_delete_2_line text-base me-2"></i> Delete</a>

                                                            <?php endif; ?>

                                                            <?php




                                                            if ($updateButtonVisible) : ?>



                                                                <button type="button" class="btn bg-warning/25 text-warning hover:bg-warning hover:text-white btn-sm rounded-full" data-fc-type="modal" data-fc-target="edit" data-fc-id="<?= $row[$primary_key] ?>">
                                                                    <i class="mgc_pencil_line text-base me-2"></i>
                                                                    Edit
                                                                </button>
                                                            <?php endif; ?>

                                                            <?php if ($addButtonVisible && (!$row['payment_status'] || $payment)) : ?>
                                                                <button type="button" class="btn bg-primary/25 text-primary hover:bg-primary hover:text-white btn-sm rounded-full" data-fc-type="modal" data-fc-target="payModal" data-fc-id="<?= $row[$primary_key] ?>">
                                                                    <i class="mgc_currency_dollar_2_fill text-base me-2"></i>
                                                                    Add payment
                                                                </button>
                                                            <?php endif; ?>

                                                        </td>
                                                        <?php foreach ($row as $value) { ?>
                                                            <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                                <?= $value ?>
                                                            </td>
                                                        <?php } ?>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4">

                            <?php if ($generateButtonVisible) : ?>
                                <button type="submit" id="generate" class="btn bg-success text-white rounded-full">
                                    <i class="mgc_pdf_line text-base me-2"></i>
                                    Generate
                                </button>





                            <?php endif; ?>

                            <?php if ($generateButtonVisible) : ?>
                                <button type="submit" id="order" class="btn bg-success text-white rounded-full">
                                    <i class="mgc_pdf_line text-base me-2"></i>
                                    Order
                                </button>
                            <?php endif; ?>


                            <?php if ($generateButtonVisible) : ?>
                                <button type="submit" id="group" class="btn bg-info text-white rounded-full">
                                    <i class="mgc_pdf_line text-base me-2"></i>
                                    Group Order
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Edit modal -->
                <div id="edit" class="w-full h-full fixed top-0 left-0 z-50 transition-all duration-500 hidden">
                    <div class="fc-modal-open:mt-7 fc-modal-open:opacity-100 fc-modal-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto  bg-white border shadow-sm rounded-md dark:bg-slate-800 dark:border-gray-700">
                        <div class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
                            <h3 class="font-medium text-gray-800 dark:text-white text-lg">
                                Edit <?= $title ?>
                            </h3>
                            <button class="inline-flex flex-shrink-0 justify-center items-center h-8 w-8 dark:text-gray-200" data-fc-dismiss type="button">
                                <span class="material-symbols-rounded">close</span>
                            </button>
                        </div>
                        <form method="POST" id="update">
                            <div class="px-4 py-8 overflow-y-auto">
                                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                                    <?php
                                    if ($type == 'book') {
                                        $fields = array('book_id', "customer", "job_type", "constant_cost", "required_quantity", "profit_margin", "vat", "bind_id", "folding_price", "type", "digital_print", "size", "commitonprice", "total_cost", "profit_margin", "total_price", "total_price_vat", "unit_price", "unit_price_vat", "date", "page_machine_run");
                                    } else {
                                        $fields = array();
                                        $columns_query = "SHOW COLUMNS FROM $type";
                                        $result = mysqli_query($con, $columns_query);
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            array_push($fields, $row['Field']);
                                        }
                                    }
                                    foreach ($fields as $field) {
                                        if ($field == $primary_key) {
                                    ?>
                                            <input type="hidden" name="<?= $primary_key ?>">
                                        <?php } else if ($field == 'customer') { ?>
                                            <div>
                                                <label class="text-gray-800 text-sm font-medium inline-block mb-2">Customer </label>
                                                <select class="search-select" name="customer" id="customers" required>
                                                    <option value="">Select Customer</option>
                                                    <?php
                                                    $sql = "SELECT * FROM customer ORDER BY customer_id DESC";
                                                    $res = mysqli_query($con, $sql);
                                                    while ($cust = mysqli_fetch_assoc($res)) {
                                                    ?>
                                                        <option value="<?php echo $cust['customer_name'] ?>">
                                                            <?php echo $cust['customer_name']; ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        <?php } else {
                                            // change snake_case to Capitalized string
                                            $field_name = ucfirst(join(' ', explode('_', $field)));
                                        ?>
                                            <div>
                                                <label class="text-gray-800 text-sm font-medium inline-block mb-2">
                                                    <?= $field_name ?>
                                                </label>
                                                <input type="<?= $field == 'date' ? 'date' : 'text' ?>" name="<?= $field ?>" class="form-input" required>
                                            </div>
                                    <?php
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="flex justify-end items-center gap-4 p-4 border-t dark:border-slate-700">
                                <button class="py-2 px-5 inline-flex justify-center items-center gap-2 rounded dark:text-gray-200 border dark:border-slate-700 font-medium hover:bg-slate-100 hover:dark:bg-slate-700 transition-all" data-fc-dismiss type="button">Close
                                </button>
                                <button type="submit" class="btn bg-success text-white">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Add payment -->
                <div id="payModal" class="w-full h-full fixed top-0 left-0 z-50 transition-all duration-500 hidden">
                    <div class="md:w-lg fc-modal-open:mt-7 fc-modal-open:opacity-100 fc-modal-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto  bg-white border shadow-sm rounded-md dark:bg-slate-800 dark:border-gray-700">
                        <div class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
                            <h3 class="font-medium text-gray-800 dark:text-white text-lg">
                                Add Payment
                            </h3>
                            <button class="inline-flex flex-shrink-0 justify-center items-center h-8 w-8 dark:text-gray-200" data-fc-dismiss type="button">
                                <span class="material-symbols-rounded">close</span>
                            </button>
                        </div>
                        <form method="POST" id="pay">
                            <div class="px-4 py-8 overflow-y-auto">
                                <div class="grid grid-cols-2 md:grid-cols-4  gap-3">
                                    <input type="hidden" name="vat" />
                                    <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?>" />



                                    <div>
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">Customer(<i>readonly</i>) </label>
                                        <input type="text" name="customer" class="form-input" readonly>
                                    </div>
                                    <div>
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">Date </label>
                                        <input type="date" name="date" value="<?= date('Y-m-d') ?>" class="form-input" required>
                                    </div>

                                    <div>
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">End Date </label>
                                        <input type="date" name="enddate" value="<?= date('Y-m-d') ?>" class="form-input" required>
                                    </div>
                                    <div>
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">Job Description
                                        </label>
                                        <input type="text" name="description" class="form-input" required>
                                    </div>
                                    <div>
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">Size </label>
                                        <input type="text" name="size" class="form-input" required>
                                    </div>

                                    <div>
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">Unit Price </label>
                                        <input type="number" min="0" step=".0000000000000001" name="unit_price" id="unit_price" class="form-input" required>
                                    </div>
                                    <div>
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">Unit Price VAT </label>
                                        <input type="number" min="0" step=".0000000000000001" name="unit_price_vat" id=" unit_price_vat" class="form-input" required>
                                    </div>
                                    <div>
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">Quantity </label>
                                        <input type="number" min="0" name="quantity" id="quantity" class="form-input" required>
                                    </div>
                                    <div>
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">Advance </label>
                                        <input type="number" min="0" step=".01" name="advance" id="advance" value="0" class="form-input" required>
                                    </div>

                                    <?php if ($type == 'book' || $type == 'brocher' || $type == 'single_page' || $type == 'multi_page' || $type == 'bag') { ?>

                                        <div>
                                            <label class="text-gray-800 text-sm font-medium inline-block mb-2">Machine Run </label>
                                            <input type="number" min="0" step=".01" name="machine_run" id="machine_run" value="0" class="form-input" required>
                                        </div>


                                        <?php if ($type == 'multi_page' || $type == 'book') { ?>
                                            <input type="hidden" min="0" step=".01" name="machine_type_page_a" id="machine_type_page_a">
                                            <input type="hidden" min="0" step=".01" name="machine_type_page_b" id="machine_type_page_b">
                                            <input type="hidden" min="0" step=".01" name="machine_type_page_c" id="machine_type_page_c">
                                            <input type="hidden" min="0" step=".01" name="machine_type_page_d" id="machine_type_page_d">

                                            <input type="hidden" min="0" step=".01" name="machine_run_page_a" id="machine_run_page_a">
                                            <input type="hidden" min="0" step=".01" name="machine_run_page_b" id="machine_run_page_b">
                                            <input type="hidden" min="0" step=".01" name="machine_run_page_c" id="machine_run_page_c">
                                            <input type="hidden" min="0" step=".01" name="machine_run_page_d" id="machine_run_page_d">



                                        <?php } ?>

                                    <?php } ?>

                                    <?php $type = $_GET['type'];

                                    if ($type == 'single_page') {

                                    ?>

                                        <div>
                                            <label class="text-gray-800 text-sm font-medium inline-block mb-2">Page ID </label>
                                            <input type="text" name="page_id" class="form-input" required>
                                            <input type="hidden" name="single_page_id" id="single_page_id" />
                                            <input type="hidden" name="machine_type" id="machine_type" />
                                        </div>

                                    <?php }

                                    if ($type == "otherdigital") {

                                    ?>

                                        <input type="hidden" name="otherdigital_id" id="otherdigital_id" class="form-input" />
                                        <div>
                                            <label class="text-gray-800 text-sm font-medium inline-block mb-2">Unit Digital Type </label>
                                            <input type="text" name="unit_digital_type" class="form-input" required>
                                        </div>


                                    <?php }

                                    if ($type == 'design') {
                                    ?>
                                        <input type="hidden" name="design_id" id="design_id" class="form-input" />
                                    <?php
                                    }


                                    if ($type == 'multi_page') { ?>

                                        <input type="hidden" name="machine_type" id="machine_type" />

                                        <div>
                                            <input type="hidden" name="multi_page_id" id="multi_page_id" />
                                            <label class="text-gray-800 text-sm font-medium inline-block mb-2">Page ID A</label>
                                            <input type="text" name="page_id_a" class="form-input" required>
                                        </div>
                                        <div>
                                            <label class="text-gray-800 text-sm font-medium inline-block mb-2">Page ID B</label>
                                            <input type="text" name="page_id_b" class="form-input" required>
                                        </div>
                                        <div>
                                            <label class="text-gray-800 text-sm font-medium inline-block mb-2">Page ID C</label>
                                            <input type="text" name="page_id_c" class="form-input" required>
                                        </div>
                                        <div>
                                            <label class="text-gray-800 text-sm font-medium inline-block mb-2">Page ID D</label>
                                            <input type="text" name="page_id_d" class="form-input" required>
                                        </div>

                                        <div>
                                            <label class="text-gray-800 text-sm font-medium inline-block mb-2">No of Page A</label>
                                            <input type="text" name="nopage_a" class="form-input" required>
                                        </div>
                                        <div>
                                            <label class="text-gray-800 text-sm font-medium inline-block mb-2">No of Page B</label>
                                            <input type="text" name="nopage_b" class="form-input" required>
                                        </div>
                                        <div>
                                            <label class="text-gray-800 text-sm font-medium inline-block mb-2">No of Page C</label>
                                            <input type="text" name="nopage_c" class="form-input" required>
                                        </div>
                                        <div>
                                            <label class="text-gray-800 text-sm font-medium inline-block mb-2">No of Page D</label>
                                            <input type="text" name="nopage_d" class="form-input" required>
                                        </div>
                                        <div>

                                            <label class="text-gray-800 text-sm font-medium inline-block mb-2">Lamination Type</label>
                                            <input type="text" name="lamination_type" class="form-input" required>

                                        </div>

                                    <?php }


                                    if ($type == 'digital') { ?>

                                        <input type="hidden" name="digital_id" id="digital_id" />
                                    <?php

                                    }

                                    if ($type == 'bag') { ?>

                                        <input type="hidden" name="machine_type" id="machine_type" />

                                        <div>
                                            <label class="text-gray-800 text-sm font-medium inline-block mb-2">Required Paper Full </label>
                                            <input type="text" min="0" step=".01" name="required_paper_full" id="required_paper_full" class="form-input" required>
                                            <input type="hidden" name="bag_id" id="bag_id" />
                                        </div>

                                        <div>
                                            <label class="text-gray-800 text-sm font-medium inline-block mb-2">Paper ID </label>
                                            <input type="text" min="0" step=".01" name="paper_id" id="paper_id" class="form-input" required>
                                        </div>

                                        <div>
                                            <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Lamination ID</label>
                                            <input type="text" name="lamination_type" class="form-input" required>

                                        </div>


                                    <?php }


                                    if ($type == 'brocher') { ?>

                                        <input type="hidden" name="machine_type" id="machine_type" />

                                        <div>
                                            <label class="text-gray-800 text-sm font-medium inline-block mb-2">Required Paper Full </label>
                                            <input type="text" min="0" step=".01" name="required_paper_full" id="required_paper_full" class="form-input" required>
                                            <input type="hidden" name="brocher_id" id="brocher_id" />
                                        </div>

                                        <div>
                                            <label class="text-gray-800 text-sm font-medium inline-block mb-2">Paper ID </label>
                                            <input type="text" min="0" step=".01" name="paper_id" id="paper_id" class="form-input" required>
                                        </div>

                                        <div>
                                            <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Lamination ID</label>
                                            <input type="text" name="lamination_type" class="form-input" required>

                                        </div>
                                    <?php


                                    }


                                    if ($type == 'book') { ?>

                                        <input type="hidden" name="machine_type" id="machine_type" />
                                        <input type="hidden" name="book_id" id="book_id" />

                                        <input type="hidden" name="required_paper_full_a" id="required_paper_full_a" required>
                                        <input type="hidden" name="required_paper_full_b" id="required_paper_full_b" required>
                                        <input type="hidden" name="required_paper_full_c" id="required_paper_full_c" required>
                                        <input type="hidden" name="required_paper_full_d" id="required_paper_full_d" required>

                                        <input type="hidden" name="paper_id_a" id="paper_id_a" required>
                                        <input type="hidden" name="paper_id_b" id="paper_id_b" required>
                                        <input type="hidden" name="paper_id_c" id="paper_id_c" required>
                                        <input type="hidden" name="paper_id_d" id="paper_id_d" required>


                                        <input type="hidden" name="lamination_type_cover">
                                        <input type="hidden" name="required_cover_paper">
                                        <input type="hidden" name="cover_paper_id" id="cover_paper_id">
                                        <input type="hidden" name="lamination_type_a" required>
                                        <input type="hidden" name="lamination_type_b" required>
                                        <input type="hidden" name="lamination_type_c" required>
                                        <input type="hidden" name="lamination_type_d" required>



                                        <div>
                                            <label class="text-gray-800 text-sm font-medium inline-block mb-2">Types</label>
                                            <input type="text" name="types" class="form-input" required>
                                        </div>


                                        <input type="hidden" name="digital_machine_type">
                                        <input type="hidden" name="digital_machine_run">
                                        <input type="hidden" name="digital_print_side">
                                        <input type="hidden" name="digital_page_type">
                                        <input type="hidden" name="digital_lamination_type">








                                    <?php


                                    }


                                    if ($type == 'banner') { ?>

                                        <div>
                                            <label class="text-gray-800 text-sm font-medium inline-block mb-2">Unit Banner </label>
                                            <input type="text" min="0" step=".01" name="unitbanner_type" id="unitbanner_type" class="form-input" required>
                                            <input type="hidden" name="banner_id" id="banner_id" />
                                        </div>
                                        <div>
                                            <label class="text-gray-800 text-sm font-medium inline-block mb-2">Width</label>
                                            <input type="text" min="0" step=".01" name="width" id="width" class="form-input" required>
                                        </div>

                                        <div>
                                            <label class="text-gray-800 text-sm font-medium inline-block mb-2">Length </label>
                                            <input type="text" min="0" step=".01" name="lengths" id="lengths" class="form-input" required>
                                        </div>
                                        <div>
                                            <label class="text-gray-800 text-sm font-medium inline-block mb-2">commitonprice </label>
                                            <input type="text" min="0" step=".01" name="commitonprice" id="commitonprice" class="form-input" required>
                                        </div>
                                        <div>
                                            <label class="text-gray-800 text-sm font-medium inline-block mb-2">Cvat </label>
                                            <input type="text" min="0" step=".01" name="cvat" id="cvat" class="form-input" required>
                                        </div>

                                        <div>
                                            <label class="text-gray-800 text-sm font-medium inline-block mb-2">Production </label>
                                            <input type="text" name="product" id="product" class="form-input" required>
                                        </div>

                                        <div>
                                            <label class="text-gray-800 text-sm font-medium inline-block mb-2">Banner Metal </label>
                                            <input type="text" name="banner_metal" id="banner_metal" class="form-input" required>
                                        </div>
                                        <div>
                                            <label class="text-gray-800 text-sm font-medium inline-block mb-2">Banner Metal Type </label>
                                            <input type="text" name="banner_metal_type" id="product" class="form-input" required>
                                        </div>





                                    <?php } ?>
                                    <div class="col-span-1 md:col-span-2 flex justify-between">
                                        <div>
                                            <h4 id="remedial"></h4>
                                        </div>
                                        <div>
                                            <div class="form-group mb-3">
                                                <h4 id="total"></h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-end items-center gap-4 p-4 border-t dark:border-slate-700">
                                <button class="py-2 px-5 inline-flex justify-center items-center gap-2 rounded dark:text-gray-200 border dark:border-slate-700 font-medium hover:bg-slate-100 hover:dark:bg-slate-700 transition-all" data-fc-dismiss type="button">Close
                                </button>
                                <button type="submit" class="py-2.5 px-4 inline-flex justify-center items-center gap-2 rounded bg-success hover:bg-success-600 text-white">Add Payment</button>
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
            $('#checkAll').change(function() {
                if ($(this).is(':checked')) {
                    $('input[name="update[]"]').prop('checked', true);
                } else {
                    $('input[name="update[]"]').each(function() {
                        $(this).prop('checked', false);
                    });
                }
            });

            $('input[name="update[]"]').click(function() {
                var total_checkboxes = $('input[name="update[]"]').length;
                var total_checkboxes_checked = $('input[name="update[]"]:checked').length;

                if (total_checkboxes_checked == total_checkboxes) {
                    $('#checkAll').prop('checked', true);
                } else {
                    $('#checkAll').prop('checked', false);
                }
            });
        });

        $(document).ready(function() {
            const calculate = () => {
                let unitPrice = $('#payModal #unit_price').val()
                let qty = $('#payModal #quantity').val()
                let advance = $('#payModal #advance').val()
                let vat = $('#payModal input[name=vat]').val()
                if (unitPrice != '' && qty != '' && advance != '' && parseInt(unitPrice) >= 0 && parseInt(qty) >=
                    0 && parseInt(advance) >= 0) {
                    $.post("calculate/index.php", {
                            unitPrice,
                            qty,
                            advance,
                            vat
                        },
                        function(response) {
                            $('#total').html(`Total: <b>${response.total}</b>`);
                            $('#remedial').html(`Remaining: <b>${response.remaining}</b>`);
                        },
                        'json'
                    )
                }
            }

            $('#unit_price, #quantity, #advance').on('input', calculate)


            $('#generate').click(function(e) {
                e.preventDefault();
                const checkboxes = $('table#zero_config').find('[type="checkbox"]:checked.box')
                const update = $.map(checkboxes, c => c.value)
                $.post("generate.php?type=<?= $type ?>&primary_key=<?= $primary_key ?>", {
                        generate: '',
                        update
                    },
                    function(data, status) {
                        console.log(data, status)
                        if (status == 'success') swal("Great!", data, "success");
                        else swal("Oops!", data, "error");
                        checkboxes.prop('checked', false)
                    }
                ).catch(err => {
                    swal("Oops!", err.responseText, "error")
                });
            });

            $('#order').click(function(e) {
                const checkboxes = $('table#zero_config').find('[type="checkbox"]:checked.box');
                const update = $.map(checkboxes, c => c.value);

                if (update.length > 0) {
                    const url = "order.php?type=<?= $type ?>&primary_key=<?= $primary_key ?>&order=&update=" + update.join(',');
                    window.location.href = url; // Redirect to the order.php page with the parameters
                } else {
                    swal("Oops!", "Please select at least one item to order.", "error");
                }
            });

            $('#group').click(function(e) {
                const checkboxes = $('table#zero_config').find('[type="checkbox"]:checked.box');
                const update = $.map(checkboxes, c => c.value);

                if (update.length > 0) {
                    const url = "group.php?type=<?= $type ?>&primary_key=<?= $primary_key ?>&group=&update=" + update.join(',');
                    window.location.href = url; // Redirect to the order.php page with the parameters
                } else {
                    swal("Oops!", "Please select at least one item to Group.", "error");
                }
            });


            // editModal - populate inputs when editModal is displayed
            $("button[data-fc-target=edit]").click(function(event) {
                let id = event.currentTarget.getAttribute('data-fc-id')
                console.log('handling')
                $.get(`api/get.php?id=${id}&type=<?= $type ?>`, row => {
                    const data = JSON.parse(row)
                    for (key in data) {
                        if (key == 'customer') {
                            $(`#edit select[name='customer']`).val(data[key])
                        } else {
                            $(`#edit input[name='${key}']`).val(data[key]);
                        }
                    }
                })
            })

            // payModal - populate pay modal when it is visible
            $('button[data-fc-target=payModal]').click(function(event) {
                let id = event.target.getAttribute('data-fc-id')
                console.log('id', id)
                $.get(`api/get.php?id=${id}&type=<?= $type ?>`, result => {


                    let unit_price = parseFloat(result.unit_price);
                    let vat_rate = parseFloat(result.vat);
                    let unit_price_vats = unit_price + (unit_price * (vat_rate / 100));





                    $('input[name="customer"').val(result.customer);
                    $('input[name="description"').val(result.job_type);
                    $('input[name="size"').val(result.size);

                    $('input[name="unit_price"').val(result.unit_price);












                    <?php if ($type == 'book') { ?>

                        $('input[name="machine_run"').val(result.cover_machine_run);
                        $('input[name="required_paper_full_a"').val(result.page_paper_require_a_with_waste_mod_full);
                        $('input[name="required_paper_full_b"').val(result.page_paper_require_b_with_waste_mod_full);
                        $('input[name="required_paper_full_c"').val(result.page_paper_require_c_with_waste_mod_full);
                        $('input[name="required_paper_full_d"').val(result.page_paper_require_d_with_waste_mod_full);
                        $('input[name="required_cover_paper"').val(result.cover_required_paper_full);


                        $('input[name="paper_id_a"').val(result.page_paper_id_a);
                        $('input[name="paper_id_b"').val(result.page_paper_id_b);
                        $('input[name="paper_id_c"').val(result.page_paper_id_c);
                        $('input[name="paper_id_d"').val(result.page_paper_id_d);
                        $('input[name="cover_paper_id"').val(result.cover_paper_id);


                        $('input[name="lamination_type_cover"').val(result.cover_lamination_type);
                        $('input[name="lamination_type_a"').val(result.page_lam_type_a);
                        $('input[name="lamination_type_b"').val(result.page_lam_type_b);
                        $('input[name="lamination_type_c"').val(result.page_lam_type_c);
                        $('input[name="lamination_type_d"').val(result.page_lam_type_d);
                        $('input[name="book_id"').val(result.book_id);
                        $('input[name="machine_type"').val(result.machine_type);

                        $('input[name="machine_type_page_a"').val(result.machine_type_page_a);
                        $('input[name="machine_type_page_b"').val(result.machine_type_page_b);
                        $('input[name="machine_type_page_c"').val(result.machine_type_page_c);
                        $('input[name="machine_type_page_d"').val(result.machine_type_page_d);

                        $('input[name="machine_run_page_a"').val(result.machine_run_page_a);
                        $('input[name="machine_run_page_b"').val(result.machine_run_page_b);
                        $('input[name="machine_run_page_c"').val(result.machine_run_page_c);
                        $('input[name="machine_run_page_d"').val(result.machine_run_page_d);

                        $('input[name="types"').val(result.types);
                        if (result.types === "digital") {
                            $('input[name="digital_machine_type"]').val(result.digital_machine_type);
                            $('input[name="digital_machine_run"]').val(result.digital_machine_run);
                            $('input[name="digital_print_side"]').val(result.digital_print_side);
                            $('input[name="digital_page_type"]').val(result.unitbanner_type);

                            $('input[name="digital_lamination_type"').val(result.digital_lamination_type);



                        }


                    <?php } else { ?>

                        $('input[name="machine_run"').val(result.machine_run);
                    <?php }


                    ?>




                    // $('input[name="machine_run"').val(result.machine_run)


                    if (result.unit_price_vat != null) {
                        $('input[name="unit_price_vat"]').val(result.unit_price_vat);
                    } else {
                        $('input[name="unit_price_vat"]').val(unit_price_vats);
                    }
                    // $('input[name="unit_price_vat"]').val(result.unit_price + (result.unit_price * (result.vat / 100)));
                    $('input[name="quantity"').val(result.required_quantity);
                    $('input[name="vat"').val(result.vat);

                    <?php if ($type == 'banner') { ?>

                        $('input[name="unitbanner_type"').val(result.unitbanner_type);
                        $('input[name="width"').val(result.width);
                        $('input[name="lengths"').val(result.lengths);
                        $('input[name="commitonprice"').val(result.commitonprice);
                        $('input[name="cvat"').val(result.cvat);
                        $('input[name="product"').val(result.product);
                        $('input[name="banner_id"').val(result.banner_id);
                        $('input[name="banner_metal"').val(result.banner_metal);
                        $('input[name="banner_metal_type"').val(result.banner_metal_type);
                    <?php } ?>


                    <?php if ($type == 'bag') { ?>

                        $('input[name="bag_id"').val(result.bag_id);
                        $('input[name="paper_id"').val(result.paper_type);
                        $('input[name="lamination_type"').val(result.lam_type);
                        $('input[name="required_paper_full"').val(result.required_paper);
                        $('input[name="machine_type"').val(result.machine_type);


                    <?php } ?>


                    <?php if ($type == 'single_page') { ?>

                        $('input[name="page_id"').val(result.page_id);
                        $('input[name="single_page_id"').val(result.single_page_id);
                        $('input[name="machine_type"').val(result.machine_type);
                    <?php } ?>


                    <?php if ($type == 'digital') { ?>


                        $('input[name="digital_id"').val(result.digital_id);
                    <?php } ?>

                    <?php if ($type == 'otherdigital') { ?>


                        $('input[name="otherdigital_id"').val(result.otherdigital_id);
                        $('input[name="unit_digital_type"').val(result.unit_digital_type);


                    <?php } ?>

                    <?php if ($type == 'design') { ?>


                        $('input[name="design_id"').val(result.digital_id);
                    <?php } ?>

                    <?php if ($type == 'multi_page') { ?>

                        $('input[name="page_id_a"').val(result.page_id_a);

                        $('input[name="page_id_b"').val(result.page_id_b);
                        $('input[name="page_id_c"').val(result.page_id_c);
                        $('input[name="page_id_d"').val(result.page_id_d);
                        $('input[name="nopage_a"').val(result.nopage_a);
                        $('input[name="nopage_b"').val(result.nopage_b);
                        $('input[name="nopage_c"').val(result.nopage_c);
                        $('input[name="nopage_d"').val(result.nopage_d);
                        $('input[name="lamination_type').val(result.lam_type);
                        $('input[name="multi_page_id').val(result.multi_page_id);
                        $('input[name="machine_type"').val(result.machine_type);
                        $('input[name="machine_type_page_a"').val(result.machine_type_page_a);
                        $('input[name="machine_type_page_b"').val(result.machine_type_page_b);
                        $('input[name="machine_type_page_c"').val(result.machine_type_page_c);
                        $('input[name="machine_type_page_d"').val(result.machine_type_page_d);

                        $('input[name="machine_run_page_a"').val(result.machine_run_page_a);
                        $('input[name="machine_run_page_b"').val(result.machine_run_page_b);
                        $('input[name="machine_run_page_c"').val(result.machine_run_page_c);
                        $('input[name="machine_run_page_d"').val(result.machine_run_page_d);

                    <?php } ?>



                    <?php if ($type == 'brocher') { ?>

                        $('input[name="required_paper_full"').val(result.required_paper_full);
                        $('input[name="paper_id"').val(result.paper_id);
                        $('input[name="lamination_type"').val(result.lam_type);
                        $('input[name="brocher_id"').val(result.brocher_id);

                        $('input[name="machine_type"').val(result.machine_type);

                    <?php } ?>

                    calculate()
                }, 'json')
            })

            $('form#pay').on('submit', function(event) {
                event.preventDefault();
                $.post('api/pay.php?type=<?= $type ?>', $(this).serialize(), (data, status) => {
                    if (status == 'success') {
                        swal({
                            title: "Successfully added payment",
                            text: data || "Successfully added payment for <?= $type ?>.",
                            icon: "success",
                            buttons: {
                                confirm: {
                                    text: "OK",
                                    value: "ok",
                                    className: "btn btn-primary"
                                },
                                next: {
                                    text: "Next Section",
                                    value: "next",
                                    className: "btn btn-secondary"
                                }
                            }
                        }).then((value) => {
                            if (value === "next") {
                                window.location.href = "../payment/bank_statment.php"; // Redirect to the next PHP page
                            } else {
                                location.reload(); // Reload the current page
                            }
                        });
                    } else {
                        swal({
                            title: "Unknown error occurred",
                            text: data || "Error occurred while adding payment for <?= $type ?>.",
                            icon: "error",
                        });
                    }
                }).catch(err => {
                    console.log(err);
                    swal({
                        title: "Unknown error occurred: " + err.statusText,
                        text: "Error occurred while adding payment for <?= $type ?>.",
                        icon: "error",
                    });
                });
            });


            // update
            $('form#update').on('submit', function(event) {
                event.preventDefault()
                $.post(`
                                                    api / get.php ? type = <?= $type ?>`, $(this).serialize(), (data, status) => {
                    if (status == 'success') {
                        swal({
                            title: "Succssfully updated",
                            text: data || "successfully updated the <?= $type ?>.",
                            icon: "success",
                        }).then(result => {
                            location.reload()
                        })
                    } else {
                        swal({
                            title: "Unknow error occured",
                            text: data || "error occured while updating the <?= $type ?>",
                            icon: "error",
                        })
                    }
                }).catch(err => {
                    swal({
                        title: "Unknow error occured: " + err.statusText,
                        text: "error occured while updating <?= $type ?>",
                        icon: "error",
                    })
                })
            })
        })
    </script>

</body>

</html>



<?php

if (isset($_POST['filter'])) {
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];

    echo "<script>window.location = 'database.php?type=$type&from_date=$from_date&to_date=$to_date'; </script>";
}



?>
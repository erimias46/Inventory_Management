<?php



$redirect_link = "../../";
$side_link = "../../";

include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php';

$user_id = $_SESSION['user_id'];

$from_date = $_GET['from'] ?? '';
$to_date = $_GET['to'] ?? '';
?>

<head>
    <?php
    $title = 'Sale cosmetics';
    include $redirect_link . 'partials/title-meta.php'; ?>

    <?php include $redirect_link . 'partials/head-css.php'; ?>
</head>


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
                            Filter Sales
                        </p>
                    </div>
                    <div class="p-6">
                        <form method="POST">
                            <p class="mt-2 text-gray-800 dark:text-gray-400">
                            <div class="flex gap-2">
                                <div class="mb-3">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2"> From Date
                                    </label>
                                    <input type="date" name="from_date" class="form-input" required>
                                </div>
                                <div class="mb-3">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2"> To Date </label>
                                    <input type="date" name="to_date" class="form-input" required>
                                </div>
                            </div>
                            </p>
                            <div class="flex justify-end">
                                <a href="?" class="btn btn-sm bg-danger text-white rounded-full me-2">
                                    <i class="msr text-base me-2">restart_alt</i>
                                    Reset
                                </a>
                                <button name="filter" type="submit"
                                    class="btn btn-sm bg-success text-white rounded-full">
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
                            <h4 class="text-slate-900 dark:text-slate-200 text-lg font-medium">Sales</h4>
                            <div>

                                <?php if ($addButtonVisible) : ?>

                                    <button type="button" data-fc-type="modal" data-fc-target="addModal"
                                        class="btn btn-sm rounded-full bg-success/25 text-success hover:bg-success hover:text-white">
                                        <i class="msr text-base me-2">add</i>
                                        Add Sales
                                    </button>

                                <?php endif; ?>


                                <?php if ($generateButtonVisible) : ?>
                                    <a href="export.php?type=sales&from_date=<?php echo $from_date; ?>&to_date=<?php echo $to_date; ?>"
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
                                    <table id="zero_config" data-order='[[ 2, "dsc" ]]'
                                        class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Sales Date</th>
                                                <th>Action</th>
                                                <th>cosmetics Name</th>
                                                <th>Size</th>
                                                <th>Total</th>
                                                <th>Cash </th>
                                                <th>Bank</th>
                                                <th>Method</th>

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

                                            $customer_condition = '';
                                            if (!empty($_GET['customer'])) {
                                                $get_customer = $_GET['customer'];
                                                $customer_condition = "AND customer = '$get_customer'";
                                            }

                                            $sql = "SELECT * 
        FROM cosmetics_sales 
        WHERE DATE(sales_date) >= '$from_date' 
        AND DATE(sales_date) <= '$to_date' 
        $customer_condition
        AND status = 'active'
        ORDER BY sales_id DESC";




                                            $result = mysqli_query($con, $sql);
                                            while ($row = mysqli_fetch_assoc($result)) {
                                            ?>
                                                <tr
                                                    class="odd:bg-white even:bg-gray-100 dark:odd:bg-slate-700 dark:even:bg-slate-800">

                                                    <td
                                                        class="px-6 90-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['sales_id']; ?></td>
                                                    <td
                                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['sales_date']; ?></td>
                                                    <td
                                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">

                                                        <?php if ($deleteButtonVisible) : ?>

                                                            <a id="del-btn"
                                                                href="api/remove.php?id=<?php echo $row['sales_id']; ?>&from=cosmetics_sales"
                                                                class="btn bg-danger/25 text-danger hover:bg-danger hover:text-white btn-sm rounded-full"><i
                                                                    class="mgc_delete_2_line text-base me-2"></i> Delete</a>

                                                        <?php endif; ?>



                                                        <?php if ($updateButtonVisible) : ?>

                                                            <button type="button"
                                                                class="btn bg-warning/25 text-warning hover:bg-warning hover:text-white btn-sm rounded-full"
                                                                data-fc-type="modal"
                                                                data-fc-target="edit<?= $row['sales_id'] ?>">
                                                                <i class="mgc_pencil_line text-base me-2"></i>
                                                                Edit
                                                            </button>
                                                        <?php endif; ?>

                                                        <?php if ($deleteButtonVisible) : ?>

                                                            <a id="del-btn"
                                                                href="api/refund.php?id=<?php echo $row['sales_id']; ?>&from=cosmetics_sales"
                                                                class="btn bg-info/25 text-info hover:bg-info hover:text-white btn-sm rounded-full"><i
                                                                    class="mgc_delete_2_line text-base me-2"></i> Refund</a>

                                                        <?php endif; ?>

                                                        <?php if ($updateButtonVisible) : ?>

                                                            <button type="button"
                                                                class="btn bg-warning/25 text-warning hover:bg-warning hover:text-white btn-sm rounded-full"
                                                                data-fc-type="modal"
                                                                data-fc-target="exchange<?= $row['sales_id'] ?>">
                                                                <i class="mgc_pencil_line text-base me-2"></i>
                                                                Exchange
                                                            </button>
                                                        <?php endif; ?>





                                                    </td>

                                                    <td
                                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['cosmetics_name']; ?></td>
                                                    <td
                                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['size']; ?></td>
                                                    <td
                                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['price']; ?></td>
                                                    <td
                                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['cash']; ?></td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php
                                                        echo $row['bank'];
                                                        if (!empty($row['bank_name'])) {
                                                            echo " (" . $row['bank_name'] . ")";
                                                        }
                                                        ?>
                                                    </td>

                                                    <td
                                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['method']; ?></td>



                                                </tr>




                                                <!-- Modal for exchanging jeans -->
                                                <div id="exchange<?= $row['sales_id'] ?>" class="w-full h-full fixed top-0 left-0 z-50 transition-all duration-500 hidden">
                                                    <div class="fc-modal-open:mt-7 fc-modal-open:opacity-100 fc-modal-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-md sm:w-full m-3 sm:mx-auto bg-white border shadow-sm rounded-md dark:bg-slate-800 dark:border-gray-700">
                                                        <div class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
                                                            <h3 class="font-medium text-gray-800 dark:text-white text-lg">Exchange cosmetics</h3>
                                                            <button class="inline-flex flex-shrink-0 justify-center items-center h-8 w-8 dark:text-gray-200" data-fc-dismiss type="button">
                                                                <span class="material-symbols-rounded">close</span>
                                                            </button>
                                                        </div>
                                                        <form method="POST" class="overflow-y-auto">
                                                            <div class="px-4 py-8">
                                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                                                    <input type="hidden" name="sales_id" value="<?= $row['sales_id']; ?>">



                                                                    <div>
                                                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">cosmetics Name</label>
                                                                        <select name="cosmetics_name" class="search-select">
                                                                            <?php
                                                                            $sql3 = "SELECT * FROM cosmetics GROUP BY cosmetics_name ORDER BY cosmetics_name ASC";
                                                                            $result3 = mysqli_query($con, $sql3);
                                                                            if (mysqli_num_rows($result3) > 0) {
                                                                                while ($row3 = mysqli_fetch_assoc($result3)) { ?>
                                                                                    <option value="<?= $row3['cosmetics_name'] ?>"><?= $row3['cosmetics_name'] ?></option>
                                                                            <?php }
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </div>

                                                                    <div>
                                                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">cosmetics Size</label>
                                                                        <select name="size" class="search-select">
                                                                            <?php
                                                                            $sql4 = "SELECT * FROM cosmeticsdb";
                                                                            $result4 = mysqli_query($con, $sql4);
                                                                            if (mysqli_num_rows($result4) > 0) {
                                                                                while ($row4 = mysqli_fetch_assoc($result4)) { ?>
                                                                                    <option value="<?= $row4['size'] ?>"><?= $row4['size'] ?></option>
                                                                            <?php }
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </div>

                                                                    <div>
                                                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">New Price</label>
                                                                        <input type="number" step="0.01" name="price" class="form-input" required>
                                                                    </div>

                                                                    <div>
                                                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">Bank</label>
                                                                        <input type="number" name="bank" id="bankInput" class="form-input" required oninput="checkBankValue()" value="0">
                                                                    </div>

                                                                    <div id="bankNameDiv">
                                                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">Bank Name</label>
                                                                        <select name="bank_name" id="bankNameInput" class="selectize">
                                                                            <option value="">Select</option>
                                                                            <?php
                                                                            $sql5 = "SELECT * FROM bankdb";
                                                                            $result5 = mysqli_query($con, $sql5);
                                                                            if (mysqli_num_rows($result5) > 0) {
                                                                                while ($row5 = mysqli_fetch_assoc($result5)) { ?>
                                                                                    <option value="<?= $row5['bankname'] ?>"><?= $row5['bankname'] ?></option>
                                                                            <?php }
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </div>

                                                                    <div>
                                                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">Cash</label>
                                                                        <input type="number" name="cash" step="0.01" class="form-input" required value="0">
                                                                    </div>

                                                                    <div>
                                                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">Date</label>
                                                                        <input type="date" name="date" class="form-input" required value="<?= date('Y-m-d'); ?>">
                                                                    </div>

                                                                    <div>
                                                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">Quantity</label>
                                                                        <input type="text" name="quantity" class="form-input" required value="1">
                                                                    </div>

                                                                    <div>
                                                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">Method</label>
                                                                        <select name="method" class="selectize">
                                                                            <option value="shop">Shop</option>
                                                                            <option value="delivery">Delivery</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="flex justify-end items-center gap-4 p-4 border-t dark:border-slate-700">
                                                                <button class="py-2 px-5 inline-flex justify-center items-center gap-2 rounded dark:text-gray-200 border dark:border-slate-700 font-medium hover:bg-slate-100 hover:dark:bg-slate-700 transition-all" data-fc-dismiss type="button">Close</button>
                                                                <button name="exchange_cosmetics" type="submit" class="btn bg-success text-white">Exchange</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>




                                                <div id="edit<?= $row['sales_id'] ?>" class="w-full h-full fixed top-0 left-0 z-50 transition-all duration-500 hidden">
                                                    <div class="fc-modal-open:mt-7 fc-modal-open:opacity-100 fc-modal-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-md sm:w-full m-3 sm:mx-auto bg-white border shadow-sm rounded-md dark:bg-slate-800 dark:border-gray-700">
                                                        <div class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
                                                            <h3 class="font-medium text-gray-800 dark:text-white text-lg">Edit Sales</h3>
                                                            <button class="inline-flex flex-shrink-0 justify-center items-center h-8 w-8 dark:text-gray-200" data-fc-dismiss type="button">
                                                                <span class="material-symbols-rounded">close</span>
                                                            </button>
                                                        </div>
                                                        <form method="POST" class="overflow-y-auto">
                                                            <div class="px-4 py-8">
                                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                                                    <input type="hidden" name="sales_id" value="<?= $row['sales_id']; ?>">

                                                                    <?php
                                                                    $sql2 = "SELECT * FROM cosmetics_sales WHERE sales_id = " . $row['sales_id'];
                                                                    $result2 = mysqli_query($con, $sql2);
                                                                    $row2 = mysqli_fetch_assoc($result2);
                                                                    ?>

                                                                    <div>
                                                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">cosmetics Name</label>
                                                                        <select name="cosmetics_name" class="search-select" id="jeans_name_select" disabled>
                                                                            <?php
                                                                            $sql3 = "SELECT * FROM cosmetics GROUP BY cosmetics_name ORDER BY cosmetics_name ASC";
                                                                            $result3 = mysqli_query($con, $sql3);
                                                                            if (mysqli_num_rows($result3) > 0) {
                                                                                while ($row3 = mysqli_fetch_assoc($result3)) { ?>
                                                                                    <option value="<?= $row3['cosmetics_name'] ?>"
                                                                                        <?php
                                                                                        // Check if the current jeans_name should be selected
                                                                                        if (isset($row['cosmetics_name']) && $row['cosmetics_name'] == $row3['cosmetics_name']) {
                                                                                            echo "selected";
                                                                                        }
                                                                                        ?>>
                                                                                        <?= $row3['cosmetics_name'] ?>
                                                                                    </option>
                                                                            <?php }
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </div>


                                                                    <div>
                                                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">cosmetics Size</label>
                                                                        <select name="size" class="search-select" disabled>
                                                                            <?php
                                                                            $sql4 = "SELECT * FROM cosmeticsdb";
                                                                            $result4 = mysqli_query($con, $sql4);
                                                                            if (mysqli_num_rows($result4) > 0) {
                                                                                while ($row4 = mysqli_fetch_assoc($result4)) { ?>
                                                                                    <option value="<?= $row4['size'] ?>"
                                                                                        <?php if (isset($row['size']) && $row['size'] == $row4['size']) echo 'selected'; ?>>
                                                                                        <?= $row4['size'] ?>
                                                                                    </option>
                                                                            <?php }
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </div>


                                                                    <div>
                                                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">Price</label>
                                                                        <input type="number" step="0.01" name="price" class="form-input" required value="<?php echo $row['price']; ?>">
                                                                    </div>

                                                                    <div>
                                                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">Bank</label>
                                                                        <input type="number" name="bank" id="bankInput" class="form-input" required oninput="checkBankValue()" value="<?php echo $row['bank'] ?>">
                                                                    </div>

                                                                    <div id="bankNameDiv">
                                                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">Bank Name</label>
                                                                        <select name="bank_name" id="bankNameInput" class="selectize">
                                                                            <option value="">Select</option>
                                                                            <?php
                                                                            $sql5 = "SELECT * FROM bankdb";
                                                                            $result5 = mysqli_query($con, $sql5);
                                                                            if (mysqli_num_rows($result5) > 0) {
                                                                                while ($row5 = mysqli_fetch_assoc($result5)) { ?>
                                                                                    <option value="<?= $row5['bankname'] ?>"
                                                                                        <?php if (isset($row['bank_name']) && $row['bank_name'] == $row5['bankname']) echo 'selected'; ?>>
                                                                                        <?= $row5['bankname'] ?>
                                                                                    </option>
                                                                            <?php }
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </div>


                                                                    <div>
                                                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">Cash</label>
                                                                        <input type="number" name="cash" step="0.01" class="form-input" required value="<?php echo $row['cash'] ?>">
                                                                    </div>

                                                                    <div>
                                                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">Date</label>
                                                                        <input type="date" name="date" class="form-input" required value="<?= date('Y-m-d'); ?>">
                                                                    </div>

                                                                    <div>
                                                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">Quantity</label>
                                                                        <input type="text" name="quantity" class="form-input" required value="<?php echo $row['quantity'] ?>" disabled>
                                                                    </div>

                                                                    <div>
                                                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">Method</label>
                                                                        <select name="method" class="selectize" disabled>
                                                                            <option value="shop" <?php if (isset($row['method']) && $row['method'] == 'shop') echo 'selected'; ?>>Shop</option>
                                                                            <option value="delivery" <?php if (isset($row['method']) && $row['method'] == 'delivery') echo 'selected'; ?>>Delivery</option>
                                                                        </select>
                                                                    </div>

                                                                </div>
                                                            </div>

                                                            <div class="flex justify-end items-center gap-4 p-4 border-t dark:border-slate-700">
                                                                <button class="py-2 px-5 inline-flex justify-center items-center gap-2 rounded dark:text-gray-200 border dark:border-slate-700 font-medium hover:bg-slate-100 hover:dark:bg-slate-700 transition-all" data-fc-dismiss type="button">Close</button>
                                                                <button name="update" type="submit" class="btn bg-warning text-white">Edit</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>


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



                <div id="addModal" class="w-full h-full fixed top-0 left-0 z-50 transition-all duration-500 hidden">
                    <div
                        class="fc-modal-open:mt-7 fc-modal-open:opacity-100 fc-modal-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-md sm:w-full m-3 sm:mx-auto  bg-white border shadow-sm rounded-md dark:bg-slate-800 dark:border-gray-700">
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
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">


                                    <div class="mb-3">
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">
                                            cosmetics Name </label>

                                        <select name="cosmetics_name" class="search-select" required onchange="fetchPrice()">

                                            <?php

                                            $sql = "SELECT * FROM cosmetics GROUP BY cosmetics_name ORDER BY cosmetics_name ASC";
                                            $result = mysqli_query($con, $sql);
                                            while ($row = mysqli_fetch_assoc($result)) {
                                            ?>
                                                <option value="<?php echo $row['cosmetics_name'] ?>" <?php
                                                                                                    if (isset($cosmetics_name)) {
                                                                                                        if ($row['cosmetics_name'] == $cosmetics_name) {
                                                                                                            echo "selected";
                                                                                                        }
                                                                                                    }
                                                                                                    ?>>
                                                    <?php echo $row['cosmetics_name']; ?>
                                                </option>
                                            <?php
                                            }
                                            ?>

                                        </select>

                                    </div>


                                    <div class="mb-3">
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">
                                            cosmetics Size </label>

                                        <select name="size" class="search-select" required onchange="fetchPrice()">

                                            <?php

                                            $sql = "SELECT * FROM cosmeticsdb";
                                            $result = mysqli_query($con, $sql);
                                            while ($row = mysqli_fetch_assoc($result)) {
                                            ?>
                                                <option value="<?php echo $row['size'] ?>" <?php
                                                                                            if (isset($cosmetics_size)) {
                                                                                                if ($row['size'] == $cosmetics_size) {
                                                                                                    echo "selected";
                                                                                                }
                                                                                            }
                                                                                            ?>>
                                                    <?php echo $row['size']; ?>
                                                </option>
                                            <?php
                                            }
                                            ?>




                                        </select>

                                    </div>



                                    <div class="mb-3">
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">
                                            Method </label>

                                        <select name="method" class="search-select" required>

                                            <option value="shop">Shop</option>
                                            <option value="delivery">Delivery</option>

                                        </select>

                                    </div>

                                    <div class="mb-3">
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">Quantity
                                        </label>
                                        <input type="text" name="quantity" class="form-input" value="1"
                                            onchange="fetchPrice()" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">Cash</label>
                                        <input type="text" name="cash"  id="cash" class="form-input" value="0" required onchange="calculateTotalPrice()">
                                    </div>

                                    <div class="mb-3">
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">Bank</label>
                                        <input type="text" name="bank"  id="bank" class="form-input" value="0" required id="bankInput" onchange="calculateTotalPrice();">
                                    </div>

                                    <div class="mb-3">
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">Total Price</label>
                                        <input type="text" name="price" id="total_price" class="form-input" value="0" required readonly>
                                    </div>



                                    <div class="mb-3">
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">Bank
                                            Name</label>
                                        <select name="bank_name" class="form-input">
                                            <option value="">Select</option>
                                            <?php
                                            $sql = "SELECT * FROM bankdb ";
                                            $result = mysqli_query($con, $sql);
                                            while ($row = mysqli_fetch_assoc($result)) {
                                            ?>
                                                <option value="<?php echo $row['bankname'] ?>" <?php
                                                                                                if (isset($bank_name)) {
                                                                                                    if ($row['bank_name'] == $bank_name) {
                                                                                                        echo "selected";
                                                                                                    }
                                                                                                }
                                                                                                ?>>
                                                    <?php echo $row['bankname']; ?>
                                                </option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <div class="flex gap-2  justify-around">

                                        <div class="mb-3">
                                            <label class="text-gray-800 text-sm font-medium inline-block mb-2">
                                                Date </label>
                                            <input type="date" name="date" class="form-input"
                                                value="<?php echo date('Y-m-d'); ?>" required>
                                        </div>

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
                                    Sales</button>
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

</body>







</html>


<script>
    function calculateTotalPrice() {
        // Retrieve the input values
        var cashValue = document.querySelector('input[id="cash"]').value;
        var bankValue = document.querySelector('input[id="bank"]').value;

        // Parse the values, default to 0 if not a valid number
        var cash = parseFloat(cashValue) || 0;
        var bank = parseFloat(bankValue) || 0;

        // Console log for debugging
        console.log("Cash: " + cash);
        console.log("Bank: " + bank);

        // Calculate the total price
        var totalPrice = cash + bank;

        // Update the total price input field
        document.querySelector('input[id="total_price"]').value = totalPrice.toFixed(2); // Display with 2 decimals
    }
</script>

<?php

if (isset($_POST['exchange_cosmetics'])) {

    include('api/exchange.php');
}


if (isset($_POST['update'])) {
    $sales_id = $_POST['sales_id'];
    $user_id = $_SESSION['user_id'];
    $cosmetics_name = $_POST['cosmetics_name'];
    $size = $_POST['size'];
    $price = $_POST['price'];
    $cash = $_POST['cash'];
    $bank = $_POST['bank'];
    $method = $_POST['method'];
    $date = $_POST['date'];
    $quantity = $_POST['quantity'];


    if ($bank == 0) {
        $bank_name = null;
        $bank_id = null;
    } else {
        $bank_name = $_POST['bank_name'];
        $sql = "SELECT * FROM bankdb WHERE bankname = '$bank_name'";
        $result = mysqli_query($con, $sql);
        $row = mysqli_fetch_assoc($result);
        $bank_id = $row['id'];
    }


    $sql = "UPDATE cosmetics_sales SET  price = '$price', cash = '$cash', bank = '$bank', update_date = '$date', user_id = '$user_id', bank_id = '$bank_id', bank_name = '$bank_name' WHERE sales_id = '$sales_id'";
    $result = mysqli_query($con, $sql);

    if ($result) {
        echo "<script>window.location = 'action.php?status=success&redirect=sale_cosmetics.php'; </script>";
    } else {
        echo "<script>window.location = 'action.php?status=error&redirect=sale_cosmetics.php'; </script>";
    }
}


if (isset($_POST['add_data'])) {

    $user_id = $_SESSION['user_id'];
    $cosmetics_name = $_POST['cosmetics_name'];
    $size = $_POST['size'];
    $price = $_POST['price'];
    $cash = $_POST['cash'];
    $bank = $_POST['bank'];
    $method = $_POST['method'];
    $date = $_POST['date'];
    $quantity = $_POST['quantity'];
    if ($bank == 0) {
        $bank_name = null;
        $bank_id = null;
    } else {
        $bank_name = $_POST['bank_name'];
        $sql = "SELECT * FROM bankdb WHERE bankname = '$bank_name'";
        $result = mysqli_query($con, $sql);
        $row = mysqli_fetch_assoc($result);
        $bank_id = $row['id'];
    }




    if (!empty($cosmetics_name) && !empty($size)) {
        $sql = "SELECT * FROM cosmetics WHERE cosmetics_name = '$cosmetics_name' AND size = '$size'";
        $result = mysqli_query($con, $sql);
        $row = mysqli_fetch_assoc($result);

        if (!$row) {
            $sql = "SELECT * FROM cosmetics WHERE cosmetics_name = '$cosmetics_name'";
            $result = mysqli_query($con, $sql);
            $row = mysqli_fetch_assoc($result);
            $price = $row['price'];
            $image = $row['image'];
            $type = $row['type'];
            $type_id = $row['type_id'];


            $sql2 = "SELECT * FROM cosmeticsdb WHERE size = '$size'";
            $result2 = mysqli_query($con, $sql2);
            $row2 = mysqli_fetch_assoc($result2);
            $size_id = $row2['id'];

            $quantity = $_POST['quantity'];

            $add_cosmetics = "INSERT INTO `cosmetics_verify`(`cosmetics_name`, `size`, `price`, `quantity`, `image`, `type`, `type_id`, `size_id`, `active`, `error`) 
                          VALUES ('$cosmetics_name', '$size', '$price', '$quantity', '$image', '$type', '$type_id', '$size_id', '0','1')";
            $result_add = mysqli_query($con, $add_cosmetics);

            if ($result_add) {
                echo "<script>window.location = 'action.php?status=error&redirect=sale_cosmetics.php'; </script>";
            }

            exit;
        }



        $cosmetics_id = $row['id'];
        $size_id = $row['size_id'];
        $current_quantity = $row['quantity'];

        if ($current_quantity < $quantity) {

            $sql = "SELECT * FROM cosmetics WHERE cosmetics_name = '$cosmetics_name' AND size = '$size'";
            $result = mysqli_query($con, $sql);
            $row = mysqli_fetch_assoc($result);
            $price = $row['price'];
            $image = $row['image'];
            $type = $row['type'];
            $type_id = $row['type_id'];
            $size_id = $row['size_id'];

            $add_cosmetics = "INSERT INTO `cosmetics_verify`(`cosmetics_name`, `size`, `price`, `quantity`, `image`, `type`, `type_id`, `size_id`, `active`, `error`) 
                              VALUES ('$cosmetics_name', '$size', '$price', '$quantity', '$image', '$type', '$type_id', '$size_id', '0','2')";
            $result_add = mysqli_query($con, $add_cosmetics);

            if ($result_add) {
                echo "<script>window.location = 'action.php?status=error&redirect=sale_cosmetics.php'; </script>";
                exit;
            }

            exit;
        }





        if ($method == 'delivery') {

            $status = "pending";
            $sql = "INSERT into cosmetics_delivery (cosmetics_id, size_id, cosmetics_name, size, price, cash, bank, method, sales_date, update_date, quantity, user_id,bank_id,bank_name,status)
            VALUES ('$cosmetics_id', '$size_id', '$cosmetics_name', '$size', '$price', '$cash', '$bank', '$method', '$date', '$date', '$quantity', '$user_id','$bank_id','$bank_name','$status')";
            $result = mysqli_query($con, $sql);

            $new_quantity = $current_quantity - $quantity;
            $update_quantity = "UPDATE cosmetics SET quantity = '$new_quantity' WHERE id = '$cosmetics_id' AND size = '$size'";
            $result_update = mysqli_query($con, $update_quantity);



            // No need to check result_add, result_adds, or result_update for delivery
            if (!$result || !$result_update) {
                echo "<script>window.location = 'action.php?status=error&redirect=sale_cosmetics.php'; </script>";
                exit;
            }
        } else {
            $add_sales = "INSERT INTO `cosmetics_sales`(`cosmetics_id`, `size_id`, `cosmetics_name`, `size`, `price`, `cash`, `bank`, `method`, `sales_date`, `update_date`, `quantity`, `user_id`,`bank_id`,`bank_name`,`status`) 
                  VALUES ('$cosmetics_id', '$size_id', '$cosmetics_name', '$size', '$price', '$cash', '$bank', '$method', '$date', '$date', '$quantity', '$user_id','$bank_id','$bank_name','active')";
            $result_add = mysqli_query($con, $add_sales);

            $status = "sold";

            $add_cosmetics_log = "INSERT INTO `cosmetics_sales_log`(`cosmetics_id`, `size_id`, `cosmetics_name`, `size`, `price`, `cash`, `bank`, `method`, `sales_date`, `update_date`, `quantity`, `user_id`, `status`) 
                      VALUES ('$cosmetics_id', '$size_id', '$cosmetics_name', '$size', '$price', '$cash', '$bank', '$method', '$date', '$date', '$quantity', '$user_id', '$status')";
            $result_adds = mysqli_query($con, $add_cosmetics_log);

            $new_quantity = $current_quantity - $quantity;
            $update_quantity = "UPDATE cosmetics SET quantity = '$new_quantity' WHERE id = '$cosmetics_id' AND size = '$size'";
            $result_update = mysqli_query($con, $update_quantity);

            // Check only for errors in non-delivery cases
            if (!$result_add || !$result_adds || !$result_update) {
                echo "<script>window.location = 'action.php?status=error&redirect=sale_cosmetics.php'; </script>";
                exit;
            }
        }

        // Notify subscribers for both delivery and sales
        $subscribers_query = "SELECT chat_id FROM subscribers";
        $subscribers_result = mysqli_query($con, $subscribers_query);
        $subscribers = mysqli_fetch_all($subscribers_result, MYSQLI_ASSOC);

        $message = "New Sale Added:\n";
        $message .= "cosmetics Name: $cosmetics_name\n";
        $message .= "Size: $size\n";
        $message .= "Price: $price\n";
        $message .= "Cash: $cash\n";
        $message .= "Bank: $bank\n";
        $message .= "Method: $method\n";
        $message .= "Date: $date\n";
        $message .= "Quantity: $quantity\n";

        $botToken = "7048538445:AAFH9g9L2EHfmH8mHK7N8CPt82INxhdzev0"; // Replace with your bot token
        $apiUrl = "https://api.telegram.org/bot$botToken/sendMessage";

        foreach ($subscribers as $subscriber) {
            $chatId = $subscriber['chat_id'];

            $data = [
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => 'HTML' // Optional: Use HTML formatting
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $apiUrl);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);
        }

        // Success redirect
        echo "<script>window.location = 'action.php?status=success&redirect=sale_cosmetics.php'; </script>";
    }
}




if (isset($_POST['filter'])) {
    $from = $_POST['from_date'];
    $to = $_POST['to_date'];
    $customer = $_POST['customer'];
    echo "<script>window.location = 'sale_cosmetics.php?from=$from&to=$to&customer=$customer'; </script>";
}




?>
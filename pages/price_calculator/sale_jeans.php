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
    $title = 'Sale Jeans';
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
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2"> From Date </label>
                                    <input type="date" name="from_date" class="form-input"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2"> To Date </label>
                                    <input type="date" name="to_date" class="form-input"
                                        required>
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
                                    <table id="zero_config"
                                        class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead>
                                            <tr>
                                                <th>Sales Date</th>
                                                <th>Action</th>
                                                <th>ID</th>
                                                <th>Jeans Name</th>
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

                                            $customer = '';
                                            if (!empty($_GET['customer'])) {
                                                $get_customer = $_GET['customer'];
                                                $customer = "customer = '$get_customer'";
                                            } else {
                                                $customer = '';
                                            }

                                            if (!$customer) {
                                                $sql = "SELECT * 
                                                FROM sales 
                                                WHERE DATE(sales_date) >= '$from_date' 
                                                AND DATE(sales_date) <= '$to_date'
                                                ORDER BY sales_id DESC";
                                            } else {
                                                $sql = "SELECT * FROM sales WHERE DATE(sales_date) >= '$from_date' AND DATE(sales_date) <= '$to_date' AND {$customer} ORDER BY sales_id DESC";
                                            }
                                            $result = mysqli_query($con, $sql);
                                            while ($row = mysqli_fetch_assoc($result)) {
                                            ?>
                                                <tr
                                                    class="odd:bg-white even:bg-gray-100 dark:odd:bg-slate-700 dark:even:bg-slate-800">
                                                    <td
                                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['sales_date']; ?></td>
                                                    <td
                                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">

                                                        <?php if ($deleteButtonVisible) : ?>

                                                            <a id="del-btn" href="api/remove.php?id=<?php echo $row['sales_id']; ?>&from=sales"
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
                                                        <?php echo $row['jeans_name']; ?></td>
                                                    <td
                                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['size']; ?></td>
                                                    <td
                                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['price']; ?></td>
                                                    <td
                                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['cash']; ?></td>
                                                    <td
                                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['bank']; ?></td>
                                                    <td
                                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['method']; ?></td>



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



                                                                <div class="mb-3">
                                                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Jeans Name </label>
                                                                    <input type="text" name="jeans_name" class="form-input"
                                                                        value="<?= $row['jeans_name'] ?>" required>
                                                                </div>

                                                                <div class="flex gap-2 justify-between">
                                                                    <div class="mb-3">
                                                                        <label
                                                                            class="text-gray-800 text-sm font-medium inline-block mb-2">Size
                                                                        </label>
                                                                        <input type="text" name="size"
                                                                            class="form-input"
                                                                            value="<?= $row['size'] ?>" required>
                                                                    </div>

                                                                    <div class="mb-3">
                                                                        <label
                                                                            class="text-gray-800 text-sm font-medium inline-block mb-2">Price
                                                                            Before Vat</label>
                                                                        <input type="text" name="price"
                                                                            class="form-input"
                                                                            value="<?= $row['price'] ?>"
                                                                            required>
                                                                    </div>

                                                                </div>
                                                                <div class="flex gap-2 justify-between">
                                                                    <div class="mb-3">
                                                                        <label
                                                                            class="text-gray-800 text-sm font-medium inline-block mb-2">
                                                                            Date </label>
                                                                        <input type="date" name="date" class="form-input"
                                                                            value="<?= $row['sales_date'] ?>" required>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label
                                                                            class="text-gray-800 text-sm font-medium inline-block mb-2">
                                                                            Method</label>
                                                                        <input type="text" name="vat"
                                                                            class="form-input" value="<?php echo $row['method']; ?>"
                                                                            required>
                                                                    </div>
                                                                </div>





                                                                <div
                                                                    class="flex justify-end items-center gap-4 p-4 border-t dark:border-slate-700">
                                                                    <button
                                                                        class="py-2 px-5 inline-flex justify-center items-center gap-2 rounded dark:text-gray-200 border dark:border-slate-700 font-medium hover:bg-slate-100 hover:dark:bg-slate-700 transition-all"
                                                                        data-fc-dismiss type="button">Close
                                                                    </button>
                                                                    <button name="update_purchase" type="submit"
                                                                        class="btn bg-success text-white">Edit Sales</button>
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


                                <div class="flex gap-2 justify-evenly">
                                    <div class="mb-3">
                                        <label
                                            class="text-gray-800 text-sm font-medium inline-block mb-2">
                                            Jeans Name </label>

                                        <select name="jeans_name" class="search-select" required onchange="fetchPrice()">

                                            <?php

                                            $sql = "SELECT * FROM jeans GROUP BY jeans_name ORDER BY jeans_name ASC";
                                            $result = mysqli_query($con, $sql);
                                            while ($row = mysqli_fetch_assoc($result)) {
                                            ?>
                                                <option value="<?php echo $row['jeans_name'] ?>" <?php
                                                                                                    if (isset($jeans_name)) {
                                                                                                        if ($row['jeans_name'] == $jeans_name) {
                                                                                                            echo "selected";
                                                                                                        }
                                                                                                    }
                                                                                                    ?>>
                                                    <?php echo $row['jeans_name']; ?>
                                                </option>
                                            <?php
                                            }
                                            ?>




                                        </select>

                                    </div>

                                    <div class="mb-3">
                                        <label
                                            class="text-gray-800 text-sm font-medium inline-block mb-2">
                                            Jeans Size </label>

                                        <select name="size" class="search-select" required onchange="fetchPrice()">

                                            <?php

                                            $sql = "SELECT * FROM jeansdb";
                                            $result = mysqli_query($con, $sql);
                                            while ($row = mysqli_fetch_assoc($result)) {
                                            ?>
                                                <option value="<?php echo $row['size'] ?>" <?php
                                                                                            if (isset($jeans_size)) {
                                                                                                if ($row['size'] == $jeans_size) {
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

                                </div>



                                <div class="flex gap-2 justify-evenly">
                                    <div class="mb-3">
                                        <label
                                            class="text-gray-800 text-sm font-medium inline-block mb-2">
                                            Method </label>

                                        <select name="method" class="search-select" required>

                                            <option value="shop">Shop</option>
                                            <option value="delivery">Delivery</option>

                                        </select>

                                    </div>

                                    <div class="mb-3">
                                        <label
                                            class="text-gray-800 text-sm font-medium inline-block mb-2">Price
                                        </label>
                                        <input type="text" name="price" class="form-input" required onchange="fetchPrice()">

                                    </div>
                                    <div class="mb-3">
                                        <label
                                            class="text-gray-800 text-sm font-medium inline-block mb-2">Quantity
                                        </label>
                                        <input type="text" name="quantity"
                                            class="form-input" value="1"
                                            onchange="fetchPrice()"
                                            required>
                                    </div>


                                </div>
                                <div class="flex gap-2 justify-between">
                                    <div class="mb-3">
                                        <label
                                            class="text-gray-800 text-sm font-medium inline-block mb-2">Cash
                                        </label>
                                        <input type="text" name="cash"
                                            class="form-input" value="0"

                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <label
                                            class="text-gray-800 text-sm font-medium inline-block mb-2">Bank
                                        </label>
                                        <input type="text" name="bank"
                                            class="form-input" value="0"

                                            required>
                                    </div>
                                    <div class="flex gap-2 justify-between">
                                        <div class="mb-3">
                                            <label
                                                class="text-gray-800 text-sm font-medium inline-block mb-2">
                                                Date </label>
                                            <input type="date" name="date" class="form-input" value="<?php echo date('Y-m-d'); ?>"
                                                required>
                                        </div>

                                    </div>

                                    <div class="flex gap-2 justify-between">

                                    </div>
                                </div>
                                <div class="flex justify-end items-center gap-4 p-4 border-t dark:border-slate-700">
                                    <button
                                        class="py-2 px-5 inline-flex justify-center items-center gap-2 rounded dark:text-gray-200 border dark:border-slate-700 font-medium hover:bg-slate-100 hover:dark:bg-slate-700 transition-all"
                                        data-fc-dismiss type="button">Close
                                    </button>
                                    <button name="add_data" type="submit"
                                        class="py-2.5 px-4 inline-flex justify-center items-center gap-2 rounded bg-success hover:bg-success-600 text-white">Add Sales</button>
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

<script>
    function fetchPrice() {
        var jeansName = document.querySelector('select[name="jeans_name"]').value;
        var size = document.querySelector('select[name="size"]').value;
        var quantity = parseInt(document.querySelector('input[name="quantity"]').value);

        if (jeansName && size) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "api/fetch_price.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onload = function() {
                if (this.status === 200) {
                    var price = parseFloat(this.responseText);
                    if (!isNaN(price)) {
                        var totalPrice = (price * quantity).toFixed(2);
                        document.querySelector('input[name="price"]').value = totalPrice;
                    }
                }
            };
            xhr.send("jeans_name=" + encodeURIComponent(jeansName) + "&size=" + encodeURIComponent(size));
        }
    }

    // Event listeners for input changes
    document.querySelector('select[name="jeans_name"]').addEventListener('change', fetchPrice);
    document.querySelector('select[name="size"]').addEventListener('change', fetchPrice);
    document.querySelector('input[name="quantity"]').addEventListener('change', fetchPrice);
</script>



</html>

<?php


if (isset($_POST['update_purchase'])) {
    $customer = $_POST['customer'];
    $sales_id = $_POST['sales_id'];
    $tin_number = $_POST['tin_number'];
    $price_before_vat = $_POST['price_before_vat'];
    $date = $_POST['date'];
    $vat = $_POST['vat'];
    $machine_number = $_POST['machine_number'];
    $holding_tax = $_POST['holding_tax'];
    $receipt_number = $_POST['receipt_number'];
    $price_including_vat = $price_before_vat + ($price_before_vat * ($vat * 0.01));




    $purchase_update = "UPDATE `sales` SET `customer`='$customer', `tin_number`='$tin_number', `price_before_vat`= '$price_before_vat', `vat`='$vat', 
                `price_including_vat`='$price_including_vat', `machine_number`='$machine_number', `holding_tax`='$holding_tax', `receipt_number`='$receipt_number', 
                `sales_date`='$date', `update_date`='$date' WHERE `sales_id` = '$sales_id'";
    $result_update = mysqli_query($con, $purchase_update);

    if ($result_update) {
        echo "<script>window.location = 'action.php?status=success&redirect=purchase.php'; </script>";
    } else {
        echo "<script>window.location = 'action.php?status=error&redirect=purchase.php'; </script>";
    }
}

if (isset($_POST['add_data'])) {
    // Get user_id from the session
    $user_id = $_SESSION['user_id']; // Ensure user_id is set in the session

    $jeans_name = $_POST['jeans_name'];
    $size = $_POST['size'];
    $price = $_POST['price'];
    $cash = $_POST['cash'];
    $bank = $_POST['bank'];
    $method = $_POST['method'];
    $date = $_POST['date'];
    $quantity = $_POST['quantity'];  // Added quantity

    if (!empty($jeans_name) && !empty($size)) {
        $sql = "SELECT * FROM jeans WHERE jeans_name = '$jeans_name' AND size = '$size'";
        $result = mysqli_query($con, $sql);
        $row = mysqli_fetch_assoc($result);

        if (!$row) {
            $add_sales = "INSERT INTO `sales`(`jeans_id`, `size_id`, `jeans_name`, `size`, `price`, `cash`, `bank`, `method`, `sales_date`, `update_date`, `quantity`, `user_id`) 
                          VALUES (NULL, NULL, '$jeans_name', '$size', '$price', '$cash', '$bank', '$method', '$date', '$date', '$quantity', '$user_id')";
            $result_add = mysqli_query($con, $add_sales);

            $status = "removed_quantity";

            $add_jeans_log = "INSERT INTO `sales_log`(`jeans_id`, `size_id`, `jeans_name`, `size`, `price`, `cash`, `bank`, `method`, `sales_date`, `update_date`, `error`, `quantity`, `user_id` ,`status`) 
                              VALUES (NULL, NULL, '$jeans_name', '$size', '$price', '$cash', '$bank', '$method', '$date', '$date', 'Error: Size not found', '$quantity', '$user_id','$status')";
            $result_adds = mysqli_query($con, $add_jeans_log);

            if (!$result_add || !$result_adds) {
                echo "<script>window.location = 'action.php?status=error&redirect=sale_jeans.php'; </script>";
                exit;
            }
        } else {
            $jeans_id = $row['id'];
            $size_id = $row['size_id'];
            $current_quantity = $row['quantity'];

            $add_sales = "INSERT INTO `sales`(`jeans_id`, `size_id`, `jeans_name`, `size`, `price`, `cash`, `bank`, `method`, `sales_date`, `update_date`, `quantity`, `user_id`) 
                          VALUES ('$jeans_id', '$size_id', '$jeans_name', '$size', '$price', '$cash', '$bank', '$method', '$date', '$date', '$quantity', '$user_id')";
            $result_add = mysqli_query($con, $add_sales);

            $status = "removed_quantity";

            $add_jeans_log = "INSERT INTO `sales_log`(`jeans_id`, `size_id`, `jeans_name`, `size`, `price`, `cash`, `bank`, `method`, `sales_date`, `update_date`, `quantity`, `user_id`, `status`) 
                              VALUES ('$jeans_id', '$size_id', '$jeans_name', '$size', '$price', '$cash', '$bank', '$method', '$date', '$date', '$quantity', '$user_id', '$status')";
            $result_adds = mysqli_query($con, $add_jeans_log);

            $new_quantity = $current_quantity - $quantity;
            $update_quantity = "UPDATE jeans SET quantity = '$new_quantity' WHERE id = '$jeans_id' AND size = '$size'";
            $result_update = mysqli_query($con, $update_quantity);

            if ($new_quantity < 0) {
                $add_error_log = "INSERT INTO `sales_log`(`jeans_id`, `size_id`, `jeans_name`, `size`, `price`, `cash`, `bank`, `method`, `sales_date`, `update_date`, `error`, `quantity`, `user_id`) 
                                  VALUES ('$jeans_id', '$size_id', '$jeans_name', '$size', '$price', '$cash', '$bank', '$method', '$date', '$date', 'Error: Insufficient stock', '$quantity', '$user_id')";
                $result_error_log = mysqli_query($con, $add_error_log);
            }


            $subscribers_query = "SELECT chat_id FROM subscribers";
            $subscribers_result = mysqli_query($con, $subscribers_query);
            $subscribers = mysqli_fetch_all($subscribers_result, MYSQLI_ASSOC);

            $message = "New Sale Added:\n";
            $message .= "Jeans Name: $jeans_name\n";
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

            if (!$result_add || !$result_adds || !$result_update) {
                echo "<script>window.location = 'action.php?status=error&redirect=sale_jeans.php'; </script>";
                exit;
            }
        }

        echo "<script>window.location = 'action.php?status=success&redirect=sale_jeans.php'; </script>";
    } else {
        echo "<script>window.location = 'action.php?status=error&redirect=sale_jeans.php'; </script>";
    }
}



if (isset($_POST['filter'])) {
    $from = $_POST['from_date'];
    $to = $_POST['to_date'];
    $customer = $_POST['customer'];
    echo "<script>window.location = 'sale_jeans.php?from=$from&to=$to&customer=$customer'; </script>";
}




?>
<?php



$redirect_link = "../../";
$side_link = "../../";

include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php';
include_once $redirect_link . 'include/email.php';
include_once $redirect_link . 'include/bot.php';

$user_id = $_SESSION['user_id'];

$from_date = $_GET['from'] ?? '';
$to_date = $_GET['to'] ?? '';
?>

<head>
    <?php
    $title = 'Sale shoes';
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

        $sale_shoes = ($module['saleshoes'] == 1) ? true : false;
        $deliverysaleshoes = ($module['deliverysaleshoes'] == 1) ? true : false;
        $deletesaleshoes = ($module['deletesaleshoes'] == 1) ? true : false;
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


                                <?php if ($sale_shoes) : ?>

                                    <button type="button" data-fc-type="modal" data-fc-target="addModal"
                                        class="btn btn-sm rounded-full bg-success/25 text-success hover:bg-success hover:text-white">
                                        <i class="msr text-base me-2">add</i>
                                        Add Sales
                                    </button>

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
                                                <th>shoes Name</th>
                                                <th>Size</th>
                                                <th>Total</th>
                                                <th>Cash </th>
                                                <th>Bank</th>
                                                <th>Method</th>
                                                <th>Status</th>

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
                                                FROM shoes_delivery
                                                WHERE DATE(sales_date) >= '$from_date' 
                                                AND DATE(sales_date) <= '$to_date'
                                                ORDER BY sales_id DESC";
                                            } else {
                                                $sql = "SELECT * FROM shoes_delivery WHERE DATE(sales_date) >= '$from_date' AND DATE(sales_date) <= '$to_date' AND {$customer} ORDER BY sales_id DESC";
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

                                                        <?php if ($deletesaleshoes) : ?>

                                                            <a id="del-btn"
                                                                href="api/remove.php?id=<?php echo $row['sales_id']; ?>&from=delivery"
                                                                class="btn bg-danger/25 text-danger hover:bg-danger hover:text-white btn-sm rounded-full"><i
                                                                    class="mgc_delete_2_line text-base me-2"></i> Delete</a>

                                                        <?php endif; ?>





                                                        <?php if ($deliverysaleshoes) : ?>


                                                            <?php if ($row['verifiy'] != '1'): ?>
                                                                <button type="button"
                                                                    class="btn bg-success/25 text-success hover:bg-warning hover:text-white btn-sm rounded-full"
                                                                    data-fc-type="modal"
                                                                    data-fc-target="verify<?= $row['sales_id'] ?>">
                                                                    <i class="mgc_pencil_line text-base me-2"></i>
                                                                    Verify
                                                                </button>

                                                            <?php endif; ?>
                                                        <?php endif; ?>





                                                    </td>
                                                    <td
                                                        class="px-6 90-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['sales_id']; ?></td>
                                                    <td
                                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['shoes_name']; ?></td>
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
                                                    <td
                                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['status']; ?></td>




                                                </tr>




                                                <!-- Modal for exchanging shoes -->
                                                <div id="verify<?= $row['sales_id'] ?>" class="w-full h-full fixed top-0 left-0 z-50 transition-all duration-500 hidden">
                                                    <div class="fc-modal-open:mt-7 fc-modal-open:opacity-100 fc-modal-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-md sm:w-full m-3 sm:mx-auto bg-white border shadow-sm rounded-md dark:bg-slate-800 dark:border-gray-700">
                                                        <div class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
                                                            <h3 class="font-medium text-gray-800 dark:text-white text-lg">Verify Delivery</h3>
                                                            <button class="inline-flex flex-shrink-0 justify-center items-center h-8 w-8 dark:text-gray-200" data-fc-dismiss type="button">
                                                                <span class="material-symbols-rounded">close</span>
                                                            </button>
                                                        </div>
                                                        <form method="POST" class="overflow-y-auto">
                                                            <div class="px-4 py-8">
                                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">

                                                                    <?php

                                                                    $sql8 = "SELECT * from shoes_delivery WHERE sales_id = " . $row['sales_id'];
                                                                    $result8 = mysqli_query($con, $sql8);
                                                                    $row8 = mysqli_fetch_assoc($result8);

                                                                    ?>





                                                                    <input type="hidden" name="sales_id" value="<?= $row8['sales_id']; ?>">

                                                                    <div>
                                                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">shoes Name</label>
                                                                        <input type="text" name="shoes_name" class="form-input" required value="<?php echo $row['shoes_name'] ?>">
                                                                    </div>

                                                                    <div>
                                                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">shoes Size</label>
                                                                        <input type="text" name="size" class="form-input" required value="<?php echo $row['size'] ?>">
                                                                    </div>

                                                                    <div>
                                                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">New Price</label>
                                                                        <input type="number" step="0.01" name="price" class="form-input" required value="<?php echo $row['price'] ?>">
                                                                    </div>

                                                                    <div>
                                                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">Bank</label>
                                                                        <input type="number" name="bank" id="bankInput" class="form-input" required oninput="checkBankValue()" value="0">
                                                                    </div>

                                                                    <div id="bankNameDiv" style="display:none;">
                                                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">Bank Name</label>
                                                                        <select name="bank_name" id="bankNameInput" class="selectize">
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
                                                                        <input type="text" name="method" class="form-input" required value="<?php echo $row['method'] ?>">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="flex justify-end items-center gap-4 p-4 border-t dark:border-slate-700">
                                                                <button class="py-2 px-5 inline-flex justify-center items-center gap-2 rounded dark:text-gray-200 border dark:border-slate-700 font-medium hover:bg-slate-100 hover:dark:bg-slate-700 transition-all" data-fc-dismiss type="button">Close</button>
                                                                <button name="exchange_shoes" type="submit" class="btn bg-success text-white">Delivery</button>
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
                        class="fc-modal-open:mt-7 fc-modal-open:opacity-100 fc-modal-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto  bg-white border shadow-sm rounded-md dark:bg-slate-800 dark:border-gray-700   ">
                        <div class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700  ">
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


                                <div class="grid grid-cols-3 md:grid-cols-3 gap-3">

                                    <div class="mb-3">
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">
                                            shoes Name </label>

                                        <select name="shoes_name" class="search-select" required onchange="fetchPrice()">

                                            <?php

                                            $sql = "SELECT * FROM shoes GROUP BY shoes_name ORDER BY shoes_name ASC";
                                            $result = mysqli_query($con, $sql);
                                            while ($row = mysqli_fetch_assoc($result)) {
                                            ?>
                                                <option value="<?php echo $row['shoes_name'] ?>" <?php
                                                                                                    if (isset($shoes_name)) {
                                                                                                        if ($row['shoes_name'] == $shoes_name) {
                                                                                                            echo "selected";
                                                                                                        }
                                                                                                    }
                                                                                                    ?>>
                                                    <?php echo $row['shoes_name']; ?>
                                                </option>
                                            <?php
                                            }
                                            ?>

                                        </select>

                                    </div>


                                    <div class="mb-3">
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">
                                            shoes Size </label>

                                        <select name="size" class="search-select" required onchange="fetchPrice()">

                                            <?php

                                            $sql = "SELECT * FROM shoesdb";
                                            $result = mysqli_query($con, $sql);
                                            while ($row = mysqli_fetch_assoc($result)) {
                                            ?>
                                                <option value="<?php echo $row['size'] ?>" <?php
                                                                                            if (isset($shoes_size)) {
                                                                                                if ($row['size'] == $shoes_size) {
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

                                        <select name="method" class="search-select" required aria-readonly>


                                            <option value="delivery" selected>Delivery</option>

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
                                        <input type="text" name="cash" id="cash" class="form-input" value="0" required onchange="calculateTotalPrice()">
                                    </div>

                                    <div class="mb-3">
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">Bank</label>
                                        <input type="text" name="bank" id="bank" class="form-input" value="0" required id="bankInput" onchange="calculateTotalPrice();">
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



if (isset($_POST['exchange_shoes'])) {

    include('api/delivery.php');
}


if (isset($_POST['add_data'])) {
    $user_id = $_SESSION['user_id'];
    $shoes_name = $_POST['shoes_name'];
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




    if (!empty($shoes_name) && !empty($size)) {
        $sql = "SELECT * FROM shoes WHERE shoes_name = '$shoes_name' AND size = '$size'";
        $result = mysqli_query($con, $sql);
        $row = mysqli_fetch_assoc($result);

        if (!$row) {
            $sql = "SELECT * FROM shoes WHERE shoes_name = '$shoes_name'";
            $result = mysqli_query($con, $sql);
            $row = mysqli_fetch_assoc($result);
            $price = $row['price'];
            $image = $row['image'];
            $type = $row['type'];
            $type_id = $row['type_id'];


            $sql2 = "SELECT * FROM shoesdb WHERE size = '$size'";
            $result2 = mysqli_query($con, $sql2);
            $row2 = mysqli_fetch_assoc($result2);
            $size_id = $row2['id'];

            $quantity = $_POST['quantity'];

            $add_shoes = "INSERT INTO `shoes_verify`(`shoes_name`, `size`, `price`, `quantity`, `image`, `type`, `type_id`, `size_id`, `active`, `error`) 
                          VALUES ('$shoes_name', '$size', '$price', '$quantity', '$image', '$type', '$type_id', '$size_id', '0','1')";
            $result_add = mysqli_query($con, $add_shoes);

            if ($result_add) {
                echo "<script>window.location = 'action.php?status=error&redirect=sale_shoes.php'; </script>";
            }

            exit;
        }



        $shoes_id = $row['id'];
        $size_id = $row['size_id'];
        $current_quantity = $row['quantity'];

        if ($current_quantity < $quantity) {

            $sql = "SELECT * FROM shoes WHERE shoes_name = '$shoes_name' AND size = '$size'";
            $result = mysqli_query($con, $sql);
            $row = mysqli_fetch_assoc($result);
            $price = $row['price'];
            $image = $row['image'];
            $type = $row['type'];
            $type_id = $row['type_id'];
            $size_id = $row['size_id'];

            $add_shoes = "INSERT INTO `shoes_verify`(`shoes_name`, `size`, `price`, `quantity`, `image`, `type`, `type_id`, `size_id`, `active`, `error`) 
                              VALUES ('$shoes_name', '$size', '$price', '$quantity', '$image', '$type', '$type_id', '$size_id', '0','2')";
            $result_add = mysqli_query($con, $add_shoes);

            if ($result_add) {
                echo "<script>window.location = 'action.php?status=error&redirect=sale_shoes.php'; </script>";
                exit;
            }

            exit;
        }





        if ($method == 'delivery') {

            $status = "pending";
            $sql = "INSERT into shoes_delivery (shoes_id, size_id, shoes_name, size, price, cash, bank, method, sales_date, update_date, quantity, user_id,bank_id,bank_name,status)
            VALUES ('$shoes_id', '$size_id', '$shoes_name', '$size', '$price', '$cash', '$bank', '$method', '$date', '$date', '$quantity', '$user_id','$bank_id','$bank_name','$status')";
            $result = mysqli_query($con, $sql);

            $new_quantity = $current_quantity - $quantity;
            $update_quantity = "UPDATE shoes SET quantity = '$new_quantity' WHERE id = '$shoes_id' AND size = '$size'";
            $result_update = mysqli_query($con, $update_quantity);



            // No need to check result_add, result_adds, or result_update for delivery
            if (!$result || !$result_update) {
                echo "<script>window.location = 'action.php?status=error&redirect=sale_shoes.php'; </script>";
                exit;
            }

            echo "<script>window.location = 'action.php?status=success&redirect=delivery.php'; </script>";
        } else {
            $add_sales = "INSERT INTO `sales`(`shoes_id`, `size_id`, `shoes_name`, `size`, `price`, `cash`, `bank`, `method`, `sales_date`, `update_date`, `quantity`, `user_id`,`bank_id`,`bank_name`,`status`) 
                  VALUES ('$shoes_id', '$size_id', '$shoes_name', '$size', '$price', '$cash', '$bank', '$method', '$date', '$date', '$quantity', '$user_id','$bank_id','$bank_name','active')";
            $result_add = mysqli_query($con, $add_sales);

            $status = "sold";

            $add_shoes_log = "INSERT INTO `sales_log`(`shoes_id`, `size_id`, `shoes_name`, `size`, `price`, `cash`, `bank`, `method`, `sales_date`, `update_date`, `quantity`, `user_id`, `status`) 
                      VALUES ('$shoes_id', '$size_id', '$shoes_name', '$size', '$price', '$cash', '$bank', '$method', '$date', '$date', '$quantity', '$user_id', '$status')";
            $result_adds = mysqli_query($con, $add_shoes_log);

            $new_quantity = $current_quantity - $quantity;
            $update_quantity = "UPDATE shoes SET quantity = '$new_quantity' WHERE id = '$shoes_id' AND size = '$size'";
            $result_update = mysqli_query($con, $update_quantity);

            // Check only for errors in non-delivery cases
            if (!$result_add || !$result_adds || !$result_update) {
                echo "<script>window.location = 'action.php?status=error&redirect=sale_shoes.php'; </script>";
                exit;
            }
        }

        $message = "shoes is going out for Delivery \n";


        $message .= "shoes Name: $shoes_name\n";
        $message .= "Price: $price\n";
        $message .= "Size: $size\n";
        $message .= "Quantity: $quantity\n";
        $message .= "Cash :  $cash\n";
        $message .= "Bank : $bank\n";



        $subject = "Sold shoes Deliverd to Customer";


        sendMessageToSubscribers($message, $con);
        sendEmailToSubscribers($message, $subject, $con);

        // Success redirect
        echo "<script>window.location = 'action.php?status=success&redirect=delivery.php'; </script>";
    }
}

if (isset($_POST['filter'])) {
    $from = $_POST['from_date'];
    $to = $_POST['to_date'];
    $customer = $_POST['customer'];
    echo "<script>window.location = 'delivery.php?from=$from&to=$to&customer=$customer'; </script>";
}










?>
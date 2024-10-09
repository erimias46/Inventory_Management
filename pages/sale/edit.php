<?php
$redirect_link = "../../";
$side_link = "../../";
include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php';
include_once $redirect_link . 'include/email.php';
include_once $redirect_link . 'include/bot.php';








$type = $_GET['type'];
$sales_id = $_GET['sales_id'];


if($type=='jeans'){
    $sql="SELECT * FROM sales WHERE sales_id = $sales_id";
}

else{
    $sql="SELECT * FROM {$type}_sales WHERE sales_id = $sales_id";
}



$result = mysqli_query($con, $sql);
$row = mysqli_fetch_assoc($result);

if ($row) {
    $product_name = $row[$type . '_name'];
    $size = $row['size'];
    $price = $row['price'];
    $cash = $row['cash'];
    $bank = $row['bank'];
    $method = $row['method'];
    $bank_name = $row['bank_name'];
    $status = $row['status'];
    $price = $row['price'];
} else {
    echo "No product found with the specified ID";
}


$current_date = date('Y-m-d');

$generate_button = '';

if (isset($_GET['import_brocher_id'])) {
    $brocher_type = $_GET['brocher_type'];



    $add_button = '<button name="add" type="submit" class="btn btn-sm bg-success text-white rounded-full"> <i class="mgc_add_fill text-base me-2"></i> Add </button>';
    $update_button = '<button name="update" type="submit" class="btn btn-sm bg-danger text-white rounded-full"> <i class="mgc_pencil_line text-base me-2"></i> Update </button>';
    $generate_button = '<button name="add_generate" type="submit" class="btn btn-sm bg-info text-white rounded-full"> <i class="mgc_pdf_line text-base me-2"></i> Generate </button>';
}

?>

<?php
if (isset($_POST['add'])) {

    // Collect form data
    $user_id = $_SESSION['user_id'];
    $code_name = $_POST['code_name']; // Get the value of the selected item
    $size = $_POST['size_name'];
    $price = $_POST['price'];
    $cash = $_POST['cash'];
    $bank = $_POST['bank'];
    $method = $_POST['method'];
    $date = date('Y-m-d H:i:s');
    $quantity = 1;

    // Split the code_name into table and product name
    list($table, $product_name) = explode('|', $code_name);

    // Handle bank details
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

    // Ensure product name and size are provided
    if (!empty($product_name) && !empty($size)) {
        // Get product details from the corresponding table
        $sql = "SELECT * FROM $table WHERE {$table}_name = '$product_name' AND size = '$size'";
        $result = mysqli_query($con, $sql);
        $row = mysqli_fetch_assoc($result);

        if (!$row) {
            // If no exact match for product name and size, get default product details (without size)
            $sql = "SELECT * FROM $table WHERE {$table}_name = '$product_name'";
            $result = mysqli_query($con, $sql);
            $row = mysqli_fetch_assoc($result);
            $price = $row['price'];
            $image = $row['image'];
            $type = $row['type'];
            $type_id = $row['type_id'];

            $sql2 = "SELECT * FROM {$table}db WHERE size = '$size'";
            $result2 = mysqli_query($con, $sql2);
            $row2 = mysqli_fetch_assoc($result2);
            $size_id = $row2['id'];

            // Insert into verification table with error status
            $add_product = "INSERT INTO `{$table}_verify`(`{$table}_name`, `size`, `price`, `quantity`, `image`, `type`, `type_id`, `size_id`, `active`, `error`) 
                          VALUES ('$product_name', '$size', '$price', '$quantity', '$image', '$type', '$type_id', '$size_id', '0','1')";
            $result_add = mysqli_query($con, $add_product);

            if ($result_add) {
                echo "<script>window.location = 'action.php?status=error&redirect=sale_$table.php'; </script>";
            }

            exit;
        }

        // Get product details from the row
        $product_id = $row['id'];
        $size_id = $row['size_id'];
        $current_quantity = $row['quantity'];

        // Check quantity availability
        if ($current_quantity < $quantity) {
            // Insert into verification table with insufficient quantity error
            $price = $row['price'];
            $image = $row['image'];
            $type = $row['type'];
            $type_id = $row['type_id'];

            $add_product = "INSERT INTO `{$table}_verify`(`{$table}_name`, `size`, `price`, `quantity`, `image`, `type`, `type_id`, `size_id`, `active`, `error`) 
                          VALUES ('$product_name', '$size', '$price', '$quantity', '$image', '$type', '$type_id', '$size_id', '0','2')";
            $result_add = mysqli_query($con, $add_product);

            if ($result_add) {
                echo "<script>window.location = 'action.php?status=error&redirect=sale_$table.php'; </script>";
                exit;
            }

            exit;
        }

        // Insert into delivery or sales table
        if ($method == 'delivery') {
            $status = "pending";

            if ($table == 'jeans')
                $delivery_table = 'delivery';
            else
                $delivery_table = $table . '_delivery';

            $sql = "INSERT into {$delivery_table} ({$table}_id, size_id, {$table}_name, size, price, cash, bank, method, sales_date, update_date, quantity, user_id, bank_id, bank_name, status)
                VALUES ('$product_id', '$size_id', '$product_name', '$size', '$price', '$cash', '$bank', '$method', '$date', '$date', '$quantity', '$user_id', '$bank_id', '$bank_name', '$status')";
            $result = mysqli_query($con, $sql);

            $new_quantity = $current_quantity - $quantity;
            $update_quantity = "UPDATE $table SET quantity = '$new_quantity' WHERE id = '$product_id' AND size = '$size'";
            $result_update = mysqli_query($con, $update_quantity);

            if (!$result || !$result_update) {
                echo "<script>window.location = 'action.php?status=error&redirect=sale_$table.php'; </script>";
                exit;
            }
        } else {

            if ($table == 'jeans') {
                $sales_table = 'sales';
            } else {
                $sales_table = $table . '_sales';
            }


            $add_sales = "INSERT INTO `$sales_table`(`{$table}_id`, `size_id`, `{$table}_name`, `size`, `price`, `cash`, `bank`, `method`, `sales_date`, `update_date`, `quantity`, `user_id`, `bank_id`, `bank_name`, `status`) 
                      VALUES ('$product_id', '$size_id', '$product_name', '$size', '$price', '$cash', '$bank', '$method', '$date', '$date', '$quantity', '$user_id', '$bank_id', '$bank_name', 'active')";
            $result_add = mysqli_query($con, $add_sales);

            $status = "sold";

            if ($table == 'jeans') {
                $sales_log = 'sales_log';
            } else {
                $sales_log = $table . '_sales_log';
            }

            $add_product_log = "INSERT INTO `$sales_log`(`{$table}_id`, `size_id`, `{$table}_name`, `size`, `price`, `cash`, `bank`, `method`, `sales_date`, `update_date`, `quantity`, `user_id`, `status`) 
                            VALUES ('$product_id', '$size_id', '$product_name', '$size', '$price', '$cash', '$bank', '$method', '$date', '$date', '$quantity', '$user_id', '$status')";
            $result_adds = mysqli_query($con, $add_product_log);

            $new_quantity = $current_quantity - $quantity;
            $update_quantity = "UPDATE $table SET quantity = '$new_quantity' WHERE id = '$product_id' AND size = '$size'";
            $result_update = mysqli_query($con, $update_quantity);

            if (!$result_add || !$result_adds || !$result_update) {
                echo "<script>window.location = 'action.php?status=error&redirect=sale_$table.php'; </script>";
                exit;
            }
        }

        // Notify subscribers for both delivery and sales
        $subscribers_query = "SELECT chat_id FROM subscribers";
        $subscribers_result = mysqli_query($con, $subscribers_query);
        $subscribers = mysqli_fetch_all($subscribers_result, MYSQLI_ASSOC);

        $message = "New Sale Added:\n";
        $message .= "Product Name: $product_name\n";
        $message .= "Size: $size\n";
        $message .= "Price: $price\n";
        $message .= "Cash: $cash\n";
        $message .= "Bank: $bank\n";
        $message .= "Method: $method\n";
        $message .= "Date: $date\n";
        $message .= "Quantity: $quantity\n";









        $subject = "Sold Product: $product_name";


        sendMessageToSubscribers($message, $con);
        sendEmailToSubscribers($message, $subject, $con);



        // Success redirect
        echo "<script>window.location = 'action.php?status=success&redirect=sale_$table.php'; </script>";
    }
}
?>

<?php


if (isset($_POST['update'])) {
}

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





        $add_button = ($module['salejeans'] == 1) ? true : false;
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
    $title = 'SALE';
    include $redirect_link . 'partials/title-meta.php'; ?>
    <link href="../../assets/libs/dropzone/min/dropzone.min.css" rel="stylesheet" type="text/css">


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
                <div class="grid grid-cols-1 gap-3">
                    <div class="card bg-white shadow-md rounded-md p-6 mx-lg max-w-lg">

                        <div class="p-6">
                            <h2 class="text-xl font-bold text-white-700 text-center mb-10">EDIT DATA ENTRY</h2>
                            <form method="post" enctype="multipart/form-data" class="grid grid-cols-2 gap-5">
                                <!-- Jeans Name Field -->
                                <div class="mb-3">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2" for="code_name">Code Name</label>
                                    <select name="code_name" id="code_name" class="w-full border border-gray-300 p-2 rounded-md search-select" onchange="fetchSizes()" required>
                                        <option value="">Select Name</option>
                                        <?php
                                        $sql3 = "SELECT * FROM `$type` GROUP BY `{$type}_name` ORDER BY `{$type}_name` ASC";
                                        $result3 = mysqli_query($con, $sql3);

                                        if (mysqli_num_rows($result3) > 0) {
                                            while ($row3 = mysqli_fetch_assoc($result3)) {
                                                // Check if the current option should be selected
                                                $selected = ($row3[$type . '_name'] == $product_name) ? 'selected' : '';
                                        ?>
                                                <option value="<?= $row3[$type . '_name'] ?>" <?= $selected ?>><?= $row3[$type . '_name'] ?></option>
                                        <?php }
                                        }
                                        ?>
                                    </select>
                                </div>


                                <div class="mb-3">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2" for="size_name">Size</label>
                                    <select name="size_name" id="size_name" class="form-select w-full border border-gray-300 p-2 rounded-md" required>

                                        <?php
                                        $sql4 = "SELECT * FROM `{$type}db`";
                                        $result4 = mysqli_query($con, $sql4);
                                        if (mysqli_num_rows($result4) > 0) {
                                            while ($row4 = mysqli_fetch_assoc($result4)) {

                                                // Check if the current option should be selected
                                                $selected = ($row4['size'] == $size) ? 'selected' : '';
                                                
                                                ?>
                                                <option value="<?= $row4['size'] ?>" <?= $selected ?>><?= $row4['size'] ?></option>
                                               
                                        <?php }
                                        }
                                        ?>


                                    </select>
                                </div>


                                <div class="mb-3">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2" for="cash">Cash</label>
                                    <input type="text" name="cash" id="cash" class="form-input w-full border border-gray-300 p-2 rounded-md" required  value="<?php echo $cash ;?>">
                                </div>
                                <div class="mb-3">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2" for="bank">Bank</label>
                                   <input type="text" name="bank" id="bank" class="form-input w-full border border-gray-300 p-2 rounded-md" required value='<?php echo $bank; ?>'>

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

                                <div class="mb-3">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2" for="price">Total Price</label>
                                    <input type="text" name="price" id="totalPrice" class="form-input w-full border border-gray-300 p-2 rounded-md" readonly required  value="<?php echo $price; ?>" >
                                </div>

                                <div class="mb-3">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2" for="price">Method</label>
                                    <select name="method" id="method" class="form-select w-full border border-gray-300 p-2 rounded-md" required>
                                        <option value="shop">Shop</option>
                                        <option value="delivery">Delivery</option>
                                    </select>
                                </div>






                                <!-- Price Field -->


                                <!-- Submit Button Section -->
                                <div class="text-center mt-5">
                                    <?php if ($add_button) : ?>
                                        <button name="add" type="submit" class="btn btn-sm bg-success text-white rounded-full px-4 py-2">
                                            <i class="mgc_add_fill text-base me-2"></i> Add Sale
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </main>

            <style>
                .card {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                }
            </style>


            <?php include $redirect_link . 'partials/footer.php'; ?>

        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->

    </div>

    <?php include $redirect_link . 'partials/customizer.php'; ?>

    <?php include $redirect_link . 'partials/footer-scripts.php'; ?>



    <script>
        // JavaScript to automatically calculate the total price
        document.getElementById('cash').addEventListener('input', calculateTotal);
        document.getElementById('bank').addEventListener('input', calculateTotal);

        function calculateTotal() {
            const cash = parseFloat(document.getElementById('cash').value) || 0;
            const bank = parseFloat(document.getElementById('bank').value) || 0;
            const total = cash + bank;

            document.getElementById('totalPrice').value = total.toFixed(2); // Display the total with two decimal places
        }
    </script>


    <script>
        function fetchSizes() {
            const codeNameSelect = document.getElementById('code_name').value;
            const [table, codeName] = codeNameSelect.split('|');

            if (table && codeName) {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'fetch_sizes.php', true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (this.status === 200) {
                        const sizes = JSON.parse(this.responseText);
                        const sizeSelect = document.getElementById('size_name');
                        sizeSelect.innerHTML = '<option value="">Select Size</option>';

                        sizes.forEach(size => {
                            const option = document.createElement('option');
                            option.value = size;
                            option.textContent = size;
                            sizeSelect.appendChild(option);
                        });
                    }
                };
                xhr.send('table=' + table + '&code_name=' + encodeURIComponent(codeName));
            }
        }
    </script>






</body>

</html>
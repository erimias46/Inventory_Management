<?php
$redirect_link = "../../";
$side_link = "../../";
include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php';
include_once $redirect_link . 'include/email.php';
include_once $redirect_link . 'include/bot.php';


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
        $filename = 'counter.txt';

        // Increment the counter
        if (!file_exists($filename)) {
            $number = 1;
        } else {
            $number = (int)file_get_contents($filename);
            $number++;
        }
        file_put_contents($filename, $number);

        // Collect form data
        $user_id = $_SESSION['user_id'];
        $code_names = $_POST['code_name']; // Multiple code names
        $sizes = $_POST['size_name']; // Multiple sizes
        $prices = $_POST['price']; // Multiple prices
        $cash_values = $_POST['cash']; // Multiple cash values
        $banks = $_POST['bank']; // Multiple banks
        $method = $_POST['method'];
        $date = date('Y-m-d H:i:s');

        // Iterate through all sales entries
        for ($i = 0; $i < count($code_names); $i++) {
            $code_name = $code_names[$i];
            $size = $sizes[$i];
            $price = $prices[$i];
            $cash = $cash_values[$i];
            $bank = $banks[$i];
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
                    // Handle missing product and size details (if necessary)
                    continue;
                }

                // Get product details from the row
                $product_id = $row['id'];
                $size_id = $row['size_id'];
                $current_quantity = $row['quantity'];

                // Check quantity availability
                if ($current_quantity < $quantity) {
                    // Handle insufficient quantity error (if necessary)
                    continue;
                }

                // Insert into delivery or sales table
                if ($method == 'delivery') {
                    $status = "pending";
                    $delivery_table = ($table == 'jeans') ? 'delivery' : $table . '_delivery';

                    $sql = "INSERT INTO $delivery_table ({$table}_id, size_id, {$table}_name, size, price, cash, bank, method, sales_date, update_date, quantity, user_id, bank_id, bank_name, status)
                        VALUES ('$product_id', '$size_id', '$product_name', '$size', '$price', '$cash', '$bank', '$method', '$date', '$date', '$quantity', '$user_id', '$bank_id', '$bank_name', '$status')";
                    mysqli_query($con, $sql);
                } else {
                    $sales_table = ($table == 'jeans') ? 'sales' : $table . '_sales';

                    // Insert into sales table
                    $sql = "INSERT INTO $sales_table ({$table}_id, size_id, {$table}_name, size, price, cash, bank, method, sales_date, update_date, quantity, user_id, bank_id, bank_name, status)
                        VALUES ('$product_id', '$size_id', '$product_name', '$size', '$price', '$cash', '$bank', '$method', '$date', '$date', '$quantity', '$user_id', '$bank_id', '$bank_name', 'active')";

                    if (mysqli_query($con, $sql)) {
                        $sales_id = mysqli_insert_id($con); // Get inserted sale ID

                        // Insert into multi_sale
                        $multi_log = "INSERT INTO multi_sale (multi_id, sales_id) VALUES ('$number', '$sales_id')";
                        mysqli_query($con, $multi_log);

                        // Insert into sales log (only one log insert per sale)
                        $sales_log = ($table == 'jeans') ? 'sales_log' : $table . '_sales_log';

                        $sql_log = "INSERT INTO $sales_log ({$table}_id, size_id, {$table}_name, size, price, cash, bank, method, sales_date, update_date, quantity, user_id, status)
                                VALUES ('$product_id', '$size_id', '$product_name', '$size', '$price', '$cash', '$bank', '$method', '$date', '$date', '$quantity', '$user_id', 'sold')";
                        mysqli_query($con, $sql_log);
                    }
                }

                // Update the quantity in the product table
                $new_quantity = $current_quantity - $quantity;
                $sql_update = "UPDATE $table SET quantity = '$new_quantity' WHERE id = '$product_id' AND size = '$size'";
                mysqli_query($con, $sql_update);
            }
        }

        // Notify subscribers about the sales (optional)
        $subscribers_query = "SELECT chat_id FROM subscribers";
        $subscribers_result = mysqli_query($con, $subscribers_query);
        $subscribers = mysqli_fetch_all($subscribers_result, MYSQLI_ASSOC);

        foreach ($subscribers as $subscriber) {
            $message = "New Sale Added:\n";
            $message .= "Product Name: $product_name\n";
            $message .= "Size: $size\n";
            $message .= "Price: $price\n";
            $message .= "Cash: $cash\n";
            $message .= "Bank: $bank\n";
            $message .= "Method: $method\n";
            // Send notification to each subscriber (Telegram bot integration here)
        }

        // Redirect on success
        echo "<script>window.location = 'action.php?status=success&redirect=multi.php';</script>";
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


    <style>
        .image-preview {
            display: inline-block;
            margin-left: 20px;
        }

        .image-preview img {
            max-width: 150px;
            max-height: 150px;
        }


        /* Hide the default file input */
        .choose-image {
            display: none;
        }

        /* Style the custom file upload button */
        .custom-file-upload {
            position: relative;
            display: inline-block;
            cursor: pointer;
            background-color: #4A90E2;
            color: white;
            padding: 10px 20px;
            font-size: 14px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .custom-file-upload:hover {
            background-color: #244bad;
        }

        .custom-file-label {
            cursor: pointer;
            font-weight: bold;
        }
    </style>
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
                            <h2 class="text-4xl font-bold text-white-700 text-center mb-10">MULTI SALE DATA ENTRY</h2>
                            <form method="post" enctype="multipart/form-data" id="saleForm" class="grid grid-cols-7 gap-5">
                                <div id="salesEntries" class="col-span-2">
                                    <div class="sale-entry grid grid-cols-5 gap-5 mb-5   ">
                                        <!-- Code Name Field -->
                                        <div class="mb-3">
                                            <label class="text-gray-800 text-sm font-medium inline-block mb-2" for="code_name">Code Name</label>
                                            <select name="code_name[]" class="code_name w-full border border-gray-300 p-2 rounded-md " onchange="fetchSizes(this)" required>
                                                <option value="">Select Name</option>
                                                <?php
                                                $tables = ['jeans', 'shoes', 'complete', 'accessory', 'top'];
                                                foreach ($tables as $table) {
                                                    $display_label = ucfirst($table);
                                                    echo "<optgroup label='$display_label'>";
                                                    $sql = "SELECT {$table}_name FROM $table GROUP BY {$table}_name";
                                                    $result = mysqli_query($con, $sql);
                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                        $name_column = "{$table}_name";
                                                ?>
                                                        <option value="<?php echo $table . '|' . $row[$name_column]; ?>">
                                                            <?php echo $row[$name_column]; ?>
                                                        </option>
                                                <?php
                                                    }
                                                    echo "</optgroup>";
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <!-- Size Field -->
                                        <div class="mb-3">
                                            <label class="text-gray-800 text-sm font-medium inline-block mb-2" for="size_name">Size</label>
                                            <select name="size_name[]" class="size_name form-select w-full border border-gray-300 p-2 rounded-md" required>
                                                <option value="">Select Size</option>
                                            </select>
                                        </div>

                                        <!-- Price Field -->
                                        <div class="mb-3">
                                            <label class="text-gray-800 text-sm font-medium inline-block mb-2" for="price">Price</label>
                                            <input type="text" name="price[]" class="price form-input w-full border border-gray-300 p-2 rounded-md" required>
                                        </div>
                                        <div class="mb-3">

                                            <label class="text-gray-800 text-sm font-medium inline-block mb-2" for="cash">Cash</label>
                                            <input type="text" name="cash[]" id="cash" class="form-input w-full border border-gray-300 p-2 rounded-md" required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="text-gray-800 text-sm font-medium inline-block mb-2" for="bank">Bank</label>
                                            <input type="text" name="bank[]" id="bank" class="form-input w-full border border-gray-300 p-2 rounded-md" required>
                                        </div>


                                    </div>
                                </div>


                                <!-- Add Sale Entry Button -->
                                <div class="mb-3 text-center">
                                    <button type="button" class="btn bg-blue-500 text-white px-4 py-2 rounded-md" onclick="addSaleEntry()">Add More</button>

                                </div>

                                <!-- Other Fields -->


                                <div id="bankNameDiv">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2">Bank Name</label>
                                    <select name="bank_name" id="bankNameInput" class="selectize">
                                        <option value="">Select</option>
                                        <?php
                                        $sql5 = "SELECT * FROM bankdb";
                                        $result5 = mysqli_query($con, $sql5);
                                        if (mysqli_num_rows($result5) > 0) {
                                            while ($row5 = mysqli_fetch_assoc($result5)) { ?>
                                                <option value="<?= $row5['bankname'] ?>">
                                                    <?= $row5['bankname'] ?>
                                                </option>
                                        <?php }
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2" for="price">Method</label>
                                    <select name="method" id="method" class="form-select w-full border border-gray-300 p-2 rounded-md" required>
                                        <option value="shop">Shop</option>
                                        <option value="delivery">Delivery</option>
                                    </select>
                                </div>

                                <!-- Submit Button -->
                                <div class="text-center mt-5">
                                    <?php if ($add_button) : ?>
                                        <button name="add" type="submit" class="btn bg-green-500 text-white px-4 py-2 rounded-md">
                                            Sale
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
        function addSaleEntry() {
            // Clone the sale entry div
            const saleEntry = document.querySelector('.sale-entry').cloneNode(true);
            // Clear the inputs in the cloned sale entry
            saleEntry.querySelectorAll('input, select').forEach(input => {
                input.value = '';
            });
            // Append the cloned sale entry to the salesEntries div
            document.getElementById('salesEntries').appendChild(saleEntry);
        }

        function fetchSizes(element) {
            const codeNameSelect = element.value;
            const [table, codeName] = codeNameSelect.split('|');

            if (table && codeName) {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'fetch_sizes.php', true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (this.status === 200) {
                        const sizes = JSON.parse(this.responseText);
                        const sizeSelect = element.closest('.sale-entry').querySelector('.size_name');
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









</body>

</html>
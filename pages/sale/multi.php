<?php
$redirect_link = "../../";
$side_link = "../../";
include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php';
include_once $redirect_link . 'include/email.php';
include_once $redirect_link . 'include/bot.php';


$current_date = date('Y-m-d');

$generate_button = '';



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

    // Counter for successful sales
    $successful_sales_count = 0;
    $sales_ids = []; // Array to hold sales IDs for multi sale logging

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

                    $message = "Verify Needed For a product\n";
                    $message .= "Product Name: $product_name\n";
                    $message .= "Price: $price\n";
                    $message .= "Size: $size\n";
                    $message .= "Quantity: $quantity\n";
                    $message .= "Cash: $cash\n";
                    $message .= "Bank: $bank\n";
                    $message .= "Reason: Size not found\n";
                   


                    $subject = "Verify Needed";

                    // Send updates to subscribers
                    sendMessageToSubscribers($message, $con);
                    sendEmailToSubscribers($message, $subject, $con);

                    echo "<script>window.location = 'action.php?status=error&redirect=multi.php'; </script>";
                }

              
                continue;
            }

            // Get product details from the row
            $product_id = $row['id'];
            $size_id = $row['size_id'];
            $current_quantity = $row['quantity'];

            // Check quantity availability
            if ($current_quantity < $quantity) {
                // Handle insufficient quantity error (if necessary)


                $price = $row['price'];
                $image = $row['image'];
                $type = $row['type'];
                $type_id = $row['type_id'];

                $add_product = "INSERT INTO `{$table}_verify`(`{$table}_name`, `size`, `price`, `quantity`, `image`, `type`, `type_id`, `size_id`, `active`, `error`) 
                          VALUES ('$product_name', '$size', '$price', '$quantity', '$image', '$type', '$type_id', '$size_id', '0','2')";
                $result_add = mysqli_query($con, $add_product);

                if ($result_add) {



                    $message = "Verify Needed For a product\n";
                    $message .= "Product Name: $product_name\n";
                    $message .= "Price: $price\n";
                    $message .= "Size: $size\n";
                    $message .= "Quantity: $quantity\n";
                    $message .= "Cash: $cash\n";
                    $message .= "Bank: $bank\n";
                    $message .= "Reason: Quantity Not found\n";



                    $subject = "Verify Needed";

                    // Send updates to subscribers
                    sendMessageToSubscribers($message, $con);
                    sendEmailToSubscribers($message, $subject, $con);
                    echo "<script>window.location = 'action.php?status=error&redirect=multi.php'; </script>";
                   
                }
                echo "<script>window.location = 'action.php?status=error&redirect=multi.php'; </script>";
                
                continue;
            }

            // Insert into delivery or sales table
            if ($method == 'delivery') {
                $status = "pending";
                $delivery_table = ($table == 'jeans') ? 'delivery' : $table . '_delivery';

                $sql = "INSERT INTO $delivery_table ({$table}_id, size_id, {$table}_name, size, price, cash, bank, method, sales_date, update_date, quantity, user_id, bank_id, bank_name, status)
                        VALUES ('$product_id', '$size_id', '$product_name', '$size', '$price', '$cash', '$bank', '$method', '$date', '$date', '$quantity', '$user_id', '$bank_id', '$bank_name', '$status')";
                mysqli_query($con, $sql);


               


                $new_quantity=$current_quantity-$quantity;
                $update_quantity="UPDATE $table SET quantity = '$new_quantity' WHERE id = '$product_id' AND size = '$size'";
                $result_update = mysqli_query($con, $update_quantity);

                if (!$result || !$result_update) {
                    echo "<script>window.location = 'action.php?status=error&redirect=sale_shoes.php'; </script>";
                    continue;
                }

                



            } else {
                $sales_table = ($table == 'jeans') ? 'sales' : $table . '_sales';

                // Insert into sales table
                $sql = "INSERT INTO $sales_table ({$table}_id, size_id, {$table}_name, size, price, cash, bank, method, sales_date, update_date, quantity, user_id, bank_id, bank_name, status)
                        VALUES ('$product_id', '$size_id', '$product_name', '$size', '$price', '$cash', '$bank', '$method', '$date', '$date', '$quantity', '$user_id', '$bank_id', '$bank_name', 'active')";

                if (mysqli_query($con, $sql)) {
                    $successful_sales_count++; // Increment count for successful sales
                    $sales_id = mysqli_insert_id($con); // Get inserted sale ID
                    $sales_ids[] = $sales_id; // Store sales ID for multi sale logging

                    // Insert into sales log (only one log insert per sale)
                    $sales_log = ($table == 'jeans') ? 'sales_log' : $table . '_sales_log';

                    $sql_log = "INSERT INTO $sales_log ({$table}_id, size_id, {$table}_name, size, price, cash, bank, method, sales_date, update_date, quantity, user_id, status)
                                VALUES ('$product_id', '$size_id', '$product_name', '$size', '$price', '$cash', '$bank', '$method', '$date', '$date', '$quantity', '$user_id', 'sold')";
                    mysqli_query($con, $sql_log);

                    // Update the quantity in the product table
                    $new_quantity = $current_quantity - $quantity;
                    $sql_update = "UPDATE $table SET quantity = '$new_quantity' WHERE id = '$product_id' AND size = '$size'";
                    mysqli_query($con, $sql_update);
                }
            }
        }
    }

    // If more than one sale was successful, insert into multi_sale
    if ($successful_sales_count > 1) {
        $from = $table;
        foreach ($sales_ids as $sales_id) {
            $multi_log = "INSERT INTO multi_sale (multi_id, sales_id, from_table) VALUES ('$number', '$sales_id', '$from')";
            mysqli_query($con, $multi_log);
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



    <!-- Include jQuery -->
    <!-- Load jQuery -->










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
                                <div id="salesEntries" class="col-span-3">
                                    <div class="sale-entry grid grid-cols-5 gap-5 mb-5">
                                        <!-- Code Name Field -->
                                        <div class="mb-3">
                                            <label class="text-gray-800 text-sm font-medium inline-block mb-2" for="code_name">Code Name</label>
                                            <select name="code_name[]" class="code_name w-full border border-gray-300 p-2 rounded-md" onchange="fetchSizes(this)" required>
                                                <option value="">Select Name</option>
                                                <!-- PHP for fetching product names -->
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


                                        <!-- Cash Field -->
                                        <div class="mb-3">
                                            <label class="text-gray-800 text-sm font-medium inline-block mb-2" for="cash">Cash</label>
                                            <input type="number" value="0" step="0.01" name="cash[]" class="cash form-input w-full border border-gray-300 p-2 rounded-md" oninput="updatePrice(this)" required>
                                        </div>

                                        <!-- Bank Field -->
                                        <div class="mb-3">
                                            <label class="text-gray-800 text-sm font-medium inline-block mb-2" for="bank">Bank</label>
                                            <input type="number" value="0" step="0.01" name="bank[]" class="bank form-input w-full border border-gray-300 p-2 rounded-md" oninput="updatePrice(this)" required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="text-gray-800 text-sm font-medium inline-block mb-2" for="price">Price</label>
                                            <input type="number" value="0" step="0.01" name="price[]" class="price form-input w-full border border-gray-300 p-2 rounded-md" required readonly>
                                        </div>

                                        <!-- Remove Entry Button -->
                                        <div class="mb-3">
                                            <button type="button" class="btn bg-red-500 text-white px-4 py-2 rounded-md remove-entry" onclick="removeSaleEntry(this)">Remove</button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Add Sale Entry Button -->
                                <div class="mb-3 text-center">
                                    <button type="button" class="btn bg-info text-white px-4 py-2 rounded-md" onclick="addSaleEntry()">Add More</button>
                                </div>

                                <!-- Bank Name Field -->
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

                                <!-- Method Field -->
                                <div class="mb-3">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2" for="method">Method</label>
                                    <select name="method" id="method" class="form-select w-full border border-gray-300 p-2 rounded-md" required>
                                        <option value="shop">Shop</option>
                                        <option value="delivery">Delivery</option>
                                    </select>
                                </div>

                                <!-- Total Price Display -->
                                <div class="mb-3">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2">Total Price</label>
                                    <input type="number" id="totalPrice" class="form-input w-full border border-gray-300 p-2 rounded-md" value="0" readonly>
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
        // Function to update price based on cash and bank input
        function updatePrice(element) {
            var saleEntry = element.closest('.sale-entry');
            var cashInput = saleEntry.querySelector('.cash').value || 0;
            var bankInput = saleEntry.querySelector('.bank').value || 0;
            var priceInput = saleEntry.querySelector('.price');

            // Calculate price as sum of cash and bank
            var totalPrice = parseFloat(cashInput) + parseFloat(bankInput);
            priceInput.value = totalPrice.toFixed(2);

            // Update overall total price
            updateTotalPrice();
        }

        // Function to update the total price for all sale entries
        function updateTotalPrice() {
            var totalPrice = 0;
            var priceInputs = document.querySelectorAll('.price');

            // Sum all individual prices
            priceInputs.forEach(function(input) {
                totalPrice += parseFloat(input.value || 0);
            });

            // Update total price display
            document.getElementById('totalPrice').value = totalPrice.toFixed(2);
        }

        // Function to add a new sale entry row (as you already have)
        function addSaleEntry() {
            // Clone the existing sale-entry and append to salesEntries div
            var saleEntry = document.querySelector('.sale-entry');
            var newEntry = saleEntry.cloneNode(true);

            // Clear input values for new entry
            newEntry.querySelectorAll('input').forEach(function(input) {
                input.value = '';
            });

            document.getElementById('salesEntries').appendChild(newEntry);
        }

        // Function to remove a sale entry
        function removeSaleEntry(button) {
            var saleEntry = button.closest('.sale-entry');
            saleEntry.remove();

            // Recalculate total price after removal
            updateTotalPrice();
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
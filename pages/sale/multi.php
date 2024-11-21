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
    $code_names = $_POST['code_name'];
    $sizes = $_POST['size_name'];
    $prices = $_POST['price'];
    $cash_values = $_POST['cash'];
    $banks = $_POST['bank'];
    $method = $_POST['method'];
    $date = date('Y-m-d H:i:s');

    $successful_sales_count = 0;
    $sales_ids = [];
    $multi_sale_message = ""; // Combined message for multiple sales or deliveries
    $multi_delivery_message = ""; // Combined message for multiple deliveries

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
        $bank_name = $bank == 0 ? null : $_POST['bank_name'];
        if ($bank !== 0) {
            $sql = "SELECT id FROM bankdb WHERE bankname = '$bank_name'";
            $result = mysqli_query($con, $sql);
            $row = mysqli_fetch_assoc($result);
            $bank_id = $row['id'] ?? null;
        }

        if (!empty($product_name) && !empty($size)) {
            // Check product and size details
            $sql = "SELECT * FROM $table WHERE {$table}_name = '$product_name' AND size = '$size'";
            $result = mysqli_query($con, $sql);
            $row = mysqli_fetch_assoc($result);

            if (!$row) {
                // Handle verification for missing size
                $verify_message = "Verify Needed for product:\nProduct Name: $product_name\nPrice: $price\nSize: $size\nQuantity: $quantity\n";
                sendVerificationNotification($verify_message, $con);
                continue;
            }

            $product_id = $row['id'];
            $current_quantity = $row['quantity'];

            if ($current_quantity < $quantity) {
                // Handle verification for insufficient quantity
                $verify_message = "Verify Needed for insufficient quantity:\nProduct Name: $product_name\nPrice: $price\nSize: $size\nQuantity: $quantity\n";
                sendVerificationNotification($verify_message, $con);
                continue;
            }

            // Insert into delivery or sales table
            if ($method == 'delivery') {
                $status = "pending";
                $reason = $_POST['reason'];
                $delivery_table = ($table == 'jeans') ? 'delivery' : $table . '_delivery';

                $sql = "INSERT INTO $delivery_table ({$table}_id, size_id, {$table}_name, size, price, cash, bank, method, sales_date, update_date, quantity, user_id, bank_id, bank_name, status, reason)
                        VALUES ('$product_id', '{$row['size_id']}', '$product_name', '$size', '$price', '$cash', '$bank', '$method', '$date', '$date', '$quantity', '$user_id', '$bank_id', '$bank_name', '$status', '$reason')";
                mysqli_query($con, $sql);

                // Update quantity
                $new_quantity = $current_quantity - $quantity;
                $update_quantity = "UPDATE $table SET quantity = '$new_quantity' WHERE id = '$product_id' AND size = '$size'";
                mysqli_query($con, $update_quantity);

                // Add delivery details to combined delivery message
                $multi_delivery_message .= "Product Name: $product_name\nPrice: $price\nSize: $size\nReason: $reason\n\n";
            } else {
                $sales_table = ($table == 'jeans') ? 'sales' : $table . '_sales';

                $sql = "INSERT INTO $sales_table ({$table}_id, size_id, {$table}_name, size, price, cash, bank, method, sales_date, update_date, quantity, user_id, bank_id, bank_name, status)
                        VALUES ('$product_id', '{$row['size_id']}', '$product_name', '$size', '$price', '$cash', '$bank', '$method', '$date', '$date', '$quantity', '$user_id', '$bank_id', '$bank_name', 'active')";
                if (mysqli_query($con, $sql)) {
                    $successful_sales_count++;
                    $sales_id = mysqli_insert_id($con);
                    $sales_ids[] = $sales_id;

                    // Log sale and update quantity
                    $sales_log = ($table == 'jeans') ? 'sales_log' : $table . '_sales_log';
                    $sql_log = "INSERT INTO $sales_log ({$table}_id, size_id, {$table}_name, size, price, cash, bank, method, sales_date, update_date, quantity, user_id, status)
                                VALUES ('$product_id', '{$row['size_id']}', '$product_name', '$size', '$price', '$cash', '$bank', '$method', '$date', '$date', '$quantity', '$user_id', 'sold')";
                    mysqli_query($con, $sql_log);

                    $new_quantity = $current_quantity - $quantity;
                    $sql_update = "UPDATE $table SET quantity = '$new_quantity' WHERE id = '$product_id' AND size = '$size'";
                    mysqli_query($con, $sql_update);

                    // Add sale details to combined sale message
                    $multi_sale_message .= "Product Name: $product_name\nPrice: $price\nSize: $size\nQuantity: $quantity\n\n";
                }
            }
        }
    }

    // Send combined notifications
    if (!empty($multi_sale_message)) {
        $sale_subject = "Combined Sales Notification";
        sendMessageToSubscribers("Sale made:\n" . $multi_sale_message, $con);
        sendEmailToSubscribers($multi_sale_message, $sale_subject, $con);
    }

    if (!empty($multi_delivery_message)) {
        $delivery_subject = "Combined Delivery Notification";
        sendMessageToSubscribers("Delivery prepared:\n" . $multi_delivery_message, $con);
        sendEmailToSubscribers($multi_delivery_message, $delivery_subject, $con);
    }

    echo "<script>window.location = 'action.php?status=success&redirect=multi.php';</script>";
}

// Function to handle verification notifications
function sendVerificationNotification($message, $con)
{
    $subject = "Verification Needed";
    sendMessageToSubscribers($message, $con);
    sendEmailToSubscribers($message, $subject, $con);
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
                <div class="grid grid-cols-4 gap-3">




                    <div class="card col-span-3 bg-white shadow-md rounded-md p-6 mx-lg max-w-lg">
                        <form method="post" enctype="multipart/form-data" id="saleForm">

                            <h2 class="text-4xl font-bold text-white-700 text-center mb-10">Rent Data Entry</h2>

                            <div>
                                <div id="salesEntries">
                                    <div class="sale-entry  grid grid-cols-5 gap-5">
                                        <!-- Code Name Field -->
                                        <div class="mb-3 col-span-2 ">
                                            <label class="text-gray-800 text-sm font-medium inline-block mb-2" for="code_name">Code Name</label>
                                            <select name="code_name[]" id="codeNameSelect" class="code_name w-48 border border-gray-300 p-2 rounded-md" style="width: 200px;" required onchange="fetchProductPriceN(this)">
                                                <option value="">Select Name</option>
                                                <?php
                                                $tables = ['dress'];
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



                                        <script>
                                            function initializeNiceSelect(container) {
                                                const selects = container.querySelectorAll('.code_name');
                                                selects.forEach(select => {
                                                    // Destroy existing nice-select if it exists
                                                    if (select.nextElementSibling && select.nextElementSibling.classList.contains('nice-select')) {
                                                        select.nextElementSibling.remove();
                                                    }

                                                    // Initialize new nice-select
                                                    NiceSelect.bind(select, {
                                                        searchable: true,
                                                        placeholder: 'Search...',
                                                        searchtext: 'Search...',
                                                        selectedtext: 'selected'
                                                    });

                                                    // Add change event listener
                                                    select.addEventListener('change', function() {
                                                        fetchSizes(this);
                                                    });
                                                });
                                            }
                                        </script>

                                        <style>
                                            /* Ensure the select element maintains a minimum width */
                                            .nice-select {
                                                width: 100%;
                                                /* Full width to fit container */
                                                min-width: 100px;
                                                /* Minimum width for short labels */
                                                max-width: 100%;
                                                /* Ensures no overflow from container */

                                            }
                                        </style>


                                        <!-- Size Field -->
                                        <!-- Size Field -->
                                        <div class="mb-3">
                                            <label class="text-gray-800 text-sm font-medium inline-block mb-2" for="size_name">Size</label>
                                            <select name="size_name[]" class="size_name  w-full border border-gray-300 form-input rounded-md" onchange="fetchProductPrice(this)" required>
                                                <option value="">Select Size</option>
                                            </select>
                                        </div>




                                        <!-- Price Field -->


                                        <!-- Cash Field -->

                                        <div class="col-span-2">
                                            <div class="mb-3 flex space-x-4 items-end">
                                                <!-- Cash Field -->
                                                <div>
                                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2" for="cash">Cash</label>
                                                    <input type="number" min="0" value="0" step="0.01" name="cash[]" class="cash form-input border border-gray-300 rounded-md" oninput="updatePrice(this)" onblur="setDefault(this)" required>
                                                </div>

                                                <!-- Bank Field -->
                                                <div>
                                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2" for="bank">Bank</label>
                                                    <input type="number" min="0" value="0" step="0.01" name="bank[]" class="bank form-input border border-gray-300 rounded-md" oninput="updatePrice(this)" onblur="setDefault(this)" required>
                                                </div>

                                                <div>
                                                    <label for="price" class="text-gray-800 text-sm font-medium inline-block mb-2"> Price</label>
                                                    <input type="number" value="0" step="0.01" name="price[]" class="price  form-input  border border-gray-300 p-2 rounded-md" required readonly>
                                                </div>
                                            </div>
                                        </div>








                                        <!-- Existing code name, size, cash, bank, price fields remain the same -->

                                        <!-- Add a new product details display section -->
                                        <div class=" product-details hidden border rounded-md p-4 bg-gray-50 mb-3  col-span-2">
                                            <div class="grid grid-cols-4 gap-4">
                                                <div class="col-span-2">
                                                    <img src="" alt="Product Image" class="product-image w-40 h-40 object-cover">
                                                </div>
                                                <div>
                                                    <p class="font-medium">Price: <span class="listed-price text-green-600">0.00</span></p>
                                                    <p class="font-medium">Quantity: <span class="product-quantity text-blue-600">0</span></p>
                                                </div>
                                                <div class="col-span-1">
                                                    <div class="payment-status hidden rounded-md p-2 text-sm">
                                                        <p>Payment Status: <span class="status-text"></span></p>
                                                        <p>Difference: <span class="price-difference"></span></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <!-- Remove Entry Button -->
                                        <div class="mb-3">
                                            <button type="button" class="btn bg-red-500 text-white px-4 py-2 rounded-md remove-entry" onclick="removeSaleEntry(this)">Remove</button>
                                        </div>
                                    </div>


                                    <!-- Add Sale Entry Button -->
                                </div>
                                <div class="mb-3 text-center">
                                    <button type="button" class="btn bg-info text-white px-4 py-2 rounded-md" onclick="addSaleEntry()">Add More</button>
                                </div>

                            </div>

                            <!-- Bank Name Field -->


                            <!-- Submit Button -->


                    </div>

                    <div class="card col-span-1 bg-white shadow-md rounded-md p-6 mx-lg max-w-lg">







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
                            <select name="method" id="method" class="form-select w-full border border-gray-300 p-2 rounded-md" required onchange="toggleReasonField()">
                                <option value="shop">Shop</option>
                                <option value="delivery">Delivery</option>
                            </select>
                        </div>

                        <!-- Reason field, initially hidden -->
                        <div class="mb-3" id="reasonField" style="display: none;">
                            <label class="text-gray-800 text-sm font-medium inline-block mb-2" for="reason">Reason</label>
                            <input type="text" name="reason" id="reason" class="form-input w-full border border-gray-300 p-2 rounded-md" placeholder="Please provide a reason">
                        </div>

                        <script>
                            function toggleReasonField() {
                                const methodSelect = document.getElementById("method");
                                const reasonField = document.getElementById("reasonField");

                                // Show the reason field only if "delivery" is selected
                                if (methodSelect.value === "delivery") {
                                    reasonField.style.display = "block";
                                } else {
                                    reasonField.style.display = "none";
                                }
                            }
                        </script>

                        <!-- Total Price Display -->
                        <div class="mb-3">
                            <label class="text-gray-800 text-sm font-medium inline-block mb-2">Total Price</label>
                            <input type="number" id="totalPrice" class="form-input w-full border border-gray-300 p-2 rounded-md" value="0" readonly>
                        </div>

                        <div class="text-center mt-5">
                            <?php if ($add_button) : ?>
                                <button name="add" type="submit" class="btn bg-green-500 text-white px-4 py-2 rounded-md">
                                    Sale
                                </button>
                            <?php endif; ?>
                        </div>


                    </div>
                    </form>

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
        function fetchProductPrice(element) {
            const saleEntry = element.closest('.sale-entry');
            const codeNameSelect = saleEntry.querySelector('.code_name');
            const [table, codeName] = codeNameSelect.value.split('|');
            const size = element.value;
            const productDetails = saleEntry.querySelector('.product-details');

            if (table && codeName && size) {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'fetch_price.php', true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (this.status === 200) {
                        const response = JSON.parse(this.responseText);
                        if (response.error) {
                            console.log('Error:', response.error);
                            return;
                        }

                        if (response.price) {
                            // Show product details section
                            productDetails.classList.remove('hidden');

                            // Update product details
                            const listedPrice = productDetails.querySelector('.listed-price');
                            const quantity = productDetails.querySelector('.product-quantity');
                            const priceInput = saleEntry.querySelector('.price');

                            listedPrice.textContent = response.price.toFixed(2);
                            quantity.textContent = response.stock;

                            // Store the actual price for validation
                            priceInput.dataset.actualPrice = response.price;

                            // Update product image if available
                            const productImage = productDetails.querySelector('.product-image');
                            if (response.image) {
                                productImage.src = '../../include/' + response.image;
                            } else {
                                productImage.src = '/api/placeholder/100/100';
                            }
                        }
                    }
                };
                xhr.send(`table=${table}&code_name=${encodeURIComponent(codeName)}&size=${encodeURIComponent(size)}`);
            }
        }


        function fetchProductPriceN(element) {
            const saleEntry = element.closest('.sale-entry');
            const codeNameSelect = saleEntry.querySelector('.code_name');
            const [table, codeName] = codeNameSelect.value.split('|');
            const size = element.value;
            const productDetails = saleEntry.querySelector('.product-details');

            if (table && codeName && size) {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'fetch_price2.php', true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (this.status === 200) {
                        const response = JSON.parse(this.responseText);
                        if (response.error) {
                            console.log('Error:', response.error);
                            return;
                        }

                        if (response.price) {
                            // Show product details section
                            productDetails.classList.remove('hidden');

                            // Update product details
                            const listedPrice = productDetails.querySelector('.listed-price');
                            const quantity = productDetails.querySelector('.product-quantity');
                            const priceInput = saleEntry.querySelector('.price');

                            listedPrice.textContent = response.price.toFixed(2);
                            quantity.textContent = response.stock;

                            // Store the actual price for validation
                            priceInput.dataset.actualPrice = response.price;

                            // Update product image if available
                            const productImage = productDetails.querySelector('.product-image');
                            if (response.image) {
                                productImage.src = '../../include/' + response.image;
                            } else {
                                productImage.src = '/api/placeholder/100/100';
                            }
                        }
                    }
                };
                xhr.send(`table=${table}&code_name=${encodeURIComponent(codeName)}`);
            }
        }

        function updatePrice(element) {
            const saleEntry = element.closest('.sale-entry');
            const cashInput = parseFloat(saleEntry.querySelector('.cash').value) || 0;
            const bankInput = parseFloat(saleEntry.querySelector('.bank').value) || 0;
            const priceInput = saleEntry.querySelector('.price');
            const actualPrice = parseFloat(priceInput.dataset.actualPrice) || 0;
            const paymentStatus = saleEntry.querySelector('.payment-status');
            const statusText = paymentStatus.querySelector('.status-text');
            const priceDifference = paymentStatus.querySelector('.price-difference');

            // Calculate total payment
            const totalPayment = cashInput + bankInput;
            priceInput.value = totalPayment.toFixed(2);

            // Show payment status
            paymentStatus.classList.remove('hidden');
            const difference = totalPayment - actualPrice;

            if (Math.abs(difference) < 0.01) { // Using small epsilon for floating-point comparison
                paymentStatus.className = 'payment-status bg-green-100 rounded-md p-2 text-sm';
                statusText.textContent = 'Correct Payment';
                statusText.className = 'status-text text-green-600 font-medium';
                priceDifference.textContent = '';
            } else {
                paymentStatus.className = 'payment-status bg-red-100 rounded-md p-2 text-sm';
                statusText.textContent = difference > 0 ? 'Overpayment' : 'Underpayment';
                statusText.className = 'status-text text-red-600 font-medium';
                priceDifference.textContent = Math.abs(difference).toFixed(2);
            }

            // Update overall total
            updateTotalPrice();
        }

        // Update the form submission validation
        document.getElementById('saleForm').addEventListener('submit', function(e) {
            let isValid = true;
            const saleEntries = document.querySelectorAll('.sale-entry');

            saleEntries.forEach(entry => {
                const cashInput = parseFloat(entry.querySelector('.cash').value) || 0;
                const bankInput = parseFloat(entry.querySelector('.bank').value) || 0;
                const actualPrice = parseFloat(entry.querySelector('.price').dataset.actualPrice) || 0;
                const totalPayment = cashInput + bankInput;

                // Check if totalPayment is less than actualPrice (indicating underpayment)
                if (totalPayment < actualPrice) {
                    isValid = false;
                    entry.querySelector('.price').classList.add('border-red-500');
                }
            });

            if (!isValid) {
                e.preventDefault();
                alert('Please ensure all payments are at least equal to the listed prices.');
            }
        });
    </script>

    <script>
        function setDefault(element) {
            if (element.value === "") {
                element.value = 0;
            }
        }
    </script>

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
        let entryCount = 1; // Initialize a counter for entries

        function addSaleEntry() {
            const salesEntries = document.getElementById('salesEntries');
            const firstEntry = salesEntries.querySelector('.sale-entry');
            const newEntry = firstEntry.cloneNode(true);

            // Reset values in the cloned entry
            newEntry.querySelectorAll('input').forEach(input => {
                input.value = input.type === 'number' ? '0' : '';
            });
            newEntry.querySelectorAll('select').forEach(select => {
                select.value = '';
            });

            // Clear any existing nice-select elements
            newEntry.querySelectorAll('.nice-select').forEach(el => el.remove());

            // Add the new entry to the container
            salesEntries.appendChild(newEntry);

            // Initialize NiceSelect2 for the new entry
            initializeNiceSelect(newEntry);
        }

        // Initialize NiceSelect2 for existing entries on page load
        document.addEventListener('DOMContentLoaded', function() {
            initializeNiceSelect(document.getElementById('salesEntries'));
        });
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
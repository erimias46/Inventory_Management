<?php
$redirect_link = "../../../";
$side_link = "../../../";
include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php';
include_once $redirect_link . 'include/email.php';
include_once $redirect_link . 'include/bot.php';








$type = $_GET['type'];
$sales_id = $_GET['sales_id'];


if ($type == 'jeans') {
    $sql = "SELECT * FROM delivery WHERE sales_id = $sales_id";
} else {
    $sql = "SELECT * FROM {$type}_delivery WHERE sales_id = $sales_id";
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


if (isset($_POST['update'])) {



    $sales_id = $_POST['sales_id'];
    $user_id = $_SESSION['user_id'];
    $product_name = $_POST['product_name'];

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

    // Update the sales record with all fields
    $sql = "UPDATE sales SET jeans_name = '$jeans_name', size = '$size', quantity = '$quantity', price = '$price', cash = '$cash', bank = '$bank', update_date = '$date', user_id = '$user_id', bank_id = '$bank_id', bank_name = '$bank_name' WHERE sales_id = '$sales_id'";
    $result = mysqli_query($con, $sql);

    if ($result) {
        // echo "<script>window.location = 'action.php?status=success&redirect=sale_jeans.php'; </script>";

        $message = "Sale has been updated\n";
        $message .= "Jeans Name: $jeans_name\n";
        $message .= "Price: $price\n";
        $message .= "Size: $size\n";
        $message .= "Quantity: $quantity\n";
        $message .= "Cash: $cash\n";
        $message .= "Bank: $bank\n";
        $message .= "Method: $method\n";


        $subject = "Sale Updated";

        // Send updates to subscribers
        sendMessageToSubscribers($message, $con);
        sendEmailToSubscribers($message, $subject, $con);
    } else {
        echo "<script>window.location = 'action.php?status=error&redirect=sale_jeans.php'; </script>";
    }
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
                            <h2 class="text-xl font-bold text-white-700 text-center mb-10">Delivery Verification</h2>
                            <form method="post" enctype="multipart/form-data" class="grid grid-cols-2 gap-5">
                                <!-- Jeans Name Field -->
                                <div class="mb-3">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2" for="code_name">Code Name</label>
                                    <select name="code_name" id="code_name" class="w-full border border-gray-300 p-2 rounded-md search-select" onchange="fetchSizes()" required readonly>
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
                                    <select name="size_name" id="size_name" class="form-select w-full border border-gray-300 p-2 rounded-md" required readonly>

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
                                    <input type="text" name="cash" id="cash" class="form-input w-full border border-gray-300 p-2 rounded-md" required value="<?php echo $cash; ?>">
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
                                    <input type="text" name="price" id="totalPrice" class="form-input w-full border border-gray-300 p-2 rounded-md" readonly required value="<?php echo $price; ?>">
                                </div>

                                <div class="mb-3">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2" for="price">Method</label>
                                    <select name="method" class="selectize" readonly>
                                        <option value="shop" <?php if (isset($row['method']) && $row['method'] == 'shop') echo 'selected'; ?>>Shop</option>
                                        <option value="delivery" <?php if (isset($row['method']) && $row['method'] == 'delivery') echo 'selected'; ?>>Delivery</option>
                                    </select>
                                </div>






                                <!-- Price Field -->


                                <!-- Submit Button Section -->
                                <div class="text-center mt-5">
                                    <?php if ($add_button) : ?>
                                        <button name="update" type="submit" class="btn btn-sm bg-info text-white rounded-full px-4 py-2">
                                            <i class="mgc_add_fill text-base me-2"></i> Verify Delivery
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
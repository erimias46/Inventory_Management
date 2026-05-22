<?php
$redirect_link = "../../";
$side_link = "../../";
include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php';
include_once $redirect_link . 'include/email.php';
include_once $redirect_link . 'include/bot.php';








$type = $_GET['type'];
$sales_id = $_GET['sales_id'];


if ($type == 'jeans') {
    $sql = "SELECT * FROM sales WHERE sales_id = $sales_id";
} else {
    $sql = "SELECT * FROM {$type}_sales WHERE sales_id = $sales_id";
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

    $product_name = $_POST['code_name'];
    $size = $_POST['size'];
    $cash = $_POST['cash'];
    $bank = $_POST['bank'];
    $method = $_POST['method'];
    $bank_name = $_POST['bank_name'];
    $price = $_POST['price'];
    $date = date('Y-m-d');
    $quantity = 1;
    $type = $_GET['type'];
    $sales_id = $_GET['sales_id'];
    $quantity = 1;


    if ($type == 'jeans') {
        $_POST['jeans_name'] = $_POST['code_name'];
        $_POST['sales_id'] = $sales_id;
        $_POST['quantity'] = $quantity;

        $place = 'all_sales.php';

        include('../price_calculator/api/exchange.php');
    } elseif ($type == 'shoes') {

        $_POST['shoes_name'] = $_POST['code_name'];
        $_POST['sales_id'] = $sales_id;
        $_POST['quantity'] = $quantity;
        $place = 'all_sales.php';


        include('../shoe/api/exchange.php');
    } elseif ($type == 'top') {

        $_POST['top_name'] = $_POST['code_name'];
        $_POST['sales_id'] = $sales_id;
        $_POST['quantity'] = $quantity;
        $place = 'all_sales.php';



        include('../top/api/exchange.php');
    } elseif ($type == 'accessory') {

        $_POST['accessory_name'] = $_POST['code_name'];
        $_POST['sales_id'] = $sales_id;
        $_POST['quantity'] = $quantity;
        $place = 'all_sales.php';
        include('../accessory/api/exchange.php');
    } elseif ($type == 'complete') {

        $_POST['complete_name'] = $_POST['code_name'];
        $_POST['sales_id'] = $sales_id;
        $_POST['quantity'] = $quantity;
        $place = 'all_sales.php';
        include('../complete/api/exchange.php');
    }





    echo "<script>window.location = 'action.php?status=success&redirect=all_sales.php'; </script>";
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





        $add_button = ($module['exchangesalejeans'] == 1) ? true : false;
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



                            <h2 class="text-4xl	 font-bold text-white-700 text-center mb-10">EXCHANGE <?php echo ucfirst($type); ?> </h2>

                            <p class="text-center text-red-500"> The product to be exchanged </p>
                            <p class="text-center text-red-500"> Product Name: <?php echo $product_name; ?> </p>
                            <p class="text-center text-red-500"> Size: <?php echo $size; ?> </p>
                            <p class="text-center text-red-500"> Price: <?php echo $price; ?> </p>



                            <div class="text-center mb-4">
                                <img id="productImage" src="" alt="Product Image" class="w-32 h-32 object-cover mx-auto hidden" />
                            </div>



                            <form method="post" enctype="multipart/form-data" class="grid grid-cols-2 gap-5">
                                <!-- Jeans Name Field -->
                                <div class="mb-3">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2" for="code_name"><?php echo ucfirst($type) ?> Name</label>
                                    <select name="code_name" id="code_name" class="w-full border border-gray-300 p-2 rounded-md search-select" onchange="fetchSizes(); fetchImage()" required>
                                        <option value="">Select Name</option>
                                        <?php
                                        $sql3 = "SELECT * FROM `$type` GROUP BY `{$type}_name` ORDER BY `{$type}_name` ASC";
                                        $result3 = mysqli_query($con, $sql3);

                                        if (mysqli_num_rows($result3) > 0) {
                                            while ($row3 = mysqli_fetch_assoc($result3)) {
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
                                    <select name="size" id="size_name" class="form-select w-full border border-gray-300 p-2 rounded-md" required>

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
                                    <input type="text" name="cash" id="cash" class="form-input w-full border border-gray-300 p-2 rounded-md" required>
                                </div>
                                <div class="mb-3">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2" for="bank">Bank</label>
                                    <input type="text" name="bank" id="bank" class="form-input w-full border border-gray-300 p-2 rounded-md" required>

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
                                    <input type="text" name="price" id="totalPrice" class="form-input w-full border border-gray-300 p-2 rounded-md" readonly required>
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
                                        <button name="add" type="submit" class="btn btn-sm bg-warning text-white rounded-full px-4 py-2">
                                            <i class="mgc_add_fill text-base me-2"></i>Exchange Sale
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
        // JavaScript function to fetch image
        function fetchImage() {
            const codeName = document.getElementById('code_name').value;
            const productImage = document.getElementById('productImage');

            if (codeName) {
                fetch(`fetch_image.php?type=<?php echo $type; ?>&name=${codeName}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            productImage.src ="../../include/"+ data.image;
                            productImage.classList.remove('hidden');
                        } else {
                            productImage.classList.add('hidden');
                        }
                    })
                    .catch(error => console.error('Error fetching image:', error));
            } else {
                productImage.classList.add('hidden');
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
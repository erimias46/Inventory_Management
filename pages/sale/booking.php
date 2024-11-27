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

    // Collect form data
    $user_id = $_SESSION['user_id'];
    $dress_id = $_POST['dress_id'];

    $price = $_POST['price'];
    $cash = $_POST['cash'];
    $bank = $_POST['bank'];
    $start_date=$_POST['start_date'];
    $end_date=$_POST['end_date'];
    $customer_name = $_POST['customer_name'];
    $customer_phone = $_POST['customer_phone'];


    $date = date('Y-m-d H:i:s');
    $quantity = 1;

    // Split the code_name into table and product name
  

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

  

    

    
    $add_booking = "INSERT INTO bookings ( dress_id,  total_price, cash, bank, bank_id, start_date, end_date, customer_name, customer_contact) 
                                VALUES ( '$dress_id','$price', '$cash', '$bank', '$bank_id', '$start_date', '$end_date', '$customer_name', '$customer_phone')";
    $result = mysqli_query($con, $add_booking);

    if ($result){
        echo "<script>alert('Booking added successfully');</script>";
    } else {
        echo "<script>alert('Failed to add booking');</script>";
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
    $title = 'Booking';
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
                            <h2 class="text-4xl font-bold text-white-700 text-center mb-10">Booking</h2>
                            <form method="post" enctype="multipart/form-data" class="grid grid-cols-2 gap-5">
                                <!-- Jeans Name Field -->
                                <div class="mb-3">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2" for="code_name">Code Name</label>
                                    <select name="dress_id" id="code_name" class=" w-full border border-gray-300 p-2 rounded-md  search-select" onchange="fetchSizes()" required>
                                        <option value="">Select Name</option>
                                        <?php
                                        $sql = "SELECT * FROM dress";
                                        $result = mysqli_query($con, $sql);
                                        if (mysqli_num_rows($result) > 0) {
                                            while ($row = mysqli_fetch_assoc($result)) { ?>
                                                <option value="<?= $row['id']?>">
                                                    <?= $row['dress_name'] ?>
                                                </option>
                                        <?php }
                                        }
                                        ?>
                                    </select>
                                </div>

                               

                                <!-- Customer Name Field -->
                                <div class="mb-3">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2" for="customer_name">Customer Name</label>
                                    <input list="customer_names" name="customer_name" id="customer_name" class="form-input w-full border border-gray-300 p-2 rounded-md" required>
                                    <datalist id="customer_names"></datalist>
                                </div>
                                <div class="mb-3">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2" for="customer_phone">Customer Phone</label>
                                    <input type="tel" name="customer_phone" id="customer_phone" class="form-input w-full border border-gray-300 p-2 rounded-md" required>
                                </div>



                                <div class="mb-3">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2" for="cash">Cash</label>
                                    <input type="number" value="0" step="0.01" name="cash" id="cash" class="form-input w-full border border-gray-300 p-2 rounded-md" required>
                                </div>
                                <div class="mb-3">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2" for="bank">Bank</label>
                                    <input type="number" value="0" step="0.01" name="bank"   id="bank" class="form-input w-full border border-gray-300 p-2 rounded-md" required>
                                </div>

                                <div class="mb-3">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2" for="cash">From Date</label>
                                    <input type="date" name="start_date" id="cash" class="form-input w-full border border-gray-300 p-2 rounded-md" required>
                                </div>
                                <div class="mb-3">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2" for="bank">Bank</label>
                                    <input type="date"  name="end_date"   id="bank" class="form-input w-full border border-gray-300 p-2 rounded-md" required>
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

                                






                                <!-- Price Field -->


                                <!-- Submit Button Section -->
                                <div class="text-center mt-5">
                                    <?php if ($add_button) : ?>
                                        <button name="add" type="submit" class="btn btn-sm bg-success text-white rounded-full px-4 py-2">
                                            <i class="mgc_add_fill text-base me-2"></i> Book
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


    <script>
        function previewImage(event) {
            const imagePreview = document.getElementById('imagePreview');
            imagePreview.innerHTML = ''; // Clear previous image
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    imagePreview.appendChild(img);
                }
                reader.readAsDataURL(file);
            }
        }
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fetch suggestions for customer names from the server and populate the datalist
            fetch('getcust.php')
                .then(response => response.json())
                .then(data => {
                    const datalist = document.getElementById('customer_names');
                    datalist.innerHTML = ''; // Clear previous options
                    data.forEach(item => {
                        const option = document.createElement('option');
                        option.value = item; // Customer name
                        datalist.appendChild(option);
                    });
                });
        });
    </script>
</body>

</html>












<script>
    document.getElementById('jeans_types').addEventListener('focus', function() {
        // Fetch suggestions from the server and populate the datalist
        fetch('get_job_types.php?database=jeans')
            .then(response => response.json())
            .then(data => {
                const datalist = document.getElementById('jeans_types');
                datalist.innerHTML = ''; // Clear previous options
                data.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.job_type; // Adjust to match your database field
                    datalist.appendChild(option);
                });
            });
    });
</script>

<script src="../../assets/libs/dropzone/min/dropzone.min.js"></script>
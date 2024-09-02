<?php
$redirect_link = "";
$side_link = "";
include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php';
$current_date = date('Y-m-d');
?>

<head>
    <?php
    $title = 'Generate Performa';
    include $redirect_link . 'partials/title-meta.php'; ?>

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
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-slate-900 dark:text-slate-200 text-lg font-medium"><?= $title ?></h4>
                    </div>
                    <div class="p-6">
                        <form method="POST" action="include/pdf/generate_performa2.php">
                            <div class="grid grid-cols-1 sm:grid-cols-3 md:grid-cols-4 gap-3 md:gap-6">
                                <div>
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2">VAT Registration No.</label>
                                    <input type="text" name="vat_reg" class="form-input" required>
                                </div>
                                <div>
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2">Tin Number</label>
                                    <input type="text" name="tin" class="form-input" required>
                                </div>
                               
                                <div>
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2">Client Name</label>
                                    <input list="customer_names" name="client" class="form-input" required>
                                    <datalist id="customer_names"></datalist>
                                </div>


                                <div>
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2">Address</label>
                                    <input type="text" name="invoice_to" class="form-input" required>
                                </div>
                                <!-- <div>
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2">Client</label>
                                    <input type="text" name="client" class="form-input" required>
                                </div> -->
                                <!-- <div>
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2">Quote Number</label>
                                    <input type="text" name="quote_number" class="form-input" required>
                                </div> -->
                                <div>
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2">Quote Sender</label>
                                    <input type="text" name="quote_sender" class="form-input" required>
                                </div>
                                <div>
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2">Requested By </label>
                                    <input type="text" name="requested_by" class="form-input" required>
                                </div>
                                <div>
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2">Phone </label>
                                    <input type="text" name="phone" class="form-input" required>
                                </div>
                                <div>
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2">VAT(<i>in %</i>) </label>
                                    <input type="number" min="0" max="100" name="vat" class="form-input" required>
                                </div>
                                <div>

                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2">Validity Day </label>
                                    <input type="number" min="0" name="valid" class="form-input" required>
                                </div>
                                <div>
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2">Choose Number of Columns</label>
                                    <select name="column_no" id="selectOption" class="form-input">
                                        <option value="">Select</option>
                                        <?php
                                        for ($i = 1; $i <= 20; $i++) {
                                        ?>
                                            <option value="<?php echo $i ?>"><?php echo $i ?> Columns</option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div id="inputForm" class="my-3 col-span-1 sm:col-span-3 md:col-span-4">
                                </div>
                            </div>
                            <div class="flex justify-end col-span-1 sm:col-span-3 md:col-span-4">
                                <button name="generate_performa" type="submit" class="btn btn-sm bg-success text-white rounded-full">
                                    <i class="mgc_add_line text-base me-2"></i>
                                    Generate
                                </button>
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

    <script>
        $(document).ready(function() {
            $('#selectOption').change(function() {
                var optionValue = $(this).val();
                if (optionValue) {
                    var inputForm = '<div class="grid grid-cols-3 gap-3">';
                    var inputCount = optionValue * 1;
                    for (var i = 1; i <= inputCount; i++) {
                        inputForm +=
                            '<div>';
                        inputForm +=
                            '<label class="text-gray-800 text-sm font-medium inline-block mb-2">Item Name </label>';
                        inputForm +=
                            '<input type="text" name="item_name[]" class="form-input" required>';
                        inputForm += '</div>';

                        inputForm +=
                            '<div>';
                        inputForm +=
                            '<label class="text-gray-800 text-sm font-medium inline-block mb-2">Quantity</label>';
                        inputForm +=
                            '<input type="number" min="0" name="item_qty[]" class="form-input" required>';
                        inputForm += '</div>';

                        inputForm +=
                            '<div>';
                        inputForm +=
                            '<label class="text-gray-800 text-sm font-medium inline-block mb-2">Unit Price</label>';
                        inputForm +=
                            '<input type="number" min="0" step=".01" name="item_price[]" class="form-input" required>';
                        inputForm += '</div>';
                    }
                    inputForm += '</div>';
                    $('#inputForm').html(inputForm);
                } else {
                    $('#inputForm').empty();
                }
            });
        });
    </script>
</body>

</html>
<?php
if (isset($_POST['add'])) {

    // POST methods
    $page_name = $_POST['page_name'];
    $table_name = $_POST['table_name'];
    $column_no = $_POST['column_no'];
    $column_types = $_POST['column_type'];
    $columns = $_POST['column'];
    $current_date = date('Y-m-d');

    // Create table
    $sql = "CREATE TABLE $table_name (";
    $sql .= "$columns[0] $column_types[0] PRIMARY KEY AUTO_INCREMENT,";
    for ($i = 1; $i < count($columns); $i++) {
        $sql .= "$columns[$i] $column_types[$i],";
    }
    $sql = rtrim($sql, ",");
    $sql .= ")";
    $result = mysqli_query($con, $sql);

    // set sql
    $constants = "INSERT INTO d_constants(name, db, date) VALUES ('$page_name', '$table_name', '$current_date')";
    $c_result = mysqli_query($con, $constants);

    if ($result && $c_result) {
        echo "<script>window.location = 'action.php?status=success&redirect=add.php'; </script>";
    } else {
        echo "<script>window.location = 'action.php?status=error&redirect=add.php'; </script>";
    }
}
?>
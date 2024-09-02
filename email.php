<?php
$redirect_link = "";
$side_link = "";
include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php';
$current_date = date('Y-m-d');
?>

<head>
    <?php
    $title = 'Email';
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
                        <form method="POST" action="include/pdf/email.php">
                            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 gap-3 md:gap-3">

                                <div class="mb-3">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2">To</label>
                                    <input type="text" name="to" class="form-input" required placeholder="Company Name">
                                </div>
                                <div class="mb-3">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2">Company Address</label>
                                    <input type="text" name="to_comp_add" class="form-input" required placeholder="Company Address">
                                </div>
                                <div class="mb-3">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2">City , State</label>
                                    <input type="text" name="to_city" class="form-input" required placeholder="City and State">
                                </div>

                                <div class="mb-3">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2">From</label>
                                    <input type="text" name="from" class="form-input" required>
                                </div>
                                <div class="mb-3">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2">Subject</label>
                                    <input type="text" name="subject" class="form-input" required>
                                </div>
                                <div class="mb-3">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2">Email Body</label>
                                    <textarea name="email_body" class="form-input" required rows="5"></textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2">Closing salutations</label>
                                    <input type="text" name="salut" class="form-input" >
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
<?php
$redirect_link = "../../"; $side_link = "../../";
include $redirect_link .'partials/main.php';
include_once $redirect_link .'include/db.php';
$current_date = date('Y-m-d');
?>

<head>
    <?php
    $title = 'Add Constant';
    include $redirect_link .'partials/title-meta.php'; ?>

    <?php include $redirect_link .'partials/head-css.php'; ?>
</head>

<body>

    <!-- Begin page -->
    <div class="flex wrapper">

        <?php include $redirect_link .'partials/menu.php'; ?>

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="page-content">

            <?php include $redirect_link .'partials/topbar.php'; ?>

            <main class="flex-grow p-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-slate-900 dark:text-slate-200 text-lg font-medium"><?= $title ?></h4>
                    </div>
                    <div class="p-6">
                        <form method="POST">
                            <div class="flex flex-wrap">
                                <div class="m-2 flex-1">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2">Page Display Name </label>
                                    <input type="date" name="page_name" class="form-input" required>
                                </div>
                                <div class="m-2 flex-1">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2">Table </label>
                                    <input type="date" name="table_name" class="form-input" required>
                                </div>
                                <div class="m-2 flex-1">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2">Choose Number of Columns</label>
                                    <select name="column_no" id="selectOption" class="form-input">
                                        <?php
                                        for ($i == 1; $i <= 20; $i++) {
                                        ?>
                                        <option value="<?php echo $i ?>"><?php echo $i ?> Columns</option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div id="inputForm" class="m-2 flex-1">
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <button name="add" type="submit" class="btn btn-sm bg-success text-white rounded-full">
                                    <i class="mgc_add_line text-base me-2"></i>
                                    add
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </main>

            <?php include $redirect_link .'partials/footer.php'; ?>

        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->

    </div>

    <?php include $redirect_link .'partials/customizer.php'; ?>

    <?php include $redirect_link .'partials/footer-scripts.php'; ?>

    <script>
    $(document).ready(function() {
        $('#selectOption').change(function() {
            var optionValue = $(this).val();
            if (optionValue) {
                var inputForm = '<div class="mb-3">';
                var inputCount = optionValue * 1;
                for (var i = 1; i <= inputCount; i++) {
                    inputForm +=
                        '<div class="mb-3">';
                    inputForm +=
                        '<label class="text-gray-800 text-sm font-medium inline-block mb-2">Column Name </label>';
                    inputForm +=
                        '<input type="text" name="column[]" class="form-input" required>';
                    inputForm += '</div>';

                    inputForm +=
                        '<div class="mb-3">';
                    inputForm += '<label class="text-gray-800 text-sm font-medium inline-block mb-2"> Type </label>';
                    inputForm +=
                        '<select name="column_type[]" class="form-input" id="inputGroupSelect04" required>';
                    inputForm += '<option value="INT"> Int</option>';
                    inputForm += '<option value="TEXT"> Text</option>';
                    inputForm += '<option value="DATE"> Date</option>';
                    inputForm += '</select>';
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
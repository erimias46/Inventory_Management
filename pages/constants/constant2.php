<?php
$redirect_link = "../../";
$side_link = "../../";
include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php';
$current_date = date('Y-m-d');

$id = $_GET['id'];

$sql1 = "SELECT * FROM d_constants WHERE id = '$id'";
$result2 = mysqli_query($con, $sql1);
$row1 = mysqli_fetch_assoc($result2);
$db_id = $row1['id'];
$name = $row1['name'];
$db_name = $row1['db'];

$ids = $_SESSION['user_id'];

$result = mysqli_query($con, "SELECT * FROM user WHERE user_id = $ids");

if ($result) {
    $row = mysqli_fetch_assoc($result);
    if ($row) {
        $user_id = $row['user_id'];
        $user_name = $row['user_name'];
        $password = $row['password'];
        $privileged = $row['previledge'];
        $module = json_decode($row['module'], true);

        $calculateButtonVisible = ($module['constview'] == 1) ? true : false;
        $addButtonVisible = ($module['constadd'] == 1) ? true : false;
        $updateButtonVisible = ($module['constedit'] == 1) ? true : false;
        $deleteButtonVisible = ($module['constdelete'] == 1) ? true : false;
        $generateButtonVisible = ($module['constgenerate'] == 1) ? true : false;
    } else {
        echo "No user found with the specified ID";
    }
    mysqli_free_result($result);
} else {
    echo "Error executing query: " . mysqli_error($con);
}

$primary_key = '';
$columns_query = "SHOW COLUMNS FROM $db_name";
$result = mysqli_query($con, $columns_query);
while ($row = mysqli_fetch_assoc($result)) {
    if ($row['Key'] == 'PRI') {
        $primary_key = $row['Field'];
        break;
    }
}
?>

<head>
    <?php
    $title = ucfirst($name);
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
                        <div class="flex justify-between items-center">
                            <h4 class="text-slate-900 dark:text-slate-200 text-lg font-medium"><?= $title ?></h4>
                            <div>
                                <?php if ($addButtonVisible) : ?>
                                    <button type="button" data-fc-type="modal" data-fc-target="addModal" class="btn btn-sm rounded-full bg-success/25 text-success hover:bg-success hover:text-white">
                                        <i class="msr text-base me-2">add</i>
                                        Add <?= $title ?>
                                    </button>
                                <?php endif; ?>

                                <?php if ($generateButtonVisible) : ?>
                                    <a href='<?= $redirect_link . "pages/export.php?type=$db_name" ?>' class="btn btn-sm rounded-full bg-success/25 text-success hover:bg-success hover:text-white">
                                        <i class="msr text-base me-2">picture_as_pdf</i>
                                        Export
                                    </a>
                                <?php endif; ?>

                                <a id="del-btn" href="removeall.php?id=<?php echo $id ?>&from=<?= $db_name ?>" class="btn btn-base rounded-full bg-danger/25 text-danger hover:bg-danger hover:text-white">
                                    <i class="mgc_delete_2_line text-base me-2"></i> Delete All
                                </a>

                                <?php if ($deleteButtonVisible) : ?>
                                    <button type="button" id="bulk-delete-btn" class="btn btn-base rounded-full bg-danger/25 text-danger hover:bg-danger hover:text-white">
                                        <i class="mgc_delete_2_line text-base me-2"></i> Delete Selected
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <div class="min-w-full inline-block align-middle">
                                <div class="overflow-hidden">
                                    <form id="bulk-delete-form" method="POST" action="bulk_delete.php">
                                        <input type="hidden" name="db_name" value="<?= $db_name ?>">
                                        <input type="hidden" name="primary_key" value="<?= $primary_key ?>">
                                        <input type="hidden" name="id" value="<?php $id = $_GET['id'];
                                                                                echo $id ?>">
                                        <table id="zero_config" data-order='[[ 0, "dsc" ]]' class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                            <thead>
                                                <tr>
                                                    <th><input type="checkbox" id="select-all"></th>
                                                    <?php
                                                    $columns_query = "SHOW COLUMNS FROM $db_name";
                                                    $result = mysqli_query($con, $columns_query);
                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                    ?>
                                                        <th><?php echo $row['Field']; ?></th>
                                                    <?php
                                                    }
                                                    ?>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $sql = "SELECT * FROM $db_name";
                                                $result = mysqli_query($con, $sql);
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                ?>
                                                    <tr class="odd:bg-white even:bg-gray-100 dark:odd:bg-slate-700 dark:even:bg-slate-800">
                                                        <td><input type="checkbox" name="selected_ids[]" value="<?= $row[$primary_key] ?>"></td>
                                                        <?php
                                                        foreach ($row as $value) {
                                                            echo '<td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">' . $value . '</td>';
                                                        }
                                                        ?>
                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                            <a id="del-btn" href="remove.php?id=<?php echo $id ?>&key=<?= $row[$primary_key] ?>&from=<?= $db_name ?>" class="btn bg-danger/25 text-danger hover:bg-danger hover:text-white btn-sm rounded-full"><i class="mgc_delete_2_line text-base me-2"></i> Delete</a>
                                                            
                                                        </td>
                                                    </tr>
                                                    <!-- Edit modal -->
                                                    <div id="edit<?= $row[$primary_key] ?>" class="w-full h-full fixed top-0 left-0 z-50 transition-all duration-500 hidden">
                                                        <div class="fc-modal-open:mt-7 fc-modal-open:opacity-100 fc-modal-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto bg-white border shadow-sm rounded-md dark:bg-slate-800 dark:border-gray-700">
                                                            <div class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
                                                                <h3 class="font-medium text-gray-800 dark:text-white text-lg">Edit $title</h3>
                                                                <button class="inline-flex flex-shrink-0 justify-center items-center h-8 w-8 dark:text-gray-200" data-fc-dismiss type="button">
                                                                    <span class="material-symbols-rounded">close</span>
                                                                </button>
                                                            </div>
                                                            <form method="POST" action="proccess.php?id=<?php echo $id; ?>">
                                                                <div class="px-4 py-8 overflow-y-auto">
                                                                    <input type="hidden" name="edit_id" value="<?= $row[$primary_key] ?>">
                                                                    <input type="hidden" name="db_name" value="<?= $db_name ?>">
                                                                    <?php
                                                                    $sql_0 = "SHOW COLUMNS FROM $db_name";
                                                                    $result_0 = mysqli_query($con, $sql_0);
                                                                    mysqli_fetch_assoc($result_0);
                                                                    while ($row_0 = mysqli_fetch_assoc($result_0)) {
                                                                    ?>
                                                                        <div class="mb-3">
                                                                            <label class="text-gray-800 text-sm font-medium inline-block mb-2"><?= $row_0['Field'] ?></label>
                                                                            <input type="text" name="<?= $row_0['Field'] ?>" value="<?= $row[$row_0['Field']] ?>" required>
                                                                        </div>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </div>
                                                                <div class="flex justify-end items-center gap-4 p-4 border-t dark:border-slate-700">
                                                                    <button class="py-2 px-5 inline-flex justify-center items-center gap-2 rounded dark:text-gray-200 border dark:border-slate-700 font-medium hover:bg-slate-100 hover:dark:bg-slate-700 transition-all" data-fc-dismiss type="button">Close</button>
                                                                    <button name="update_data" type="submit" class="btn bg-success text-white">Update</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="addModal" class="w-full h-full fixed top-0 left-0 z-50 transition-all duration-500 hidden">
                    <div class="fc-modal-open:mt-7 fc-modal-open:opacity-100 fc-modal-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto bg-white border shadow-sm rounded-md dark:bg-slate-800 dark:border-gray-700">
                        <div class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
                            <h3 class="font-medium text-gray-800 dark:text-white text-lg">Add <?= $title ?></h3>
                            <button class="inline-flex flex-shrink-0 justify-center items-center h-8 w-8 dark:text-gray-200" data-fc-dismiss type="button">
                                <span class="material-symbols-rounded">close</span>
                            </button>
                        </div>
                        <form method="POST" action="proccess.php?id=<?php echo $db_id; ?>">
                            <div class="px-4 py-8 overflow-y-auto">
                                <input type="hidden" name="db_name" value="<?= $db_name ?>" class="form-input" required>
                                <?php
                                $sql = "SHOW COLUMNS FROM $db_name";
                                $result = mysqli_query($con, $sql);
                                mysqli_fetch_assoc($result);
                                while ($row = mysqli_fetch_assoc($result)) {
                                ?>
                                    <div class="mb-3">
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2"><?= $row['Field']; ?></label>
                                        <input type="text" name="<?= $row['Field']; ?>" class="form-input" required>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="flex justify-end items-center gap-4 p-4 border-t dark:border-slate-700">
                                <button class="py-2 px-5 inline-flex justify-center items-center gap-2 rounded dark:text-gray-200 border dark:border-slate-700 font-medium hover:bg-slate-100 hover:dark:bg-slate-700 transition-all" data-fc-dismiss type="button">Close</button>
                                <button name="add_data" type="submit" class="py-2.5 px-4 inline-flex justify-center items-center gap-2 rounded bg-success hover:bg-success-600 text-white">Add <?= $title ?></button>
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
        // Select/Deselect all checkboxes
        document.getElementById('select-all').addEventListener('click', function(event) {
            let checkboxes = document.querySelectorAll('input[name="selected_ids[]"]');
            checkboxes.forEach(checkbox => checkbox.checked = event.target.checked);
        });

        // Handle bulk delete
        document.getElementById('bulk-delete-btn').addEventListener('click', function() {
            let form = document.getElementById('bulk-delete-form');
            form.submit();
        });
    </script>
</body>

</html>
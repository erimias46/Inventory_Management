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
?>




<?php
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


        $calculateButtonVisible = ($module['constant'] == 1) ? true : false;


        $addButtonVisible = ($module['constant'] == 1) ? true : false;


        $updateButtonVisible = ($module['constant'] == 1) ? true : false;

        $deleteButtonVisible = ($module['constant'] == 1) ? true : false;


        $generateButtonVisible = ($module['constant'] == 1) ? true : false;
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

            <?php include $redirect_link . 'partials/topbar.php'; ?> <main class="flex-grow p-6">
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


                                <?php
                                $id = $_GET['id'];


                                ?>



                                <a id="del-btn" href="removeall.php?id=<?php echo $id ?>&from=<?= $db_name ?>" class="btn btn-base rounded-full bg-danger/25 text-danger hover:bg-danger hover:text-white"><i class="mgc_delete_2_line text-base me-2"></i> Delete All</a>

                                <a href="constant2.php?id=<?php echo $id ?>&from=<?= $db_name ?>" class="btn btn-base rounded-full bg-danger/25 text-danger hover:bg-danger hover:text-white">
                                    <i class="mgc_delete_2_line text-base me-2"></i> Delete Selected
                                </a>




                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <div class="min-w-full inline-block align-middle">
                                <div class="overflow-hidden">
                                    <table id="zero_config" data-order='[[ 0, "dsc" ]]' class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead>
                                            <tr>
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
                                            $columns_query = "SHOW COLUMNS FROM $db_name";
                                            $result = mysqli_query($con, $columns_query);
                                            $primary_key = '';
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                if ($row['Key'] == 'PRI') {
                                                    $primary_key = $row['Field'];
                                                    break;
                                                }
                                            }
                                            $sql = "SELECT * FROM $db_name";
                                            $result = mysqli_query($con, $sql);
                                            while ($row = mysqli_fetch_assoc($result)) {
                                            ?>
                                                <tr class="odd:bg-white even:bg-gray-100 dark:odd:bg-slate-700 dark:even:bg-slate-800">
                                                    <?php
                                                    foreach ($row as $value) {
                                                        echo '<td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">' . $value . '</td>';
                                                    }
                                                    ?>
                                                    <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">


                                                        <?php

                                                        $id = $_GET['id'];

                                                        ?>
                                                        <?php if ($deleteButtonVisible) : ?>




                                                            <a id="del-btn" href="remove.php?id=<?php echo $id ?>&key=<?= $row[$primary_key] ?>&from=<?= $db_name ?>" class="btn bg-danger/25 text-danger hover:bg-danger hover:text-white btn-sm rounded-full"><i class="mgc_delete_2_line text-base me-2"></i> Delete</a>


                                                        <?php endif; ?>

                                                        <?php if ($updateButtonVisible) : ?>
                                                            <button type="button" class="btn bg-warning/25 text-warning hover:bg-warning hover:text-white btn-sm rounded-full" data-fc-type="modal" data-fc-target="edit<?= $row[$primary_key] ?>">
                                                                <i class="mgc_pencil_line text-base me-2"></i>
                                                                Edit
                                                            </button>
                                                        <?php endif; ?>

                                                    </td>
                                                </tr>
                                                <!-- Edit modal -->
                                                <div id="edit<?= $row[$primary_key] ?>" class="w-full h-full fixed top-0 left-0 z-50 transition-all duration-500 hidden">
                                                    <div class="fc-modal-open:mt-7 fc-modal-open:opacity-100 fc-modal-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto bg-white border shadow-sm rounded-md dark:bg-slate-800 dark:border-gray-700">
                                                        <div class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
                                                            <h3 class="font-medium text-gray-800 dark:text-white text-lg">
                                                                Edit <?= $title ?>
                                                            </h3>
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
                                                                    $field = $row_0['Field'];
                                                                ?>
                                                                    <div class="mb-3">
                                                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2"><?= $field ?></label>
                                                                        <?php if ($field === 'stock_id' && $db_name === 'paper') {
                                                                            $stockSql = "SELECT stock_id, stock_type FROM stock";
                                                                            $stockResult = mysqli_query($con, $stockSql);
                                                                        ?>
                                                                            <select name="stock_id" id="stock_id" class="form-input" required>
                                                                                <option value="">Select a Stock</option>
                                                                                <?php while ($stockRow = mysqli_fetch_assoc($stockResult)) { ?>
                                                                                    <option value="<?= $stockRow['stock_id']; ?>" <?= $row['stock_id'] == $stockRow['stock_id'] ? 'selected' : '' ?>><?= $stockRow['stock_type']; ?></option>
                                                                                <?php } ?>
                                                                            </select>
                                                                        <?php } elseif ($field === 'stock_id' && $db_name === 'unitdigital') {
                                                                            $stockSql = "SELECT stock_id, stock_type FROM stock";
                                                                            $stockResult = mysqli_query($con, $stockSql);
                                                                        ?>
                                                                            <select name="stock_id" id="stock_id" class="form-input" required>
                                                                                <option value="">Select a Stock</option>
                                                                                <?php while ($stockRow = mysqli_fetch_assoc($stockResult)) { ?>
                                                                                    <option value="<?= $stockRow['stock_id']; ?>" <?= $row['stock_id'] == $stockRow['stock_id'] ? 'selected' : '' ?>><?= $stockRow['stock_type']; ?></option>
                                                                                <?php } ?>
                                                                            </select>

                                                                        <?php } elseif ($field === 'stock_id' && $db_name === 'banner_metal') {
                                                                            $stockSql = "SELECT stock_id, stock_type FROM stock";
                                                                            $stockResult = mysqli_query($con, $stockSql);
                                                                        ?>
                                                                            <select name="stock_id" id="stock_id" class="form-input" required>
                                                                                <option value="">Select a Stock</option>
                                                                                <?php while ($stockRow = mysqli_fetch_assoc($stockResult)) { ?>
                                                                                    <option value="<?= $stockRow['stock_id']; ?>"><?= $stockRow['stock_type']; ?></option>
                                                                                <?php } ?>
                                                                            </select>

                                                                        <?php } elseif ($field === 'stock_id' && $db_name === 'pagedb') {
                                                                            $stockSql = "SELECT stock_id, stock_type FROM office_stock";
                                                                            $stockResult = mysqli_query($con, $stockSql);
                                                                        ?>
                                                                            <select name="stock_id" id="stock_id" class="form-input" required>
                                                                                <option value="">Select a Stock</option>
                                                                                <?php while ($stockRow = mysqli_fetch_assoc($stockResult)) { ?>
                                                                                    <option value="<?= $stockRow['stock_id']; ?>" <?= $row['stock_id'] == $stockRow['stock_id'] ? 'selected' : '' ?>><?= $stockRow['stock_type']; ?></option>
                                                                                <?php } ?>
                                                                            </select>

                                                                        <?php } else { ?>
                                                                            <input type="text" name="<?= $field ?>" class="form-input" value="<?= $row[$field] ?>" required>
                                                                        <?php } ?>
                                                                    </div>
                                                                <?php } ?>
                                                            </div>
                                                            <div class="flex justify-end items-center gap-4 p-4 border-t dark:border-slate-700">
                                                                <button class="py-2 px-5 inline-flex justify-center items-center gap-2 rounded dark:text-gray-200 border dark:border-slate-700 font-medium hover:bg-slate-100 hover:dark:bg-slate-700 transition-all" data-fc-dismiss type="button">Close
                                                                </button>
                                                                <button name="update_data" type="submit" class="btn bg-success text-white">Update</button>
                                                            </div>
                                                        </form>
                                                        <script>
                                                            document.getElementById('width').addEventListener('input', calculateCareBanner);
                                                            document.getElementById('height').addEventListener('input', calculateCareBanner);

                                                            function calculateCareBanner() {
                                                                const width = document.getElementById('width').value;
                                                                const height = document.getElementById('height').value;
                                                                const careBanner = width * height;
                                                                document.getElementById('care_banner').value = careBanner || '';
                                                            }
                                                        </script>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="addModal" class="w-full h-full fixed top-0 left-0 z-50 transition-all duration-500 hidden">
                    <div class="fc-modal-open:mt-7 fc-modal-open:opacity-100 fc-modal-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto  bg-white border shadow-sm rounded-md dark:bg-slate-800 dark:border-gray-700">
                        <div class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
                            <h3 class="font-medium text-gray-800 dark:text-white text-lg">
                                Add <?= $title ?>
                            </h3>
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
                                    $field = $row['Field'];
                                ?>
                                    <div class="mb-3">
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2"><?= $field; ?></label>
                                        <?php if ($field === 'stock_id' && $db_name === 'paper') {
                                            $stockSql = "SELECT stock_id, stock_type FROM stock";
                                            $stockResult = mysqli_query($con, $stockSql);
                                        ?>
                                            <select name="stock_id" id="stock_id" class="form-input" required>
                                                <option value="">Select a Stock</option>
                                                <?php while ($stockRow = mysqli_fetch_assoc($stockResult)) { ?>
                                                    <option value="<?= $stockRow['stock_id']; ?>"><?= $stockRow['stock_type']; ?></option>
                                                <?php } ?>
                                            </select>
                                        <?php } elseif ($field === 'stock_id' && $db_name === 'laminationdb') {
                                            $stockSql = "SELECT stock_id, stock_type FROM stock";
                                            $stockResult = mysqli_query($con, $stockSql);
                                        ?>
                                            <select name="stock_id" id="stock_id" class="form-input" required>
                                                <option value="">Select a Stock</option>
                                                <option value="0">None</option>
                                                <?php while ($stockRow = mysqli_fetch_assoc($stockResult)) { ?>
                                                    <option value="<?= $stockRow['stock_id']; ?>"><?= $stockRow['stock_type']; ?></option>
                                                <?php } ?>
                                            </select>
                                        <?php } elseif ($field === 'stock_id' && $db_name === 'unitbanner') {
                                            $stockSql = "SELECT stock_id, stock_type FROM stock";
                                            $stockResult = mysqli_query($con, $stockSql);
                                        ?>

                                            <select name="stock_id" id="stock_id" class="form-input" required>
                                                <option value="">Select a Stock</option>
                                                <option value="0">None</option>
                                                <?php while ($stockRow = mysqli_fetch_assoc($stockResult)) { ?>
                                                    <option value="<?= $stockRow['stock_id']; ?>"><?= $stockRow['stock_type']; ?></option>
                                                <?php } ?>
                                            </select>
                                        <?php } elseif ($field === 'stock_id' && $db_name === 'unitdigital') {
                                            $stockSql = "SELECT stock_id, stock_type FROM stock";
                                            $stockResult = mysqli_query($con, $stockSql);
                                        ?>
                                            <select name="stock_id" id="stock_id" class="form-input" required>
                                                <option value="">Select a Stock</option>
                                                <?php while ($stockRow = mysqli_fetch_assoc($stockResult)) { ?>
                                                    <option value="<?= $stockRow['stock_id']; ?>"><?= $stockRow['stock_type']; ?></option>
                                                <?php } ?>
                                            </select>

                                        <?php } elseif ($field === 'stock_id' && $db_name === 'banner_metal') {
                                            $stockSql = "SELECT stock_id, stock_type FROM stock";
                                            $stockResult = mysqli_query($con, $stockSql);
                                        ?>
                                            <select name="stock_id" id="stock_id" class="form-input" required>
                                                <option value="">Select a Stock</option>
                                                <?php while ($stockRow = mysqli_fetch_assoc($stockResult)) { ?>
                                                    <option value="<?= $stockRow['stock_id']; ?>"><?= $stockRow['stock_type']; ?></option>
                                                <?php } ?>
                                            </select>

                                        <?php } elseif ($field === 'stock_id' && $db_name === 'pagedb') {
                                            $stockSql = "SELECT stock_id, stock_type FROM office_stock";
                                            $stockResult = mysqli_query($con, $stockSql);
                                        ?>
                                            <select name="stock_id" id="stock_id" class="form-input" required>
                                                <option value="">Select a Stock</option>
                                                <?php while ($stockRow = mysqli_fetch_assoc($stockResult)) { ?>
                                                    <option value="<?= $stockRow['stock_id']; ?>"><?= $stockRow['stock_type']; ?></option>
                                                <?php } ?>
                                            </select>

                                        <?php } else { ?>
                                            <input type="text" name="<?= $field; ?>" id="<?= $field; ?>" class="form-input" required>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="flex justify-end items-center gap-4 p-4 border-t dark:border-slate-700">
                                <button class="py-2 px-5 inline-flex justify-center items-center gap-2 rounded dark:text-gray-200 border dark:border-slate-700 font-medium hover:bg-slate-100 hover:dark:bg-slate-700 transition-all" data-fc-dismiss type="button">Close
                                </button>
                                <button name="add_data" type="submit" class="py-2.5 px-4 inline-flex justify-center items-center gap-2 rounded bg-success hover:bg-success-600 text-white">Add <?= $title ?>
                                </button>
                            </div>
                        </form>
                        <script>
                            document.getElementById('width').addEventListener('input', calculateCareBanner);
                            document.getElementById('height').addEventListener('input', calculateCareBanner);

                            document.getElementById('width').addEventListener('input', calculateLamination);
                            document.getElementById('height').addEventListener('input', calculateLamination);

                            function calculateLamination() {
                                const width = document.getElementById('width').value;
                                const height = document.getElementById('height').value;
                                const lamination = width * height;
                                document.getElementById('care_lamination').value = lamination || '';
                            }



                            function calculateCareBanner() {
                                const width = document.getElementById('width').value;
                                const height = document.getElementById('height').value;
                                const careBanner = width * height;
                                document.getElementById('care_banner').value = careBanner || '';
                            }
                        </script>
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

</body>

</html>
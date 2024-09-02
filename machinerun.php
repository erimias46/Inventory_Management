<?php
$redirect_link = "";
$side_link = "";
include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php';
$current_date = date('Y-m-d');
?>

<head>
    <?php
    $title = 'Machine Run';
    include $redirect_link . 'partials/title-meta.php'; ?>

    <?php include $redirect_link . 'partials/head-css.php'; ?>



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


            $calculateButtonVisible = ($module['saleview'] == 1) ? true : false;


            $addButtonVisible = ($module['saleadd'] == 1) ? true : false;

            $deleteButtonVisible = ($module['saledelete'] == 1) ? true : false;


            $updateButtonVisible = ($module['saleedit'] == 1) ? true : false;


            $generateButtonVisible = ($module['salegenerate'] == 1) ? true : false;
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
                    <div class="card mt-3">
                        <div class="card-header">
                            <div class="flex justify-between items-center">
                                <h4 class="text-slate-900 dark:text-slate-200 text-lg font-medium">Add</h4>
                                <div>

                                    <?php if ($addButtonVisible) : ?>

                                        <button type="button" data-fc-type="modal" data-fc-target="addModal" class="btn btn-sm rounded-full bg-success/25 text-success hover:bg-success hover:text-white">
                                            <i class="msr text-base me-2">add</i>
                                            Add Machine

                                        </button>

                                    <?php endif; ?>


                                    <?php if ($generateButtonVisible) : ?>
                                        <a href="../export.php?type=sales" class="btn btn-sm rounded-full bg-success/25 text-success hover:bg-success hover:text-white">
                                            <i class="msr text-base me-2">picture_as_pdf</i>
                                            Export
                                        </a>

                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="overflow-x-auto">
                                <div class="min-w-full inline-block align-middle">
                                    <div class="overflow-hidden">
                                        <table id="zero_config" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                            <thead>
                                                <tr>
                                                    <th>Action</th>
                                                    <th>Type</th>
                                                    <th>Device Count</th>
                                                    <th>Calculation count</th>
                                                    <th>Difference</th>


                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php

                                                $sql = " SELECT * from machine_run";

                                                $result = mysqli_query($con, $sql);
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                ?>
                                                    <tr class="odd:bg-white even:bg-gray-100 dark:odd:bg-slate-700 dark:even:bg-slate-800">
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">

                                                            <?php if ($deleteButtonVisible) : ?>

                                                                <a id="del-btn" href="machinerun.php?id=<?php echo $row['id']; ?>&from=machinerun" class="btn bg-danger/25 text-danger hover:bg-danger hover:text-white btn-sm rounded-full"><i class="mgc_delete_2_line text-base me-2"></i> Delete</a>

                                                            <?php endif; ?>



                                                            <?php if ($updateButtonVisible) : ?>

                                                                <button type="button" class="btn bg-warning/25 text-warning hover:bg-warning hover:text-white btn-sm rounded-full" data-fc-type="modal" data-fc-target="edit<?= $row['id'] ?>">
                                                                    <i class="mgc_pencil_line text-base me-2"></i>
                                                                    Edit
                                                                </button>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td class="px-6 90-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                            <?php echo $row['type']; ?></td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                            <?php echo $row['device_count']; ?></td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                            <?php echo $row['calc_count']; ?></td>

                                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                            <?php
                                                            $device_count = $row['device_count'];
                                                            $calc_count = $row['calc_count'];

                                                            echo  (int)$device_count   -   (int)$calc_count;


                                                            ?></td>

                                                    </tr>
                                                    <!-- Edit modal -->
                                                    <div id="edit<?= $row['id'] ?>" class="w-full h-full fixed top-0 left-0 z-50 transition-all duration-500 hidden">
                                                        <div class="fc-modal-open:mt-7 fc-modal-open:opacity-100 fc-modal-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto  bg-white border shadow-sm rounded-md dark:bg-slate-800 dark:border-gray-700">
                                                            <div class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
                                                                <h3 class="font-medium text-gray-800 dark:text-white text-lg">
                                                                    Edit Machine
                                                                </h3>
                                                                <button class="inline-flex flex-shrink-0 justify-center items-center h-8 w-8 dark:text-gray-200" data-fc-dismiss type="button">
                                                                    <span class="material-symbols-rounded">close</span>
                                                                </button>
                                                            </div>
                                                            <form method="POST">
                                                                <div class="px-4 py-8 overflow-y-auto">
                                                                    <input type="hidden" name="sales_id" value="<?php echo $row['id'] ?>">
                                                                    <input type="hidden" name="customer" value="<?php echo $row['type']; ?>">


                                                                    <div class="mb-3">
                                                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Device Count</label>
                                                                        <input type="text" name="device_count" class="form-input" value="<?= $row['device_count'] ?>" required>
                                                                    </div>

                                                                    <div class="flex gap-2 justify-between">
                                                                        <div class="mb-3">
                                                                            <label class="text-gray-800 text-sm font-medium inline-block mb-2">Calc Count
                                                                                Number</label>
                                                                            <input type="text" name="calc_count" class="form-input" value="<?= $row['calc_count'] ?>" required>
                                                                        </div>



                                                                    </div>




                                                                    <div class="flex justify-end items-center gap-4 p-4 border-t dark:border-slate-700">
                                                                        <button class="py-2 px-5 inline-flex justify-center items-center gap-2 rounded dark:text-gray-200 border dark:border-slate-700 font-medium hover:bg-slate-100 hover:dark:bg-slate-700 transition-all" data-fc-dismiss type="button">Close
                                                                        </button>
                                                                        <button name="update_purchase" type="submit" class="btn bg-success text-white">Edit Machine</button>
                                                                    </div>
                                                            </form>
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
                </div>


                <div id="addModal" class="w-full h-full fixed top-0 left-0 z-50 transition-all duration-500 hidden">
                    <div class="fc-modal-open:mt-7 fc-modal-open:opacity-100 fc-modal-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto  bg-white border shadow-sm rounded-md dark:bg-slate-800 dark:border-gray-700">
                        <div class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
                            <h3 class="font-medium text-gray-800 dark:text-white text-lg">
                                Add Machine
                            </h3>
                            <button class="inline-flex flex-shrink-0 justify-center items-center h-8 w-8 dark:text-gray-200" data-fc-dismiss type="button">
                                <span class="material-symbols-rounded">close</span>
                            </button>
                        </div>
                        <form method="POST">
                            <div class="px-4 py-8 overflow-y-auto">


                                <div class="flex gap-2 justify-between">
                                    <div class="mb-3">
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">
                                            Machine Type</label>

                                            <input type="text" name="type" class="form-input">

                                        
                                        
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">
                                            Device Count</label>
                                        <input type="text" name="device_count" class="form-input" required>
                                    </div>
                                </div>



                                <div class="flex gap-2 justify-between">
                                    <div class="mb-3">
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">Calculation Count
                                        </label>
                                        <input type="text" name="calc_count" class="form-input" required>
                                    </div>
                                </div>

                            </div>
                            <div class="flex justify-end items-center gap-4 p-4 border-t dark:border-slate-700">
                                <button class="py-2 px-5 inline-flex justify-center items-center gap-2 rounded dark:text-gray-200 border dark:border-slate-700 font-medium hover:bg-slate-100 hover:dark:bg-slate-700 transition-all" data-fc-dismiss type="button">Close
                                </button>
                                <button name="add_data" type="submit" class="py-2.5 px-4 inline-flex justify-center items-center gap-2 rounded bg-success hover:bg-success-600 text-white">Add Machine</button>
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


</body>

</html>

<?php
if (isset($_POST['add_data'])) {
    $type = $_POST['type'];
    $calc_count = $_POST['calc_count'];

    $device_count = $_POST['device_count'];

    $add_data = "INSERT INTO `machine_run`(`type`, `device_count`, `calc_count`)
VALUES ('$type','$device_count','$calc_count')";
    $result_add = mysqli_query($con, $add_data);

    if ($result_add) {
        echo "<script>
    window.location = 'machinerun.php?status=success';
</script>";
    } else {
        echo "<script>
    window.location = 'action.php?status=error&redirect=machinerun.php';
</script>";
    }
}


if (isset($_POST['update_purchase'])) {
    $sales_id = $_POST['sales_id'];
    $device_count = $_POST['device_count'];
    $calc_count = $_POST['calc_count'];

    $update_purchase = "UPDATE `machine_run` SET `device_count`='$device_count',`calc_count`='$calc_count' WHERE id='$sales_id'";
    $result_update = mysqli_query($con, $update_purchase);

    if ($result_update) {
        echo "<script>
    window.location = 'machinerun.php?status=success';
</script>";
    } else {
        echo "<script>
    window.location = 'action.php?status=error&redirect=machinerun.php';
</script>";
    }
}



if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $from = $_GET['from'];
    $delete = "DELETE FROM machine_run WHERE id='$id'";
    $result_delete = mysqli_query($con, $delete);

    if ($result_delete) {
        echo "<script>
    window.location = 'machinerun.php?status=success';
</script>";
    } else {
        echo "<script>
    window.location = 'action.php?status=error&redirect=machinerun.php';
</script>";
    }
}




?>
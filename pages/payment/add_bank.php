<?php
$redirect_link = "../../";
$side_link = "../../";

include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php';
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

        
        $calculateButtonVisible = ($module['banksview'] == 1) ? true : false;

        
        $addButtonVisible = ($module['banksadd'] == 1) ? true : false;
        $deleteButtonVisible = ($module['banksdelete'] == 1) ? true : false;

        


        
        $updateButtonVisible = ($module['banksedit'] == 1) ? true : false;

        
        $generateButtonVisible = ($module['banksgenerate'] == 1) ? true : false;
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
    $title = 'Add banks';
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
                            <h4 class="text-slate-900 dark:text-slate-200 text-lg font-medium">Banks</h4>
                            <div>

                            <?php if ($addButtonVisible) { ?>
                                <button type="button" data-fc-type="modal" data-fc-target="addModal" class="btn btn-sm rounded-full bg-success/25 text-success hover:bg-success hover:text-white">
                                    <i class="msr text-base me-2">add</i>
                                    Add Bank
                                </button>
                            <?php } ?>

                            <?php if ($generateButtonVisible) { ?>
                                <a href="<?= $redirect_link . 'pages/export.php?type=bankdb' ?>" class="btn btn-sm rounded-full bg-success/25 text-success hover:bg-success hover:text-white">
                                    <i class="msr text-base me-2">picture_as_pdf</i>
                                    Export
                                </a>
                            <?php } ?>
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
                                                <th>ID</th>
                                                <th>Bank Name</th>
                                                <th>Account Number</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php   
                                                $result = mysqli_query($con, "SELECT * FROM bankdb");
                                                while ($row = mysqli_fetch_assoc($result)){
                                                    ?>
                                            <tr class="odd:bg-white even:bg-gray-100 dark:odd:bg-slate-700 dark:even:bg-slate-800">
                                                <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"><?php echo $row['id'] ?></td>
                                                <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"><?php echo $row['bankname'] ?></td>
                                                <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"><?php echo $row['accountnumber'] ?></td>
                                                <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">

                                                <?php if ($deleteButtonVisible) { ?>
                                                    <a id="del-btn" href="remove.php?id=<?php echo $row['id'];?>&from=bankdb"
                                                        class="btn bg-danger/25 text-danger hover:bg-danger hover:text-white btn-sm rounded-full"><i
                                                            class="mgc_delete_2_line text-base me-2"></i> Delete</a>
                                                <?php } ?>

                                                <?php if ($updateButtonVisible) { ?>
                                                    <button type="button" class="btn bg-warning/25 text-warning hover:bg-warning hover:text-white btn-sm rounded-full"
                                                        data-fc-type="modal" data-fc-target="edit<?= $row['id'] ?>">
                                                        <i class="mgc_pencil_line text-base me-2"></i> 
                                                        Edit
                                                    </button>
                                                <?php } ?>

                                                </td>
                                            </tr>   
                                            <!-- Edit modal -->
                                            <div id="edit<?= $row['id'] ?>" class="w-full h-full fixed top-0 left-0 z-50 transition-all duration-500 hidden">
                                                <div class="fc-modal-open:mt-7 fc-modal-open:opacity-100 fc-modal-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto  bg-white border shadow-sm rounded-md dark:bg-slate-800 dark:border-gray-700">
                                                    <div class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
                                                        <h3 class="font-medium text-gray-800 dark:text-white text-lg">
                                                            Edit bank
                                                        </h3>
                                                        <button class="inline-flex flex-shrink-0 justify-center items-center h-8 w-8 dark:text-gray-200"
                                                                data-fc-dismiss type="button">
                                                            <span class="material-symbols-rounded">close</span>
                                                        </button>
                                                    </div>
                                                    <form method="POST">
                                                        <div class="px-4 py-8 overflow-y-auto">
                                                            <input type="hidden" name="bank_id" value="<?= $row['id'] ?>">
                                                            <div class="mb-3">
                                                                <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Name of the bank </label>
                                                                <input type="text" name="bankname" class="form-input"
                                                                    value="<?= $row['bankname'] ?>" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Account Number </label>
                                                                <input type="text" name="accnum" class="form-input"
                                                                    value="<?= $row['accountnumber'] ?>" required>
                                                            </div>
                                                        </div>
                                                        <div class="flex justify-end items-center gap-4 p-4 border-t dark:border-slate-700">
                                                            <button class="py-2 px-5 inline-flex justify-center items-center gap-2 rounded dark:text-gray-200 border dark:border-slate-700 font-medium hover:bg-slate-100 hover:dark:bg-slate-700 transition-all"
                                                                    data-fc-dismiss type="button">Close
                                                            </button>
                                                            <button name="edit_bank" type="submit" class="btn bg-success text-white">Edit bank</button>
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

                <div id="addModal" class="w-full h-full fixed top-0 left-0 z-50 transition-all duration-500 hidden">
                    <div class="fc-modal-open:mt-7 fc-modal-open:opacity-100 fc-modal-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto  bg-white border shadow-sm rounded-md dark:bg-slate-800 dark:border-gray-700">
                        <div class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
                            <h3 class="font-medium text-gray-800 dark:text-white text-lg">
                                Add Bank
                            </h3>
                            <button class="inline-flex flex-shrink-0 justify-center items-center h-8 w-8 dark:text-gray-200"
                                    data-fc-dismiss type="button">
                                <span class="material-symbols-rounded">close</span>
                            </button>
                        </div>
                        <form method="POST">
                            <div class="px-4 py-8 overflow-y-auto">
                                <div class="mb-3">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Name of the bank </label>
                                    <input type="text" name="bankname" class="form-input" required>
                                </div>
                                <div class="mb-3">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Account Number </label>
                                    <input type="text" name="accnum" class="form-input" required>
                                </div>
                            </div>
                            <div class="flex justify-end items-center gap-4 p-4 border-t dark:border-slate-700">
                                <button class="py-2 px-5 inline-flex justify-center items-center gap-2 rounded dark:text-gray-200 border dark:border-slate-700 font-medium hover:bg-slate-100 hover:dark:bg-slate-700 transition-all"
                                        data-fc-dismiss type="button">Close
                                </button>
                                <button name="add_bank" type="submit" class="py-2.5 px-4 inline-flex justify-center items-center gap-2 rounded bg-success hover:bg-success-600 text-white">Add bank</button>
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

if (isset($_POST['add_bank'])) {
    $bankname=$_POST['bankname'];
    $accountnumber=$_POST['accnum'];

    $add_bank = "INSERT INTO `bankdb` (`id`, `bankname`, `accountnumber`) VALUES (NULL, '$bankname', '$accountnumber');";
    error_log($add_bank);
    $result_add = mysqli_query($con, $add_bank);

    if ($result_add) {
        echo "<script>window.location = 'action.php?status=success&redirect=add_bank.php'; </script>";
    } else {
        echo "<script>window.location = 'action.php?status=error&redirect=add_bank.php'; </script>";
    }
}

if (isset($_POST['edit_bank'])) {
    $id = $_POST['bank_id'];
    $bankname=$_POST['bankname'];
    $accountnumber=$_POST['accnum'];

    $add_bank = "UPDATE `bankdb` SET `bankname` = '$bankname', `accountnumber` = '$accountnumber' WHERE id = $id;";
    $result_add = mysqli_query($con, $add_bank);

    if ($result_add) {
        echo "<script>window.location = 'action.php?status=success&redirect=add_bank.php'; </script>";
    } else {
        echo "<script>window.location = 'action.php?status=error&redirect=add_bank.php'; </script>";
    }

}
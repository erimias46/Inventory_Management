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


        $calculateButtonVisible = ($module['stockview'] == 1) ? true : false;


        $addButtonVisible = ($module['stockadd'] == 1) ? true : false;


        $updateButtonVisible = ($module['stockedit'] == 1) ? true : false;

        $deleteButtonVisible = ($module['stockdelete'] == 1) ? true : false;


        $generateButtonVisible = ($module['stockgenerate'] == 1) ? true : false;
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
    $title = 'Stock Managment';
    include $redirect_link . 'partials/title-meta.php'; ?>
    <link href="../../assets/libs/nice-select2/css/nice-select2.css" rel="stylesheet" type="text/css">

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
                            <h4 class="text-slate-900 dark:text-slate-200 text-lg font-medium">Stock</h4>
                            <div>

                                <?php if ($addButtonVisible) : ?>
                                    <button type="button" data-fc-type="modal" data-fc-target="addModal" class="btn btn-sm rounded-full bg-success/25 text-success hover:bg-success hover:text-white">
                                        <i class="msr text-base me-2">add</i>
                                        Add Stock
                                    </button>

                                <?php endif; ?>

                                <?php
                                $link = "export.php?type=stock";
                                ?>

                                <?php if ($generateButtonVisible) : ?>

                                    <a href="<?php echo $link; ?>" class="btn btn-sm rounded-full bg-success/25 text-success hover:bg-success hover:text-white">
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

                                                <th>Actions</th>
                                                <th>#</th>
                                                <th>Stock Type</th>
                                                <th>Ratio</th>
                                                <th>Stock Quantity</th>
                                                <th>Total Quantity</th>
                                                <th>Transaction</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sql = "SELECT * FROM stock ORDER BY stock_id DESC";
                                            $result = mysqli_query($con, $sql);
                                            while ($row = mysqli_fetch_assoc($result)) {
                                            ?>
                                                <tr class="odd:bg-white even:bg-gray-100 dark:odd:bg-slate-700 dark:even:bg-slate-800">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">

                                                        <?php if ($deleteButtonVisible) : ?>

                                                            <a id="del-btn" href="remove.php?id=<?php echo $row['stock_id']; ?>&from=stock" class="btn bg-danger/25 text-danger hover:bg-danger hover:text-white btn-sm rounded-full"><i class="mgc_delete_2_line text-base me-2"></i> Delete</a>
                                                        <?php endif; ?>



                                                        <?php if ($updateButtonVisible) : ?>


                                                            <button type="button" class="btn bg-warning/25 text-warning hover:bg-warning hover:text-white btn-sm rounded-full" data-fc-type="modal" data-fc-target="edit<?= $row['stock_id'] ?>">
                                                                <i class="mgc_pencil_line text-base me-2"></i>
                                                                Edit
                                                            </button>

                                                        <?php endif; ?>

                                                    </td>
                                                    <td class="px-6 90-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"><?php echo $row['stock_id']; ?></td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"><?php echo $row['stock_type']; ?></td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"><?php echo $row['ratio']; ?></td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"><?php echo $row['stock_quantity']; ?></td>

                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"><?php echo $row['stock_quantity2']; ?></td>

                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">

                                                        <?php if ($addButtonVisible) : ?>

                                                            <button type="button" class="btn bg-success/25 text-success hover:bg-success hover:text-white btn-sm rounded-full" data-fc-type="modal" data-fc-target="add" data-bs-id="<?php echo $row['stock_id']; ?>">
                                                                <i class="mgc_add_circle_line"></i>
                                                                <span class="m-1">Add</span>



                                                            </button>
                                                        <?php endif; ?>


                                                        <?php if ($deleteButtonVisible) : ?>


                                                            <button type="button" class="btn bg-warning/25 text-warning hover:bg-warning hover:text-white btn-sm rounded-full" data-fc-type="modal" data-fc-target="remove" data-bs-id="<?php echo $row['stock_id']; ?>">
                                                                <i class="mgc_minus_circle_line"></i>
                                                                <span class="m-1">Remove</span>

                                                            </button>

                                                        <?php endif; ?>



                                                    </td>


                                                </tr>
                                                <!-- Edit modal -->
                                                <div id="edit<?= $row['stock_id'] ?>" class="w-full h-full fixed top-0 left-0 z-50 transition-all duration-500 hidden">
                                                    <div class="fc-modal-open:mt-7 fc-modal-open:opacity-100 fc-modal-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto  bg-white border shadow-sm rounded-md dark:bg-slate-800 dark:border-gray-700">
                                                        <div class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
                                                            <h3 class="font-medium text-gray-800 dark:text-white text-lg">
                                                                Edit Stock
                                                            </h3>
                                                            <button class="inline-flex flex-shrink-0 justify-center items-center h-8 w-8 dark:text-gray-200" data-fc-dismiss type="button">
                                                                <span class="material-symbols-rounded">close</span>
                                                            </button>
                                                        </div>
                                                        <form method="POST">
                                                            <div class="px-4 py-8 overflow-y-auto">
                                                                <input type="hidden" name="stock_id" value="<?php echo $row['stock_id']; ?>">
                                                                <div class="mb-3">
                                                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Stock Type </label>
                                                                    <input type="text" name="stock_type" class="form-input" value="<?php echo $row['stock_type']; ?>" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Stock Quantity </label>
                                                                    <input type="text" name="stock_quantity" class="form-input" value="<?php echo $row['stock_quantity']; ?>" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Ratio </label>
                                                                    <input type="text" name="ratio" class="form-input" value="<?php echo $row['ratio']; ?>" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Danger Zone </label>
                                                                    <input type="text" name="dangerzone" class="form-input" value="<?php echo $row['dangerzone']; ?>" required>
                                                                </div>
                                                            </div>
                                                            <div class="flex justify-end items-center gap-4 p-4 border-t dark:border-slate-700">
                                                                <button class="py-2 px-5 inline-flex justify-center items-center gap-2 rounded dark:text-gray-200 border dark:border-slate-700 font-medium hover:bg-slate-100 hover:dark:bg-slate-700 transition-all" data-fc-dismiss type="button">Close
                                                                </button>
                                                                <button name="update_stock" type="submit" class="btn bg-success text-white">Update Stock</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>


                                                <!-- Add stock quanitity modal -->


                                                <div id="add" class="w-full h-full fixed top-0 left-0 z-50 transition-all duration-500 hidden">
                                                    <div class="fc-modal-open:mt-7 fc-modal-open:opacity-100 fc-modal-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto  bg-white border shadow-sm rounded-md dark:bg-slate-800 dark:border-gray-700">
                                                        <div class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
                                                            <h3 class="font-medium text-gray-800 dark:text-white text-lg">
                                                                Add Stock
                                                            </h3>
                                                            <button class="inline-flex flex-shrink-0 justify-center items-center h-8 w-8 dark:text-gray-200" data-fc-dismiss type="button">
                                                                <span class="material-symbols-rounded">close</span>
                                                            </button>
                                                        </div>
                                                        <form method="POST">
                                                            <input type="hidden" name="stock_id" value="<?php echo $row['stock_id']; ?>">
                                                            <div class="px-4 py-8 overflow-y-auto">
                                                                <div class="mb-3">
                                                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Previous Stock Quantity </label>
                                                                    <input type="text" name="pr_stock" class="form-input" value="<?php echo $row['stock_quantity2']; ?>" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Stock Quantity </label>
                                                                    <input type="text" name="plus_stock_quantity" class="form-input" required>
                                                                </div>

                                                            </div>
                                                            <div class="flex justify-end items-center gap-4 p-4 border-t dark:border-slate-700">
                                                                <button class="py-2 px-5 inline-flex justify-center items-center gap-2 rounded dark:text-gray-200 border dark:border-slate-700 font-medium hover:bg-slate-100 hover:dark:bg-slate-700 transition-all" data-fc-dismiss type="button">Close
                                                                </button>
                                                                <button name="plus_stock" type="submit" class="py-2.5 px-4 inline-flex justify-center items-center gap-2 rounded bg-success hover:bg-success-600 text-white">Add Stock</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>



                                                <!-- Remove quantitiy  modal -->


                                                <div id="remove" class="w-full h-full fixed top-0 left-0 z-50 transition-all duration-500 hidden">
                                                    <div class="fc-modal-open:mt-7 fc-modal-open:opacity-100 fc-modal-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto  bg-white border shadow-sm rounded-md dark:bg-slate-800 dark:border-gray-700">
                                                        <div class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
                                                            <h3 class="font-medium text-gray-800 dark:text-white text-lg">
                                                                Remove Stock
                                                            </h3>
                                                            <button class="inline-flex flex-shrink-0 justify-center items-center h-8 w-8 dark:text-gray-200" data-fc-dismiss type="button">
                                                                <span class="material-symbols-rounded">close</span>
                                                            </button>
                                                        </div>
                                                        <form method="POST">
                                                            <input type="hidden" name="stock_id" value="<?php echo $row['stock_id']; ?>">
                                                            <div class="px-4 py-8 overflow-y-auto">
                                                                <div class="mb-3">
                                                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Previous Stock Quantity </label>
                                                                    <input type="text" name="min_pr_stock" class="form-input" value="<?php echo $row['stock_quantity2']; ?>" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Stock Quantity </label>
                                                                    <input type="text" name="min_stock_quantity" class="form-input" required>
                                                                </div>

                                                                <div class="mb-3">
    <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Job Number </label>
    <select name="job_number" id="job_number" class="search-select" required>
        <?php
        // Assuming $con is your mysqli connection object
        $sql = "SELECT * FROM payment";
        $result = mysqli_query($con, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<option value="' . $row['job_number'] . '">' . $row['job_number'] . '</option>';
        }
        ?>
    </select>
</div>

<div class="mb-3">
    <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Reason </label>
    <select name="reason" id="reason" class="form-input" required>
        <?php
        // Initially, populate with job descriptions
        $sql_reasons = "SELECT DISTINCT job_description FROM payment";
        $result_reasons = mysqli_query($con, $sql_reasons);
        while ($row_reason = mysqli_fetch_assoc($result_reasons)) {
            echo '<option value="' . $row_reason['job_description'] . '">' . $row_reason['job_description'] . '</option>';
        }
        ?>
        <option value="Other">Other</option> <!-- Allow user to enter custom reason -->
    </select>
    </div>

                                                            </div>
                                                            <div class="flex justify-end items-center gap-4 p-4 border-t dark:border-slate-700">
                                                                <button class="py-2 px-5 inline-flex justify-center items-center gap-2 rounded dark:text-gray-200 border dark:border-slate-700 font-medium hover:bg-slate-100 hover:dark:bg-slate-700 transition-all" data-fc-dismiss type="button">Close
                                                                </button>
                                                                <button name="min_stock" type="submit" class="py-2.5 px-4 inline-flex justify-center items-center gap-2 rounded bg-warning hover:bg-warning-600 text-white">Remove Stock</button>
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
                                Add Stock
                            </h3>
                            <button class="inline-flex flex-shrink-0 justify-center items-center h-8 w-8 dark:text-gray-200" data-fc-dismiss type="button">
                                <span class="material-symbols-rounded">close</span>
                            </button>
                        </div>
                        <form method="POST">
                            <div class="px-4 py-8 overflow-y-auto">
                                <div class="mb-3">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Stock Type </label>
                                    <input type="text" name="stock_type" class="form-input" required>
                                </div>
                                <div class="mb-3">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Stock Quantity </label>
                                    <input type="text" name="stock_quantity" class="form-input" required>
                                </div>
                                <div class="mb-3">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Danger Zone </label>
                                    <input type="text" name="dangerzone" class="form-input" required>
                                </div>
                                <div class="mb-3">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Ratio </label>
                                    <input type="text" name="ratio" class="form-input" required>
                                </div>
                            </div>
                            <div class="flex justify-end items-center gap-4 p-4 border-t dark:border-slate-700">
                                <button class="py-2 px-5 inline-flex justify-center items-center gap-2 rounded dark:text-gray-200 border dark:border-slate-700 font-medium hover:bg-slate-100 hover:dark:bg-slate-700 transition-all" data-fc-dismiss type="button">Close
                                </button>
                                <button name="add_stock" type="submit" class="py-2.5 px-4 inline-flex justify-center items-center gap-2 rounded bg-success hover:bg-success-600 text-white">Add Stock</button>
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

    <!-- <link href="https://cdn.datatables.net/v/dt/jq-3.7.0/dt-1.13.6/datatables.min.css" rel="stylesheet">

    <script src="https://cdn.datatables.net/v/dt/jq-3.7.0/dt-1.13.6/datatables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable();
        });
    </script> -->

</body>

</html>

<?php

if (isset($_POST['add_stock'])) {
    $stock_type = $_POST['stock_type'];
    $stock_quantity = $_POST['stock_quantity'];
    $ratio = $_POST['ratio'];
    $stock_danger = $_POST['dangerzone'];

    $stock_quantity2 = $stock_quantity * $ratio;

    $add_stock = "INSERT INTO stock(stock_type, stock_quantity,ratio,stock_quantity2,dangerzone) 
                    VALUES ('$stock_type', '$stock_quantity','$ratio','$stock_quantity2','$stock_danger')";
    $result_add = mysqli_query($con, $add_stock);

    if ($result_add) {
        echo "<script>window.location = 'action.php?status=success&redirect=stock_managment.php'; </script>";
    } else {
        echo "<script>window.location = 'action.php?status=error&redirect=stock_managment.php'; </script>";
    }
}

if (isset($_POST['plus_stock'])) {
    $stock_id = $_POST['stock_id'];
    $plus_stock_quantity = $_POST['plus_stock_quantity'];
    $user = $_SESSION['username'];

    $sql = "SELECT * FROM stock WHERE stock_id = '$stock_id'";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);
    $stock_id = $row['stock_id'];
    $stock_quantity = $row['stock_quantity2'];
    $ratio = $row['ratio'];

    $sql_0 = "SELECT * FROM user WHERE user_name = '$user'";
    $result_0 = mysqli_query($con, $sql_0);
    $row_0 = mysqli_fetch_assoc($result_0);
    $user_id = $row_0['user_id'];

    $net_quantity = $plus_stock_quantity + $stock_quantity;
    $set_stock_quantity = $net_quantity / $ratio;

    $insert_log = "INSERT INTO stock_log(user_id, stock_id, status, last_quantity, added_removed, reason, jobnumber) 
                    VALUES ('$user_id', '$stock_id', 'add_quantity', '$stock_quantity', '$plus_stock_quantity', '', '')";
    $result_log = mysqli_query($con, $insert_log);

    if ($result_log) {
        $stock_update = "UPDATE `stock` SET `stock_quantity2`='$net_quantity',`stock_quantity`='$set_stock_quantity' WHERE `stock_id` = '$stock_id'";
        $result_update = mysqli_query($con, $stock_update);
        if ($result_update) {
            echo "<script>window.location = 'action.php?status=success&redirect=stock_managment.php'; </script>";
        } else {
            echo "<script>window.location = 'action.php?status=error&redirect=stock_managment.php'; </script>";
        }
    } else {
        echo "<script>window.location = 'action.php?status=error&redirect=stock_managment.php'; </script>";
    }
}

if (isset($_POST['min_stock'])) {
    $stock_id = $_POST['stock_id'];
    $min_pr_stock = $_POST['min_pr_stock'];
    $min_stock_quantity = $_POST['min_stock_quantity'];
    $job_number = $_POST['job_number'];
    $reason = $_POST['reason'];
    $user = $_SESSION['username'];

    $sql = "SELECT * FROM stock WHERE stock_id = '$stock_id'";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);
    $stock_id = $row['stock_id'];
    $stock_quantity = $row['stock_quantity2'];
    $ratio = $row['ratio'];

    $sql_0 = "SELECT * FROM user WHERE user_name = '$user'";
    $result_0 = mysqli_query($con, $sql_0);
    $row_0 = mysqli_fetch_assoc($result_0);
    $user_id = $row_0['user_id'];

    $net_quantity = $stock_quantity - $min_stock_quantity;
    $set_stock_quantity = $net_quantity / $ratio;
    if ($net_quantity < 0) {
        echo "<script>window.location = 'action.php?status=error&redirect=stock_managment.php'; </script>";
        exit();
    }

    $insert_log = "INSERT INTO stock_log(user_id, stock_id, status, last_quantity, added_removed, reason, jobnumber) 
                    VALUES ('$user_id', '$stock_id', 'remove_quantity', '$stock_quantity', '$min_stock_quantity', '$reason', '$job_number')";
    $result_log = mysqli_query($con, $insert_log);

    if ($result_log) {
        $stock_update = "UPDATE `stock` SET `stock_quantity2`='$net_quantity',`stock_quantity`='$set_stock_quantity' WHERE `stock_id` = '$stock_id'";
        $result_update = mysqli_query($con, $stock_update);
        if ($result_update) {
            echo "<script>window.location = 'action.php?status=success&redirect=stock_managment.php'; </script>";
        } else {
            echo "<script>window.location = 'action.php?status=error&redirect=stock_managment.php'; </script>";
        }
    } else {
        echo "<script>window.location = 'action.php?status=error&redirect=stock_managment.php'; </script>";
    }
}



if (isset($_POST['update_stock'])) {
    $stock_id = $_POST['stock_id'];
    $stock_type = $_POST['stock_type'];
    $stock_quantity = $_POST['stock_quantity'];
    $ratio = $_POST['ratio'];
    $dangerzone = $_POST['dangerzone'];

    $stock_quantity2 = $ratio * $stock_quantity;

    $stock_update = "UPDATE `stock` SET `stock_type`='$stock_type', `stock_quantity`='$stock_quantity',`ratio`='$ratio',`stock_quantity2`='$stock_quantity2' `dangerzone` = '$dangerzone' WHERE `stock_id` = '$stock_id'";
    $result_update = mysqli_query($con, $stock_update);

    if ($result_update) {
        echo "<script>window.location = 'action.php?status=success&redirect=stock_managment.php'; </script>";
    } else {
        echo "<script>window.location = 'action.php?status=error&redirect=stock_managment.php'; </script>";
    }
}
?>




<script>
    // JavaScript to handle selection change
    document.getElementById('job_number').addEventListener('change', function() {
        var selectedJobNumber = this.value;
        var reasonSelect = document.getElementById('reason');

        // Clear previous options
        reasonSelect.innerHTML = '';

        // Fetch and add options based on selected job number
        <?php
        $sql_reasons_dynamic = "SELECT job_description FROM payment WHERE job_number = ?";
        $stmt = mysqli_prepare($con, $sql_reasons_dynamic);
        mysqli_stmt_bind_param($stmt, 's', $selectedJobNumber);
        mysqli_stmt_execute($stmt);
        $result_dynamic = mysqli_stmt_get_result($stmt);

        while ($row_dynamic = mysqli_fetch_assoc($result_dynamic)) {
            echo 'reasonSelect.innerHTML += \'<option value="' . $row_dynamic['job_description'] . '">' . $row_dynamic['job_description'] . '</option>\';';
        }
        ?>

        // Add an option for 'Other' if user wants to enter custom reason
        reasonSelect.innerHTML += '<option value="Other">Other</option>';
    });
</script>
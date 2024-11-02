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

        $verifyButtonVisible = ($module['verifyjeans'] == 1) ? true : false;
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
    $title = 'Verify';
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
                            <h4 class="text-slate-900 dark:text-slate-200 text-lg font-medium">Verify</h4>
                            <div>


                            </div>
                        </div>
                    </div>

                    <div class="p-6">
                        <form method="POST">


                            <div class="overflow-x-auto">
                                <div class="min-w-full inline-block align-middle">
                                    <div class="overflow-hidden">
                                        <table id="zero_config" data-order='[[ 1, "dsc" ]]' class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                            <thead>
                                                <tr>
                                                    <th scope="col" class="py-3 ps-4" data-searchable="false" data-orderable="false">
                                                        <div class="flex items-center h-5">
                                                            <input id="checkAll" type="checkbox" class="form-checkbox rounded">
                                                            <label for="table-checkbox-all" class="sr-only">Checkbox</label>
                                                        </div>
                                                    </th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Action</th>

                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Item Name</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Size</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>

                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Active</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Error Type</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $sql = "
            SELECT id, 'Shoes' AS item_type, shoes_name AS item_name, size, type, price, quantity, created_at, active, error FROM shoes_verify
            UNION
            SELECT id, 'Jeans' AS item_type, jeans_name AS item_name, size, type, price, quantity, created_at, active, error FROM jeans_verify
            UNION
            SELECT id, 'Top' AS item_type, top_name AS item_name, size, type, price, quantity, created_at, active, error FROM top_verify
            UNION
            SELECT id, 'Complete' AS item_type, complete_name AS item_name, size, type, price, quantity, created_at, active, error FROM complete_verify
            UNION
            SELECT id, 'Accessory' AS item_type, accessory_name AS item_name, size, type, price, quantity, created_at, active, error FROM accessory_verify
            ORDER BY created_at DESC
        ";

                                                $result = mysqli_query($con, $sql);
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                ?>
                                                    <tr class="odd:bg-white even:bg-gray-100 dark:odd:bg-slate-700 dark:even:bg-slate-800">
                                                        <td class="py-3 ps-4">
                                                            <div class="flex items-center h-5">
                                                                <input id="table-checkbox-5" name="update[]" type="checkbox" class="form-checkbox rounded" value="<?php echo $row['id'] ?>">
                                                                <label for="table-checkbox-5" class="sr-only">Checkbox</label>
                                                            </div>
                                                        </td>

                                                        <td>
                                                            <a href="api/verify_products.php?id=<?php echo $row['id']; ?>&from=<?php echo strtolower($row['item_type']); ?>" id="del-btn" class="btn bg-success/25 text-success hover:bg-success hover:text-white btn-sm rounded-full">
                                                                <i class="mgc_check_circle_line text-base me-2"></i>
                                                                Verify
                                                            </a>
                                                            <a href="api/verify_products.php?id=<?php echo $row['id']; ?>&from=<?php echo strtolower($row['item_type']); ?>&delete=delete" id="del-btn" class="btn bg-danger/25 text-danger hover:bg-danger hover:text-white btn-sm rounded-full">
                                                                <i class="mgc_delete_2_line text-base me-2"></i>
                                                                Delete
                                                            </a>
                                                            
                                                        </td>

                                                        <td class="px-2.5 py-2 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                            <?php
                                                            // Assuming $row['created_at'] contains the date string
                                                            $date = new DateTime($row['created_at']);
                                                            echo $date->format('d-M-Y'); // Output format: 17-Oct-2024
                                                            ?>
                                                        </td>
                                                        <td class="px-2.5 py-2 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"><?php echo $row['id']; ?></td>
                                                        <td class="px-2.5 py-2 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"><?php echo $row['item_name']; ?></td>
                                                        <td class="px-2.5 py-2 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"><?php echo $row['size']; ?></td>
                                                        <td class="px-2.5 py-2 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"><?php echo $row['type']; ?></td>
                                                        <td class="px-2.5 py-2 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"><?php echo $row['price']; ?></td>
                                                        <td class="px-2.5 py-2 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"><?php echo $row['quantity']; ?></td>

                                                        <td class="px-2.5 py-2 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                            <?php
                                                            if ($row['active'] == 1) {
                                                                echo '<span class="text-success">Verified</span>';
                                                            } else {
                                                                echo '<span class="text-danger">Unverified</span>';
                                                            }
                                                            ?>
                                                        </td>
                                                        <td class="px-2.5 py-2 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                            <?php
                                                            if ($row['error'] == 1) {
                                                                echo '<span class="text-success">No Size Found</span>';
                                                            } else {
                                                                echo '<span class="text-danger">No Quantity Found</span>';
                                                            }
                                                            ?>
                                                        </td>
                                                    </tr>
                                                <?php
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
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
            $('#checkAll').change(function() {
                if ($(this).is(':checked')) {
                    $('input[name="update[]"]').prop('checked', true);
                } else {
                    $('input[name="update[]"]').each(function() {
                        $(this).prop('checked', false);
                    });
                }
            });

            $('input[name="update[]"]').click(function() {
                var total_checkboxes = $('input[name="update[]"]').length;
                var total_checkboxes_checked = $('input[name="update[]"]:checked').length;

                if (total_checkboxes_checked == total_checkboxes) {
                    $('#checkAll').prop('checked', true);
                } else {
                    $('#checkAll').prop('checked', false);
                }
            });
        });
    </script>




</body>

</html>

<?php
if (isset($_POST['verify'])) {
    if (isset($_POST['update'])) {
        foreach ($_POST['update'] as $update_id) {

            $sql = "SELECT * FROM shoes_verify WHERE id = $update_id";
            $result = mysqli_query($con, $sql);
            $row = mysqli_fetch_assoc($result);
            $shoes_name = $row['shoes_name'];
            $size = $row['size'];
            $size_id = $row['size_id'];
            $type_id = $row['type_id'];
            $image = $row['image'];
            $type = $row['type'];
            $price = $row['price'];
            $quantity = $row['quantity'];

            $active = $row['active'];
            $error = $row['error'];


            if ($error == '1') {
                $active = '1';
                $insert = "INSERT INTO `shoes`(`shoes_name`, `size`, `type`, `price`, `quantity`, `active`,`size_id`,`type_id`,`image`) VALUES ('$shoes_name','$size','$type','$price','$quantity','$active','$size_id','$type_id','$image')";
                $result_insert = mysqli_query($con, $insert);
                if ($result_insert) {

                    $delete = "DELETE FROM `shoes_verify` WHERE id = $update_id";
                    $result_delete = mysqli_query($con, $delete);
                    if ($result_delete) {
                        echo "<script>window.location = 'action.php?status=success&redirect=verify.php'; </script>";
                    } else {
                        echo "<script>window.location = 'action.php?status=error&redirect=verify.php'; </script>";
                    }
                } else {
                    echo "<script>window.location = 'action.php?status=error&redirect=verify.php'; </script>";
                }
            }
            if ($error == '2') {
                $sql = "SELECT * FROM shoes WHERE shoes_name = '$shoes_name' AND size = '$size' ";
                $result = mysqli_query($con, $sql);
                $row = mysqli_fetch_assoc($result);
                $quantity = $row['quantity'] + $quantity;
                $update = "UPDATE `shoes` SET `quantity`= '$quantity' WHERE shoes_name = '$shoes_name' AND size = '$size'";
                $result_update = mysqli_query($con, $update);
                if ($result_update) {
                    $delete = "DELETE FROM `shoes_verify` WHERE id = $update_id";
                    $result_delete = mysqli_query($con, $delete);
                    if ($result_delete) {
                        echo "<script>window.location = 'action.php?status=success&redirect=verify.php'; </script>";
                    } else {
                        echo "<script>window.location = 'action.php?status=error&redirect=verify.php'; </script>";
                    }
                } else {
                    echo "<script>window.location = 'action.php?status=error&redirect=verify.php'; </script>";
                }
            }
        }
    }
}
?>
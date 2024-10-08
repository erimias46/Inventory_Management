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
    $title = 'Refund Log';
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
                            <h4 class="text-slate-900 dark:text-slate-200 text-lg font-medium">Refund Log</h4>
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



                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Date Bought</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">shoes Name</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Size</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Method</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Date Refunded</th>
                                                    


                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
$sql = "SELECT * FROM shoes_sales_log WHERE status = 'Refund' ORDER BY sales_id DESC";

                                                $result = mysqli_query($con, $sql);
                                                while ($row = mysqli_fetch_assoc($result)) {

                                                   

                                                ?>
                                                    <tr class="odd:bg-white even:bg-gray-100 dark:odd:bg-slate-700 dark:even:bg-slate-800">


                                                        <td class="px-2.5 py-2 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"><?php echo $row['sales_id']; ?></td>
                                                        <td class="px-2.5 py-2 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"><?php echo $row['sales_date']; ?></td>
                                                        <td class="px-2.5 py-2 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"><?php echo $row['shoes_name']; ?></td>
                                                        <td class="px-2.5 py-2 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"><?php echo $row['size']; ?></td>
                                                        <td class="px-2.5 py-2 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"><?php echo $row['price']; ?></td>
                                                        <td class="px-2.5 py-2 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"><?php echo $row['method']; ?></td>
                                                        <td class="px-2.5 py-2 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"><?php echo $row['update_date']; ?></td>
                                                       




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
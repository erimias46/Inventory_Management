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


        $calculateButtonVisible = ($module['payview'] == 1) ? true : false;


        $addButtonVisible = ($module['payadd'] == 1) ? true : false;
        $deleteButtonVisible = ($module['paydelete'] == 1) ? true : false;

        $verifyButtonVisible = ($module['payverify'] == 1) ? true : false;



        $updateButtonVisible = ($module['payedit'] == 1) ? true : false;


        $generateButtonVisible = ($module['paygenerate'] == 1) ? true : false;
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
    $title = 'Exchange Log';
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
                            <h4 class="text-slate-900 dark:text-slate-200 text-lg font-medium">Exchange Log</h4>
                            <div>

                                <?php if ($generateButtonVisible) { ?>
                                    <a href="<?= $redirect_link . 'pages/export.php?type=bank' ?>" class=" btn btn-sm rounded-full bg-success/25 text-success hover:bg-success hover:text-white">
                                        <i class="msr text-base me-2">picture_as_pdf</i>
                                        Export
                                    </a>
                                <?php } ?>
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
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">cosmetics Name</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Size</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Exchange Date</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">cosmetics Name</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Size</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Difference</th>


                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
    // Fetch all rows from exchange_cosmetics ordered by id
    $sql = "SELECT * FROM exchange_cosmetics ORDER BY id DESC";
    $result = mysqli_query($con, $sql);

    while ($row = mysqli_fetch_assoc($result)) {
        
        // Fetch details for before_sale_id
        $sql = "SELECT * FROM cosmetics_sales WHERE sales_id = ".$row['before_sale_id'];
        $result2 = mysqli_query($con, $sql);
        if ($row2 = mysqli_fetch_assoc($result2)) {
            // If found, extract the values
            $before_date = $row2['sales_date'];
            $before_cosmetics_name = $row2['cosmetics_name'];
            $before_size = $row2['size'];
            $before_price = $row2['price'];
        } else {
            // If not found, skip or handle it (e.g., set default values)
            $before_date = $before_cosmetics_name = $before_size = $before_price = 'Not found';
        }

        // Fetch details for after_sale_id
        $sql = "SELECT * FROM cosmetics_sales WHERE sales_id = ".$row['after_sale_id'];
        $result2 = mysqli_query($con, $sql);
        if ($row2 = mysqli_fetch_assoc($result2)) {
            // If found, extract the values
            $after_date = $row2['sales_date'];
            $after_cosmetics_name = $row2['cosmetics_name'];
            $after_size = $row2['size'];
            $after_price = $row2['price'];
        } else {
            // If not found, skip or handle it (e.g., set default values)
            $after_date = $after_cosmetics_name = $after_size = $after_price = 'Not found';
        }

        // Calculate price difference if both sales are found and have valid prices
        if (is_numeric($after_price) && is_numeric($before_price)) {
            $difference = $after_price - $before_price;
        } else {
            $difference = 'N/A'; // Handle if prices are not available or invalid
        }

        // Output or use the data as needed
        // ...
    
?>

                                                    <tr class="odd:bg-white even:bg-gray-100 dark:odd:bg-slate-700 dark:even:bg-slate-800">
                                                        
                                                       
                                                        <td class="px-2.5 py-2 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"><?php echo $row['id']; ?></td>
                                                        <td class="px-2.5 py-2 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"><?php echo $before_date; ?></td>
                                                        <td class="px-2.5 py-2 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"><?php echo $before_cosmetics_name; ?></td>
                                                        <td class="px-2.5 py-2 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"><?php echo $before_size; ?></td>
                                                        <td class="px-2.5 py-2 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"><?php echo $before_price; ?></td>
                                                        <td class="px-2.5 py-2 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"><?php echo $after_date; ?></td>
                                                        <td class="px-2.5 py-2 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"><?php echo $after_cosmetics_name; ?></td>
                                                        <td class="px-2.5 py-2 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"><?php echo $after_size; ?></td>
                                                        <td class="px-2.5 py-2 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"><?php echo $after_price; ?></td>
                                                        <td class="px-2.5 py-2 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"><?php echo $difference; ?></td>
                                                        
                                                        
                                                        

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

            $sql = "SELECT * FROM cosmetics_verify WHERE id = $update_id";
            $result = mysqli_query($con, $sql);
            $row = mysqli_fetch_assoc($result);
            $cosmetics_name = $row['cosmetics_name'];
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
                $insert = "INSERT INTO `cosmetics`(`cosmetics_name`, `size`, `type`, `price`, `quantity`, `active`,`size_id`,`type_id`,`image`) VALUES ('$cosmetics_name','$size','$type','$price','$quantity','$active','$size_id','$type_id','$image')";
                $result_insert = mysqli_query($con, $insert);
                if ($result_insert) {

                    $delete = "DELETE FROM `cosmetics_verify` WHERE id = $update_id";
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
                $sql = "SELECT * FROM cosmetics WHERE cosmetics_name = '$cosmetics_name' AND size = '$size' ";
                $result = mysqli_query($con, $sql);
                $row = mysqli_fetch_assoc($result);
                $quantity = $row['quantity'] + $quantity;
                $update = "UPDATE `cosmetics` SET `quantity`= '$quantity' WHERE cosmetics_name = '$cosmetics_name' AND size = '$size'";
                $result_update = mysqli_query($con, $update);
                if ($result_update) {
                    $delete = "DELETE FROM `cosmetics_verify` WHERE id = $update_id";
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
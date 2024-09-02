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


        $calculateButtonVisible = ($module['vatview'] == 1) ? true : false;


        $addButtonVisible = ($module['vatadd'] == 1) ? true : false;
        $deleteButtonVisible = ($module['vatdelete'] == 1) ? true : false;





        $updateButtonVisible = ($module['vatedit'] == 1) ? true : false;


        $generateButtonVisible = ($module['vatgenerate'] == 1) ? true : false;
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
    $title = 'Vat Status';
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
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-3">


                </div>

                <div class="card mt-4">
                    <div class="card-header">
                        <h4 class="text-slate-900 dark:text-slate-200 text-lg font-medium">
                            VAT Dates
                        </h4>
                    </div>
                    <div class="p-4">
                        <div class="overflow-x-auto">
                            <div class="min-w-full inline-block align-middle">
                                <div class="overflow-hidden">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">From Date</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">To Date</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Sales including VAT</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Purchase including VAT</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Decision</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sql = "SELECT * FROM compare;";

                                            $result = mysqli_query($con, $sql);
                                            while ($row = mysqli_fetch_assoc($result)) {

                                                $from_date = $row['from_date'];
                                                $to_date = $row['to_date'];

                                                // Sum price_including_vat for sales
                                                $sql_sales_sum = "SELECT SUM(price_including_vat) AS total_sales_including_vat 
                                                                    FROM sales_withvat 
                                                                    WHERE sales_date BETWEEN '$from_date' AND '$to_date'";
                                                $result_sales_sum = mysqli_query($con, $sql_sales_sum);
                                                $sales_sum_row = mysqli_fetch_assoc($result_sales_sum);
                                                $total_sales_including_vat = $sales_sum_row['total_sales_including_vat'];

                                                // Sum price_including_vat for purchases
                                                $sql_purchase_sum = "SELECT SUM(price_including_vat) AS total_purchase_including_vat 
                                                                    FROM sales
                                                                    WHERE sales_date BETWEEN '$from_date' AND '$to_date'";
                                                $result_purchase_sum = mysqli_query($con, $sql_purchase_sum);
                                                $purchase_sum_row = mysqli_fetch_assoc($result_purchase_sum);
                                                $total_purchase_including_vat = $purchase_sum_row['total_purchase_including_vat'];

                                                // Now you can use $total_sales_including_vat and $total_purchase_including_vat as needed




                                            ?>
                                                <tr class="odd:bg-white even:bg-gray-100 hover:bg-gray-100 dark:odd:bg-gray-800 dark:even:bg-gray-700 dark:hover:bg-gray-700">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200"><?php echo $row['id']; ?></td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200"><?php echo $row['from_date']; ?></td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200"><?php echo $row['to_date']; ?></td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200"><?php echo $total_sales_including_vat; ?></td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200"><?php echo $total_purchase_including_vat; ?></td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                        <?php
                                                        if ($total_sales_including_vat > $total_purchase_including_vat) {
                                                            echo "Need to Purchase Products ". $total_sales_including_vat - $total_purchase_including_vat;
                                                        } else if ($total_sales_including_vat < $total_purchase_including_vat) {
                                                            echo "Need to Sale Products ". $total_purchase_including_vat - $total_sales_including_vat;
                                                        } else {
                                                            echo "Balanced";
                                                        }
                                                        ?>


                                                    
                                                </tr>
                                                
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Add Modal -->
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


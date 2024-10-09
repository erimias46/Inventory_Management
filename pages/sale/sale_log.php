<?php
$redirect_link = "../../";
$side_link = "../../";
include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php';
$current_date = date('Y-m-d');
?>

<head>
    <?php

    include $redirect_link . 'partials/title-meta.php'; ?>
    <?php include $redirect_link . 'partials/head-css.php'; ?>
</head>

<body>

    <!-- Begin page -->
    <div class="flex wrapper">
        <?php include $redirect_link . 'partials/menu.php'; ?>
        <div class="page-content">

            <?php include $redirect_link . 'partials/topbar.php'; ?>
            <main class="flex-grow p-6">

                <div class="card">
                    <div class="card mt-3">
                        <div class="p-6">
                            <div class="overflow-x-auto">
                                <div class="min-w-full inline-block align-middle">
                                    <div class="overflow-hidden">

                                        <table id="zero_config" data-order='[[ 0, "dsc" ]]' class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                            <thead>
                                                <tr>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Product Name</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Size</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Price</th>

                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Log Type</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Status</th>


                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php

                                                $sql = "
SELECT 'shoes' AS source, sales_id, shoes_name AS Name, sales_date, price, size,status
FROM shoes_sales_log
UNION ALL
SELECT 'top' AS source, sales_id, top_name AS Name, sales_date, price, size,status
FROM top_sales_log
UNION ALL
SELECT 'complete' AS source, sales_id, complete_name AS Name, sales_date, price, size,status
FROM complete_sales_log
UNION ALL
SELECT 'accessory' AS source, sales_id, accessory_name AS Name, sales_date, price, size,status
FROM accessory_sales_log
UNION ALL
SELECT 'jeans' AS source, sales_id, jeans_name AS Name, sales_date, price, size,status
FROM sales_log
ORDER BY sales_date DESC;
";


                                                $result22 = mysqli_query($con, $sql);
                                                $num = 1;
                                                while ($row = mysqli_fetch_assoc($result22)) {

                                                    $type = $row['source'];

                                                ?>
                                                    <tr class="odd:bg-white even:bg-gray-100 dark:odd:bg-slate-700 dark:even:bg-slate-800 cursor-pointer">
                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"> <?php echo $num ?> </td>


                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"> <?php echo $row['Name']; ?> </td>
                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"> <?php echo $row['size']; ?> </td>
                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"> <?php echo $row['price']; ?> </td>
                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"> <?php echo $row['sales_date']; ?> </td>
                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"> <?php echo ucfirst($row['source']); ?> </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                            <?php
                                                            $status = $row['status'];
                                                            if ($status == "add_quantity" || $status == "Refund"  || $status == "Exchange Back"  || $status == "DELIVERY CANCELED"  || $status == "SELL DELETED") {
                                                            ?>
                                                                <span class="btn bg-success">Quantity Added</span>
                                                            <?php
                                                            } else {
                                                            ?>
                                                                <span class="btn bg-danger">Quantity Removed</span>
                                                            <?php
                                                            }
                                                            ?>
                                                        </td>


                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"> <?php echo ucfirst($row['status']); ?> </td>




                                                    </tr>




                                                <?php
                                                    $num++;
                                                };
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <?php include $redirect_link . 'partials/footer.php'; ?>
            </main>
        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->

    </div>

    <?php include $redirect_link . 'partials/customizer.php'; ?>
    <?php include $redirect_link . 'partials/footer-scripts.php'; ?>
</body>


</html>
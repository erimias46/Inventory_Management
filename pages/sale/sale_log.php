<?php
$redirect_link = "../../";
$side_link = "../../";
include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php';
$current_date = date('Y-m-d');
$title = "Sale Log";
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

                                        <?php
                                        // Define an array of colors (10 colors)
                                        $colors = ['#D32F2F', '#7B1FA2', '#303F9F', '#0288D1', '#0097A7', '#388E3C', '#FBC02D', '#FFA000', '#F57C00', '#D84315'];



                                        // Array to keep track of assigned colors for dates
                                        $date_colors = [];

                                        // Index to keep track of color rotation
                                        $color_index = 0;

                                        $sql = "
SELECT 'dress' AS source, sales_id, dress_name AS Name, sales_date, price, size, status, created_at
FROM dress_sales_log

ORDER BY created_at DESC;
";

                                        $result22 = mysqli_query($con, $sql);
                                        $num = 1;
                                        ?>

                                        <table id="zero_config" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                            <thead>
                                                <tr>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Product Name</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Size</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Log Type</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Status</th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                while ($row = mysqli_fetch_assoc($result22)) {
                                                    $date = $row['sales_date'];

                                                    // If the date is not assigned a color yet, assign the next color from the array
                                                    if (!isset($date_colors[$date])) {
                                                        $date_colors[$date] = $colors[$color_index];
                                                        $color_index = ($color_index + 1) % count($colors); // Cycle through colors
                                                    }

                                                    $date_color = $date_colors[$date]; // Get the assigned color for the current date
                                                ?>
                                                    <tr class="odd:bg-white even:bg-gray-100 dark:odd:bg-slate-700 dark:even:bg-slate-800 cursor-pointer">
                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"><?php echo $num ?></td>
                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200" style="background-color: <?php echo $date_color; ?>;">
                                                            <?php echo date('d-M-Y', strtotime($row['sales_date'])); ?>
                                                        </td>
                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"><?php echo $row['Name']; ?></td>
                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"><?php echo $row['size']; ?></td>
                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"><?php echo $row['price']; ?></td>
                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"><?php echo ucfirst($row['source']); ?></td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                            <?php
                                                            $status = $row['status'];
                                                            if ($status == "add_quantity" || $status == "Refund" || $status == "Exchange Back" || $status == "DELIVERY CANCELED" || $status == "SELL DELETED") {
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
                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"><?php echo ucfirst($row['status']); ?></td>

                                                        <!-- Color the Date Cell Only -->

                                                    </tr>
                                                <?php
                                                    $num++;
                                                }
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
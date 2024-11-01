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



                                        ?>

                                        <table id="zero_config" data-order='[[ 0, "dsc" ]]' class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                            <thead>
                                                <tr>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Product Name</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Size</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Method</th>

                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Multi ID</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $sql = "SELECT * FROM multi_sale  ORDER BY created_at DESC";
                                                $result22 = mysqli_query($con, $sql);
                                                $num = 1;
                                                $prev_multi_id = null; // Store previous multi_id
                                                $colors = ['bg-red-500', 'bg-green-500', 'bg-yellow-500', 'bg-purple-500']; // Array of 5 strong colors
                                                $color_index = 0; // Index to keep track of current color

                                                while ($row = mysqli_fetch_assoc($result22)) {
                                                    $from_table = $row['from_table'];
                                                    $sales_id = $row['sales_id'];
                                                    $multi_id = $row['multi_id'];

                                                    if ($from_table == "jeans") {
                                                        $from_tables = "sales";
                                                    } else {
                                                        $from_tables = $from_table . "_sales";
                                                    }

                                                    $sql2 = "SELECT * FROM $from_tables WHERE sales_id='$sales_id'";
                                                    $result2 = mysqli_query($con, $sql2);

                                                    // Loop through inner result
                                                    while ($row2 = mysqli_fetch_assoc($result2)) {
                                                        $product_name = $row2[$from_table . '_name'];
                                                        $size = $row2['size'];
                                                        $price = $row2['price'];
                                                        $method = $row2['method'];
                                                        $created_at = $row2['created_at'];

                                                        // Change color if multi_id is different from the previous one
                                                        if ($multi_id != $prev_multi_id) {
                                                            $color_index = ($color_index + 1) % count($colors); // Cycle through colors
                                                            $prev_multi_id = $multi_id;
                                                        }
                                                ?>
                                                        <tr class="odd:bg-white even:bg-gray-100 dark:odd:bg-slate-700 dark:even:bg-slate-800 cursor-pointer">
                                                            <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"><?php
                                                                                                                                                            $createdAt = $row['created_at'];

                                                                                                                                                            // Create a DateTime object
                                                                                                                                                            $dateTime = new DateTime($createdAt);

                                                                                                                                                            // Format the date as "19-Oct-2024"
                                                                                                                                                            $formattedDate = $dateTime->format('d-M-Y');

                                                                                                                                                            // Format the time as "23:22:38"
                                                                                                                                                            $formattedTime = $dateTime->format('H:i:s');

                                                                                                                                                            echo $formattedDate . " - " . $formattedTime; ?></td>
                                                            <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"><?php echo $num ?></td>
                                                            <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"><?php echo $product_name; ?></td>
                                                            <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"><?php echo $size; ?></td>
                                                            <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"><?php echo $price ?></td>
                                                            <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"><?php echo ucfirst($method); ?></td>

                                                            <!-- Apply color only to the Multi ID column -->
                                                            <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium <?php echo $colors[$color_index]; ?> text-white"> <?php echo $multi_id; ?></td>
                                                        </tr>
                                                <?php
                                                        $num++;
                                                    }
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
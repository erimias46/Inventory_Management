<?php
$redirect_link = "../../";
$side_link = "../../";
include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php';
$current_date = date('Y-m-d');

$title = "All Sales";
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

                                        <table id="zero_config" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                            <thead>
                                                <tr>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Date</th>

                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Product Name</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Size</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Cash</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Bank</th>


                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Method</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                                    <th class=" p-2.5 text-left text-xs font-medium text-gray-500 uppercase"> Image</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php


                                                $prevDate = ''; // Variable to track the previous date (Y-m-d)
                                                $colors = [
                                                    'text-red-800',
                                                    'text-green-500',
                                                   
                                                    'text-yellow-500',
                                                    
                                                    'text-pink-500',
                                                    'text-indigo-500',
                                                    'text-orange-500',
                                                    'text-teal-500',
                                                    'text-gray-500'
                                                ]; // Array of 10 different text colors to alternate between
                                                $currentColorIndex = 0;

                                                $sql = "
SELECT 'dress' AS source, sales_id, dress_name AS Name, sales_date, price, size,cash,bank,method
FROM dress_sales

ORDER BY sales_date DESC;
";


                                                $result22 = mysqli_query($con, $sql);
                                                $num = 1;
                                                while ($row = mysqli_fetch_assoc($result22)) {


                                                    $currentDate = date('Y-m-d', strtotime($row['sales_date']));

                                                    // Check if the current row's date matches the previous row's date
                                                    if ($currentDate != $prevDate) {
                                                        $currentColorIndex = ($currentColorIndex + 1) % count($colors); // Cycle through colors
                                                    }
                                                    $dateTextColor = $colors[$currentColorIndex]; // Assign the text color based on the index

                                                    // Update previous date tracker
                                                    $prevDate = $currentDate;

                                                    $type = $row['source'];

                                                ?>
                                                    <tr class="odd:bg-white even:bg-gray-100 dark:odd:bg-slate-700 dark:even:bg-slate-800 cursor-pointer">
                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"> <?php echo $num ?> </td>
                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-sm <?php echo $dateTextColor; ?>">
                                                            <?php echo date('d-M-Y', strtotime($row['sales_date'])); ?>
                                                        </td>


                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"> <?php echo $row['Name']; ?> </td>
                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"> <?php echo $row['size']; ?> </td>
                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"> <?php echo $row['price']; ?> </td>
                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"> <?php echo $row['cash']; ?> </td>
                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"> <?php echo $row['bank']; ?> </td>


                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"> <?php echo $row['source']; ?> </td>
                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"> <?php echo $row['method']; ?> </td>


                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                            <a id="del-btn" href="api/remove.php?type=<?= $type ?>&sales_id=<?php echo $row['sales_id']; ?>&from=<?php echo $type . '_sales' ?>" class="btn bg-danger/25 text-danger hover:bg-danger hover:text-white btn-sm rounded-full">
                                                                <i class="mgc_delete_2_line text-base me-2"></i> Delete
                                                            </a>

                                                            <a id="del-btn" href="exchange.php?type=<?= $type ?>&sales_id=<?php echo $row['sales_id']; ?>" class="btn bg-warning text-white hover:bg-warning hover:text-white btn-sm rounded-full">
                                                                <i class="mgc_delete_2_line text-base me-2"></i> Exchange
                                                            </a>
                                                            <a id="del-btn" href="api/refund.php?type=<?= $type ?>&sales_id=<?php echo $row['sales_id']; ?>" class="btn bg-info text-white hover:bg-warning hover:text-white btn-sm rounded-full">
                                                                <i class="mgc_delete_2_line text-base me-2"></i> Refund
                                                            </a>
                                                            <a id="del-btn" href="edit.php?type=<?= $type ?>&sales_id=<?php echo $row['sales_id']; ?>" class="btn bg-success text-white hover:bg-warning hover:text-white btn-sm rounded-full">
                                                                <i class="mgc_delete_2_line text-base me-2"></i> Edit
                                                            </a>


                                                        </td>
                                                        <td>
                                                            <?php
                                                            $type_name = $type . '_name';
                                                            $product_name = $row['Name'];
                                                            $size = $row['size'];
                                                            $sql5 = "SELECT * FROM $type WHERE $type_name = '$product_name' AND size = '$size'";
                                                            $result5 = mysqli_query($con, $sql5);
                                                            $row5 = mysqli_fetch_assoc($result5);
                                                            $image = $row5['image'];
                                                            ?>
                                                            <img src="../../include/<?= $image ?>" alt="" class="w-30 h-20">

                                                        </td>
                                                    </tr>



                                                    <div id="exchange<?= $row['sales_id'] ?>" class="w-full h-full fixed top-0 left-0 z-50 transition-all duration-500 hidden">
                                                        <div class="fc-modal-open:mt-7 fc-modal-open:opacity-100 fc-modal-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-md sm:w-full m-3 sm:mx-auto bg-white border shadow-sm rounded-md dark:bg-slate-800 dark:border-gray-700">
                                                            <div class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
                                                                <h3 class="font-medium text-gray-800 dark:text-white text-lg">Exchange <?php echo $type; ?></h3>
                                                                <button class="inline-flex flex-shrink-0 justify-center items-center h-8 w-8 dark:text-gray-200" data-fc-dismiss type="button">
                                                                    <span class="material-symbols-rounded">close</span>
                                                                </button>
                                                            </div>
                                                            <form method="POST" class="overflow-y-auto">
                                                                <div class="px-4 py-8">
                                                                    <div class="grid grid-cols-3 md:grid-cols-2 gap-3">
                                                                        <input type="hidden" name="sales_id" value="<?= $row['sales_id']; ?>">



                                                                        <?php
                                                                        switch ($type) {
                                                                            case 'jeans':
                                                                                $sql3 = "SELECT * FROM jeans GROUP BY jeans_name ORDER BY jeans_name ASC";
                                                                                $sql4 = "SELECT size FROM jeansdb";  // Ensure this table exists
                                                                                break;
                                                                            case 'dress':
                                                                                $sql3 = "SELECT * FROM dress GROUP BY dress_name ORDER BY dress_name ASC";
                                                                                $sql4 = "SELECT size FROM dressdb";  // Ensure this table exists
                                                                                break;
                                                                            case 'top':
                                                                                $sql3 = "SELECT * FROM `top` GROUP BY top_name ORDER BY top_name ASC";
                                                                                $sql4 = "SELECT size FROM topdb";  // Ensure this table exists
                                                                                break;
                                                                            case 'accessory':
                                                                                $sql3 = "SELECT * FROM accessory GROUP BY accessory_name ORDER BY accessory_name ASC";
                                                                                $sql4 = "SELECT size FROM accessorydb";  // Ensure this table exists
                                                                                break;
                                                                            case 'complete':
                                                                                $sql3 = "SELECT * FROM complete GROUP BY complete_name ORDER BY complete_name ASC";
                                                                                $sql4 = "SELECT size FROM completedb";  // Ensure this table exists
                                                                                break;
                                                                        }
                                                                        ?>




                                                                        <div>
                                                                            <label class="text-gray-800 text-sm font-medium inline-block mb-2">Product Name</label>
                                                                            <select name="product_name" class="search-select" id="product_name_select">
                                                                                <?php
                                                                                // Select options based on ty


                                                                                $result3 = mysqli_query($con, $sql3);
                                                                                if (mysqli_num_rows($result3) > 0) {
                                                                                    while ($row3 = mysqli_fetch_assoc($result3)) { ?>
                                                                                        <option value="<?= $row3[$type . '_name'] ?>"><?= $row3[$type . '_name'] ?></option>
                                                                                <?php }
                                                                                }
                                                                                ?>
                                                                            </select>
                                                                        </div>

                                                                        <div>
                                                                            <label class="text-gray-800 text-sm font-medium inline-block mb-2">Size</label>
                                                                            <select name="size" class="search-select" id="jeans_size_select">
                                                                                <?php




                                                                                // $sql4 = "SELECT * FROM jeansdb";
                                                                                $result4 = mysqli_query($con, $sql4);
                                                                                if (mysqli_num_rows($result4) > 0) {
                                                                                    while ($row4 = mysqli_fetch_assoc($result4)) { ?>
                                                                                        <option value="<?= $row4['size'] ?>"><?= $row4['size'] ?></option>
                                                                                <?php }
                                                                                }
                                                                                ?>
                                                                            </select>
                                                                        </div>

                                                                        <div>
                                                                            <label class="text-gray-800 text-sm font-medium inline-block mb-2">New Price</label>
                                                                            <input type="number" step="0.01" name="price" class="form-input" required>
                                                                        </div>

                                                                        <div>
                                                                            <label class="text-gray-800 text-sm font-medium inline-block mb-2">Bank</label>
                                                                            <input type="number" name="bank" id="bankInput" class="form-input" required oninput="checkBankValue()" value="0">
                                                                        </div>

                                                                        <div id="bankNameDiv">
                                                                            <label class="text-gray-800 text-sm font-medium inline-block mb-2">Bank Name</label>
                                                                            <select name="bank_name" id="bankNameInput" class="selectize">
                                                                                <option value="">Select</option>
                                                                                <?php
                                                                                $sql5 = "SELECT * FROM bankdb";
                                                                                $result5 = mysqli_query($con, $sql5);
                                                                                if (mysqli_num_rows($result5) > 0) {
                                                                                    while ($row5 = mysqli_fetch_assoc($result5)) { ?>
                                                                                        <option value="<?= $row5['bankname'] ?>"><?= $row5['bankname'] ?></option>
                                                                                <?php }
                                                                                }
                                                                                ?>
                                                                            </select>
                                                                        </div>

                                                                        <div>
                                                                            <label class="text-gray-800 text-sm font-medium inline-block mb-2">Cash</label>
                                                                            <input type="number" name="cash" step="0.01" class="form-input" required value="0">
                                                                        </div>

                                                                        <div>
                                                                            <label class="text-gray-800 text-sm font-medium inline-block mb-2">Date</label>
                                                                            <input type="date" name="date" class="form-input" required value="<?= date('Y-m-d'); ?>">
                                                                        </div>

                                                                        <div>
                                                                            <label class="text-gray-800 text-sm font-medium inline-block mb-2">Quantity</label>
                                                                            <input type="text" name="quantity" class="form-input" required value="1">
                                                                        </div>

                                                                        <div>
                                                                            <label class="text-gray-800 text-sm font-medium inline-block mb-2">Method</label>
                                                                            <select name="method" class="selectize">
                                                                                <option value="shop">Shop</option>
                                                                                <option value="delivery">Delivery</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="flex justify-end items-center gap-4 p-4 border-t dark:border-slate-700">
                                                                    <button class="py-2 px-5 inline-flex justify-center items-center gap-2 rounded dark:text-gray-200 border dark:border-slate-700 font-medium hover:bg-slate-100 hover:dark:bg-slate-700 transition-all" data-fc-dismiss type="button">Close</button>
                                                                    <button name="exchange_jeans" type="submit" class="btn bg-success text-white">Exchange</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const rows = document.querySelectorAll('tr[data-href]');
        rows.forEach(row => {
            row.addEventListener('click', function(e) {
                // Check if the clicked element is not the delete button or a child of the delete button
                if (!e.target.closest('#del-btn')) {
                    window.location.href = row.dataset.href;
                }
            });
        });
    });
</script>

</html>
<?php
$redirect_link = "../../";
$side_link = "../../";
include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php';
$current_date = date('Y-m-d');

$title = "Tracking";
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
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Status</th>


                                                 
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

                                                $sql = "select * from bookings ORDER BY created_at DESC; ";


                                                $result22 = mysqli_query($con, $sql);
                                                $num = 1;
                                                while ($row = mysqli_fetch_assoc($result22)) {


                                                    $currentDate = date('Y-m-d', strtotime($row['created_at']));

                                                    // Check if the current row's date matches the previous row's date
                                                    if ($currentDate != $prevDate) {
                                                        $currentColorIndex = ($currentColorIndex + 1) % count($colors); // Cycle through colors
                                                    }
                                                    $dateTextColor = $colors[$currentColorIndex]; // Assign the text color based on the index

                                                    // Update previous date tracker
                                                    $prevDate = $currentDate;
                                                    $type = 'dress';


                                                ?>
                                                    <tr class="odd:bg-white even:bg-gray-100 dark:odd:bg-slate-700 dark:even:bg-slate-800 cursor-pointer">
                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"> <?php echo $num ?> </td>
                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-sm <?php echo $dateTextColor; ?>">
                                                            <?php echo date('d-M-Y', strtotime($row['created_at'])); ?>
                                                        </td>


                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"> <?php 

                                                        $sql2="SELECT * FROM dress WHERE id = ".$row['dress_id'];
                                                        $result2 = $con->query($sql2);
                                                        $row2 = $result2->fetch_assoc();
                                                        $dress_name = $row2['dress_name'];
                                                        $dress_size = $row2['size'];

                                                        echo $dress_name; ?> </td>
                                                        
                                                    
                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"> <?php echo $dress_size ?> </td>
                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"> <?php echo $row['total_price']; ?> </td>
                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"> <?php echo $row['cash']; ?> </td>
                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"> <?php echo $row['bank']; ?> </td>
                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"> <?php echo $row['status']; ?> </td>


                                                     


                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                            <a id="del-btn" href="api/remove.php?type=<?= $type ?>&sales_id=<?php echo $row['id']; ?>&from=<?php echo $type . '_sales' ?>" class="btn bg-danger/25 text-danger hover:bg-danger hover:text-white btn-sm rounded-full">
                                                                <i class="mgc_delete_2_line text-base me-2"></i> Delete
                                                            </a>

                                                            <a id="del-btn" href="edit.php?type=<?= $type ?>&sales_id=<?php echo $row['id']; ?>" class="btn bg-success text-white hover:bg-warning hover:text-white btn-sm rounded-full">
                                                                <i class="mgc_delete_2_line text-base me-2"></i> Edit
                                                            </a>


                                                        </td>
                                                        <td>
                                                            <?php
                                                            $type_name = $type . '_name';
                                                            $product_name = $dress_name;
                                                            $size = $dress_size;
                                                            $sql5 = "SELECT * FROM $type WHERE $type_name = '$product_name' AND size = '$size'";
                                                            $result5 = mysqli_query($con, $sql5);
                                                            $row5 = mysqli_fetch_assoc($result5);
                                                            $image = $row5['image'];
                                                            ?>
                                                            <img src="../../include/<?= $image ?>" alt="" class="w-30 h-20">

                                                        </td>
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
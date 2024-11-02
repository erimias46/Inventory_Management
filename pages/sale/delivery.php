<?php
$redirect_link = "../../";
$side_link = "../../";
include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php';
include_once $redirect_link . 'include/bot.php';
include_once $redirect_link . 'include/email.php';
$current_date = date('Y-m-d');
$title = "Delivery";
?>

<head>
    <?php

    include $redirect_link . 'partials/title-meta.php'; ?>
    <?php include $redirect_link . 'partials/head-css.php'; ?>
</head>


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

        $sale_jeans = ($module['salejeans'] == 1) ? true : false;
        $deliverysalejeans = ($module['deliverysalejeans'] == 1) ? true : false;
        $deletesalejeans = ($module['deletesalejeans'] == 1) ? true : false;
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
                            <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200 text-center">Delivery</h2>
                            <div class="overflow-x-auto">
                                <div class="min-w-full inline-block align-middle">
                                    <div class="overflow-hidden">

                                        <table id="zero_config" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                            <thead>
                                                <tr>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Product Name</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Size</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Price</th>

                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Type</th>

                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                                    <th class=" p-2.5 text-left text-xs font-medium text-gray-500 uppercase"> Image</th>
                                                    <th class=" p-2.5 text-left text-xs font-medium text-gray-500 uppercase"> Reason </th>
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


                                                    'text-teal-500'

                                                ]; // Array of 10 different text colors to alternate between
                                                $currentColorIndex = 0;



                                                $sql = "
SELECT 'shoes' AS source, sales_id, shoes_name AS Name, sales_date, price, size,verifiy,created_at,reason
FROM shoes_delivery where verifiy = 0
UNION ALL
SELECT 'top' AS source, sales_id, top_name AS Name, sales_date, price, size,verifiy,created_at,reason
FROM top_delivery where verifiy = 0
UNION ALL
SELECT 'complete' AS source, sales_id, complete_name AS Name, sales_date, price, size,verifiy,created_at,reason
FROM complete_delivery where verifiy = 0
UNION ALL
SELECT 'accessory' AS source, sales_id, accessory_name AS Name, sales_date, price, size,verifiy,created_at,reason
FROM accessory_delivery where verifiy = 0
UNION ALL
SELECT 'jeans' AS source, sales_id, jeans_name AS Name, sales_date, price, size,verifiy,created_at,reason
FROM delivery where verifiy = 0
ORDER BY created_at DESC;
";


                                                $result22 = mysqli_query($con, $sql);
                                                $num = 1;
                                                while ($row = mysqli_fetch_assoc($result22)) {


                                                    $currentDate = $row['reason'];

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


                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"> <?php echo $row['Name']; ?> </td>
                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"> <?php echo $row['size']; ?> </td>
                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"> <?php echo $row['price']; ?> </td>
                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"> <?php echo $row['sales_date']; ?> </td>
                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"> <?php echo $row['source']; ?> </td>

                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                            <?php if ($deletesalejeans) : ?>

                                                                <a id="del-btn"
                                                                    href="api/delivery_remove.php?id=<?php echo $row['sales_id']; ?>&from=delivery&source=<?php echo $row['source']; ?>"
                                                                    class="btn bg-danger/25 text-danger hover:bg-danger hover:text-white btn-sm rounded-full"><i
                                                                        class="mgc_delete_2_line text-base me-2"></i> Delete</a>

                                                            <?php endif; ?>





                                                            <?php if ($deliverysalejeans) : ?>


                                                                <?php if ($row['verifiy'] != '1'): ?>
                                                                    <a id="del-btn" href="api/verify.php?type=<?= $type ?>&sales_id=<?php echo $row['sales_id']; ?>" class="btn bg-success text-white hover:bg-warning hover:text-white btn-sm rounded-full">
                                                                        <i class="mgc_delete_2_line text-base me-2"></i> Verify
                                                                    </a>

                                                                <?php endif; ?>
                                                            <?php endif; ?>


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
                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-sm <?php echo $dateTextColor; ?>">
                                                            <?php echo $row['reason']; ?>
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
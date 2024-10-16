<?php
$redirect_link = "../../";
$side_link = "../../";
include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php';
$current_date = date('Y-m-d');

$title = "Type shoes";
?>

<head>
    <?php

    include $redirect_link . 'partials/title-meta.php'; ?>
    <?php include $redirect_link . 'partials/head-css.php'; ?>

    <?php

    $title = "All shoes";


    ?>


    <style>
        .product-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
            /* Ensures the image covers the entire area while maintaining aspect ratio */
            border-radius: 5px;
            /* Optional: Adds rounded corners to the image */
        }
    </style>
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
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">shoes Name</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Size</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Price</th>

                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Image</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Action</th>


                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $prevDate = ''; // Variable to track the previous date (Y-m-d)
                                                $colors = [
                                                    'text-red-800',
                                                    'text-green-500',
                                                    'text-blue-500',
                                                    'text-yellow-500',
                                                    'text-purple-500',
                                                    'text-pink-500',
                                                    'text-indigo-500',
                                                    'text-orange-500',
                                                    'text-teal-500',
                                                    'text-gray-500'
                                                ]; // Array of 10 different text colors to alternate between
                                                $currentColorIndex = 0; // To toggle between colors

                                                $sql = "SELECT shoes_name, 
               GROUP_CONCAT(CONCAT(size, '(', quantity, ')') SEPARATOR ', ') AS sizes, 
               price, image, created_at, id 
        FROM shoes 
       
        GROUP BY shoes_name, price, image  
        ORDER BY created_at DESC";

                                                $result22 = mysqli_query($con, $sql);
                                                while ($row = mysqli_fetch_assoc($result22)) {
                                                    // Extract just the date part (Y-m-d) from the timestamp
                                                    $currentDate = date('Y-m-d', strtotime($row['created_at']));

                                                    // Check if the current row's date matches the previous row's date
                                                    if ($currentDate != $prevDate) {
                                                        $currentColorIndex = ($currentColorIndex + 1) % count($colors); // Cycle through colors
                                                    }
                                                    $dateTextColor = $colors[$currentColorIndex]; // Assign the text color based on the index

                                                    // Update previous date tracker
                                                    $prevDate = $currentDate;
                                                ?>
                                                    <tr class="odd:bg-white even:bg-gray-100 dark:odd:bg-slate-700 dark:even:bg-slate-800 cursor-pointer">
                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium <?php echo $dateTextColor; ?>">
                                                            <?php echo $row['created_at']; ?>
                                                        </td>
                                                        <td> <?php echo $row['id']; ?> </td>
                                                        <td> <?php echo $row['shoes_name']; ?> </td>
                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200 text-ellipsis overflow-hidden">
                                                            <?php echo $row['sizes']; ?>
                                                        </td>
                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                            <?php echo $row['price']; ?>
                                                        </td>
                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                            <img width="100px" height="100px" src="../../include/<?php echo $row['image']; ?>" alt="Product Image" class="product-image" />
                                                        </td>
                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                            <a href="editprice.php?id=<?php echo $row['id']; ?>" class="btn bg-primary/25 text-primary hover:bg-primary hover:text-white btn-sm rounded-full">Set Price</a>

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
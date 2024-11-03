<?php
$redirect_link = "../../";
$side_link = "../../";
include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php';
$current_date = date('Y-m-d');


$title = "All Products";
?>

<head>
    <?php

    include $redirect_link . 'partials/title-meta.php'; ?>
    <?php include $redirect_link . 'partials/head-css.php'; ?>

    <?php

    $title = "All Jeans";


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

                                        <table id="zero_configss" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                            <thead>
                                                <tr>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Date</th>

                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Product Name</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Size</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Total Now</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Total Sold</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Total Recived</th>




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

                                                $sql = "
    SELECT 'jeans' AS category, 
       jeans_name AS product_name, 
       GROUP_CONCAT(CONCAT(size, '(', quantity, ')') SEPARATOR ', ') AS sizes, 
       SUM(quantity) AS total_quantity, 
       price, image, created_at, id 
FROM jeans 
WHERE quantity > 0 
GROUP BY jeans_name, price, image

UNION ALL

SELECT 'shoes' AS category, 
       shoes_name AS product_name, 
       GROUP_CONCAT(CONCAT(size, '(', quantity, ')') SEPARATOR ', ') AS sizes, 
       SUM(quantity) AS total_quantity, 
       price, image, created_at, id 
FROM shoes 
WHERE quantity > 0 
GROUP BY shoes_name, price, image

UNION ALL

SELECT 'accessory' AS category, 
       accessory_name AS product_name, 
       GROUP_CONCAT(CONCAT(size, '(', quantity, ')') SEPARATOR ', ') AS sizes, 
       SUM(quantity) AS total_quantity, 
       price, image, created_at, id 
FROM accessory 
WHERE quantity > 0 
GROUP BY accessory_name, price, image

UNION ALL

SELECT 'top' AS category, 
       top_name AS product_name, 
       GROUP_CONCAT(CONCAT(size, '(', quantity, ')') SEPARATOR ', ') AS sizes, 
       SUM(quantity) AS total_quantity, 
       price, image, created_at, id 
FROM top 
WHERE quantity > 0 
GROUP BY top_name, price, image

UNION ALL

SELECT 'complete' AS category, 
       complete_name AS product_name, 
       GROUP_CONCAT(CONCAT(size, '(', quantity, ')') SEPARATOR ', ') AS sizes, 
       SUM(quantity) AS total_quantity, 
       price, image, created_at, id
FROM complete
WHERE quantity > 0
GROUP BY complete_name, price, image

ORDER BY created_at DESC;

";


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


                                                    $product_name = $row['product_name'];
                                                    $total_quantity_now = $row['total_quantity'];


                                                    $sql4 = "SELECT product_name, SUM(quantity) AS total_quantity FROM products where product_name='$product_name'  ";
                                                    $result4 = mysqli_query($con, $sql4);
                                                    $row4 = mysqli_fetch_assoc($result4);
                                                    $total_quantity = $row4['total_quantity'];


                                                    $category = $row['category'];
                                                    file_put_contents("log.txt", "Category: $category\n", FILE_APPEND);

                                                    if ($category == 'jeans') {
                                                        $sql6 = "SELECT COUNT(*) AS total_sales 
         FROM sales 
         WHERE jeans_name = '$product_name' AND (status = 'active' OR status = 'Exchange Sell')";


                                                        $result6 = mysqli_query($con, $sql6);
                                                        $row6 = mysqli_fetch_assoc($result6);

                                                        // Set $total_quantity_sold to 0 if no result is found
                                                        $total_quantity_sold = $row6 ? $row6['total_sales'] : 0;

                                                        // Log the product name if total sales count is 0



                                                    } else {
                                                        $category_sales = $row['category'] . '_sales';
                                                        $product_names = $row['category'] . '_name';

                                                        // Construct the SQL query to count the occurrences
                                                        $sql5 = "SELECT COUNT(*) AS total_sales 
         FROM $category_sales 
         WHERE $product_names = '$product_name' AND (status = 'active' OR status = 'Exchange Sell')";

                                                        // Execute the query
                                                        $result5 = mysqli_query($con, $sql5);
                                                        $row5 = mysqli_fetch_assoc($result5);
                                                        $total_quantity_sold = $row5 ? $row5['total_sales'] : 0;
                                                    }









                                                ?>
                                                    <tr class="odd:bg-white even:bg-gray-100 dark:odd:bg-slate-700 dark:even:bg-slate-800 cursor-pointer">
                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-sm <?php echo $dateTextColor; ?>">
                                                            <?php
                                                            $createdAt = $row['created_at'];

                                                            // Create a DateTime object
                                                            $dateTime = new DateTime($createdAt);

                                                            // Format the date as "19-Oct-2024"
                                                            $formattedDate = $dateTime->format('d-M-Y');

                                                            // Format the time as "23:22:38"
                                                            $formattedTime = $dateTime->format('H:i:s');

                                                            echo $formattedDate . " - " . $formattedTime; ?>
                                                        </td>

                                                        <td> <?php echo $row['product_name']; ?> 
                                                        </td>

                                                        <td> <?php echo $row['category']; ?> </td>

                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200 text-ellipsis overflow-hidden">

                                                            <?php
                                                            // Check if getRedShade() is already declared
                                                            if (!function_exists('getRedShade')) {
                                                                function getRedShade($quantity)
                                                                {
                                                                    // Define the color steps
                                                                    $colors = [
                                                                        0 => '#ffffff', // Very light red (zero)
                                                                        1 => '#ffeeee', // Light red
                                                                        2 => '#ffb0b0', // Medium red
                                                                        3 => '#ff8080', // Bright red
                                                                        4 => '#ff4d4d', // Full red
                                                                        5 => '#cc0000'
                                                                    ];
                                                                    // Clamp quantity to 5 maximum
                                                                    $index = min(5, max(0, $quantity));

                                                                    return $colors[$index];
                                                                }
                                                            }

                                                            // Split the sizes string by ", " to get individual size-quantity pairs
                                                            $sizes = explode(', ', $row['sizes']);

                                                            // Display each size with its quantity-based color
                                                            foreach ($sizes as $sizeQty) {
                                                                if (preg_match('/(\w+)\((\d+)\)/', $sizeQty, $matches)) {
                                                                    $size = $matches[1]; // Extract the size name
                                                                    $quantity = (int)$matches[2]; // Extract the quantity

                                                                    // Get background color based on quantity
                                                                    $backgroundColor = getRedShade($quantity);

                                                                    // Set text color (black for lighter backgrounds, white for darker ones)
                                                                    $textColor = ($quantity <= 2) ? '#000000' : '#000000';

                                                                    // Display the size and quantity
                                                                    echo "<span><span style=\"background-color: $backgroundColor; color: $textColor; padding: 1px 3px; border-radius: 2px;\">$size [$quantity]</span></span> ";
                                                                }
                                                            }
                                                            ?>
                                                        </td>



                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                            <?php echo $row['price']; ?>
                                                        </td>
                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                            <?php echo $total_quantity_now ?>
                                                        </td>
                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                            <?php echo $total_quantity_sold ?>
                                                        </td>
                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                            <?php echo $total_quantity ?>
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


<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- DataTables library -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<!-- Moment.js for date handling -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

<!-- DataTables Moment.js plugin for sorting dates -->
<script src="https://cdn.datatables.net/plug-ins/1.10.19/sorting/datetime-moment.js"></script>


<script>
    $(document).ready(function() {
        // Initialize moment.js with the format you are using
        $.fn.dataTable.moment('DD-MMM-YYYY - HH:mm:ss'); // This should match your date format exactly

        $('#zero_configss').DataTable({
            "order": [
                [0, "desc"]
            ], // Orders the first column (date column) in descending order
            "searching": true, // Enables the search functionality
            "pageLength": -1, // Default number of entries to show per page
            "lengthMenu": [
                [10, 25, 50, -1], // Options for entries per page
                [10, 25, 50, "All"]
            ],
            "paging": false // Enables pagination
        });
    });
</script>



</html>
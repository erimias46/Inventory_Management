<?php
$redirect_link = "../../";
$side_link = "../../";
include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php';
$current_date = date('Y-m-d');
$title = "All shoes";
?>

<head>
    <?php

    include $redirect_link . 'partials/title-meta.php'; ?>
    <?php include $redirect_link . 'partials/head-css.php'; ?>

    <?php

    $title = "All shoes";


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





            $updateButtonVisible = ($module['editshoes'] == 1) ? true : false;
            $deleteButtonVisible = ($module['deleteshoes'] == 1) ? true : false;
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


    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
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
                                        <table id="zero_config"  class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                            <thead>
                                                <tr>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">shoes Name</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Size</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Image</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $sql = "SELECT * FROM shoes ORDER BY shoes_name, created_at DESC";
                                                $result22 = mysqli_query($con, $sql);

                                                // Define colors with better contrast and opacity for readability
                                                $colors = [
                                                    'rgba(0, 128, 128, 0.2)',    // Teal
                                                    'rgba(255, 87, 34, 0.2)',    // Deep orange
                                                    'rgba(103, 58, 183, 0.2)',   // Deep purple
                                                    'rgba(139, 195, 74, 0.2)',   // Lime green
                                                    'rgba(158, 158, 158, 0.2)'   // Medium grey
                                                ];

                                                // Store all unique shoes names first
                                                $uniqueNames = [];
                                                $colorMap = [];
                                                $tempResults = [];

                                                // First pass: collect unique names and assign colors
                                                while ($row = mysqli_fetch_assoc($result22)) {
                                                    $tempResults[] = $row;
                                                    if (!in_array($row['shoes_name'], $uniqueNames)) {
                                                        $uniqueNames[] = $row['shoes_name'];
                                                        $colorIndex = count($uniqueNames) - 1;
                                                        $colorMap[$row['shoes_name']] = $colors[$colorIndex % count($colors)];
                                                    }
                                                }

                                                $groupId = 0;
                                                $currentName = '';

                                                // Second pass: display the data
                                                foreach ($tempResults as $row) {
                                                    $shoesName = $row['shoes_name'];
                                                    $color = $colorMap[$shoesName];

                                                    // Start new group
                                                    if ($currentName !== $shoesName) {
                                                        $groupId++;
                                                        $currentName = $shoesName;
                                                ?>
                                                        <!-- Group header row -->
                                                        <tr style="background-color: <?php echo $color; ?>;" class="cursor-pointer hover:opacity-90">
                                                            <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo $row['id']; ?></td>
                                                            <td class="px-2 py-2.5 text-sm font-medium text-gray-900"><?php echo $shoesName; ?></td>
                                                            <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo $row['size']; ?></td>
                                                            <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo $row['price']; ?></td>
                                                            <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo $row['quantity']; ?></td>
                                                            <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-900">
                                                                <img width="100px" height="100px" src="../../include/<?php echo $row['image']; ?>" alt="Product Image" class="product-image" />
                                                            </td>
                                                            <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo $row['created_at']; ?></td>
                                                            <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-900">
                                                                <button onclick="toggleGroup('<?php echo $groupId; ?>', this.closest('tr'))"
                                                                    class="btn bg-primary/25 text-primary hover:bg-primary hover:text-white btn-sm rounded-full">
                                                                    <i class="mgc_arrow_down_2_line text-base me-2"></i> Expand
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>

                                                    <!-- Detail row -->
                                                    <tr class="group-<?php echo $groupId; ?> hidden group-row" style="background-color: <?php echo $color; ?>;">
                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo $row['id']; ?></td>
                                                        <td class="px-2 py-2.5 text-sm font-medium text-gray-900"><?php echo $shoesName; ?></td>
                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo $row['size']; ?></td>
                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo $row['price']; ?></td>
                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo $row['quantity']; ?></td>
                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-900">
                                                            <img width="100px" height="100px" src="../../include/<?php echo $row['image']; ?>" alt="Product Image" class="product-image" />
                                                        </td>
                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo $row['created_at']; ?></td>
                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-900">
                                                            <?php if ($deleteButtonVisible) : ?>
                                                                <a id="del-btn" href="api/remove.php?id=<?php echo $row['id']; ?>&from=shoes"
                                                                    class="btn bg-danger/25 text-danger hover:bg-danger hover:text-white btn-sm rounded-full">
                                                                    <i class="mgc_delete_2_line text-base me-2"></i> Delete
                                                                </a>
                                                            <?php endif; ?>
                                                            <?php if ($updateButtonVisible) : ?>
                                                                <a id="edit-btn" href="edit_shoes.php?id=<?php echo $row['id']; ?>"
                                                                    class="btn bg-warning/25 text-warning hover:bg-warning hover:text-white btn-sm rounded-full">
                                                                    <i class="mgc_edit_2_line text-base me-2"></i> Edit
                                                                </a>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
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


<script>
    // Function to toggle visibility of group rows
    function toggleGroup(groupId, currentRow) {
        // Get all rows with the matching group class (e.g., group-1)
        var elements = document.querySelectorAll('.group-' + groupId);

        // If already expanded, collapse them
        if (!elements[0].classList.contains('hidden')) {
            elements.forEach(function(element) {
                element.classList.add('hidden');
            });
        } else {
            // Collapse any other expanded rows
            document.querySelectorAll('.group-row').forEach(function(element) {
                element.classList.add('hidden');
            });

            // Ensure the current group's rows are expanded right below the current row
            elements.forEach(function(element) {
                element.classList.remove('hidden');
            });

            // Scroll into view for better UX (optional)
            currentRow.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    }
</script>


<script>
    $(document).ready(function() {
        // Destroy the existing DataTable instance, if it exists
        if ($.fn.DataTable.isDataTable('#zero_config')) {
            $('#zero_config').DataTable().destroy();
        }

        // Initialize the DataTable
        $('#zero_config').DataTable({
            // Your DataTable options
            paging: false, // Disable pagination
            order: [
               
            ], // Default ordering
        });
    });
</script>
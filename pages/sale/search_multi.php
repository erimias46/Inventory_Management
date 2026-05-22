<?php
$redirect_link = "../../";
$side_link = "../../";
include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php';
include_once $redirect_link . 'include/email.php';
include_once $redirect_link . 'include/bot.php';




?>








<head>
    <?php
    $title = 'Search Product';
    include $redirect_link . 'partials/title-meta.php'; ?>
    <link href="../../assets/libs/dropzone/min/dropzone.min.css" rel="stylesheet" type="text/css">


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
                <div class="grid grid-cols-1 gap-3">
                    <div class="card bg-white shadow-md rounded-md p-6 mx-lg max-w-lg">
                        <div class="p-6">
                            <h2 class="text-4xl font-bold text-white-700 text-center mb-10">Search By Size</h2>
                            <form method="post" enctype="multipart/form-data" class="grid grid-cols-3 gap-5">
                                <!-- Search By Option -->
                                <div class="mb-3">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2">Select Product Type:</label>
                                    <select id="product-type" name="product-type" class="form-input">
                                        <option value="">Select Product Type</option>
                                        <option value="jeans">Jeans</option>
                                        <option value="shoes">Shoes</option>
                                        <option value="top">Top</option>
                                        <option value="cosmetics">Cosmetics</option>
                                        <option value="accessory">Accessory</option>
                                    </select>
                                </div>

                                <!-- Sizes Container -->
                                <div id="sizes-container" class="mb-3 flex flex-wrap items-center">
                                    <label for="size" class="text-gray-800 text-sm font-medium inline-block mb-2">Select Size:</label>
                                    <div class="size-group flex">
                                        <select name="size[]" class="form-input size-dropdown">
                                            <option value="">Select Size</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Add Size Button -->
                                <div class="mb-3">
                                    <button type="button" id="add-size-btn" class="btn btn-sm bg-info text-white">Add Size</button>
                                </div>


                            </form>

                            <!-- Table for displaying product names and quantities based on selected sizes -->
                            <table id="resultTable" class="w-full mt-6">
                                <thead>
                                    <tr>
                                        <th>Product Name</th>
                                        <th>Size</th>

                                        <th>Image</th>
                                    </tr>
                                </thead>
                                <tbody id="size-table-body">
                                    <!-- The rows will be dynamically inserted here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>

            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script>
                $(document).ready(function() {
                    // Fetch sizes when product type is selected
                    $('#product-type').change(function() {
                        var productType = $(this).val();
                        if (productType) {
                            $.ajax({
                                url: 'api/searchapi/get_sizes.php',
                                type: 'POST',
                                data: {
                                    product_type: productType
                                },
                                success: function(response) {
                                    // Populate all size dropdowns with the available sizes
                                    $('.size-dropdown').html(response);
                                }
                            });
                        } else {
                            $('.size-dropdown').html('<option value="">Select Size</option>');
                        }
                    });

                    // Add new size dropdown when the "Add Size" button is clicked
                    $('#add-size-btn').click(function() {
                        var newSizeDropdown = `
            <div class="size-group flex items-center mb-3 mr-3 mx-2 my-1">
                <select name="size[]" class="form-input size-dropdown">
                    <option value="">Select Size</option>
                </select>
                <button type="button" class="remove-size-btn ml-2 mx-2 text-red-500">Remove</button>
            </div>`;
                        $('#sizes-container').append(newSizeDropdown);

                        // Populate the new dropdown with the current product type's sizes
                        var productType = $('#product-type').val();
                        if (productType) {
                            $.ajax({
                                url: 'api/searchapi/get_sizes.php',
                                type: 'POST',
                                data: {
                                    product_type: productType
                                },
                                success: function(response) {
                                    $('.size-dropdown').last().html(response);
                                }
                            });
                        }
                    });

                    // Remove size dropdown and update size selection
                    $(document).on('click', '.remove-size-btn', function() {
                        $(this).parent('.size-group').remove();
                        updateSelectedSizes();
                    });

                    // Fetch products based on selected sizes
                    $('#sizes-container').on('change', '.size-dropdown', function() {
                        updateSelectedSizes();
                    });

                    function updateSelectedSizes() {
                        var productType = $('#product-type').val();
                        var selectedSizes = $('select[name="size[]"]').map(function() {
                            return $(this).val();
                        }).get();

                        if (selectedSizes.length > 0) {
                            $.ajax({
                                url: 'api/searchapi/get_multi.php',
                                type: 'POST',
                                data: {
                                    product_type: productType,
                                    sizes: selectedSizes
                                },
                                success: function(response) {
                                    $('#size-table-body').html(response); // Populate table with products
                                }
                            });
                        } else {
                            $('#size-table-body').html('');
                        }
                    }
                });
            </script>







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



<style>
    .card {
        display: flex;
        justify-content: center;
        align-items: center;
    }



    #product-image {
        text-align: center;
        margin-bottom: 20px;
    }

    #size-table {
        width: 100%;
        border-collapse: collapse;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }

    #size-table th,
    #size-table td {
        padding: 12px;
        border-bottom: 2px solid #ddd;
        text-align: left;
    }

    #size-table th {

        font-size: 16px;

    }

    #size-table td {
        font-size: 14px;

    }
</style>


<style>
    .product-search {
        margin: 20px;
    }

    .product-search label {
        margin-right: 10px;
    }

    .product-search select {
        margin-bottom: 20px;
        padding: 5px;
    }

    #resultTable {
        width: 100%;
        border-collapse: collapse;
    }

    #resultTable th,
    #resultTable td {
        border: 1px solid black;
        padding: 8px;
        text-align: left;
    }
</style>
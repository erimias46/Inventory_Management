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

                                <div class="mb-3">
                                    <label for="size" class="text-gray-800 text-sm font-medium inline-block mb-2">Select Size:</label>
                                    <select id="size" name="size" class="form-input">
                                        <option value="">Select Size</option>
                                    </select>
                                </div>


                                <div class="mb-3">
                                   
                                    <a href="search.php" class="btn btn-sm bg-success text-white "> <i class="mgc_search_fill text-base me-2"></i> Search By Product Name </a>

                                </div>
                            </form>

                            <!-- Table for displaying product names and quantities based on selected size -->
                            <table id="resultTable">
                                <thead>
                                    <tr>
                                        <th>Product Name</th>
                                        <th>Size</th>
                                        <th>Quantity</th>
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
                    // When product type is selected, fetch the sizes
                    $('#product-type').change(function() {
                        var productType = $(this).val();

                        if (productType) {
                            // AJAX request to fetch sizes for the selected product type
                            $.ajax({
                                url: 'api/searchapi/get_sizes.php', // Backend PHP script to fetch sizes
                                type: 'POST',
                                data: {
                                    product_type: productType
                                },
                                success: function(response) {
                                    $('#size').html(response); // Populate size dropdown
                                }
                            });
                        } else {
                            $('#size').html('<option value="">Select Size</option>');
                        }
                    });

                    // When size is selected, fetch products with that size
                    $('#size').change(function() {
                        var size = $(this).val();
                        var productType = $('#product-type').val();

                        if (size) {
                            // AJAX request to fetch products based on the selected size
                            $.ajax({
                                url: 'api/searchapi/get_products.php', // Backend PHP script to fetch products
                                type: 'POST',
                                data: {
                                    product_type: productType,
                                    size: size
                                },
                                success: function(response) {
                                    $('#size-table-body').html(response); // Populate the table
                                }
                            });
                        } else {
                            $('#size-table-body').html('');
                        }
                    });
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
<?php
$redirect_link = "../../";
$side_link = "../../";
include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php';
include_once $redirect_link . 'include/email.php';
include_once $redirect_link . 'include/bot.php';


$current_date = date('Y-m-d');

$generate_button = '';

if (isset($_GET['import_brocher_id'])) {
    $brocher_type = $_GET['brocher_type'];



    $add_button = '<button name="add" type="submit" class="btn btn-sm bg-success text-white rounded-full"> <i class="mgc_add_fill text-base me-2"></i> Add </button>';
    $update_button = '<button name="update" type="submit" class="btn btn-sm bg-danger text-white rounded-full"> <i class="mgc_pencil_line text-base me-2"></i> Update </button>';
    $generate_button = '<button name="add_generate" type="submit" class="btn btn-sm bg-info text-white rounded-full"> <i class="mgc_pdf_line text-base me-2"></i> Generate </button>';
}

?>



<?php
if (isset($_POST['search'])) {
    $search_by = $_POST['search_by']; // 'name' or 'size'
    $result_table = ''; // This will hold the HTML for the results table

    if ($search_by == 'name' && isset($_POST['code_name'])) {
        // Split the table and name from the selection
        list($table, $code_name) = explode('|', $_POST['code_name']);

        // Query to fetch available sizes for the selected name
        $sql = "SELECT size FROM $table WHERE {$table}_name = '$code_name'";
        $result = mysqli_query($con, $sql);

        if (mysqli_num_rows($result) > 0) {
            // Build the result table
            $result_table .= '<table class="table">';
            $result_table .= '<thead><tr><th>Size</th></tr></thead><tbody>';

            while ($row = mysqli_fetch_assoc($result)) {
                $result_table .= '<tr><td>' . $row['size'] . '</td></tr>';
            }

            $result_table .= '</tbody></table>';
        } else {
            $result_table .= '<p>No sizes found for the selected product.</p>';
        }
    } elseif ($search_by == 'size' && isset($_POST['size_name'])) {
        $size_name = $_POST['size_name'];

        // Query to fetch products available in the selected size
        $sql = "SELECT {$table}_name FROM $table WHERE size = '$size_name'";
        $result = mysqli_query($con, $sql);

        if (mysqli_num_rows($result) > 0) {
            // Build the result table
            $result_table .= '<table class="table">';
            $result_table .= '<thead><tr><th>Product Name</th></tr></thead><tbody>';

            while ($row = mysqli_fetch_assoc($result)) {
                $result_table .= '<tr><td>' . $row["{$table}_name"] . '</td></tr>';
            }

            $result_table .= '</tbody></table>';
        } else {
            $result_table .= '<p>No products found for the selected size.</p>';
        }
    }

    echo $result_table;
}
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





        $add_button = ($module['salejeans'] == 1) ? true : false;
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

<head>
    <?php
    $title = 'Search Product';
    include $redirect_link . 'partials/title-meta.php'; ?>
    <link href="../../assets/libs/dropzone/min/dropzone.min.css" rel="stylesheet" type="text/css">


    <?php include $redirect_link . 'partials/head-css.php'; ?>


    <style>
        .image-preview {
            display: inline-block;
            margin-left: 20px;
        }

        .image-preview img {
            max-width: 150px;
            max-height: 150px;
        }


        /* Hide the default file input */
        .choose-image {
            display: none;
        }

        /* Style the custom file upload button */
        .custom-file-upload {
            position: relative;
            display: inline-block;
            cursor: pointer;
            background-color: #4A90E2;
            color: white;
            padding: 10px 20px;
            font-size: 14px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .custom-file-upload:hover {
            background-color: #244bad;
        }

        .custom-file-label {
            cursor: pointer;
            font-weight: bold;
        }
    </style>
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
                            <h2 class="text-4xl font-bold text-white-700 text-center mb-10">SEARCH Product</h2>
                            <form method="post" enctype="multipart/form-data" class="grid grid-cols-3 gap-5">
                                <!-- Jeans Name Field -->
                                <form method="post" enctype="multipart/form-data" class="grid grid-cols-2 gap-5">
                                    <!-- Search By Option -->
                                    <div class="mb-3">
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2" for="code_name">Select Product Type:</label>
                                        <!-- Product Type Dropdown -->

                                        <select id="product-type" name="product-type" onchange="loadProductNames()" class="form-input">
                                            <option value="">Select Product Type</option>
                                            <option value="Jeans">Jeans</option>
                                            <option value="Shoes">Shoes</option>
                                            <option value="Top">Top</option>
                                            <option value="Cosmetics">Cosmetics</option>
                                            <option value="Accessory">Accessory</option>
                                        </select>

                                    </div>

                                    <!-- Product Name Dropdown -->
                                    <div class="mb-3">
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2" for="code_name">Select Product Name:</label>
                                        <select id="product-name" name="product-name" onchange="loadSizes()" class="form-input">
                                            <option value="">Select Product Name</option>
                                        </select>

                                    </div>

                                    <!-- Size Dropdown -->

                                    <div class="mb-3">
                                        <label for="size">Select Size:</label>
                                        <select id="size" name="size" onchange="filterBySize()" class="form-input">
                                            <option value="">Select Size</option>
                                        </select>
                                    </div>
                                </form>









                                <!-- Price Field -->


                                <!-- Submit Button Section -->




                                <table border="1" id="resultTable">
                                    <thead>
                                        <tr>
                                            <th>Size</th>
                                            <th>Quantity</th>
                                        </tr>
                                    </thead>
                                    <tbody id="size-table-body">
                                        <!-- This will be populated dynamically -->
                                    </tbody>
                                </table>

                            </form>
                        </div>
                    </div>
                </div>
            </main>

            <style>
                .card {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                }
            </style>


            <?php include $redirect_link . 'partials/footer.php'; ?>

        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->

    </div>

    <?php include $redirect_link . 'partials/customizer.php'; ?>

    <?php include $redirect_link . 'partials/footer-scripts.php'; ?>



    



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



    <script>
        // Load product names based on the selected product type
        function loadProductNames() {
            var productType = document.getElementById("product-type").value;

            if (productType !== "") {
                fetch('api/searchapi/loadProductNames.php?productType=' + productType)
                    .then(response => response.json())
                    .then(data => {
                        let productDropdown = document.getElementById("product-name");
                        productDropdown.innerHTML = '<option value="">Select Product Name</option>';

                        data.productNames.forEach(product => {
                            let option = document.createElement('option');
                            option.value = product.name;
                            option.text = product.name;
                            productDropdown.appendChild(option);
                        });
                    });
            }
        }

        // Load sizes and quantities based on the selected product name
        function loadSizes() {
            var productType = document.getElementById("product-type").value;
            var productName = document.getElementById("product-name").value;

            if (productType !== "" && productName !== "") {
                fetch('api/searchapi/loadSizes.php?productType=' + encodeURIComponent(productType) + '&productName=' + encodeURIComponent(productName))
                    .then(response => response.json())
                    .then(data => {
                        let sizeTableBody = document.getElementById("size-table-body");
                        sizeTableBody.innerHTML = ''; // Clear existing rows

                        // Check if sizes data exists
                        if (data.sizes && data.sizes.length > 0) {
                            data.sizes.forEach(sizeData => {
                                let row = document.createElement('tr');

                                // Create size cell
                                let sizeCell = document.createElement('td');
                                sizeCell.textContent = sizeData.size;
                                row.appendChild(sizeCell);

                                // Create quantity cell
                                let quantityCell = document.createElement('td');
                                quantityCell.textContent = sizeData.quantity;
                                row.appendChild(quantityCell);

                                // Append the row to the table body
                                sizeTableBody.appendChild(row);
                            });
                        } else {
                            let row = document.createElement('tr');
                            let noDataCell = document.createElement('td');
                            noDataCell.colSpan = 2;
                            noDataCell.textContent = 'No sizes available';
                            row.appendChild(noDataCell);
                            sizeTableBody.appendChild(row);
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching sizes:', error);
                    });
            }
        }



        // Filter products by size and display them in the table
        function filterBySize() {
            var size = document.getElementById("size").value;
            var productId = document.getElementById("product-name").value;

            if (size !== "") {
                fetch('filterBySize.php?size=' + size + '&productId=' + productId)
                    .then(response => response.json())
                    .then(data => {
                        let tableBody = document.getElementById("resultTable").querySelector("tbody");
                        tableBody.innerHTML = ''; // Clear the table before adding new results

                        data.products.forEach(product => {
                            let row = document.createElement('tr');
                            row.innerHTML = `<td>${product.name}</td><td>${product.size}</td><td>${product.quantity}</td>`;
                            tableBody.appendChild(row);
                        });
                    });
            }
        }
    </script>



    <script>
        // JavaScript to automatically calculate the total price
        document.addEventListener('DOMContentLoaded', function() {
            const searchByName = document.getElementById('search_by_name');
            const searchBySize = document.getElementById('search_by_size');
            const nameSelectContainer = document.getElementById('name_select_container');
            const sizeSelectContainer = document.getElementById('size_select_container');

            searchByName.addEventListener('change', function() {
                if (this.checked) {
                    nameSelectContainer.style.display = 'block';
                    sizeSelectContainer.style.display = 'none';
                }
            });

            searchBySize.addEventListener('change', function() {
                if (this.checked) {
                    nameSelectContainer.style.display = 'none';
                    sizeSelectContainer.style.display = 'block';
                }
            });
        });
    </script>


    <script>
        function fetchSizes() {
            const codeNameSelect = document.getElementById('code_name').value;
            const [table, codeName] = codeNameSelect.split('|');

            if (table && codeName) {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'fetch_sizes.php', true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (this.status === 200) {
                        const sizes = JSON.parse(this.responseText);
                        const sizeSelect = document.getElementById('size_name');
                        sizeSelect.innerHTML = '<option value="">Select Size</option>';

                        sizes.forEach(size => {
                            const option = document.createElement('option');
                            option.value = size;
                            option.textContent = size;
                            sizeSelect.appendChild(option);
                        });
                    }
                };
                xhr.send('table=' + table + '&code_name=' + encodeURIComponent(codeName));
            }
        }
    </script>


    <script>
        function previewImage(event) {
            const imagePreview = document.getElementById('imagePreview');
            imagePreview.innerHTML = ''; // Clear previous image
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    imagePreview.appendChild(img);
                }
                reader.readAsDataURL(file);
            }
        }
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fetch suggestions for customer names from the server and populate the datalist
            fetch('getcust.php')
                .then(response => response.json())
                .then(data => {
                    const datalist = document.getElementById('customer_names');
                    datalist.innerHTML = ''; // Clear previous options
                    data.forEach(item => {
                        const option = document.createElement('option');
                        option.value = item; // Customer name
                        datalist.appendChild(option);
                    });
                });
        });
    </script>
</body>

</html>












<script>
    document.getElementById('jeans_types').addEventListener('focus', function() {
        // Fetch suggestions from the server and populate the datalist
        fetch('get_job_types.php?database=jeans')
            .then(response => response.json())
            .then(data => {
                const datalist = document.getElementById('jeans_types');
                datalist.innerHTML = ''; // Clear previous options
                data.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.job_type; // Adjust to match your database field
                    datalist.appendChild(option);
                });
            });
    });
</script>

<script src="../../assets/libs/dropzone/min/dropzone.min.js"></script>
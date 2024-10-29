<?php
$redirect_link = "../../";
$side_link = "../../";
include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php';
include_once $redirect_link . 'include/email.php';
include_once $redirect_link . 'include/bot.php';


$current_date = date('Y-m-d');



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


    <!-- Select2 CSS -->
    <!-- jQuery -->


    <!-- Select2 CSS -->



    <!-- Select2 CSS -->
    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <!-- Select2 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

    <!-- Select2 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>








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
                                            <option value="jeans">Jeans</option>
                                            <option value="shoes">Shoes</option>
                                            <option value="top">Top</option>
                                            <option value="cosmetics">Cosmetics</option>
                                            <option value="accessory">Accessory</option>
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
                                        <a href="search2.php" class="btn btn-sm bg-success text-white "> <i class="mgc_search_fill text-base me-2"></i> Search By Size </a>

                                    </div>
                                </form>









                                <!-- Price Field -->


                                <!-- Submit Button Section -->




                                <div id="product-image" style="margin-bottom: 10px;">
                                    <!-- The image will be inserted here dynamically -->
                                </div>

                                <!-- Table for displaying sizes and quantities -->
                                <table id="size-table">
                                    <thead>
                                        <tr>
                                            <th>Size</th>
                                            <th>Quantity</th>
                                        </tr>
                                    </thead>
                                    <tbody id="size-table-body">
                                        <!-- The rows will be dynamically inserted here -->
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
        let currentNiceSelect = null;

        function initializeNiceSelect() {
            const productDropdown = document.getElementById("product-name");

            // First destroy if there's an existing instance
            if (currentNiceSelect) {
                currentNiceSelect.destroy();
            }

            // Create new instance using bind
            currentNiceSelect = NiceSelect.bind(productDropdown, {
                searchable: true
            });
        }

        function loadProductNames() {
            const productType = document.getElementById("product-type").value;

            // Destroy existing NiceSelect before modifying options
            if (currentNiceSelect) {
                currentNiceSelect.destroy();
                currentNiceSelect = null;
            }

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

                        // Initialize Nice Select after loading new options
                        initializeNiceSelect();
                    })
                    .catch(error => {
                        console.error('Error loading product names:', error);
                    });
            } else {
                // Reset dropdown if no product type is selected
                let productDropdown = document.getElementById("product-name");
                productDropdown.innerHTML = '<option value="">Select Product Name</option>';
                initializeNiceSelect();
            }
        }

        // Initialize the first NiceSelect when the page loads
        document.addEventListener('DOMContentLoaded', function() {
            initializeNiceSelect();
        });

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
                            // Add the image before the size and quantity rows
                            if (data.image) {
                                let imageContainer = document.getElementById("product-image");
                                imageContainer.innerHTML = ''; // Clear previous image if any
                                let image = document.createElement('img');
                                image.src = '../../include/' + data.image; // Adjust this path based on your file structure

                                image.alt = productName + ' image';
                                image.style.width = '200px'; // Adjust the size as needed
                                imageContainer.appendChild(image);
                            }

                            // Populate the table with sizes and quantities
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
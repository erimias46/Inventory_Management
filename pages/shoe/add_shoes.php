<?php
$redirect_link = "../../";
$side_link = "../../";
include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php';
include_once $redirect_link . 'include/bot.php';
include_once $redirect_link . 'include/email.php';


$current_date = date('Y-m-d');



?>

<?php
if (isset($_POST['add'])) {

    // Collect form data
    $shoes_name = $_POST['shoes_name'];
    $size_ids = $_POST['size_ids']; // Array of size IDs
    $sizes = $_POST['sizes']; // Array of sizes
    $quantities = $_POST['quantities']; // Array of quantities
    $type_id = $_POST['type'];
    $price = $_POST['price'];

    $image = $_FILES['image']['name'];

    // Fetch type from the database
    $sql = "SELECT * FROM shoe_type_db WHERE id='$type_id'";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);
    $type = $row['type'];

    // Set the target directory for uploads
    $target_dir = $redirect_link . "include/uploads/";

    // Check if an image was uploaded
    if (!empty($image)) {
        $target_file = $target_dir . basename($image);
        $uploadOk = 1;

        // Validate if the file is an image
        $check = getimagesize($_FILES['image']['tmp_name']);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $uploadOk = 0;
            $error_message = "File is not an image.";
        }

        // Allow only specific file formats
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($imageFileType, $allowed_extensions)) {
            $uploadOk = 0;
            $error_message = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        }

        // Check file size (500 KB limit)
        if ($_FILES['image']['size'] > 500000) {
            $uploadOk = 0;
            $error_message = "Sorry, your file is too large.";
        }

        // Attempt to upload the file if no issues
        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                $image_path = 'uploads/' . basename($image);
            } else {
                $uploadOk = 0;
                $error_message = "Sorry, there was an error uploading your file.";
            }
        }
    } else {
        // If no image is uploaded, use the default image
        $image_path = 'uploads/defaultshoes.jpg';
        $uploadOk = 1;
    }

    // If image upload failed, use the default image
    if ($uploadOk == 0) {
        $image_path = 'uploads/defaultshoes.jpg';
    }

    // Loop through sizes and quantities to insert each size with quantity > 0
    for ($i = 0; $i < count($sizes); $i++) {
        $size = $sizes[$i];
        $size_id = $size_ids[$i];
        $quantity = $quantities[$i];

        // Insert only if the quantity is greater than zero
        if ($quantity > 0) {


            $check_existing = "SELECT id, quantity FROM shoes 
                      WHERE shoes_name = ? AND size = ? AND active = '1'";

            $stmt = mysqli_prepare($con, $check_existing);
            mysqli_stmt_bind_param($stmt, "ss", $shoes_name, $size);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) > 0) {
                // Item exists, update quantity
                $row = mysqli_fetch_assoc($result);
                $new_quantity = $row['quantity'] + $quantity;

                $update_query = "UPDATE shoes 
                        SET quantity = ? 
                        WHERE id = ?";

                $stmt = mysqli_prepare($con, $update_query);
                mysqli_stmt_bind_param($stmt, "ii", $new_quantity, $row['id']);
                mysqli_stmt_execute($stmt);


                $source_table = 'shoes';
                $product_type = 'shoes';




                $add_jeans_product = "INSERT INTO product(product_name, product_type, size, `type`, image, price, quantity, source_table) 
                      VALUES ('$shoes_name', '$product_type', '$size', '$type', '$image_path', '$price', '$quantity', '$source_table')";
                mysqli_query($con, $add_jeans_product);
            } else {

                $add_shoes = "INSERT INTO shoes(shoes_name, size, size_id, image, price,type_id, type, quantity,active) 
                          VALUES ('$shoes_name', '$size', '$size_id', '$image_path', '$price', '$type_id', '$type', '$quantity', '1')";
                mysqli_query($con, $add_shoes);
            }
        }
    }

    // Redirect after successful insertion

    if ($add_shoes || $update_query) {

        $message = "New shoes Added:\n";
        $message .= "shoes Name: $shoes_name\n";
        $message .= "Price: $price\n";
        $message .= "Type: $type\n";

        $message .= "Sizes and Quantities:\n";
        for ($i = 0; $i < count($sizes); $i++) {
            $size = $sizes[$i];
            $quantity = $quantities[$i];
            if ($quantity > 0) {
                $message .= "$size: $quantity\n";
            }
        }



        $subject = "New shoes Added";

        sendMessageToSubscribers($message, $con);
        sendEmailToSubscribers($message, $subject, $con);


        echo "<script>window.location = 'action.php?status=success&redirect=add_shoes.php';</script>";
    } else {
        echo "<script>window.location = 'action.php?status=error&message=Error adding shoes to the database.&redirect=add_shoes.php';</script>";
    }
}


?>

<?php


if (isset($_POST['update'])) {
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


        $addButtonVisible = ($module['addshoes'] == 1) ? true : false;
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
    $title = 'Add shoes';
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
                <div class="grid grid-cols-1 md:grid-cols-1 gap-3">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="text-slate-900 dark:text-slate-200 text-lg font-medium"><?= $title ?></h4>
                        </div>
                        <div class="p-6">

                            <form method="post" enctype="multipart/form-data" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">


                                <div class="relative mb-3">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2" for="shoes_name">shoes Name</label>
                                    <div class="relative">
                                        <input
                                            type="text"
                                            name="shoes_name"
                                            id="shoes_name"
                                            value="<?php if (isset($shoes_name)) echo $shoes_name ?>"
                                            class="form-input w-full"
                                            autocomplete="off"
                                            required
                                            oninput="filterOptions(this.value)"
                                            onblur="handleBlur()">
                                        <div id="dropdown" class="absolute z-10 w-full bg-white border border-gray-300 rounded-md mt-1 max-h-60 overflow-y-auto hidden">
                                            <?php
                                            $sql10 = "SELECT DISTINCT shoes_name FROM shoes";
                                            $result10 = $con->query($sql10);

                                            if ($result10->num_rows > 0) {
                                                while ($row10 = $result10->fetch_assoc()) {
                                                    echo "<div class='option px-4 py-2 hover:bg-gray-100 cursor-pointer' onclick='selectOption(this.innerText)'>" .
                                                        htmlspecialchars($row10['shoes_name']) .
                                                        "</div>";
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>


                                <script>
                                    function filterOptions(searchText) {
                                        const dropdown = document.getElementById('dropdown');
                                        const options = dropdown.getElementsByClassName('option');

                                        dropdown.classList.remove('hidden');

                                        for (let option of options) {
                                            const text = option.innerText.toLowerCase();
                                            const search = searchText.toLowerCase();

                                            if (text.includes(search)) {
                                                option.style.display = '';
                                            } else {
                                                option.style.display = 'none';
                                            }
                                        }
                                    }

                                    function selectOption(value) {
                                        document.getElementById('shoes_name').value = value;
                                        document.getElementById('dropdown').classList.add('hidden');
                                    }

                                    function handleBlur() {
                                        // Delay hiding dropdown to allow click events to register
                                        setTimeout(() => {
                                            document.getElementById('dropdown').classList.add('hidden');
                                        }, 200);
                                    }

                                    // Show dropdown when clicking input
                                    document.getElementById('shoes_name').addEventListener('click', function() {
                                        document.getElementById('dropdown').classList.remove('hidden');
                                        filterOptions(this.value);
                                    });
                                </script>

                                <style>
                                    .form-input {
                                        width: 100%;
                                        padding: 0.5rem;
                                        border: 1px solid #e2e8f0;
                                        border-radius: 0.375rem;
                                    }

                                    .form-input:focus {
                                        outline: none;
                                        border-color: #4f46e5;
                                        box-shadow: 0 0 0 1px #4f46e5;
                                    }
                                </style>




                                <div class="mb-3">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Type</label>

                                    <select name="type" class="search-select" required>

                                        <?php

                                        $sql = "SELECT * FROM shoe_type_db";
                                        $result = mysqli_query($con, $sql);
                                        while ($row = mysqli_fetch_assoc($result)) {
                                        ?>
                                            <option value="<?php echo $row['id'] ?>" <?php
                                                                                        if (isset($type)) {
                                                                                            if ($row['type'] == $type) {
                                                                                                echo "selected";
                                                                                            }
                                                                                        }
                                                                                        ?>>
                                                <?php echo $row['type']; ?>
                                            </option>
                                        <?php
                                        }
                                        ?>




                                    </select>

                                </div>




                                <div class="mb-3">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Price</label>
                                    <input type="number" min="0" value="0" step="0.01" name="price" class="form-input" required value="<?php if (isset($price)) echo  $price ?>">
                                </div>




                                <div class="mb-3">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2">Product Image</label>
                                    <div class="custom-file-upload">
                                        <label for="fileInput" class="custom-file-label">Choose Image</label>
                                        <input type="file" name="image" class="form-input  choose-image" id="fileInput" onchange="previewImage(event)">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="image-preview" id="imagePreview">
                                        <!-- The selected image will be displayed here -->
                                        <img src="../../include/uploads/defaultshoes.jpg" />
                                    </div>
                                </div>


                                <div class="mb-3">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2">shoes Sizes and Quantities</label>

                                    <?php
                                    // Fetch all sizes from the `shoesdb` table
                                    $sql = "SELECT * FROM shoesdb";
                                    $result = mysqli_query($con, $sql);
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $size = $row['size'];
                                    ?>
                                        <div class="flex items-center mb-2 justify-around">
                                            <!-- Size Label -->
                                            <label class="text-gray-800 text-sm font-medium flex-1"><?php echo $size; ?></label>

                                            <!-- Hidden input for size ID -->
                                            <input type="hidden" name="size_ids[]" value="<?php echo $row['id']; ?>">

                                            <!-- Hidden input for size value -->
                                            <input type="hidden" name="sizes[]" value="<?php echo $size; ?>">

                                            <!-- Quantity Input -->
                                            <input type="number" min="0" name="quantities[]" value="0" step="1" class="form-input flex-1 ml-4 border border-gray-300 p-2 rounded-md text-gray-800" placeholder="Quantity for size <?php echo $size; ?>">
                                        </div>
                                    <?php
                                    }
                                    ?>
                                </div>








                                <div class="col-span-1 sm:col-span-2 md:col-span-3 text-end">
                                    <div class="mt-3">


                                        <!-- Display the Calculate button if $calculateButtonVisible is true -->
                                        <?php if ($addButtonVisible) : ?>

                                            <button name="add" type="submit" class="btn btn-sm bg-success text-white rounded-full"> <i class="mgc_add_fill text-base me-2"></i> Add </button>

                                        <?php endif; ?>

                                        <!-- Display the Add button if $addButtonVisible is true -->

                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>




            </main>

            <?php include $redirect_link . 'partials/footer.php'; ?>

        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->

    </div>

    <?php include $redirect_link . 'partials/customizer.php'; ?>

    <?php include $redirect_link . 'partials/footer-scripts.php'; ?>


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
    document.getElementById('shoes_types').addEventListener('focus', function() {
        // Fetch suggestions from the server and populate the datalist
        fetch('get_job_types.php?database=shoes')
            .then(response => response.json())
            .then(data => {
                const datalist = document.getElementById('shoes_types');
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
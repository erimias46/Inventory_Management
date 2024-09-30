<?php
$redirect_link = "../../";
$side_link = "../../";
include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php';

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
if (isset($_POST['add'])) {

    // Collect form data
    $top_name = $_POST['top_name'];
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
        $image_path = 'uploads/defaulttop.jpg';
    }

    // If image upload failed, use the default image
    if ($uploadOk == 0) {
        $image_path = 'uploads/defaulttop.jpg';
    }

    // Loop through sizes and quantities to insert each size with quantity > 0
    for ($i = 0; $i < count($sizes); $i++) {
        $size = $sizes[$i];
        $size_id = $size_ids[$i];
        $quantity = $quantities[$i];

        // Insert only if the quantity is greater than zero
        if ($quantity > 0) {
            $add_top = "INSERT INTO top(top_name, size, size_id, image, price,type_id, type, quantity,active) 
                          VALUES ('$top_name', '$size', '$size_id', '$image_path', '$price', '$type_id', '$type', '$quantity', '1')";
            mysqli_query($con, $add_top);
        }
    }

    // Redirect after successful insertion

    if ($add_top) {
        echo "<script>window.location = 'action.php?status=success&redirect=add_top.php';</script>";
    } else {
        echo "<script>window.location = 'action.php?status=error&message=Error adding top to the database.&redirect=add_top.php';</script>";
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


        $calculateButtonVisible = ($module['calcview'] == 1) ? true : false;


        $addButtonVisible = ($module['calcadd'] == 1) ? true : false;


        $updateButtonVisible = ($module['calcedit'] == 1) ? true : false;


        $generateButtonVisible = ($module['calcgenerate'] == 1) ? true : false;
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
    $title = 'Add Top';
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


                                <div class="mb-3">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2" >Top Name</label>
                                    <input type="text" name="top_name" id="top_name"  class="form-input" list="jeans_types" required>
                                    <datalist id="jeans_types">
                                        <!-- Options will be populated here -->
                                    </datalist>
                                </div>




                                <div class="mb-3">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Type</label>

                                    <select name="type" class="search-select" required>

                                        <?php

                                        $sql = "SELECT * FROM top_type_db";
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
                                    <input type="number" step="0.0000001" name="price" class="form-input" required value="<?php if (isset($price)) echo  $price ?>">
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
                                        <img src="../../include/uploads/defaulttop.jpg" />
                                    </div>
                                </div>


                                <div class="mb-3">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2">Top Sizes and Quantities</label>

                                    <?php
                                  
                                    $sql = "SELECT * FROM topdb";
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
                                            <input type="number" name="quantities[]" value="0" step="1" class="form-input flex-1 ml-4 border border-gray-300 p-2 rounded-md text-gray-800" placeholder="Quantity for size <?php echo $size; ?>">
                                        </div>
                                    <?php
                                    }
                                    ?>
                                </div>








                                <div class="col-span-1 sm:col-span-2 md:col-span-3 text-end">
                                    <div class="mt-3">


                                        <!-- Display the Calculate button if $calculateButtonVisible is true -->
                                        <?php if ($calculateButtonVisible) : ?>

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
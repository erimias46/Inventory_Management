<?php
$redirect_link = "../../";
$side_link = "../../";
include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php';

    include_once $redirect_link . 'include/email.php';
    include_once $redirect_link . 'include/bot.php';
    $current_date = date('Y-m-d');

$generate_button = '';



if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM shoes WHERE id = $id";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);
    $shoes_name = $row['shoes_name'];
    $size = $row['size'];
    $type = $row['type'];
    $price = $row['price'];
    $quantity = $row['quantity'];
    $image = $row['image'];

    $image = "../../include/" . $image;
    $update_button = '<button name="update" type="submit" class="btn btn-sm bg-danger text-white rounded-full"> <i class="mgc_pencil_line text-base me-2"></i> Update </button>';
    $generate_button = '<button name="add_generate" type="submit" class="btn btn-sm bg-info text-white rounded-full"> <i class="mgc_pdf_line text-base me-2"></i> Generate </button>';
}


if (isset($_POST['update'])) {

    $shoes_name = $_POST['shoes_name'];

    $size = $_POST['size']; // Array of sizes
    $quantity = $_POST['quantity']; // Array of quantities
    $type_id = $_POST['type'];
    $price = $_POST['price'];

    $image = $_FILES['image']['name'];

    // Fetch the old image from the database if no new image is uploaded
    $id = $_GET['id']; // Assuming `shoes_id` is passed for identifying the record
    $sql = "SELECT image FROM shoes WHERE id='$id'";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);
    $old_image = $row['image'];  // The current image in the database

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
                $image_path = 'uploads/' . basename($image);  // Use the new image path
            } else {
                $uploadOk = 0;
                $error_message = "Sorry, there was an error uploading your file.";
            }
        }
    } else {
        // No new image uploaded, keep the old image
        $image_path = $old_image;
    }

    // If image upload failed, use the old image
    if ($uploadOk == 0 && empty($image_path)) {
        $image_path = $old_image;
    }

    // Update the shoes record with the new or old image
    $sql = "UPDATE shoes SET shoes_name='$shoes_name', size='$size', type='$type', price='$price', quantity='$quantity', image='$image_path' WHERE id='$id'";
    $result = mysqli_query($con, $sql);

    if ($result) {
        echo "<script>window.location = 'action.php?status=success&redirect=all_shoes.php';</script>";

        $message = " shoes Updated:\n";
        $message .= "shoes Name: $shoes_name\n";
        $message .= "Price: $price\n";
        $message .= "Type: $type\n";
        $message .= "Size: $size\n";
        $message .= "Quantity: $quantity\n";


        $subject = "shoes Updated";







        sendMessageToSubscribers($message, $con);
        sendEmailToSubscribers($message, $subject, $con);
    } else {
        echo "<script>window.location = 'action.php?status=error&message=Error updating shoes in the database.&redirect=all.php';</script>";
    }
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


        $updateButtonVisible = ($module['editshoes'] == 1) ? true : false;

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
    $title = 'Edit Shoes';
    include $redirect_link . 'partials/title-meta.php'; ?>
    <link href="../../assets/libs/dropzone/min/dropzone.min.css" rel="stylesheet" type="text/css">


    <?php include $redirect_link . 'partials/head-css.php'; ?>

    <script>
        function previewImage(event, index) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('imagePreview_' + index).getElementsByTagName('img')[0];
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>


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
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2">Shoes Name</label>
                                    <input type="text" name="shoes_name"  class="form-input" list="shoes_types" required  value="<?php echo $shoes_name ?>">
                                    <datalist id="shoes_types">
                                        <!-- Options will be populated here -->
                                    </datalist>
                                </div>
                                <div class="mb-3">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Size</label>

                                    <select name="size" class="search-select" required>

                                        <?php

                                        $sql = "SELECT * FROM shoesdb";
                                        $result = mysqli_query($con, $sql);
                                        while ($row = mysqli_fetch_assoc($result)) {
                                        ?>
                                            <option value="<?php echo $row['size'] ?>" <?php
                                                                                        if (isset($size)) {
                                                                                            if ($row['size'] == $size) {
                                                                                                echo "selected";
                                                                                            }
                                                                                        }
                                                                                        ?>>
                                                <?php echo $row['size']; ?>
                                            </option>
                                        <?php
                                        }
                                        ?>




                                    </select>

                                </div>



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
                                    <input type="number" step="0.0000001" name="price" class="form-input" required value="<?php if (isset($price)) echo  $price ?>">
                                </div>

                                <div class="mb-3">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Quantity</label>
                                    <input type="number" step="0.0000001" name="quantity" class="form-input" required value="<?php if (isset($quantity)) echo  $quantity ?>">
                                </div>


                                <div class="mb-3">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2">Product Image</label>
                                    <div class="custom-file-upload">
                                        <label for="fileInput" class="custom-file-label">Choose Image</label>
                                        <input type="file" name="image" class="form-input choose-image" id="fileInput" onchange="previewImage(event)">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="image-preview" id="imagePreview">
                                        <!-- The initial image will be displayed here -->
                                        <img id="previewImg" src="<?php echo !empty($image) ? $image : 'uploads/defaultshoes.jpg'; ?>" alt="Product Image" width="200" />
                                    </div>
                                </div>










                                <div class="col-span-1 sm:col-span-2 md:col-span-3 text-end">
                                    <div class="mt-3">


                                        <!-- Display the Calculate button if $calculateButtonVisible is true -->
                                        <?php if ($updateButtonVisible) : ?>

                                            <button name="update" type="submit" class="btn btn-sm bg-warning text-white rounded-full"> <i class="mgc_pencil_fill text-base me-2"></i> Update </button>

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
            const input = event.target;
            const preview = document.getElementById('previewImg');

            // If the user selects a new image, show the new image preview
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result; // Set the image source to the selected file
                };
                reader.readAsDataURL(input.files[0]); // Read the image as a data URL
            }
        }
    </script>





    
</body>

</html>













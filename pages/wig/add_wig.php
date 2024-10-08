<?php
$redirect_link = "../../";
$side_link = "../../";
include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php';
    include_once $redirect_link . 'include/bot.php';
    include_once $redirect_link . 'include/email.php';

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
    $wig_name = $_POST['wig_name'];
    $size_ids = $_POST['size_ids']; // Array of size IDs
    $sizes = $_POST['sizes']; // Array of sizes

    $quantities_1_piece = $_POST['quantities_1_piece']; // Array of 1-piece quantities
    $quantities_2_piece = $_POST['quantities_2_piece']; // Array of 2-piece quantities
    $quantities_3_piece = $_POST['quantities_3_piece']; // Array of 3-piece quantities

    $type_id = $_POST['type'];

    // Fetch type from the database
    $sql = "SELECT * FROM wig_type_db WHERE id='$type_id'";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);
    $type = $row['type'];

    // Set the target directory for uploads
    $target_dir = "include/uploads/";

    // Check if an image was uploaded
    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
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
        $image_path = 'uploads/defaultwig.jpg';
    }

    // If image upload failed, use the default image
    if ($uploadOk == 0) {
        $image_path = 'uploads/defaultwig.jpg';
    }

    // Loop through sizes and quantities to insert each size with quantity > 0
    for ($i = 0; $i < count($sizes); $i++) {
        $size = $sizes[$i];
        $size_id = $size_ids[$i];

        // Insert for 1-piece quantity
        if ($quantities_1_piece[$i] > 0) {
            $quantity = $quantities_1_piece[$i];
            $piece = 1;
            $total = $quantity * $piece;

            $add_wig = "INSERT INTO wig (wig_name, size, size_id, image, type_id, type, quantity, active, piece, total) 
                          VALUES ('$wig_name', '$size', '$size_id', '$image_path', '$type_id', '$type', '$quantity', '1', '$piece', '$total')";
            mysqli_query($con,
                $add_wig
            );
        }

        // Insert for 2-piece quantity
        if ($quantities_2_piece[$i] > 0) {
            $quantity = $quantities_2_piece[$i];
            $piece = 2;
            $total = $quantity * $piece;

            $add_wig = "INSERT INTO wig (wig_name, size, size_id, image, type_id, type, quantity, active, piece, total) 
                          VALUES ('$wig_name', '$size', '$size_id', '$image_path', '$type_id', '$type', '$quantity', '1', '$piece', '$total')";
            mysqli_query($con,
                $add_wig
            );
        }

        // Insert for 3-piece quantity
        if ($quantities_3_piece[$i] > 0) {
            $quantity = $quantities_3_piece[$i];
            $piece = 3;
            $total = $quantity * $piece;

            $add_wig = "INSERT INTO wig (wig_name, size, size_id, image, type_id, type, quantity, active, piece, total) 
                          VALUES ('$wig_name', '$size', '$size_id', '$image_path', '$type_id', '$type', '$quantity', '1', '$piece', '$total')";
            mysqli_query($con,
                $add_wig
            );
        }
    }

    // Redirect after successful insertion

    if ($add_wig) {





        $message = "New Wig Added:\n";
        $message .= "Wig Name: $wig_name\n";
        $message .= "Type: $type\n";
        $message .= "Sizes and Quantities:\n";

        // Loop through sizes and piece counts
        for ($i = 0; $i < count($sizes); $i++) {
            $size = $sizes[$i];

            // Add 1-piece quantity
            if ($quantities_1_piece[$i] > 0) {
                $message .= "Size $size (1-Piece): " . $quantities_1_piece[$i] . "\n";
            }

            // Add 2-piece quantity
            if ($quantities_2_piece[$i] > 0) {
                $message .= "Size $size (2-Piece): " . $quantities_2_piece[$i] . "\n";
            }

            // Add 3-piece quantity
            if ($quantities_3_piece[$i] > 0) {
                $message .= "Size $size (3-Piece): " . $quantities_3_piece[$i] . "\n";
            }
        }

        // Define the subject for the email
        $subject = "New Wig Added";

        // Send message to subscribers via Telegram and Email
        sendMessageToSubscribers($message, $con); // Sends to Telegram subscribers
        sendEmailToSubscribers($message, $subject, $con);


        echo "<script>window.location = 'action.php?status=success&redirect=add_wig.php';</script>";
    } else {
        echo "<script>window.location = 'action.php?status=error&message=Error adding wig to the database.&redirect=add_wig.php';</script>";
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


       


        $addButtonVisible = ($module['addwig'] == 1) ? true : false;


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
    $title = 'Add Wig';
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
                                <div class="mb-3 col-span-1">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2">Wig Name</label>
                                    <input type="text" name="wig_name" id="wig_name" class="form-input" list="jeans_types" required>
                                    <datalist id="jeans_types">
                                        <!-- Options will be populated here -->
                                    </datalist>
                                </div>

                                <div class="mb-3 col-span-1">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2">Type</label>
                                    <select name="type" class="search-select" required>
                                        <?php
                                        $sql = "SELECT * FROM wig_type_db";
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

                                <div class="mb-3 col-span-1 sm:col-span-2 md:col-span-3">
                                    <label class="text-black-800 text-sm font-medium inline-block mb-2">Wig Sizes and Quantities</label>
                                    <table class="min-w-full border border-grey-300 mt-4">
                                        <thead>
                                            <tr class="bg-gray-100">
                                                <th class="text-left p-2 text-sm text-gray-800 border-b">Size</th>
                                                <th class="text-left p-2 text-sm text-gray-800 border-b">1 Piece Quantity</th>
                                                <th class="text-left p-2 text-sm text-gray-800 border-b">2 Piece Quantity</th>
                                                <th class="text-left p-2 text-sm text-gray-800 border-b">3 Piece Quantity</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sql = "SELECT * FROM wigdb";
                                            $result = mysqli_query($con, $sql);
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                $size = $row['size'];
                                            ?>
                                                <tr class="border-b">
                                                    <td class="p-2 text-sm text-black-800">
                                                        <?php echo $size; ?>
                                                        <input type="hidden" name="size_ids[]" value="<?php echo $row['id']; ?>">
                                                        <input type="hidden" name="sizes[]" value="<?php echo $size; ?>">
                                                    </td>
                                                    <td class="p-2">
                                                        <input type="number" name="quantities_1_piece[]" value="0" step="1" class="form-input w-full border border-gray-300 p-1 rounded-md text-gray-800" placeholder="Quantity">
                                                    </td>
                                                    <td class="p-2">
                                                        <input type="number" name="quantities_2_piece[]" value="0" step="1" class="form-input w-full border border-gray-300 p-1 rounded-md text-gray-800" placeholder="Quantity">
                                                    </td>
                                                    <td class="p-2">
                                                        <input type="number" name="quantities_3_piece[]" value="0" step="1" class="form-input w-full border border-gray-300 p-1 rounded-md text-gray-800" placeholder="Quantity">
                                                    </td>
                                                </tr>
                                            <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>

                                

                                <div class="col-span-1 sm:col-span-2 md:col-span-3 text-end">

                                <?php 
                                if($addButtonVisible){
                                    ?>
                                    <div class="mt-3">
                                        <button name="add" type="submit" class="btn btn-sm bg-success text-white rounded-full">Add</button>
                                    </div>

                                    <?php
                                }
                                ?>
                                
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


    


    
</body>

</html>















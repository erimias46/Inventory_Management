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






if (isset($_POST['set_entries'])) {
    $num_entries = $_POST['num_entries'];

    // Fetch size options from the jeansdb
    $sizeOptions = '';
    $sql = "SELECT * FROM jeansdb";
    $result = mysqli_query($con, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $sizeOptions .= '<option value="' . $row['id'] . '">' . $row['size'] . '</option>';
    }

    // Fetch type options from the trouser_type_db
    $typeOptions = '';
    $sql = "SELECT * FROM trouser_type_db";
    $result = mysqli_query($con, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $typeOptions .= '<option value="' . $row['id'] . '">' . $row['type'] . '</option>';
    }

    echo '<form method="post" enctype="multipart/form-data">';
    echo '<table class="min-w-full border-collapse">';
    echo '<thead>';
    echo '<tr>';
    echo '<th class="border p-2">Jeans Name</th>';
    echo '<th class="border p-2">Size</th>';
    echo '<th class="border p-2">Type</th>';
    echo '<th class="border p-2">Price</th>';
    echo '<th class="border p-2">Quantity</th>';
    echo '<th class="border p-2">Private</th>';
    echo '<th class="border p-2">Product Image</th>';
    echo '<th class="border p-2">Image Preview</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    for ($i = 1; $i <= $num_entries; $i++) {
        echo '<tr>';
        echo '<td class="border p-2"><input type="text" name="jeans_name_' . $i . '" class="form-input" required></td>';
        echo '<td class="border p-2"><select name="size_' . $i . '" class="search-select" required>' . $sizeOptions . '</select></td>';
        echo '<td class="border p-2"><select name="type_' . $i . '" class="search-select" required>' . $typeOptions . '</select></td>';
        echo '<td class="border p-2"><input type="number" step="0.0000001" name="price_' . $i . '" class="form-input" required></td>';
        echo '<td class="border p-2"><input type="number" step="0.0000001" name="quantity_' . $i . '" class="form-input" required></td>';
        echo '<td class="border p-2"><select name="private_' . $i . '" class="form-input" required>';
        echo '<option value="choose">Select</option>';
        echo '<option value="yes">Yes</option>';
        echo '<option value="no">No</option>';
        echo '</select></td>';

        // File input for product image
        echo '<td class="border p-2">';
        echo '<label for="fileInput_' . $i . '" class="custom-file-label">Choose Image</label>';
        echo '<input type="file" name="image_' . $i . '" class="form-input choose-image" id="fileInput_' . $i . '" onchange="previewImage(event, ' . $i . ')">';
        echo '</td>';

        // Image preview section
        echo '<td class="border p-2">';
        echo '<div class="image-preview" id="imagePreview_' . $i . '">';
        echo '<img src="../../include/uploads/defaultjeans.jpg" />';
        echo '</div>';
        echo '</td>';

        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';
    echo '<button type="submit" name="add_entries" class="btn btn-sm bg-success text-white rounded-full mt-4">Add Entries</button>';
    echo '</form>';
}





if (isset($_POST['add_entries'])) {
$num_entries = $_POST['num_entries'];

for ($i = 1; $i <= $num_entries; $i++) {
    $jeans_name=$_POST['jeans_name_' . $i];
    $size_id=$_POST['size_' . $i];
    $type_id=$_POST['type_' . $i];
    $price=$_POST['price_' . $i];
    $quantity=$_POST['quantity_' . $i];
    $private=$_POST['private_' . $i];
    $image=$_FILES['image_' . $i]['name'];

    // Fetch size from the database
    $sql="SELECT * FROM jeansdb WHERE id='$size_id'" ;
    $result=mysqli_query($con, $sql);
    $row=mysqli_fetch_assoc($result);
    $size=$row['size'];

    // Fetch type from the database
    $sql="SELECT * FROM trouser_type_db WHERE id='$type_id'" ;
    $result=mysqli_query($con, $sql);
    $row=mysqli_fetch_assoc($result);
    $type=$row['type'];

    // Set the target directory for uploads
    $target_dir=$redirect_link . "include/uploads/" ;

    // Check if an image was uploaded
    if (!empty($image)) {
    $target_file=$target_dir . basename($image);
    $uploadOk=1;

    // Validate if the file is an image
    $check=getimagesize($_FILES['image_' . $i]['tmp_name']);
    if ($check !==false) {
    $uploadOk=1;
    } else {
    $uploadOk=0;
    $error_message="File is not an image." ;
    }

    // Allow only specific file formats
    $imageFileType=strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $allowed_extensions=['jpg', 'jpeg' , 'png' , 'gif' ];
    if (!in_array($imageFileType, $allowed_extensions)) {
    $uploadOk=0;
    $error_message="Sorry, only JPG, JPEG, PNG & GIF files are allowed." ;
    }

    // Check file size
    if ($_FILES['image_' . $i]['size']> 500000) { // 500 KB
    $uploadOk = 0;
    $error_message = "Sorry, your file is too large.";
    }

    // Attempt to upload the file if no issues
    if ($uploadOk == 1) {
    if (move_uploaded_file($_FILES['image_' . $i]['tmp_name'], $target_file)) {
    $image_path = 'uploads/' . basename($image);
    } else {
    $uploadOk = 0;
    $error_message = "Sorry, there was an error uploading your file.";
    }
    }
    } else {
    // If no image is uploaded, use the default image
    $image_path = 'uploads/defaultjeans.jpg';
    }

    // If image upload failed, use the default image
    if ($uploadOk == 0) {
    $image_path = 'uploads/defaultjeans.jpg';
    }

    // Insert the jeans data into the database
    $add_jeans = "INSERT INTO jeans(jeans_name, size, size_id, image, price, private, type_id, type, quantity)
    VALUES ('$jeans_name', '$size', '$size_id', '$image_path', '$price', '$private', '$type_id', '$type', '$quantity')";
    $result_add = mysqli_query($con, $add_jeans);

    // Check if the insert was successful
    if (!$result_add) {
    $status = isset($error_message) ? $error_message : "Error adding jeans to the database for entry $i.";
    echo "<script>
        window.location = 'action.php?status=error&message=$status&redirect=add_multiple_jeans.php';
    </script>";
  //  exit; // Stop further processing if there's an error
    }
    }

    // // If all inserts are successful, redirect with success
    // echo "<script>
    //     window.location = 'action.php?status=success&redirect=add_multiple_jeans.php';
    // </script>";
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
        $title = 'Add Jeans';
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


                            <form method="post" action="">
                                <label for="num_entries">How many entries would you like to make?</label>
                                <input type="number" name="num_entries" id="num_entries" min="1" required class="form-input">
                                <button type="submit" name="set_entries">Set Entries</button>
                            </form>

                            <div class="p-6">

                                <form method="post" enctype="multipart/form-data" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">


                                    <div class="mb-3">
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2" for="Jeans names">Jeans Name</label>
                                        <input type="text" name="jeans_name" id="jeans_name" value="<?php if (isset($jeans_name)) echo  $jeans_name ?>" class="form-input" list="jeans_types" required>
                                        <datalist id="jeans_types">
                                            <!-- Options will be populated here -->
                                        </datalist>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Size</label>

                                        <select name="size" class="search-select" required>

                                            <?php

                                            $sql = "SELECT * FROM jeansdb";
                                            $result = mysqli_query($con, $sql);
                                            while ($row = mysqli_fetch_assoc($result)) {
                                            ?>
                                                <option value="<?php echo $row['id'] ?>" <?php
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

                                            $sql = "SELECT * FROM trouser_type_db";
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
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Private</label>


                                        <Select name="private" class="form-input" required>

                                            <option value="choose">Select</option>
                                            <option value="yes" <?php if (isset($private) && $private == 'yes') echo 'selected' ?>>Yes
                                            </option>
                                            <option value="no" <?php if (isset($private) && $private == 'no') echo 'selected' ?>>No</option>
                                        </Select>


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
                                            <img src="../../include/uploads/defaultjeans.jpg" />
                                        </div>
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
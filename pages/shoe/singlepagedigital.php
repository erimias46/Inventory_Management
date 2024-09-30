<?php
$redirect_link = "../../";
$side_link = "../../";
include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php';
include_once $redirect_link . 'include/mdb.php';

$current_date = date('Y-m-d');

$total_price = 0;
$total_price_vat = 0;
$unitbanner_price  = 0;
$commitonpricevat = 0;
$care = 0;
$total_care = 0;
$machine_run = 0;

$add_button = '';
$update_button = '';
$generate_button = '';

if (isset($_GET['import_banner_id'])) {
    $banner_type = $_GET['banner_type'];
    $sql = "SELECT * FROM single_page WHERE single_page_id = '$banner_type'";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);
    $customer = $row['customer'];
    $job_type = $row['job_type'];
    $commitonprice = $row['commitonprice'];
    $size = $row['size'];
    $vat = $row['vat'];
    $cvat = $row['cvat'];
    $required_quantity = $row['required_quantity'];
    $total_price = $row['total_price'];
    $unitbanner_type = $row['unit_price'];
    $width = $row['width'];
    $private = $row['private'];
    $machine_type = $row['machine_type'];

    $print_side = $row['print_side'];

    $page_id = $row['page_id'];





    $total_price_vat = $row['total_price_vat'];
    $sql = "SELECT * FROM pagedb WHERE page_id = '$page_id'";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);
    $unitbanner_type = $row['page_id'];
    $unitbanner_price = $row['page_price'];

    $machine_run = $print_side * $required_quantity;


    $commitonpricevat = $commitonprice + (($vat / 100) * $commitonprice) + (($cvat / 100) * $commitonprice);

    $total_care = $unitbanner_price + $width;



    $add_button = '<button name="add" type="submit" class="btn btn-sm bg-success text-white rounded-full"> <i class="mgc_add_fill text-base me-2"></i> Add </button>';
    $update_button = '<button name="update" type="submit" class="btn btn-sm bg-danger text-white rounded-full"> <i class="mgc_pencil_line text-base me-2"></i> Update </button>';
    $generate_button = '<button name="add_generate" type="submit" class="btn btn-sm bg-info text-white rounded-full"> <i class="mgc_pdf_line text-base me-2"></i> Generate </button>';
}

if (isset($_POST['calculate'])) {
    $customer = $_POST['customer'];
    $job_type = $_POST['job_type'];

    $unitbanner_type = $_POST['unitbanner_type'];
    $required_quantity = $_POST['required_quantity'];
    $commitonprice = $_POST['commitonprice'];
    $vat = $_POST['vat'];
    $cvat = $_POST['cvat'];
    $width = $_POST['width'];
    $private = $_POST['private'];
    $machine_type = $_POST['machine_type'];
    $size = $_POST['size'];

    $sql = "SELECT * FROM pagedb WHERE page_id = '$unitbanner_type'";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);
    $unitbanner_price = $row['page_price'];
    $print_side = $_POST['print_side'];

    $machine_run = $print_side * $required_quantity;

    $total_care = $unitbanner_price + $width;
    $commitonpricevat = $commitonprice + (($vat / 100) * $commitonprice) + (($cvat / 100) * $commitonprice);
    $cvat_amount = $commitonprice * ($cvat / 100) / (1 + $vat / 100); // Adjusting $cvat_amount to neutralize VAT
    $total_price = $commitonprice + $cvat_amount + ($total_care * $required_quantity * $print_side);
    $total_price_vat = $total_price + $total_price * ($vat / 100);



    $commitonpricevat = $commitonprice + (($vat / 100) * $commitonprice) + (($cvat / 100) * $commitonprice);

    if (isset($_GET['import_banner_id'])) {
        $add_button = '<button name="add" type="submit" class="btn btn-sm bg-success text-white rounded-full"> <i class="mgc_add_fill text-base me-2"></i> Add </button>';
        $update_button = '<button name="update" type="submit" class="btn btn-sm bg-danger text-white rounded-full"> <i class="mgc_pencil_line text-base me-2"></i> Update </button>';
        $generate_button = '<button name="add_generate" type="submit" class="btn btn-sm bg-info text-white rounded-full"> <i class="mgc_pdf_line text-base me-2"></i> Generate </button>';
    } else {
        $add_button = '<button name="add" type="submit" class="btn btn-sm bg-success text-white rounded-full"> <i class="mgc_add_fill text-base me-2"></i> Add </button>';
        $generate_button = '<button name="add_generate" type="submit" class="btn btn-sm bg-info text-white rounded-full"> <i class="mgc_pdf_line text-base me-2"></i> Generate </button>';
        $update_button = '';
    }
}

if (isset($_POST['add'])) {
    $customer = $_POST['customer'];
    $job_type = $_POST['job_type'];

    $unitbanner_type = $_POST['unitbanner_type'];
    $required_quantity = $_POST['required_quantity'];
    $commitonprice = $_POST['commitonprice'];
    $vat = $_POST['vat'];
    $cvat = $_POST['cvat'];
    $width = $_POST['width'];
    $private = $_POST['private'];
    $machine_type = $_POST['machine_type'];
    $print_side = $_POST['print_side'];
    $size = $_POST['size'];

    $sql = "SELECT * FROM pagedb WHERE page_id = '$unitbanner_type'";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);
    $unitbanner_price = $row['page_price'];
    $unitbanner_type = $row['page_id'];

    $total_care = $unitbanner_price + $width;
    $commitonpricevat = $commitonprice + (($vat / 100) * $commitonprice) + (($cvat / 100) * $commitonprice);
    $cvat_amount = $commitonprice * ($cvat / 100) / (1 + $vat / 100); // Adjusting $cvat_amount to neutralize VAT
    $total_price = $commitonprice + $cvat_amount + ($total_care * $required_quantity * $print_side);
    $total_price_vat = $total_price + $total_price * ($vat / 100);


    $machine_run = $print_side * $required_quantity;

    $unit_pricee = $total_price / $required_quantity;


    $add_banner = "INSERT INTO single_page(customer, job_type, size, required_quantity, total_price, unit_price, total_price_vat, vat,cvat, width, commitonprice, date,private, machine_run,print_side,page_id,machine_type) 
        VALUES ('$customer', '$job_type', '$size', '$required_quantity', '$total_price', '$unit_pricee', '$total_price_vat', '$vat','$cvat', '$width', '$commitonprice', '$current_date','$private', '$machine_run','$print_side','$unitbanner_type','$machine_type')";
    $result_add = mysqli_query($con, $add_banner);
    $add_recent_order = "INSERT INTO recent_order(customer, total_price, date,type) 
    VALUES ('$customer','$total_price_vat','$current_date','single_page')";

    $recent_order = mysqli_query($con, $add_recent_order);

    if ($result_add) {
        echo "<script>window.location = 'action.php?status=success&redirect=singlepagedigital.php'; </script>";
    } else {
        echo "<script>window.location = 'action.php?status=error&redirect=singlepagedigital.php'; </script>";
    }
}

if (isset($_POST['update'])) {
    $banner_id = $_GET['banner_type'];
    $customer = $_POST['customer'];
    $job_type = $_POST['job_type'];

    $unitbanner_type = $_POST['unitbanner_type'];
    $required_quantity = $_POST['required_quantity'];
    $commitonprice = $_POST['commitonprice'];
    $vat = $_POST['vat'];
    $cvat = $_POST['cvat'];
    $width = $_POST['width'];
    $private = $_POST['private'];
    $machine_type = $_POST['machine_type'];
    $size = $_POST['size'];

    $print_side = $_POST['print_side'];

    $sql = "SELECT * FROM pagedb WHERE page_id = '$unitbanner_type'";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);
    $unitbanner_price = $row['page_price'];
    $unitbanner_type = $row['page_id'];

    $total_care = $unitbanner_price + $width;
    $commitonpricevat = $commitonprice + (($vat / 100) * $commitonprice) + (($cvat / 100) * $commitonprice);
    $cvat_amount = $commitonprice * ($cvat / 100) / (1 + $vat / 100); // Adjusting $cvat_amount to neutralize VAT
    $total_price = $commitonprice + $cvat_amount + ($total_care * $required_quantity * $print_side);
    $total_price_vat = $total_price + $total_price * ($vat / 100);

    $machine_run = $print_side * $required_quantity;

    $unit_pricee = $total_price / $required_quantity;

    $banner_update = "UPDATE `single_page` SET 
                customer = '$customer',
                job_type = '$job_type', 
                size = '$size',
                required_quantity = '$required_quantity', 
                total_price = '$total_price', 
                unit_price = '$unit_pricee', 
                total_price_vat = '$total_price_vat',
                vat = '$vat', 
                cvat = '$cvat', 
                width  = '$width',
                machine_run='$machine_run',
                print_side='$print_side',
                commitonprice = '$commitonprice',
                date = '$current_date',
                page_id='$unitbanner_type',
                machine_type='$machine_type',
                private = '$private'
                WHERE single_page_id = '$banner_id'";
    $result_banner = mysqli_query($con, $banner_update);

    if ($result_banner) {
        echo "<script>window.location = 'action.php?status=success&redirect=singlepagedigital.php'; </script>";
    } else {
        echo "<script>window.location = 'action.php?status=error&redirect=singlepagedigital.php'; </script>";
    }
}

if (isset($_POST['add_generate'])) {
    $customer = $_POST['customer'];
    $job_type = $_POST['job_type'];
    $commitonprice = $_POST['commitonprice'];
    $size = $_POST['size'];
    $required_quantity = $_POST['required_quantity'];
    $vat = $_POST['vat'];
    $cvat = $_POST['cvat'];
    $width = $_POST['width'];
    //$length = $_POST['length'];
    $unitbanner_type = $_POST['unitbanner_type'];

    $print_side = $_POST['print_side'];
    $type = 'Single Page Digital';

    $sql = "SELECT * FROM pagedb WHERE page_id = '$unitbanner_type'";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);
    $unitbanner_price = $row['page_price'];


    $machine_run = $print_side * $required_quantity;


    $addnum = $commitonprice / $required_quantity;

    $total_care = $unitbanner_price + $width;
    $commitonpricevat = $commitonprice + (($vat / 100) * $commitonprice) + (($cvat / 100) * $commitonprice);
    $cvat_amount = $commitonprice * ($cvat / 100) / (1 + $vat / 100); // Adjusting $cvat_amount to neutralize VAT
    $total_price = $commitonprice + $cvat_amount + $total_care * $required_quantity;
    $total_price_vat = $total_price + $total_price * ($vat / 100);

    $unit_pricee = $total_price / $required_quantity;

    $generate_banner = "INSERT INTO generate(customer,job_description,size,quantity,total_price ,unit_price,price_vat ,types) 
        VALUES ('$customer', '$job_type', '$size', '$required_quantity', '$total_price', '$unit_pricee' , '$total_price_vat','$type')";
    $result_generate = mysqli_query($con, $generate_banner);




    $generate_banner = "INSERT INTO performa_log(customer,job_description,size,quantity,total_price ,unit_price,price_vat ,types) 
        VALUES ('$customer', '$job_type', '$size', '$required_quantity', '$total_price', '$unit_pricee' , '$total_price_vat','$type')";
    $result_generate = mysqli_query($con, $generate_banner);



    if ($result_generate) {
        echo "<script>window.location = 'action.php?status=success&redirect=singlepagedigital.php'; </script>";
    } else {
        echo "<script>window.location = 'action.php?status=error&redirect=singlepagedigital.php'; </script>";
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
    $title = 'Single Page Digital';
    include $redirect_link . 'partials/title-meta.php'; ?>

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
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="text-slate-900 dark:text-slate-200 text-lg font-medium">Single Page Digital</h4>
                        </div>
                        <div class="p-6">
                            <form method="get">
                                <div class="mb-3 flex rounded-full shadow-sm">
                                    <select name="banner_type" class="form-input  rounded-none">
                                        <?php

                                        if ($_SESSION['username'] == 'masteradmin') {
                                            $sql = "SELECT * FROM single_page ORDER BY single_page_id DESC";
                                        } else {
                                            $sql = "SELECT * FROM single_page WHERE private != 'yes' ORDER BY single_page_id DESC";
                                        }

                                        $result = mysqli_query($con, $sql);
                                        while ($row = mysqli_fetch_assoc($result)) {
                                        ?>
                                            <option value="<?php echo $row['single_page_id'] ?>" <?php if (isset($banner_type) && $row['single_page_id'] == $banner_type) {
                                                                                                        echo "selected";
                                                                                                    } ?>>
                                                <?php echo $row['single_page_id'] ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>

                                    <?php if ($calculateButtonVisible) : ?>
                                        <button name="import_banner_id" type="submit" class="btn btn-sm bg-danger text-white rounded-full rounded-s-none">
                                            Import
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </form>
                            <form method="post" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                                <div class="mb-3">
                                    <div class="flex items-center justify-between">
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">Customer</label>
                                    </div>
                                    <div class="flex items-center space-x-1"> <!-- Added flex container for select and button -->
                                        <select type="text" name="customer" class="search-select" required>
                                            <?php
                                            $sql = "SELECT * FROM customer ORDER BY customer_id DESC";
                                            $result = mysqli_query($con, $sql);
                                            while ($row = mysqli_fetch_assoc($result)) {
                                            ?>
                                                <option value="<?php echo $row['customer_name'] ?>" <?php
                                                                                                    if (isset($customer)) {
                                                                                                        if ($row['customer_name'] == $customer) {
                                                                                                            echo "selected";
                                                                                                        }
                                                                                                    }
                                                                                                    ?>>
                                                    <?php echo $row['customer_name']; ?>
                                                </option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                        <button type="button" data-fc-type="modal" data-fc-target="addModal" class="btn btn-sm rounded-full bg-success/25 text-success hover:bg-success hover:text-white">
                                            +
                                        </button>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2" for="job_type">Job Type</label>
                                    <input type="text" name="job_type" id="job_type" value="<?php if (isset($job_type)) echo  $job_type ?>" class="form-input" list="job_types" required>
                                    <datalist id="job_types">
                                        <!-- Options will be populated here -->
                                    </datalist>
                                </div>

                                <div class="mb-3">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Size</label>
                                    <input type="text" name="size" value="<?php if (isset($size)) echo $size ?>" class="form-input" required>
                                </div>
                                <div class="mb-3">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Page
                                        Type</label>
                                    <select type="text" name="unitbanner_type" class="form-input" required>
                                        <?php
                                        $sql = "SELECT * FROM pagedb";
                                        $result = mysqli_query($con, $sql);
                                        while ($row = mysqli_fetch_assoc($result)) {
                                        ?>
                                            <option value="<?php echo $row['page_id'] ?>" <?php
                                                                                            if (isset($unitbanner_type)) {
                                                                                                if ($row['page_id'] == $unitbanner_type) {
                                                                                                    echo "selected";
                                                                                                }
                                                                                            }
                                                                                            ?>>
                                                <?php echo $row['page_type']; ?>
                                            </option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Required
                                        Quantity</label>
                                    <input type="text" name="required_quantity" value="<?php if (isset($required_quantity)) echo $required_quantity ?>" class="form-input" required>
                                </div>
                                <div class="mb-3">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Folding
                                        Price</label>
                                    <input type="text" name="width" value="<?php if (isset($width)) echo $width ?>" class="form-input" required>
                                </div>

                                <div class="mb-3">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Print Side
                                    </label>
                                    <input type="text" name="print_side" value="<?php if (isset($print_side)) echo $print_side ?>" class="form-input" required>
                                </div>

                                <div class="mb-3">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2"> VAT(<i>in
                                            %</i>)</label>
                                    <input type="text" name="vat" value="<?php if (isset($vat)) echo $vat ?>" class="form-input" required>
                                </div>
                                <div class="mb-3">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Commition
                                        Price</label>
                                    <input type="text" name="commitonprice" value="<?php if (isset($commitonprice)) echo  $commitonprice ?>" class="form-input" required>
                                </div>
                                <div class="mb-3">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Commotion Profit
                                        Tax(<i>in %</i>)</label>
                                    <input type="text" name="cvat" value="<?php if (isset($cvat)) echo $cvat ?>" class="form-input" required>
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
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Machine Type</label>


                                    <Select name="machine_type" class="form-input" required>


                                        <?php $sql = "SELECT * FROM machine_run";
                                        $result = mysqli_query($con, $sql);
                                        while ($row = mysqli_fetch_assoc($result)) {
                                        ?>
                                            <option value="<?php echo $row['id'] ?>" <?php
                                                                                        if (isset($machine_type) && $row['id'] == $machine_type) {
                                                                                            echo "selected";
                                                                                        }
                                                                                        ?>>
                                                <?php echo $row['type'] ?>
                                            </option>
                                        <?php } ?>


                                    </Select>


                                </div>


                                <div class="col-span-1 sm:col-span-2 md:col-span-3 text-end">
                                    <div class="mt-3">
                                        <?php if ($calculateButtonVisible) : ?>
                                            <button name="calculate" type="submit" class="btn btn-sm bg-warning text-white rounded-full">
                                                <i class="mgc_add_fill text-base me-2"></i>
                                                Calculate
                                            </button>


                                            <a href="../database/database.php?type=single_page" class="btn btn-sm bg-info text-white rounded-full">
                                                <i class="mgc_pdf_line text-base me-2"></i>
                                                Database
                                            </a>



                                        <?php endif; ?>

                                        <?= ($addButtonVisible) ? $add_button : '' ?>

                                        <!-- Display the Update button if $updateButtonVisible is true -->
                                        <?= ($updateButtonVisible) ? $update_button : '' ?>

                                        <!-- Display the Generate button if $generateButtonVisible is true -->
                                        <?= ($generateButtonVisible) ? $generate_button : '' ?>

                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h4 class="text-slate-900 dark:text-slate-200 text-lg font-medium">Result</h4>
                        </div>
                        <form method="post" class="p-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">

                            <div class="mb-3">
                                <label class="text-gray-800 text-sm font-medium inline-block mb-2">Machine Run</label>
                                <input type="text" value="<?= $machine_run ?>" class="form-input" disabled>
                            </div>
                            <div class="mb-3">
                                <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Price Before Vat</label>
                                <input type="text" value="<?= $total_price ?>" class="form-input" disabled>
                            </div>
                            <div class="mb-3">
                                <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Unit Price Before
                                    VAT</label>
                                <input type="text" value="<?php if (isset($required_quantity)) echo  $total_price / $required_quantity ?>" class="form-input" disabled>
                            </div>

                            <div class="mb-3">
                                <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Total Price
                                    VAT</label>
                                <input type="text" value="<?= $total_price_vat ?>" class="form-input" disabled>
                            </div>
                            <div class="mb-3">
                                <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Unit Price With VAT
                                </label>
                                <input type="text" value="<?php if (isset($required_quantity)) echo $total_price_vat / $required_quantity ?>" class="form-input" disabled>
                            </div>
                            <div class="mb-3">
                                <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Commition Price
                                    VAT</label>
                                <input type="text" value="<?= $commitonpricevat ?>" class="form-input" disabled>
                            </div>
                            <div class="mb-3">
                                <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Page Price</label>
                                <input type="text" value="<?= $total_care ?>" class="form-input" disabled>
                            </div>



                        </form>
                    </div>
                </div>
                <div id="addModal" class="w-full h-full fixed top-0 left-0 z-50 transition-all duration-500 hidden">
                    <div class="fc-modal-open:mt-7 fc-modal-open:opacity-100 fc-modal-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto bg-white border shadow-sm rounded-md dark:bg-slate-800 dark:border-gray-700">
                        <div class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
                            <h3 class="font-medium text-gray-800 dark:text-white text-lg">Add Customers</h3>
                            <button class="inline-flex flex-shrink-0 justify-center items-center h-8 w-8 dark:text-gray-200" data-fc-dismiss type="button">
                                <span class="material-symbols-rounded">close</span>
                            </button>
                        </div>
                        <form method="POST">
                            <div class="px-4 py-8 overflow-y-auto">
                                <div class="mb-3">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2">Name</label>
                                    <input list="customer_names" name="name" class="form-input" required>
                                    <datalist id="customer_names"></datalist>
                                </div>
                                <div class="mb-3">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2">Tin Number</label>
                                    <input type="text" name="tin_number" class="form-input" required>
                                </div>
                                <div class="mb-3">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2">Phone Number</label>
                                    <input type="text" name="phone_number" class="form-input" required>
                                </div>

                                <div class="mb-3">
                                    <label for="type" class="text-gray-800 text-sm font-medium inline-block mb-2">Type</label>
                                    <select name="mtype" id="type" class="form-input" required>
                                        <option value="">Select Type</option>
                                        <option value="organization">Organization</option>
                                        <option value="person">Person</option>
                                    </select>
                                </div>

                                


                            </div>
                            <div class="flex justify-end items-center gap-4 p-4 border-t dark:border-slate-700">
                                <button class="py-2 px-5 inline-flex justify-center items-center gap-2 rounded dark:text-gray-200 border dark:border-slate-700 font-medium hover:bg-slate-100 hover:dark:bg-slate-700 transition-all" data-fc-dismiss type="button">Close</button>
                                <button name="add_customer" type="submit" class="py-2.5 px-4 inline-flex justify-center items-center gap-2 rounded bg-success hover:bg-success-600 text-white">Add Customer</button>
                            </div>
                        </form>
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
    var select_box_element = document.querySelector('#customers');
    dselect(select_box_element, {
        search: true
    });
</script>


<?php

if (isset($_POST['add_customer'])) {
    $name = $_POST['name'];
    $tin_number = $_POST['tin_number'];
    $phone_number = $_POST['phone_number'];
    $type = $_POST['mtype'];
    $address = $_POST['address'];
    $state = $_POST['state'];
    $city = $_POST['city'];
    $date = date('Y-m-d');
    $owner_id = $_POST['owner_id'];


    $check_duplicate = "SELECT * FROM customer WHERE customer_name = '$name'";
    $result_check = mysqli_query($con, $check_duplicate);

    if (mysqli_num_rows($result_check) > 0) {
        // Customer name already exists, show an alert message
        echo "<script>alert('There already exists a customer by that name.'); window.location = 'singlepagedigital.php'; </script>";
    } else {
        // Proceed with adding the new customer
        $add_customer_oli = "INSERT INTO oli_clients(company_name, vat_number, phone, type, address, state, city, created_date,owner_id) 
                         VALUES ('$name', '$tin_number', '$phone_number', '$type', '$address', '$state', '$city', '$date', '$owner_id')";

        $result_add_oli = mysqli_query($conn, $add_customer_oli);

        if ($result_add_oli) {
            // Retrieve the last inserted ID from oli_clients
            $management_id = mysqli_insert_id($conn);

            // Insert into customer table using the retrieved management_id
            $add_customer_cust = "INSERT INTO customer(customer_name, tin_number, phone_number, management_id) 
                              VALUES ('$name', '$tin_number', '$phone_number', '$management_id')";

            $result_add_cust = mysqli_query($con, $add_customer_cust);

            if ($result_add_cust) {
                echo "<script>window.location = 'action.php?status=success&redirect=singlepagedigital.php'; </script>";
            } else {
                echo "<script>window.location = 'action.php?status=error&redirect=singlepagedigital.php'; </script>";
            }
        } else {
            echo "<script>window.location = 'action.php?status=error&redirect=singlepagedigital.php'; </script>";
        }
    }
}

?>




<script>
    document.getElementById('job_type').addEventListener('focus', function() {
        // Fetch suggestions from the server and populate the datalist
        fetch('get_job_types.php?database=single_page')
            .then(response => response.json())
            .then(data => {
                const datalist = document.getElementById('job_types');
                datalist.innerHTML = ''; // Clear previous options
                data.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.job_type; // Adjust to match your database field
                    datalist.appendChild(option);
                });
            });
    });
</script>
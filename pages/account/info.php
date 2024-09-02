<?php
$redirect_link = "../../";
$side_link = "../../";

include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php';
?>

<head>
    <?php
    $title = 'Information';
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

            <main class="flex-grow p-6 flex justify-center items-center">
                <div class="card flex-grow" style="max-width: 420px">
                    <div class="card-header">
                        <h4 class="text-slate-900 dark:text-slate-200 text-lg font-medium">Information</h4>
                    </div>
                    <?php
                   
                    $sql = "SELECT * FROM info WHERE id = 1";
                    $result = mysqli_query($con, $sql);
                    $row = mysqli_fetch_assoc($result);
                    ?>
                    <form method="POST" class="p-6 grid grid-cols-1 gap-3">
                        <!-- <div class="mx-auto">
                            <img src="../../assets/images/users/1.jpg" class="rounded-full" style="width:200px;" alt="">
                        </div> -->
                        <div>
                            <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Address </label>
                            <input type="text" name="address" class="form-input" value="<?php echo $row['address']; ?>" required>
                        </div>
                        <div>
                            <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Phone Number </label>
                            <input type="text" name="phone_number" class="form-input" value="<?php echo $row['phone_number']; ?>" required>
                        </div>
                        <div>
                            <label class="text-gray-800 text-sm font-medium inline-block mb-2"> VAT </label>
                            <input type="text" name="vat" class="form-input" value="<?php echo $row['vat']; ?>" required>
                        </div>

                        <div>
                            <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Tin Number </label>
                            <input type="text" name="tin_number" class="form-input" value="<?php echo $row['tin_number']; ?>" required>
                        </div>

                        <div>
                            <label class="text-gray-800 text-sm font-medium inline-block mb-2"> VAT registertion Number </label>
                            <input type="text" name="vat_number" class="form-input" value="<?php echo $row['vat_number']; ?>" required>
                        </div>
                       
                        <button name="update_profile" type="submit" class="btn bg-success text-white">Update</button>
                    </form>
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

<?php

if (isset($status)) {
    $status = $_GET['status'];
    if ($status == "success") {
?>
        <script>
            swal("Great!", "Task Done", "success");
        </script>
    <?php
    } else {
    ?>
        <script>
            swal("Opps!", "Have an error please contact admin", "error");
        </script>
<?php
    }
}

if (isset($_POST['update_profile'])) {

    $address = $_POST['address'];
    $phone_number = $_POST['phone_number'];
    $vat = $_POST['vat'];
    $tin_number = $_POST['tin_number'];
    $vat_number = $_POST['vat_number'];


    $profile_update = "UPDATE `info` SET `address`='$address', `phone_number`='$phone_number', `vat`='$vat', `tin_number`='$tin_number', `vat_number`='$vat_number' WHERE `id` = 1";
    $result_update = mysqli_query($con, $profile_update);

    if ($result_update) {
        echo "<script>window.location = 'action.php?status=success&redirect=info.php'; </script>";
    } else {
        echo "<script>window.location = 'action.php?status=error&redirect=info.php'; </script>";
    }
    

    
}

?>
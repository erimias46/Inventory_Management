<?php
include $redirect_link . 'include/db.php';
// require_once($redirect_link . 'pages/backup/backup.php');


// $back = new Backup;

// if (isset($_POST['backup'])) {
//     $message = $back->backup_tables();
//     echo "<script>alert('" . $message . "')</script>";
// }
// if (isset($_POST['backuplc'])) {
//     $message = $back->backup_tableslc();
//     echo "<script>alert('" . $message . "')</script>";
// }


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


        $calcview = ($module['calcview'] == 1) ? true : false;
        $constview = ($module['constview'] == 1) ? true : false;
        $payview = ($module['payview'] == 1) ? true : false;
        $bankview = ($module['bankview'] == 1) ? true : false;
        $vatview = ($module['vatview'] == 1) ? true : false;
        $banksview = ($module['banksview'] == 1) ? true : false;
        $stockview = ($module['stockview'] == 1) ? true : false;
        $dataview = ($module['dataview'] == 1) ? true : false;
        $jobview = ($module['jobview'] == 1) ? true : false;
        $saleview = ($module['saleview'] == 1) ? true : false;
        $reportview = ($module['reportview'] == 1) ? true : false;
        $userview = ($module['userview'] == 1) ? true : false;
        $fileview = ($module['fileview'] == 1) ? true : false;
        $custview = ($module['custview'] == 1) ? true : false;
        $profileview = ($module['profileview'] == 1) ? true : false;
        $backview = ($module['backview'] == 1) ? true : false;
        $generateview = ($module['generateview'] == 1) ? true : false;
        $brocherview = ($module['brocherview'] == 1) ? true : false;
        $bookview = ($module['bookview'] == 1) ? true : false;
        $digitalview = ($module['digitalview'] == 1) ? true : false;
        $manualview = ($module['manualview'] == 1) ? true : false;
        $bannerview = ($module['bannerview'] == 1) ? true : false;
        $designview = ($module['designview'] == 1) ? true : false;
        $singlepagedigitalview = ($module['singlepageview'] == 1) ? true : false;
        $multipagedigitalview = ($module['multipageview'] == 1) ? true : false;
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

<div class="app-menu">

    <!-- Sidenav Brand Logo -->
    <a href=" <?php echo $redirect_link ?>index.php" class="logo-box">
        <!-- Light Brand Logo -->
        <div class="logo-light">
            <img src="<?php echo $redirect_link ?>assets/images/logo2.png" class="logo-lg h-10" alt="Light logo">
            <img src="<?php echo $redirect_link ?>assets/images/logo2.png" class="logo-sm" alt="Small logo">
        </div>

        <!-- Dark Brand Logo -->
        <div class="logo-dark">
            <img src="<?php echo $redirect_link ?>assets/images/logo2.png" class="logo-lg h-6" alt="Dark logo">
            <img src="<?php echo $redirect_link ?>assets/images/logo2.png" class="logo-sm" alt="Small logo">
        </div>
    </a>

    <!-- Sidenav Menu Toggle Button -->
    <button id="button-hover-toggle" class="absolute top-5 end-2 rounded-full p-1.5">
        <span class="sr-only">Menu Toggle Button</span>
        <i class="mgc_round_line text-xl"></i>
    </button>

    <!--- Menu -->
    <div class="srcollbar" data-simplebar>
        <ul class="menu" data-fc-type="accordion">
            <li class="menu-title">Menu</li>






            <li class="menu-item">
                <a href="<?php echo $redirect_link ?>index.php" class="menu-link">
                    <span class="menu-icon"><i class="mgc_home_3_line"></i></span>
                    <span class="menu-text"> Dashboard </span>
                </a>
            </li>


            <?php if ($calcview) : ?>


                <li class="menu-item">
                    <a href="javascript:void(0)" data-fc-type="collapse" class="menu-link">
                        <span class="menu-icon"><i class="msr">calculate</i></span>
                        <span class="menu-text"> Jeans </span>
                        <span class="menu-arrow"></span>
                    </a>

                    <ul class="sub-menu hidden">

                        <?php if ($brocherview) : ?>

                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/price_calculator/add_single_jeans.php" class="menu-link">
                                    <span class="menu-text">Jeans</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($bookview) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/price_calculator/all_jeans.php" class="menu-link">
                                    <span class="menu-text">All Jeans</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if ($bookview) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/price_calculator/type_jeans.php" class="menu-link">
                                    <span class="menu-text">Type Jeans</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($bookview) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/price_calculator/sale_jeans.php" class="menu-link">
                                    <span class="menu-text">Sale Jeans</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($bookview) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/price_calculator/jeans_stock_log.php" class="menu-link">
                                    <span class="menu-text">Jeans Stock Log</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($bookview) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/price_calculator/verify.php" class="menu-link">
                                    <span class="menu-text">Verify</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if ($bookview) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/price_calculator/exchange.php" class="menu-link">
                                    <span class="menu-text">Exchange Log</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($bookview) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/price_calculator/refund.php" class="menu-link">
                                    <span class="menu-text">Refund Log</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if ($bookview) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/price_calculator/delivery.php" class="menu-link">
                                    <span class="menu-text">Delivery</span>
                                </a>
                            </li>
                        <?php endif; ?>



                        <!-- <?php if ($bookview) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/price_calculator/bag.php" class="menu-link">
                                    <span class="menu-text">Bag</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($manualview) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/price_calculator/digital.php" class="menu-link">
                                    <span class="menu-text">Manual</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($digitalview) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/price_calculator/otherdigital.php" class="menu-link">
                                    <span class="menu-text">Digital</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($bannerview) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/price_calculator/banner.php" class="menu-link">
                                    <span class="menu-text">Banner</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($designview) : ?>

                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/price_calculator/design.php" class="menu-link">
                                    <span class="menu-text">Design</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($singlepagedigitalview) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/price_calculator/singlepagedigital.php" class="menu-link">
                                    <span class="menu-text">Single Page Digital</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($multipagedigitalview) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/price_calculator/multipage.php" class="menu-link">
                                    <span class="menu-text">Multi Page Digital</span>
                                </a>
                            </li>
                        <?php endif; ?> -->
                    </ul>
                </li>
            <?php endif; ?>


            <?php if ($constview) : ?>


                <li class="menu-item">
                    <a href="javascript:void(0)" data-fc-type="collapse" class="menu-link">
                        <span class="menu-icon"><i class="msr">bookmark</i></span>
                        <span class="menu-text"> Constants </span>
                        <span class="menu-arrow"></span>
                    </a>

                    <ul class="sub-menu hidden">




                        <?php
                        $constants = mysqli_query($con, "SELECT * FROM d_constants");
                        while ($constant = $constants->fetch_assoc()) {
                        ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/constants/constant.php?id=<?php echo $constant['id'] ?>" class="menu-link">
                                    <span class="menu-text"><?php echo $constant['name'] ?></span>
                                </a>
                            </li>
                        <?php
                        }
                        ?>
                    </ul>
                </li>

            <?php endif; ?>


            
            <?php if ($backview) : ?>
                <li class="menu-item">
                    <a href="<?php echo $redirect_link ?>newbackup.php" class="menu-link">
                        <span class="menu-icon"><i class="mgc_pdf_line"></i></span>
                        <span class="menu-text">BackUp</span>
                    </a>
                </li>
            <?php endif; ?>













        </ul>
    </div>
</div>
<!-- Sidenav Menu End  -->
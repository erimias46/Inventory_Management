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




                    </ul>
                </li>
            <?php endif; ?>


            <?php if ($calcview) : ?>


                <li class="menu-item">
                    <a href="javascript:void(0)" data-fc-type="collapse" class="menu-link">
                        <span class="menu-icon"><i class="msr">calculate</i></span>
                        <span class="menu-text"> Shoes </span>
                        <span class="menu-arrow"></span>
                    </a>

                    <ul class="sub-menu hidden">

                        <?php if ($brocherview) : ?>

                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/shoe/add_shoes.php" class="menu-link">
                                    <span class="menu-text">Add Shoes</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($bookview) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/shoe/all_shoes.php" class="menu-link">
                                    <span class="menu-text">All Shoes</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if ($bookview) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/shoe/type_shoes.php" class="menu-link">
                                    <span class="menu-text">Type Shoes</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($bookview) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/shoe/sale_shoes.php" class="menu-link">
                                    <span class="menu-text">Sale Shoes</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($bookview) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/shoe/shoes_stock_log.php" class="menu-link">
                                    <span class="menu-text">Shoes Stock Log</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($bookview) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/shoe/verify.php" class="menu-link">
                                    <span class="menu-text">Verify</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if ($bookview) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/shoe/exchange.php" class="menu-link">
                                    <span class="menu-text">Exchange Log</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($bookview) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/shoe/refund.php" class="menu-link">
                                    <span class="menu-text">Refund Log</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if ($bookview) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/shoe/delivery.php" class="menu-link">
                                    <span class="menu-text">Delivery</span>
                                </a>
                            </li>
                        <?php endif; ?>




                    </ul>
                </li>
            <?php endif; ?>


            <?php if ($calcview) : ?>


                <li class="menu-item">
                    <a href="javascript:void(0)" data-fc-type="collapse" class="menu-link">
                        <span class="menu-icon"><i class="msr">calculate</i></span>
                        <span class="menu-text"> Top </span>
                        <span class="menu-arrow"></span>
                    </a>

                    <ul class="sub-menu hidden">

                        <?php if ($brocherview) : ?>

                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/top/add_top.php" class="menu-link">
                                    <span class="menu-text">Add Top</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($bookview) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/top/all_top.php" class="menu-link">
                                    <span class="menu-text">All Top</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if ($bookview) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/top/type_top.php" class="menu-link">
                                    <span class="menu-text">Type Top</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($bookview) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/top/sale_top.php" class="menu-link">
                                    <span class="menu-text">Sale Top</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($bookview) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/top/top_stock_log.php" class="menu-link">
                                    <span class="menu-text">Top Stock Log</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($bookview) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/top/verify.php" class="menu-link">
                                    <span class="menu-text">Verify</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if ($bookview) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/top/exchange.php" class="menu-link">
                                    <span class="menu-text">Exchange Log</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($bookview) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/top/refund.php" class="menu-link">
                                    <span class="menu-text">Refund Log</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if ($bookview) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/top/delivery.php" class="menu-link">
                                    <span class="menu-text">Delivery</span>
                                </a>
                            </li>
                        <?php endif; ?>




                    </ul>
                </li>
            <?php endif; ?>



            <?php if ($calcview) : ?>


                <li class="menu-item">
                    <a href="javascript:void(0)" data-fc-type="collapse" class="menu-link">
                        <span class="menu-icon"><i class="msr">calculate</i></span>
                        <span class="menu-text"> Complete </span>
                        <span class="menu-arrow"></span>
                    </a>

                    <ul class="sub-menu hidden">

                        <?php if ($brocherview) : ?>

                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/complete/add_complete.php" class="menu-link">
                                    <span class="menu-text">Add Complete</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($bookview) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/complete/all_complete.php" class="menu-link">
                                    <span class="menu-text">All Complete</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if ($bookview) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/complete/type_complete.php" class="menu-link">
                                    <span class="menu-text">Type Complete</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($bookview) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/complete/sale_complete.php" class="menu-link">
                                    <span class="menu-text">Sale complete</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($bookview) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/complete/complete_stock_log.php" class="menu-link">
                                    <span class="menu-text">complete Stock Log</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($bookview) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/complete/verify.php" class="menu-link">
                                    <span class="menu-text">Verify</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if ($bookview) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/complete/exchange.php" class="menu-link">
                                    <span class="menu-text">Exchange Log</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($bookview) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/complete/refund.php" class="menu-link">
                                    <span class="menu-text">Refund Log</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if ($bookview) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/complete/delivery.php" class="menu-link">
                                    <span class="menu-text">Delivery</span>
                                </a>
                            </li>
                        <?php endif; ?>




                    </ul>
                </li>
            <?php endif; ?>


            <?php if ($calcview) : ?>


                <li class="menu-item">
                    <a href="javascript:void(0)" data-fc-type="collapse" class="menu-link">
                        <span class="menu-icon"><i class="msr">calculate</i></span>
                        <span class="menu-text"> Accessory </span>
                        <span class="menu-arrow"></span>
                    </a>

                    <ul class="sub-menu hidden">

                        <?php if ($brocherview) : ?>

                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/accessory/add_accessory.php" class="menu-link">
                                    <span class="menu-text">Add accessory</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($bookview) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/accessory/all_accessory.php" class="menu-link">
                                    <span class="menu-text">All accessory</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if ($bookview) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/accessory/type_accessory.php" class="menu-link">
                                    <span class="menu-text">Type accessory</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($bookview) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/accessory/sale_accessory.php" class="menu-link">
                                    <span class="menu-text">Sale accessory</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($bookview) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/accessory/accessory_stock_log.php" class="menu-link">
                                    <span class="menu-text">Accessory Stock Log</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($bookview) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/accessory/verify.php" class="menu-link">
                                    <span class="menu-text">Verify</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if ($bookview) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/accessory/exchange.php" class="menu-link">
                                    <span class="menu-text">Exchange Log</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($bookview) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/accessory/refund.php" class="menu-link">
                                    <span class="menu-text">Refund Log</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if ($bookview) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/accessory/delivery.php" class="menu-link">
                                    <span class="menu-text">Delivery</span>
                                </a>
                            </li>
                        <?php endif; ?>




                    </ul>
                </li>
            <?php endif; ?>



            <?php if ($calcview) : ?>


                <li class="menu-item">
                    <a href="javascript:void(0)" data-fc-type="collapse" class="menu-link">
                        <span class="menu-icon"><i class="msr">calculate</i></span>
                        <span class="menu-text"> Wig </span>
                        <span class="menu-arrow"></span>
                    </a>

                    <ul class="sub-menu hidden">

                        <?php if ($brocherview) : ?>

                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/wig/add_wig.php" class="menu-link">
                                    <span class="menu-text">Add wig</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($bookview) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/wig/all_wig.php" class="menu-link">
                                    <span class="menu-text">All Wig</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if ($bookview) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/wig/type_wig.php" class="menu-link">
                                    <span class="menu-text">Type accessory</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($bookview) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/wig/sale_wig.php" class="menu-link">
                                    <span class="menu-text">Sale Wig</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($bookview) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/wig/wig_stock_log.php" class="menu-link">
                                    <span class="menu-text">Wig Stock Log</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($bookview) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/wig/verify.php" class="menu-link">
                                    <span class="menu-text">Verify</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if ($bookview) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/wig/exchange.php" class="menu-link">
                                    <span class="menu-text">Exchange Log</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($bookview) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/wig/refund.php" class="menu-link">
                                    <span class="menu-text">Refund Log</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if ($bookview) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/wig/delivery.php" class="menu-link">
                                    <span class="menu-text">Delivery</span>
                                </a>
                            </li>
                        <?php endif; ?>




                    </ul>
                </li>
            <?php endif; ?>


            <?php if ($calcview) : ?>


                <li class="menu-item">
                    <a href="javascript:void(0)" data-fc-type="collapse" class="menu-link">
                        <span class="menu-icon"><i class="msr">calculate</i></span>
                        <span class="menu-text"> SALE </span>
                        <span class="menu-arrow"></span>
                    </a>

                    <ul class="sub-menu hidden">


                        <?php if ($bookview) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/sale/sale.php" class="menu-link">
                                    <span class="menu-text">Sale</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if ($bookview) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/sale/all_sales.php" class="menu-link">
                                    <span class="menu-text">All Sales</span>
                                </a>
                            </li>
                        <?php endif; ?>





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
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


        $viewjeans = ($module['viewjeans'] == 1) ? true : false;
        $viewshoes = ($module['viewshoes'] == 1) ? true : false;
        $viewtop = ($module['viewtop'] == 1) ? true : false;
        $viewcomplete = ($module['viewcomplete'] == 1) ? true : false;
        $viewaccessory = ($module['viewaccessory'] == 1) ? true : false;
        $viewwig = ($module['viewwig'] == 1) ? true : false;
        $viewcosmetics = ($module['viewcosmetics'] == 1) ? true : false;


        $addjeans = ($module['addjeans'] == 1) ? true : false;
        $addshoes = ($module['addshoes'] == 1) ? true : false;
        $addtop = ($module['addtop'] == 1) ? true : false;
        $addcomplete = ($module['addcomplete'] == 1) ? true : false;
        $addaccessory = ($module['addaccessory'] == 1) ? true : false;
        $addwig = ($module['addwig'] == 1) ? true : false;
        $addcosmetics = ($module['addcosmetics'] == 1) ? true : false;

        $salejeans = ($module['salejeans'] == 1) ? true : false;
        $saleshoes = ($module['saleshoes'] == 1) ? true : false;
        $saletop = ($module['saletop'] == 1) ? true : false;
        $salecomplete = ($module['salecomplete'] == 1) ? true : false;
        $saleaccessory = ($module['saleaccessory'] == 1) ? true : false;
        $salewig = ($module['salewig'] == 1) ? true : false;
        $salecosmetics = ($module['salecosmetics'] == 1) ? true : false;

        $logjeans = ($module['logjeans'] == 1) ? true : false;
        $logshoes = ($module['logshoes'] == 1) ? true : false;
        $logtop = ($module['logtop'] == 1) ? true : false;
        $logcomplete = ($module['logcomplete'] == 1) ? true : false;
        $logaccessory = ($module['logaccessory'] == 1) ? true : false;
        $logwig = ($module['logwig'] == 1) ? true : false;
        $logcosmetics = ($module['logcosmetics'] == 1) ? true : false;

        $verifyjeans = ($module['verifyjeans'] == 1) ? true : false;
        $verifyshoes = ($module['verifyshoes'] == 1) ? true : false;
        $verifytop = ($module['verifytop'] == 1) ? true : false;
        $verifycomplete = ($module['verifycomplete'] == 1) ? true : false;
        $verifyaccessory = ($module['verifyaccessory'] == 1) ? true : false;
        $verifywig = ($module['verifywig'] == 1) ? true : false;
        $verifycosmetics = ($module['verifycosmetics'] == 1) ? true : false;

        $deliverysalejeans = ($module['deliverysalejeans'] == 1) ? true : false;
        $deliverysaleshoes = ($module['deliverysaleshoes'] == 1) ? true : false;
        $deliverysaletop = ($module['deliverysaletop'] == 1) ? true : false;
        $deliverysalecomplete = ($module['deliverysalecomplete'] == 1) ? true : false;
        $deliverysaleaccessory = ($module['deliverysaleaccessory'] == 1) ? true : false;
        $deliverysalewig = ($module['deliverysalewig'] == 1) ? true : false;
        $deliverysalecosmetics = ($module['deliverysalecosmetics'] == 1) ? true : false;


        $constant = ($module['constant'] == 1) ? true : false;
        $backup = ($module['backup'] == 1) ? true : false;
        $email = ($module['email'] == 1) ? true : false;








        // $constview = ($module['constview'] == 1) ? true : false;
        // $backview = ($module['backview'] == 1) ? true : false;
        // $profileview = ($module['profileview'] == 1) ? true : false;





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


            <?php if ($viewjeans) : ?>


                <li class="menu-item">
                    <a href="javascript:void(0)" data-fc-type="collapse" class="menu-link">
                        <span class="menu-icon"><i class="msr">calculate</i></span>
                        <span class="menu-text"> Jeans </span>
                        <span class="menu-arrow"></span>
                    </a>

                    <ul class="sub-menu hidden">

                        <?php if ($addjeans) : ?>

                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/price_calculator/add_single_jeans.php" class="menu-link">
                                    <span class="menu-text">Jeans</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($viewjeans) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/price_calculator/all_jeans.php" class="menu-link">
                                    <span class="menu-text">All Jeans</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if ($viewjeans) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/price_calculator/type_jeans.php" class="menu-link">
                                    <span class="menu-text">Type Jeans</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($salejeans) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/price_calculator/sale_jeans.php" class="menu-link">
                                    <span class="menu-text">Sale Jeans</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($logjeans) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/price_calculator/jeans_stock_log.php" class="menu-link">
                                    <span class="menu-text">Jeans Stock Log</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($verifyjeans) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/price_calculator/verify.php" class="menu-link">
                                    <span class="menu-text">Verify</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if ($logjeans) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/price_calculator/exchange.php" class="menu-link">
                                    <span class="menu-text">Exchange Log</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($logjeans) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/price_calculator/refund.php" class="menu-link">
                                    <span class="menu-text">Refund Log</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if ($deliverysalejeans) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/price_calculator/delivery.php" class="menu-link">
                                    <span class="menu-text">Delivery</span>
                                </a>
                            </li>
                        <?php endif; ?>




                    </ul>
                </li>
            <?php endif; ?>


            <?php if ($viewshoes) : ?>


                <li class="menu-item">
                    <a href="javascript:void(0)" data-fc-type="collapse" class="menu-link">
                        <span class="menu-icon"><i class="msr">calculate</i></span>
                        <span class="menu-text"> Shoes </span>
                        <span class="menu-arrow"></span>
                    </a>

                    <ul class="sub-menu hidden">

                        <?php if ($addshoes) : ?>

                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/shoe/add_shoes.php" class="menu-link">
                                    <span class="menu-text">Add Shoes</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($viewshoes) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/shoe/all_shoes.php" class="menu-link">
                                    <span class="menu-text">All Shoes</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if ($viewshoes) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/shoe/type_shoes.php" class="menu-link">
                                    <span class="menu-text">Type Shoes</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($saleshoes) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/shoe/sale_shoes.php" class="menu-link">
                                    <span class="menu-text">Sale Shoes</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($logshoes) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/shoe/shoes_stock_log.php" class="menu-link">
                                    <span class="menu-text">Shoes Stock Log</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($verifyshoes) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/shoe/verify.php" class="menu-link">
                                    <span class="menu-text">Verify</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if ($logshoes) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/shoe/exchange.php" class="menu-link">
                                    <span class="menu-text">Exchange Log</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($logshoes) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/shoe/refund.php" class="menu-link">
                                    <span class="menu-text">Refund Log</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if ($deliverysaleshoes) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/shoe/delivery.php" class="menu-link">
                                    <span class="menu-text">Delivery</span>
                                </a>
                            </li>
                        <?php endif; ?>




                    </ul>
                </li>
            <?php endif; ?>


            <?php if ($viewtop) : ?>


                <li class="menu-item">
                    <a href="javascript:void(0)" data-fc-type="collapse" class="menu-link">
                        <span class="menu-icon"><i class="msr">calculate</i></span>
                        <span class="menu-text"> Top </span>
                        <span class="menu-arrow"></span>
                    </a>

                    <ul class="sub-menu hidden">

                        <?php if ($addtop) : ?>

                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/top/add_top.php" class="menu-link">
                                    <span class="menu-text">Add Top</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($viewtop) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/top/all_top.php" class="menu-link">
                                    <span class="menu-text">All Top</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if ($viewtop) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/top/type_top.php" class="menu-link">
                                    <span class="menu-text">Type Top</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($saletop) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/top/sale_top.php" class="menu-link">
                                    <span class="menu-text">Sale Top</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($logtop) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/top/top_stock_log.php" class="menu-link">
                                    <span class="menu-text">Top Stock Log</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($verifytop) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/top/verify.php" class="menu-link">
                                    <span class="menu-text">Verify</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if ($logtop) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/top/exchange.php" class="menu-link">
                                    <span class="menu-text">Exchange Log</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($logtop) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/top/refund.php" class="menu-link">
                                    <span class="menu-text">Refund Log</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if ($deliverysaletop) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/top/delivery.php" class="menu-link">
                                    <span class="menu-text">Delivery</span>
                                </a>
                            </li>
                        <?php endif; ?>




                    </ul>
                </li>
            <?php endif; ?>



            <?php if ($viewcomplete) : ?>


                <li class="menu-item">
                    <a href="javascript:void(0)" data-fc-type="collapse" class="menu-link">
                        <span class="menu-icon"><i class="msr">calculate</i></span>
                        <span class="menu-text"> Complete </span>
                        <span class="menu-arrow"></span>
                    </a>

                    <ul class="sub-menu hidden">

                        <?php if ($addcomplete) : ?>

                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/complete/add_complete.php" class="menu-link">
                                    <span class="menu-text">Add Complete</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($viewcomplete) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/complete/all_complete.php" class="menu-link">
                                    <span class="menu-text">All Complete</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if ($viewcomplete) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/complete/type_complete.php" class="menu-link">
                                    <span class="menu-text">Type Complete</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($salecomplete) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/complete/sale_complete.php" class="menu-link">
                                    <span class="menu-text">Sale complete</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($logcomplete) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/complete/complete_stock_log.php" class="menu-link">
                                    <span class="menu-text">complete Stock Log</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($verifycomplete) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/complete/verify.php" class="menu-link">
                                    <span class="menu-text">Verify</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if ($logcomplete) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/complete/exchange.php" class="menu-link">
                                    <span class="menu-text">Exchange Log</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($logcomplete) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/complete/refund.php" class="menu-link">
                                    <span class="menu-text">Refund Log</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if ($deliverysalecomplete) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/complete/delivery.php" class="menu-link">
                                    <span class="menu-text">Delivery</span>
                                </a>
                            </li>
                        <?php endif; ?>




                    </ul>
                </li>
            <?php endif; ?>


            <?php if ($viewaccessory) : ?>


                <li class="menu-item">
                    <a href="javascript:void(0)" data-fc-type="collapse" class="menu-link">
                        <span class="menu-icon"><i class="msr">calculate</i></span>
                        <span class="menu-text"> Accessory </span>
                        <span class="menu-arrow"></span>
                    </a>

                    <ul class="sub-menu hidden">

                        <?php if ($addaccessory) : ?>

                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/accessory/add_accessory.php" class="menu-link">
                                    <span class="menu-text">Add accessory</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($viewaccessory) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/accessory/all_accessory.php" class="menu-link">
                                    <span class="menu-text">All accessory</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if ($viewaccessory) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/accessory/type_accessory.php" class="menu-link">
                                    <span class="menu-text">Type accessory</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($saleaccessory) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/accessory/sale_accessory.php" class="menu-link">
                                    <span class="menu-text">Sale accessory</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($logaccessory) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/accessory/accessory_stock_log.php" class="menu-link">
                                    <span class="menu-text">Accessory Stock Log</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($verifyaccessory) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/accessory/verify.php" class="menu-link">
                                    <span class="menu-text">Verify</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if ($logaccessory) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/accessory/exchange.php" class="menu-link">
                                    <span class="menu-text">Exchange Log</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($logaccessory) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/accessory/refund.php" class="menu-link">
                                    <span class="menu-text">Refund Log</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if ($deliverysaleaccessory) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/accessory/delivery.php" class="menu-link">
                                    <span class="menu-text">Delivery</span>
                                </a>
                            </li>
                        <?php endif; ?>




                    </ul>
                </li>
            <?php endif; ?>



            <?php if ($viewwig) : ?>


                <li class="menu-item">
                    <a href="javascript:void(0)" data-fc-type="collapse" class="menu-link">
                        <span class="menu-icon"><i class="msr">calculate</i></span>
                        <span class="menu-text"> Wig </span>
                        <span class="menu-arrow"></span>
                    </a>

                    <ul class="sub-menu hidden">

                        <?php if ($addwig) : ?>

                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/wig/add_wig.php" class="menu-link">
                                    <span class="menu-text">Add wig</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($viewwig) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/wig/all_wig.php" class="menu-link">
                                    <span class="menu-text">All Wig</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if ($viewwig) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/wig/type_wig.php" class="menu-link">
                                    <span class="menu-text">Type accessory</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($salewig) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/wig/sale_wig.php" class="menu-link">
                                    <span class="menu-text">Sale Wig</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($logwig) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/wig/wig_stock_log.php" class="menu-link">
                                    <span class="menu-text">Wig Stock Log</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($verifywig) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/wig/verify.php" class="menu-link">
                                    <span class="menu-text">Verify</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if ($logwig) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/wig/exchange.php" class="menu-link">
                                    <span class="menu-text">Exchange Log</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($logwig) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/wig/refund.php" class="menu-link">
                                    <span class="menu-text">Refund Log</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if ($deliverysalewig) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/wig/delivery.php" class="menu-link">
                                    <span class="menu-text">Delivery</span>
                                </a>
                            </li>
                        <?php endif; ?>




                    </ul>
                </li>
            <?php endif; ?>


            <?php if ($viewcosmetics) : ?>


                <li class="menu-item">
                    <a href="javascript:void(0)" data-fc-type="collapse" class="menu-link">
                        <span class="menu-icon"><i class="msr">calculate</i></span>
                        <span class="menu-text"> Cosmetics </span>
                        <span class="menu-arrow"></span>
                    </a>

                    <ul class="sub-menu hidden">

                        <?php if ($addcosmetics) : ?>

                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/cosmetics/add_cosmetics.php" class="menu-link">
                                    <span class="menu-text">Add Cosmetics</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($viewcosmetics) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/cosmetics/all_cosmetics.php" class="menu-link">
                                    <span class="menu-text">All Cosmetics</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if ($viewcosmetics) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/cosmetics/type_cosmetics.php" class="menu-link">
                                    <span class="menu-text">Type cosmetics</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($salecosmetics) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/cosmetics/sale_cosmetics.php" class="menu-link">
                                    <span class="menu-text">Sale Cosmetics</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($logcosmetics) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/cosmetics/cosmetics_stock_log.php" class="menu-link">
                                    <span class="menu-text">Cosmetics Stock Log</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($verifycosmetics) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/cosmetics/verify.php" class="menu-link">
                                    <span class="menu-text">Verify</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if ($logcosmetics) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/cosmetics/exchange.php" class="menu-link">
                                    <span class="menu-text">Exchange Log</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($logcosmetics) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/cosmetics/refund.php" class="menu-link">
                                    <span class="menu-text">Refund Log</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if ($deliverysalecosmetics) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/cosmetics/delivery.php" class="menu-link">
                                    <span class="menu-text">Delivery</span>
                                </a>
                            </li>
                        <?php endif; ?>




                    </ul>
                </li>
            <?php endif; ?>


            <?php if ($salejeans) : ?>


                <li class="menu-item">
                    <a href="javascript:void(0)" data-fc-type="collapse" class="menu-link">
                        <span class="menu-icon"><i class="msr">calculate</i></span>
                        <span class="menu-text"> SALE </span>
                        <span class="menu-arrow"></span>
                    </a>

                    <ul class="sub-menu hidden">


                        <?php if ($salejeans) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/sale/sale.php" class="menu-link">
                                    <span class="menu-text">Single Sale</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($salejeans) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/sale/multi.php" class="menu-link">
                                    <span class="menu-text">Multi Sale</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if ($logjeans) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/sale/all_sales.php" class="menu-link">
                                    <span class="menu-text">All Sales</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($logjeans) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/sale/sale_log.php" class="menu-link">
                                    <span class="menu-text">All Sales Log</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if ($logjeans) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/sale/search.php" class="menu-link">
                                    <span class="menu-text">Search Product</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if ($logjeans) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/sale/delivery.php" class="menu-link">
                                    <span class="menu-text">Delivery</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if ($logjeans) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/sale/all_product_type.php" class="menu-link">
                                    <span class="menu-text">All Product Types</span>
                                </a>
                            </li>
                        <?php endif; ?>




                    </ul>
                </li>
            <?php endif; ?>


            <?php if ($constant) : ?>


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



            <?php if ($backup) : ?>
                <li class="menu-item">
                    <a href="<?php echo $redirect_link ?>newbackup.php" class="menu-link">
                        <span class="menu-icon"><i class="mgc_pdf_line"></i></span>
                        <span class="menu-text">BackUp</span>
                    </a>
                </li>
            <?php endif; ?>


            <?php if ($email) : ?>
                <li class="menu-item">
                    <a href="<?php echo $redirect_link ?>pages/email/email.php" class="menu-link">
                        <span class="menu-icon"><i class="mgc_pdf_line"></i></span>
                        <span class="menu-text">Email</span>
                    </a>
                </li>
            <?php endif; ?>













        </ul>
    </div>
</div>
<!-- Sidenav Menu End  -->
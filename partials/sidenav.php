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

        $addproduct = ($module['addproduct'] == 1) ? true : false;
        $fullsale = ($module['fullsale'] == 1) ? true : false;
        $allsale = ($module['allsale'] == 1) ? true : false;
        $logsale = ($module['logsale'] == 1) ? true : false;
        $searchproduct = ($module['searchproduct'] == 1) ? true : false;
        $deliverysale = ($module['deliverysale'] == 1) ? true : false;
        $producttypes = ($module['producttypes'] == 1) ? true : false;
        $productsin= ($module['productsin'] == 1) ? true : false;
        $verifyproducts= ($module['verifyproducts'] == 1) ? true : false;








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
            <img src="<?php echo $redirect_link ?>assets/images/zuqemens.JPG" class="logo-lg" style="height: 40px; width: 40px;" alt="Light Logo">
            <img src="<?php echo $redirect_link ?>assets/images/zuqemens.JPG" class="logo-sm" style="height: 40px; width: 40px;" alt="Small Light Logo">
        </div>

        <!-- Dark Brand Logo -->
        <div class="logo-dark">
            <img src="<?php echo $redirect_link ?>assets/images/zuqemens.JPG" class="logo-lg" style="height: 40px; width: 40px;" alt="Dark Logo">
            <img src="<?php echo $redirect_link ?>assets/images/zuqemens.JPG" class="logo-sm" style="height: 40px; width: 40px;" alt="Small Dark Logo">
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


            


            


                <li class="menu-item">
                    <a href="javascript:void(0)" data-fc-type="collapse" class="menu-link">
                        <span class="menu-icon"><i class="msr">calculate</i></span>
                        <span class="menu-text"> SALE </span>
                        <span class="menu-arrow"></span>
                    </a>

                    <ul class="sub-menu hidden">

                        
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/sale/main.php" class="menu-link">
                                    <span class="menu-text">Main</span>
                                </a>
                            </li>
                       

                        <?php if ($addproduct) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/sale/add/add_dress.php" class="menu-link">
                                    <span class="menu-text">Add Product</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        
                        <?php if ($addproduct) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/dress/viewdress.php" class="menu-link">
                                    <span class="menu-text">View Dress</span>
                                </a>
                            </li>
                        <?php endif; ?>




                       
                        <?php if ($fullsale) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/sale/multi.php" class="menu-link">
                                    <span class="menu-text">Sale</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if ($allsale) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/sale/all_sales.php" class="menu-link">
                                    <span class="menu-text">All Sales</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($logsale) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/sale/sale_log.php" class="menu-link">
                                    <span class="menu-text">All Sales Log</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if ($searchproduct) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/sale/search.php" class="menu-link">
                                    <span class="menu-text">Search Product</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if ($deliverysale) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/sale/delivery.php" class="menu-link">
                                    <span class="menu-text">Delivery</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if ($producttypes) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/sale/all_product_type.php" class="menu-link">
                                    <span class="menu-text">All Product Types</span>
                                </a>
                            </li>
                        <?php endif; ?>


                        <?php if ($searchproduct) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/sale/search_multi.php" class="menu-link">
                                    <span class="menu-text">Multiple Search</span>
                                </a>
                            </li>
                        <?php endif; ?>


                        <?php if ($productsin) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/sale/products_log.php" class="menu-link">
                                    <span class="menu-text">Products In</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if ($logsale) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/sale/multi_log.php" class="menu-link">
                                    <span class="menu-text">Multi Sale Log</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if ($verifyproducts) : ?>
                            <li class="menu-item">
                                <a href="<?php echo $redirect_link ?>pages/sale/verify_products.php" class="menu-link">
                                    <span class="menu-text">Verify Products</span>
                                </a>
                            </li>
                        <?php endif; ?>




                    </ul>
                </li>
            


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
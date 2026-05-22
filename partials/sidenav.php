<?php
include $redirect_link . 'include/db.php';

/* ── Load app settings (logo, company name) ─────────────────── */
if (!function_exists('get_app_settings')) {
    function get_app_settings($con) {
        static $cache = null;
        if ($cache !== null) return $cache;
        // Create table if missing
        mysqli_query($con, "CREATE TABLE IF NOT EXISTS `app_settings` (
            `key` varchar(100) NOT NULL,
            `value` text NOT NULL,
            PRIMARY KEY (`key`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        $cache = [];
        $res = mysqli_query($con, "SELECT `key`, `value` FROM `app_settings`");
        if ($res) {
            while ($r = mysqli_fetch_assoc($res)) {
                $cache[$r['key']] = $r['value'];
            }
        }
        $defaults = [
            'company_name' => 'Zuqemens',
            'company_logo' => '',
            'currency'     => 'ETB',
            'store_name'   => 'Stock Hub',
        ];
        $cache = array_merge($defaults, $cache);
        return $cache;
    }
}

$app_settings   = get_app_settings($con);
$company_name   = htmlspecialchars($app_settings['company_name']);
$company_logo   = $app_settings['company_logo'];

/* ── Module visibility toggles ──────────────────────────────── */
$mod_jeans     = ($app_settings['mod_jeans']     ?? '1') === '1';
$mod_shoes     = ($app_settings['mod_shoes']     ?? '1') === '1';
$mod_top       = ($app_settings['mod_top']       ?? '1') === '1';
$mod_complete  = ($app_settings['mod_complete']  ?? '1') === '1';
$mod_accessory = ($app_settings['mod_accessory'] ?? '1') === '1';
$mod_wig       = ($app_settings['mod_wig']       ?? '1') === '1';
$mod_cosmetics = ($app_settings['mod_cosmetics'] ?? '1') === '1';
$logo_src       = !empty($company_logo) && file_exists($redirect_link . $company_logo)
                    ? $redirect_link . $company_logo
                    : $redirect_link . 'assets/images/zuqemens.JPG';

/* ── Load current user + permissions ────────────────────────── */
$id     = $_SESSION['user_id'];
$result = mysqli_query($con, "SELECT * FROM user WHERE user_id = " . (int)$id);

$user_id = $user_name = $privileged = '';
$module  = [];

if ($result && $row = mysqli_fetch_assoc($result)) {
    $user_id    = $row['user_id'];
    $user_name  = $row['user_name'];
    $privileged = $row['previledge'];
    $module     = json_decode($row['module'], true) ?: [];
    mysqli_free_result($result);
}

/* helper: safe permission check */
$perm = function(string $key) use ($module): bool {
    return !empty($module[$key]) && $module[$key] == 1;
};

$viewjeans     = $perm('viewjeans');
$viewshoes     = $perm('viewshoes');
$viewtop       = $perm('viewtop');
$viewcomplete  = $perm('viewcomplete');
$viewaccessory = $perm('viewaccessory');
$viewwig       = $perm('viewwig');
$viewcosmetics = $perm('viewcosmetics');

$addjeans     = $perm('addjeans');
$addshoes     = $perm('addshoes');
$addtop       = $perm('addtop');
$addcomplete  = $perm('addcomplete');
$addaccessory = $perm('addaccessory');
$addwig       = $perm('addwig');
$addcosmetics = $perm('addcosmetics');

$salejeans     = $perm('salejeans');
$saleshoes     = $perm('saleshoes');
$saletop       = $perm('saletop');
$salecomplete  = $perm('salecomplete');
$saleaccessory = $perm('saleaccessory');
$salewig       = $perm('salewig');
$salecosmetics = $perm('salecosmetics');

$logjeans     = $perm('logjeans');
$logshoes     = $perm('logshoes');
$logtop       = $perm('logtop');
$logcomplete  = $perm('logcomplete');
$logaccessory = $perm('logaccessory');
$logwig       = $perm('logwig');
$logcosmetics = $perm('logcosmetics');

$verifyjeans     = $perm('verifyjeans');
$verifyshoes     = $perm('verifyshoes');
$verifytop       = $perm('verifytop');
$verifycomplete  = $perm('verifycomplete');
$verifyaccessory = $perm('verifyaccessory');
$verifywig       = $perm('verifywig');
$verifycosmetics = $perm('verifycosmetics');

$deliverysalejeans     = $perm('deliverysalejeans');
$deliverysaleshoes     = $perm('deliverysaleshoes');
$deliverysaletop       = $perm('deliverysaletop');
$deliverysalecomplete  = $perm('deliverysalecomplete');
$deliverysaleaccessory = $perm('deliverysaleaccessory');
$deliverysalewig       = $perm('deliverysalewig');
$deliverysalecosmetics = $perm('deliverysalecosmetics');

$constant       = $perm('constant');
$backup         = $perm('backup');
$email          = $perm('email');
$settings_perm  = $perm('settings');

$addproduct     = $perm('addproduct');
$fullsale       = $perm('fullsale');
$allsale        = $perm('allsale');
$logsale        = $perm('logsale');
$searchproduct  = $perm('searchproduct');
$deliverysale   = $perm('deliverysale');
$producttypes   = $perm('producttypes');
$productsin     = $perm('productsin');
$verifyproducts = $perm('verifyproducts');

$is_master = ($user_name === 'masteradmin');
?>

<!-- ============================================================ -->
<!-- Sidenav Start                                               -->
<!-- ============================================================ -->
<div class="app-menu">

    <!-- Brand / Logo -->
    <a href="<?= $redirect_link ?>index.php" class="logo-box" style="text-decoration:none;">
        <div class="logo-light" style="display:flex;align-items:center;gap:10px;padding:18px 20px;border-bottom:1px solid rgba(255,255,255,0.07);">
            <img src="<?= $logo_src ?>" class="logo-lg" style="height:38px;width:38px;border-radius:12px;object-fit:cover;box-shadow:0 4px 12px rgba(0,0,0,0.35);" alt="Logo">
            <div>
                <div style="font-size:0.9rem;font-weight:800;color:#fff;letter-spacing:-0.01em;line-height:1.1;"><?= $company_name ?></div>
                <div style="font-size:0.62rem;color:rgba(255,255,255,0.35);font-weight:600;text-transform:uppercase;letter-spacing:0.1em;">Stock Hub</div>
            </div>
        </div>
        <div class="logo-dark" style="display:none;"></div>
    </a>

    <!-- Hover-toggle pin -->
    <button id="button-hover-toggle" class="absolute top-5 end-2 rounded-full p-1.5">
        <span class="sr-only">Toggle Menu</span>
        <i class="mgc_round_line text-xl"></i>
    </button>

    <!-- ── Scrollable menu area ────────────────────────────── -->
    <div class="srcollbar" data-simplebar>
        <ul class="menu" data-fc-type="accordion">

            <?php if ($is_master) : ?>
            <!-- Dashboard -->
            <li class="menu-title menu-section-label">Overview</li>
            <li class="menu-item">
                <a href="<?= $redirect_link ?>index2.php" class="menu-link">
                    <span class="menu-icon"><i class="fas fa-chart-line"></i></span>
                    <span class="menu-text">Dashboard</span>
                </a>
            </li>
            <?php endif; ?>

            <!-- ── INVENTORY ──────────────────────────────── -->
            <?php
            $show_inventory = ($mod_jeans && $viewjeans) || ($mod_shoes && $viewshoes) || ($mod_top && $viewtop) || ($mod_complete && $viewcomplete) || ($mod_accessory && $viewaccessory) || ($mod_wig && $viewwig) || ($mod_cosmetics && $viewcosmetics);
            if ($show_inventory) :
            ?>
            <li class="menu-title menu-section-label">Inventory</li>

            <?php if ($mod_jeans && $viewjeans) : ?>
            <li class="menu-item">
                <a href="javascript:void(0)" data-fc-type="collapse" class="menu-link">
                    <span class="menu-icon"><i class="fas fa-scroll"></i></span>
                    <span class="menu-text">Jeans</span>
                    <span class="menu-arrow"></span>
                </a>
                <ul class="sub-menu hidden">
                    <?php if ($addjeans) : ?>
                    <li class="menu-item"><a href="<?= $redirect_link ?>pages/price_calculator/add_single_jeans.php" class="menu-link"><span class="menu-text">Add Jeans</span></a></li>
                    <?php endif; ?>
                    <li class="menu-item"><a href="<?= $redirect_link ?>pages/price_calculator/all_jeans.php" class="menu-link"><span class="menu-text">All Jeans</span></a></li>
                    <li class="menu-item"><a href="<?= $redirect_link ?>pages/price_calculator/type_jeans.php" class="menu-link"><span class="menu-text">Type Jeans</span></a></li>
                    <?php if ($salejeans) : ?>
                    <li class="menu-item"><a href="<?= $redirect_link ?>pages/price_calculator/sale_jeans.php" class="menu-link"><span class="menu-text">Sale Jeans</span></a></li>
                    <?php endif; ?>
                    <?php if ($logjeans) : ?>
                    <li class="menu-item"><a href="<?= $redirect_link ?>pages/price_calculator/jeans_stock_log.php" class="menu-link"><span class="menu-text">Stock Log</span></a></li>
                    <?php endif; ?>
                    <?php if ($verifyjeans) : ?>
                    <li class="menu-item"><a href="<?= $redirect_link ?>pages/price_calculator/verify.php" class="menu-link"><span class="menu-text">Verify</span></a></li>
                    <?php endif; ?>
                    <?php if ($logjeans) : ?>
                    <li class="menu-item"><a href="<?= $redirect_link ?>pages/price_calculator/exchange.php" class="menu-link"><span class="menu-text">Exchange Log</span></a></li>
                    <li class="menu-item"><a href="<?= $redirect_link ?>pages/price_calculator/refund.php" class="menu-link"><span class="menu-text">Refund Log</span></a></li>
                    <?php endif; ?>
                    <?php if ($deliverysalejeans) : ?>
                    <li class="menu-item"><a href="<?= $redirect_link ?>pages/price_calculator/delivery.php" class="menu-link"><span class="menu-text">Delivery</span></a></li>
                    <?php endif; ?>
                </ul>
            </li>
            <?php endif; ?>

            <?php if ($mod_shoes && $viewshoes) : ?>
            <li class="menu-item">
                <a href="javascript:void(0)" data-fc-type="collapse" class="menu-link">
                    <span class="menu-icon"><i class="fas fa-shoe-prints"></i></span>
                    <span class="menu-text">Shoes</span>
                    <span class="menu-arrow"></span>
                </a>
                <ul class="sub-menu hidden">
                    <?php if ($addshoes) : ?>
                    <li class="menu-item"><a href="<?= $redirect_link ?>pages/shoe/add_shoes.php" class="menu-link"><span class="menu-text">Add Shoes</span></a></li>
                    <?php endif; ?>
                    <li class="menu-item"><a href="<?= $redirect_link ?>pages/shoe/all_shoes.php" class="menu-link"><span class="menu-text">All Shoes</span></a></li>
                    <li class="menu-item"><a href="<?= $redirect_link ?>pages/shoe/type_shoes.php" class="menu-link"><span class="menu-text">Type Shoes</span></a></li>
                    <?php if ($saleshoes) : ?>
                    <li class="menu-item"><a href="<?= $redirect_link ?>pages/shoe/sale_shoes.php" class="menu-link"><span class="menu-text">Sale Shoes</span></a></li>
                    <?php endif; ?>
                    <?php if ($logshoes) : ?>
                    <li class="menu-item"><a href="<?= $redirect_link ?>pages/shoe/shoes_stock_log.php" class="menu-link"><span class="menu-text">Stock Log</span></a></li>
                    <?php endif; ?>
                    <?php if ($verifyshoes) : ?>
                    <li class="menu-item"><a href="<?= $redirect_link ?>pages/shoe/verify.php" class="menu-link"><span class="menu-text">Verify</span></a></li>
                    <?php endif; ?>
                    <?php if ($logshoes) : ?>
                    <li class="menu-item"><a href="<?= $redirect_link ?>pages/shoe/exchange.php" class="menu-link"><span class="menu-text">Exchange Log</span></a></li>
                    <li class="menu-item"><a href="<?= $redirect_link ?>pages/shoe/refund.php" class="menu-link"><span class="menu-text">Refund Log</span></a></li>
                    <?php endif; ?>
                    <?php if ($deliverysaleshoes) : ?>
                    <li class="menu-item"><a href="<?= $redirect_link ?>pages/shoe/delivery.php" class="menu-link"><span class="menu-text">Delivery</span></a></li>
                    <?php endif; ?>
                </ul>
            </li>
            <?php endif; ?>

            <?php if ($mod_top && $viewtop) : ?>
            <li class="menu-item">
                <a href="javascript:void(0)" data-fc-type="collapse" class="menu-link">
                    <span class="menu-icon"><i class="fas fa-tshirt"></i></span>
                    <span class="menu-text">Top</span>
                    <span class="menu-arrow"></span>
                </a>
                <ul class="sub-menu hidden">
                    <?php if ($addtop) : ?>
                    <li class="menu-item"><a href="<?= $redirect_link ?>pages/top/add_top.php" class="menu-link"><span class="menu-text">Add Top</span></a></li>
                    <?php endif; ?>
                    <li class="menu-item"><a href="<?= $redirect_link ?>pages/top/all_top.php" class="menu-link"><span class="menu-text">All Top</span></a></li>
                    <li class="menu-item"><a href="<?= $redirect_link ?>pages/top/type_top.php" class="menu-link"><span class="menu-text">Type Top</span></a></li>
                    <?php if ($saletop) : ?>
                    <li class="menu-item"><a href="<?= $redirect_link ?>pages/top/sale_top.php" class="menu-link"><span class="menu-text">Sale Top</span></a></li>
                    <?php endif; ?>
                    <?php if ($logtop) : ?>
                    <li class="menu-item"><a href="<?= $redirect_link ?>pages/top/top_stock_log.php" class="menu-link"><span class="menu-text">Stock Log</span></a></li>
                    <?php endif; ?>
                    <?php if ($verifytop) : ?>
                    <li class="menu-item"><a href="<?= $redirect_link ?>pages/top/verify.php" class="menu-link"><span class="menu-text">Verify</span></a></li>
                    <?php endif; ?>
                    <?php if ($logtop) : ?>
                    <li class="menu-item"><a href="<?= $redirect_link ?>pages/top/exchange.php" class="menu-link"><span class="menu-text">Exchange Log</span></a></li>
                    <li class="menu-item"><a href="<?= $redirect_link ?>pages/top/refund.php" class="menu-link"><span class="menu-text">Refund Log</span></a></li>
                    <?php endif; ?>
                    <?php if ($deliverysaletop) : ?>
                    <li class="menu-item"><a href="<?= $redirect_link ?>pages/top/delivery.php" class="menu-link"><span class="menu-text">Delivery</span></a></li>
                    <?php endif; ?>
                </ul>
            </li>
            <?php endif; ?>

            <?php if ($mod_complete && $viewcomplete) : ?>
            <li class="menu-item">
                <a href="javascript:void(0)" data-fc-type="collapse" class="menu-link">
                    <span class="menu-icon"><i class="fas fa-box-open"></i></span>
                    <span class="menu-text">Complete</span>
                    <span class="menu-arrow"></span>
                </a>
                <ul class="sub-menu hidden">
                    <?php if ($addcomplete) : ?>
                    <li class="menu-item"><a href="<?= $redirect_link ?>pages/complete/add_complete.php" class="menu-link"><span class="menu-text">Add Complete</span></a></li>
                    <?php endif; ?>
                    <li class="menu-item"><a href="<?= $redirect_link ?>pages/complete/all_complete.php" class="menu-link"><span class="menu-text">All Complete</span></a></li>
                    <li class="menu-item"><a href="<?= $redirect_link ?>pages/complete/type_complete.php" class="menu-link"><span class="menu-text">Type Complete</span></a></li>
                    <?php if ($salecomplete) : ?>
                    <li class="menu-item"><a href="<?= $redirect_link ?>pages/complete/sale_complete.php" class="menu-link"><span class="menu-text">Sale Complete</span></a></li>
                    <?php endif; ?>
                    <?php if ($logcomplete) : ?>
                    <li class="menu-item"><a href="<?= $redirect_link ?>pages/complete/complete_stock_log.php" class="menu-link"><span class="menu-text">Stock Log</span></a></li>
                    <?php endif; ?>
                    <?php if ($verifycomplete) : ?>
                    <li class="menu-item"><a href="<?= $redirect_link ?>pages/complete/verify.php" class="menu-link"><span class="menu-text">Verify</span></a></li>
                    <?php endif; ?>
                    <?php if ($logcomplete) : ?>
                    <li class="menu-item"><a href="<?= $redirect_link ?>pages/complete/exchange.php" class="menu-link"><span class="menu-text">Exchange Log</span></a></li>
                    <li class="menu-item"><a href="<?= $redirect_link ?>pages/complete/refund.php" class="menu-link"><span class="menu-text">Refund Log</span></a></li>
                    <?php endif; ?>
                    <?php if ($deliverysalecomplete) : ?>
                    <li class="menu-item"><a href="<?= $redirect_link ?>pages/complete/delivery.php" class="menu-link"><span class="menu-text">Delivery</span></a></li>
                    <?php endif; ?>
                </ul>
            </li>
            <?php endif; ?>

            <?php if ($mod_accessory && $viewaccessory) : ?>
            <li class="menu-item">
                <a href="javascript:void(0)" data-fc-type="collapse" class="menu-link">
                    <span class="menu-icon"><i class="fas fa-gem"></i></span>
                    <span class="menu-text">Accessory</span>
                    <span class="menu-arrow"></span>
                </a>
                <ul class="sub-menu hidden">
                    <?php if ($addaccessory) : ?>
                    <li class="menu-item"><a href="<?= $redirect_link ?>pages/accessory/add_accessory.php" class="menu-link"><span class="menu-text">Add Accessory</span></a></li>
                    <?php endif; ?>
                    <li class="menu-item"><a href="<?= $redirect_link ?>pages/accessory/all_accessory.php" class="menu-link"><span class="menu-text">All Accessory</span></a></li>
                    <li class="menu-item"><a href="<?= $redirect_link ?>pages/accessory/type_accessory.php" class="menu-link"><span class="menu-text">Type Accessory</span></a></li>
                    <?php if ($saleaccessory) : ?>
                    <li class="menu-item"><a href="<?= $redirect_link ?>pages/accessory/sale_accessory.php" class="menu-link"><span class="menu-text">Sale Accessory</span></a></li>
                    <?php endif; ?>
                    <?php if ($logaccessory) : ?>
                    <li class="menu-item"><a href="<?= $redirect_link ?>pages/accessory/accessory_stock_log.php" class="menu-link"><span class="menu-text">Stock Log</span></a></li>
                    <?php endif; ?>
                    <?php if ($verifyaccessory) : ?>
                    <li class="menu-item"><a href="<?= $redirect_link ?>pages/accessory/verify.php" class="menu-link"><span class="menu-text">Verify</span></a></li>
                    <?php endif; ?>
                    <?php if ($logaccessory) : ?>
                    <li class="menu-item"><a href="<?= $redirect_link ?>pages/accessory/exchange.php" class="menu-link"><span class="menu-text">Exchange Log</span></a></li>
                    <li class="menu-item"><a href="<?= $redirect_link ?>pages/accessory/refund.php" class="menu-link"><span class="menu-text">Refund Log</span></a></li>
                    <?php endif; ?>
                    <?php if ($deliverysaleaccessory) : ?>
                    <li class="menu-item"><a href="<?= $redirect_link ?>pages/accessory/delivery.php" class="menu-link"><span class="menu-text">Delivery</span></a></li>
                    <?php endif; ?>
                </ul>
            </li>
            <?php endif; ?>

            <?php if ($mod_wig && $viewwig) : ?>
            <li class="menu-item">
                <a href="javascript:void(0)" data-fc-type="collapse" class="menu-link">
                    <span class="menu-icon"><i class="fas fa-hat-wizard"></i></span>
                    <span class="menu-text">Wig</span>
                    <span class="menu-arrow"></span>
                </a>
                <ul class="sub-menu hidden">
                    <?php if ($addwig) : ?>
                    <li class="menu-item"><a href="<?= $redirect_link ?>pages/wig/add_wig.php" class="menu-link"><span class="menu-text">Add Wig</span></a></li>
                    <?php endif; ?>
                    <li class="menu-item"><a href="<?= $redirect_link ?>pages/wig/all_wig.php" class="menu-link"><span class="menu-text">All Wig</span></a></li>
                    <li class="menu-item"><a href="<?= $redirect_link ?>pages/wig/type_wig.php" class="menu-link"><span class="menu-text">Type Wig</span></a></li>
                    <?php if ($salewig) : ?>
                    <li class="menu-item"><a href="<?= $redirect_link ?>pages/wig/sale_wig.php" class="menu-link"><span class="menu-text">Sale Wig</span></a></li>
                    <?php endif; ?>
                    <?php if ($logwig) : ?>
                    <li class="menu-item"><a href="<?= $redirect_link ?>pages/wig/wig_stock_log.php" class="menu-link"><span class="menu-text">Stock Log</span></a></li>
                    <?php endif; ?>
                    <?php if ($verifywig) : ?>
                    <li class="menu-item"><a href="<?= $redirect_link ?>pages/wig/verify.php" class="menu-link"><span class="menu-text">Verify</span></a></li>
                    <?php endif; ?>
                    <?php if ($logwig) : ?>
                    <li class="menu-item"><a href="<?= $redirect_link ?>pages/wig/exchange.php" class="menu-link"><span class="menu-text">Exchange Log</span></a></li>
                    <li class="menu-item"><a href="<?= $redirect_link ?>pages/wig/refund.php" class="menu-link"><span class="menu-text">Refund Log</span></a></li>
                    <?php endif; ?>
                    <?php if ($deliverysalewig) : ?>
                    <li class="menu-item"><a href="<?= $redirect_link ?>pages/wig/delivery.php" class="menu-link"><span class="menu-text">Delivery</span></a></li>
                    <?php endif; ?>
                </ul>
            </li>
            <?php endif; ?>

            <?php if ($mod_cosmetics && $viewcosmetics) : ?>
            <li class="menu-item">
                <a href="javascript:void(0)" data-fc-type="collapse" class="menu-link">
                    <span class="menu-icon"><i class="fas fa-spa"></i></span>
                    <span class="menu-text">Cosmetics</span>
                    <span class="menu-arrow"></span>
                </a>
                <ul class="sub-menu hidden">
                    <?php if ($addcosmetics) : ?>
                    <li class="menu-item"><a href="<?= $redirect_link ?>pages/cosmetics/add_cosmetics.php" class="menu-link"><span class="menu-text">Add Cosmetics</span></a></li>
                    <?php endif; ?>
                    <li class="menu-item"><a href="<?= $redirect_link ?>pages/cosmetics/all_cosmetics.php" class="menu-link"><span class="menu-text">All Cosmetics</span></a></li>
                    <li class="menu-item"><a href="<?= $redirect_link ?>pages/cosmetics/type_cosmetics.php" class="menu-link"><span class="menu-text">Type Cosmetics</span></a></li>
                    <?php if ($salecosmetics) : ?>
                    <li class="menu-item"><a href="<?= $redirect_link ?>pages/cosmetics/sale_cosmetics.php" class="menu-link"><span class="menu-text">Sale Cosmetics</span></a></li>
                    <?php endif; ?>
                    <?php if ($logcosmetics) : ?>
                    <li class="menu-item"><a href="<?= $redirect_link ?>pages/cosmetics/cosmetics_stock_log.php" class="menu-link"><span class="menu-text">Stock Log</span></a></li>
                    <?php endif; ?>
                    <?php if ($verifycosmetics) : ?>
                    <li class="menu-item"><a href="<?= $redirect_link ?>pages/cosmetics/verify.php" class="menu-link"><span class="menu-text">Verify</span></a></li>
                    <?php endif; ?>
                    <?php if ($logcosmetics) : ?>
                    <li class="menu-item"><a href="<?= $redirect_link ?>pages/cosmetics/exchange.php" class="menu-link"><span class="menu-text">Exchange Log</span></a></li>
                    <li class="menu-item"><a href="<?= $redirect_link ?>pages/cosmetics/refund.php" class="menu-link"><span class="menu-text">Refund Log</span></a></li>
                    <?php endif; ?>
                    <?php if ($deliverysalecosmetics) : ?>
                    <li class="menu-item"><a href="<?= $redirect_link ?>pages/cosmetics/delivery.php" class="menu-link"><span class="menu-text">Delivery</span></a></li>
                    <?php endif; ?>
                </ul>
            </li>
            <?php endif; ?>

            <?php endif; /* end show_inventory */ ?>

            <!-- ── SALES ──────────────────────────────────── -->
            <li class="menu-title menu-section-label">Sales</li>

            <li class="menu-item">
                <a href="javascript:void(0)" data-fc-type="collapse" class="menu-link">
                    <span class="menu-icon"><i class="fas fa-cash-register"></i></span>
                    <span class="menu-text">Sales</span>
                    <span class="menu-arrow"></span>
                </a>
                <ul class="sub-menu hidden">
                    <li class="menu-item"><a href="<?= $redirect_link ?>pages/sale/main.php" class="menu-link"><span class="menu-text">Point of Sale</span></a></li>

                    <?php if ($is_master && $addproduct) : ?>
                    <li class="menu-item"><a href="<?= $redirect_link ?>pages/sale/add/add_shoes.php" class="menu-link"><span class="menu-text">Add Product</span></a></li>
                    <?php endif; ?>

                    <?php if ($fullsale) : ?>
                    <li class="menu-item"><a href="<?= $redirect_link ?>pages/sale/multi.php" class="menu-link"><span class="menu-text">New Sale</span></a></li>
                    <?php endif; ?>

                    <?php if ($allsale) : ?>
                    <li class="menu-item"><a href="<?= $redirect_link ?>pages/sale/all_sales.php" class="menu-link"><span class="menu-text">All Sales</span></a></li>
                    <?php endif; ?>

                    <?php if ($logsale) : ?>
                    <li class="menu-item"><a href="<?= $redirect_link ?>pages/sale/sale_log.php" class="menu-link"><span class="menu-text">Sales Log</span></a></li>
                    <?php endif; ?>

                    <?php if ($searchproduct) : ?>
                    <li class="menu-item"><a href="<?= $redirect_link ?>pages/sale/search.php" class="menu-link"><span class="menu-text">Search Product</span></a></li>
                    <li class="menu-item"><a href="<?= $redirect_link ?>pages/sale/search_multi.php" class="menu-link"><span class="menu-text">Multi Search</span></a></li>
                    <?php endif; ?>

                    <?php if ($deliverysale) : ?>
                    <li class="menu-item"><a href="<?= $redirect_link ?>pages/sale/delivery.php" class="menu-link"><span class="menu-text">Delivery</span></a></li>
                    <?php endif; ?>

                    <?php if ($is_master && $producttypes) : ?>
                    <li class="menu-item"><a href="<?= $redirect_link ?>pages/sale/all_product_type.php" class="menu-link"><span class="menu-text">All Product Types</span></a></li>
                    <?php endif; ?>

                    <?php if ($is_master && $productsin) : ?>
                    <li class="menu-item"><a href="<?= $redirect_link ?>pages/sale/products_log.php" class="menu-link"><span class="menu-text">Products In</span></a></li>
                    <?php endif; ?>

                    <?php if ($is_master && $logsale) : ?>
                    <li class="menu-item"><a href="<?= $redirect_link ?>pages/sale/multi_log.php" class="menu-link"><span class="menu-text">Multi Sale Log</span></a></li>
                    <?php endif; ?>

                    <?php if ($is_master && $verifyproducts) : ?>
                    <li class="menu-item"><a href="<?= $redirect_link ?>pages/sale/verify_products.php" class="menu-link"><span class="menu-text">Verify Products</span></a></li>
                    <?php endif; ?>
                </ul>
            </li>

            <!-- ── ADMIN ──────────────────────────────────── -->
            <?php if ($is_master) : ?>
            <li class="menu-title menu-section-label">Admin</li>

            <?php if ($constant) : ?>
            <li class="menu-item">
                <a href="javascript:void(0)" data-fc-type="collapse" class="menu-link">
                    <span class="menu-icon"><i class="fas fa-bookmark"></i></span>
                    <span class="menu-text">Constants</span>
                    <span class="menu-arrow"></span>
                </a>
                <ul class="sub-menu hidden">
                    <?php
                    $constants_res = mysqli_query($con, "SELECT * FROM d_constants ORDER BY name ASC");
                    if ($constants_res) {
                        while ($c = $constants_res->fetch_assoc()) {
                            echo '<li class="menu-item"><a href="' . $redirect_link . 'pages/constants/constant.php?id=' . $c['id'] . '" class="menu-link"><span class="menu-text">' . htmlspecialchars($c['name']) . '</span></a></li>';
                        }
                    }
                    ?>
                </ul>
            </li>
            <?php endif; ?>

            <?php if ($backup) : ?>
            <li class="menu-item">
                <a href="<?= $redirect_link ?>newbackup.php" class="menu-link">
                    <span class="menu-icon"><i class="fas fa-database"></i></span>
                    <span class="menu-text">Backup</span>
                </a>
            </li>
            <?php endif; ?>

            <?php if ($email) : ?>
            <li class="menu-item">
                <a href="<?= $redirect_link ?>pages/email/email.php" class="menu-link">
                    <span class="menu-icon"><i class="fas fa-envelope"></i></span>
                    <span class="menu-text">Email</span>
                </a>
            </li>
            <?php endif; ?>

            <!-- Users — always visible for masteradmin -->
            <li class="menu-item">
                <a href="<?= $redirect_link ?>pages/account/users.php" class="menu-link">
                    <span class="menu-icon"><i class="fas fa-users"></i></span>
                    <span class="menu-text">Users</span>
                </a>
            </li>

            <!-- Settings — masteradmin or granted permission -->
            <?php if ($is_master || $settings_perm) : ?>
            <li class="menu-item settings-item">
                <a href="<?= $redirect_link ?>pages/settings/index.php" class="menu-link">
                    <span class="menu-icon"><i class="fas fa-gear"></i></span>
                    <span class="menu-text">Settings</span>
                </a>
            </li>
            <?php endif; ?>

            <?php endif; /* end is_master admin section */ ?>

        </ul>
    </div><!-- end srcollbar -->

    <!-- ── User strip (bottom of sidebar) ──────────────────── -->
    <div class="sidenav-user-strip">
        <img src="<?= $redirect_link ?>assets/images/users/1.jpg" alt="<?= htmlspecialchars($user_name) ?>">
        <div class="user-info">
            <div class="user-name"><?= htmlspecialchars($user_name) ?></div>
            <div class="user-role"><?= htmlspecialchars($privileged ?: 'user') ?></div>
        </div>
        <a href="<?= $redirect_link ?>logout.php" title="Sign out" style="color:rgba(255,255,255,0.3);font-size:0.85rem;transition:color 0.15s;" onmouseover="this.style.color='#f87171'" onmouseout="this.style.color='rgba(255,255,255,0.3)'">
            <i class="fas fa-right-from-bracket"></i>
        </a>
    </div>

</div>
<!-- Sidenav End -->

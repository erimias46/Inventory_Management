<?php
include $redirect_link . 'include/db.php';

/* ── App settings (logo, company name, module toggles) ─────────── */
if (!function_exists('get_app_settings')) {
    function get_app_settings($con) {
        static $cache = null;
        if ($cache !== null) return $cache;
        mysqli_query($con, "CREATE TABLE IF NOT EXISTS `app_settings` (
            `key` varchar(100) NOT NULL,
            `value` text NOT NULL,
            PRIMARY KEY (`key`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        $cache = [];
        $res = mysqli_query($con, "SELECT `key`, `value` FROM `app_settings`");
        if ($res) while ($r = mysqli_fetch_assoc($res)) $cache[$r['key']] = $r['value'];
        $defaults = ['company_name'=>'Stock Hub','company_logo'=>'','currency'=>'ETB','store_name'=>'Stock Hub'];
        $cache = array_merge($defaults, $cache);
        return $cache;
    }
}

$app_settings = get_app_settings($con);
$company_name = htmlspecialchars($app_settings['company_name']);
$company_logo = $app_settings['company_logo'];
$logo_src     = !empty($company_logo) && file_exists($redirect_link . $company_logo)
                  ? $redirect_link . $company_logo
                  : $redirect_link . 'assets/images/zuqemens.JPG';

/* ── Current user + permissions ─────────────────────────────────── */
$id     = (int)$_SESSION['user_id'];
$result = mysqli_query($con, "SELECT * FROM user WHERE user_id = $id");

$user_id = $user_name = $privileged = '';
$module  = [];

if ($result && $row = mysqli_fetch_assoc($result)) {
    $user_id    = $row['user_id'];
    $user_name  = $row['user_name'];
    $privileged = $row['previledge'];
    $module     = json_decode($row['module'], true) ?: [];
    mysqli_free_result($result);
}

$perm = function(string $key) use ($module): bool {
    return !empty($module[$key]) && $module[$key] == 1;
};

/* Global permissions (non-category) */
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

/* ── Load active categories from DB ─────────────────────────────── */
$all_categories = stock_get_categories($con);

/* Build per-category permission + module data */
foreach ($all_categories as &$cat) {
    $s = $cat['slug'];
    $cat['mod_enabled']  = ($app_settings['mod_' . $s] ?? '1') === '1';
    $cat['can_view']     = $perm('view'        . $s);
    $cat['can_add']      = $perm('add'         . $s);
    $cat['can_sale']     = $perm('sale'        . $s);
    $cat['can_log']      = $perm('log'         . $s);
    $cat['can_verify']   = $perm('verify'      . $s);
    $cat['can_delivery'] = $perm('deliverysale'. $s);
    $cat['links']        = stock_category_nav_links($cat, $redirect_link);
}
unset($cat);

$show_inventory = false;
foreach ($all_categories as $cat) {
    if ($cat['mod_enabled'] && $cat['can_view']) { $show_inventory = true; break; }
}
?>

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

    <button id="button-hover-toggle" class="absolute top-5 end-2 rounded-full p-1.5">
        <span class="sr-only">Toggle Menu</span>
        <i class="mgc_round_line text-xl"></i>
    </button>

    <div class="srcollbar" data-simplebar>
        <ul class="menu" data-fc-type="accordion">

            <?php if ($is_master) : ?>
            <li class="menu-title menu-section-label">Overview</li>
            <li class="menu-item">
                <a href="<?= $redirect_link ?>index2.php" class="menu-link">
                    <span class="menu-icon"><i class="fas fa-chart-line"></i></span>
                    <span class="menu-text">Dashboard</span>
                </a>
            </li>
            <?php endif; ?>

            <!-- ── INVENTORY ──────────────────────────────── -->
            <?php if ($show_inventory) : ?>
            <li class="menu-title menu-section-label">Inventory</li>

            <?php foreach ($all_categories as $cat) :
                if (!$cat['mod_enabled'] || !$cat['can_view']) continue;
                $links = $cat['links'];
            ?>
            <li class="menu-item">
                <a href="javascript:void(0)" data-fc-type="collapse" class="menu-link">
                    <span class="menu-icon"><i class="<?= htmlspecialchars($cat['icon']) ?>"></i></span>
                    <span class="menu-text"><?= htmlspecialchars($cat['label']) ?></span>
                    <span class="menu-arrow"></span>
                </a>
                <ul class="sub-menu hidden">
                    <?php if ($cat['can_add']) : ?>
                    <li class="menu-item"><a href="<?= htmlspecialchars($links['add']) ?>" class="menu-link"><span class="menu-text">Add <?= htmlspecialchars($cat['label']) ?></span></a></li>
                    <?php endif; ?>
                    <li class="menu-item"><a href="<?= htmlspecialchars($links['all']) ?>" class="menu-link"><span class="menu-text">All <?= htmlspecialchars($cat['label']) ?></span></a></li>
                    <li class="menu-item"><a href="<?= htmlspecialchars($links['type']) ?>" class="menu-link"><span class="menu-text">Type <?= htmlspecialchars($cat['label']) ?></span></a></li>
                    <?php if ($cat['can_sale']) : ?>
                    <li class="menu-item"><a href="<?= htmlspecialchars($links['sale']) ?>" class="menu-link"><span class="menu-text">Sale <?= htmlspecialchars($cat['label']) ?></span></a></li>
                    <?php endif; ?>
                    <?php if ($cat['can_log']) : ?>
                    <li class="menu-item"><a href="<?= htmlspecialchars($links['log']) ?>" class="menu-link"><span class="menu-text">Stock Log</span></a></li>
                    <?php endif; ?>
                    <?php if ($cat['can_verify']) : ?>
                    <li class="menu-item"><a href="<?= htmlspecialchars($links['verify']) ?>" class="menu-link"><span class="menu-text">Verify</span></a></li>
                    <?php endif; ?>
                    <?php if ($cat['can_log']) : ?>
                    <li class="menu-item"><a href="<?= htmlspecialchars($links['exchange']) ?>" class="menu-link"><span class="menu-text">Exchange Log</span></a></li>
                    <li class="menu-item"><a href="<?= htmlspecialchars($links['refund']) ?>" class="menu-link"><span class="menu-text">Refund Log</span></a></li>
                    <?php endif; ?>
                    <?php if ($cat['can_delivery']) : ?>
                    <li class="menu-item"><a href="<?= htmlspecialchars($links['delivery']) ?>" class="menu-link"><span class="menu-text">Delivery</span></a></li>
                    <?php endif; ?>
                </ul>
            </li>
            <?php endforeach; ?>
            <?php endif; ?>

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
                    $cr = mysqli_query($con, "SELECT * FROM d_constants ORDER BY name ASC");
                    if ($cr) {
                        while ($c = $cr->fetch_assoc()) {
                            echo '<li class="menu-item"><a href="' . $redirect_link . 'pages/constants/constant.php?id=' . $c['id'] . '" class="menu-link"><span class="menu-text">' . htmlspecialchars($c['name']) . '</span></a></li>';
                        }
                    }
                    ?>
                </ul>
            </li>
            <?php endif; ?>

            <!-- Categories Management -->
            <li class="menu-item">
                <a href="<?= $redirect_link ?>pages/settings/categories.php" class="menu-link">
                    <span class="menu-icon"><i class="fas fa-layer-group"></i></span>
                    <span class="menu-text">Categories</span>
                </a>
            </li>

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

            <li class="menu-item">
                <a href="<?= $redirect_link ?>pages/account/users.php" class="menu-link">
                    <span class="menu-icon"><i class="fas fa-users"></i></span>
                    <span class="menu-text">Users</span>
                </a>
            </li>

            <?php if ($is_master || $settings_perm) : ?>
            <li class="menu-item settings-item">
                <a href="<?= $redirect_link ?>pages/settings/index.php" class="menu-link">
                    <span class="menu-icon"><i class="fas fa-gear"></i></span>
                    <span class="menu-text">Settings</span>
                </a>
            </li>
            <?php endif; ?>
            <?php endif; ?>

        </ul>
    </div>

    <!-- User strip -->
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

<?php
/* ── Load settings for dynamic logo/company name ───────────── */
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
        if ($res) { while ($r = mysqli_fetch_assoc($res)) { $cache[$r['key']] = $r['value']; } }
        $cache = array_merge(['company_name'=>'Zuqemens','company_logo'=>'','store_name'=>'Stock Hub'], $cache);
        return $cache;
    }
}
include_once $redirect_link . 'include/db.php';
$_tb_settings    = get_app_settings($con);
$_tb_logo        = $_tb_settings['company_logo'];
$_tb_logo_src    = (!empty($_tb_logo) && file_exists($redirect_link . $_tb_logo))
                    ? $redirect_link . $_tb_logo
                    : $redirect_link . 'assets/images/zuqemens.JPG';
$_tb_company     = htmlspecialchars($_tb_settings['company_name']);
$_tb_username    = htmlspecialchars($_SESSION['username'] ?? 'Admin');
?>
<!-- Topbar Start -->
<header class="app-header flex items-center px-5 gap-3">

    <!-- Menu Toggle -->
    <button id="button-toggle-menu" class="nav-link p-2" title="Toggle Menu">
        <span class="sr-only">Menu Toggle Button</span>
        <span class="flex items-center justify-center h-6 w-6">
            <i class="mgc_menu_line text-xl"></i>
        </span>
    </button>

    <!-- Brand Logo (dynamic) -->
    <a href="<?= $redirect_link ?>index.php" class="logo-box flex items-center gap-2.5" style="text-decoration:none;">
        <div class="logo-light flex items-center gap-2">
            <img src="<?= $_tb_logo_src ?>"
                 class="logo-lg h-8 w-8 rounded-lg object-cover shadow-sm" alt="<?= $_tb_company ?>">
            <span class="font-bold text-sm text-gray-800 dark:text-white tracking-tight hidden md:block"><?= $_tb_company ?></span>
        </div>
        <div class="logo-dark flex items-center gap-2">
            <img src="<?= $_tb_logo_src ?>"
                 class="logo-sm h-8 w-8 rounded-lg object-cover shadow-sm" alt="<?= $_tb_company ?>">
            <span class="font-bold text-sm text-white tracking-tight hidden md:block"><?= $_tb_company ?></span>
        </div>
    </a>

    <div class="flex-1"></div>

    <!-- Settings quick-link (masteradmin) -->
    <?php if (($_SESSION['username'] ?? '') === 'masteradmin') : ?>
    <a href="<?= $redirect_link ?>pages/settings/index.php" class="nav-link p-2" title="Settings" style="border-radius:10px;">
        <span class="flex items-center justify-center h-7 w-7">
            <i class="fas fa-gear" style="font-size:0.95rem;"></i>
        </span>
    </a>
    <?php endif; ?>

    <!-- Dark / Light Toggle -->
    <button id="light-dark-mode" type="button" class="nav-link p-2" title="Toggle Theme">
        <span class="sr-only">Toggle Light/Dark Mode</span>
        <span class="flex items-center justify-center h-7 w-7">
            <i class="mgc_moon_line text-xl"></i>
        </span>
    </button>

    <!-- Profile Dropdown -->
    <div class="relative">
        <button data-fc-type="dropdown" data-fc-placement="bottom-end" type="button"
                class="nav-link flex items-center gap-2 px-2 py-1.5 rounded-xl">
            <img src="<?= $redirect_link ?>assets/images/users/1.jpg"
                 alt="<?= $_tb_username ?>"
                 class="rounded-full h-9 w-9 object-cover">
            <div class="hidden sm:block text-left">
                <p class="text-xs font-semibold text-gray-800 dark:text-gray-200 leading-tight"><?= $_tb_username ?></p>
                <p class="text-xs text-gray-400"><?= $_tb_company ?></p>
            </div>
            <i class="mgc_down_line text-xs text-gray-400 hidden sm:block"></i>
        </button>

        <div class="fc-dropdown fc-dropdown-open:opacity-100 hidden opacity-0 w-56 z-50
                    transition-[margin,opacity] duration-300 mt-2
                    bg-white shadow-2xl border rounded-2xl p-2
                    border-gray-100 dark:border-gray-700 dark:bg-gray-800">

            <!-- User header in dropdown -->
            <div class="px-3 py-2.5 mb-1 border-b border-gray-100 dark:border-gray-700">
                <div class="flex items-center gap-2.5">
                    <div style="width:34px;height:34px;border-radius:10px;background:linear-gradient(135deg,#7c3aed,#a855f7);display:flex;align-items:center;justify-content:center;color:white;font-weight:800;font-size:0.82rem;flex-shrink:0;">
                        <?= strtoupper(substr($_tb_username, 0, 2)) ?>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-800 dark:text-gray-200"><?= $_tb_username ?></p>
                        <p class="text-xs text-gray-400 mt-0.5"><?= $_tb_company ?></p>
                    </div>
                </div>
            </div>

            <a class="flex items-center gap-2.5 py-2 px-3 rounded-xl text-sm text-gray-700
                       hover:bg-purple-50 hover:text-purple-600 dark:text-gray-400
                       dark:hover:bg-gray-700 transition-colors"
               href="<?= $redirect_link ?>pages/account/profile.php">
                <i class="fas fa-user text-sm"></i>
                <span>My Profile</span>
            </a>

            <?php if (($_SESSION['username'] ?? '') === 'masteradmin') : ?>
            <a class="flex items-center gap-2.5 py-2 px-3 rounded-xl text-sm text-gray-700
                       hover:bg-purple-50 hover:text-purple-600 dark:text-gray-400
                       dark:hover:bg-gray-700 transition-colors"
               href="<?= $redirect_link ?>pages/settings/index.php">
                <i class="fas fa-gear text-sm"></i>
                <span>System Settings</span>
            </a>
            <?php endif; ?>

            <hr class="my-1.5 border-gray-100 dark:border-gray-700">

            <a class="flex items-center gap-2.5 py-2 px-3 rounded-xl text-sm text-red-500
                       hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors font-medium"
               href="<?= $redirect_link ?>logout.php">
                <i class="fas fa-right-from-bracket text-sm"></i>
                <span>Sign Out</span>
            </a>
        </div>
    </div>

</header>
<!-- Topbar End -->

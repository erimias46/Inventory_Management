<?php
$cur_page = basename($_SERVER['PHP_SELF']);
$links = [
    'index.php'       => ['Dashboard', 'fas fa-chart-pie'],
    'shops.php'       => ['Shops',     'fas fa-store'],
    'create_shop.php' => ['New Shop',  'fas fa-plus'],
];
?>
<nav class="sa-nav">
    <a href="index.php" class="sa-nav-brand">
        <div class="brand-icon"><i class="fas fa-shield-halved"></i></div>
        Stock Hub Admin
    </a>
    <div class="sa-nav-links">
        <?php foreach ($links as $href => [$label, $icon]) : ?>
        <a href="<?= $href ?>" class="sa-nav-link <?= ($cur_page === $href) ? 'active' : '' ?>">
            <i class="<?= $icon ?>"></i> <?= $label ?>
        </a>
        <?php endforeach; ?>
    </div>
    <div class="sa-nav-right">
        <span class="sa-user"><i class="fas fa-user-shield" style="margin-right:5px;color:#a78bfa"></i><?= htmlspecialchars($_SESSION['superadmin_username'] ?? '') ?></span>
        <a href="logout.php" class="sa-logout"><i class="fas fa-right-from-bracket"></i> Logout</a>
    </div>
</nav>

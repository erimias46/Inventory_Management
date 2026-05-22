<?php
require_once __DIR__ . '/_guard.php';
require_once __DIR__ . '/../../include/db_master.php';

$master = stock_master_connect();
if (!$master) { die('Cannot connect to master DB. <a href="../../tools/run_setup.php">Run Setup</a>'); }

/* ── Stats ─────────────────────────────────────────────────────── */
$total_shops  = mysqli_fetch_assoc(mysqli_query($master, "SELECT COUNT(*) c FROM shops"))['c'];
$active_shops = mysqli_fetch_assoc(mysqli_query($master, "SELECT COUNT(*) c FROM shops WHERE active=1"))['c'];
$trial_shops  = mysqli_fetch_assoc(mysqli_query($master, "SELECT COUNT(*) c FROM shops WHERE plan='trial'"))['c'];
$pro_shops    = mysqli_fetch_assoc(mysqli_query($master, "SELECT COUNT(*) c FROM shops WHERE plan='pro'"))['c'];

/* ── Recent shops ───────────────────────────────────────────────── */
$shops_res = mysqli_query($master, "SELECT * FROM shops ORDER BY created_at DESC LIMIT 6");
$recent_shops = [];
while ($r = mysqli_fetch_assoc($shops_res)) {
    /* count users in shop DB */
    $scon = mysqli_connect('localhost','root','root', $r['db_name']);
    $r['user_count'] = 0;
    $r['cat_count']  = 0;
    if ($scon) {
        $ures = mysqli_query($scon, "SELECT COUNT(*) c FROM user");
        if ($ures) $r['user_count'] = mysqli_fetch_assoc($ures)['c'];
        if (mysqli_query($scon, "SHOW TABLES LIKE 'categories'")) {
            $cres = mysqli_query($scon, "SELECT COUNT(*) c FROM categories WHERE enabled=1");
            if ($cres) $r['cat_count'] = mysqli_fetch_assoc($cres)['c'];
        }
        mysqli_close($scon);
    }
    $recent_shops[] = $r;
}
mysqli_close($master);

$admin_user = htmlspecialchars($_SESSION['superadmin_username'] ?? 'superadmin');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Dashboard — Stock Hub Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
<?php include __DIR__ . '/_styles.php'; ?>
</style>
</head>
<body>
<?php include __DIR__ . '/_nav.php'; ?>

<div class="sa-wrap">
    <!-- Page header -->
    <div class="page-header">
        <div>
            <h1 class="page-title">Dashboard</h1>
            <p class="page-sub">Welcome back, <?= $admin_user ?>. Here's what's happening across your platform.</p>
        </div>
        <a href="create_shop.php" class="btn-primary"><i class="fas fa-plus"></i> New Shop</a>
    </div>

    <!-- Stats grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(88,28,220,0.15);color:#a78bfa"><i class="fas fa-store"></i></div>
            <div>
                <div class="stat-value"><?= $total_shops ?></div>
                <div class="stat-label">Total Shops</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(16,185,129,0.15);color:#34d399"><i class="fas fa-circle-check"></i></div>
            <div>
                <div class="stat-value"><?= $active_shops ?></div>
                <div class="stat-label">Active Shops</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(245,158,11,0.15);color:#fbbf24"><i class="fas fa-clock"></i></div>
            <div>
                <div class="stat-value"><?= $trial_shops ?></div>
                <div class="stat-label">Trial Shops</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(236,72,153,0.15);color:#f472b6"><i class="fas fa-crown"></i></div>
            <div>
                <div class="stat-value"><?= $pro_shops ?></div>
                <div class="stat-label">Pro Shops</div>
            </div>
        </div>
    </div>

    <!-- Recent shops -->
    <div class="section-header">
        <h2 class="section-title">Recent Shops</h2>
        <a href="shops.php" class="link-all">View all <i class="fas fa-arrow-right"></i></a>
    </div>

    <div class="shop-grid">
        <?php foreach ($recent_shops as $sh) :
            $plan_colors = ['trial'=>['bg'=>'rgba(245,158,11,0.15)','color'=>'#fbbf24'], 'basic'=>['bg'=>'rgba(59,130,246,0.15)','color'=>'#60a5fa'], 'pro'=>['bg'=>'rgba(16,185,129,0.15)','color'=>'#34d399']];
            $pc = $plan_colors[$sh['plan']] ?? $plan_colors['trial'];
        ?>
        <div class="shop-card">
            <div class="shop-card-top">
                <div class="shop-avatar"><?= strtoupper(substr($sh['name'],0,2)) ?></div>
                <div class="shop-meta">
                    <div class="shop-name"><?= htmlspecialchars($sh['name']) ?></div>
                    <div class="shop-slug">slug: <?= htmlspecialchars($sh['slug']) ?></div>
                </div>
                <div style="margin-left:auto">
                    <span class="badge" style="background:<?= $pc['bg'] ?>;color:<?= $pc['color'] ?>;border-color:<?= $pc['color'] ?>20"><?= strtoupper($sh['plan']) ?></span>
                </div>
            </div>
            <div class="shop-stats">
                <div class="shop-stat"><i class="fas fa-users" style="color:#a78bfa"></i> <?= $sh['user_count'] ?> users</div>
                <div class="shop-stat"><i class="fas fa-layer-group" style="color:#f472b6"></i> <?= $sh['cat_count'] ?> categories</div>
                <div class="shop-stat"><i class="fas fa-database" style="color:#60a5fa"></i> <?= htmlspecialchars($sh['db_name']) ?></div>
            </div>
            <div class="shop-status">
                <span class="status-dot <?= $sh['active'] ? 'active' : 'inactive' ?>"></span>
                <span><?= $sh['active'] ? 'Active' : 'Inactive' ?></span>
            </div>
            <div class="shop-actions">
                <a href="edit_shop.php?id=<?= $sh['id'] ?>" class="shop-btn"><i class="fas fa-pen"></i> Edit</a>
                <a href="view_shop.php?id=<?= $sh['id'] ?>" class="shop-btn"><i class="fas fa-eye"></i> View</a>
                <a href="action.php?action=toggle&id=<?= $sh['id'] ?>" class="shop-btn <?= $sh['active'] ? 'btn-danger' : 'btn-success' ?>">
                    <i class="fas <?= $sh['active'] ? 'fa-ban' : 'fa-check' ?>"></i> <?= $sh['active'] ? 'Disable' : 'Enable' ?>
                </a>
            </div>
        </div>
        <?php endforeach; ?>

        <?php if (empty($recent_shops)) : ?>
        <div class="empty-state">
            <i class="fas fa-store-slash"></i>
            <p>No shops yet. <a href="create_shop.php">Create your first shop</a></p>
        </div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>

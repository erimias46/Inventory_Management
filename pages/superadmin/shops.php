<?php
require_once __DIR__ . '/_guard.php';
require_once __DIR__ . '/../../include/db_master.php';

$master = stock_master_connect();
if (!$master) die('Cannot connect to master DB.');

$shops_res = mysqli_query($master, "SELECT * FROM shops ORDER BY created_at DESC");
$shops = [];
while ($r = mysqli_fetch_assoc($shops_res)) {
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
    $shops[] = $r;
}
mysqli_close($master);

$flash = $_GET['flash'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Shops — Stock Hub Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style><?php include __DIR__ . '/_styles.php'; ?></style>
</head>
<body>
<?php include __DIR__ . '/_nav.php'; ?>

<div class="sa-wrap">
    <div class="page-header">
        <div>
            <h1 class="page-title">All Shops</h1>
            <p class="page-sub"><?= count($shops) ?> shop<?= count($shops) !== 1 ? 's' : '' ?> registered on the platform</p>
        </div>
        <a href="create_shop.php" class="btn-primary"><i class="fas fa-plus"></i> New Shop</a>
    </div>

    <?php if ($flash === 'created') : ?>
    <div class="alert alert-success"><i class="fas fa-circle-check"></i> Shop created successfully!</div>
    <?php elseif ($flash === 'updated') : ?>
    <div class="alert alert-success"><i class="fas fa-circle-check"></i> Shop updated successfully!</div>
    <?php elseif ($flash === 'deleted') : ?>
    <div class="alert alert-error"><i class="fas fa-trash"></i> Shop deleted.</div>
    <?php endif; ?>

    <div class="sa-table-wrap">
        <table class="sa-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Shop</th>
                    <th>Database</th>
                    <th>Plan</th>
                    <th>Users</th>
                    <th>Categories</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($shops)) : ?>
            <tr><td colspan="9" style="text-align:center;padding:40px;color:rgba(255,255,255,0.2);">No shops yet. <a href="create_shop.php" style="color:#a78bfa">Create one</a></td></tr>
            <?php endif; ?>
            <?php foreach ($shops as $sh) :
                $plan_colors = ['trial'=>['rgba(245,158,11,0.15)','#fbbf24'], 'basic'=>['rgba(59,130,246,0.15)','#60a5fa'], 'pro'=>['rgba(16,185,129,0.15)','#34d399']];
                [$pbg,$pc] = $plan_colors[$sh['plan']] ?? $plan_colors['trial'];
            ?>
            <tr>
                <td class="mono"><?= $sh['id'] ?></td>
                <td>
                    <div class="name-cell"><?= htmlspecialchars($sh['name']) ?></div>
                    <div class="mono">slug: <?= htmlspecialchars($sh['slug']) ?></div>
                </td>
                <td class="mono"><?= htmlspecialchars($sh['db_name']) ?></td>
                <td><span class="badge" style="background:<?= $pbg ?>;color:<?= $pc ?>"><?= strtoupper($sh['plan']) ?></span></td>
                <td><?= $sh['user_count'] ?></td>
                <td><?= $sh['cat_count'] ?></td>
                <td>
                    <span class="badge" style="background:<?= $sh['active'] ? 'rgba(16,185,129,0.1)' : 'rgba(148,163,184,0.1)' ?>;color:<?= $sh['active'] ? '#34d399' : '#94a3b8' ?>">
                        <?= $sh['active'] ? 'Active' : 'Inactive' ?>
                    </span>
                </td>
                <td class="mono"><?= date('M d, Y', strtotime($sh['created_at'])) ?></td>
                <td>
                    <div style="display:flex;gap:6px;flex-wrap:wrap">
                        <a href="view_shop.php?id=<?= $sh['id'] ?>" class="shop-btn"><i class="fas fa-eye"></i></a>
                        <a href="edit_shop.php?id=<?= $sh['id'] ?>" class="shop-btn"><i class="fas fa-pen"></i></a>
                        <a href="action.php?action=toggle&id=<?= $sh['id'] ?>" class="shop-btn <?= $sh['active'] ? 'btn-danger' : 'btn-success' ?>">
                            <i class="fas <?= $sh['active'] ? 'fa-ban' : 'fa-check' ?>"></i>
                        </a>
                        <a href="action.php?action=delete&id=<?= $sh['id'] ?>" class="shop-btn btn-danger" onclick="return confirm('Delete <?= htmlspecialchars(addslashes($sh['name'])) ?>? This cannot be undone.')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>

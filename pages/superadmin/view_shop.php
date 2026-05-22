<?php
require_once __DIR__ . '/_guard.php';
require_once __DIR__ . '/../../include/db_master.php';

$master = stock_master_connect();
if (!$master) die('Cannot connect to master DB.');

$id   = (int)($_GET['id'] ?? 0);
$res  = mysqli_query($master, "SELECT * FROM shops WHERE id=$id LIMIT 1");
$shop = $res ? mysqli_fetch_assoc($res) : null;
if (!$shop) { header('Location: shops.php'); exit; }
mysqli_close($master);

/* Connect to shop DB */
$scon = mysqli_connect('localhost','root','root', $shop['db_name']);
$users = [];
$cats  = [];
$settings = [];

if ($scon) {
    $ur = mysqli_query($scon, "SELECT user_id, user_name, previledge FROM user ORDER BY user_id");
    if ($ur) while ($r = mysqli_fetch_assoc($ur)) $users[] = $r;

    $cr = mysqli_query($scon, "SHOW TABLES LIKE 'categories'");
    if ($cr && mysqli_num_rows($cr) > 0) {
        $cr2 = mysqli_query($scon, "SELECT * FROM categories ORDER BY sort_order");
        if ($cr2) while ($r = mysqli_fetch_assoc($cr2)) $cats[] = $r;
    }

    $sr = mysqli_query($scon, "SELECT `key`,`value` FROM app_settings");
    if ($sr) while ($r = mysqli_fetch_assoc($sr)) $settings[$r['key']] = $r['value'];

    mysqli_close($scon);
}

$plan_colors = ['trial'=>'#fbbf24','basic'=>'#60a5fa','pro'=>'#34d399'];
$pc = $plan_colors[$shop['plan']] ?? '#fbbf24';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= htmlspecialchars($shop['name']) ?> — Shop Details</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style><?php include __DIR__ . '/_styles.php'; ?></style>
</head>
<body>
<?php include __DIR__ . '/_nav.php'; ?>

<div class="sa-wrap">
    <div class="page-header">
        <div style="display:flex;align-items:center;gap:16px">
            <div class="shop-avatar" style="width:52px;height:52px;font-size:1.1rem"><?= strtoupper(substr($shop['name'],0,2)) ?></div>
            <div>
                <h1 class="page-title"><?= htmlspecialchars($shop['name']) ?></h1>
                <div style="display:flex;align-items:center;gap:10px;margin-top:4px">
                    <span class="badge" style="color:<?= $pc ?>;background:<?= $pc ?>22"><?= strtoupper($shop['plan']) ?></span>
                    <span class="badge" style="color:<?= $shop['active'] ? '#34d399' : '#94a3b8' ?>;background:<?= $shop['active'] ? '#34d39918' : '#94a3b818' ?>">
                        <?= $shop['active'] ? 'Active' : 'Inactive' ?>
                    </span>
                    <span style="font-size:0.78rem;color:rgba(255,255,255,0.3)">slug: <?= htmlspecialchars($shop['slug']) ?></span>
                </div>
            </div>
        </div>
        <div style="display:flex;gap:10px">
            <a href="edit_shop.php?id=<?= $id ?>" class="btn-primary"><i class="fas fa-pen"></i> Edit Shop</a>
            <a href="shops.php" class="btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
        </div>
    </div>

    <div class="detail-grid" style="margin-bottom:24px">
        <!-- Info -->
        <div class="detail-card">
            <h3>Shop Details</h3>
            <div class="detail-row"><span class="detail-key">Database</span><span class="detail-val"><?= htmlspecialchars($shop['db_name']) ?></span></div>
            <div class="detail-row"><span class="detail-key">Admin User</span><span class="detail-val"><?= htmlspecialchars($shop['admin_username']) ?></span></div>
            <div class="detail-row"><span class="detail-key">Plan</span><span class="detail-val"><?= ucfirst($shop['plan']) ?></span></div>
            <div class="detail-row"><span class="detail-key">Created</span><span class="detail-val"><?= date('M d, Y H:i', strtotime($shop['created_at'])) ?></span></div>
            <div class="detail-row"><span class="detail-key">Currency</span><span class="detail-val"><?= htmlspecialchars($settings['currency'] ?? 'ETB') ?> (<?= htmlspecialchars($settings['currency_symbol'] ?? 'Br') ?>)</span></div>
        </div>

        <!-- Categories -->
        <div class="detail-card">
            <h3>Categories (<?= count(array_filter($cats, fn($c) => $c['enabled'])) ?> active)</h3>
            <?php if (empty($cats)) : ?>
            <div style="color:rgba(255,255,255,0.25);font-size:0.82rem">No categories configured</div>
            <?php else : ?>
            <div style="display:flex;flex-wrap:wrap;gap:8px;margin-top:6px">
                <?php foreach ($cats as $cat) : ?>
                <span class="badge" style="<?= $cat['enabled'] ? 'background:rgba(88,28,220,0.15);color:#a78bfa;border-color:#5b21b620' : 'background:rgba(148,163,184,0.08);color:#475569' ?>">
                    <i class="<?= htmlspecialchars($cat['icon']) ?>"></i>
                    <?= htmlspecialchars($cat['label']) ?>
                    <?php if (!$cat['enabled']) : ?><span style="opacity:0.5">(off)</span><?php endif; ?>
                </span>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Users -->
    <div class="section-header"><h2 class="section-title">Users (<?= count($users) ?>)</h2></div>
    <div class="sa-table-wrap">
        <table class="sa-table">
            <thead><tr><th>#</th><th>Username</th><th>Role</th></tr></thead>
            <tbody>
            <?php if (empty($users)) : ?>
            <tr><td colspan="3" style="text-align:center;padding:30px;color:rgba(255,255,255,0.2)">No users found</td></tr>
            <?php endif; ?>
            <?php foreach ($users as $u) : ?>
            <tr>
                <td class="mono"><?= $u['user_id'] ?></td>
                <td class="name-cell"><?= htmlspecialchars($u['user_name']) ?></td>
                <td><span class="badge" style="background:rgba(255,255,255,0.05);color:rgba(255,255,255,0.5)"><?= htmlspecialchars($u['previledge']) ?></span></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>

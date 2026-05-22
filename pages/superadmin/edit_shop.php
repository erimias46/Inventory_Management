<?php
require_once __DIR__ . '/_guard.php';
require_once __DIR__ . '/../../include/db_master.php';

$master = stock_master_connect();
if (!$master) die('Cannot connect to master DB.');

$id   = (int)($_GET['id'] ?? 0);
$shop = null;
if ($id > 0) {
    $res  = mysqli_query($master, "SELECT * FROM shops WHERE id=$id LIMIT 1");
    $shop = $res ? mysqli_fetch_assoc($res) : null;
}
if (!$shop) { header('Location: shops.php'); exit; }

/* Load shop's categories */
$shop_cats = [];
$scon = mysqli_connect('localhost','root','root', $shop['db_name']);
if ($scon) {
    $cr = mysqli_query($scon, "SHOW TABLES LIKE 'categories'");
    if ($cr && mysqli_num_rows($cr) > 0) {
        $cr2 = mysqli_query($scon, "SELECT slug,label,icon,enabled FROM categories ORDER BY sort_order");
        while ($r = mysqli_fetch_assoc($cr2)) $shop_cats[] = $r;
    }
    mysqli_close($scon);
}

$errors  = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_shop'])) {
    $name = trim($_POST['shop_name'] ?? '');
    $plan = in_array($_POST['plan'] ?? '', ['trial','basic','pro']) ? $_POST['plan'] : $shop['plan'];
    $active = isset($_POST['active']) ? 1 : 0;

    if ($name === '') $errors[] = 'Shop name is required';

    if (empty($errors)) {
        $sn = mysqli_real_escape_string($master, $name);
        mysqli_query($master, "UPDATE shops SET name='$sn', plan='$plan', active=$active WHERE id=$id");

        /* Update categories in shop DB */
        $scon2 = mysqli_connect('localhost','root','root', $shop['db_name']);
        if ($scon2) {
            $cr = mysqli_query($scon2, "SHOW TABLES LIKE 'categories'");
            if ($cr && mysqli_num_rows($cr) > 0) {
                $enabled_cats = $_POST['enabled_cats'] ?? [];
                $cr3 = mysqli_query($scon2, "SELECT slug FROM categories");
                while ($r = mysqli_fetch_assoc($cr3)) {
                    $s = $r['slug'];
                    $en = in_array($s, $enabled_cats) ? 1 : 0;
                    $safe_s = mysqli_real_escape_string($scon2, $s);
                    mysqli_query($scon2, "UPDATE categories SET enabled=$en WHERE slug='$safe_s'");
                    $val = $en ? '1' : '0';
                    mysqli_query($scon2, "INSERT INTO app_settings (`key`,`value`) VALUES ('mod_$safe_s','$val') ON DUPLICATE KEY UPDATE `value`='$val'");
                }
                /* Update company name */
                $sn2 = mysqli_real_escape_string($scon2, $name);
                mysqli_query($scon2, "INSERT INTO app_settings (`key`,`value`) VALUES ('company_name','$sn2') ON DUPLICATE KEY UPDATE `value`='$sn2'");
            }
            mysqli_close($scon2);
        }
        mysqli_close($master);
        header("Location: shops.php?flash=updated"); exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Edit Shop — Stock Hub Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style><?php include __DIR__ . '/_styles.php'; ?></style>
</head>
<body>
<?php include __DIR__ . '/_nav.php'; ?>

<div class="sa-wrap">
    <div class="page-header">
        <div>
            <h1 class="page-title">Edit Shop</h1>
            <p class="page-sub">Editing: <strong style="color:#a78bfa"><?= htmlspecialchars($shop['name']) ?></strong></p>
        </div>
        <div style="display:flex;gap:10px">
            <a href="view_shop.php?id=<?= $id ?>" class="btn-secondary"><i class="fas fa-eye"></i> View</a>
            <a href="shops.php" class="btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
        </div>
    </div>

    <?php if (!empty($errors)) : ?>
    <div class="alert alert-error" style="max-width:680px;margin-bottom:20px"><i class="fas fa-triangle-exclamation"></i> <?= implode(', ', array_map('htmlspecialchars', $errors)) ?></div>
    <?php endif; ?>

    <div class="form-card">
        <form method="POST">
            <div style="margin-bottom:8px;font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:rgba(255,255,255,0.25);">Shop Information</div>
            <div class="form-grid" style="margin-bottom:20px">
                <div class="field">
                    <label>Shop Name *</label>
                    <input name="shop_name" type="text" value="<?= htmlspecialchars($_POST['shop_name'] ?? $shop['name']) ?>" required>
                </div>
                <div class="field">
                    <label>Database</label>
                    <input type="text" value="<?= htmlspecialchars($shop['db_name']) ?>" disabled style="opacity:0.4;cursor:not-allowed">
                    <div class="field-hint">Cannot change database name after creation</div>
                </div>
                <div class="field">
                    <label>Plan</label>
                    <select name="plan">
                        <?php foreach (['trial','basic','pro'] as $p) : ?>
                        <option value="<?= $p ?>" <?= (($_POST['plan'] ?? $shop['plan']) === $p) ? 'selected' : '' ?>><?= ucfirst($p) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="field" style="display:flex;align-items:center;gap:14px;padding-top:28px">
                    <label style="display:flex;align-items:center;gap:10px;margin:0;cursor:pointer;text-transform:none;letter-spacing:0;font-size:0.9rem;color:rgba(255,255,255,0.7)">
                        <input type="checkbox" name="active" value="1" <?= ($shop['active'] ?? 1) ? 'checked' : '' ?> style="width:18px;height:18px;accent-color:#7c3aed">
                        Active (shop is accessible to users)
                    </label>
                </div>
            </div>

            <?php if (!empty($shop_cats)) : ?>
            <hr class="section-divider">
            <div style="margin-bottom:12px;font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:rgba(255,255,255,0.25);">Product Categories</div>
            <div class="cats-grid" style="margin-bottom:24px">
                <?php foreach ($shop_cats as $cat) : ?>
                <label class="cat-toggle <?= $cat['enabled'] ? 'checked' : '' ?>" id="ct_<?= $cat['slug'] ?>">
                    <input type="checkbox" name="enabled_cats[]" value="<?= htmlspecialchars($cat['slug']) ?>" <?= $cat['enabled'] ? 'checked' : '' ?> onchange="toggleCat('<?= $cat['slug'] ?>')">
                    <div class="cat-icon"><i class="<?= htmlspecialchars($cat['icon']) ?>"></i></div>
                    <span class="cat-label"><?= htmlspecialchars($cat['label']) ?></span>
                </label>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <div style="display:flex;gap:12px">
                <button type="submit" name="update_shop" class="btn-primary"><i class="fas fa-save"></i> Save Changes</button>
                <a href="shops.php" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
<script>
function toggleCat(slug) {
    const label = document.getElementById('ct_' + slug);
    label.classList.toggle('checked', label.querySelector('input').checked);
}
</script>
</body>
</html>

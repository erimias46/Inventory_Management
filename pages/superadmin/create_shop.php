<?php
require_once __DIR__ . '/_guard.php';
require_once __DIR__ . '/../../include/db_master.php';
require_once __DIR__ . '/../../tools/schema.php';

$errors  = [];
$success = '';

$default_categories = [
    ['jeans',    'Jeans',    'fas fa-scroll'],
    ['shoes',    'Shoes',    'fas fa-shoe-prints'],
    ['top',      'Top',      'fas fa-tshirt'],
    ['complete', 'Complete', 'fas fa-box-open'],
    ['accessory','Accessory','fas fa-gem'],
    ['wig',      'Wig',      'fas fa-hat-wizard'],
    ['cosmetics','Cosmetics','fas fa-spa'],
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_shop'])) {
    $name       = trim($_POST['shop_name']       ?? '');
    $slug       = preg_replace('/[^a-z0-9_]/', '', strtolower(trim($_POST['shop_slug'] ?? '')));
    $admin_user = trim($_POST['admin_username']  ?? '');
    $admin_pass = trim($_POST['admin_password']  ?? '');
    $plan       = in_array($_POST['plan'] ?? '', ['trial','basic','pro']) ? $_POST['plan'] : 'trial';
    $sel_cats   = $_POST['categories'] ?? [];

    if ($name === '')       $errors[] = 'Shop name is required';
    if ($slug === '')       $errors[] = 'Shop slug is required (auto-generated from name)';
    if (strlen($slug) < 3) $errors[] = 'Slug must be at least 3 characters';
    if ($admin_user === '') $errors[] = 'Admin username is required';
    if ($admin_pass === '') $errors[] = 'Admin password is required';

    if (empty($errors)) {
        $master = stock_master_connect();
        if (!$master) { $errors[] = 'Cannot connect to master DB'; goto show_form; }

        $safe_slug = mysqli_real_escape_string($master, $slug);
        $res = mysqli_query($master, "SELECT id FROM shops WHERE slug='$safe_slug'");
        if (mysqli_num_rows($res) > 0) $errors[] = "Slug '$slug' is already taken";

        if (empty($errors)) {
            $db_name = 'stock_' . $slug;

            /* 1. Create the shop database */
            if (!mysqli_query($master, "CREATE DATABASE IF NOT EXISTS `$db_name` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci")) {
                $errors[] = 'Failed to create database: ' . mysqli_error($master);
                mysqli_close($master); goto show_form;
            }

            /* 2. Connect to new DB and create tables */
            $scon = mysqli_connect('localhost','root','root', $db_name);
            if (!$scon) { $errors[] = 'Cannot connect to new shop DB'; mysqli_close($master); goto show_form; }
            mysqli_query($scon, "SET SESSION sql_mode = 'ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'");

            foreach (stock_schema_sql() as $sql) {
                if (!mysqli_query($scon, $sql)) {
                    error_log("Schema error in $db_name: " . mysqli_error($scon) . " | SQL: $sql");
                }
            }

            /* 3. Seed base data */
            foreach (stock_schema_seed_categories() as $sql) {
                mysqli_query($scon, $sql);
            }

            /* 4. Disable categories not selected */
            if (!empty($sel_cats)) {
                $safe_cats = implode("','", array_map(fn($c) => mysqli_real_escape_string($scon, $c), $sel_cats));
                mysqli_query($scon, "UPDATE categories SET enabled=0 WHERE slug NOT IN ('$safe_cats')");
                mysqli_query($scon, "UPDATE categories SET enabled=1 WHERE slug IN ('$safe_cats')");
                /* Also update app_settings module toggles */
                foreach ($default_categories as [$cs,,]) {
                    $val = in_array($cs, $sel_cats) ? '1' : '0';
                    $key = 'mod_' . $cs;
                    mysqli_query($scon, "INSERT INTO app_settings (`key`,`value`) VALUES ('$key','$val') ON DUPLICATE KEY UPDATE `value`='$val'");
                }
            }

            /* 5. Set company name */
            $safe_name = mysqli_real_escape_string($scon, $name);
            mysqli_query($scon, "INSERT INTO app_settings (`key`,`value`) VALUES ('company_name','$safe_name') ON DUPLICATE KEY UPDATE `value`='$safe_name'");
            mysqli_query($scon, "INSERT INTO app_settings (`key`,`value`) VALUES ('store_name','$safe_name') ON DUPLICATE KEY UPDATE `value`='$safe_name'");

            /* 6. Create masteradmin user with full permissions */
            $perms = [];
            $perm_prefixes = ['view','add','edit','delete','verify','sale','editsale','deletesale','refundsale','exchangesale','deliverysale','log'];
            $all_slugs = ['jeans','shoes','top','complete','accessory','wig','cosmetics'];
            foreach ($perm_prefixes as $pfx) {
                foreach ($all_slugs as $s) $perms[$pfx.$s] = 1;
            }
            $extra = ['constant','backup','email','user','editbuyprice','settings','addproduct','fullsale','allsale','logsale','searchproduct','deliverysale','producttypes','productsin','verifyproducts'];
            foreach ($extra as $e) $perms[$e] = 1;
            $perms_json = mysqli_real_escape_string($scon, json_encode($perms));

            $safe_admin = mysqli_real_escape_string($scon, $admin_user);
            $safe_pass  = mysqli_real_escape_string($scon, $admin_pass);
            mysqli_query($scon, "INSERT INTO user (user_name, password, previledge, module)
                                 VALUES ('$safe_admin','$safe_pass','administrator','$perms_json')");

            mysqli_close($scon);

            /* 7. Register shop in master */
            $safe_name2  = mysqli_real_escape_string($master, $name);
            $safe_slug2  = mysqli_real_escape_string($master, $slug);
            $safe_db     = mysqli_real_escape_string($master, $db_name);
            $safe_admin2 = mysqli_real_escape_string($master, $admin_user);
            mysqli_query($master, "INSERT INTO shops (name,slug,db_name,admin_username,plan,active)
                                   VALUES ('$safe_name2','$safe_slug2','$safe_db','$safe_admin2','$plan',1)");
            mysqli_close($master);

            header("Location: shops.php?flash=created");
            exit;
        }
        if ($master) mysqli_close($master);
    }
}

show_form:
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Create Shop — Stock Hub Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style><?php include __DIR__ . '/_styles.php'; ?></style>
</head>
<body>
<?php include __DIR__ . '/_nav.php'; ?>

<div class="sa-wrap">
    <div class="page-header">
        <div>
            <h1 class="page-title">Create New Shop</h1>
            <p class="page-sub">Set up a new tenant shop with its own database and admin account.</p>
        </div>
        <a href="shops.php" class="btn-secondary"><i class="fas fa-arrow-left"></i> Back to Shops</a>
    </div>

    <?php if (!empty($errors)) : ?>
    <div class="alert alert-error" style="margin-bottom:20px">
        <i class="fas fa-triangle-exclamation"></i>
        <div><?= implode('<br>', array_map('htmlspecialchars', $errors)) ?></div>
    </div>
    <?php endif; ?>

    <div class="form-card">
        <form method="POST" id="create-form">

            <!-- Shop Info -->
            <div style="margin-bottom:8px;font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:rgba(255,255,255,0.25);">Shop Information</div>
            <div class="form-grid" style="margin-bottom:20px">
                <div class="field">
                    <label>Shop Name *</label>
                    <input name="shop_name" type="text" placeholder="e.g. Fashion Store" value="<?= htmlspecialchars($_POST['shop_name'] ?? '') ?>" required oninput="autoSlug(this)">
                    <div class="field-hint">The display name for this shop</div>
                </div>
                <div class="field">
                    <label>Shop Slug *</label>
                    <input name="shop_slug" id="shop_slug" type="text" placeholder="e.g. fashion_store" value="<?= htmlspecialchars($_POST['shop_slug'] ?? '') ?>" required pattern="[a-z0-9_]{3,}" title="Lowercase letters, numbers, underscores only (min 3 chars)">
                    <div class="field-hint">URL-safe identifier — auto-generated, editable. DB: stock_{slug}</div>
                </div>
                <div class="field">
                    <label>Plan *</label>
                    <select name="plan">
                        <?php foreach (['trial','basic','pro'] as $p) : ?>
                        <option value="<?= $p ?>" <?= (($_POST['plan'] ?? 'trial') === $p) ? 'selected' : '' ?>><?= ucfirst($p) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <hr class="section-divider">

            <!-- Admin account -->
            <div style="margin-bottom:8px;font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:rgba(255,255,255,0.25);">Admin Account</div>
            <div class="form-grid" style="margin-bottom:20px">
                <div class="field">
                    <label>Admin Username *</label>
                    <input name="admin_username" type="text" placeholder="masteradmin" value="<?= htmlspecialchars($_POST['admin_username'] ?? 'masteradmin') ?>" required>
                </div>
                <div class="field">
                    <label>Admin Password *</label>
                    <input name="admin_password" type="text" placeholder="Strong password" value="<?= htmlspecialchars($_POST['admin_password'] ?? '') ?>" required>
                    <div class="field-hint">Temporary password — shop owner should change it</div>
                </div>
            </div>

            <hr class="section-divider">

            <!-- Categories -->
            <div style="margin-bottom:12px;font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:rgba(255,255,255,0.25);">Product Categories</div>
            <p style="font-size:0.82rem;color:rgba(255,255,255,0.35);margin-bottom:14px;">Select which inventory categories to enable for this shop. More can be added later.</p>
            <div class="cats-grid" style="margin-bottom:24px">
                <?php foreach ($default_categories as [$slug, $label, $icon]) :
                    $selected_cats = $_POST['categories'] ?? array_column($default_categories, 0);
                    $is_checked = in_array($slug, $selected_cats);
                ?>
                <label class="cat-toggle <?= $is_checked ? 'checked' : '' ?>" id="ct_<?= $slug ?>">
                    <input type="checkbox" name="categories[]" value="<?= $slug ?>" <?= $is_checked ? 'checked' : '' ?> onchange="toggleCat('<?= $slug ?>')">
                    <div class="cat-icon"><i class="<?= $icon ?>"></i></div>
                    <span class="cat-label"><?= $label ?></span>
                </label>
                <?php endforeach; ?>
            </div>

            <div style="display:flex;gap:12px;align-items:center;flex-wrap:wrap">
                <button type="submit" name="create_shop" class="btn-primary" style="padding:12px 28px;font-size:0.9rem">
                    <i class="fas fa-store"></i> Create Shop
                </button>
                <a href="shops.php" class="btn-secondary">Cancel</a>
            </div>

        </form>
    </div>
</div>

<script>
function autoSlug(input) {
    const slug = document.getElementById('shop_slug');
    slug.value = input.value.toLowerCase().replace(/[^a-z0-9]+/g,'_').replace(/^_|_$/g,'');
}
function toggleCat(slug) {
    const label = document.getElementById('ct_' + slug);
    const cb = label.querySelector('input');
    label.classList.toggle('checked', cb.checked);
}
</script>
</body>
</html>

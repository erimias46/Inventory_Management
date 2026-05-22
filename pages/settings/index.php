<?php
$redirect_link = "../../";
$side_link     = "../../";
include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php';

/* ── RBAC: masteradmin only ─────────────────────────────────── */
$cur_id  = (int)$_SESSION['user_id'];
$cur_res = mysqli_query($con, "SELECT user_name, module FROM user WHERE user_id = $cur_id");
$cur_row = $cur_res ? mysqli_fetch_assoc($cur_res) : null;
$cur_user   = $cur_row ? $cur_row['user_name'] : '';
$cur_module = $cur_row ? (json_decode($cur_row['module'], true) ?: []) : [];
$has_settings = ($cur_user === 'masteradmin') || !empty($cur_module['settings']);

if (!$has_settings) {
    header("Location: {$redirect_link}index.php");
    exit;
}

/* ── Auto-create app_settings table ─────────────────────────── */
mysqli_query($con, "CREATE TABLE IF NOT EXISTS `app_settings` (
    `key`   varchar(100) NOT NULL,
    `value` text         NOT NULL,
    PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

/* ── Load current settings ──────────────────────────────────── */
$settings = [];
$res = mysqli_query($con, "SELECT `key`, `value` FROM `app_settings`");
if ($res) { while ($r = mysqli_fetch_assoc($res)) { $settings[$r['key']] = $r['value']; } }
$defaults = [
    'company_name'    => 'My Shop',
    'store_name'      => 'Stock Hub',
    'company_address' => '',
    'company_phone'   => '',
    'company_email'   => '',
    'currency'        => 'ETB',
    'currency_symbol' => 'Br',
    'company_logo'    => '',
    'receipt_footer'  => 'Thank you for your business!',
    'low_stock_alert' => '5',
    'timezone'        => 'Africa/Addis_Ababa',
];
/* Add dynamic module defaults from categories */
$_cat_res = mysqli_query($con, "SHOW TABLES LIKE 'categories'");
if ($_cat_res && mysqli_num_rows($_cat_res) > 0) {
    $_cats = mysqli_query($con, "SELECT slug FROM categories ORDER BY sort_order");
    if ($_cats) while ($_c = mysqli_fetch_assoc($_cats)) $defaults['mod_' . $_c['slug']] = '1';
} else {
    foreach (['jeans','shoes','top','complete','accessory','wig','cosmetics'] as $_s) $defaults['mod_' . $_s] = '1';
}
$s = array_merge($defaults, $settings);

/* ── Handle form submission ─────────────────────────────────── */
$success_msg = '';
$error_msg   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_settings'])) {
    $tab = $_POST['active_tab'] ?? 'company';

    /* Upsert helper */
    $upsert = function(string $key, string $value) use ($con) {
        $key   = mysqli_real_escape_string($con, $key);
        $value = mysqli_real_escape_string($con, $value);
        mysqli_query($con, "INSERT INTO app_settings (`key`,`value`) VALUES ('$key','$value')
                            ON DUPLICATE KEY UPDATE `value`='$value'");
    };

    if ($tab === 'company') {
        $upsert('company_name',    trim($_POST['company_name']    ?? ''));
        $upsert('store_name',      trim($_POST['store_name']      ?? ''));
        $upsert('company_address', trim($_POST['company_address'] ?? ''));
        $upsert('company_phone',   trim($_POST['company_phone']   ?? ''));
        $upsert('company_email',   trim($_POST['company_email']   ?? ''));
        $upsert('currency',        trim($_POST['currency']        ?? 'ETB'));
        $upsert('currency_symbol', trim($_POST['currency_symbol'] ?? 'Br'));
        $upsert('receipt_footer',  trim($_POST['receipt_footer']  ?? ''));
        $upsert('low_stock_alert', (int)($_POST['low_stock_alert'] ?? 5));
        $upsert('timezone',        trim($_POST['timezone']        ?? 'Africa/Addis_Ababa'));

        /* Logo upload */
        if (!empty($_FILES['company_logo']['name'])) {
            $file      = $_FILES['company_logo'];
            $allowed   = ['image/jpeg','image/png','image/gif','image/webp'];
            $max_size  = 2 * 1024 * 1024; // 2 MB

            if (!in_array($file['type'], $allowed)) {
                $error_msg = 'Logo must be a JPG, PNG, GIF, or WebP image.';
            } elseif ($file['size'] > $max_size) {
                $error_msg = 'Logo file is too large (max 2 MB).';
            } elseif ($file['error'] !== UPLOAD_ERR_OK) {
                $error_msg = 'Upload failed (code ' . $file['error'] . ').';
            } else {
                $ext      = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                $filename = 'logo_' . time() . '.' . $ext;
                $dest_dir = $redirect_link . 'assets/uploads/';
                $dest     = $dest_dir . $filename;

                if (!is_dir($dest_dir)) { mkdir($dest_dir, 0755, true); }

                /* Delete old logo file if it exists */
                $old_logo = $s['company_logo'];
                if (!empty($old_logo) && file_exists($redirect_link . $old_logo)) {
                    @unlink($redirect_link . $old_logo);
                }

                if (move_uploaded_file($file['tmp_name'], $dest)) {
                    $upsert('company_logo', 'assets/uploads/' . $filename);
                } else {
                    $error_msg = 'Could not save the uploaded file.';
                }
            }
        }
    }

    if ($tab === 'modules') {
        $cat_slugs_res = mysqli_query($con, "SELECT slug FROM categories ORDER BY sort_order");
        $all_mods = [];
        if ($cat_slugs_res) {
            while ($cr = mysqli_fetch_assoc($cat_slugs_res)) $all_mods[] = 'mod_' . $cr['slug'];
        }
        if (empty($all_mods)) {
            $all_mods = ['mod_jeans','mod_shoes','mod_top','mod_complete','mod_accessory','mod_wig','mod_cosmetics'];
        }
        foreach ($all_mods as $m) {
            $upsert($m, isset($_POST[$m]) ? '1' : '0');
            /* Also sync categories.enabled */
            $cslug = mysqli_real_escape_string($con, substr($m, 4));
            $en    = isset($_POST[$m]) ? 1 : 0;
            mysqli_query($con, "UPDATE categories SET enabled=$en WHERE slug='$cslug'");
        }
    }

    if (empty($error_msg)) {
        $success_msg = 'Settings saved successfully.';
        /* Reload */
        $settings = [];
        $res2 = mysqli_query($con, "SELECT `key`, `value` FROM `app_settings`");
        if ($res2) { while ($r = mysqli_fetch_assoc($res2)) { $settings[$r['key']] = $r['value']; } }
        $s = array_merge($defaults, $settings);
    }
}

/* ── Logo display src ───────────────────────────────────────── */
$logo_preview = !empty($s['company_logo']) && file_exists($redirect_link . $s['company_logo'])
    ? $redirect_link . $s['company_logo']
    : $redirect_link . 'assets/images/zuqemens.JPG';

/* ── Timezone list ──────────────────────────────────────────── */
$timezones = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php $title = 'Settings'; include $redirect_link . 'partials/title-meta.php'; ?>
    <?php include $redirect_link . 'partials/head-css.php'; ?>
    <style>
        /* ── Settings Page Styles ─────────────────────────── */
        .settings-tabs { display:flex; gap:4px; background:#f1f5f9; border-radius:14px; padding:5px; margin-bottom:24px; }
        .settings-tab  { flex:1; text-align:center; padding:9px 16px; border-radius:10px; font-size:0.82rem; font-weight:600; color:#64748b; cursor:pointer; transition:all 0.18s ease; border:none; background:transparent; }
        .settings-tab.active { background:#fff; color:#7c3aed; box-shadow:0 2px 12px rgba(0,0,0,0.08); }
        .settings-tab i { margin-right:6px; }
        .tab-panel { display:none; }
        .tab-panel.active { display:block; }
        [data-mode="dark"] .settings-tabs { background:rgba(255,255,255,0.05); }
        [data-mode="dark"] .settings-tab.active { background:rgba(255,255,255,0.1); color:#a78bfa; box-shadow:0 2px 12px rgba(0,0,0,0.3); }

        /* Logo upload zone */
        .logo-upload-zone {
            border: 2px dashed #e2e8f0;
            border-radius: 16px;
            padding: 28px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s ease;
            background: #f8fafc;
            position: relative;
        }
        .logo-upload-zone:hover { border-color: #7c3aed; background: rgba(124,58,237,0.04); }
        .logo-upload-zone input[type="file"] { position:absolute; inset:0; opacity:0; cursor:pointer; width:100%; height:100%; }
        [data-mode="dark"] .logo-upload-zone { background:rgba(255,255,255,0.03); border-color:rgba(255,255,255,0.1); }

        /* Section headings */
        .settings-section-title {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #94a3b8;
            margin-bottom: 16px;
            padding-bottom: 8px;
            border-bottom: 1px solid #f1f5f9;
        }
        [data-mode="dark"] .settings-section-title { border-bottom-color:rgba(255,255,255,0.06); }

        /* RBAC permission grid */
        .rbac-grid { display:grid; gap:0; }
        .rbac-row  { display:grid; grid-template-columns:1fr repeat(7,48px); align-items:center; padding:10px 0; border-bottom:1px solid #f8fafc; gap:4px; }
        .rbac-row:last-child { border-bottom:none; }
        .rbac-label { font-size:0.82rem; font-weight:500; color:#475569; }
        .rbac-cell  { display:flex; justify-content:center; }
        .rbac-header { display:grid; grid-template-columns:1fr repeat(7,48px); padding:8px 0; gap:4px; }
        .rbac-header-label { font-size:0.65rem; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:#94a3b8; text-align:center; }
        [data-mode="dark"] .rbac-row { border-bottom-color:rgba(255,255,255,0.04); }
        [data-mode="dark"] .rbac-label { color:#94a3b8; }

        /* Modern checkbox */
        .perm-check {
            width:18px; height:18px;
            border-radius:5px;
            border:2px solid #e2e8f0;
            cursor:pointer;
            accent-color:#7c3aed;
            transition:all 0.15s;
        }

        /* Module toggle switches */
        .mod-toggle { position:relative; display:inline-block; width:50px; height:26px; flex-shrink:0; }
        .mod-toggle input { opacity:0; width:0; height:0; position:absolute; }
        .toggle-slider {
            position:absolute; cursor:pointer; top:0; left:0; right:0; bottom:0;
            background:#e2e8f0; transition:.25s; border-radius:26px;
        }
        .toggle-slider:before {
            content:""; position:absolute; height:18px; width:18px;
            left:4px; bottom:4px; background:#fff; transition:.25s; border-radius:50%;
            box-shadow:0 2px 5px rgba(0,0,0,0.18);
        }
        .mod-toggle input:checked + .toggle-slider { background:#7c3aed; }
        .mod-toggle input:checked + .toggle-slider:before { transform:translateX(24px); }
        [data-mode="dark"] .toggle-slider { background:rgba(255,255,255,0.12); }

        /* Module cards grid */
        .modules-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(260px,1fr)); gap:14px; }
        .module-card {
            display:flex; align-items:center; gap:14px; padding:18px 20px;
            border:1.5px solid #f1f5f9; border-radius:16px; background:#fff;
            transition:all .2s ease; cursor:default;
        }
        .module-card.mod-off { opacity:.5; background:#f8fafc; filter:grayscale(.3); }
        .module-card-icon {
            width:46px; height:46px; border-radius:13px;
            display:flex; align-items:center; justify-content:center;
            flex-shrink:0; font-size:1.15rem; transition:all .2s;
        }
        .module-card-body { flex:1; min-width:0; }
        .module-card-name { font-size:.875rem; font-weight:700; color:#0f172a; line-height:1.2; }
        .module-card-desc { font-size:.72rem; color:#94a3b8; margin-top:3px; }
        [data-mode="dark"] .module-card { background:rgba(255,255,255,0.04); border-color:rgba(255,255,255,0.08); }
        [data-mode="dark"] .module-card.mod-off { background:rgba(255,255,255,0.02); }
        [data-mode="dark"] .module-card-name { color:#e2e8f0; }

        /* Alert messages */
        .alert-success { background:rgba(16,185,129,0.1); border:1px solid rgba(16,185,129,0.3); border-radius:12px; padding:12px 18px; color:#059669; font-size:0.875rem; display:flex; align-items:center; gap:10px; margin-bottom:20px; }
        .alert-error   { background:rgba(239,68,68,0.1);  border:1px solid rgba(239,68,68,0.3);  border-radius:12px; padding:12px 18px; color:#dc2626; font-size:0.875rem; display:flex; align-items:center; gap:10px; margin-bottom:20px; }
    </style>
</head>
<body>
<div class="flex wrapper">
    <?php include $redirect_link . 'partials/menu.php'; ?>

    <div class="page-content">
        <?php include $redirect_link . 'partials/topbar.php'; ?>

        <main class="flex-grow p-6">
            <?php $pagetitle = 'Settings'; $subtitle = 'System Configuration'; include $redirect_link . 'partials/page-title.php'; ?>

            <?php if ($success_msg) : ?>
            <div class="alert-success"><i class="fas fa-check-circle"></i><?= htmlspecialchars($success_msg) ?></div>
            <?php endif; ?>
            <?php if ($error_msg) : ?>
            <div class="alert-error"><i class="fas fa-exclamation-circle"></i><?= htmlspecialchars($error_msg) ?></div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="save_settings" value="1">
                <input type="hidden" name="active_tab" value="company" id="active_tab_input">

                <!-- ── Tab Navigation ────────────────────── -->
                <div class="settings-tabs">
                    <button type="button" class="settings-tab active" data-tab="company" onclick="switchTab('company',this)">
                        <i class="fas fa-building"></i>Company
                    </button>
                    <button type="button" class="settings-tab" data-tab="appearance" onclick="switchTab('appearance',this)">
                        <i class="fas fa-palette"></i>Appearance
                    </button>
                    <button type="button" class="settings-tab" data-tab="users" onclick="switchTab('users',this)">
                        <i class="fas fa-users"></i>Users & RBAC
                    </button>
                    <button type="button" class="settings-tab" data-tab="modules" onclick="switchTab('modules',this)">
                        <i class="fas fa-toggle-on"></i>Modules
                    </button>
                </div>

                <!-- ══════════════════════════════════════════
                     TAB 1: Company Settings
                ═══════════════════════════════════════════════ -->
                <div class="tab-panel active" id="tab-company">
                    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

                        <!-- Left: Logo Upload -->
                        <div class="xl:col-span-1">
                            <div class="card">
                                <div class="card-header"><span class="card-title">Company Logo</span></div>
                                <div class="card-body">
                                    <div class="logo-upload-zone" id="logoZone">
                                        <input type="file" name="company_logo" accept="image/*" onchange="previewLogo(this)">
                                        <img id="logoPreview" src="<?= htmlspecialchars($logo_preview) ?>"
                                             alt="Logo" style="width:100px;height:100px;object-fit:cover;border-radius:16px;margin:0 auto 14px;display:block;box-shadow:0 4px 16px rgba(0,0,0,0.15);">
                                        <p style="font-size:0.82rem;color:#64748b;font-weight:500;">Click or drag to upload</p>
                                        <p style="font-size:0.72rem;color:#94a3b8;margin-top:4px;">JPG, PNG, WebP · Max 2 MB</p>
                                    </div>
                                    <p style="font-size:0.72rem;color:#94a3b8;margin-top:10px;text-align:center;">
                                        This logo appears in the sidebar and login page.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Right: Company Details -->
                        <div class="xl:col-span-2">
                            <div class="card">
                                <div class="card-header"><span class="card-title">Company Details</span></div>
                                <div class="card-body">
                                    <p class="settings-section-title">Identity</p>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                                        <div>
                                            <label>Company Name</label>
                                            <input type="text" name="company_name" class="form-input" value="<?= htmlspecialchars($s['company_name']) ?>" required>
                                        </div>
                                        <div>
                                            <label>Store / Branch Name</label>
                                            <input type="text" name="store_name" class="form-input" value="<?= htmlspecialchars($s['store_name']) ?>">
                                        </div>
                                        <div class="md:col-span-2">
                                            <label>Address</label>
                                            <textarea name="company_address" class="form-input" rows="2" style="resize:vertical;"><?= htmlspecialchars($s['company_address']) ?></textarea>
                                        </div>
                                        <div>
                                            <label>Phone</label>
                                            <input type="text" name="company_phone" class="form-input" value="<?= htmlspecialchars($s['company_phone']) ?>" placeholder="+251 9xx xxx xxx">
                                        </div>
                                        <div>
                                            <label>Email</label>
                                            <input type="email" name="company_email" class="form-input" value="<?= htmlspecialchars($s['company_email']) ?>" placeholder="info@yourstore.com">
                                        </div>
                                    </div>

                                    <p class="settings-section-title">Currency & Locale</p>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-5">
                                        <div>
                                            <label>Currency Code</label>
                                            <input type="text" name="currency" class="form-input" value="<?= htmlspecialchars($s['currency']) ?>" placeholder="ETB" maxlength="8">
                                        </div>
                                        <div>
                                            <label>Currency Symbol</label>
                                            <input type="text" name="currency_symbol" class="form-input" value="<?= htmlspecialchars($s['currency_symbol']) ?>" placeholder="Br" maxlength="8">
                                        </div>
                                        <div>
                                            <label>Timezone</label>
                                            <select name="timezone" class="form-select">
                                                <?php foreach ($timezones as $tz) : ?>
                                                <option value="<?= htmlspecialchars($tz) ?>" <?= $s['timezone'] === $tz ? 'selected' : '' ?>><?= htmlspecialchars($tz) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <p class="settings-section-title">Receipt &amp; Alerts</p>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label>Receipt Footer Text</label>
                                            <input type="text" name="receipt_footer" class="form-input" value="<?= htmlspecialchars($s['receipt_footer']) ?>">
                                        </div>
                                        <div>
                                            <label>Low Stock Alert Threshold</label>
                                            <input type="number" name="low_stock_alert" class="form-input" value="<?= (int)$s['low_stock_alert'] ?>" min="1" max="999">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Save button -->
                    <div class="flex justify-end mt-5">
                        <button type="submit" class="btn btn-primary" onclick="document.getElementById('active_tab_input').value='company'">
                            <i class="fas fa-save" style="margin-right:8px;"></i>Save Company Settings
                        </button>
                    </div>
                </div>

                <!-- ══════════════════════════════════════════
                     TAB 2: Appearance
                ═══════════════════════════════════════════════ -->
                <div class="tab-panel" id="tab-appearance">
                    <div class="card">
                        <div class="card-header"><span class="card-title">Theme &amp; Appearance</span></div>
                        <div class="card-body">
                            <p class="settings-section-title">Preview — current configuration</p>

                            <!-- Live preview of branding -->
                            <div style="background:linear-gradient(170deg,#0f172a,#1a0e40);border-radius:20px;padding:28px;display:flex;align-items:center;gap:18px;max-width:380px;margin-bottom:24px;">
                                <img src="<?= htmlspecialchars($logo_preview) ?>" style="width:52px;height:52px;border-radius:14px;object-fit:cover;box-shadow:0 4px 14px rgba(0,0,0,0.35);">
                                <div>
                                    <div style="font-size:1rem;font-weight:800;color:#fff;"><?= htmlspecialchars($s['company_name']) ?></div>
                                    <div style="font-size:0.68rem;color:rgba(255,255,255,0.35);text-transform:uppercase;letter-spacing:0.1em;margin-top:2px;"><?= htmlspecialchars($s['store_name']) ?></div>
                                </div>
                            </div>

                            <div style="background:rgba(124,58,237,0.07);border:1px solid rgba(124,58,237,0.2);border-radius:12px;padding:14px 18px;font-size:0.85rem;color:#7c3aed;">
                                <i class="fas fa-info-circle" style="margin-right:8px;"></i>
                                The sidebar uses a deep navy + purple gradient. Update the company name and logo in the <strong>Company</strong> tab to see changes here instantly.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ══════════════════════════════════════════
                     TAB 3: Users & RBAC
                ═══════════════════════════════════════════════ -->
                <div class="tab-panel" id="tab-users">
                    <div class="card">
                        <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;">
                            <span class="card-title">User Accounts &amp; Permissions</span>
                            <a href="<?= $redirect_link ?>pages/account/users.php" class="btn btn-primary" style="font-size:0.78rem;padding:7px 14px;">
                                <i class="fas fa-user-plus" style="margin-right:6px;"></i>Manage Users
                            </a>
                        </div>
                        <div class="card-body">
                            <p class="settings-section-title">Registered Users</p>

                            <?php
                            $users_res = mysqli_query($con, "SELECT user_id, user_name, previledge FROM user ORDER BY user_id ASC");
                            if ($users_res && mysqli_num_rows($users_res) > 0) :
                            ?>
                            <div style="overflow-x:auto;">
                                <table style="width:100%;border-collapse:separate;border-spacing:0;">
                                    <thead>
                                        <tr>
                                            <th style="padding:10px 14px;text-align:left;font-size:0.68rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:#94a3b8;background:#f8fafc;border-bottom:2px solid #e2e8f0;">#</th>
                                            <th style="padding:10px 14px;text-align:left;font-size:0.68rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:#94a3b8;background:#f8fafc;border-bottom:2px solid #e2e8f0;">Username</th>
                                            <th style="padding:10px 14px;text-align:left;font-size:0.68rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:#94a3b8;background:#f8fafc;border-bottom:2px solid #e2e8f0;">Role</th>
                                            <th style="padding:10px 14px;text-align:left;font-size:0.68rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:#94a3b8;background:#f8fafc;border-bottom:2px solid #e2e8f0;">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $u_idx = 1;
                                    while ($u = mysqli_fetch_assoc($users_res)) :
                                        $role_color = match($u['previledge']) {
                                            'administrator' => 'rgba(124,58,237,0.12);color:#7c3aed',
                                            'finance'       => 'rgba(16,185,129,0.12);color:#059669',
                                            default         => 'rgba(100,116,139,0.1);color:#475569',
                                        };
                                        $is_self = ($u['user_id'] == $cur_id);
                                    ?>
                                    <tr style="border-bottom:1px solid #f1f5f9;">
                                        <td style="padding:12px 14px;font-size:0.8rem;color:#94a3b8;"><?= $u_idx++ ?></td>
                                        <td style="padding:12px 14px;">
                                            <div style="display:flex;align-items:center;gap:10px;">
                                                <div style="width:34px;height:34px;border-radius:10px;background:linear-gradient(135deg,#7c3aed,#a855f7);display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:0.8rem;flex-shrink:0;">
                                                    <?= strtoupper(substr($u['user_name'],0,2)) ?>
                                                </div>
                                                <div>
                                                    <div style="font-size:0.875rem;font-weight:600;color:#0f172a;"><?= htmlspecialchars($u['user_name']) ?></div>
                                                    <?php if ($is_self) : ?><div style="font-size:0.68rem;color:#7c3aed;font-weight:600;">You</div><?php endif; ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td style="padding:12px 14px;">
                                            <span style="display:inline-flex;align-items:center;padding:3px 10px;border-radius:6px;font-size:0.72rem;font-weight:700;background:<?= $role_color ?>;">
                                                <?= ucfirst(htmlspecialchars($u['previledge'] ?: 'user')) ?>
                                            </span>
                                        </td>
                                        <td style="padding:12px 14px;">
                                            <a href="<?= $redirect_link ?>pages/account/user3.php?id=<?= $u['user_id'] ?>&from=settings"
                                               style="display:inline-flex;align-items:center;gap:6px;padding:6px 12px;border-radius:8px;background:rgba(124,58,237,0.08);color:#7c3aed;font-size:0.78rem;font-weight:600;text-decoration:none;transition:all 0.15s;"
                                               onmouseover="this.style.background='rgba(124,58,237,0.18)'" onmouseout="this.style.background='rgba(124,58,237,0.08)'">
                                                <i class="fas fa-shield-halved"></i>Permissions
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php else : ?>
                            <p style="color:#94a3b8;font-size:0.875rem;text-align:center;padding:24px 0;">No users found.</p>
                            <?php endif; ?>

                            <div style="margin-top:20px;background:rgba(124,58,237,0.06);border:1px solid rgba(124,58,237,0.15);border-radius:12px;padding:16px 18px;">
                                <p style="font-size:0.82rem;color:#475569;margin:0;">
                                    <i class="fas fa-shield-halved" style="color:#7c3aed;margin-right:8px;"></i>
                                    <strong style="color:#0f172a;">RBAC is active.</strong> Each user's access is controlled by their module permissions JSON.
                                    Click <strong>Permissions</strong> next to any user to configure their access rights across all product categories, sales, logs, and admin features.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ══════════════════════════════════════════
                     TAB 4: Module Toggles
                ═══════════════════════════════════════════════ -->
                <div class="tab-panel" id="tab-modules">
                    <div class="card">
                        <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;">
                            <span class="card-title">Product Modules</span>
                            <span style="font-size:0.75rem;color:#94a3b8;display:flex;align-items:center;gap:6px;">
                                <i class="fas fa-mobile-screen" style="color:#7c3aed;"></i>
                                Changes sync to the mobile app in real-time
                            </span>
                        </div>
                        <div class="card-body">
                            <p class="settings-section-title">Enable / Disable product categories</p>
                            <p style="font-size:0.82rem;color:#64748b;margin-bottom:20px;">
                                Toggle off any category this store doesn't use. Disabled modules are hidden from the sidebar navigation and are excluded from the mobile app.
                            </p>

                            <?php
                            /* Build module defs dynamically from categories table */
                            $icon_colors = ['#6366f1','#3b82f6','#0ea5e9','#14b8a6','#f59e0b','#ec4899','#ef4444','#8b5cf6','#10b981','#f97316'];
                            $dyn_cats = stock_get_categories($con);
                            $module_defs = [];
                            foreach ($dyn_cats as $i => $cat) {
                                $color = $icon_colors[$i % count($icon_colors)];
                                $module_defs[] = [
                                    'key'   => 'mod_' . $cat['slug'],
                                    'name'  => $cat['label'],
                                    'desc'  => $cat['label'] . ' inventory management',
                                    'icon'  => ltrim($cat['icon'], 'fas '),
                                    'bg'    => 'rgba(' . implode(',', sscanf(ltrim($color,'#'), '%02x%02x%02x')) . ',0.1)',
                                    'color' => $color,
                                ];
                            }
                            ?>

                            <div class="modules-grid">
                            <?php foreach ($module_defs as $mod) :
                                $enabled = ($s[$mod['key']] ?? '1') === '1';
                            ?>
                                <div class="module-card <?= $enabled ? '' : 'mod-off' ?>" id="mc-<?= $mod['key'] ?>">
                                    <div class="module-card-icon" style="background:<?= $mod['bg'] ?>;">
                                        <i class="<?= htmlspecialchars($mod['icon']) ?>" style="color:<?= $mod['color'] ?>;"></i>
                                    </div>
                                    <div class="module-card-body">
                                        <div class="module-card-name"><?= htmlspecialchars($mod['name']) ?></div>
                                        <div class="module-card-desc"><?= htmlspecialchars($mod['desc']) ?></div>
                                    </div>
                                    <label class="mod-toggle" title="<?= $enabled ? 'Click to disable' : 'Click to enable' ?>">
                                        <input type="checkbox" name="<?= htmlspecialchars($mod['key']) ?>" value="1"
                                               <?= $enabled ? 'checked' : '' ?>
                                               onchange="updateModuleCard('<?= htmlspecialchars($mod['key']) ?>',this)">
                                        <span class="toggle-slider"></span>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                            </div>

                            <!-- API hint -->
                            <div style="margin-top:22px;background:rgba(124,58,237,0.06);border:1px solid rgba(124,58,237,0.15);border-radius:12px;padding:16px 18px;display:flex;align-items:flex-start;gap:12px;">
                                <i class="fas fa-code" style="color:#7c3aed;margin-top:2px;flex-shrink:0;"></i>
                                <div>
                                    <p style="font-size:0.82rem;font-weight:700;color:#0f172a;margin-bottom:4px;">Mobile App API Endpoint</p>
                                    <code style="font-size:0.75rem;color:#7c3aed;background:rgba(124,58,237,0.08);padding:4px 10px;border-radius:7px;display:inline-block;word-break:break-all;">
                                        <?= (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . '/' . ltrim($redirect_link, './') ?>pages/settings/api/modules.php
                                    </code>
                                    <p style="font-size:0.72rem;color:#94a3b8;margin-top:6px;">Returns JSON with active modules, store info, and currency. No authentication required.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Save button -->
                    <div class="flex justify-end mt-5">
                        <button type="submit" class="btn btn-primary" onclick="document.getElementById('active_tab_input').value='modules'">
                            <i class="fas fa-save" style="margin-right:8px;"></i>Save Module Settings
                        </button>
                    </div>
                </div>

            </form><!-- end form -->
        </main>

        <?php include $redirect_link . 'partials/footer.php'; ?>
    </div>
</div>

<?php include $redirect_link . 'partials/customizer.php'; ?>
<?php include $redirect_link . 'partials/footer-scripts.php'; ?>

<script>
function switchTab(tab, btn) {
    document.querySelectorAll('.settings-tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('tab-' + tab).classList.add('active');
    document.getElementById('active_tab_input').value = tab;
}

function previewLogo(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('logoPreview').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function updateModuleCard(key, checkbox) {
    const card = document.getElementById('mc-' + key);
    if (!card) return;
    card.classList.toggle('mod-off', !checkbox.checked);
}

// Drag & drop highlight for logo zone
const zone = document.getElementById('logoZone');
if (zone) {
    zone.addEventListener('dragover', e => { e.preventDefault(); zone.style.borderColor = '#7c3aed'; });
    zone.addEventListener('dragleave', () => { zone.style.borderColor = ''; });
    zone.addEventListener('drop', e => { e.preventDefault(); zone.style.borderColor = ''; });
}
</script>
</body>
</html>

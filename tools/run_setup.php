<?php
/**
 * One-time setup script.
 * Visit: http://localhost:8888/stock/tools/run_setup.php
 * Creates stock_master DB, seeds it, and adds categories table to stock DB.
 */

$host   = 'localhost';
$dbuser = 'root';
$dbpass = 'root';

$con = mysqli_connect($host, $dbuser, $dbpass);
if (!$con) {
    die('Cannot connect to MySQL: ' . mysqli_connect_error());
}
mysqli_query($con, "SET SESSION sql_mode = 'ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'");

$msgs = [];

/* ── 1. Create stock_master DB ───────────────────────────────────────── */
mysqli_query($con, "CREATE DATABASE IF NOT EXISTS `stock_master` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
$msgs[] = '✓ stock_master database created (or already exists)';

mysqli_select_db($con, 'stock_master');

/* ── 2. superadmins table ────────────────────────────────────────────── */
mysqli_query($con, "CREATE TABLE IF NOT EXISTS `superadmins` (
  `id`         INT AUTO_INCREMENT PRIMARY KEY,
  `username`   VARCHAR(100) NOT NULL UNIQUE,
  `password`   VARCHAR(255) NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$res = mysqli_query($con, "SELECT id FROM superadmins WHERE username='superadmin'");
if (mysqli_num_rows($res) === 0) {
    mysqli_query($con, "INSERT INTO superadmins (username, password) VALUES ('superadmin', 'superadmin123')");
    $msgs[] = '✓ Default superadmin created (user: superadmin / pass: superadmin123)';
} else {
    $msgs[] = '• Superadmin already exists — skipped';
}

/* ── 3. shops table ──────────────────────────────────────────────────── */
mysqli_query($con, "CREATE TABLE IF NOT EXISTS `shops` (
  `id`             INT AUTO_INCREMENT PRIMARY KEY,
  `name`           VARCHAR(200) NOT NULL,
  `slug`           VARCHAR(100) NOT NULL UNIQUE,
  `db_name`        VARCHAR(100) NOT NULL UNIQUE,
  `admin_username` VARCHAR(100) NOT NULL DEFAULT 'masteradmin',
  `plan`           ENUM('trial','basic','pro') DEFAULT 'trial',
  `active`         TINYINT(1) DEFAULT 1,
  `created_at`     DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$res = mysqli_query($con, "SELECT id FROM shops WHERE slug='zuqemens'");
if (mysqli_num_rows($res) === 0) {
    mysqli_query($con, "INSERT INTO shops (name, slug, db_name, admin_username, plan, active)
                        VALUES ('Zuqemens', 'zuqemens', 'stock', 'masteradmin', 'pro', 1)");
    $msgs[] = '✓ Zuqemens shop registered in master (existing stock DB)';
} else {
    $msgs[] = '• Zuqemens shop already registered — skipped';
}

/* ── 4. categories table in stock (existing shop) ────────────────────── */
mysqli_select_db($con, 'stock');

mysqli_query($con, "CREATE TABLE IF NOT EXISTS `categories` (
  `id`           INT AUTO_INCREMENT PRIMARY KEY,
  `slug`         VARCHAR(50)  NOT NULL UNIQUE,
  `label`        VARCHAR(100) NOT NULL,
  `icon`         VARCHAR(100) NOT NULL DEFAULT 'fas fa-box',
  `sort_order`   INT NOT NULL DEFAULT 0,
  `enabled`      TINYINT(1)   NOT NULL DEFAULT 1,
  `default_image` VARCHAR(200) NOT NULL DEFAULT '',
  `page_folder`  VARCHAR(100) NOT NULL DEFAULT 'category',
  `file_prefix`  VARCHAR(100) NOT NULL DEFAULT '',
  `add_file`     VARCHAR(200) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
$msgs[] = '✓ categories table created in stock DB (or already exists)';

$seed = [
    ['jeans',     'Jeans',     'fas fa-scroll',     1, 'defaultjeans.jpg',     'price_calculator', 'jeans',     'add_single_jeans.php'],
    ['shoes',     'Shoes',     'fas fa-shoe-prints', 2, 'defaultshoes.jpg',     'shoe',             'shoes',     'add_shoes.php'],
    ['top',       'Top',       'fas fa-tshirt',      3, 'defaulttop.jpg',       'top',              'top',       'add_top.php'],
    ['complete',  'Complete',  'fas fa-box-open',    4, 'defaultcomplete.jpg',  'complete',         'complete',  'add_complete.php'],
    ['accessory', 'Accessory', 'fas fa-gem',         5, 'defaultaccessory.jpg', 'accessory',        'accessory', 'add_accessory.php'],
    ['wig',       'Wig',       'fas fa-hat-wizard',  6, 'defaultwig.jpg',       'wig',              'wig',       'add_wig.php'],
    ['cosmetics', 'Cosmetics', 'fas fa-spa',         7, 'defaultcosmetics.jpg', 'cosmetics',        'cosmetics', 'add_cosmetics.php'],
];

$inserted = 0;
foreach ($seed as [$slug, $label, $icon, $order, $img, $folder, $prefix, $addfile]) {
    $res = mysqli_query($con, "SELECT id FROM categories WHERE slug='$slug'");
    if (mysqli_num_rows($res) === 0) {
        mysqli_query($con, "INSERT INTO categories (slug,label,icon,sort_order,enabled,default_image,page_folder,file_prefix,add_file)
                            VALUES ('$slug','$label','$icon',$order,1,'$img','$folder','$prefix','$addfile')");
        $inserted++;
    }
}
$msgs[] = $inserted > 0 ? "✓ Seeded $inserted categories into stock DB" : '• All categories already seeded — skipped';

mysqli_close($con);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Stock Hub — Setup</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: 'Inter', sans-serif; background: #0b1120; color: #e2e8f0; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
.card { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 20px; padding: 40px; max-width: 560px; width: 100%; }
h1 { font-size: 1.6rem; font-weight: 700; margin-bottom: 6px; background: linear-gradient(135deg,#a78bfa,#f472b6); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
.sub { color: rgba(255,255,255,0.4); font-size: 0.85rem; margin-bottom: 28px; }
.msg { padding: 10px 14px; background: rgba(255,255,255,0.04); border-radius: 10px; font-size: 0.85rem; margin-bottom: 8px; border-left: 3px solid #7c3aed; }
.links { margin-top: 28px; display: flex; gap: 12px; flex-wrap: wrap; }
.btn { padding: 10px 20px; border-radius: 10px; font-size: 0.88rem; font-weight: 600; text-decoration: none; display: inline-block; }
.btn-primary { background: linear-gradient(135deg,#7c3aed,#a855f7); color: #fff; }
.btn-secondary { background: rgba(255,255,255,0.07); color: #e2e8f0; border: 1px solid rgba(255,255,255,0.1); }
.warn { margin-top: 20px; padding: 12px 16px; background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3); border-radius: 10px; font-size: 0.8rem; color: #fca5a5; }
</style>
</head>
<body>
<div class="card">
    <h1>Stock Hub Setup</h1>
    <p class="sub">One-time initialization — creates master database and seeds categories.</p>
    <?php foreach ($msgs as $m): ?>
    <div class="msg"><?= htmlspecialchars($m) ?></div>
    <?php endforeach; ?>
    <div class="warn">
        <strong>Security:</strong> Delete or password-protect this file after running setup. It creates database users and tables.
    </div>
    <div class="links">
        <a href="../pages/superadmin/login.php" class="btn btn-primary">Go to Super Admin</a>
        <a href="../login.php" class="btn btn-secondary">Go to Shop Login</a>
    </div>
</div>
</body>
</html>

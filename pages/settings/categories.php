<?php
$redirect_link = "../../";
$side_link     = "../../";
include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php';

/* masteradmin only */
$cur = mysqli_fetch_assoc(mysqli_query($con, "SELECT user_name FROM user WHERE user_id=" . (int)$_SESSION['user_id']));
if (!$cur || $cur['user_name'] !== 'masteradmin') {
    header("Location: {$redirect_link}index.php"); exit;
}

/* Ensure categories table exists */
mysqli_query($con, "CREATE TABLE IF NOT EXISTS `categories` (
  `id`            INT AUTO_INCREMENT PRIMARY KEY,
  `slug`          VARCHAR(50)  NOT NULL UNIQUE,
  `label`         VARCHAR(100) NOT NULL,
  `icon`          VARCHAR(100) NOT NULL DEFAULT 'fas fa-box',
  `sort_order`    INT NOT NULL DEFAULT 0,
  `enabled`       TINYINT(1)   NOT NULL DEFAULT 1,
  `default_image` VARCHAR(200) NOT NULL DEFAULT '',
  `page_folder`   VARCHAR(100) NOT NULL DEFAULT 'category',
  `file_prefix`   VARCHAR(100) NOT NULL DEFAULT '',
  `add_file`      VARCHAR(200) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$success = $error = '';

/* ── Handle actions ─────────────────────────────────────────────── */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['toggle_cat'])) {
        $cat_id = (int)$_POST['cat_id'];
        $enabled = (int)$_POST['enabled'];
        mysqli_query($con, "UPDATE categories SET enabled=$enabled WHERE id=$cat_id");
        /* Sync app_settings mod_ key */
        $slug_res = mysqli_query($con, "SELECT slug FROM categories WHERE id=$cat_id");
        if ($slug_res && $sr = mysqli_fetch_assoc($slug_res)) {
            $s = mysqli_real_escape_string($con, $sr['slug']);
            $v = $enabled ? '1' : '0';
            mysqli_query($con, "INSERT INTO app_settings (`key`,`value`) VALUES ('mod_$s','$v') ON DUPLICATE KEY UPDATE `value`='$v'");
        }
        $success = 'Category updated';
    }

    if (isset($_POST['add_category'])) {
        $label  = trim($_POST['label'] ?? '');
        $slug   = preg_replace('/[^a-z0-9_]/', '', strtolower(trim($_POST['slug'] ?? '')));
        $icon   = trim($_POST['icon'] ?? 'fas fa-box');
        $order  = (int)($_POST['sort_order'] ?? 99);

        if ($label === '' || $slug === '') {
            $error = 'Label and slug are required';
        } else {
            $safe_l = mysqli_real_escape_string($con, $label);
            $safe_s = mysqli_real_escape_string($con, $slug);
            $safe_i = mysqli_real_escape_string($con, $icon);
            $result = mysqli_query($con, "INSERT IGNORE INTO categories (slug,label,icon,sort_order,enabled,page_folder,file_prefix,add_file)
                                          VALUES ('$safe_s','$safe_l','$safe_i',$order,1,'category','','')");
            if ($result && mysqli_affected_rows($con) > 0) {
                /* Update app_settings */
                mysqli_query($con, "INSERT INTO app_settings (`key`,`value`) VALUES ('mod_$safe_s','1') ON DUPLICATE KEY UPDATE `value`='1'");
                /* Clear helper cache */
                $success = "Category '$label' added. You can now add products under this category using the generic category pages.";
            } else {
                $error = "Slug '$slug' already exists";
            }
        }
    }

    if (isset($_POST['update_order'])) {
        $orders = $_POST['order'] ?? [];
        foreach ($orders as $cat_id => $ord) {
            $cat_id = (int)$cat_id;
            $ord    = (int)$ord;
            mysqli_query($con, "UPDATE categories SET sort_order=$ord WHERE id=$cat_id");
        }
        $success = 'Sort order saved';
    }
}

$cats = [];
$res = mysqli_query($con, "SELECT * FROM categories ORDER BY sort_order ASC, id ASC");
if ($res) while ($r = mysqli_fetch_assoc($res)) $cats[] = $r;

$title = 'Categories';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include $redirect_link . 'partials/title-meta.php'; ?>
    <?php include $redirect_link . 'partials/head-css.php'; ?>
    <style>
        .cat-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(300px,1fr)); gap:14px; margin-bottom:28px; }
        .cat-card { background:#fff; border:1px solid #f1f5f9; border-radius:16px; padding:18px 20px; display:flex; align-items:center; gap:14px; transition:all 0.2s; }
        .cat-card:hover { border-color:#e2e8f0; box-shadow:0 4px 20px rgba(0,0,0,0.06); }
        .cat-card.disabled { opacity:0.5; background:#f8fafc; }
        [data-mode="dark"] .cat-card { background:rgba(255,255,255,0.03); border-color:rgba(255,255,255,0.07); }
        [data-mode="dark"] .cat-card.disabled { background:rgba(255,255,255,0.01); }
        .cat-ico { width:42px; height:42px; border-radius:12px; background:rgba(124,58,237,0.1); display:flex; align-items:center; justify-content:center; font-size:1rem; color:#7c3aed; flex-shrink:0; }
        .cat-name { font-weight:700; font-size:0.95rem; }
        .cat-slug { font-size:0.72rem; color:#94a3b8; font-family:monospace; }
        .cat-generic { font-size:0.7rem; color:#f59e0b; background:#fef3c7; border-radius:4px; padding:2px 6px; margin-top:3px; display:inline-block; }
        .toggle-wrap { margin-left:auto; display:flex; flex-direction:column; gap:6px; align-items:flex-end; }
        .mod-toggle { position:relative; display:inline-block; width:46px; height:24px; }
        .mod-toggle input { opacity:0; width:0; height:0; position:absolute; }
        .tslider { position:absolute; cursor:pointer; top:0; left:0; right:0; bottom:0; background:#e2e8f0; border-radius:24px; transition:.25s; }
        .tslider:before { content:""; position:absolute; height:16px; width:16px; left:4px; bottom:4px; background:#fff; border-radius:50%; transition:.25s; box-shadow:0 2px 5px rgba(0,0,0,0.18); }
        .mod-toggle input:checked + .tslider { background:#7c3aed; }
        .mod-toggle input:checked + .tslider:before { transform:translateX(22px); }
        .add-form { background:#fff; border:1px solid #f1f5f9; border-radius:16px; padding:24px; }
        [data-mode="dark"] .add-form { background:rgba(255,255,255,0.03); border-color:rgba(255,255,255,0.07); }
        .order-input { width:56px; border:1px solid #e2e8f0; border-radius:8px; padding:5px 8px; font-size:0.8rem; text-align:center; }
        [data-mode="dark"] .order-input { background:rgba(255,255,255,0.05); border-color:rgba(255,255,255,0.1); color:#e2e8f0; }
    </style>
</head>
<body>
<div class="flex wrapper">
    <?php include $redirect_link . 'partials/menu.php'; ?>
    <div class="page-content">
        <?php include $redirect_link . 'partials/topbar.php'; ?>
        <main class="flex-grow p-6">
            <?php include $redirect_link . 'partials/page-title.php'; ?>

            <?php if ($success) : ?>
            <div class="alert alert-success border border-success/30 text-success-600 dark:text-success-400 mb-4 rounded-xl p-4 flex gap-3">
                <i class="fas fa-circle-check mt-0.5"></i> <?= htmlspecialchars($success) ?>
            </div>
            <?php endif; ?>
            <?php if ($error) : ?>
            <div class="alert alert-danger border border-danger/30 text-danger-600 dark:text-danger-400 mb-4 rounded-xl p-4 flex gap-3">
                <i class="fas fa-triangle-exclamation mt-0.5"></i> <?= htmlspecialchars($error) ?>
            </div>
            <?php endif; ?>

            <!-- Active categories -->
            <div class="card mb-4">
                <div class="card-header flex justify-between items-center">
                    <h4 class="text-lg font-semibold">Product Categories</h4>
                    <form method="POST">
                        <button name="update_order" class="btn btn-sm bg-primary/10 text-primary hover:bg-primary hover:text-white rounded-lg">Save Order</button>
                        <?php foreach ($cats as $c) : ?>
                        <input type="hidden" name="order[<?= $c['id'] ?>]" id="ord_<?= $c['id'] ?>" value="<?= $c['sort_order'] ?>">
                        <?php endforeach; ?>
                    </form>
                </div>
                <div class="p-4">
                    <div class="cat-grid" id="cat-grid">
                        <?php foreach ($cats as $cat) : ?>
                        <div class="cat-card <?= $cat['enabled'] ? '' : 'disabled' ?>" draggable="true" data-id="<?= $cat['id'] ?>">
                            <div class="cat-ico"><i class="<?= htmlspecialchars($cat['icon']) ?>"></i></div>
                            <div style="flex:1;min-width:0">
                                <div class="cat-name"><?= htmlspecialchars($cat['label']) ?></div>
                                <div class="cat-slug">slug: <?= htmlspecialchars($cat['slug']) ?></div>
                                <?php if ($cat['page_folder'] === 'category') : ?>
                                <span class="cat-generic">Generic pages</span>
                                <?php endif; ?>
                            </div>
                            <div class="toggle-wrap">
                                <form method="POST">
                                    <input type="hidden" name="cat_id" value="<?= $cat['id'] ?>">
                                    <input type="hidden" name="enabled" value="<?= $cat['enabled'] ? 0 : 1 ?>">
                                    <label class="mod-toggle" title="<?= $cat['enabled'] ? 'Click to disable' : 'Click to enable' ?>">
                                        <input type="checkbox" <?= $cat['enabled'] ? 'checked' : '' ?> onchange="this.closest('form').submit()">
                                        <input type="hidden" name="toggle_cat" value="1">
                                        <span class="tslider"></span>
                                    </label>
                                </form>
                                <span style="font-size:0.68rem;color:#94a3b8"><?= $cat['enabled'] ? 'Enabled' : 'Disabled' ?></span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Add new category -->
            <div class="card">
                <div class="card-header">
                    <h4 class="text-lg font-semibold">Add New Category</h4>
                </div>
                <div class="p-4">
                    <form method="POST" class="add-form">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label class="text-sm font-semibold text-gray-600 dark:text-gray-300 block mb-1.5">Label *</label>
                                <input type="text" name="label" placeholder="e.g. Electronics" class="form-input" required>
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-gray-600 dark:text-gray-300 block mb-1.5">Slug *</label>
                                <input type="text" name="slug" id="new_slug" placeholder="e.g. electronics" class="form-input font-mono" required pattern="[a-z0-9_]+" title="Lowercase letters, numbers, underscores">
                                <p class="text-xs text-gray-400 mt-1">Used as database table prefix</p>
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-gray-600 dark:text-gray-300 block mb-1.5">Icon class</label>
                                <input type="text" name="icon" placeholder="fas fa-mobile-alt" value="fas fa-box" class="form-input font-mono">
                                <p class="text-xs text-gray-400 mt-1"><a href="https://fontawesome.com/icons" target="_blank" class="text-primary">Browse FA icons</a></p>
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-gray-600 dark:text-gray-300 block mb-1.5">Sort Order</label>
                                <input type="number" name="sort_order" value="<?= count($cats) + 1 ?>" class="form-input" min="1">
                            </div>
                        </div>
                        <div class="mt-4 p-3 bg-amber-50 dark:bg-amber-900/10 border border-amber-200 dark:border-amber-700/30 rounded-lg text-sm text-amber-700 dark:text-amber-400">
                            <i class="fas fa-circle-info mr-1"></i>
                            New categories use generic pages at <code>pages/category/</code>. You can access them from the sidebar under Inventory.
                            The database tables (<code>{slug}</code>, <code>{slug}_sales</code>, etc.) must be created manually or via the setup script.
                        </div>
                        <div class="mt-4">
                            <button type="submit" name="add_category" class="btn bg-primary text-white hover:bg-primary-600">
                                <i class="fas fa-plus mr-1"></i> Add Category
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </main>
        <?php include $redirect_link . 'partials/footer.php'; ?>
    </div>
</div>
<?php include $redirect_link . 'partials/footer-scripts.php'; ?>
<script>
/* Auto-slug from label */
document.querySelector('input[name="label"]').addEventListener('input', function() {
    document.getElementById('new_slug').value = this.value.toLowerCase().replace(/[^a-z0-9]+/g,'_').replace(/^_|_$/g,'');
});
</script>
</body>
</html>

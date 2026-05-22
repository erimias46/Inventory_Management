<?php
$redirect_link = "../../";
$side_link     = "../../";
include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php';

$id   = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$from = $_GET['from'] ?? 'users.php';

$row = null;
if ($id > 0) {
    $res = mysqli_query($con, "SELECT * FROM user WHERE user_id = $id");
    if ($res) { $row = mysqli_fetch_assoc($res); mysqli_free_result($res); }
}
if (!$row) { echo "No user found"; exit; }

/* Load categories dynamically */
$categories = stock_get_categories($con);
$cat_slugs  = array_column($categories, 'slug');

/* Permission row definitions */
$perm_rows = [
    ['View Product',   'view'],
    ['Add Product',    'add'],
    ['Edit Product',   'edit'],
    ['Delete Product', 'delete'],
    ['Verify Product', 'verify'],
    ['Add Sale',       'sale'],
    ['Edit Sale',      'editsale'],
    ['Delete Sale',    'deletesale'],
    ['Refund Sale',    'refundsale'],
    ['Exchange Sale',  'exchangesale'],
    ['Delivery',       'deliverysale'],
    ['Log',            'log'],
];
$global_perms = ['constant','backup','email','user','settings','editbuyprice','addproduct','fullsale','allsale','logsale','searchproduct','deliverysale','producttypes','productsin','verifyproducts','verifyproducts'];

/* ── Handle update ─────────────────────────────────────────────── */
if (isset($_POST['update_user'])) {
    $user_id   = (int)$_POST['user_id'];
    $user_name = mysqli_real_escape_string($con, $_POST['user_name'] ?? '');
    $password  = mysqli_real_escape_string($con, $_POST['password']  ?? '');
    $privileged= mysqli_real_escape_string($con, $_POST['privileged'] ?? 'user');

    $json = [];

    /* Category permissions */
    foreach ($perm_rows as [$label, $pfx]) {
        foreach ($cat_slugs as $slug) {
            $key = $pfx . $slug;
            $json[$key] = (int)boolval($_POST[$key] ?? 0);
        }
    }

    /* Global permissions */
    $all_globals = ['constant','backup','email','user','settings','editbuyprice',
                    'addproduct','fullsale','allsale','logsale','searchproduct',
                    'deliverysale','producttypes','productsin','verifyproducts'];
    foreach ($all_globals as $g) {
        $json[$g] = (int)boolval($_POST[$g] ?? 0);
    }

    $json_str = mysqli_real_escape_string($con, json_encode($json));
    $q = "UPDATE user SET user_name='$user_name', password='$password', previledge='$privileged', module='$json_str' WHERE user_id=$user_id";
    $ok = mysqli_query($con, $q);

    echo "<script>window.location = 'action.php?status=" . ($ok ? 'success' : 'error') . "&redirect=users.php';</script>";
    exit;
}

$module_data = json_decode($row['module'], true) ?: [];
$title = 'Edit User';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include $redirect_link . 'partials/title-meta.php'; ?>
    <?php include $redirect_link . 'partials/head-css.php'; ?>
    <style>
        .perm-wrap { overflow-x: auto; }
        .perm-table { min-width: 100%; border-collapse: collapse; }
        .perm-table th { padding: 10px 12px; text-align: center; font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em; color: #94a3b8; border-bottom: 2px solid #f1f5f9; white-space: nowrap; }
        .perm-table th.label-col { text-align: left; min-width: 140px; }
        .perm-table td { padding: 9px 12px; text-align: center; border-bottom: 1px solid #f8fafc; font-size: 0.82rem; }
        .perm-table td.label-col { text-align: left; font-weight: 500; color: #475569; }
        .perm-table tr:last-child td { border-bottom: none; }
        .perm-table tr:hover td { background: #f8fafc; }
        .perm-table input[type="checkbox"] { width: 16px; height: 16px; accent-color: #7c3aed; cursor: pointer; }
        [data-mode="dark"] .perm-table th { border-bottom-color: rgba(255,255,255,0.06); color: #64748b; }
        [data-mode="dark"] .perm-table td { border-bottom-color: rgba(255,255,255,0.04); }
        [data-mode="dark"] .perm-table td.label-col { color: #94a3b8; }
        [data-mode="dark"] .perm-table tr:hover td { background: rgba(255,255,255,0.02); }
        .select-all-btn { font-size: 0.7rem; color: #7c3aed; cursor: pointer; background: none; border: none; padding: 0; margin-top: 2px; display: block; }
    </style>
</head>
<body>
<div class="flex wrapper">
    <?php include $redirect_link . 'partials/menu.php'; ?>
    <div class="page-content">
        <?php include $redirect_link . 'partials/topbar.php'; ?>
        <main class="flex-grow p-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-lg font-medium">Edit User — <?= htmlspecialchars($row['user_name']) ?></h4>
                </div>
                <div class="p-4">
                    <form method="POST" class="space-y-4">
                        <input type="hidden" name="user_id" value="<?= $row['user_id'] ?>">

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="form-label">Username</label>
                                <input type="text" name="user_name" value="<?= htmlspecialchars($row['user_name']) ?>" class="form-input" required>
                            </div>
                            <div>
                                <label class="form-label">Password</label>
                                <input type="text" name="password" value="<?= htmlspecialchars($row['password']) ?>" class="form-input" required>
                            </div>
                            <div>
                                <label class="form-label">Role</label>
                                <select name="privileged" class="form-select">
                                    <?php foreach (['administrator','user','finance'] as $role) : ?>
                                    <option value="<?= $role ?>" <?= ($row['previledge'] === $role) ? 'selected' : '' ?>><?= ucfirst($role) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <!-- Category Permissions -->
                        <div class="card mt-4">
                            <div class="card-header flex justify-between items-center">
                                <h5 class="font-semibold">Category Permissions</h5>
                                <div style="display:flex;gap:8px">
                                    <button type="button" onclick="checkAll(true)" class="btn btn-sm bg-success/10 text-success hover:bg-success hover:text-white rounded-lg text-xs">Check All</button>
                                    <button type="button" onclick="checkAll(false)" class="btn btn-sm bg-danger/10 text-danger hover:bg-danger hover:text-white rounded-lg text-xs">Uncheck All</button>
                                </div>
                            </div>
                            <div class="p-3 perm-wrap">
                                <table class="perm-table">
                                    <thead>
                                        <tr>
                                            <th class="label-col">Permission</th>
                                            <?php foreach ($categories as $cat) : ?>
                                            <th>
                                                <?= htmlspecialchars($cat['label']) ?>
                                                <button type="button" class="select-all-btn" onclick="toggleCol('<?= $cat['slug'] ?>')">all/none</button>
                                            </th>
                                            <?php endforeach; ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($perm_rows as [$label, $pfx]) : ?>
                                        <tr>
                                            <td class="label-col"><?= $label ?></td>
                                            <?php foreach ($cat_slugs as $slug) :
                                                $key = $pfx . $slug;
                                                $checked = !empty($module_data[$key]) && $module_data[$key] == 1;
                                            ?>
                                            <td>
                                                <input type="checkbox" name="<?= $key ?>" value="1" <?= $checked ? 'checked' : '' ?> class="perm-cb" data-slug="<?= $slug ?>">
                                            </td>
                                            <?php endforeach; ?>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Global Permissions -->
                        <div class="card mt-4">
                            <div class="card-header"><h5 class="font-semibold">System Permissions</h5></div>
                            <div class="p-4">
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                    <?php
                                    $sys_perms = [
                                        'constant' => 'Constants', 'backup' => 'Backup', 'email' => 'Email',
                                        'user' => 'User Management', 'settings' => 'Settings',
                                        'editbuyprice' => 'Edit Buy Price', 'addproduct' => 'Add Product',
                                        'fullsale' => 'New Sale', 'allsale' => 'All Sales', 'logsale' => 'Sales Log',
                                        'searchproduct' => 'Search Product', 'deliverysale' => 'Delivery',
                                        'producttypes' => 'Product Types', 'productsin' => 'Products In',
                                        'verifyproducts' => 'Verify Products',
                                    ];
                                    foreach ($sys_perms as $key => $label) :
                                        $checked = !empty($module_data[$key]) && $module_data[$key] == 1;
                                    ?>
                                    <label class="flex items-center gap-2 cursor-pointer p-2 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-700/50">
                                        <input type="checkbox" name="<?= $key ?>" value="1" <?= $checked ? 'checked' : '' ?> style="accent-color:#7c3aed;width:15px;height:15px">
                                        <span class="text-sm text-gray-700 dark:text-gray-300"><?= $label ?></span>
                                    </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 mt-4">
                            <a href="users.php" class="btn border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-700">Cancel</a>
                            <button type="submit" name="update_user" class="btn bg-primary text-white hover:bg-primary-600">Save User</button>
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
function checkAll(state) {
    document.querySelectorAll('.perm-cb').forEach(cb => cb.checked = state);
}
function toggleCol(slug) {
    const cbs = document.querySelectorAll('.perm-cb[data-slug="' + slug + '"]');
    const anyUnchecked = Array.from(cbs).some(cb => !cb.checked);
    cbs.forEach(cb => cb.checked = anyUnchecked);
}
</script>
</body>
</html>

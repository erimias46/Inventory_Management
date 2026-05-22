<?php
require_once __DIR__ . '/_guard.php';
require_once __DIR__ . '/../../include/db_master.php';

$action = $_GET['action'] ?? '';
$id     = (int)($_GET['id'] ?? 0);

$master = stock_master_connect();
if (!$master || $id <= 0) { header('Location: shops.php'); exit; }

$res  = mysqli_query($master, "SELECT * FROM shops WHERE id=$id LIMIT 1");
$shop = $res ? mysqli_fetch_assoc($res) : null;
if (!$shop) { mysqli_close($master); header('Location: shops.php'); exit; }

switch ($action) {
    case 'toggle':
        $new = $shop['active'] ? 0 : 1;
        mysqli_query($master, "UPDATE shops SET active=$new WHERE id=$id");
        mysqli_close($master);
        header('Location: shops.php');
        break;

    case 'delete':
        /* Drop the shop database and remove from master */
        $db = $shop['db_name'];
        /* Safety: never delete the primary 'stock' database */
        if ($db !== 'stock') {
            mysqli_query($master, "DROP DATABASE IF EXISTS `$db`");
        }
        mysqli_query($master, "DELETE FROM shops WHERE id=$id");
        mysqli_close($master);
        header('Location: shops.php?flash=deleted');
        break;

    default:
        mysqli_close($master);
        header('Location: shops.php');
}
exit;

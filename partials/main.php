<?php
session_start();
if (!$_SESSION['user'] || !isset($_SESSION['user'])) {
    header("Location: {$redirect_link}login.php?redirect={$_SERVER['REQUEST_URI']}");
    exit();
}
?>

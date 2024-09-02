<?php

if (isset($_GET['download'])) {
    $file_name = $_GET['download'];
    $file_url = 'files/' . $file_name;
    header('Content-Type: application/octet-stream');
    header("Content-Transfer-Encoding: Binary"); 
    header("Content-disposition: attachment; filename=\"".$file_name."\""); 
    readfile($file_name);    
    echo "<script>window.location = 'action.php?status=success&redirect=index.php'; </script>";
}

?>
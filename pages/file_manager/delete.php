<?php
// Ensure captured GET param exists
if (isset($_GET['file'])) {
    // Check if file is a directory
    if (is_dir($_GET['file'])) {
        // Attempt to delete the directory
        if (rmdir($_GET['file'])) {
            // Delete success! Redirect to file manager page
            echo "<script>window.location = 'action.php?status=success&redirect=index.php'; </script>";
            exit;
        } else {
            // Delete failed - directory is empty or insufficient permissions
            exit('Directory must be empty!');
            echo "<script>window.location = 'action.php?status=error&redirect=index.php'; </script>";
        }
    } else {
        // Delete the file
        unlink($_GET['file']);
        echo "<script>window.location = 'action.php?status=success&redirect=index.php'; </script>";
        exit;
    }
} else {
    exit('Invalid Request!');
    echo "<script>window.location = 'action.php?status=error&redirect=index.php'; </script>";
}
?>
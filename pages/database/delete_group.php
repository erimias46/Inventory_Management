<?php
include_once '../../include/db.php';



if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare the SQL statement to delete the record
    $sql = "DELETE FROM brocher_group WHERE id = ?";

    if ($stmt = $con->prepare($sql)) {
        // Bind the ID parameter to the statement
        $stmt->bind_param('i', $id);

        // Execute the statement
        if ($stmt->execute()) {
            // Redirect to the grouppage.php after successful deletion
            header('Location: grouppage.php');
            exit();
        } else {
            echo "Error: Could not execute the delete statement.";
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Error: Could not prepare the delete statement.";
    }
} else {
    echo "Error: No ID was provided.";
}

// Close the database connection
$conn->close();
?>
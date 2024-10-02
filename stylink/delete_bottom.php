<?php
session_start();
include("include/config.php");

if (isset($_GET['id'])) {
    $imageId = $_GET['id'];

    // Delete the image from the database
    $stmt = $conn->prepare("DELETE FROM wardrobe_bottom WHERE id = ? AND username = ?");
    $stmt->bind_param("is", $imageId, $_SESSION['username']);

    if ($stmt->execute()) {
        $success = "Image removed successfully.";
    } else {
        $error = "Error removing image: " . $stmt->error;
    }

    $stmt->close();
}

// Redirect back to the page (with optional success/error messages)
header("Location: customer_customizewardrobe.php" . (isset($success) ? "?success=$success" : "") . (isset($error) ? "?error=$error" : ""));
exit;

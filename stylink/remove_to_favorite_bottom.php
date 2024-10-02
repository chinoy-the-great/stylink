<?php
session_start();
include("include/config.php");

if (!isset($_SESSION["username"])) {
    header("Location: user_login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $outfitId = $_GET['id'];

    // Prepare and execute the SQL DELETE query
    $stmt = $conn->prepare("DELETE FROM favorite_bottoms WHERE id = ?");
    $stmt->bind_param("i", $outfitId);

    if ($stmt->execute()) {
        // Success - redirect back to the wardrobe page
        header("Location: customer_favoritewardrobe.php#bottom-1"); 
        exit; 
    } else {
        // Error handling
        $error = "Error deleting outfit: " . $stmt->error;
        header("Location: customer_favoritewardrobe.php#bottom-1?error=$error"); 
        exit;
    }

    $stmt->close(); 
}
?>

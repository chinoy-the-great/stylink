<?php
session_start();
include("include/config.php");

if (!isset($_SESSION["username"])) {
    header("Location: user_login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $outfitId = $_GET['id'];

    // Prepare and execute the SQL query to update the archive_status
    $stmt = $conn->prepare("UPDATE favorite_outfits SET archive_status = 'Trash' WHERE id = ?");
    $stmt->bind_param("i", $outfitId);

    if ($stmt->execute()) {
        // Redirect back to the page where you display the outfits (index.php or similar)
        $success = "Move to Trash Successfully.";
        header("Location: customer_favoritewardrobe.php" . (isset($success) ? "?success=$success" : "") . (isset($error) ? "?error=$error" : ""));
        exit;
    } else {
        echo "Error updating outfit: " . $stmt->error;
        $error = "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>

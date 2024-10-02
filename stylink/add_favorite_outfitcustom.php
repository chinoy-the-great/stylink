
<?php
session_start();
include("include/config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_SESSION["username"])) {
        $username = $_SESSION["username"];
        $topId = $_POST["top_id1"];
        $bottomId = $_POST["bottom_id1"];

        // Insert into favorite outfits table
        $stmt = $conn->prepare("INSERT INTO favorite_outfits (username, top_id, bottom_id, archive_status) VALUES (?, ?, ?, 'Active')");
        $stmt->bind_param("sss", $username, $topId, $bottomId);
        $stmt->execute();
    }
}
// Redirect (optional)
header("Location: customer_customizewardrobe.php?success-save=Added to Favorite.");
?>
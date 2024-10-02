<?php
session_start();
include("include/config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_SESSION["username"])) {
        $username = $_SESSION["username"];
        $bottomId = $_POST["bottom_id"];

        // Check if already in favorite_tops
        $checkStmt = $conn->prepare("SELECT * FROM favorite_bottoms WHERE username = ? AND bottom_id = ?");
        $checkStmt->bind_param("ss", $username, $bottomId);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows > 0) {
            // Already in favorites - display message
         
            header("Location: customer_customizewardrobe.php?error-top= Already in your favorites!"); // Redirect on success
            exit();
        } else {
            // Not in favorites - insert
            $stmt = $conn->prepare("INSERT INTO favorite_bottoms (username, bottom_id, archive_status) VALUES (?, ?, 'Active')");
            $stmt->bind_param("ss", $username, $bottomId);
            $stmt->execute();
            header("Location: customer_customizewardrobe.php?success-top= Added to Favorite Bottom"); // Redirect on success
            exit();
        }

        $checkStmt->close(); 
    }
}
?>

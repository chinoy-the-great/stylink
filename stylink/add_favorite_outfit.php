
<?php
session_start();
include("include/config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_SESSION["username"])) {
        $username = $_SESSION["username"];
        $topId = $_POST["top_id"];
        $bottomId = $_POST["bottom_id"];

        // // Insert into favorite outfits table
        // $stmt = $conn->prepare("INSERT INTO favorite_outfits (username, top_id, bottom_id, archive_status) VALUES (?, ?, ?, 'Active')");
        // $stmt->bind_param("sss", $username, $topId, $bottomId);
        // $stmt->execute();

        // if ($checkResult->num_rows > 0) {
        //     // Already in favorites - display message

        //     header("Location: customer_customizewardrobe.php?error-top= Already in your favorites!"); // Redirect on success
        //     exit();
        // } else {
        //     // Not in favorites - insert
        //     $stmt = $conn->prepare("INSERT INTO favorite_tops (username, top_id, archive_status) VALUES (?, ?, 'Active')");
        //     $stmt->bind_param("ss", $username, $topId);
        //     $stmt->execute();
        //     header("Location: customer_customizewardrobe.php?success-top= Added to Favorite Top"); // Redirect on success
        //     exit();
        // }

        // Check if already in favorite_tops
        $checkStmt = $conn->prepare("SELECT * FROM favorite_outfits WHERE username = ? AND top_id = ? AND bottom_id= ?");
        $checkStmt->bind_param("sss", $username, $topId, $bottomId);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows > 0) {
            // Already in favorites - display message

            header("Location: customer_customizewardrobe.php?error-top= Already in your favorites!"); // Redirect on success
            exit();
        } else {
            // Not in favorites - insert
            $stmt = $conn->prepare("INSERT INTO favorite_outfits (username, top_id, bottom_id, archive_status) VALUES (?, ?, ?, 'Active')");
            $stmt->bind_param("sss", $username, $topId, $bottomId);
            $stmt->execute();
            header("Location: customer_customizewardrobe.php?success-top= Added to Favorite Outfit"); // Redirect on success
            exit();
        }

        $checkStmt->close();
    }
}
// Redirect (optional)
// header("Location: customer_customizewardrobe.php");
?>
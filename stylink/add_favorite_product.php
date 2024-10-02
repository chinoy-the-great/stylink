
<?php
session_start();
include("include/config.php");




if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_SESSION["username"])) {
        $username = $_SESSION["username"];
        $topId = $_POST["top_id"];
        $topColor = $_POST["top_color"];
        $bottomId = $_POST["bottom_id"];
        $bottomColor = $_POST["bottom_color"];

        // Insert into favorite products table
        $stmt = $conn->prepare("INSERT INTO favorite_product (username, top_id, top_color, bottom_id,bottom_color, archive_status) VALUES (?, ?, ?, ?, ?, 'Active')");
        $stmt->bind_param("sssss", $username, $topId,$topColor, $bottomId, $bottomColor);
        $stmt->execute();
    }
}
// Redirect (optional)
header("Location: customer_wardrobe.php");
?>
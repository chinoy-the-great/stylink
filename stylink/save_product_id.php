<?php
session_start();

if (isset($_POST['product_id']) && isset($_SESSION['username'])) {
    include("include/config.php");

    $productId = $_POST['product_id'];
    $username = $_SESSION['username'];

     // Store in the session
     $_SESSION['product_id'] = $productId;  
     
    // Optional: Store in the database
    $stmt = $conn->prepare("INSERT INTO product_ids (product_id, username) VALUES (?, ?)");
    $stmt->bind_param("ss", $productId, $username);
    
    if ($stmt->execute()) {
        echo "Product ID saved successfully";
    } else {
        echo "Error saving product ID: " . $stmt->error;
    }
} else {
    echo "Invalid request";
}
?>

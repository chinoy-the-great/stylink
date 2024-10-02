<?php
session_start();
include("include/config.php");

if (!isset($_SESSION["username"])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

if (isset($_GET["product_id"])) {
   
    $wardrobeId = $_GET["product_id"]; // Get the wardrobe ID

    $username = $_SESSION["username"];

    // Delete the product from the customer's wardrobe
    $stmt = $conn->prepare("DELETE FROM customer_wardrobe WHERE product_id = ? AND username = ?"); 
    $stmt->bind_param("ss", $wardrobeId, $username); // Use the wardrobe ID
    
    if ($stmt->execute()) {
        // Success: Redirect back to the page where products are displayed
        header("Location: customer_wardrobe.php?success=Product Remove from Wardrobe!");  
        exit();
    } else {
        // Handle the error (e.g., display an error message)
        echo "Error removing product: " . $stmt->error;
    }
} else {
    echo "Missing product_id or wardrobe_id"; // Handle missing parameters
}
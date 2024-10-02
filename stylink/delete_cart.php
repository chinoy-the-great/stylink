<?php
include("include/config.php"); // Include your database connection file

if(isset($_GET['product_id']) && isset($_GET['product_color']) && isset($_GET['product_size'])) {
    $productId = $_GET['product_id'];
    $productColor = $_GET['product_color'];
    $productSize = $_GET['product_size'];

    // Prepare and execute the delete query
    $deleteStmt = $conn->prepare("DELETE FROM customer_cart WHERE product_id = ? AND product_color = ? AND product_size = ?");
    $deleteStmt->bind_param("sss", $productId, $productColor, $productSize);

    if ($deleteStmt->execute()) {
        // Deletion successful, redirect back to the cart page
        header("Location: customer_cart.php"); // Replace 'cart.php' with your actual cart page filename
        exit();
    } else {
        // Handle deletion error (e.g., display an error message)
        echo "Error deleting item from cart: " . $deleteStmt->error;
    }

    $deleteStmt->close();
} else {
    // Handle invalid request (e.g., redirect to the cart page)
    header("Location: customer_cart.php");
    exit();
}
?>

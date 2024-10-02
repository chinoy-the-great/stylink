<?php
// 1. Database Connection (Replace placeholders with your actual credentials)
include("include/config.php");

// 2. Get Data from AJAX Request
$data = json_decode(file_get_contents('php://input'), true);
$productId = $data['productId'];
$productColor = $data['productColor'];
$productSize = $data['productSize']; // Get product size from the request
$quantity = $data['quantity'];

// 3. Update Database
$stmt = $conn->prepare("UPDATE customer_cart SET product_quantity = ? WHERE product_id = ? AND product_color = ? AND product_size = ?");
$stmt->bind_param("isss", $quantity, $productId, $productColor, $productSize);
$stmt->execute();

// 4. Close Connection
$stmt->close();
$conn->close();
?>

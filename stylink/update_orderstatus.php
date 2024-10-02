<?php
session_start();  // Start the session to access username

// include_once("config1.php");
include("include/config.php");


// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input to prevent SQL injection (using prepared statements)
    $transaction_id = $conn->real_escape_string($_POST['transaction_id']);
    $shop_name = $conn->real_escape_string($_POST['shop_name']);
    $order_status = $conn->real_escape_string($_POST['order_status']);

    // Update the order status
    $sql = "UPDATE checkout_order 
            SET checkout_status = ? 
            WHERE transaction_id = ? AND shop_name = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $order_status, $transaction_id, $shop_name);

    if ($stmt->execute()) {
        echo "Order status updated successfully!";
        // You might want to redirect back to the order details page
        header("Location: seller_order.php?success=Order status updated successfully!");
        exit();
    } else {
        echo "Error updating order status: " . $stmt->error;
    }
}

$conn->close();
?>
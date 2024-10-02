<?php
session_start();
include("include/config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_SESSION['username'];
    $modePayment = $_POST['modePayment'];
    $referenceNo = $_POST['reference_no'];
    $totalOverallPrice = floatval(str_replace(['₱', ','], ['', ''], $_POST['total_overallprice']));
    $totalCount = $_POST['total_count'];

    $transactionId = uniqid();

    $conn->begin_transaction();

    try {
        $insertStmt = $conn->prepare("INSERT INTO checkout_order (transaction_id, username, product_id, product_name, product_price, product_quantity, total_price, modePayment, total_overallprice, total_count, reference_no, checkout_status, shop_name) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        foreach ($_POST['product_id'] as $key => $productId) {
            // Explicitly cast $productId to an integer
            // $productId = intval($productId);

            $productName = $_POST['product_name'][$key];
            $shop_name = $_POST['shop_name'][$key];
            // $productId2 = $_POST['product_id2'][$key];
            $productPrice = floatval($_POST['product_price'][$key]);
            $productQuantity = intval($_POST['product_quantity'][$key]);
            $totalPrice = $productPrice * $productQuantity;
            $checkoutStatus = 'pending';

            $insertStmt->bind_param("ssssdidssdsss", $transactionId, $username, $productId, $productName, $productPrice, $productQuantity, $totalPrice, $modePayment, $totalOverallPrice, $totalCount, $referenceNo, $checkoutStatus, $shop_name);

            if (!$insertStmt->execute()) {
                // Throw an exception if the insert fails
                throw new Exception("Error inserting order: " . $insertStmt->error);
            }
        }

        $insertStmt->close();

        $clearCartStmt = $conn->prepare("DELETE FROM customer_cart WHERE username = ?");
        $clearCartStmt->bind_param("s", $username);
        $clearCartStmt->execute();
        $clearCartStmt->close();

        $conn->commit();

        header("Location: order_success.php?transaction_id=" . $transactionId);
        exit();
    } catch (Exception $e) {
        // If an error occurs, rollback the transaction
        $conn->rollback();

        // Log the error for debugging
        error_log("Checkout error: " . $e->getMessage());

        // Display a user-friendly error message
        echo "An error occurred during checkout. Please try again later.";
    }
} else {
    echo "Invalid request.";
}

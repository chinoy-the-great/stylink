<?php
ob_start();
session_start();
include("include/config.php");

// Initialize variables for error and success messages
$error = null;
$success = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_SESSION["username"])) {
        $username = $_SESSION["username"];
        $productId = $_POST["product_id"];
        $productName = $_POST["product_name"];
        $productPrice = $_POST["product_price"];
        $productSize = $_POST["product_size"];
        $productColor = $_POST["product_color"];
        $productQuantity = $_POST["product_quantity"];
        $productTypeclothes = $_POST["product_typeclothes"];
        $productStyle = $_POST["product_style"];


        // Basic Input Validation
        if (empty($productId)) {
            $error = "Please fill out all required fields.";
        } else {
            // Check if the item already exists in the cart for this user
            $checkStmt = $conn->prepare("SELECT * FROM customer_wardrobe WHERE username = ? AND product_id = ? AND product_size = ? AND product_color = ?");
            $checkStmt->bind_param("ssss", $username, $productId, $productSize, $productColor);
            $checkStmt->execute();
            $checkResult = $checkStmt->get_result();

            if ($checkResult->num_rows > 0) {
                // Item already exists, update quantity
                $updateStmt = $conn->prepare("UPDATE customer_wardrobe SET product_quantity = product_quantity + ? WHERE username = ? AND product_id = ? AND product_size = ? AND product_color = ?");
                $updateStmt->bind_param("issss", $productQuantity, $username, $productId, $productSize, $productColor);

                if ($updateStmt->execute()) {
                    $success = "Item quantity updated in cart.";
                } else {
                    $error = "Error updating item quantity: " . $updateStmt->error;
                }

                $updateStmt->close();
            } else {

                // Item doesn't exist, insert new row (Corrected query)
                $stmt = $conn->prepare("INSERT INTO customer_wardrobe (username, product_id, product_name, product_price, product_size, product_color, product_quantity, product_typeclothes, product_style, wardrobe_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'Active')"); // Removed extra comma
                $stmt->bind_param("ssssssdss", $username, $productId, $productName, $productPrice, $productSize, $productColor, $productQuantity, $productTypeclothes, $productStyle);


                if ($stmt->execute()) {
                    $success = "Item added to Wardrobe.";
                } else {
                    $error = "Error adding item to Wardrobe: " . $stmt->error;
                }

                $stmt->close();
            }
            $success = "Product added to Wardrobe.sss";
            // Redirect using full URL
            $_SESSION['success_message'] = "Product added to Wardrobe.";
            $_SESSION['username'] ;
            $redirectUrl = "single-product.php?product_id=$productId";
            header("Location: $redirectUrl");
            exit;
        }
    } else {
        $error = "You need to be logged in to add items to the Wardrobe.";
        //         header("single-product.php?product_id=$productId");
        header("Location: user_login.php?error=You need to be logged in to add items to the Wardrobe.");
        exit; // Stop script execution after redirect
        // exit;
    }
}

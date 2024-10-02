<?php
include("include/config.php");

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $product_id = $_POST["product_id"];
    $product_name = $_POST["product_name"];
    $product_price = $_POST["product_price"];
    $product_stocks = $_POST["product_stocks"];

    // Validate Input (add more validation as needed)
    if (empty($product_id) || empty($product_name) || empty($product_price) || empty($product_stocks)) {
        echo "Please fill in all fields.";
    } else {
        // Prepare and execute the update query
        $sql = "UPDATE product_list 
                SET product_name = ?, product_price = ?, product_stocks = ?
                WHERE product_id = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sdis", $product_name, $product_price, $product_stocks, $product_id);

        if ($stmt->execute()) {
            echo "Product updated successfully.";
            header("Location: seller_manageproduct.php?success=Product updated Successfully"); // Redirect with success message
            exit();
        } else {
            echo "Error updating product: " . $stmt->error;
            header("Location: seller_manageproduct.php?success=Error updating Product"); // Redirect with success message
            exit();
        }

        $stmt->close();
    }
}

$conn->close();

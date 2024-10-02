<?php
session_start();
include("include/config.php");

if (!isset($_SESSION["username"])) {
    header("Location: user_login.php");
    exit;
}

$username = $_SESSION["username"];
$productId = $_GET["product_id"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productName = $_POST["product_name"];
    $productPrice = $_POST["product_price"];
    $productBrand = $_POST["product_brand"];
    $productType = $_POST["product_type"];
    $productCategory = $_POST["product_category"];
    $productDescription = $_POST["product_description"];
    $productStyle = $_POST["product_style"];
    $productTypeclothes = $_POST["product_typeclothes"];
    $productStocks = $_POST["product_stocks"];
    $productArrival = $_POST["new_arrival"];
    $productFeatured = $_POST["featured_product"];

    // Handle Image Upload (only if an image was uploaded)
    $updateImage = false; // Flag to indicate if image needs to be updated
    if (isset($_FILES["product_image"]) && $_FILES["product_image"]["error"] == 0) {
        $targetDir = "uploads/cover/";
        $targetFile = $targetDir . basename($_FILES["product_image"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["product_image"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $error = "File is not an image.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        $allowedTypes = array("jpg", "jpeg", "png", "gif");
        if (!in_array($imageFileType, $allowedTypes)) {
            $error = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo $error;
        } else {
            $updateImage = true; // Set flag to update the image in the database
        }
    }

    // Update product details in the database
    if ($updateImage && move_uploaded_file($_FILES["product_image"]["tmp_name"], $targetFile)) {
        // Update with image
        $stmt = $conn->prepare("UPDATE product_list SET product_name = ?, product_price = ?, product_brand = ?, product_type = ?, product_category = ?, product_description = ?, product_style = ?, product_typeclothes = ? , product_stocks = ?, new_arrival = ?, featured_product = ?, product_image = ? WHERE product_id = ? AND username = ?");
        $stmt->bind_param("ssssssssssssss", $productName, $productPrice, $productBrand, $productType, $productCategory, $productDescription, $productStyle, $productTypeclothes, $productStocks, $productArrival, $productFeatured, $targetFile, $productId, $username);
    } else {
        // Update without image
        $stmt = $conn->prepare("UPDATE product_list SET product_name = ?, product_price = ?, product_brand = ?, product_type = ?, product_category = ?, product_description = ?, product_style = ?, product_typeclothes = ? , product_stocks = ?, new_arrival = ?, featured_product = ? WHERE product_id = ? AND username = ?");
        $stmt->bind_param("sssssssssssss", $productName, $productPrice, $productBrand, $productType, $productCategory, $productDescription, $productStyle, $productTypeclothes, $productStocks, $productArrival, $productFeatured, $productId, $username);
    }

    if ($stmt->execute()) {
        $success = "Product updated successfully!";
    } else {
        $error = "Error updating product: " . $stmt->error;
    }
    
    $stmt->close();
}



// Fetch product details from product_list table for the specific product_id
$stmt = $conn->prepare("SELECT * FROM product_list WHERE product_id = ? AND username = ?"); 
$stmt->bind_param("ss", $productId, $username);
$stmt->execute();
$result = $stmt->get_result();
$productRow = $result->fetch_assoc(); // Store retrieved data in $productRow

$stmt->close();
$conn->close();
?>

<?php
session_start();
include("include/config.php");

// Check if username is set in the session
if (!isset($_SESSION['username'])) {
    // Redirect to login or error page if not logged in
    header("Location: login.php"); // Replace with your login page
    exit();
}

$username = $_SESSION['username'];

// Fetch original data for comparison
$sql = "SELECT * FROM seller_register WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$sellerRegisterData = $result->fetch_assoc();

// Handle Image Upload
$shopImage = $sellerRegisterData['shop_image']; // Default to original image
if ($_FILES['shop_image']['size'] > 0) {
    $targetDir = "uploads/"; // Image upload directory
    $imageFileType = strtolower(pathinfo($_FILES["shop_image"]["name"], PATHINFO_EXTENSION));
    $newFileName = uniqid() . "." . $imageFileType; // Unique file name
    $targetFile = $targetDir . $newFileName;

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["shop_image"]["tmp_name"]);
    if ($check === false) {
        die("File is not an image.");
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        die("Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
    }

    if (move_uploaded_file($_FILES["shop_image"]["tmp_name"], $targetFile)) {
        $shopImage = $targetFile;
    } else {
        die("Sorry, there was an error uploading your file.");
    }
}

// Prepare and execute the update query
$sql = "UPDATE seller_register SET 
        shop_image = ?,
        store_type = ?,
        contact_no = ?,
        email = ?,
        facebook = ?,
        instagram = ?,
        twitter = ?,
        province = ?,
        municipality = ?,
        barangay = ?,
        shop_name = ?
        WHERE username = ?"; 

$stmt = $conn->prepare($sql);

// Ensure all parameters have values (use empty strings if not set)
$facebook = isset($_POST['facebook']) ? $_POST['facebook'] : '';
$instagram = isset($_POST['instagram']) ? $_POST['instagram'] : '';
$twitter = isset($_POST['twitter']) ? $_POST['twitter'] : '';

// Correctly bind parameters
$stmt->bind_param("ssssssssssss",
    $shopImage, 
    $_POST['store_type'],
    $_POST['contact_no'],
    $_POST['email'],
    $facebook,
    $instagram,
    $twitter,
    $_POST['province'],
    $_POST['municipality'],
    $_POST['barangay'],
    $_POST['shop_name'],
    $username);

if ($stmt->execute()) {
    header("Location: seller_information.php?success=Seller Information Updated!");
    exit();
} else {
    echo "Error updating record: " . $stmt->error;
}

$stmt->close();
$conn->close();

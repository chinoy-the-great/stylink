<?php
session_start();
include("include/config.php");

// Check if username is set in the session
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

$username = $_SESSION['username'];

// Fetch existing data from the seller_information table
$sql = "SELECT * FROM seller_information WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$sellerInformationData = $result->fetch_assoc();
$stmt->close(); // Close the statement

// Handle BIR Certificate Upload (if changed)
$birImage = $sellerInformationData['bir_image'];
if ($_FILES['bir_image']['size'] > 0) {
    $targetDir = "uploads/"; 
    $imageFileType = strtolower(pathinfo($_FILES["bir_image"]["name"], PATHINFO_EXTENSION));
    $newFileName = uniqid() . "." . $imageFileType; 
    $targetFile = $targetDir . $newFileName;

    // Basic image validation
    $check = getimagesize($_FILES["bir_image"]["tmp_name"]);
    if ($check === false) {
        die("File is not an image.");
    } 

    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        die("Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
    }

    if (move_uploaded_file($_FILES["bir_image"]["tmp_name"], $targetFile)) {
        $birImage = $targetFile;
    } else {
        die("Sorry, there was an error uploading your file.");
    }
}

// Prepare update query
$sql = "UPDATE seller_information SET 
        seller_type = ?,
        tin_id = ?,
        tax_status = ?,
        bir_image = ?,
        trade_mark = ?,
        sworn_declaration = ?,
        province = ?,
        municipality = ?,
        barangay = ? 
        WHERE username = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssssss",
    $_POST['seller_type'],
    $_POST['tin_id'],
    $_POST['tax_status'],
    $birImage, 
    $_POST['trade_mark'],
    $_POST['sworn_declaration'],
    $_POST['province'],
    $_POST['municipality'],
    $_POST['barangay'],
    $username); 

// Execute the query
if ($stmt->execute()) {
    header("Location: seller_information.php?success=Business Information Updated!");
    exit();
} else {
    echo "Error updating record: " . $stmt->error;
}
$stmt->close();
$conn->close();

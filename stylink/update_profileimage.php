<?php
session_start();
include("include/config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // File Upload Handling
    $targetDir = "upload_profile/";
    $targetFile = $targetDir . basename($_FILES["profile_image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    $username = $_SESSION['username']; // Get the username

    // Check if image file is actually uploaded
    if (empty($_FILES["profile_image"]["tmp_name"])) {
        echo "Error: No file was uploaded.";
        header("Location: customer_profile.php?error=No file was uploaded.");
        exit();
    }

    // Check if image file is a valid image
    $check = getimagesize($_FILES["profile_image"]["tmp_name"]); 
    if ($check === false) {
        echo "File is not an image.";
        header("Location: customer_profile.php?error=File is not an image.");
        exit();
    }

    // // Check if file already exists (optional)
    // if (file_exists($targetFile)) {
    //     echo "Sorry, file already exists.";
    //     header("Location: customer_profile.php?error=Sorry, file already exists.");
    //     exit();
    // }

    // Check file size (optional, you can set a limit)
    // if ($_FILES["profile_image"]["size"] > 500000) { // 500KB limit (example)
    //     echo "Sorry, your file is too large.";
    //     $uploadOk = 0;
    // }

    // Allow certain file formats
    $allowedTypes = array("jpg", "jpeg", "png", "gif");
    if (!in_array($imageFileType, $allowedTypes)) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        header("Location: customer_profile.php?error=Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
        exit();
    }

    // Check if $uploadOk is set to 0 by an error (not needed if you've handled all errors above)
    // if ($uploadOk == 0) {
    //     echo "Sorry, your file was not uploaded.";
    // } else {
    // Try to upload the file
    if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $targetFile)) {
        // Update database 
        $sql = "UPDATE users SET profile_image = ? WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $targetFile, $username); // "ss" for string parameters

        if ($stmt->execute()) {
            header("Location: customer_profile.php?success=Profile picture updated successfully");
            exit();
        } else {
            echo "Error updating record: " . $stmt->error;
            header("Location: customer_profile.php?error=Error updating record");
            exit();
        }
    } else {
        echo "Sorry, there was an error uploading your file.";
        header("Location: customer_profile.php?error=Sorry, there was an error uploading your file.");
        exit();
    }
    //}
}
$conn->close();

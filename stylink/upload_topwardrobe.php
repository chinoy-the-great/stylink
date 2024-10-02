<?php
ob_start(); // Start output buffering

session_start();
include("include/config.php");

$error = "";  // Initialize an error message variable
$success = ""; // Initialize a success message variabl

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_FILES['top_clothes']['name'][0])) {
        // $error = "Please select at least one image to upload."; // Set error for no file selection
        header("Location: customer_customizewardrobe.php?error= Please select at least one image to upload.");
        exit;
    } else {
        $username = $_SESSION["username"]; // Get username from session
        $targetDir = "uploads/wardrobe_top/"; // Directory to store uploaded images

        // Check if directory exists, create it if not
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true); // Create directory with permissions
        }
        // Handle multiple file uploads
        $fileCount = count($_FILES['top_clothes']['name']);
        for ($i = 0; $i < $fileCount; $i++) {
            $targetFile = $targetDir . basename($_FILES["top_clothes"]["name"][$i]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

            // Check if image file is a actual image or fake image
            $check = getimagesize($_FILES["top_clothes"]["tmp_name"][$i]);
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
                // break; // Stop the loop if there's an error
            } else {
                // If everything is ok, try to upload file
                if (move_uploaded_file($_FILES["top_clothes"]["tmp_name"][$i], $targetFile)) {
                    // Insert image path into database
                    $stmt = $conn->prepare("INSERT INTO wardrobe_top (username, clothes_image) VALUES (?, ?)");
                    $stmt->bind_param("ss", $username, $targetFile);

                    if ($stmt->execute()) {
                        $success = "Image(s) uploaded successfully!";
                    } else {
                        $error = "Error: " . $stmt->error;
                    }

                    $stmt->close();
                } else {
                    $error = "Sorry, there was an error uploading your file.";
                }
            }
        }
    } // End of for loop
} // End of if POST request
// Redirect with messages and handle display
header("Location: customer_customizewardrobe.php" . (isset($success) ? "?success=$success" : ""));
exit;
ob_end_flush();

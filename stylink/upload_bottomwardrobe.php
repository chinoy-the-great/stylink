<?php
ob_start(); // Start output buffering

session_start();
include("include/config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_FILES['bottom_clothes']['name'][0])) {
        // $error = "Please select at least one image to upload."; // Set error for no file selection
        header("Location: customer_customizewardrobe.php?error= Please select at least one image to upload.");
        exit;
    } else {

        $username = $_SESSION["username"]; // Get username from session
        $targetDir = "uploads/wardrobe_bottom/"; // Directory to store uploaded images

        // Check if directory exists, create it if not
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true); // Create directory with permissions
        }
        // Handle multiple file uploads
        $fileCount = count($_FILES['bottom_clothes']['name']);
        for ($i = 0; $i < $fileCount; $i++) {
            $targetFile = $targetDir . basename($_FILES["bottom_clothes"]["name"][$i]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

            // Check if image file is a actual image or fake image
            $check = getimagesize($_FILES["bottom_clothes"]["tmp_name"][$i]);
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

            //Check if filename already exists in database
            // $checkExistingImageStmt = $conn->prepare("SELECT * FROM wardrobe_bottom WHERE clothes_image = ?");
            // $checkExistingImageStmt->bind_param("s", $targetFile);
            // $checkExistingImageStmt->execute();
            // $checkExistingImageResult = $checkExistingImageStmt->get_result();

            // if ($checkExistingImageResult->num_rows > 0) {
            //     $error = "Error: This image already exists.";
            //     $uploadOk = 0; 
            // } 

            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                echo $error;
                // break; // Stop the loop if there's an error
            } else {
                // If everything is ok, try to upload file
                if (move_uploaded_file($_FILES["bottom_clothes"]["tmp_name"][$i], $targetFile)) {
                    // Insert image path into database
                    $stmt = $conn->prepare("INSERT INTO wardrobe_bottom (username, clothes_image) VALUES (?, ?)");
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

            // Close the statement to check existing images
            // $checkExistingImageStmt->close();
        }
    } // End of for loop
} // End of if POST request

// Redirect back to the form or another page (add error/success messages to the URL if needed)
// header("Location: customer_customizewardrobe.php");
header("Location: customer_customizewardrobe.php" . (isset($success) ? "?success=$success" : ""));

exit; // Make sure to exit after the redirect

ob_end_flush(); // End output buffering

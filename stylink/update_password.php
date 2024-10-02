<?php
session_start();  // Start the session to access username

// include_once("config1.php");
include("include/config.php");

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") { 

    // Get form data (sanitized for security)
    $oldPassword = $_POST['old_password']; 
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Get username from session
    $username = $_SESSION['username']; // Assuming username is stored in the session

    // Fetch the user's data
    $sql = "SELECT password FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($result->num_rows > 0) {
        $hashedPassword = $row['password']; // Get the stored hashed password

        // Verify the old password (using password_verify)
        if (password_verify($oldPassword, $hashedPassword)) {

            // Check if the new passwords match
            if ($newPassword === $confirmPassword) {

                // Hash the new password (using password_hash)
                $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);

                // Update the password
                $sql = "UPDATE users SET password = ? WHERE username = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ss", $hashedNewPassword, $username);
                if ($stmt->execute()) {
                    echo "Password updated successfully";
                    header("Location: customer_profile.php?success=Password updated successfully");
                exit();

                } else {
                    echo "Error updating password: " . $conn->error;
                }

            } else {
                echo "New passwords do not match";
                header("Location: customer_profile.php?error=New passwords do not match");
                exit();
            }
        } else {
            echo "Incorrect old password";
            header("Location: customer_profile.php?error=Incorrect old password");
            exit();
        }
    } else {
        echo "User not found";
        header("Location: customer_profile.php?error=User not Found");
        exit();
    }

    $stmt->close();
    $conn->close();
}



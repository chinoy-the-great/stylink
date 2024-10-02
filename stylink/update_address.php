<?php
session_start();
include("include/config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_SESSION['username'];
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $phoneNo = $_POST['phone_no'];
    $province = $_POST['province'];
    $municipality = $_POST['municipality'];
    $barangay = $_POST['barangay'];
    $fullAddress = $_POST['full_address'];


    // Check if the user's address already exists
    $checkQuery = $conn->prepare("SELECT * FROM customer_address WHERE username = ?");
    $checkQuery->bind_param("s", $username);
    $checkQuery->execute();
    $result = $checkQuery->get_result();

    if ($result->num_rows > 0) {
        // Address exists, so update it
        $updateQuery = $conn->prepare("UPDATE customer_address SET first_name = ?, last_name = ?, phone_no = ?, province = ?, municipality = ?, barangay = ?, full_address = ? WHERE username = ?");
        $updateQuery->bind_param("ssssssss", $firstName, $lastName, $phoneNo, $province, $municipality, $barangay, $fullAddress, $username); // Eight parameters

        if ($updateQuery->execute()) {
            header("Location: customer_profile.php?success=Address updated successfully");
            exit();
        } else {
            header("Location: customer_profile.php?error=Error updating address"); // Redirect with error message
            exit();
        }
    } else {
        // Address doesn't exist, so insert a new one
        $insertQuery = $conn->prepare("INSERT INTO customer_address (username, first_name, last_name, phone_no, province, municipality, barangay, full_address) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $insertQuery->bind_param("ssssssss", $username, $firstName, $lastName, $phoneNo, $province, $municipality, $barangay, $fullAddress); // Eight parameters

        if ($insertQuery->execute()) {
            header("Location: customer_profile.php?success=Address added successfully"); // Redirect with success message
            exit();
        } else {
            header("Location: customer_profile.php?error=Error adding address"); // Redirect with error message
            exit();
        }
    }
} else {
    // If the form is not submitted, redirect back to the address form (or wherever you want)
    header("Location: customer_profile.php"); // Redirect without any message
    exit();
}

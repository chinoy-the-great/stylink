<?php
include("include/config.php"); // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data

    $username = $_POST['username']; 
    $firstName = $_POST["first_name"];
    $lastName = $_POST["last_name"];
    $phoneNo = $_POST["phone_no"];
    $province = $_POST["province"];
    $municipality = $_POST["customer_municipal"]; // Corrected field name
    $barangay = $_POST["customer_barangay"]; // Corrected field name
    $fullAddress = $_POST["full_address"];

    // Validate input (add more validation as needed)
    if (empty($firstName) || empty($lastName) || empty($phoneNo) || empty($fullAddress)) {
        echo "Please fill in all required fields.";
        exit();
    }

    // Prepare and execute the SQL query
    $stmt = $conn->prepare("INSERT INTO customer_address (username, first_name, last_name, phone_no, province, municipality, barangay, full_address) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $username, $firstName, $lastName, $phoneNo, $province, $municipality, $barangay, $fullAddress);

    if ($stmt->execute()) {
        // Success: Redirect or display a success message
        header("Location: customer_profile.php?success=Delivery Address succefully Added"); // Redirect to the cart page after adding the address
        exit(); 
    } else {
        // Error: Display an error message
        echo "Error inserting address: " . $stmt->error;
    }
}
?>

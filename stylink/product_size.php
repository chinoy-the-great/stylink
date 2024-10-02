<?php
// Database connection (Replace with your own credentials)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "stylink";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Form handling (Only if method is POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productId = $_POST["product_id"];
    $productSize = $_POST["product_size"];

    // Insert into database
    $sql = "INSERT INTO product_sizes (product_id, product_size) VALUES ('$productId', '$productSize')";

    if ($conn->query($sql) === TRUE) {
        // Fetch the newly inserted row (for updating the table)
        $last_id = $conn->insert_id;
        $sql = "SELECT product_size FROM product_sizes WHERE id = $last_id";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            // Send back the newly inserted size (JSON format)
            echo json_encode($row["product_size"]);
        } else {
            echo "Error fetching new data.";
        }
    } else {
        echo "Error inserting data: " . $conn->error;
    }
}

$conn->close();
?>

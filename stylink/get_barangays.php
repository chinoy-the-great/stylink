<?php
// Database connection details (replace with yours)
include("include/config.php");

// **2. Retrieve Municipality from Request**
$municipality = isset($_GET['municipality']) ? $_GET['municipality'] : ''; 

// **3. Fetch Barangays (Assuming a 'barangays' table exists)**
$query = "SELECT DISTINCT name FROM barangays WHERE municipality = ?";

// Prepare the statement to protect against SQL injection
$stmt = mysqli_prepare($conn, $query); 
if (!$stmt) {
    die("Query preparation failed: " . mysqli_error($conn));
}

// Bind the municipality name to the query parameter
mysqli_stmt_bind_param($stmt, "s", $municipality); 

// Execute the statement
mysqli_stmt_execute($stmt);

// Get the result
$result = mysqli_stmt_get_result($stmt);

// **4. Prepare Data**
$barangays = [];
while ($row = mysqli_fetch_assoc($result)) {
    $barangays[] = $row['name']; 
}

// **5. Output Data as JSON**
header('Content-Type: application/json');
echo json_encode($barangays);

// **6. Close Connections**
mysqli_stmt_close($stmt);
mysqli_close($conn); 
?>

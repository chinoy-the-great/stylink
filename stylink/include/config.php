<?php
// Database Connection (Replace with your credentials)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "stylink";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}




?>
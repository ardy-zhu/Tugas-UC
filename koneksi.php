<?php
// Database Connection Configuration
$host = "localhost";
$username = "root";
$password = "";
$database = "miapp";

// Create connection
$koneksi = mysqli_connect($host, $username, $password, $database);

// Check connection
if (!$koneksi) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set charset to utf8
mysqli_set_charset($koneksi, "utf8");

// Optional: Display success message (remove in production)
// echo "Connected successfully";

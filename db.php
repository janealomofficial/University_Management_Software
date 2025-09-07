<?php
// Database configuration

$DB_HOST = "localhost";   // Database host
$DB_USER = "root";        // Default XAMPP/WAMP user
$DB_PASS = "";            // Default XAMPP/WAMP password is empty
$DB_NAME = "university_ms"; // Database name

try {
    $DB_con = new PDO("mysql:host={$DB_HOST};dbname={$DB_NAME}", $DB_USER, $DB_PASS);
    $DB_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Database connected successfully"; // Uncomment to test
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
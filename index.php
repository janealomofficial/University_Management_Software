<?php
session_start();
require_once __DIR__ . "/app/config/db.php";

// Redirect logged-in users to their dashboard based on role
if (isset($_SESSION['role'])) {
    switch ($_SESSION['role']) {
        case 'admin':
            header("Location: admin/dashboard.php");
            exit;
        case 'faculty':
            header("Location: faculty/dashboard.php");
            exit;
        case 'student':
            header("Location: student/dashboard.php");
            exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>University Management System</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; }
        a { text-decoration: none; color: #007BFF; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>

<h1>Welcome to University Management System</h1>

<p>
    <a href="login.php">Login</a> | 
    <a href="register.php">Register</a>
</p>

<h2>Features</h2>
<ul>
    <li><strong>Admin:</strong> Manage Students, Faculty, Courses</li>
    <li><strong>Faculty:</strong> View Assigned Courses & Enrolled Students</li>
    <li><strong>Student:</strong> Enroll in Courses & View Enrollments</li>
</ul>

</body>
</html>

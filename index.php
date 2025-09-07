<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: ../login.php");
    exit;
}
echo "<h1>Welcome Student Dashboard</h1>";
echo "<a href='../logout.php'>Logout</a>";

<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'faculty') {
    header("Location: ../login.php");
    exit;
}
echo "<h1>Welcome Faculty Dashboard</h1>";
echo "<a href='../logout.php'>Logout</a>";

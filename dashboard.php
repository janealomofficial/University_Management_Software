<?php
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role']!=='admin'){
    header("Location: ../login.php"); exit;
}
?>

<h1>Admin Dashboard</h1>
<ul>
    <li><a href="students.php">Manage Students</a></li>
    <li><a href="faculty.php">Manage Faculty</a></li>
    <li><a href="courses.php">Manage Courses</a></li>
    <li><a href="../logout.php">Logout</a></li>
</ul>

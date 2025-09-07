<?php
require_once __DIR__ . "/../app/config/db.php";

// Fetch student with user_id=3
$stmt = $DB_con->prepare("
    SELECT u.username, s.full_name, s.department, s.contact 
    FROM users u
    JOIN students s ON u.user_id = s.user_id
    WHERE u.user_id = 3
");
$stmt->execute();
$student = $stmt->fetch(PDO::FETCH_ASSOC);

echo "<h2>Student Profile</h2>";
print_r($student);

// Fetch faculty with user_id=2
$stmt = $DB_con->prepare("
    SELECT u.username, f.full_name, f.designation, f.department 
    FROM users u
    JOIN faculty f ON u.user_id = f.user_id
    WHERE u.user_id = 2
");
$stmt->execute();
$faculty = $stmt->fetch(PDO::FETCH_ASSOC);

echo "<h2>Faculty Profile</h2>";
print_r($faculty);

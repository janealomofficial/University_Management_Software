<?php
require_once __DIR__ . "/../app/config/db.php";

$users = [
    ['admin1', 'admin@example.com', 'admin123', 'admin'],
    ['faculty1', 'faculty@example.com', 'faculty123', 'faculty'],
    ['student1', 'student@example.com', 'student123', 'student'],
];

foreach ($users as $u) {
    $stmt = $DB_con->prepare("INSERT INTO users (username, email, password, role, status) VALUES (:username, :email, :password, :role, 'active')");
    $stmt->execute([
        ':username' => $u[0],
        ':email' => $u[1],
        ':password' => password_hash($u[2], PASSWORD_DEFAULT), // hash password
        ':role' => $u[3]
    ]);
}

echo "Users inserted successfully with hashed passwords!";
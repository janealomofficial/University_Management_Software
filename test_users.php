<?php
require_once __DIR__ . "/../app/config/db.php";

$stmt = $DB_con->query("SELECT user_id, username, role, status FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<pre>";
print_r($users);
echo "</pre>";

<?php
session_start();
require_once __DIR__ . "/app/config/db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Fetch user
    $stmt = $DB_con->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
    $stmt->execute([':username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Store session data
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['username'] = $user['username'];

        // Redirect based on role
        switch ($user['role']) {
            case 'admin':
                header("Location: admin/dashboard.php");
                exit;
            case 'faculty':
                header("Location: faculty/dashboard.php");
                exit;
            case 'student':
                header("Location: student/dashboard.php");
                exit;
            default:
                echo "Invalid role!";
        }
    } else {
        echo "<p style='color:red;'>Invalid username or password</p>";
    }
}
?>

<h2>Login</h2>
<form method="post">
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
</form>

<?php
session_start();
require_once __DIR__ . "/../../app/config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $full_name = $_POST['full_name'];
    $department = $_POST['department'];
    $contact = $_POST['contact'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        // Insert into users table
        $stmt = $DB_con->prepare("INSERT INTO users (username, email, password, role, status) VALUES (:username, :email, :password, 'student', 'active')");
        $stmt->execute([
            ':username' => $username,
            ':email' => $email,
            ':password' => $password
        ]);
        $user_id = $DB_con->lastInsertId();

        // Insert into students table
        $stmt2 = $DB_con->prepare("INSERT INTO students (user_id, full_name, department, contact) VALUES (:uid, :name, :dept, :contact)");
        $stmt2->execute([
            ':uid' => $user_id,
            ':name' => $full_name,
            ':dept' => $department,
            ':contact' => $contact
        ]);

        $message = "Student added successfully!";
    } catch (PDOException $e) {
        $message = "Error: " . $e->getMessage();
    }
}
?>

<h2>Add New Student</h2>
<?php if($message) echo "<p>$message</p>"; ?>
<form method="post">
    <input type="text" name="username" placeholder="Username" required><br><br>
    <input type="email" name="email" placeholder="Email" required><br><br>
    <input type="password" name="password" placeholder="Password" required><br><br>
    <input type="text" name="full_name" placeholder="Full Name" required><br><br>
    <input type="text" name="department" placeholder="Department"><br><br>
    <input type="text" name="contact" placeholder="Contact"><br><br>
    <button type="submit">Add Student</button>
</form>

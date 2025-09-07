<?php
session_start();
require_once __DIR__ . "/../../app/config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Handle deletion
if (isset($_GET['delete'])) {
    $student_id = $_GET['delete'];
    $stmt = $DB_con->prepare("DELETE FROM students WHERE student_id = :id");
    $stmt->execute([':id' => $student_id]);
    header("Location: students.php");
    exit;
}

// Fetch all students
$stmt = $DB_con->query("
    SELECT s.student_id, s.full_name, s.department, s.contact, u.username, u.email
    FROM students s
    JOIN users u ON s.user_id = u.user_id
");
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Admin: Manage Students</h2>
<a href="add_student.php">Add New Student</a>
<table border="1" cellpadding="5">
    <tr>
        <th>ID</th>
        <th>Full Name</th>
        <th>Username</th>
        <th>Email</th>
        <th>Department</th>
        <th>Contact</th>
        <th>Actions</th>
    </tr>
    <?php foreach($students as $s): ?>
    <tr>
        <td><?php echo $s['student_id']; ?></td>
        <td><?php echo $s['full_name']; ?></td>
        <td><?php echo $s['username']; ?></td>
        <td><?php echo $s['email']; ?></td>
        <td><?php echo $s['department']; ?></td>
        <td><?php echo $s['contact']; ?></td>
        <td>
            <a href="edit_student.php?id=<?php echo $s['student_id']; ?>">Edit</a> |
            <a href="?delete=<?php echo $s['student_id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<?php
session_start();
require_once __DIR__ . "/../../app/config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Handle deletion
if (isset($_GET['delete'])) {
    $faculty_id = $_GET['delete'];
    $stmt = $DB_con->prepare("DELETE FROM faculty WHERE faculty_id = :id");
    $stmt->execute([':id' => $faculty_id]);
    header("Location: faculty.php");
    exit;
}

// Fetch all faculty
$stmt = $DB_con->query("
    SELECT f.faculty_id, f.full_name, f.department, f.contact, u.username, u.email
    FROM faculty f
    JOIN users u ON f.user_id = u.user_id
");
$faculty_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Admin: Manage Faculty</h2>
<a href="add_faculty.php">Add New Faculty</a>
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
    <?php foreach($faculty_list as $f): ?>
    <tr>
        <td><?php echo $f['faculty_id']; ?></td>
        <td><?php echo $f['full_name']; ?></td>
        <td><?php echo $f['username']; ?></td>
        <td><?php echo $f['email']; ?></td>
        <td><?php echo $f['department']; ?></td>
        <td><?php echo $f['contact']; ?></td>
        <td>
            <a href="edit_faculty.php?id=<?php echo $f['faculty_id']; ?>">Edit</a> |
            <a href="?delete=<?php echo $f['faculty_id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

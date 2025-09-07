<?php
session_start();
require_once __DIR__ . "/../../app/config/db.php";

if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'faculty'){
    header("Location: ../login.php");
    exit;
}

$course_id = $_GET['course_id'] ?? null;
if(!$course_id) exit("Course ID missing");

// Get course info
$course_stmt = $DB_con->prepare("SELECT * FROM courses WHERE course_id = :cid");
$course_stmt->execute([':cid' => $course_id]);
$course = $course_stmt->fetch(PDO::FETCH_ASSOC);

// Get enrolled students
$students_stmt = $DB_con->prepare("
    SELECT s.full_name, u.username
    FROM enrollments e
    JOIN students s ON e.student_id = s.student_id
    JOIN users u ON s.user_id = u.user_id
    WHERE e.course_id = :cid
");
$students_stmt->execute([':cid' => $course_id]);
$students = $students_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h1><?php echo $course['course_name'] . " (" . $course['course_code'] . ")"; ?> - Enrolled Students</h1>

<?php if(count($students) === 0): ?>
    <p>No students enrolled yet.</p>
<?php else: ?>
    <ul>
    <?php foreach($students as $s): ?>
        <li><?php echo $s['full_name'] . " (" . $s['username'] . ")"; ?></li>
    <?php endforeach; ?>
    </ul>
<?php endif; ?>

<p><a href="dashboard.php">Back to Dashboard</a></p>

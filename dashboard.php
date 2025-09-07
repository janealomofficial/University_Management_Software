<?php
session_start();
require_once __DIR__ . "/../app/config/db.php";

if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'student'){
    header("Location: ../login.php");
    exit;
}

// Get student info
$stmt = $DB_con->prepare("SELECT s.full_name, s.department, u.username FROM students s JOIN users u ON s.user_id = u.user_id WHERE u.user_id = :uid");
$stmt->execute([':uid' => $_SESSION['user_id']]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch enrolled courses
$enroll_stmt = $DB_con->prepare("
    SELECT c.course_name, c.course_code, c.department, c.semester
    FROM enrollments e
    JOIN courses c ON e.course_id = c.course_id
    JOIN students s ON e.student_id = s.student_id
    WHERE s.user_id = :uid
");
$enroll_stmt->execute([':uid' => $_SESSION['user_id']]);
$enrolled_courses = $enroll_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h1>Student Dashboard</h1>
<p>Welcome, <?php echo $student['full_name']; ?> (<?php echo $student['username']; ?>)</p>
<p>Department: <?php echo $student['department']; ?></p>

<h2>Your Enrolled Courses</h2>
<?php if(count($enrolled_courses) === 0) echo "<p>You are not enrolled in any courses yet.</p>"; ?>
<ul>
<?php foreach($enrolled_courses as $c): ?>
    <li><?php echo $c['course_name'] . " (" . $c['course_code'] . ") - " . $c['semester']; ?></li>
<?php endforeach; ?>
</ul>

<p><a href="courses.php">Enroll in New Courses</a></p>
<p><a href="../logout.php">Logout</a></p>

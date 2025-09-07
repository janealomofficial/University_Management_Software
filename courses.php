<?php
session_start();
require_once __DIR__ . "/../../app/config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'faculty') {
    header("Location: ../login.php");
    exit;
}

// Get faculty_id from faculty table
$stmt = $DB_con->prepare("SELECT faculty_id FROM faculty WHERE user_id = :uid");
$stmt->execute([':uid' => $_SESSION['user_id']]);
$faculty = $stmt->fetch(PDO::FETCH_ASSOC);
$faculty_id = $faculty['faculty_id'];

// Fetch courses assigned to this faculty
$courses_stmt = $DB_con->prepare("
    SELECT course_id, course_name, course_code, department, semester
    FROM courses
    WHERE faculty_id = :fid
");
$courses_stmt->execute([':fid' => $faculty_id]);
$courses = $courses_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Faculty Dashboard: Assigned Courses</h2>

<?php if(count($courses) == 0) echo "<p>No courses assigned yet.</p>"; ?>

<?php foreach($courses as $c): ?>
<h3><?php echo $c['course_name'] . " (" . $c['course_code'] . ")"; ?></h3>
<p>Department: <?php echo $c['department']; ?> | Semester: <?php echo $c['semester']; ?></p>

<?php
// Fetch students enrolled in this course
$students_stmt = $DB_con->prepare("
    SELECT s.full_name, u.username
    FROM enrollments e
    JOIN students s ON e.student_id = s.student_id
    JOIN users u ON s.user_id = u.user_id
    WHERE e.course_id = :cid
");
$students_stmt->execute([':cid' => $c['course_id']]);
$students = $students_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h4>Enrolled Students:</h4>
<?php if(count($students) == 0): ?>
    <p>No students enrolled yet.</p>
<?php else: ?>
    <ul>
    <?php foreach($students as $s): ?>
        <li><?php echo $s['full_name'] . " (" . $s['username'] . ")"; ?></li>
    <?php endforeach; ?>
    </ul>
<?php endif; ?>
<hr>
<?php endforeach; ?>

<?php
session_start();
require_once __DIR__ . "/../app/config/db.php";

if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'faculty'){
    header("Location: ../login.php");
    exit;
}

// Get faculty info
$stmt = $DB_con->prepare("SELECT f.full_name, f.department, u.username FROM faculty f JOIN users u ON f.user_id = u.user_id WHERE u.user_id = :uid");
$stmt->execute([':uid' => $_SESSION['user_id']]);
$faculty = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch assigned courses
$courses_stmt = $DB_con->prepare("SELECT * FROM courses WHERE faculty_id = :fid");
$courses_stmt->execute([':fid' => $faculty['faculty_id'] ?? 0]);
$courses = $courses_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h1>Faculty Dashboard</h1>
<p>Welcome, <?php echo $faculty['full_name']; ?> (<?php echo $faculty['username']; ?>)</p>
<p>Department: <?php echo $faculty['department']; ?></p>

<h2>Assigned Courses</h2>
<?php if(count($courses) === 0) echo "<p>No courses assigned yet.</p>"; ?>
<ul>
<?php foreach($courses as $c): ?>
    <li>
        <?php echo $c['course_name'] . " (" . $c['course_code'] . ")"; ?>
        - <a href="view_students.php?course_id=<?php echo $c['course_id']; ?>">View Enrolled Students</a>
    </li>
<?php endforeach; ?>
</ul>

<p><a href="../logout.php">Logout</a></p>

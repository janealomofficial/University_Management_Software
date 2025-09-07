<?php
session_start();
require_once __DIR__ . "/../../app/config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: ../login.php");
    exit;
}

// Get student_id from students table
$stmt = $DB_con->prepare("SELECT student_id FROM students WHERE user_id = :uid");
$stmt->execute([':uid' => $_SESSION['user_id']]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);
$student_id = $student['student_id'];

// Handle enrollment
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $course_id = $_POST['course_id'];

    // Check if already enrolled
    $check = $DB_con->prepare("SELECT * FROM enrollments WHERE student_id = :sid AND course_id = :cid");
    $check->execute([':sid' => $student_id, ':cid' => $course_id]);

    if ($check->rowCount() > 0) {
        $message = "You are already enrolled in this course!";
    } else {
        $stmt = $DB_con->prepare("INSERT INTO enrollments (student_id, course_id, semester) VALUES (:sid, :cid, :semester)");
        // Get course semester
        $course_stmt = $DB_con->prepare("SELECT semester FROM courses WHERE course_id = :cid");
        $course_stmt->execute([':cid' => $course_id]);
        $course = $course_stmt->fetch(PDO::FETCH_ASSOC);

        $stmt->execute([
            ':sid' => $student_id,
            ':cid' => $course_id,
            ':semester' => $course['semester']
        ]);
        $message = "Enrolled successfully!";
    }
}

// Fetch all courses
$courses_stmt = $DB_con->query("SELECT course_id, course_name, course_code, department, semester FROM courses");
$courses = $courses_stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch enrolled courses
$enrolled_stmt = $DB_con->prepare("
    SELECT c.course_name, c.course_code, c.department, c.semester
    FROM courses c
    JOIN enrollments e ON c.course_id = e.course_id
    WHERE e.student_id = :sid
");
$enrolled_stmt->execute([':sid' => $student_id]);
$enrolled_courses = $enrolled_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Student Dashboard: Courses</h2>

<?php if(isset($message)) echo "<p>$message</p>"; ?>

<h3>Enroll in a Course</h3>
<form method="post">
    <select name="course_id" required>
        <option value="">--Select Course--</option>
        <?php foreach($courses as $c): ?>
            <option value="<?php echo $c['course_id']; ?>">
                <?php echo $c['course_name'] . " (" . $c['course_code'] . ")"; ?>
            </option>
        <?php endforeach; ?>
    </select>
    <button type="submit">Enroll</button>
</form>

<h3>Your Enrolled Courses</h3>
<table border="1" cellpadding="5">
    <tr>
        <th>Name</th>
        <th>Code</th>
        <th>Department</th>
        <th>Semester</th>
    </tr>
    <?php foreach($enrolled_courses as $ec): ?>
    <tr>
        <td><?php echo $ec['course_name']; ?></td>
        <td><?php echo $ec['course_code']; ?></td>
        <td><?php echo $ec['department']; ?></td>
        <td><?php echo $ec['semester']; ?></td>
    </tr>
    <?php endforeach; ?>
</table>

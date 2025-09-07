<?php
session_start();
require_once __DIR__ . "/../../app/config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Handle new course submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['course_name'];
    $code = $_POST['course_code'];
    $dept = $_POST['department'];
    $faculty_id = $_POST['faculty_id'];
    $semester = $_POST['semester'];

    $stmt = $DB_con->prepare("INSERT INTO courses (course_name, course_code, department, faculty_id, semester) VALUES (:name, :code, :dept, :faculty, :semester)");
    $stmt->execute([
        ':name' => $name,
        ':code' => $code,
        ':dept' => $dept,
        ':faculty' => $faculty_id,
        ':semester' => $semester
    ]);
    echo "<p style='color:green;'>Course added successfully!</p>";
}

// Fetch faculty list for dropdown
$faculty_stmt = $DB_con->query("SELECT faculty_id, full_name FROM faculty");
$faculties = $faculty_stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all courses
$courses_stmt = $DB_con->query("
    SELECT c.course_id, c.course_name, c.course_code, c.department, c.semester, 
           f.full_name AS faculty_name 
    FROM courses c 
    LEFT JOIN faculty f ON c.faculty_id = f.faculty_id
");
$courses = $courses_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin: Manage Courses</title>
</head>
<body>

<h2>Admin: Manage Courses</h2>

<h3>Add New Course</h3>
<form method="post">
    <input type="text" name="course_name" placeholder="Course Name" required>
    <input type="text" name="course_code" placeholder="Course Code" required>
    <input type="text" name="department" placeholder="Department">
    <select name="faculty_id">
        <option value="">--Assign Faculty--</option>
        <?php foreach($faculties as $f): ?>
            <option value="<?php echo $f['faculty_id']; ?>"><?php echo $f['full_name']; ?></option>
        <?php endforeach; ?>
    </select>
    <input type="text" name="semester" placeholder="Semester">
    <button type="submit">Add Course</button>
</form>

<h3>All Courses</h3>
<table border="1" cellpadding="5">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Code</th>
        <th>Department</th>
        <th>Semester</th>
        <th>Faculty</th>
    </tr>
    <?php foreach($courses as $c): ?>
    <tr>
        <td><?php echo $c['course_id']; ?></td>
        <td><?php echo $c['course_name']; ?></td>
        <td><?php echo $c['course_code']; ?></td>
        <td><?php echo $c['department']; ?></td>
        <td><?php echo $c['semester']; ?></td>
        <td><?php echo $c['faculty_name'] ?? 'Unassigned'; ?></td>
    </tr>
    <?php endforeach; ?>
</table>

</body>
</html>

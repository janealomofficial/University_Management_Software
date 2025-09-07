<?php
session_start();
require_once __DIR__ . "/../../app/config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$student_id = $_GET['id'] ?? null;
if(!$student_id) exit("Student ID missing.");

$message = "";

// Fetch current data
$stmt = $DB_con->prepare("
    SELECT s.student_id, s.full_name, s.department, s.contact, u.username, u.email
    FROM students s
    JOIN users u ON s.user_id = u.user_id
    WHERE s.student_id = :sid
");
$stmt->execute([':sid' => $student_id]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$student) exit("Student not found.");

// Handle update
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $username = $_POST['username'];
    $email = $_POST['email'];
    $full_name = $_POST['full_name'];
    $department = $_POST['department'];
    $contact = $_POST['contact'];
    $password = $_POST['password'];

    try {
        // Update users table
        if($password) {
            $stmt1 = $DB_con->prepare("UPDATE users SET username=:username, email=:email, password=:password WHERE user_id=:uid");
            $stmt1->execute([
                ':username'=>$username,
                ':email'=>$email,
                ':password'=>password_hash($password, PASSWORD_DEFAULT),
                ':uid'=>$student['user_id']
            ]);
        } else {
            $stmt1 = $DB_con->prepare("UPDATE users SET username=:username, email=:email WHERE user_id=:uid");
            $stmt1->execute([
                ':username'=>$username,
                ':email'=>$email,
                ':uid'=>$student['user_id']
            ]);
        }

        // Update students table
        $stmt2 = $DB_con->prepare("UPDATE students SET full_name=:name, department=:dept, contact=:contact WHERE student_id=:sid");
        $stmt2->execute([
            ':name'=>$full_name,
            ':dept'=>$department,
            ':contact'=>$contact,
            ':sid'=>$student_id
        ]);

        $message = "Student updated successfully!";
    } catch(PDOException $e){
        $message = "Error: ".$e->getMessage();
    }
}
?>

<h2>Edit Student</h2>
<?php if($message) echo "<p>$message</p>"; ?>
<form method="post">
    <input type="text" name="username" value="<?php echo $student['username']; ?>" required><br><br>
    <input type="email" name="email" value="<?php echo $student['email']; ?>" required><br><br>
    <input type="password" name="password" placeholder="Leave blank to keep current"><br><br>
    <input type="text" name="full_name" value="<?php echo $student['full_name']; ?>" required><br><br>
    <input type="text" name="department" value="<?php echo $student['department']; ?>"><br><br>
    <input type="text" name="contact" value="<?php echo $student['contact']; ?>"><br><br>
    <button type="submit">Update Student</button>
</form>

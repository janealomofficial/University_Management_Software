<?php
if(isset($_SESSION['role'])){
    echo "<nav>";
    switch($_SESSION['role']){
        case 'admin':
            echo '<a href="/public/admin/dashboard.php">Dashboard</a> | ';
            echo '<a href="/public/logout.php">Logout</a>';
            break;
        case 'faculty':
            echo '<a href="/public/faculty/courses.php">Courses</a> | ';
            echo '<a href="/public/logout.php">Logout</a>';
            break;
        case 'student':
            echo '<a href="/public/student/courses.php">Courses</a> | ';
            echo '<a href="/public/logout.php">Logout</a>';
            break;
    }
    echo "</nav><hr>";
}
?>

<?php $currentPage = 'registered_courses.php'; 
include './connection/db_connection.php';

session_start();

if(isset($_SESSION['studentID'])) {
    $studentID = $_SESSION['studentID'];
} else {
    header("Location: index.php");
    exit();
}

$sql = "SELECT courses.courseID, courses.courseName, courses.instructor, courses.credit
        FROM courses
        INNER JOIN student_courses ON courses.courseID = student_courses.courseID
        WHERE student_courses.studentID = $studentID";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $courses = [];
    while($row = $result->fetch_assoc()) {
        $courses[] = $row;
    }
} else {
    echo "The student has no registered courses for this semester.";
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registered Courses</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <?php include 'CUF/student_header.php'; ?>
</head>
<body>

<div class="container-fluid" style="display:contents;">
        <div class="row" style="display: flex;">
            <?php include 'CUF/student_navbar.php'?>

            <main role="main" class="col-md-9 px-md-4" style="display:inline;">
                <h2 class="mt-4">Registered Courses</h2>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Course ID</th>
                                <th>Course Name</th>
                                <th>Instructor</th>
                                <th>Credit</th>
                                <th>Edited by</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php foreach ($courses as $course): ?>
                                <tr>
                                    <td><?php echo $course["courseID"]; ?></td>
                                    <td><?php echo $course["courseName"]; ?></td>
                                    <td><?php echo $course["instructor"]; ?></td>
                                    <td><?php echo $course["credit"]; ?></td>
                                    <td><?php echo $course["edited_by"]; ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <?php else: ?>
                            <tr>
                                <td colspan="4">No courses found.</td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
            </main>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

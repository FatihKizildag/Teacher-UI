<?php $currentPage = 'course_selection_student.php'; 
include './connection/db_connection.php';

$studentID = 1;
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
    <title>Course Selection</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <?php include 'CUF/student_header.php'; ?>
</head>
<body>

<div class="container-fluid" style="display:contents;">
        <div class="row" style="display: flex;">
            <?php include 'CUF/student_navbar.php'?>

            <main role="main" class="col-md-9 px-md-4" style="display:inline;">
                <h2 class="mt-4">Course Selection</h2>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Course ID</th>
                                <th>Course Name</th>
                                <th>Instructor</th>
                                <th>Credit</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($courses as $course): ?>
                                <tr>
                                    <td><?php echo $course["courseID"]; ?></td>
                                    <td><?php echo $course["courseName"]; ?></td>
                                    <td><?php echo $course["instructor"]; ?></td>
                                    <td><?php echo $course["credit"]; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>
    <div>
                <form>
                    <div class="form-group">
                        <label for="courseId">Course ID</label>
                        <input type="text" class="form-control" id="courseId" placeholder="Enter Course ID">
                    </div>
                    <div class="form-group">
                        <label for="courseName">Course Name</label>
                        <input type="text" class="form-control" id="courseName" placeholder="Enter Course Name">
                    </div>
                    <button type="submit" class="btn btn-primary">Enroll</button>
                </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

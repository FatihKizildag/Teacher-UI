<?php
$currentPage = 'course_list.php';
include './connection/db_connection.php';

session_start();

if(isset($_SESSION['instructorID'])) {
    $instructorID = $_SESSION['instructorID'];
    $instructorNameQuery = "SELECT instructorName FROM instructors WHERE instructorID = $instructorID";
    $result = $conn->query($instructorNameQuery);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $instructorName = $row['instructorName'];
        $_SESSION['instructorName'] = $instructorName;
    }
} else {
    header("Location: index.php");
    exit();
}

$sql = "SELECT * FROM courses"; 
$coursesResult = $conn->query($sql);

if ($coursesResult->num_rows > 0) {
    $courses = [];
    while($row = $coursesResult->fetch_assoc()) {
        $courses[] = $row;
    }
} else {
    echo "There is no courses for this semester.";
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
    <style>
        .alert {
            display: none;
        }
    </style>
</head>
<body>
    <?php include './CUF/teacher_header.php'; ?>
    <div class="container-fluid" style="display:contents;">
        <div class="row" style="display: flex;">
            <?php include './CUF/teacher_navbar.php'; ?>

            <main role="main" class="col-md-9 px-md-4" style="display:inline;">
                <h2 class="mt-4">Course List <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addCourseModal">Add Course</button></h2>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Course ID</th>
                                <th>Course Name</th>
                                <th>Instructor</th>
                                <th>Credit</th>
                                <th>Details</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (count($courses) > 0): ?>
                            <?php foreach ($courses as $course): ?>
                                <tr>
                                    <td><?php echo $course["courseID"]; ?></td>
                                    <td><?php echo $course["courseName"]; ?></td>
                                    <td><?php echo $course["instructor"]; ?></td>
                                    <td><?php echo $course["credit"]; ?></td>
                                    <td>
                                        <button type="button" class="btn btn-info btn-sm" onclick="showStudents(<?php echo $course['courseID']; ?>)">Show Students</button>
                                        <button type="button" class="btn btn-primary btn-sm" onclick="showExams(<?php echo $course['courseID']; ?>)">Show Exams</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5">No courses found.</td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>

    <div class="modal fade" id="addCourseModal" tabindex="-1" aria-labelledby="addCourseModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCourseModalLabel">Add Course</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-success" id="courseSuccessAlert"></div>
                    <div class="alert alert-danger" id="courseErrorAlert"></div>
                    <form id="addCourseForm">
                        <div class="form-group">
                            <label for="courseName">Course Name *</label>
                            <input type="text" class="form-control" id="courseName" name="courseName" placeholder="Enter Course Name">
                        </div>
                        <div class="form-group">
                            <label for="credit">Credit *</label>
                            <input type="number" class="form-control" id="credit" name="credit" placeholder="Enter Credit">
                        </div>
                        <button type="submit" class="btn btn-primary">Add Course</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="studentsModal" tabindex="-1" role="dialog" aria-labelledby="studentsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="studentsModalLabel">Students Enrolled</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="studentsList"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="examsModal" tabindex="-1" role="dialog" aria-labelledby="examsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="examsModalLabel">Exams</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="examsList"></div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function showStudents(courseID) {
            $.ajax({
                url: './connection/fetch_student.php',
                type: 'POST',
                data: {courseID: courseID},
                success: function(response) {
                    $('#studentsList').html(response);
                    $('#studentsModal').modal('show');
                }
            });
        }

        function showExams(courseID) {
            $.ajax({
                url: './connection/fetch_exams.php',
                type: 'POST',
                data: {courseID: courseID},
                success: function(response) {
                    $('#examsList').html(response);
                    $('#examsModal').modal('show');
                }
            });
        }
    </script>
    <script>
        $('#addCourseForm').submit(function(event) {
            event.preventDefault();
            $('#courseSuccessAlert').hide();
            $('#courseErrorAlert').hide();

            $.ajax({
                url: './connection/ajax_add_course.php',
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    response = JSON.parse(response);
                    if (response.status === 'success') {
                        $('#courseSuccessAlert').text(response.message).show();
                        $('#courseErrorAlert').hide();
                        setTimeout(() => { location.reload(); }, 2000);
                    } else {
                        $('#courseErrorAlert').text(response.message).show();
                        $('#courseSuccessAlert').hide();
                    }
                },
                error: function() {
                    $('#courseErrorAlert').text('An error occurred while processing your request.').show();
                    $('#courseSuccessAlert').hide();
                }
            });
        });
    </script>
</body>
</html>

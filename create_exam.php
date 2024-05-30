<?php
$currentPage = 'create_exam.php'; 
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

$sql = "SELECT * FROM courses INNER JOIN instructors ON courses.instructorID = instructors.instructorID WHERE instructors.instructorID = $instructorID"; 
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Selection</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet">
    <style>
        .alert {
            display: none;
        }
    </style>
</head>
<body>
    <?php include 'CUF/teacher_header.php'; ?>
    <div class="container-fluid" style="display:contents;">
        <div class="row">
            <?php include 'CUF/teacher_navbar.php'; ?>

            <main role="main" class="col-md-9 px-md-4">
                <h2 class="mt-4">Your Courses</h2>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tr>
                            <th>ID</th>
                            <th>Course Name</th>
                            <th>Instructor</th>
                            <th>Credit</th>
                            <th>Updated by</th>
                            <th>Update Date</th>
                        </tr>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row["courseID"]; ?></td>
                                    <td><?php echo $row["courseName"]; ?></td>
                                    <td><?php echo $row["instructorName"]; ?></td>
                                    <td><?php echo $row["credit"]; ?></td>
                                    <td><?php echo $row["updated_by"]; ?></td>
                                    <td><?php echo $row["update_date"]; ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6">No courses found.</td>
                            </tr>
                        <?php endif; ?>
                    </table>
                </div>
                <h2 class="mt-4">Create Exam</h2>
                <div class="alert alert-success" id="successAlert"></div>
                <div class="alert alert-danger" id="errorAlert"></div>
                <form id="courseForm">
                    <div class="form-group">
                        <label for="courseId">Course ID *</label>
                        <input type="text" class="form-control" id="courseId" name="courseId" placeholder="Enter Course ID">
                    </div>
                    <div class="form-group">
                        <label for="courseName">Course Name</label>
                        <input type="text" class="form-control" id="courseName" name="courseName" placeholder="Enter Course Name">
                    </div>
                    <div class="form-group">
                        <label for="examDate">Exam Date</label>
                        <input type="text" class="form-control" id="examDate" name="examDate" placeholder="Select Exam Date">
                    </div>
                    <div id="gradingFields">
                        <div class="form-group">
                            <label for="grading">Course Grading *</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <select class="form-control gradingType" name="gradingType">
                                        <option selected>Select Exam Type</option>
                                        <option value="midterm">Midterm</option>
                                        <option value="final">Final</option>
                                        <option value="quiz">Quiz</option>
                                        <option value="lab_quiz">Lab Quiz</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <input type="number" class="form-control percentage" name="percentage" placeholder="Percentage">
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Exam</button>
                </form>
            </main>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#examDate').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true
            });

            $('#addCourseForm').submit(function(event) {
                event.preventDefault();
                $('#courseSuccessAlert').hide();
                $('#courseErrorAlert').hide();

                $.ajax({
                    url: './ajax_add_course.php',
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
        });
    </script>
</body>
</html>

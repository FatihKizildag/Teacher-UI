<?php 
session_start();

$currentPage = 'browse_exams.php';
include './connection/db_connection.php';

if(isset($_SESSION['instructorID'])) {
    $instructorID = $_SESSION['instructorID'];
} else {
    header("Location: index.php");
    exit();
}

$coursesQuery = "SELECT c.courseID, c.courseName, e.examID, e.examName, e.type AS exam_type, e.percentage, e.updated_by, e.update_date
                FROM courses AS c
                INNER JOIN exam_list AS e ON c.courseID = e.courseID
                WHERE c.instructorID = '$instructorID'";

$coursesResult = $conn->query($coursesQuery);

$courses = [];
if ($coursesResult->num_rows > 0) {
    while ($row = $coursesResult->fetch_assoc()) {
        $courses[] = $row;
    }
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Exams</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'CUF/teacher_header.php'; ?>

    <div class="container-fluid" style="display:contents;">
        <div class="row" style="display: flex;">
            <?php include 'CUF/teacher_navbar.php'; ?>

            <main role="main" class="col-md-9 px-md-4" style="display:inline;">
                <h2 class="mb-4">Browse Exams</h2>
                <div id="alertMessage"></div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Course ID</th>
                                <th>Course Name</th>
                                <th>Exam Type</th>
                                <th>Percentage</th>
                                <th>Updated by</th>
                                <th>Update Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($courses as $course): ?>
                                <tr id="examRow<?php echo $course['examID']; ?>">
                                    <td><?php echo $course["courseID"]; ?></td>
                                    <td><?php echo $course["courseName"]; ?></td>
                                    <td><?php echo $course["exam_type"]; ?></td>
                                    <td><?php echo $course["percentage"]; ?></td> 
                                    <td><?php echo $course["updated_by"]?></td>
                                    <td><?php echo $course["update_date"]?></td>
                                    <td>
                                        <button type="button" class="btn btn-primary btn-sm" onclick="openEditModal(<?php echo $course['examID']; ?>, '<?php echo $course['courseName']; ?>', '<?php echo $course['exam_type']; ?>', <?php echo $course['percentage']; ?>, '<?php echo $course['update_date']; ?>')">Edit</button>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="deleteExam(<?php echo $course['examID']; ?>)">Delete</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Exam</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editForm" method="post">
                        <input type="hidden" id="editCourseID" name="editCourseID">
                        <div class="form-group">
                            <label for="editCourseName">Course Name</label>
                            <input type="text" class="form-control" id="editCourseName" name="courseName" required>
                        </div>
                        <div class="form-group">
                            <label for="editExamType">Exam Type</label>
                            <input type="text" class="form-control" id="editExamType" name="exam_type" required>
                        </div>
                        <div class="form-group">
                            <label for="editPercentage">Percentage</label>
                            <input type="number" class="form-control" id="editPercentage" name="percentage" required>
                        </div>
                        <div class="form-group">
                            <label for="examDate">Exam Date</label>
                            <input type="text" class="form-control" id="examDate" name="examDate" placeholder="Select Exam Date">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" onclick="submitEditForm()">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function openEditModal(examID, courseName, exam_type, percentage, update_date) {
            $('#editCourseID').val(examID);
            $('#editCourseName').val(courseName);
            $('#editExamType').val(exam_type);
            $('#editPercentage').val(percentage);
            $('#editUpdateDate').val(new Date(update_date).toISOString().slice(0, 16));
            $('#editModal').modal('show');
        }

        function submitEditForm() {
            $.ajax({
                url: './connection/ajax_edit_exam.php',
                type: 'POST',
                data: $('#editForm').serialize(),
                success: function(response) {
                    response = JSON.parse(response);
                    $('#editModal').modal('hide');
                    if (response.status === 'success') {
                        $('#alertMessage').html('<div class="alert alert-success" role="alert">' + response.message + '</div>');
                        setTimeout(() => { location.reload(); }, 2000);
                    } else {
                        $('#alertMessage').html('<div class="alert alert-danger" role="alert">' + response.message + '</div>');
                    }
                },
                error: function() {
                    $('#editModal').modal('hide');
                    $('#alertMessage').html('<div class="alert alert-danger" role="alert">An error occurred while processing your request.</div>');
                }
            });
        }

        function deleteExam(examID) {
            if (confirm('Are you sure you want to delete this exam?')) {
                $.ajax({
                    url: './connection/ajax_delete_exam.php',
                    type: 'POST',
                    data: { delete_id: examID },
                    success: function(response) {
                        response = JSON.parse(response);
                        if (response.status === 'success') {
                            $('#alertMessage').html('<div class="alert alert-success" role="alert">' + response.message + '</div>');
                            $('#examRow' + examID).remove();
                        } else {
                            $('#alertMessage').html('<div class="alert alert-danger" role="alert">' + response.message + '</div>');
                        }
                    },
                    error: function() {
                        $('#alertMessage').html('<div class="alert alert-danger" role="alert">An error occurred while processing your request.</div>');
                    }
                });
            }
        }
    </script>
</body>
</html>


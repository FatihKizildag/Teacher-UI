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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_id"])) {
    $deleteID = $_POST["delete_id"];
    $deleteQuery = "DELETE FROM exam_list WHERE examID = $deleteID";
    
    if ($conn->query($deleteQuery) === TRUE) {
        echo '<div class="alert alert-success" role="alert">Course successfully deleted.</div>';
    } else {
        echo '<div class="alert alert-danger" role="alert">Error: ' . $conn->error . '</div>';
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["editCourseID"])) {
    $editCourseID = $_POST["editCourseID"];
    $newCourseName = $_POST["courseName"];
    $newExamType = $_POST["exam_type"];
    $newPercentage = $_POST["percentage"];

    $updateDate = date("Y-m-d H:i:s");

    $updatedByID = $_SESSION['instructorID'];
    $instructorQuery = "SELECT instructorName FROM instructors WHERE instructorID = $updatedByID";
    $instructorResult = $conn->query($instructorQuery);
    $updatedBy = "";
    if ($instructorResult->num_rows > 0) {
        $instructorData = $instructorResult->fetch_assoc();
        $updatedBy = $instructorData['instructorName'];
    }

    $updateQuery = "UPDATE exam_list SET examName = '$newCourseName', type = '$newExamType', percentage = '$newPercentage', update_date = '$updateDate', updated_by = '$updatedBy' WHERE examID = $editCourseID";

    if ($conn->query($updateQuery) === TRUE) {
        echo '<div class="alert alert-success" role="alert">Course successfully updated.</div>';
    } else {
        echo '<div class="alert alert-danger" role="alert">Error: ' . $conn->error . '</div>';
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
                <div class="table-responsive">
                    <table class="table table-bordered">
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
                                <tr>
                                    <td><?php echo $course["courseID"]; ?></td>
                                    <td><?php echo $course["courseName"]; ?></td>
                                    <td><?php echo $course["exam_type"]; ?></td>
                                    <td><?php echo $course["percentage"]; ?></td> 
                                    <td><?php echo $course["updated_by"]?></td>
                                    <td><?php echo $course["update_date"]?></td>
                                    <td>
                                        <button type="button" class="btn btn-primary btn-sm" onclick="openEditModal(<?php echo $course["examID"]; ?>, '<?php echo $course["courseName"]; ?>', '<?php echo $course["exam_type"]; ?>', <?php echo $course["percentage"]; ?>)">Edit</button> 
                                        <form method="post" style="display:inline;">
                                            <input type="hidden" name="delete_id" value="<?php echo $course["examID"]; ?>">
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this exam?')">Delete</button>
                                        </form>
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
                <form id="editForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
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
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function openEditModal(examID, courseName, exam_type, percentage) {
            $('#editCourseID').val(examID);
            $('#editCourseName').val(courseName);
            $('#editExamType').val(exam_type);
            $('#editPercentage').val(percentage);
            $('#editModal').modal('show');
        }
    </script>
</body>
</html>

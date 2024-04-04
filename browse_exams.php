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

$coursesQuery = "SELECT * FROM exam_list WHERE courseName IN (SELECT courseName FROM courses WHERE instructorID = $instructorID)";
$coursesResult = $conn->query($coursesQuery);

$courses = [];
if ($coursesResult->num_rows > 0) {
    while ($row = $coursesResult->fetch_assoc()) {
        $courses[] = $row;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_id"])) {
    $deleteID = $_POST["delete_id"];
    $deleteQuery = "DELETE FROM exam_list WHERE courseID = $deleteID";
    
    if ($conn->query($deleteQuery) === TRUE) {
        echo '<div class="alert alert-success" role="alert">Course successfully deleted.</div>';
    } else {
        echo '<div class="alert alert-danger" role="alert">Hata: ' . $conn->error . '</div>';
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["editCourseID"])) {
    $editCourseID = $_POST["editCourseID"];
    $newCourseName = $_POST["courseName"];
    $newExamType = $_POST["examType"];
    $newPercentage = $_POST["percentage"];

    $updateQuery = "UPDATE exam_list SET courseName = '$newCourseName', examType = '$newExamType', percentage = '$newPercentage' WHERE courseID = $editCourseID";

    if ($conn->query($updateQuery) === TRUE) {
        echo '<div class="alert alert-success" role="alert">Course successfully updated.</div>';
    } else {
        echo '<div class="alert alert-danger" role="alert">Hata: ' . $conn->error . '</div>';
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
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($courses as $course): ?>
                                <tr>
                                    <td><?php echo $course["courseID"]; ?></td>
                                    <td><?php echo $course["courseName"]; ?></td>
                                    <td><?php echo $course["examType"]; ?></td>
                                    <td><?php echo $course["percentage"]; ?></td> 
                                    <td>
                                        <button type="button" class="btn btn-primary btn-sm" onclick="openEditModal(<?php echo $course["courseID"]; ?>, '<?php echo $course["courseName"]; ?>', '<?php echo $course["examType"]; ?>', <?php echo $course["percentage"]; ?>)">Edit</button> 
                                        <form method="post" style="display:inline;">
                                            <input type="hidden" name="delete_id" value="<?php echo $course["courseID"]; ?>">
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bu kursu silmek istediğinize emin misiniz?')">Delete</button>
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
                        <input type="text" class="form-control" id="editExamType" name="examType" required>
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
        function openEditModal(courseID, courseName, examType, percentage) {
            $('#editCourseID').val(courseID);
            $('#editCourseName').val(courseName);
            $('#editExamType').val(examType);
            $('#editPercentage').val(percentage);
            $('#editModal').modal('show');
        }
    </script>
</body>
</html>

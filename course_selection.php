<?php
$currentPage = 'course_selection.php';
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

$sql = "SELECT * FROM students";
$studentsResult = $conn->query($sql);

$sql = "SELECT * FROM courses"; 
$coursesResult = $conn->query($sql);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["student"]) && isset($_POST["course"])) {
        $studentName = $_POST["student"];
        $courseID = $_POST["course"];

        $studentIDQuery = "SELECT studentID FROM students WHERE studentName = '$studentName'";
        $studentIDResult = $conn->query($studentIDQuery);

        if ($studentIDResult->num_rows > 0) {
            $studentData = $studentIDResult->fetch_assoc();
            $studentID = $studentData["studentID"];

            $existingAssignmentQuery = "SELECT * FROM student_courses WHERE studentID = '$studentID' AND courseID = '$courseID'";
            $existingAssignmentResult = $conn->query($existingAssignmentQuery);

            if ($existingAssignmentResult->num_rows > 0) {
                echo '<div class="alert alert-warning" role="alert">The student is already taking this course.</div>';
            } else {
                $edited_by = $instructorName; 

                $insertQuery = "INSERT INTO student_courses (studentID, courseID, edited_by) VALUES ('$studentID', '$courseID', '$edited_by')";
                if ($conn->query($insertQuery) === TRUE) {
                    echo '<div class="alert alert-success" role="alert">Student successfully assigned to the course.</div>';
                } else {
                    echo '<div class="alert alert-danger" role="alert">Error: ' . $conn->error . '</div>';
                }
            }
        } else {
            echo '<div class="alert alert-danger" role="alert">No students found.</div>';
        }
    } else {
        echo '<div class="alert alert-danger" role="alert">Please select a student and a course.</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Selection</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        
    </style>
</head>
<body>
    <?php include './CUF/teacher_header.php'; ?>
    <div class="container-fluid" style="display:contents;">
        <div class="row" style="display: flex;">
            <?php include 'CUF/teacher_navbar.php'; ?>

            <main role="main" class="col-md-9 px-md-4" style="display:inline;">
                <h2 class="mt-4">Course Selection</h2>
                <form id="assignForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <div class="form-group">
                        <label for="student">Select Student</label>
                        <input class="form-control" list="students" id="student" name="student" placeholder="Type to search...">
                        <datalist id="students">
                            <?php while($row = $studentsResult->fetch_assoc()): ?>
                                <option value="<?php echo $row['studentName']; ?>">
                            <?php endwhile; ?>
                        </datalist>
                    </div>
                    <div class="form-group">
                        <label for="course">Select Course</label>
                        <select class="form-control" id="course" name="course">
                            <?php while($row = $coursesResult->fetch_assoc()): ?>
                                <option value="<?php echo $row['courseID']; ?>"><?php echo $row['courseName']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Assign Student to Course</button>
                </form>
                <div id="responseMessage"></div>
            </main>
        </div>
    </div>
    <script>
        document.getElementById('assignForm').addEventListener('submit', function(event) {
            event.preventDefault();

            var studentName = document.getElementById('student').value;
            var courseName = document.getElementById('course').value;

            if (studentName.trim() === '' || courseName.trim() === '') {
                alert('Please select a student and a course.');
                return;
            }
            
            this.submit();
        });
    </script>
    <script>
        $(document).ready(function(){
            $('#assignForm').on('submit', function(e){
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'POST',
                    url: './connection/selection.php',
                    data: formData,
                    success: function(response){
                        $('#responseMessage').html(response);
                    }
                });
            });
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
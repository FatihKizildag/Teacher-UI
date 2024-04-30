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


function function_alert($message) { 
    echo "<script>alert('$message');</script>"; 
} 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $courseId = $_POST["courseId"];
    $courseName = $_POST["courseName"];
    $examType = $_POST["gradingType"];
    $percentage = $_POST["percentage"];

    if (empty($courseId) || empty($examType) || empty($percentage)) {
        function_alert('Please fill out necessary fields.');
        exit();
    }
    
    $totalPercentageQuery = "SELECT SUM(percentage) AS total_percentage FROM exam_list WHERE courseID = $courseId";
    $totalPercentageResult = $conn->query($totalPercentageQuery);
    $totalPercentageRow = $totalPercentageResult->fetch_assoc();
    $totalPercentage = $totalPercentageRow["total_percentage"] + $percentage;
    
    
    if ($totalPercentage > 100) {
        function_alert('Total percentage must be 100 or less.');
        echo $totalPercentageQuery;
        exit();
    }

    if ($examType == 'final') {
        $finalCountQuery = "SELECT COUNT(*) AS final_count FROM exam_list WHERE courseID = $courseId AND type = 'final'";
        $finalCountResult = $conn->query($finalCountQuery);
        $finalCountRow = $finalCountResult->fetch_assoc();
        $finalCount = $finalCountRow["final_count"];
        if ($finalCount > 0) {
            function_alert('Only one final exam can be added.');
            exit();
        }
    }

    $updatedBy = $instructorName; 
    $updateDate = date('Y-m-d H:i:s'); 

    $sql = "INSERT INTO exam_list (courseID, examName, type, percentage, updated_by, update_date) 
            VALUES ('$courseId', '$courseName', '$examType', '$percentage', '$updatedBy', '$updateDate')";

    if ($conn->query($sql) === TRUE) {
        function_alert('Exam successfully added.');
    } else {
        $errorMessage = "Error: " . $sql . "<br>" . $conn->error;
        function_alert($errorMessage);
    }
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
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <?php include 'CUF/teacher_header.php'; ?>
    <div class="container-fluid" style="display:contents;">
        <div class="row" >
            <?php include 'CUF/teacher_navbar.php'; ?>

            <main role="main" class="col-md-9 px-md-4">
                <h2 class="mt-4">Your Courses</h2>
                <div class="table-responsive">
                    <table>
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
                                <td colspan="4">No courses found.</td>
                            </tr>
                        <?php endif; ?>
                    </table>
                </div>
                <h2 class="mt-4">Create Exam</h2>
                <form id="courseForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <div class="form-group">
                        <label for="courseId">Course ID *</label>
                        <input type="text" class="form-control" id="courseId" name="courseId" placeholder="Enter Course ID">
                    </div>
                    <div class="form-group">
                        <label for="courseName">Course Name</label>
                        <input type="text" class="form-control" id="courseName" name="courseName" placeholder="Enter Course Name">
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
    <script>
        document.getElementById('courseForm').addEventListener('submit', function(event) {
            event.preventDefault();

            var courseID = document.getElementById('courseId').value;
            var courseName = document.getElementById('courseName').value;
            

            if (courseID < 0 || isNaN(courseID) ) {
                alert('Please fill out all fields correctly.');
                return;
            }
            
            this.submit();
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

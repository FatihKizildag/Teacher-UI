<?php 
$currentPage = 'create_exam.php'; 
include './connection/db_connection.php';
$error = ""; 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $courseId = $_POST["courseId"];
    $courseName = $_POST["courseName"];
    $examType = $_POST["gradingType"];
    $percentage = $_POST["percentage"];

    $totalPercentage = (int)$percentage;

    $finalCount = $examType === "final" ? 1 : 0;
    
    function function_alert($message) { 
        echo "<script>alert('$message');</script>"; 
    } 

    $existingExamsQuery = "SELECT examType, percentage FROM exam_list WHERE courseID = $courseId";
    $existingExamsResult = $conn->query($existingExamsQuery);
    $existingPercentages = [];
    $existingFinalCount = 0;

    if ($existingExamsResult->num_rows > 0) {
        while ($row = $existingExamsResult->fetch_assoc()) {
            $existingPercentages[$row['examType']] = (int)$row['percentage'];
            if ($row['examType'] === "final") {
                $existingFinalCount++;
            }
        }
    }

    if ($existingFinalCount > 0 && $finalCount > 0) {
        function_alert('Only one final exam is allowed.');
        exit();
    }

    if (isset($existingPercentages[$examType])) {
        $existingPercentage = $existingPercentages[$examType];
        $totalPercentage += $existingPercentage;
    }

    foreach ($existingPercentages as $existingPercentage) {
        $totalPercentage += $existingPercentage;
    }

    if ($totalPercentage > 100) {
        function_alert('Total percentage must be 100 or less.');
        exit();
    }

    $sql = "INSERT INTO exam_list (courseID, courseName, examType, percentage) VALUES ";
    $sql .= "('$courseId', '$courseName', CONCAT(IFNULL(examType, ''), ',', '$examType'), '$totalPercentage') ";
    $sql .= "ON DUPLICATE KEY UPDATE examType = CONCAT(IFNULL(examType, ''), ',', '$examType'), percentage = '$totalPercentage'";

    if ($conn->query($sql) === TRUE) {
        function_alert('Course successfully added or updated');
    } else {
        $errorMessage = "Error: " . $sql . "<br>" . $conn->error;
        function_alert($errorMessage);
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
</head>
<body>
    <?php include 'CUF/teacher_header.php'; ?>
    <div class="container-fluid" style="display:contents;">
        <div class="row" >
            <?php include 'CUF/teacher_navbar.php'; ?>

            <main role="main" class="col-md-9 px-md-4">
                
                <form id="courseForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <div class="form-group">
                        <label for="courseId">Course ID</label>
                        <input type="text" class="form-control" id="courseId" name="courseId" placeholder="Enter Course ID">
                    </div>
                    <div class="form-group">
                        <label for="courseName">Course Name</label>
                        <input type="text" class="form-control" id="courseName" name="courseName" placeholder="Enter Course Name">
                    </div>
                    <div id="gradingFields">
                        <div class="form-group">
                            <label for="grading">Course Grading</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <select class="form-control gradingType" name="gradingType">
                                        <option value="">Select Exam Type</option>
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

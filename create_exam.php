<?php 
$currentPage = 'create_exam.php'; 
include './connection/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $courseId = $_POST["courseId"];
    $courseName = $_POST["courseName"];
    $examTypes = $_POST["gradingType"];
    $percentages = $_POST["percentage"];

    $courseCheckQuery = "SELECT * FROM courses WHERE courseID = $courseId";
    $courseCheckResult = $conn->query($courseCheckQuery);

    if ($courseCheckResult->num_rows > 0) {
        $sql = "INSERT INTO exam_list (courseID, courseName, examType, percentage) VALUES ";

        for ($i = 0; $i < count($examTypes); $i++) {
            $sql .= "('$courseId', '$courseName', '$examTypes[$i]', '$percentages[$i]')";

            if ($i < count($examTypes) - 1) {
                $sql .= ", ";
            }
        }

        if ($conn->query($sql) === TRUE) {
            echo "Kurs başarıyla eklendi.";
        } else {
            echo "Hata: " . $sql . "<br>" . $conn->error;
        }
    } else {
        
        echo "Hata: Belirtilen kurs bulunamadı. Lütfen geçerli bir kurs ID'si girin.";
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
                                    <select class="form-control gradingType" name="gradingType[]">
                                        <option value="">Select Exam Type</option>
                                        <option value="midterm">Midterm</option>
                                        <option value="final">Final</option>
                                        <option value="quiz">Quiz</option>
                                        <option value="lab_quiz">Lab Quiz</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <input type="number" class="form-control percentage" name="percentage[]" placeholder="Percentage">
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Exam</button>
                </form>
            </main>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php $currentPage = 'create_exam.php'; 
include './connection/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Formdan gelen verileri al
    $courseId = $_POST["courseId"];
    $courseName = $_POST["courseName"];
    $examTypes = $_POST["gradingType"];
    $percentages = $_POST["percentage"];

    // Kurs bilgilerini exam_list tablosuna eklemek için SQL sorgusu oluştur
    $sql = "INSERT INTO exam_list (courseID, courseName, examType, percentage) VALUES ";

    // Her sınav türü ve yüzdesi için SQL değerlerini ekle
    for ($i = 0; $i < count($examTypes); $i++) {
        // Değerlerin güvenliğini sağlamak için prepared statement kullanılması önerilir
        $sql .= "('$courseId', '$courseName', '$examTypes[$i]', '$percentages[$i]')";

        // Sonuncu değilse virgül ekle
        if ($i < count($examTypes) - 1) {
            $sql .= ", ";
        }
    }

    // Sorguyu çalıştır
    if ($conn->query($sql) === TRUE) {
        echo "Kurs başarıyla eklendi.";
    } else {
        echo "Hata: " . $sql . "<br>" . $conn->error;
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
                    <button type="button" class="btn btn-success" onclick="addGradingField()">Add New Exam Type</button>
                    <button type="submit" class="btn btn-primary">Add Course</button>
                </form>
            </main>
        </div>
    </div>
    <script>
        function addGradingField() {
            var gradingFields = document.getElementById('gradingFields');
    
            var newField = document.createElement('div');
            newField.classList.add('form-group');
    
            var newRow = document.createElement('div');
            newRow.classList.add('row');
    
            var col1 = document.createElement('div');
            col1.classList.add('col-md-6');
    
            var col2 = document.createElement('div');
            col2.classList.add('col-md-6');
    
            var select = document.createElement('select');
            select.classList.add('form-control', 'gradingType');
            select.setAttribute('name', 'gradingType[]');
    
            var option1 = document.createElement('option');
            option1.setAttribute('value', '');
            option1.textContent = 'Select Exam Type';
            select.appendChild(option1);
    
            var option2 = document.createElement('option');
            option2.setAttribute('value', 'midterm');
            option2.textContent = 'Midterm';
            select.appendChild(option2);
    
            var option3 = document.createElement('option');
            option3.setAttribute('value', 'final');
            option3.textContent = 'Final';
            select.appendChild(option3);
    
            var option4 = document.createElement('option');
            option4.setAttribute('value', 'quiz');
            option4.textContent = 'Quiz';
            select.appendChild(option4);
    
            var option5 = document.createElement('option');
            option5.setAttribute('value', 'lab_quiz');
            option5.textContent = 'Lab Quiz';
            select.appendChild(option5);
    
            var input = document.createElement('input');
            input.classList.add('form-control', 'percentage');
            input.setAttribute('type', 'number');
            input.setAttribute('name', 'percentage[]');
            input.setAttribute('placeholder', 'Percentage');
    
            col1.appendChild(select);
            col2.appendChild(input);
            newRow.appendChild(col1);
            newRow.appendChild(col2);
            newField.appendChild(newRow);
    
            gradingFields.appendChild(newField);
        }
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

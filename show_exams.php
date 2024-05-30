<?php 
$currentPage = 'show_exams.php';
include './connection/db_connection.php';

session_start();

if(isset($_SESSION['studentID'])) {
    $studentID = $_SESSION['studentID'];
} else {
    header("Location: index.php");
    exit();
}

$sql = "SELECT c.courseID, c.courseName, e.examID, e.examName, e.type AS exam_type, e.percentage, e.exam_date, e.updated_by, e.update_date
        FROM student_courses sc
        INNER JOIN exam_list e ON sc.courseID = e.courseID
        INNER JOIN courses c ON sc.courseID = c.courseID
        WHERE sc.studentID = '$studentID'
        ORDER BY e.exam_date";

$result = $conn->query($sql);

$exams = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $exams[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Show Exams</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        
    </style>
</head>
<body>
    <?php include 'CUF/student_header.php'; ?>

    <div class="container-fluid" style="display:contents;">
        <div class="row" style="display: flex;">
            <?php include 'CUF/student_navbar.php'?>

            <main role="main" class="col-md-9 px-md-4" style="display:inline;">
                
                    <h2 class="mt-4">Show Exams</h2>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th>Exam Name</th>
                                <th>Exam Type</th>
                                <th>Percentage</th>
                                <th>Exam Date</th>
                                <th>Updated By</th>
                                <th>Update Date</th>
                            </tr>
                            <?php foreach ($exams as $exam): ?>
                                <tr>
                                    <td><?php echo $exam['examName']; ?></td>
                                    <td><?php echo $exam['exam_type']; ?></td>
                                    <td><?php echo $exam['percentage']; ?></td>
                                    <td><?php echo $exam['exam_date']; ?></td>
                                    <td><?php echo $exam['updated_by']; ?></td>
                                    <td><?php echo $exam['update_date']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>
                
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
</body>
</html>

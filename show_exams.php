<?php $currentPage = 'show_exams.php';
include './connection/db_connection.php';
// $courses = [
//     [
//         'ID' => 1,
//         'NAME' => 'Web Prog',
//         'NumofStudents' => 12,
//         'NumofExams' => 2
//     ],
//     [
//         'ID' => 2,
//         'NAME' => 'Algorithm',
//         'NumofStudents' => 99,
//         'NumofExams' => 3
//     ],
//     [
//         'ID' =>3,
//         'NAME' => 'Introduction to Computer Science',
//         'NumofStudents' => 45,
//         'NumofExams' => 2,
//     ]
   
// ];

$sql = "SELECT courseID, courseName, examType, percentage FROM exam_list";
$result = $conn->query($sql);

// Çekilen verileri dizi olarak saklama
$courses = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $courses[] = $row;
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
    <?php include 'CUF/student_header.php'; ?>

    <div class="container-fluid" style="display:contents;">
        <div class="row" style="display: flex;">
            <?php include 'CUF/student_navbar.php'?>

            <main role="main" class="col-md-9 px-md-4" style="display:inline;">
                <h2 class="mt-4">Show Exams</h2>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th>Course ID</th>
                            <th>Course Name</th>
                            <th>Exam Type</th>
                            <th>Percentage</th>
                        </tr>
                        <?php foreach ($courses as $course): ?>
                            <tr>
                                <td><?php echo $course['courseID']; ?></td>
                                <td><?php echo $course['courseName']; ?></td>
                                <td><?php echo $course['examType']; ?></td>
                                <td><?php echo $course['percentage']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
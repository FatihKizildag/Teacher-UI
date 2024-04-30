<?php 
$currentPage = 'show_exams.php';
include './connection/db_connection.php';

$sql = "SELECT * FROM exam_list ";
$result = $conn->query($sql);

$classes = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $class = $row['class'];
        if (!isset($classes[$class])) {
            $classes[$class] = [];
        }
        $classes[$class][] = $row;
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
                <?php foreach ($classes as $class => $exams): ?>
                    <h2 class="mt-4">Class <?php echo $class; ?> Exams</h2>
                    <div class="table-responsive">
                        <table>
                            <tr>
                                <th>Course ID</th>
                                <th>Course Name</th>
                                <th>Exam Type</th>
                                <th>Percentage</th>
                            </tr>
                            <?php foreach ($exams as $exam): ?>
                                <tr>
                                    <td><?php echo $exam['courseID']; ?></td>
                                    <td><?php echo $exam['courseName']; ?></td>
                                    <td><?php echo $exam['exam_type']; ?></td>
                                    <td><?php echo $exam['percentage']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>
                <?php endforeach; ?>
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>

<?php $currentPage = 'show_exams.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Show Exams</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
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
                        <thead>
                            <tr>
                                <th>Course ID</th>
                                <th>Course Name</th>
                                <th>Exam Type</th>
                                <th>Exam Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>108</td>
                                <td>Introduction to Computer Science</td>
                                <td>Midterm</td>
                                <td>2024-03-15</td>
                            </tr>
                            <tr>
                                <td>204</td>
                                <td>Data Structures and Algorithms</td>
                                <td>Lab Quiz</td>
                                <td>2024-05-20</td>
                            </tr>
                            <tr>
                              <td>136</td>
                              <td>Web Development</td>
                              <td>Quiz</td>
                              <td>2024-03-04</td>
                            </tr>
                        </tbody>
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

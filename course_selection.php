<?php
$currentPage = 'course_selection.php';
include './connection/db_connection.php';

$sql = "SELECT *
        FROM courses"; 
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
    <?php include './CUF/teacher_header.php'; ?>
    <div class="container-fluid" style="display:contents;">
        <div class="row" style="display: flex;">
            <?php include 'CUF/teacher_navbar.php'; ?>

            <main role="main" class="col-md-9 px-md-4" style="display:inline;">
                <!-- <h2 class="mt-4">Create New Course</h2>
                    <form method="post">
                        <div class="form-group">
                            <label for="courseName">Course Name</label>
                            <input type="text" class="form-control" id="courseName" name="courseName" required>
                        </div>
                        <div class="form-group">
                            <label for="credit">Credit</label>
                            <input type="number" class="form-control" id="credit" name="credit" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Create Course</button>
                    </form> -->
                <h2 class="mt-4">Show All Courses</h2>
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
                                        <td><?php echo $row["instructor"]; ?></td>
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
            </main>
        </div>
    </div>
   
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

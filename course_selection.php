<?php
$currentPage = 'course_selection.php';
include './connection/db_connection.php';
session_start(); 

if(isset($_SESSION['instructorID'])) {
    $instructorID = $_SESSION['instructorID'];
} else {
    header("Location: index.php");
    exit();
}


$sql = "SELECT courses.courseID, courses.courseName, instructors.instructorName, courses.credit
        FROM courses
        INNER JOIN instructors ON courses.instructorID = instructors.instructorID
        WHERE instructors.instructorID = $instructorID"; 
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
                <h2 class="mt-4">Show Courses</h2>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th>ID</th>
                            <th>Course Name</th>
                            <th>Instructor</th>
                            <th>Credit</th>
                        </tr>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row["courseID"]; ?></td>
                                    <td><?php echo $row["courseName"]; ?></td>
                                    <td><?php echo $row["instructorName"]; ?></td>
                                    <td><?php echo $row["credit"]; ?></td>
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

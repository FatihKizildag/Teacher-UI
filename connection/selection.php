<?php
include './db_connection.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["student"]) && isset($_POST["course"])) {
        $studentName = $_POST["student"];
        $courseID = $_POST["course"];

        $studentIDQuery = "SELECT studentID FROM students WHERE studentName = '$studentName'";
        $studentIDResult = $conn->query($studentIDQuery);

        if ($studentIDResult->num_rows > 0) {
            $studentData = $studentIDResult->fetch_assoc();
            $studentID = $studentData["studentID"];

            $existingAssignmentQuery = "SELECT * FROM student_courses WHERE studentID = '$studentID' AND courseID = '$courseID'";
            $existingAssignmentResult = $conn->query($existingAssignmentQuery);

            if ($existingAssignmentResult->num_rows > 0) {
                echo '<div class="alert alert-warning" role="alert">The student is already taking this course.</div>';
            } else {
                $edited_by = $_SESSION['instructorName'];

                $insertQuery = "INSERT INTO student_courses (studentID, courseID, edited_by) VALUES ('$studentID', '$courseID', '$edited_by')";
                if ($conn->query($insertQuery) === TRUE) {
                    echo '<div class="alert alert-success" role="alert">Student successfully assigned to the course.</div>';
                } else {
                    echo '<div class="alert alert-danger" role="alert">Error: ' . $conn->error . '</div>';
                }
            }
        } else {
            echo '<div class="alert alert-danger" role="alert">No students found.</div>';
        }
    } else {
        echo '<div class="alert alert-danger" role="alert">Please select a student and a course.</div>';
    }
}
?>

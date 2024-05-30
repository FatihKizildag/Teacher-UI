<?php
include './db_connection.php';

session_start();

if (isset($_SESSION['instructorID'])) {
    $instructorID = $_SESSION['instructorID'];
} else {
    echo json_encode(['status' => 'error', 'message' => 'Session expired. Please login again.']);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $editCourseID = $_POST["editCourseID"];
    $newCourseName = $_POST["courseName"];
    $newExamType = $_POST["exam_type"];
    $newPercentage = $_POST["percentage"];
    $exam_date = $_POST["examDate"];

    $updatedByID = $_SESSION['instructorID'];
    $instructorQuery = "SELECT instructorName FROM instructors WHERE instructorID = $updatedByID";
    $instructorResult = $conn->query($instructorQuery);
    $updatedBy = "";
    if ($instructorResult->num_rows > 0) {
        $instructorData = $instructorResult->fetch_assoc();
        $updatedBy = $instructorData['instructorName'];
    }

    $updateQuery = "UPDATE exam_list SET examName = '$newCourseName', type = '$newExamType', percentage = '$newPercentage', exam_date = '$exam_date', updated_by = '$updatedBy' WHERE examID = $editCourseID";

    if ($conn->query($updateQuery) === TRUE) {
        echo json_encode(['status' => 'success', 'message' => 'Exam successfully updated.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error: ' . $conn->error]);
    }
}
?>

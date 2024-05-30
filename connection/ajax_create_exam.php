<?php
include './db_connection.php';

session_start();

if(isset($_SESSION['instructorID'])) {
    $instructorID = $_SESSION['instructorID'];
    $instructorNameQuery = "SELECT instructorName FROM instructors WHERE instructorID = $instructorID";
    $result = $conn->query($instructorNameQuery);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $instructorName = $row['instructorName'];
        $_SESSION['instructorName'] = $instructorName;
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Session expired. Please login again.']);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $courseId = $_POST["courseId"];
    $courseName = $_POST["courseName"];
    $examType = $_POST["gradingType"];
    $percentage = $_POST["percentage"];
    $exam_date = $_POST["examDate"];

    if (empty($courseId) || empty($examType) || empty($percentage)) {
        echo json_encode(['status' => 'error', 'message' => 'Please fill out necessary fields.']);
        exit();
    }
    
    $totalPercentageQuery = "SELECT SUM(percentage) AS total_percentage FROM exam_list WHERE courseID = $courseId";
    $totalPercentageResult = $conn->query($totalPercentageQuery);
    $totalPercentageRow = $totalPercentageResult->fetch_assoc();
    $totalPercentage = $totalPercentageRow["total_percentage"] + $percentage;
    
    if ($totalPercentage > 100) {
        echo json_encode(['status' => 'error', 'message' => 'Total percentage must be 100 or less.']);
        exit();
    }

    if ($examType == 'final') {
        $finalCountQuery = "SELECT COUNT(*) AS final_count FROM exam_list WHERE courseID = $courseId AND type = 'final'";
        $finalCountResult = $conn->query($finalCountQuery);
        $finalCountRow = $finalCountResult->fetch_assoc();
        $finalCount = $finalCountRow["final_count"];
        if ($finalCount > 0) {
            echo json_encode(['status' => 'error', 'message' => 'Only one final exam can be added.']);
            exit();
        }
    }

    $updatedBy = $instructorName; 
    $updateDate = date('Y-m-d H:i:s'); 

    $sql = "INSERT INTO exam_list (courseID, examName, type, percentage, updated_by, update_date, exam_date) 
            VALUES ('$courseId', '$courseName', '$examType', '$percentage', '$updatedBy', '$updateDate', '$exam_date')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(['status' => 'success', 'message' => 'Exam successfully added.']);
    } else {
        $errorMessage = "Error: " . $sql . "<br>" . $conn->error;
        echo json_encode(['status' => 'error', 'message' => $errorMessage]);
    }
} 
?>

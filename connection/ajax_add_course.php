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
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Instructor not found.']);
        exit();
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Session expired. Please login again.']);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $courseName = $_POST["courseName"];
    $credit = $_POST["credit"];

    if (empty($courseName) || empty($credit)) {
        echo json_encode(['status' => 'error', 'message' => 'Please fill out necessary fields.']);
        exit();
    }

    $updatedBy = $instructorName;
    $updateDate = date('Y-m-d H:i:s');

    $sql = "INSERT INTO courses (courseName, instructor, instructorID, credit, updated_by, update_date) 
            VALUES ('$courseName', '$instructorName', '$instructorID', '$credit', '$updatedBy', '$updateDate')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(['status' => 'success', 'message' => 'Course successfully added.']);
    } else {
        $errorMessage = "Error: " . $sql . "<br>" . $conn->error;
        echo json_encode(['status' => 'error', 'message' => $errorMessage]);
    }
}
?>

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
    $deleteID = $_POST["delete_id"];
    $deleteQuery = "DELETE FROM exam_list WHERE examID = $deleteID";

    if ($conn->query($deleteQuery) === TRUE) {
        echo json_encode(['status' => 'success', 'message' => 'Exam successfully deleted.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error: ' . $conn->error]);
    }
}
?>

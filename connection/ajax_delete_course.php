<?php
include './db_connection.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $courseID = $_POST["courseID"];
    $instructorID = $_SESSION['instructorID'];

    // Only delete the course if it belongs to the logged-in instructor
    $sql = "DELETE FROM courses WHERE courseID = ? AND instructorID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $courseID, $instructorID);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Course deleted successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error deleting course."]);
    }

    $stmt->close();
    $conn->close();
}
?>

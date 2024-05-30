<?php
include './db_connection.php';

if(isset($_POST['courseID'])) {
    $courseID = $_POST['courseID'];

    $sql = "SELECT s.studentID, s.studentName 
            FROM student_courses sc
            INNER JOIN students s ON sc.studentID = s.studentID
            WHERE sc.courseID = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $courseID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo '<table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Student Name</th>
                    </tr>
                </thead>
                <tbody>';
        while($row = $result->fetch_assoc()) {
            echo '<tr>
                    <td>' . $row['studentID'] . '</td>
                    <td>' . $row['studentName'] . '</td>
                </tr>';
        }
        echo '</tbody></table>';
    } else {
        echo 'No students found for this course.';
    }

    $stmt->close();
    $conn->close();
}
?>

<?php
include './db_connection.php';

if(isset($_POST['courseID'])) {
    $courseID = $_POST['courseID'];

    $sql = "SELECT examID, examName, type, percentage, updated_by, update_date, exam_date 
            FROM exam_list 
            WHERE courseID = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $courseID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo '<table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Exam ID</th>
                        <th>Exam Name</th>
                        <th>Type</th>
                        <th>Percentage</th>
                        <th>Updated By</th>
                        <th>Update Date</th>
                        <th>Exam Date</th>
                    </tr>
                </thead>
                <tbody>';
        while($row = $result->fetch_assoc()) {
            echo '<tr>
                    <td>' . $row['examID'] . '</td>
                    <td>' . $row['examName'] . '</td>
                    <td>' . $row['type'] . '</td>
                    <td>' . $row['percentage'] . '</td>
                    <td>' . $row['updated_by'] . '</td>
                    <td>' . $row['update_date'] . '</td>
                    <td>' . $row['exam_date'] . '</td>
                </tr>';
        }
        echo '</tbody></table>';
    } else {
        echo 'No exams found for this course.';
    }

    $stmt->close();
    $conn->close();
}
?>

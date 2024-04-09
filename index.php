<?php
include 'connection/db_connection.php';

$error = ""; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userID = $_POST["userID"];
    $password = $_POST["password"];

    $userID = htmlspecialchars($userID);
    $password = htmlspecialchars($password);

    if (!empty($userID) && !empty($password)) {
        if (!is_numeric($userID) || $userID <= 0) {
            $error = "User ID must be a positive number.";
        } elseif (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $password)) {
            $error = "Password cannot contain special characters.";
        } else {
            $userID = $conn->real_escape_string($userID);
            $password = $conn->real_escape_string($password);
            
            $hashedPassword = md5($password);

            $query = "SELECT * FROM user_list WHERE userID = '$userID' AND hashedPassword = '$hashedPassword'";
            $result = $conn->query($query);
            
            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                if ($user['userType'] == 'teacher') {
                    session_start(); 
                    $_SESSION['instructorID'] = $userID; 
                    header("Location: teacher.php");
                    exit();
                } else {
                    session_start();
                    $_SESSION['studentID'] = $userID; 
                    header("Location: student.php");
                    exit();
                }
            } else {
                $error = "Username or password incorrect.";
            }
        }
    } else {
        $error = "Username and password fields cannot be left blank.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">Login</h3>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($error)) : ?>
                            <div class="alert alert-danger"><?= $error ?></div>
                        <?php endif; ?>
                        <form id="loginForm" method="post">
                            <div class="form-group">
                                <label for="userID">User ID</label>
                                <input type="text" class="form-control" id="userID" name="userID" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Login</button>
                        </form>
                        <div id="errorAlert" class="alert alert-danger mt-3" style="display: none;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('loginForm').addEventListener('submit', function(event) {
            event.preventDefault();

            var userID = document.getElementById('userID').value;
            var password = document.getElementById('password').value;

            if (userID <= 0 || isNaN(userID)) {
                alert('User ID must be a positive number.');
                return;
            }
            this.submit();
        });
    </script>
</body>
</html>

<?php $currentPage = 'course_selection.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Selection</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <header class=" text-bg-dark">
        
          <div class="navbar navbar-expand-lg navbar-light bg-light " style="display: flex; justify-content: space-between;">
            <a href="index.php" class="d-flex align-items-center mb-2 mb-lg-0 navbar-brand" style="color:#222; font-weight: bold;">
            Teacher Dashboard</a>
            <div class="text-end">
              <button type="button" class="btn btn-danger">Log Out</button>
            </div>
          </div>
        
      </header>
    <div class="container-fluid" style="display:contents;">
        <div class="row" style="display: flex;">
            <?php include 'CUF/teacher_navbar.php'; ?>

            <main role="main" class="col-md-9 px-md-4">
                    
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Course ID</th>
                                <th scope="col">Course Name</th>
                                <th scope="col">Course Grading </th>
                                <th scope="col">Course Enrollment </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">108</th>
                                <td>Introduction to Computer Science</td>
                                <td>Midterm + Final</td>
                                <td>45</td>
                            </tr>
                            <tr>
                                <th scope="row">204</th>
                                <td>Data Structures and Algorithms</td>
                                <td>Midterm + Lab Quiz + Final</td>
                                <td>55</td>
                            </tr>
                            <tr>
                                <th scope="row">220</th>
                                <td>Database Management Systems</td>
                                <td>Midterm + Project + Final</td>
                                <td>55</td>
                            </tr>
                            <tr>
                                <th scope="row">236</th>
                                <td>Web Development</td>
                                <td>Midterm + Quizes + Final</td>
                                <td>35</td>
                            </tr>
                        </tbody>
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

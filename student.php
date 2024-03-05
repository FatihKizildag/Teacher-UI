<?php $currentPage = 'student.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>

    </style>
</head>
<body>
    <header class=" text-bg-dark">
        
        <div class="navbar navbar-expand-lg navbar-light bg-light " style="display: flex; justify-content: space-between;">
          <a href="student.php" class="d-flex align-items-center mb-2 mb-lg-0 navbar-brand" style="color:#222; font-weight: bold;">
          Student Dashboard</a>
          <div class="text-end">
            <button type="button" class="btn btn-danger">Log Out</button>
            <a class="btn btn-link" href="index.php">
              <i class="fas fa-exchange-alt"></i>
          </a>
          </div>
        </div>
      
    </header>

    <div class="container-fluid" style="display:contents;">
        <div class="row" style="display: flex;">
            <?php include 'CUF\student_navbar.php'; ?>
            
            <main role="main" class="col-md-9 px-md-4" style="display: inline;">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2 justify-content-center">Welcome, Student!</h1>
                </div>
                <div class="container">
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Ab, corporis voluptatum quae optio sed illum. Tenetur veniam qui distinctio, culpa cumque expedita consectetur earum, molestias natus voluptatibus ipsum reiciendis! Necessitatibus in harum dicta accusamus soluta. Ratione ipsam tenetur iusto officia saepe, nobis sint accusamus aliquid fuga obcaecati, provident suscipit in repellat asperiores, sunt at blanditiis. Perspiciatis sit et sed porro suscipit facere aut reiciendis. Sint iste inventore nemo deleniti, minima, id numquam blanditiis aut vitae sapiente provident, hic expedita nam dignissimos laboriosam. Nisi, neque quos impedit eaque dolorem fugiat voluptatum! Dolore aperiam nam unde minus. Enim veritatis voluptate consectetur aspernatur!</p>
                </div>
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


</body>
</html>

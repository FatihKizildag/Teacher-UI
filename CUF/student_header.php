<header class="text-bg-dark">
        <div class="navbar navbar-expand-lg navbar-light bg-light" style="display: flex; justify-content: space-between;">
            <a href="student.php" class="d-flex align-items-center mb-2 mb-lg-0 navbar-brand" style="color:#222; font-weight: bold;">
                Student Dashboard
            </a>
            <div class="text-end">
                <button id="logoutBtn" type="button" class="btn btn-danger">Log Out</button>
            </div>
        </div>
</header>
<script>
        document.getElementById('logoutBtn').addEventListener('click', function() {
            window.location.href = 'index.php'; 
        });
    </script>
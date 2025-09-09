<?php
session_start();
include_once "connectdb.php";

//  Check kung admin
if (!isset($_SESSION['useremail']) || $_SESSION['useremail'] !== "Admin") {
 
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>System Utilities</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container mt-5">
  <h2 class="mb-4"> System Utilities</h2>
  
  <div class="row">
    <!-- Backup and Restore -->
    <div class="col-md-4">
      <div class="card shadow-sm mb-3">
        <div class="card-body">
          <h5 class="card-title"> Backup Database</h5>
          <p class="card-text">Download a backup copy of your system database.</p>
          <a href="backup.php" class="btn btn-primary">Download Backup</a>
        </div>
      </div>
    </div>
    
    <div class="col-md-4">
      <div class="card shadow-sm mb-3">
        <div class="card-body">
          <h5 class="card-title"> Restore Database</h5>
          <p class="card-text">Upload an SQL backup file to restore data.</p>
          <form action="restore.php" method="post" enctype="multipart/form-data">
            <input type="file" name="backup_file" class="form-control mb-2" required>
            <button type="submit" class="btn btn-warning">Restore</button>
          </form>
        </div>
      </div>
    </div>
    
    <!-- Logs -->
    <div class="col-md-4">
      <div class="card shadow-sm mb-3">
        <div class="card-body">
          <h5 class="card-title"> Activity Logs</h5>
          <p class="card-text">View user activity logs for monitoring system usage.</p>
          <a href="logs.php" class="btn btn-secondary">View Logs</a>
        </div>
      </div>
    </div>
  </div>
</div>

</body>
</html>

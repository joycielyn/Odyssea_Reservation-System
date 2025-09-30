<?php
$host     = "localhost";
$username = "root";
$password = "";
$database = "odyssea_db"; // palitan kung iba db name mo

$message = "";

if (isset($_GET['action']) && $_GET['action'] === 'backup') {
    $backup_file = "backup_" . date("Y-m-d_H-i-s") . ".sql";
    $mysqldump   = "C:\\xampp\\mysql\\bin\\mysqldump.exe";

    if ($password === "") {
        $command = "\"$mysqldump\" --user=$username --host=$host $database > \"$backup_file\"";
    } else {
        $command = "\"$mysqldump\" --user=$username --password=$password --host=$host $database > \"$backup_file\"";
    }

    system($command, $output);

    if (file_exists($backup_file)) {
        header("Content-Disposition: attachment; filename=" . basename($backup_file));
        header("Content-Type: application/octet-stream");
        readfile($backup_file);
        unlink($backup_file);
        exit;
    } else {
        $message = "❌ Backup failed! Please check your database credentials or path.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Backup Database</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f4f6f9;
      font-family: Arial, sans-serif;
    }
    .container-box {
      max-width: 600px;
      margin: 100px auto;
      padding: 30px;
      background: #fff;
      border-radius: 15px;
      box-shadow: 0 6px 12px rgba(0,0,0,0.1);
      text-align: center;
    }
    h2 {
      color: #316C40;
      margin-bottom: 20px;
    }
    .btn-custom {
      background-color: #316C40;
      color: white;
      font-weight: bold;
      border-radius: 10px;
      padding: 10px 20px;
    }
    .btn-custom:hover {
      background-color: #274E2F;
    }
  </style>
</head>
<body>
  <div class="container-box">
    <h2><i class="bi bi-cloud-download"></i> Backup Database</h2>
    <p class="text-muted">Click the button below to download the latest backup of your database.</p>
    <a href="backup.php?action=backup" class="btn btn-custom w-100">Download Backup</a>
    <?php if ($message): ?>
      <div class="alert alert-danger mt-3"><?php echo $message; ?></div>
    <?php endif; ?>
    <div class="mt-3">
      <a href="dashboard.php" class="btn btn-outline-secondary btn-sm">⬅ Back to Dashboard</a>
    </div>
  </div>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</body>
</html>

<?php
// Kunin counts per status
$pendingCount   = $pdo->query("SELECT COUNT(*) FROM tbl_resroom WHERE status='Pending'")->fetchColumn();
$confirmedCount = $pdo->query("SELECT COUNT(*) FROM tbl_resroom WHERE status='Confirmed'")->fetchColumn();
$cancelledCount = $pdo->query("SELECT COUNT(*) FROM tbl_resroom WHERE status='Cancelled'")->fetchColumn();
$completedCount = $pdo->query("SELECT COUNT(*) FROM tbl_resroom WHERE status='Completed'")->fetchColumn();
$allCount       = $pdo->query("SELECT COUNT(*) FROM tbl_resroom")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>OBROR_System</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini sidebar-collapse">
<!-- Site wrapper -->
<div class="wrapper">

  <!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4" style="position:fixed; top:0; left:0; height:100vh;">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      <img src="malinawsiya.jpg" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">ODYSSEA</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="malinawsiya.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block"><?php echo $_SESSION['username']; ?></a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">

          <li class="nav-item">
            <a href="dashboard.php" class="nav-link">
              <i class="nav-icon fas fa-chart-bar"></i>
              <p>Reports & Analytics</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="rooms.php" class="nav-link">
              <i class="nav-icon fas fa-bed"></i>
              <p>Room Management</p>
            </a>
          </li>
             <li class="nav-item">
            <a href="activity.php" class="nav-link">
              <i class="nav-icon fas fa-star"></i>
              <p>Activity Management</p>
            </a>
          </li>


          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nnav-icon fas fa-clipboard-list"></i>
              <p>
                Reservation List
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="reservationlist.php?status=All" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>All <span class="badge badge-secondary right"><?php echo $allCount; ?></span></p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pending.php" class="nav-link">
                  <i class="far fa-clock nav-icon text-warning"></i>
                  <p>Pending <span class="badge badge-warning right"><?php echo $pendingCount; ?></span></p>
                </a>
              </li>
              <li class="nav-item">
                <a href="confirmed.php" class="nav-link">
                  <i class="far fa-check-circle nav-icon text-success"></i>
                  <p>Confirmed <span class="badge badge-success right"><?php echo $confirmedCount; ?></span></p>
                </a>
              </li>
              <li class="nav-item">
                <a href="cancelled.php" class="nav-link">
                  <i class="far fa-times-circle nav-icon text-danger"></i>
                  <p>Cancelled <span class="badge badge-danger right"><?php echo $cancelledCount; ?></span></p>
                </a>
              </li>
              <li class="nav-item">
                <a href="completed.php" class="nav-link">
                  <i class="far fa-flag nav-icon text-info"></i>
                  <p>Completed <span class="badge badge-info right"><?php echo $completedCount; ?></span></p>
                </a>
              </li>
            </ul>
          </li>

       

              <li class="nav-item">
            <a href="system_utilities.php" class="nav-link">
              <i class="nav-icon fas fa-cogs"></i>
              <p>System Utilities</p>
            </a>
          </li>


          <li class="nav-item">
            <a href="logout.php" class="nav-link">
              <i class="nav-icon fas fa-sign-out-alt"></i>
              <p>Logout</p>
            </a>
          </li>

        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

<?php
include_once "connectdb.php";
session_start();

if ($_SESSION['useremail'] != "Admin") {

}

include_once "header.php";

// --- Stats Queries ---
$totalReservations = $pdo->query("SELECT COUNT(*) as total FROM tbl_resroom")->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
$pending = $pdo->query("SELECT COUNT(*) as total FROM tbl_resroom WHERE status='Pending'")->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
$confirmed = $pdo->query("SELECT COUNT(*) as total FROM tbl_resroom WHERE status='Confirmed'")->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
$cancelled = $pdo->query("SELECT COUNT(*) as total FROM tbl_resroom WHERE status='Cancelled'")->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
$completed = $pdo->query("SELECT COUNT(*) as total FROM tbl_resroom WHERE status='Completed'")->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

$revenue = $pdo->query("SELECT SUM(payment) as rev FROM tbl_resroom WHERE status IN ('Confirmed','Completed')")->fetch(PDO::FETCH_ASSOC)['rev'] ?? 0;

$rooms = $pdo->query("SELECT COUNT(*) as total FROM tbl_rooms")->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
$users = $pdo->query("SELECT COUNT(*) as total FROM tbl_user WHERE role='User'")->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

// Login counts (ilang beses nag-reserve bawat user)
$loginCounts = $pdo->query("SELECT u.username, COUNT(r.pid) as reserve_count 
                            FROM tbl_user u 
                            LEFT JOIN tbl_resroom r ON u.username = r.guestname
                            WHERE u.role='User'
                            GROUP BY u.username")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-4">
    <h2 class="mb-4"> Dashboard Overview</h2>

    <div class="row">
        <!-- Total Reservations -->
        <div class="col-md-3">
            <div class="card text-white bg-primary shadow rounded-4 mb-4">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-calendar-check"></i> Total Reservations</h5>
                    <h2><?= $totalReservations ?></h2>
                </div>
            </div>
        </div>

        <!-- Pending -->
        <div class="col-md-3">
            <div class="card text-dark bg-warning shadow rounded-4 mb-4">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-hourglass-half"></i> Pending</h5>
                    <h2><?= $pending ?></h2>
                </div>
            </div>
        </div>

        <!-- Confirmed -->
        <div class="col-md-3">
            <div class="card text-white bg-success shadow rounded-4 mb-4">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-check-circle"></i> Confirmed</h5>
                    <h2><?= $confirmed ?></h2>
                </div>
            </div>
        </div>

        <!-- Cancelled -->
        <div class="col-md-3">
            <div class="card text-white bg-danger shadow rounded-4 mb-4">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-times-circle"></i> Cancelled</h5>
                    <h2><?= $cancelled ?></h2>
                </div>
            </div>
        </div>

        <!-- Completed -->
        <div class="col-md-3">
            <div class="card text-white bg-info shadow rounded-4 mb-4">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-flag-checkered"></i> Completed</h5>
                    <h2><?= $completed ?></h2>
                </div>
            </div>
        </div>

        <!-- Revenue -->
        <div class="col-md-3">
            <div class="card text-white bg-dark shadow rounded-4 mb-4">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-money-bill-wave"></i> Revenue</h5>
                    <h3>₱<?= number_format($revenue, 2) ?></h3>                </div>
            </div>
        </div>

        <!-- Rooms -->
        <div class="col-md-3">
            <div class="card text-dark bg-light shadow rounded-4 mb-4">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-bed"></i> Rooms</h5>
                    <h2><?= $rooms ?></h2>
                </div>
            </div>
        </div>

        <!-- Users -->
        <div class="col-md-3">
            <div class="card text-dark bg-secondary shadow rounded-4 mb-4">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-users"></i> Users</h5>
                    <h2><?= $users ?></h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Reservation Count per User -->
    <div class="card shadow rounded-4 mt-4">
        <div class="card-body">
            <h4><i class="fas fa-user-clock"></i> Reservation Frequency per User</h4>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Guest Name</th>
                        <th>Number of Reservations</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($loginCounts as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['username']) ?></td>
                            <td><?= $row['reserve_count'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include_once "footer.php"; ?>

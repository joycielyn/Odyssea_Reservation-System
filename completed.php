<?php
include_once "connectdb.php";
session_start();

// ✅ Admin access check
if (!isset($_SESSION['useremail']) || $_SESSION['useremail'] !== "Admin") {
    header("Location: login.php");
    exit();
}

include_once "header.php";

// Kunin lahat ng Confirmed reservations
$stmt = $pdo->query("SELECT * FROM tbl_resroom WHERE status = 'Confirmed' ORDER BY checkin ASC");
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>Confirmed Reservations</h1>
    </section>

    <section class="content">
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>PID</th>
                            <th>Guest Name</th>
                            <th>Room Type</th>
                            <th>Room Number</th>
                            <th>Check-in</th>
                            <th>Check-out</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reservations as $row): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['pid']) ?></td>
                                <td><?= htmlspecialchars($row['guestname']) ?></td>
                                <td><?= htmlspecialchars($row['roomtype']) ?></td>
                                <td><?= htmlspecialchars($row['roomnum']) ?></td>
                                <td><?= htmlspecialchars($row['checkin_date']) ?></td>
                                <td><?= htmlspecialchars($row['checkout_date']) ?></td>
                                <td><span class="badge bg-success"><?= htmlspecialchars($row['status']) ?></span></td>
                                <td>
                                    <form method="POST" action="update_status.php" style="display:inline;">
                                        <input type="hidden" name="pid" value="<?= $row['pid'] ?>">
                                        <input type="hidden" name="status" value="Completed">
                                        <button type="submit" class="btn btn-primary btn-sm">Mark as Completed</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($reservations)): ?>
                            <tr><td colspan="8" class="text-center">No confirmed reservations.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<?php include_once "footer.php"; ?>

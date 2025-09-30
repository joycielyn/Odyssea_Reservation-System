```php
<?php
include "connectdb.php";
session_start();

// --- Search Filter ---
$search = isset($_GET['search']) ? $_GET['search'] : '';
$tab = isset($_GET['tab']) ? $_GET['tab'] : 'reservations'; // default tab

// --- Restore Function ---
if (isset($_GET['restore']) && isset($_GET['table']) && isset($_GET['id'])) {
    $table = $_GET['table'];
    $id = $_GET['id'];

    if ($table == 'resroom_archive') {
        $pdo->query("INSERT INTO tbl_resroom 
            (pid, roomtype, guestname, address, contnum, roomnum, adults, children, special_request, payment, checkin_date, checkout_date, status, proof_of_payment, created_at)
            SELECT pid, roomtype, guestname, address, contnum, roomnum, adults, children, special_request, payment, checkin_date, checkout_date, status, proof_of_payment, created_at
            FROM resroom_archive WHERE pid='$id'");
        $pdo->query("DELETE FROM resroom_archive WHERE pid='$id'");
        $_SESSION['flash'] = "Reservation Restored!";

    } elseif ($table == 'rooms_archive') {
        $pdo->query("INSERT INTO tbl_rooms 
            (room_id, roomtype, roomnum, max_children, max_adults, price, status, room_image)
            SELECT room_id, roomtype, roomnum, max_children, max_adults, price, status, room_image
            FROM rooms_archive WHERE room_id='$id'");
        $pdo->query("DELETE FROM rooms_archive WHERE room_id='$id'");
        $_SESSION['flash'] = "Room Restored!";

    } elseif ($table == 'activities_archive') {
        $pdo->query("INSERT INTO tbl_activities 
            (activity_id, activity_name, description, activity_image, status, created_at)
            SELECT activity_id, activity_name, description, activity_image, status, NOW()
            FROM activities_archive WHERE activity_id='$id'");
        $pdo->query("DELETE FROM activities_archive WHERE activity_id='$id'");
        $_SESSION['flash'] = "Activity Restored!";
    }

    header("Location: archive.php?tab=$tab&search=$search");
    exit;
}

// --- Delete Permanently ---
if (isset($_GET['delete']) && isset($_GET['table']) && isset($_GET['id'])) {
    $table = $_GET['table'];
    $id = $_GET['id'];

    if ($table == 'resroom_archive') {
        $pdo->query("DELETE FROM resroom_archive WHERE pid='$id'");
        $_SESSION['flash'] = "Reservation Deleted!";
    } elseif ($table == 'rooms_archive') {
        $pdo->query("DELETE FROM rooms_archive WHERE room_id='$id'");
        $_SESSION['flash'] = "Room Deleted!";
    } elseif ($table == 'activities_archive') {
        $pdo->query("DELETE FROM activities_archive WHERE activity_id='$id'");
        $_SESSION['flash'] = "Activity Deleted!";
    }

    header("Location: archive.php?tab=$tab&search=$search");
    exit;
}

include_once "header.php";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Archive Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { background: #f8f9fa; font-family: Arial, sans-serif; }
        .container { margin-top: 30px; }
        .table th, .table td { text-align: center; vertical-align: middle; }
        .btn { padding: 4px 8px; }
        .search-box {
            max-width: 200px;
            margin-left: auto;
        }
    </style>
</head>
<body>
<div class="container">
    <h2 class="mb-4">Archive Management</h2>

    <?php if (isset($_SESSION['flash'])): ?>
        <script>
        Swal.fire({
            icon: 'success',
            title: '<?= $_SESSION['flash']; ?>',
            timer: 2000,
            showConfirmButton: false
        });
        </script>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <!-- Tabs + Search on the right -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link <?= ($tab=='reservations'?'active':'') ?>" href="archive.php?tab=reservations">Reservations</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= ($tab=='rooms'?'active':'') ?>" href="archive.php?tab=rooms">Rooms</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= ($tab=='activities'?'active':'') ?>" href="archive.php?tab=activities">Activities</a>
            </li>
        </ul>

        <form method="get" action="archive.php" class="d-flex search-box">
            <input type="hidden" name="tab" value="<?= $tab ?>">
            <input type="text" name="search" class="form-control form-control-sm me-2" placeholder="Search..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="btn btn-sm btn-primary">Go</button>
        </form>
    </div>

    <!-- Content per tab -->
    <div class="tab-content">
        <?php if ($tab == 'reservations'): ?>
            <table class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Guest</th>
                    <th>Room Type</th>
                    <th>Room No</th>
                    <th>Check-in</th>
                    <th>Check-out</th>
                    <th>Status</th>
                    <th>Deleted At</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $stmt = $pdo->prepare("SELECT * FROM resroom_archive WHERE guestname LIKE ? OR roomtype LIKE ? OR roomnum LIKE ?");
                $stmt->execute(["%$search%", "%$search%", "%$search%"]);
                while ($row = $stmt->fetch()) {
                    echo "<tr>
                        <td>{$row['pid']}</td>
                        <td>{$row['guestname']}</td>
                        <td>{$row['roomtype']}</td>
                        <td>{$row['roomnum']}</td>
                        <td>{$row['checkin_date']}</td>
                        <td>{$row['checkout_date']}</td>
                        <td>{$row['status']}</td>
                        <td>{$row['deleted_at']}</td>
                        <td>
                            <button class='btn btn-success btn-sm' onclick=\"confirmAction('archive.php?restore=1&table=resroom_archive&id={$row['pid']}&tab=reservations','restore')\">Restore</button>
                            <button class='btn btn-danger btn-sm' onclick=\"confirmAction('archive.php?delete=1&table=resroom_archive&id={$row['pid']}&tab=reservations','delete')\">Delete</button>
                        </td>
                    </tr>";
                }
                ?>
                </tbody>
            </table>
        <?php elseif ($tab == 'rooms'): ?>
            <table class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Room Type</th>
                    <th>Room No</th>
                    <th>Max Children</th>
                    <th>Max Adults</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Deleted At</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $stmt = $pdo->prepare("SELECT * FROM rooms_archive WHERE roomtype LIKE ? OR roomnum LIKE ?");
                $stmt->execute(["%$search%", "%$search%"]);
                while ($row = $stmt->fetch()) {
                    echo "<tr>
                        <td>{$row['room_id']}</td>
                        <td>{$row['roomtype']}</td>
                        <td>{$row['roomnum']}</td>
                        <td>{$row['max_children']}</td>
                        <td>{$row['max_adults']}</td>
                        <td>{$row['price']}</td>
                        <td>{$row['status']}</td>
                        <td>{$row['deleted_at']}</td>
                        <td>
                            <button class='btn btn-success btn-sm' onclick=\"confirmAction('archive.php?restore=1&table=rooms_archive&id={$row['room_id']}&tab=rooms','restore')\">Restore</button>
                            <button class='btn btn-danger btn-sm' onclick=\"confirmAction('archive.php?delete=1&table=rooms_archive&id={$row['room_id']}&tab=rooms','delete')\">Delete</button>
                        </td>
                    </tr>";
                }
                ?>
                </tbody>
            </table>
        <?php elseif ($tab == 'activities'): ?>
            <table class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Activity</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Deleted At</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $stmt = $pdo->prepare("SELECT * FROM activities_archive WHERE activity_name LIKE ? OR description LIKE ?");
                $stmt->execute(["%$search%", "%$search%"]);
                while ($row = $stmt->fetch()) {
                    echo "<tr>
                        <td>{$row['activity_id']}</td>
                        <td>{$row['activity_name']}</td>
                        <td>{$row['description']}</td>
                        <td>{$row['status']}</td>
                        <td>{$row['deleted_at']}</td>
                        <td>
                            <button class='btn btn-success btn-sm' onclick=\"confirmAction('archive.php?restore=1&table=activities_archive&id={$row['activity_id']}&tab=activities','restore')\">Restore</button>
                            <button class='btn btn-danger btn-sm' onclick=\"confirmAction('archive.php?delete=1&table=activities_archive&id={$row['activity_id']}&tab=activities','delete')\">Delete</button>
                        </td>
                    </tr>";
                }
                ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<script>
function confirmAction(url, actionType) {
    let actionText = actionType === 'restore' ? 'restore this item' : 'delete this item permanently';
    Swal.fire({
        title: 'Are you sure?',
        text: `You are about to ${actionText}.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, proceed!'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url;
        }
    });
}
</script>
</body>
</html>
<?php include_once "footer.php"; ?>

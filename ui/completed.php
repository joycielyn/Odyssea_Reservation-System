<?php
include_once 'connectdb.php';
session_start();

// Kung may authentication ka, pwede mo ilagay dito
// if ($_SESSION['useremail'] != "Admin") { header("Location: login.php"); exit; }

include_once "header.php";
?>

<div class="container mt-4">
    <h2> Completed Reservations</h2>
    <table class="table table-bordered table-striped mt-3">
        <thead>
            <tr>
                <th>PID</th>
                <th>Guest Name</th>
                <th>Room</th>
                <th>Check-in</th>
                <th>Check-out</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stmt = $pdo->query("SELECT * FROM tbl_resroom WHERE status = 'Completed'");
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>
                        <td>{$row['pid']}</td>
                        <td>{$row['guestname']}</td>
                        <td>{$row['roomtype']} - {$row['roomnum']}</td>
                        <td>{$row['checkin_date']}</td>
                        <td>{$row['checkout_date']}</td>
                        <td><span class='badge bg-success'>{$row['status']}</span></td>
                      </tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include_once "footer.php"; ?>

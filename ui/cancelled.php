<?php
include_once "connectdb.php";
session_start();

if (!isset($_SESSION['useremail']) || $_SESSION['useremail'] != "Admin") {

}

include_once "header.php";
?>

<div class="container mt-4">
    <h3>Cancelled Reservations</h3>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Guest Name</th>
                <th>Room</th>
                <th>Check-in</th>
                <th>Check-out</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stmt = $pdo->prepare("SELECT * FROM tbl_resroom WHERE status = 'Cancelled'");
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>
                    <td>{$row['guestname']}</td>
                    <td>{$row['roomnum']}</td>
                    <td>{$row['checkin_date']}</td>
                    <td>{$row['checkout_date']}</td>
                    <td><span class='badge bg-danger'>{$row['status']}</span></td>
                </tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include_once "footer.php"; ?>

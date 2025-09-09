<?php
include_once "connectdb.php";
session_start();

if (!isset($_SESSION['useremail']) || $_SESSION['useremail'] != "Admin") {
 
}

include_once "header.php";
?>

<div class="container mt-4">
    <h3>Confirmed Reservations</h3>
    <table class="table table-bordered table-striped text-center align-middle">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Guest Name</th>
                <th>Room</th>
                <th>Check-in</th>
                <th>Check-out</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stmt = $pdo->prepare("SELECT * FROM tbl_resroom WHERE status = 'Confirmed'");
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>
                    <td>{$row['pid']}</td>
                    <td>{$row['guestname']}</td>
                    <td>{$row['roomnum']}</td>
                    <td>{$row['checkin_date']}</td>
                    <td>{$row['checkout_date']}</td>
                    <td><span class='badge bg-success'>{$row['status']}</span></td>
                    <td>
                        <button class='btn btn-primary btn-sm complete-btn' data-id='{$row['pid']}'>Mark as Completed</button>
                    </td>
                </tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<!-- SweetAlert + AJAX -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.querySelectorAll(".complete-btn").forEach(button => {
    button.addEventListener("click", function() {
        let pid = this.getAttribute("data-id");

        Swal.fire({
            title: "Mark as Completed?",
            text: "Sigurado ka ba na tapos na ang reservation na ito?",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#198754",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, complete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                fetch("update_status.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: "pid=" + pid + "&status=Completed"
                })
                .then(response => response.text())
                .then(data => {
                    Swal.fire("Updated!", "Reservation marked as Completed.", "success")
                    .then(() => location.reload());
                })
                .catch(err => {
                    Swal.fire("Error!", "Something went wrong.", "error");
                });
            }
        });
    });
});
</script>

<?php include_once "footer.php"; ?>

<?php
include_once 'connectdb.php';
session_start();
include_once "header.php";

if (isset($_GET['pid'])) {
    $pid = $_GET['pid'];

    $select = $pdo->prepare("SELECT * FROM tbl_resroom WHERE pid = :pid");
    $select->bindParam(':pid', $pid);
    $select->execute();
    $row = $select->fetch(PDO::FETCH_OBJ);

    // Redirect if no guest is found
    if (!$row) {
        header("Location: reservationlist.php");
        exit();
    }
?>
        <div class="content-wrapper">
            <div class="content">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <div class="card shadow-lg">
                                <div class="card-header bg-primary text-white text-center">
                                    <h5 class="mb-0">Guest Details</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered table-sm">
                                        <tr>
                                            <th>Guest Name</th>
                                            <td><?php echo $row->guestname; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Address</th>
                                            <td><?php echo $row->address; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Contact No.</th>
                                            <td><?php echo $row->contnum; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Room Type</th>
                                            <td><?php echo $row->roomtype; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Room No.</th>
                                            <td><?php echo $row->roomnum; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Adults</th>
                                            <td><?php echo $row->adults; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Children</th>
                                            <td><?php echo $row->children; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Check-In</th>
                                            <td><?php echo $row->checkin_date; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Check-Out</th>
                                            <td><?php echo $row->checkout_date; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Special Request</th>
                                            <td><?php echo $row->special_request; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Payment</th>
                                            <td><?php echo $row->payment; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Proof of Payment</th>
                                            <td class="text-center">
                                                <img src="payments/<?php echo $row->proof_of_payment; ?>" class="img-thumbnail" width="150px">
                                            </td>
                                        </tr>
                                    </table>
                                    <?php if ($row->status != 'Cancelled' && $row->status != 'Confirmed'): ?>
    <button class="btn btn-success btnConfirm" data-id="<?php echo $row->pid; ?>">
        <i class="fas fa-check"></i> Confirm
    </button>
    <button class="btn btn-danger btnCancel" data-id="<?php echo $row->pid; ?>">
        <i class="fas fa-times"></i> Cancel
    </button>
<?php endif; ?>


    
    <a href="reservationlist.php" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back
    </a>
</div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php
}
include_once "footer.php";
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function () {
        $(".btnConfirm").click(function () {
            var pid = $(this).data("id");

            Swal.fire({
                title: "Confirm Reservation?",
                text: "Are you sure?",
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#28a745",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, Confirm"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post("update_status.php", { pid: pid, status: "Confirmed" }, function () {
                        Swal.fire("Confirmed!", "Reservation updated.", "success")
                            .then(() => window.location.reload());
                    });
                }
            });
        });

        $(".btnCancel").click(function () {
            var pid = $(this).data("id");

            Swal.fire({
                title: "Cancel Reservation?",
                text: "Are you sure?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, Cancel"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post("update_status.php", { pid: pid, status: "Cancelled" }, function () {
                        Swal.fire("Cancelled!", "Reservation updated.", "success")
                            .then(() => window.location.reload());
                    });
                }
            });
        });
    });
</script>

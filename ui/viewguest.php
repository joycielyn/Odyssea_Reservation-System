<?php
include_once 'connectdb.php';
session_start();
include_once "header.php";

if (isset($_GET['pid'])) {
    $pid = $_GET['pid'];

    $stmt = $pdo->prepare("SELECT * FROM tbl_resroom WHERE pid = :pid");
    $stmt->bindParam(':pid', $pid);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_OBJ);

    if (!$row) {
        header("Location: reservationlist.php");
        exit();
    }
}
?>

<div class="content-wrapper">
    <div class="content">
        <div class="container my-5">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-md-10">
                    <div class="card shadow-lg border-0 rounded-4">
                        <div class="card-header bg-gradient-primary text-white text-center py-4 rounded-top">
                            <h3 class="mb-0 fw-bold">Guest Reservation Details</h3>
                            <p class="mb-0 small opacity-75">Booking reference #<?php echo $row->pid; ?></p>
                        </div>
                        <div class="card-body p-4">

                            <div class="mb-4">
                                <h5 class="fw-bold text-primary">Guest Information</h5>
                                <hr>
                                <p><strong>Guest Name:</strong> <?php echo htmlspecialchars($row->guestname); ?></p>
                                <p><strong>Contact No.:</strong> <?php echo htmlspecialchars($row->contnum); ?></p>
                                <p><strong>Address:</strong> <?php echo htmlspecialchars($row->address); ?></p>
                            </div>

                            <div class="mb-4">
                                <h5 class="fw-bold text-primary">Room Information</h5>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Room Type:</strong> <?php echo htmlspecialchars($row->roomtype); ?></p>
                                        <p><strong>Room No.:</strong> <?php echo htmlspecialchars($row->roomnum); ?></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Adults:</strong> <?php echo htmlspecialchars($row->adults); ?></p>
                                        <p><strong>Children:</strong> <?php echo htmlspecialchars($row->children); ?></p>
                                    </div>
                                </div>
                                <p><strong>Status:</strong> 
                                    <span class="badge 
                                        <?php echo $row->status == 'Confirmed' ? 'bg-success' : 
                                                   ($row->status == 'Pending' ? 'bg-warning text-dark' : 
                                                   ($row->status == 'Cancelled' ? 'bg-danger' : 'bg-secondary')); ?>">
                                        <?php echo htmlspecialchars($row->status); ?>
                                    </span>
                                </p>
                            </div>

                            <div class="mb-4">
                                <h5 class="fw-bold text-primary">Stay Details</h5>
                                <hr>
                                <p><strong>Check-In:</strong> <?php echo htmlspecialchars($row->checkin_date); ?></p>
                                <p><strong>Check-Out:</strong> <?php echo htmlspecialchars($row->checkout_date); ?></p>
                                <p><strong>Special Request:</strong> 
                                    <?php echo !empty($row->special_request) ? htmlspecialchars($row->special_request) : '<span class="text-muted">None</span>'; ?>
                                </p>
                            </div>

                            <div class="mb-4">
                                <h5 class="fw-bold text-primary">Payment Information</h5>
                                <hr>
                                <p><strong>Payment Method:</strong> <?php echo htmlspecialchars($row->payment); ?></p>
                                <div class="text-center">
                                    <?php 
                                    if (!empty($row->proof_of_payment)) { 
                                        $proofPath = "payment_proofs/" . htmlspecialchars($row->proof_of_payment);
                                        if (file_exists($proofPath)) {
                                    ?>
                                        <img src="<?php echo $proofPath; ?>" 
                                             class="img-fluid rounded shadow-sm" style="max-width:200px; cursor:pointer"
                                             onclick="showProof('<?php echo htmlspecialchars($row->proof_of_payment); ?>')">
                                        <p class="mt-2 text-muted small">Click image to enlarge</p>
                                    <?php } else { ?>
                                        <span class="text-danger">File not found in folder</span>
                                    <?php } 
                                    } else { ?>
                                        <span class="text-muted">No proof of payment uploaded</span>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="text-center mt-4">
                                <a href="reservationlist.php" class="btn btn-outline-primary px-4">
                                    <i class="fas fa-arrow-left"></i> Back to List
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SweetAlert2 Script -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function showProof(filename) {
    Swal.fire({
        title: 'Proof of Payment',
        html: `<img src="payment_proofs/${filename}" style="max-width:100%; border-radius:10px;">`,
        showCloseButton: true,
        showConfirmButton: false,
        width: 600
    });
}
</script>

<?php include_once "footer.php"; ?>

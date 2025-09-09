<?php
session_start();
include_once "ui/connectdb.php";

// Autofill values from GET and SESSION
$roomtype = $_GET['roomtype'] ?? 'Standard'; // default Standard
$roomnum = $_GET['roomnum'] ?? '101';        // default 101
$guestname = $_SESSION['name'] ?? '';
$address = $_SESSION['address'] ?? '';
$contnum = $_SESSION['contnum'] ?? '';

if (isset($_POST['btnsubmit'])) {
    // Get POST data
    $guestname = $_POST['txtguestname'];
    $address = $_POST['txtaddress'];
    $contnum = $_POST['txtcontnum'];
    $roomtype = $_POST['txtroomtype'] ?: 'Standard';
    $roomnum = $_POST['txtroomnum'] ?: '101';
    $adults = $_POST['txtadults'];
    $children = $_POST['txtchildren'];
    $checkin = $_POST['txtcheckin_date'];
    $checkout = $_POST['txtcheckout_date'];
    $payment = $_POST['txtpayment'];
    $special_request = $_POST['txtspecial_request'] ?? '';

    // File upload
    $proof_file = '';
    if (isset($_FILES['proof_of_payment']) && $_FILES['proof_of_payment']['error'] == 0) {
        $target_dir = "ui/payment_proofs/";
        if(!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        $proof_file = time() . '_' . basename($_FILES['proof_of_payment']['name']);
        move_uploaded_file($_FILES['proof_of_payment']['tmp_name'], $target_dir . $proof_file);
    }

    // Insert into database
    $stmt = $pdo->prepare("INSERT INTO tbl_resroom 
        (guestname, address, contnum, roomtype, roomnum, adults, children, checkin_date, checkout_date, payment, proof_of_payment, special_request, status) 
        VALUES 
        (:guestname, :address, :contnum, :roomtype, :roomnum, :adults, :children, :checkin, :checkout, :payment, :proof, :special_request, 'Pending')");

    $result = $stmt->execute([
        ':guestname' => $guestname,
        ':address' => $address,
        ':contnum' => $contnum,
        ':roomtype' => $roomtype,
        ':roomnum' => $roomnum,
        ':adults' => $adults,
        ':children' => $children,
        ':checkin' => $checkin,
        ':checkout' => $checkout,
        ':payment' => $payment,
        ':proof' => $proof_file,
        ':special_request' => $special_request
    ]);

    if ($result) {
        $_SESSION['status'] = "Reservation Successful! Waiting for admin approval.";
        $_SESSION['status_code'] = "success";
        header("Location: resform.php?roomtype=$roomtype&roomnum=$roomnum&success=1");
        exit();
    } else {
        $_SESSION['status'] = "Reservation Failed!";
        $_SESSION['status_code'] = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Odyssea | Reservation Form</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
body { background: #f5f6fa; }
.card { border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
.card-header { background: #1abc9c; color: white; font-weight: bold; font-size: 1.3rem; border-radius: 12px 12px 0 0; }
.input-group-text { background: #1abc9c; color: white; }
.btn-submit { background: #1abc9c; color: white; font-weight: bold; }
.btn-submit:hover { background: #159c82; color: white; }
.section-title { font-weight: bold; margin-top: 20px; margin-bottom: 10px; color: #1abc9c; }
</style>
</head>
<body>

<div class="container mt-5 mb-5">
  <!-- Room Preview Card -->
  <div class="card mb-4">
    <div class="card-body d-flex align-items-center">
      <i class="fas fa-bed fa-2x me-3" style="color:#1abc9c;"></i>
      <div>
        <h5 class="mb-1">Room Type: <?= htmlspecialchars($roomtype) ?></h5>
        <p class="mb-0">Room No: <?= htmlspecialchars($roomnum) ?></p>
      </div>
    </div>
  </div>

  <!-- Reservation Form Card -->
  <div class="card">
    <div class="card-header text-center">
      Hotel Reservation Form
    </div>
    <div class="card-body">
      <form action="" method="post" enctype="multipart/form-data">

        <!-- Guest Info -->
        <h5 class="section-title">Guest Information</h5>
        <div class="form-row">
          <div class="form-group col-md-6">
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-user"></i></span>
              </div>
              <input type="text" class="form-control" name="txtguestname" value="<?= htmlspecialchars($guestname) ?>" readonly>
            </div>
          </div>
          <div class="form-group col-md-6">
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-phone"></i></span>
              </div>
              <input type="text" class="form-control" name="txtcontnum" value="<?= htmlspecialchars($contnum) ?>" readonly>
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
            </div>
            <input type="text" class="form-control" name="txtaddress" value="<?= htmlspecialchars($address) ?>" readonly>
          </div>
        </div>

        <!-- Room Info -->
        <h5 class="section-title">Room Information</h5>
        <div class="form-row">
          <div class="form-group col-md-6">
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-bed"></i></span>
              </div>
              <input type="text" class="form-control" name="txtroomtype" value="<?= htmlspecialchars($roomtype) ?>" readonly>
            </div>
          </div>
          <div class="form-group col-md-6">
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
              </div>
              <input type="text" class="form-control" name="txtroomnum" value="<?= htmlspecialchars($roomnum) ?>" readonly>
            </div>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-6">
            <label>Adults</label>
            <input type="number" class="form-control" name="txtadults" required>
          </div>
          <div class="form-group col-md-6">
            <label>Children</label>
            <input type="number" class="form-control" name="txtchildren" required>
          </div>
        </div>

        <!-- Stay Duration -->
        <h5 class="section-title">Stay Duration</h5>
        <div class="form-row">
          <div class="form-group col-md-6">
            <label>Check-in Date & Time</label>
            <input type="datetime-local" class="form-control" name="txtcheckin_date" required>
          </div>
          <div class="form-group col-md-6">
            <label>Check-out Date & Time</label>
            <input type="datetime-local" class="form-control" name="txtcheckout_date" required>
          </div>
        </div>

        <!-- Payment -->
        <h5 class="section-title">Payment</h5>
        <div class="form-row">
          <div class="form-group col-md-6">
            <select class="form-control" name="txtpayment" required>
              <option value="">-- Select Payment Method --</option>
              <option value="GCash">GCash</option>
              <option value="Cash">Cash</option>
            </select>
          </div>
          <div class="form-group col-md-6">
            <input type="file" class="form-control-file" name="proof_of_payment">
          </div>
        </div>

        <!-- Special Requests -->
        <h5 class="section-title">Special Requests</h5>
        <div class="form-group">
          <textarea class="form-control" name="txtspecial_request" rows="3" placeholder="Any requests? (Optional)"></textarea>
        </div>

        <!-- Submit Button -->
        <div class="text-center mt-4">
          <button type="submit" name="btnsubmit" class="btn btn-submit btn-lg w-50">
            <i class="fas fa-check-circle me-2"></i> Submit Reservation
          </button>
        </div>

      </form>
    </div>
  </div>
</div>

<!-- SweetAlert -->
<?php
if (isset($_SESSION['status'])) {
    $icon = $_SESSION['status_code'];
    $title = $_SESSION['status'];
    unset($_SESSION['status']); unset($_SESSION['status_code']);
    echo "
    <script>
      Swal.fire({
        icon: '$icon',
        title: '$title',
        showConfirmButton: false,
        timer: 3000
      }).then(() => {
        window.location.href = 'index.php';
      });
    </script>";
}
?>
</body>
</html>

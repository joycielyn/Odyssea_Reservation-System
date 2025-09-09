<?php
include_once 'connectdb.php';

// --- Kunin filter kung may pinili ---
$checkinFilter = isset($_GET['checkin']) ? $_GET['checkin'] : '';
$checkoutFilter = isset($_GET['checkout']) ? $_GET['checkout'] : '';

// --- Kunin lahat ng rooms ---
$rooms = $pdo->query("SELECT roomnum, roomtype, room_image FROM tbl_rooms ORDER BY roomtype, roomnum")->fetchAll(PDO::FETCH_ASSOC);

// --- Kunin lahat ng bookings ---
$query = $pdo->prepare("
    SELECT roomnum, checkin_date, checkout_date
    FROM tbl_resroom
    ORDER BY checkin_date ASC
");
$query->execute();
$bookings = $query->fetchAll(PDO::FETCH_ASSOC);

// Organize bookings per room
$roomBookings = [];
foreach ($bookings as $b) {
    $roomBookings[$b['roomnum']][] = [
        'checkin' => $b['checkin_date'],
        'checkout' => $b['checkout_date']
    ];
}

// --- Function: Check if room is available in selected range ---
function isAvailable($roomnum, $checkinFilter, $checkoutFilter, $roomBookings) {
    if (empty($checkinFilter) || empty($checkoutFilter)) {
        return true; // walang filter = ipakita lahat
    }

    $checkin = new DateTime($checkinFilter);
    $checkout = new DateTime($checkoutFilter);

    if (!empty($roomBookings[$roomnum])) {
        foreach ($roomBookings[$roomnum] as $bk) {
            $bkIn  = new DateTime($bk['checkin']);
            $bkOut = new DateTime($bk['checkout']);

            // Overlap check
            if ($checkin <= $bkOut && $checkout >= $bkIn) {
                return false; // may conflict
            }
        }
    }
    return true; // available
}

// --- Group rooms by type ---
$roomTypes = [];
foreach ($rooms as $room) {
    $roomnum = $room['roomnum'];
    $roomtype = $room['roomtype'];
    $image = $room['room_image'];

    $available = isAvailable($roomnum, $checkinFilter, $checkoutFilter, $roomBookings);

    $roomTypes[$roomtype]['rooms'][] = [
        'roomnum' => $roomnum,
        'image' => $image,
        'available' => $available
    ];
}

// Count available rooms per type
foreach ($roomTypes as $type => &$data) {
    $count = 0;
    foreach ($data['rooms'] as $r) {
        if ($r['available']) {
            $count++;
        }
    }
    $data['available_count'] = $count;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Room Availability</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #f4f6f9; }
    .filter-box { background: #fff; border-radius: 12px; padding: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
    .room-card { transition: transform .2s; border: none; border-radius: 15px; overflow: hidden; }
    .room-card:hover { transform: scale(1.02); }
    .room-card img { height: 200px; object-fit: cover; }
    .badge-available { background-color: #2ecc71; }
    .badge-unavailable { background-color: #e74c3c; }
  </style>
</head>
<body>
<div class="container py-4">
  <h2 class="mb-4 text-center fw-bold"> Room Availability</h2>

  <!-- 🔍 Filter Form -->
  <div class="filter-box mb-4">
    <form method="get" action="" class="row g-3">
      <div class="col-md-5">
        <label for="checkin" class="form-label">Check-in</label>
        <input type="date" id="checkin" name="checkin" value="<?php echo htmlspecialchars($checkinFilter); ?>" class="form-control" required>
      </div>
      <div class="col-md-5">
        <label for="checkout" class="form-label">Check-out</label>
        <input type="date" id="checkout" name="checkout" value="<?php echo htmlspecialchars($checkoutFilter); ?>" class="form-control" required>
      </div>
      <div class="col-md-2 d-flex align-items-end">
        <button type="submit" class="btn btn-primary w-100">Search</button>
      </div>
    </form>
  </div>

  <?php foreach ($roomTypes as $type => $data): ?>
    <div class="mb-5">
      <h3 class="mb-3"><?php echo htmlspecialchars($type); ?> 
        <span class="badge bg-secondary">Available: <?php echo $data['available_count']; ?></span>
      </h3>
      <div class="row g-4">
        <?php foreach ($data['rooms'] as $room): ?>
          <div class="col-md-4">
            <div class="card room-card shadow-sm">
              <img src="room_images/<?php echo htmlspecialchars($room['image']); ?>" class="card-img-top" alt="Room Image">
              <div class="card-body">
                <h5 class="card-title">Room <?php echo $room['roomnum']; ?></h5>
                <?php if ($room['available']): ?>
                  <span class="badge badge-available">Available</span>
                  <a href="resroom.php?roomnum=<?php echo $room['roomnum']; ?>" class="btn btn-success btn-sm float-end">Book Now</a>
                <?php else: ?>
                  <span class="badge badge-unavailable">Not Available</span>
                <?php endif; ?>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  <?php endforeach; ?>

</div>
</body>
</html>

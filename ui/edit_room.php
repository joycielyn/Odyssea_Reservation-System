<?php
include_once 'connectdb.php';
session_start();

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: rooms.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM tbl_rooms WHERE room_id = ?");
$stmt->execute([$id]);
$room = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$room) {
    header("Location: rooms.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $roomnum = $_POST['roomnum'];
    $roomtype = $_POST['roomtype'];
    $max_adults = $_POST['max_adults'];
    $max_children = $_POST['max_children'];
    $price = $_POST['price'];
    $status = $_POST['status'];

    $image_name = $room['room_image'];
    if (!empty($_FILES['room_image']['name'])) {
        $image_name = time() . '_' . $_FILES['room_image']['name'];
        move_uploaded_file($_FILES['room_image']['tmp_name'], 'room_images/' . $image_name);
    }

    $update = $pdo->prepare("UPDATE tbl_rooms SET roomnum=?, roomtype=?, max_adults=?, max_children=?, price=?, status=?, room_image=? WHERE room_id=?");
    $update->execute([$roomnum, $roomtype, $max_adults, $max_children, $price, $status, $image_name, $id]);

    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: 'Room updated!',
                timer: 1500,
                showConfirmButton: false
            }).then(() => {
                window.location.href = 'rooms.php';
            });
        });
    </script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Room</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<div class="container mt-5">
    <h3 class="mb-4">Edit Room</h3>
    <form method="POST" enctype="multipart/form-data">
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Room No.</label>
                <input type="text" name="roomnum" class="form-control" value="<?= $room['roomnum'] ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Room Type</label>
                <input type="text" name="roomtype" class="form-control" value="<?= $room['roomtype'] ?>" required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label class="form-label">Max Adults</label>
                <input type="number" name="max_adults" class="form-control" value="<?= $room['max_adults'] ?>" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Max Children</label>
                <input type="number" name="max_children" class="form-control" value="<?= $room['max_children'] ?>" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Price</label>
                <input type="number" name="price" class="form-control" value="<?= $room['price'] ?>" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select" required>
                <option value="Available" <?= $room['status'] == 'Available' ? 'selected' : '' ?>>Available</option>
                <option value="Unavailable" <?= $room['status'] == 'Unavailable' ? 'selected' : '' ?>>Unavailable</option>
                <option value="Under Maintenance" <?= $room['status'] == 'Under Maintenance' ? 'selected' : '' ?>>Under Maintenance</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Current Image</label><br>
            <img src="room_images/<?= $room['room_image'] ?>" width="300" class="img-thumbnail">
        </div>

        <div class="mb-3">
            <label class="form-label">Upload New Image (optional)</label>
            <input type="file" name="room_image" class="form-control">
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success">Update Room</button>
            <a href="rooms.php" class="btn btn-secondary">Back</a>
        </div>
    </form>
</div>
</body>
</html>

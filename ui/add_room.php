<?php
include_once 'connectdb.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $roomnum = $_POST['roomnum'];
    $roomtype = $_POST['roomtype'];
    $adults = $_POST['max_adults'];
    $children = $_POST['max_children'];
    $price = $_POST['price'];
    $status = $_POST['status'];

    $img_name = $_FILES['room_image']['name'];
    $img_tmp = $_FILES['room_image']['tmp_name'];
    move_uploaded_file($img_tmp, "room_images/$img_name");

    $stmt = $pdo->prepare("INSERT INTO tbl_rooms (roomnum, roomtype, max_adults, max_children, price, status, room_image) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$roomnum, $roomtype, $adults, $children, $price, $status, $img_name]);

    header("Location: rooms.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Room</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            border-radius: 10px;
        }
        .form-control, .btn {
            border-radius: 5px;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="card shadow p-4">
        <h4 class="mb-4 text-center">🛏️ Add New Room</h4>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Room No.</label>
                    <input type="text" name="roomnum" class="form-control" required>
                </div>
                <div class="form-group col-md-6">
                    <label>Room Type</label>
                    <input type="text" name="roomtype" class="form-control" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Max Adults</label>
                    <input type="number" name="max_adults" class="form-control" required>
                </div>
                <div class="form-group col-md-6">
                    <label>Max Children</label>
                    <input type="number" name="max_children" class="form-control" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Price</label>
                    <input type="number" name="price" class="form-control" required>
                </div>
                <div class="form-group col-md-6">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="Available">Available</option>
                        <option value="Unavailable">Unavailable</option>
                        <option value="UnderMaintainance">UnderMaintainance</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label>Room Image</label>
                <input type="file" name="room_image" class="form-control-file" required>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-success px-4">Add Room</button>
                <a href="rooms.php" class="btn btn-secondary px-4">Back</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>

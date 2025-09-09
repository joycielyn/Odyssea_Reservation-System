<?php
include_once 'connectdb.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM tbl_rooms WHERE room_id = ?");
    $stmt->execute([$id]);
}
header("Location: rooms.php");

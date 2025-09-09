<?php
include 'connectdb.php';
$id = $_GET['id'] ?? null;
if($id){
    $stmt = $pdo->prepare("DELETE FROM tbl_activities WHERE activity_id=?");
    $stmt->execute([$id]);
}
header("Location: activity.php");

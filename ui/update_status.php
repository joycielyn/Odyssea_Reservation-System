<?php
include_once 'connectdb.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['pid']) && isset($_POST['status'])) {
    $pid = $_POST['pid'];
    $status = $_POST['status'];

    // ✅ Dapat kasama ang Completed
    $validStatus = ['Confirmed', 'Cancelled', 'Completed'];

    if (in_array($status, $validStatus)) {
        $stmt = $pdo->prepare("UPDATE tbl_resroom SET status = :status WHERE pid = :pid");
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':pid', $pid);

        if ($stmt->execute()) {
            echo 'success';
        } else {
            echo 'error';
        }
    } else {
        echo 'invalid_status';
    }
} else {
    echo 'invalid_request';
}
?>

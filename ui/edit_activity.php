<?php
include 'connectdb.php';

$id = $_GET['id'] ?? null;
if(!$id){
    header("Location: activities.php");
    exit;
}

// Fetch existing activity
$stmt = $pdo->prepare("SELECT * FROM tbl_activities WHERE activity_id=?");
$stmt->execute([$id]);
$activity = $stmt->fetch(PDO::FETCH_ASSOC);
if(!$activity){
    header("Location: activity.php");
    exit;
}

// Update activity
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $name = $_POST['activity_name'];
    $desc = $_POST['description'];
    $status = $_POST['status'];

    $img_name = $activity['activity_image']; // keep old image if not updated
    if(!empty($_FILES['activity_image']['name'])){
        $img_name = time().'_'.$_FILES['activity_image']['name'];
        move_uploaded_file($_FILES['activity_image']['tmp_name'], 'activity_images/'.$img_name);
    }

    $stmt = $pdo->prepare("UPDATE tbl_activities SET activity_name=?, description=?, activity_image=?, status=? WHERE activity_id=?");
    $stmt->execute([$name, $desc, $img_name, $status, $id]);

    echo "<script>
        Swal.fire({icon:'success', title:'Activity Updated!', timer:1500, showConfirmButton:false})
        .then(()=>{window.location.href='activities.php';});
    </script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Activity</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<div class="container mt-5">
    <h2>Edit Activity</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Activity Name</label>
            <input type="text" name="activity_name" class="form-control" value="<?= htmlspecialchars($activity['activity_name']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control"><?= htmlspecialchars($activity['description']) ?></textarea>
        </div>
        <div class="mb-3">
            <label>Current Image</label><br>
            <img src="activity_images/<?= $activity['activity_image'] ?>" width="120" class="mb-2"><br>
            <label>Change Image</label>
            <input type="file" name="activity_image" class="form-control">
        </div>
        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-select">
                <option value="Available" <?= $activity['status']=='Available'?'selected':'' ?>>Available</option>
                <option value="Unavailable" <?= $activity['status']=='Unavailable'?'selected':'' ?>>Unavailable</option>
            </select>
        </div>
        <button class="btn btn-primary">Update Activity</button>
        <a href="activity.php" class="btn btn-secondary">Back</a>
    </form>
</div>
</body>
</html>

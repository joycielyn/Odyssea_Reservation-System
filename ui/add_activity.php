 <?php
include 'connectdb.php';



if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $name = $_POST['activity_name'];
    $desc = $_POST['description'];
    $status = $_POST['status'];

    $img_name = '';
    if(!empty($_FILES['activity_image']['name'])){
        $img_name = time().'_'.$_FILES['activity_image']['name'];
        move_uploaded_file($_FILES['activity_image']['tmp_name'], 'activity_images/'.$img_name);
    }

    $stmt = $pdo->prepare("INSERT INTO tbl_activities (activity_name, description, activity_image, status) VALUES (?,?,?,?)");
    $stmt->execute([$name, $desc, $img_name, $status]);

    echo "<script>
        Swal.fire({icon:'success', title:'Activity Added!', timer:1500, showConfirmButton:false})
        .then(()=>{window.location.href='activity.php';});
    </script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Activity</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<div class="container mt-5">
    <h2>Add Activity</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Activity Name</label>
            <input type="text" name="activity_name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control"></textarea>
        </div>
        <div class="mb-3">
            <label>Image</label>
            <input type="file" name="activity_image" class="form-control">
        </div>
        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-select">
                <option value="Available">Available</option>
                <option value="Unavailable">Unavailable</option>
            </select>
        </div>
        <button class="btn btn-success">Add Activity</button>
        <a href="activity.php" class="btn btn-secondary">Back</a>
    </form>
</div>
</body>
</html>

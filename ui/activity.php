<?php
session_start();
include 'connectdb.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Activities</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<div class="container mt-5">
    <h2>Activities</h2>
    <a href="add_activity.php" class="btn btn-success mb-3">Add Activity</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Image</th>
                <th>Activity Name</th>
                <th>Description</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $stmt = $pdo->query("SELECT * FROM tbl_activities ORDER BY activity_id DESC");
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $img = !empty($row['activity_image']) ? "activity_images/".$row['activity_image'] : "activity_images/";
            echo "<tr>
                    <td><img src='$img' width='80' height='60' style='object-fit:cover;'></td>
                    <td>{$row['activity_name']}</td>
                    <td>{$row['description']}</td>
                    <td>{$row['status']}</td>
                    <td>
                        <a href='edit_activity.php?id={$row['activity_id']}' class='btn btn-primary btn-sm'>Edit</a>
                        <a href='delete_activity.php?id={$row['activity_id']}' class='btn btn-danger btn-sm'>Delete</a>
                    </td>
                  </tr>";
        }
        ?>
        </tbody>
    </table>
</div>
</body>
</html>

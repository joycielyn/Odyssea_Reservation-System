```php
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
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="../plugins/jquery/jquery.min.js"></script>
  <style>
    .action-btn {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      padding: 6px 10px;
      border-radius: 8px;
      font-size: 0.85rem;
      font-weight: 600;
      transition: all 0.2s ease-in-out;
    }
    .action-btn i { font-size: 0.9rem; }
    .action-btn:hover {
      transform: translateY(-2px);
      opacity: 0.9;
    }
    .btn-edit { background: #0d6efd; color: #fff; }
    .btn-edit:hover { background: #0b5ed7; color: #fff; }
    .btn-delete { background: #dc3545; color: #fff; }
    .btn-delete:hover { background: #bb2d3b; color: #fff; }
  </style>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <!-- Sidebar -->
  <?php include 'header.php'; ?>

  <!-- Content Wrapper -->
  <div class="content-wrapper" style="margin-left:250px; padding:20px;">

    <section class="content">
      <div class="container-fluid">

        <h2 class="mb-4">Activities</h2>
        <a href="add_activity.php" class="btn btn-success mb-3">
          <i class="fas fa-plus"></i> Add Activity
        </a>

        <div class="card">
          <div class="card-body">
            <table class="table table-bordered table-striped align-middle">
              <thead class="table-dark text-center">
                <tr>
                  <th style="width:100px;">Image</th>
                  <th>Activity Name</th>
                  <th>Description</th>
                  <th>Status</th>
                  <th style="width:180px;">Actions</th>
                </tr>
              </thead>
              <tbody>
              <?php
              $stmt = $pdo->query("SELECT * FROM tbl_activities ORDER BY activity_id DESC");
              while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                  $img = !empty($row['activity_image']) ? "activity_images/".$row['activity_image'] : "https://via.placeholder.com/80x60?text=No+Image";
                  echo "<tr>
                          <td class='text-center'><img src='$img' width='80' height='60' style='object-fit:cover; border-radius:6px;'></td>
                          <td>".htmlspecialchars($row['activity_name'])."</td>
                          <td>".htmlspecialchars($row['description'])."</td>
                          <td class='text-center'><span class='badge ".($row['status']=='Available'?'bg-success':'bg-secondary')."'>".$row['status']."</span></td>
                          <td class='text-center'>
                            <a href='edit_activity.php?id={$row['activity_id']}' class='action-btn btn-edit'>
                              <i class='fas fa-edit'></i> Edit
                            </a>
                            <button class='action-btn btn-delete delete-activity-btn' data-id='{$row['activity_id']}'>
                              <i class='fas fa-trash'></i> Delete
                            </button>
                          </td>
                        </tr>";
              }
              ?>
              </tbody>
            </table>
          </div>
        </div>

      </div>
    </section>
  </div>

</div>

<!-- SweetAlert Delete Script -->
<script>
$(document).ready(function(){
  $('.delete-activity-btn').click(function(){
    var activityId = $(this).data('id');

    Swal.fire({
      title: 'Are you sure?',
      text: "This activity will be permanently deleted!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: 'delete_activity.php',
          type: 'GET',
          data: { id: activityId },
          success: function(){
            Swal.fire({
              icon: 'success',
              title: 'Deleted!',
              text: 'Activity deleted successfully.',
              timer: 1500,
              showConfirmButton: false
            }).then(() => {
              location.reload(); // refresh after success
            });
          },
          error: function(){
            Swal.fire('Error!', 'Something went wrong.', 'error');
          }
        });
      }
    });
  });
});
</script>

</body>
</html>
```

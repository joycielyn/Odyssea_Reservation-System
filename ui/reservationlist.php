<?php
include_once 'connectdb.php';
session_start();



if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_query = $pdo->prepare("DELETE FROM tbl_resroom WHERE pid = :pid");
    $delete_query->bindParam(':pid', $delete_id);
    
    if ($delete_query->execute()) {
        $_SESSION['status'] = "Reservation deleted successfully.";
        $_SESSION['status_code'] = "success";
    } else {
        $_SESSION['status'] = "Failed to delete reservation.";
        $_SESSION['status_code'] = "error";
    }
    header("Location: reservationlist.php");
    exit;
}


include_once "header.php";
?>

<!-- Content Wrapper -->
<div class="content-wrapper">
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="m-0">Reservations</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-hover">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Guest Name</th>
                                        <th>Contact Number</th>
                                        <th>Address</th>
                                        <th>Room No.</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $select = $pdo->prepare("SELECT pid, guestname, contnum, address, roomnum, status FROM tbl_resroom ORDER BY pid DESC");  
                                    $select->execute();

                                    while($row = $select->fetch(PDO::FETCH_OBJ)) {
                                        echo '
                                        <tr>
                                            <td>'.$row->guestname.'</td>
                                            <td>'.$row->contnum.'</td>
                                            <td>'.$row->address.'</td>
                                            <td>'.$row->roomnum.'</td>   
                                            <td>'.$row->status.'</td>

                                            <td>
                                                <a href="viewguest.php?pid='.$row->pid.'" class="btn btn-info btn-sm">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <!-- Delete Button -->
                                                <a href="javascript:void(0);" class="btn btn-danger btn-sm delete-btn" data-id="'.$row->pid.'">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once "footer.php"; ?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // SweetAlert for delete confirmation
    $('.delete-btn').click(function() {
        var reservationId = $(this).data('id');
        
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this action!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'reservationlist.php?delete_id=' + reservationId;
            }
        });
    });
</script>

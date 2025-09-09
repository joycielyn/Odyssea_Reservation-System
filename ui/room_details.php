<?php
include_once "connectdb.php";

// Check kung may room_id sa URL
if (isset($_GET['room_id'])) {
    $room_id = $_GET['room_id'];


    $query = $db->prepare("SELECT * FROM tbl_rooms WHERE room_id = :room_id");
    $query->bindParam(':room_id', $room_id);
    $query->execute();

    // Fetch the room details
    $room = $query->fetch(PDO::FETCH_ASSOC);

    if (!$room) {
        echo "Room not found or no ID provided.";
        exit;
    }
} else {
    echo "Room ID is missing!";
    exit;
}

include_once "headeruser.php";
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Room Details</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="user.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="user.php">Rooms</a></li>
                        <li class="breadcrumb-item active"><?php echo $room['room_type']; ?></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <img src="ui/room_images/<?php echo $room['image']; ?>" class="img-fluid" alt="Room Image">
                </div>
                <div class="col-md-6">
                    <h5><?php echo $room['room_type']; ?></h5>
                    <p><strong>Capacity:</strong> <?php echo $room['max_persons']; ?> persons</p>
                    <p><strong>Price:</strong> ₱<?php echo $room['price']; ?></p>
                    <p><strong>Status:</strong> <?php echo $room['status'] == 'available' ? 'Available' : 'Not Available'; ?></p>
                    <p><strong>Description:</strong> <?php echo $room['description']; ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once "footer.php"; ?>

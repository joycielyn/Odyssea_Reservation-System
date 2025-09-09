<?php
session_start();
include_once 'connectdb.php';
include_once 'header.php';
?>

<div class="content-wrapper">
    <section class="content p-4">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-bold">Room Management</h4>
                <a href="add_room.php" class="btn btn-success">
                    <i class="fas fa-plus"></i> Add Room
                </a>
            </div>
         
            <div class="card shadow-sm">
                <div class="card-body">
                    <table class="table table-bordered table-hover text-center align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Image</th>
                                <th>Room No.</th>
                                <th>Room Type</th>
                                <th>Adults <br><span class="text-muted" style="font-size:11px;">(max:5)</span></th>
                                <th>Children <br><span class="text-muted" style="font-size:11px;">(max:5, 3yrs below free)</span></th>
                                <th>Price</th>
                                <th>Status</th>
                                <th style="width: 180px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stmt = $pdo->query("SELECT * FROM tbl_rooms ORDER BY roomnum ASC");
                            if ($stmt->rowCount() > 0) {
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    $imagePath = "room_images/" . $row['room_image'];
                                    $imageTag = file_exists($imagePath) && !empty($row['room_image']) 
                                        ? "<img src='$imagePath' width='100' class='img-thumbnail'>" 
                                        : "<span class='text-muted'>No Image</span>";

                                    // Highlight/max indicator
                                    $adults = $row['max_adults'] > 5 ? "<span class='text-danger fw-bold'>{$row['max_adults']}*</span>" : $row['max_adults'];
                                    $children = $row['max_children'] > 5 ? "<span class='text-danger fw-bold'>{$row['max_children']}*</span>" : $row['max_children'];

                                    echo "<tr>
                                            <td>$imageTag</td>
                                            <td>{$row['roomnum']}</td>
                                            <td>{$row['roomtype']}</td>
                                            <td>$adults</td>
                                            <td>$children</td>
                                            <td>₱{$row['price']}</td>
                                            <td>{$row['status']}</td>
                                            <td>
                                                <a href='edit_room.php?id={$row['room_id']}' class='btn btn-sm btn-warning me-1'>
                                                    <i class='fas fa-edit'></i> Edit
                                                </a>
                                                <a href='delete_room.php?id={$row['room_id']}' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure you want to delete this room?\")'>
                                                    <i class='fas fa-trash'></i> Delete
                                                </a>
                                            </td>
                                        </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='8' class='text-center text-muted'>No rooms found.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                    <div class="mt-2" style="font-size:0.96rem;">
                        <span class="text-danger fw-bold">*</span> If the value is red, it is above the maximum allowed.
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include_once 'footer.php'; ?>
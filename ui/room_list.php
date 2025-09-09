<?php
include 'connectdb.php';
$query = "SELECT * FROM room_list";
$stmt = $pdo->prepare($query);
$stmt->execute();
$rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<table border="1">
    <tr>
        <th>ID</th>
        <th>Room Type</th>
        <th>Room Number</th>
        <th>Max Persons</th>
        <th>Price</th>
        <th>Availability</th>
        <th>Action</th>
    </tr>
    <?php foreach ($rooms as $room): ?>
    <tr>
        <td><?php echo $room['id']; ?></td>
        <td><?php echo $room['roomtype']; ?></td>
        <td><?php echo $room['roomnum']; ?></td>
        <td><?php echo $room['max_persons']; ?></td>
        <td><?php echo $room['price']; ?></td>
        <td><?php echo $room['availability']; ?></td>
        <td>
            <a href="room_edit.php?id=<?php echo $room['room_id']; ?>">Edit</a>
            <a href="room_delete.php?id=<?php echo $room['room_id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<a href="room_add.php">Add New Room</a>

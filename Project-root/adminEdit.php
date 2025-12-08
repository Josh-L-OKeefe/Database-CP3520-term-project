<?php

session_start();

$connect = new mysqli("localhost", "root", "12345", "CP3520_2");

if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
}

/*
--------------------------------
GET BOOKING ID
--------------------------------
*/
$bid = $_GET['bid'] ?? $_POST['bid'] ?? null;

if ($bid === null) {
    die("No booking selected.");
}

$bid = intval($bid);

/*
--------------------------------
FETCH BOOKING
--------------------------------
*/
$stmt = $connect->prepare(
    "SELECT bid, startTime, endTime, DOW, bookingStatus
     FROM Bookings
     WHERE bid = ?"
);
$stmt->bind_param("i", $bid);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Booking not found.");
}

$booking = $result->fetch_assoc();

/*
--------------------------------
UPDATE STATUS
--------------------------------
*/
if (isset($_POST['update'])) {

    $newStatus = $_POST['bookingStatus'];

    $update = $connect->prepare(
        "UPDATE Bookings SET bookingStatus = ? WHERE bid = ?"
    );
    $update->bind_param("si", $newStatus, $bid);

    if ($update->execute()) {
        header("Location: adminView.php");
        exit();
    } else {
        $error = "Update failed.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Booking</title>
    <style>
        body { font-family: Arial; background: #f4f6f8; }
        .box {
            width: 400px;
            margin: 100px auto;
            background: white;
            padding: 25px;
            border-radius: 6px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        label { font-weight: bold; }
        select, button {
            width: 100%;
            padding: 8px;
            margin-top: 10px;
        }
        button {
            background: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover { background: #0056b3; }
        .info { margin-bottom: 5px; }
        .error { color: red; text-align: center; }
    </style>
</head>

<body>

<div class="box">
    <h2>Edit Booking Status</h2>

    <div class="info">Booking ID: <?= $booking['bid'] ?></div>
    <div class="info">Day: <?= $booking['DOW'] ?></div>
    <div class="info">Start: <?= $booking['startTime'] ?></div>
    <div class="info">End: <?= $booking['endTime'] ?></div>

    <?php if (isset($error)): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>

    <form method="POST">
        <input type="hidden" name="bid" value="<?= $booking['bid'] ?>">

        <label>Status</label>
        <select name="bookingStatus" required>
            <option value="Pending" <?= $booking['bookingStatus'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
            <option value="Approved" <?= $booking['bookingStatus'] === 'Approved' ? 'selected' : '' ?>>Approved</option>
            <option value="Cancelled" <?= $booking['bookingStatus'] === 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
        </select>

        <button type="submit" name="update">Update Status</button>
    </form>
</div>

</body>
</html>
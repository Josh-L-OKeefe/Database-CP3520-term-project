<?php
$connect = new mysqli("localhost", "root", "12345", "cp3520_2");

if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
}

session_start();

$sql = "SELECT * FROM Rooms";
$stmt = $connect->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMIN - Manage Rooms</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .container {
            max-width: 1200px;
        }
        .filter-group {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
            align-items: center;
        }
        .filter-group input,
        .filter-group select {
            flex: 1;
        }
    </style>
</head>

<body>
<div class="container">

    <h2>Rooms Available</h2>

    <?php if ($result->num_rows == 0): ?>
        <div class="error">
            <p>No rooms found.</p>
        </div>
    <?php else: ?>
        <p><strong>Total Rooms: <?= $result->num_rows ?></strong></p>

        <!-- FILTER BAR (same as bookings page) -->
        <div class="filter-group">
            <input
                type="text"
                id="techFilter"
                placeholder="Search technology..."
                onkeyup="filterTable()"
            >

            <select id="capacityFilter" onchange="filterTable()">
                <option value="">All Capacities</option>
                <option value="20">20+</option>
                <option value="30">30+</option>
                <option value="40">40+</option>
                <option value="50">50+</option>
            </select>

            <select id="availabilityFilter" onchange="filterTable()">
                <option value="">All</option>
                <option value="1">Available</option>
                <option value="0">Unavailable</option>
            </select>
        </div>

        <!-- ROOMS TABLE -->
        <table id="roomsTable">
            <thead>
            <tr>
                <th>Room ID</th>
                <th>Capacity</th>
                <th>Location</th>
                <th>Technology</th>
                <th>Available</th>
            </tr>
            </thead>
            <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['rid'] ?></td>
                    <td><?= $row['capacity'] ?></td>
                    <td><?= $row['roomLocation'] ?></td>
                    <td><?= $row['techFeatures'] ?></td>
                    <td><?= $row['availableStatus'] ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <div class="button-group" style="margin-top: 30px;">
        <a href="index.php"><button class="btn-primary">Back</button></a>
    </div>

</div>

<script>
function filterTable() {
    const techFilter = document.getElementById("techFilter").value.toLowerCase();
    const capacityFilter = document.getElementById("capacityFilter").value;
    const availabilityFilter = document.getElementById("availabilityFilter").value;

    const table = document.getElementById("roomsTable");
    const rows = table.getElementsByTagName("tr");

    for (let i = 1; i < rows.length; i++) {
        const cells = rows[i].getElementsByTagName("td");

        const capacity = parseInt(cells[1].textContent);
        const tech = cells[3].textContent.toLowerCase();
        const availability = cells[4].textContent;

        const matchTech = tech.includes(techFilter);
        const matchCapacity = capacityFilter === "" || capacity >= capacityFilter;
        const matchAvailability = availabilityFilter === "" || availability === availabilityFilter;

        if (matchTech && matchCapacity && matchAvailability) {
            rows[i].style.display = "";
        } else {
            rows[i].style.display = "none";
        }
    }
}
</script>

</body>
</html>
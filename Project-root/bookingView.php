<?php

$connect = new mysqli("localhost", "root","12345","cp3520_2");

// Check connection
if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
}


session_start();

/*$xml_file = 'data/tickets.xml';

// Check if XML file exists
$tickets_exist = file_exists($xml_file);

if ($tickets_exist) {
    $xml = simplexml_load_file($xml_file);
    $tickets = $xml->ticket;
}*/

// MYSQL
$user_query = "SELECT * FROM BOOKINGS JOIN Instructor ON Instructor.iid = Bookings.iid WHERE Instructor.instructorInfo = ?";
$stmt = $connect->prepare($user_query);
$stmt->bind_param("s", $_SESSION['form_data']['instructorInfo']);
$stmt->execute();
$result = $stmt->get_result();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructor - View My Bookings</title>
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
        <h2>Bookings Submitted</h2>

        <?php if ($result->num_rows == 0): ?>
            <div class="error">
                <p>No Bookings registered yet.</p>
            </div>
        <?php else: ?>
            <p><strong>Total Bookings: <?php echo $result->num_rows; ?></strong></p>

            <div class="filter-group">
                <input type="text" id="searchInput" placeholder="Search..." onkeyup="filterTable()">
                <select id="roomFilter" onchange="filterTable()">
                    <option value="">All</option>
                    <option value="101">101</option>
                    <option value="102">102</option>
                    <option value="103">103</option>
                    <option value="104">104</option>
                    <option value="105">105</option>
                    <option value="201">201</option>
                    <option value="202">202</option>
                    <option value="203">203</option>
                    <option value="204">204</option>
                    <option value="205">205</option>
                </select>
                <select id="statusFilter" onchange="filterTable()">
                    <option value="">All</option>
                    <option value="1">Approved</option>
                    <option value="2">Denied</option>
                </select>
            </div>

            <table id="Bookings">
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Start time</th>
                        <th>End Time</th>
                        <th>Booking Date</th>
                        <th>Booking Status</th>
                        <th>Room ID</th>
                        <th>Instructor ID</th>
                        <th>Course ID</th>
                       
                        
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['bid']; ?></td>
                            <td><?php echo $row['startTime']; ?></td>
                            <td><?php echo $row['endTime']; ?></td>
                            <td><?php echo $row['bookingDate']; ?></td>
                            <td><?php echo $row['bookingStatus']; ?></td>
                            <td><?php echo $row['rid']; ?></td>
                            <td><?php echo $row['iid']; ?></td>
                            <td><?php echo $row['cid']; ?></td>
                            <td><a href="admin_edit.php?id=<?php echo $row['bookingStatus'] ?>"> Edit </a></td>

                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <div class="button-group" style="margin-top: 30px;">
            <a href="index.php"><button class="btn-primary">Submit New Booking</button></a>
        </div>
    </div>

    <script>
        function filterTable() {
            const searchInput = document.getElementById('searchInput').value.toLowerCase();
            const priorityFilter = document.getElementById('roomFilter').value.toLowerCase();
            const statusFilter = document.getElementById('statusFilter').value.toLowerCase();
            const table = document.getElementById('bookingsTable');
            const rows = table.getElementsByTagName('tr');

            for (let i = 1; i < rows.length; i++) {
                const row = rows[i];
                const name = row.cells[1].textContent.toLowerCase();
                const email = row.cells[2].textContent.toLowerCase();
                const title = row.cells[5].textContent.toLowerCase();
                const priority = row.cells[7].textContent.toLowerCase();
                const status = row.cells[8].textContent.toLowerCase();

                const matchesSearch = name.includes(searchInput) || email.includes(searchInput) || title.includes(searchInput);
                const matchesPriority = roomFilter === '' || room.includes(roomFilter);
                const matchesStatus = statusFilter === '' || status.includes(statusFilter);
                

                if (matchesSearch && (matchesRoom && matchesStatus)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        }
    </script>
</body>
</html>
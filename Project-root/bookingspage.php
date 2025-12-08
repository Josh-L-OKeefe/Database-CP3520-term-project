<?php
session_start();

// Initialize session variables if not set
if (!isset($_SESSION['form_data'])) {
    $_SESSION['form_data'] = array();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $errors = array();

    // Validate inputs
    if (empty($_POST['CID'])) {
        $errors[] = "Course ID is required";
    } else {
        $_SESSION['form_data']['CID'] = htmlspecialchars(trim($_POST['CID']));
    }

    if (empty($_POST['RID'])) {
        $errors[] = "Room ID is required";
    } else {
        $_SESSION['form_data']['RID'] = htmlspecialchars(trim($_POST['RID']));
    }

    if (empty($_POST['DOW'])) {
        $_SESSION['form_data']['DOW'] = "";
    } else {
        $_SESSION['form_data']['DOW'] = htmlspecialchars(trim($_POST['DOW']));
    }

    if (!preg_match('/^\d{2}:\d{2}$/', $_SESSION['form_data']['Start_Time'])) {
        $errors[] = "Invalid start time format";
    } else {
        $_SESSION['form_data']['Start_Time'] = htmlspecialchars(trim($_POST['Start_Time']));
    }

    if (!preg_match('/^\d{2}:\d{2}$/', $_SESSION['form_data']['End_Time'])) {
        $errors[] = "Invalid end time format";
    } else {
        $_SESSION['form_data']['End_Time'] = htmlspecialchars(trim($_POST['End_Time']));
    }

    // If no errors -> insert into database
    if (empty($errors)) {

        // CONNECT TO DATABASE
        $connect = new mysqli("localhost", "root", "12345", "cp3520_2");

        if ($connect->connect_error) {
            die("Database connection failed: " . $connect->connect_error);
        }

        // PREPARE SQL INSERT
        $stmt = $connect->prepare("
            INSERT INTO Bookings (cid, rid, startTime, endTime, DOW, iid, bookingStatus)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");

        $status = "Pending";

        $stmt->bind_param(
            "iisssis",
        $_SESSION['form_data']['CID'],
        $_SESSION['form_data']['RID'],
            $_SESSION['form_data']['Start_Time'],
            $_SESSION['form_data']['End_Time'],
            $_SESSION['form_data']['DOW'],
            $_SESSION['form_data']['IID'],
            $status
        );

        if ($stmt->execute()) {
            // SUCCESS: redirect to confirmation page
            header('Location: confirmation.php');
            exit;
        } else {
            echo "<p style='color:red'>Database error: " . $stmt->error . "</p>";
        }
    } else {
        $_SESSION['errors'] = $errors;
    }
}

// Retrieve saved values
$CID = $_SESSION['form_data']['CID'] ?? '';
$RID = $_SESSION['form_data']['RID'] ?? '';
$Start_Time = $_SESSION['form_data']['Start_Time'] ?? '';
$End_Time = $_SESSION['form_data']['End_Time'] ?? '';
$DOW = $_SESSION['form_data']['DOW'] ?? '';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ALL - Room Bookings Submission System Entry</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Room Bookings Submission System</h2>

        <div class="center-button">
            <a href="bookingView.php">
                <button type="button" class="btn-center">View My Current Bookings</button>
            </a>
        </div>

        <?php
        // Display validation errors
        if (isset($_SESSION['errors'])) {
            echo '<div class="error">';
            foreach ($_SESSION['errors'] as $error) {
                echo '<p>' . $error . '</p>';
            }
            echo '</div>';
            unset($_SESSION['errors']);
        }
        ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="CID">Course ID<span>*</span></label>
                <input type="text" id="CID" name="CID" value="<?php echo $CID; ?>" required>
            </div>

            <div class="form-group">
                <label for="RID">Room ID <span>*</span></label>
                <input type="text" id="RID" name="RID" value="<?php echo $RID; ?>" required>
            </div>

            <div class="form-group">
                <label for="Start_Time">Start Time <span>*</span></label>
                <input type="time" id="Start_Time" name="Start_Time" required>
            </div>

            <div class="form-group">
                <label for="End_Time">End Time <span>*</span></label>
                <input type="time" id="End_Time" name="End_Time" required>
            </div>

            <div class="form-group">
                <label for="DOW">Day Of Week <span>*</span></label>
                <select id="DOW" name="DOW" required>
                    <option value="Monday"    <?php echo $DOW == 'Monday' ? 'selected' : ''; ?>>Monday</option>
                    <option value="Tuesday"   <?php echo $DOW == 'Tuesday' ? 'selected' : ''; ?>>Tuesday</option>
                    <option value="Wednesday" <?php echo $DOW == 'Wednesday' ? 'selected' : ''; ?>>Wednesday</option>
                    <option value="Thursday"  <?php echo $DOW == 'Thursday' ? 'selected' : ''; ?>>Thursday</option>
                    <option value="Friday"    <?php echo $DOW == 'Friday' ? 'selected' : ''; ?>>Friday</option>
                    <option value="Saturday"  <?php echo $DOW == 'Saturday' ? 'selected' : ''; ?>>Saturday</option>
                    <option value="Sunday"    <?php echo $DOW == 'Sunday' ? 'selected' : ''; ?>>Sunday</option>
                </select>
            </div>

            <div class="button-group">
                <a href="page2.php"><button type="button" class="btn-secondary">← Back</button></a>
                <button type="submit" class="btn-primary">Submit Booking →</button>
            </div>
        </form>
    </div>
</body>
</html>

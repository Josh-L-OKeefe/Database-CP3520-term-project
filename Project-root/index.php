<?php

session_start();

// Initialize session variables if not set
if (!isset($_SESSION['form_data'])) {
    $_SESSION['form_data'] = array();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Save dropdown value
    if (empty($_POST['user_type'])) {
        $_SESSION['form_data']['user_type'] = "";
    } else {
        $_SESSION['form_data']['user_type'] = htmlspecialchars(trim($_POST['user_type']));
    }

    // Redirect based on user type
    if ($_SESSION['form_data']['user_type'] == 'Instructor') {
        header('Location: page2.php'); 
    } else {
        header('Location: adminView.php');
    }
    exit;
}

// Restore previous selection
$user_type = isset($_SESSION['form_data']['user_type']) ? $_SESSION['form_data']['user_type'] : "";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select User Type</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Room Bookings Submission System</h2>

        <form method="POST" action="">
            <div class="form-group">
                <label for="user_type">User Type <span>*</span></label>
                <select id="user_type" name="user_type" required>
                    <option value="Instructor" <?php echo $user_type === 'Instructor' ? 'selected' : ''; ?>>
                        Instructor (Books rooms)
                    </option>
                    <option value="admin" <?php echo $user_type === 'admin' ? 'selected' : ''; ?>>
                        Admin (Approve bookings)
                    </option>
                </select>
            </div>

            <div class="button-group" style="justify-content: flex-end;">
                <button type="submit" class="btn-primary">Next →</button>
            </div>
        </form>
    </div>
</body>
</html>
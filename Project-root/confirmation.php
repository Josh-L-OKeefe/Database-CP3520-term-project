<?php
// Start the session if needed, although not strictly necessary for a landing page
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to User Registration</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Simple styling for the main page */
        .main-content {
            text-align: center;
            padding: 50px 20px;
        }

        .main-content h1 {
            color: #333;
            margin-bottom: 20px;
        }

        .main-content p {
            color: #555;
            margin-bottom: 30px;
            font-size: 1.1em;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="main-content">
            <h1>Booking Submission Complete!</h1>
            <p>
                Click button to return home.
            </p>
            <a href="bookingspage.php">
                <button type="button" class="btn-primary">Return</button>
            </a>
        </div>
    </div>
</body>
</html>
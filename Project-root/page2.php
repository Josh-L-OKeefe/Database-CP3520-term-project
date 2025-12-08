<?php
session_start();

if (!isset($_SESSION['form_data']['first_name'])) {
    header('Location: index.php');
    exit;
}

$connect = new mysqli("localhost", "root", "12345", "CP3520_2");
if ($connect->connect_error) {
    die("Database connection failed");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];

    if (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email is required";
    }

    if (empty($_POST['IID']) || !ctype_digit($_POST['IID'])) {
        $errors[] = "Instructor ID must be numeric";
    }

    if (empty($_POST['Department'])) {
        $errors[] = "Department is required";
    }

    if (empty($errors)) {
        $iid = (int)$_POST['IID'];
        $_SESSION['form_data']['IID'] = $iid;
        $name = $_SESSION['form_data']['first_name'] . " " . $_SESSION['form_data']['last_name'];
        $email = trim($_POST['email']);
        $department = trim($_POST['Department']);

        $sql = "INSERT INTO Instructor (iid, instructorName, instructorInfo, department)
                VALUES (?, ?, ?, ?)";

        $stmt = $connect->prepare($sql);
        $stmt->bind_param("isss", $iid, $name, $email, $department);

        if ($stmt->execute()) {
            header("Location: bookingspage.php");
            exit;
        } else {
            $errors[] = "Instructor ID already exists";
        }
    }

    $_SESSION['errors'] = $errors;
}

$email = $_POST['email'] ?? '';
$IID = $_POST['IID'] ?? '';
$Department = $_POST['Department'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registration - Step 2</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h2>User Registration - Step 2 of 2</h2>

    <?php
    if (isset($_SESSION['errors'])) {
        echo '<div class="error">';
        foreach ($_SESSION['errors'] as $error) {
            echo "<p>$error</p>";
        }
        echo '</div>';
        unset($_SESSION['errors']);
    }
    ?>

    <form method="POST">
        <div class="form-group">
            <label>Email <span>*</span></label>
            <input type="email" name="email" value="<?= $email ?>" required>
        </div>

        <div class="form-group">
            <label>Instructor ID <span>*</span></label>
            <input type="text" name="IID" value="<?= $IID ?>" required>
        </div>

        <div class="form-group">
            <label>Department <span>*</span></label>
            <input type="text" name="Department" value="<?= $Department ?>" required>
        </div>

        <div class="button-group">
            <a href="index.php">
                <button type="button" class="btn-secondary">← Back</button>
            </a>
            <button type="submit" class="btn-primary">Next →</button>
        </div>
    </form>
</div>

</body>
</html>
<?php
session_start();
require 'phpMailer.php'; // Include the phpMailer file

// Include database connection
$servername = "localhost";
$username = "root";
$password = "root";
$database = "TreatWell";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_SESSION['step'])) {
        $_SESSION['step'] = 1;
    }

    switch ($_SESSION['step']) {
        case 1: // Step 1: Enter Email
            $email = $_POST['email'];

            // Check if the email exists in the database
            $sql = "SELECT * FROM patient_login WHERE patient_email = '$email'";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);

            if ($row) {
                // Generate a random verification code
                $verification_code = mt_rand(100000, 999999);

                // Store the verification code in a session variable
                $_SESSION['verification_code'] = $verification_code;

                // Send email with verification code using PHPMailer
                sendVerificationCode($email, $verification_code); // Call the function from phpMailer.php

                // Store email in session
                $_SESSION['email'] = $email;
                $_SESSION['step'] = 2;

                header("Location: ".$_SERVER['PHP_SELF']);
                exit();
            } else {
                echo "Email not found!";
            }
            break;

        case 2: // Step 2: Verify Code
            $verification_code = $_POST['verification_code'];

            if ($verification_code == $_SESSION['verification_code']) {
                $_SESSION['step'] = 3;
                header("Location: ".$_SERVER['PHP_SELF']);
                exit();
            } else {
                echo "Invalid verification code!";
            }
            break;

        case 3: // Step 3: Reset Password
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];

            if ($password == $confirm_password) {
                $email = $_SESSION['email'];
                // Hash the password
                //$hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Update password in the database
                $sql = "UPDATE patient_login SET patient_pass = '$password' WHERE patient_email = '$email'";
                mysqli_query($conn, $sql);

                // Destroy session
                session_destroy();

                echo "Password updated successfully!";
            } else {
                echo "Passwords do not match!";
            }
            break;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forget Password</title>
    <link rel="stylesheet" href="css/patientLogin.css">
</head>
<body style="background-size: cover; background: url(image/patient.jpg) center;">
<div class="wrapper">
    <?php
    if (!isset($_SESSION['step']) || $_SESSION['step'] == 1) {
        echo '<h1>Forget Password - Step 1: Enter Email</h1>';
        echo '<form method="post" class="input-box">';
        echo '<label for="email">Enter your email address:</label><br>';
        echo '<input type="email" id="email" name="email" required><br><br>';
        echo '<button type="submit" class="submit">Submit</button>';
        echo '</form>';
    } elseif ($_SESSION['step'] == 2) {
        echo '<h1>Forget Password - Step 2: Verify Code</h1>';
        echo '<form method="post" class="input-box">';
        echo '<label for="verification_code">Enter the verification code sent to your email:</label><br>';
        echo '<input type="text" id="verification_code" name="verification_code" required><br><br>';
        echo '<button type="submit" class="submit">Verify</button>';
        echo '</form>';
    } elseif ($_SESSION['step'] == 3) {
        echo '<h1>Forget Password - Step 3: Reset Password</h1>';
        echo '<form method="post" class="input-box">';
        echo '<label for="password">New Password:</label><br>';
        echo '<input type="password" id="password" name="password" required><br><br>';
        echo '<label for="confirm_password">Confirm Password:</label><br>';
        echo '<input type="password" id="confirm_password" name="confirm_password" required><br><br>';
        echo '<button type="submit" class="submit">Reset Password</button>';
        echo '</form>';
    }
    ?>
</div>
</body>
</html>
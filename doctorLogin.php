<?php
// Database credentials
$servername = "localhost";
$username = "root";
$password = "";
$database = "TreatWell";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate username
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter a username.";
    } else {
        $username = trim($_POST["username"]);
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Check input errors before querying the database
    if (empty($username_err) && empty($password_err)) {

        // Prepare a SELECT statement
        $sql = "SELECT Patient_ID, Patient_Username, Patient_Pass FROM Patient_login WHERE Patient_Username = ?";

        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_username);

            // Set parameters
            $param_username = $username;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Store result
                $stmt->store_result();

                // Check if username exists, if yes then verify password
                if ($stmt->num_rows == 1) {
                    // Bind result variables
                    $stmt->bind_result($user_id, $username, $stored_password);
                    if ($stmt->fetch()) {
                        // Compare plaintext password
                        if ($password === $stored_password) {
                            // Password is correct, so start a new session
                            session_start();

                            // Store data in session variables
                            $_SESSION["user_id"] = $user_id;
                            $_SESSION["username"] = $username;

                            // Redirect user to welcome page
                            header("location: welcome.php");
                            exit();
                        } else {
                            // Display an error message if password is not valid
                            $password_err = "The password you entered was not valid.";
                        }
                    }
                } else {
                    // Display an error message if username doesn't exist
                    $username_err = "No account found with that username.";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }

    // Close connection
    $conn->close();
}
?>

<!DOCTYPE html>




<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form | Dan Aleko</title>
    <link rel="stylesheet" href="css/patientLogin.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body style="background: url(image/doctor.jpg);background-size: cover;background-position: center;">
<div class="wrapper">
    <form class="mt-6" action="doctorLogin.php" method="POST">
        <h1>Doctor Login</h1>
        <div class="input-box">
            <input type="text" name ="username" placeholder="Username" required>
            <i class='bx bxs-user'></i>
            <?php if (!empty($username_err)) : ?>
                <p style="color: black;background-color: red;text-align: center;padding: 5px;margin: 5px 0;border-radius: 40px;"><?php echo $username_err; ?></p>
            <?php endif; ?>
        </div>
        <div class="input-box">
            <input type="password" name="password" placeholder="Password" required>
            <i class='bx bxs-lock-alt' ></i>
            <?php if (!empty($password_err)) : ?>
                <p style="color: black;background-color: red;text-align: center;padding: 5px;margin: 5px 0;border-radius: 40px;"><?php echo $password_err; ?></p>
            <?php endif; ?>
        </div>
        <div class="remember-forgot">
            <a href="#">Forgot Password</a>
        </div>
        <button type="submit" class="submit" name="signin">Login</button>

        <div class="doctor">
            <a href="patientLogin.php">Are you a Patient? Click Here</a>
        </div>
    </form>
</div>
</body>
</html>

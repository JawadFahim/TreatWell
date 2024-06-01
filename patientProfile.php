<?php
session_start();
include "connection.php";
global $conn;

// Retrieve the username from the session variable
$username = $_SESSION["username"];
$user_id = $_SESSION["user_id"];

// Prepare a SELECT statement
$sql = "SELECT Patient_fullname, Patient_username, Patient_dob, Patient_gender, Patient_NID, Patient_address, Patient_phone, Patient_email, patient_id, photo FROM Patient_login WHERE Patient_Username = ?";

if ($stmt = $conn->prepare($sql)) {
    // Bind variables to the prepared statement as parameters
    $stmt->bind_param("s", $param_username);

    // Set parameters
    $param_username = $username;

    // Attempt to execute the prepared statement
    if ($stmt->execute()) {
        // Store result
        $stmt->store_result();

        // Check if username exists, if yes then fetch the data
        if ($stmt->num_rows == 1) {
            // Bind result variables
            $stmt->bind_result($Patient_fullname, $Patient_username, $Patient_dob, $Patient_gender, $Patient_NID, $Patient_address, $Patient_phone, $Patient_email, $patient_id, $photo);
            if ($stmt->fetch()) {
                // Now the data is fetched and can be used in the HTML below
            }
        } else {
            // Display an error message if username doesn't exist
            echo "No account found with that username.";
        }
    } else {
        echo "Oops! Something went wrong. Please try again later.";
    }

    // Close statement
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/patientProfile.css">
</head>
<body>
<div class="container">
    <aside class="sidebar">
        <div class="profile">
            <img src="<?php echo $photo; ?>" alt="Profile Photo">
            <h2><?php echo $Patient_fullname; ?></h2>
        </div>
        <nav class="menu">
            <a href="appointment_list.php">Appointments</a>
            <a href="#">Medical Records</a>

            <a href="patientProfile.php" class="active">Profile Settings</a>
            <a href="medicineCart.php">My Cart</a>

            <a href="patientLogin.php" style="color: red">Log Out</a>
        </nav>
    </aside>
    <main class="content">
        <div class="profile-details">
            <div class="profile-header">

                <h2><?php echo $Patient_fullname; ?></h2> <p><strong>Patient Id: </strong><?php echo $patient_id;?></p>

            </div>
            <div class="profile-info">
                <p><strong>Username:</strong> <?php echo $Patient_username; ?></p>
                <p><strong>Date of Birth:</strong> <?php echo $Patient_dob; ?></p>
                <p><strong>Gender:</strong> <?php echo $Patient_gender; ?></p>
                <p><strong>NID:</strong> <?php echo $Patient_NID; ?></p>
                <p><strong>Address:</strong> <?php echo $Patient_address; ?></p>
                <p><strong>Phone:</strong> <?php echo $Patient_phone; ?></p>
                <p><strong>Email:</strong> <?php echo $Patient_email; ?></p>

            </div>

        </div>
    </main>
</div>
</body>
</html>

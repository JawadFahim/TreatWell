<?php
session_start();
include "connection.php";
global $conn;

// Retrieve the username from the session variable
$username = $_SESSION["username"];
$user_id = $_SESSION["user_id"];

// Prepare a SELECT statement
$sql = "SELECT doctor_login.DoctorID, doctor_login.DoctorName, doctor_login.Gender, doctor_login.DoctorEmail, doctor_login.DoctorPhone, doctor_login.DoctorUsername, doctor_login.regNo, doctorinfo.id, doctorinfo.name, doctorinfo.qualifications, doctorinfo.speciality, doctorinfo.designation, doctorinfo.workplace, doctorinfo.chamber_appointment, doctorinfo.about_section, doctorinfo.appointment_number, doctorinfo.address, doctorinfo.visiting_time_start, doctorinfo.common_speciality, doctorinfo.regNo, doctorinfo.visiting_time_end, doctorinfo.visiting_day FROM doctor_login INNER JOIN doctorinfo ON doctor_login.regNo = doctorinfo.regNo WHERE doctor_login.DoctorUsername = ?";

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
            $stmt->bind_result($DoctorID, $DoctorName, $Gender, $DoctorEmail, $DoctorPhone, $DoctorUsername, $regNo, $id, $name, $qualifications, $speciality, $designation, $workplace, $chamber_appointment, $about_section, $appointment_number, $address, $visiting_time_start, $common_speciality, $regNo, $visiting_time_end, $visiting_day);
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
            <h2><?php echo $name; ?></h2>
        </div>
        <nav class="menu">
            <a href="appointment_list.php">Appointments</a>
            <a href="doctorProfile.php" class="active">Profile Settings</a>
            <a href="doctorLogin.php" style="color: red">Log Out</a>
        </nav>
    </aside>
    <main class="content">
        <div class="profile-details">
            <div class="profile-header">
                <h2><?php echo $name; ?></h2> <p><strong>Doctor Id: </strong><?php echo $DoctorID;?></p>
            </div>
            <div class="profile-info">
                <p><strong>Username:</strong> <?php echo $DoctorUsername; ?></p>
                <p><strong>Gender:</strong> <?php echo $Gender; ?></p>
                <p><strong>Email:</strong> <?php echo $DoctorEmail; ?></p>
                <p><strong>Phone:</strong> <?php echo $DoctorPhone; ?></p>
                <p><strong>Registration Number:</strong> <?php echo $regNo; ?></p>
                <p><strong>Qualifications:</strong> <?php echo $qualifications; ?></p>
                <p><strong>Speciality:</strong> <?php echo $speciality; ?></p>
                <p><strong>Designation :</strong> <?php echo $designation; ?></p>
                <p><strong>Workplace :</strong> <?php echo $workplace; ?></p>
                <p><strong>Appointment Number:</strong> <?php echo $appointment_number; ?></p>
                <p><strong>Visiting Day :</strong> <?php echo $visiting_day; ?></p>



            </div>
        </div>
    </main>
</div>
</body>
</html>

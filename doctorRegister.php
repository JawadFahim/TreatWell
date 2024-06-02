<?php
include 'connection.php';
global $conn;

// Check connection

// Define variables and initialize with empty values
$doctorName = $gender = $doctorDesignation = $doctorSpeciality = $doctorEducationalBackground = $doctorChamber = $doctorEmail = $doctorAvailabilityDateTime = $doctorAppointmentType = $doctorLocation = $doctorPhone = $doctorChargingFee = $medicalLicenseNumber = $address = $doctorUsername = $doctorPassword = $confirmedPassword = "";
$doctorName_err = $gender_err = $doctorDesignation_err = $doctorSpeciality_err = $doctorEducationalBackground_err = $doctorChamber_err = $doctorEmail_err = $doctorAvailabilityDateTime_err = $doctorAppointmentType_err = $doctorLocation_err = $doctorPhone_err = $doctorChargingFee_err = $medicalLicenseNumber_err = $address_err = $doctorUsername_err = $doctorPassword_err = $confirmedPassword_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate DoctorName
    if (empty(trim($_POST["DoctorName"]))) {
        $doctorName_err = "Please enter a Doctor Name.";
    } else {
        $doctorName = trim($_POST["DoctorName"]);
    }

    // Validate Gender
    if (empty(trim($_POST["Gender"]))) {
        $gender_err = "Please enter a Gender.";
    } else {
        $gender = trim($_POST["Gender"]);
    }
//DoctorDesignation, DoctorSpeciality, DoctorEducationalBackground, DoctorChamber, DoctorEmail, DoctorAvailabilityDateTime, DoctorAppointmentType, DoctorLocation, DoctorPhone, DoctorChargingFee, MedicalLicenseNumber, Address, DoctorUsername,
    // ... Repeat this process for all the other fields ...



    if(empty(trim($_POST["DoctorEmail"]))){
        $doctorEmail_err = "Please enter a Doctor Email.";
    } else {
        $doctorEmail = trim($_POST["DoctorEmail"]);
    }



    if(empty(trim($_POST["DoctorPhone"]))){
        $doctorPhone_err = "Please enter a Doctor Phone.";
    } else {
        $doctorPhone = trim($_POST["DoctorPhone"]);
    }




    if(empty(trim($_POST["DoctorUsername"]))){
        $doctorUsername_err = "Please enter a Doctor Username.";
    } else {
        $doctorUsername = trim($_POST["DoctorUsername"]);
    }

    // Validate DoctorPassword
    if (empty(trim($_POST["DoctorPassword"]))) {
        $doctorPassword_err = "Please enter a password.";
    } else {
        $doctorPassword = trim($_POST["DoctorPassword"]);
    }

    // Validate ConfirmedPassword
    if (empty(trim($_POST["ConfirmedPassword"]))) {
        $confirmedPassword_err = "Please confirm the password.";
    } else {
        $confirmedPassword = trim($_POST["ConfirmedPassword"]);
        if($doctorPassword != $confirmedPassword){
            $confirmedPassword_err = "Password did not match.";
        }
    }

    // Define a new variable for BMDCRegNumber
    $BMDCRegNumber = $BMDCRegNumber_err = "";

// Validate BMDCRegNumber
    if(empty(trim($_POST["BMDCRegNumber"]))){
        $BMDCRegNumber_err = "Please enter a BMDC Registration Number.";
    } else {
        $BMDCRegNumber = trim($_POST["BMDCRegNumber"]);
        // Check if the BMDCRegNumber exists in the doctorinfo table
        $sql = "SELECT regNo FROM doctorinfo WHERE regNo = ?";
        if($stmt = $conn->prepare($sql)){
            $stmt->bind_param("s", $param_BMDCRegNumber);
            $param_BMDCRegNumber = $BMDCRegNumber;
            if($stmt->execute()){
                $stmt->store_result();
                if($stmt->num_rows == 1){
                    // BMDCRegNumber exists in the doctorinfo table
                    $BMDCRegNumber_err = "";
                } else {
                    // BMDCRegNumber does not exist in the doctorinfo table
                    $BMDCRegNumber_err = "BMDC Registration Number did not match.";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
            $stmt->close();
        }
    }



    // Check input errors before inserting in database
    // Prepare a SELECT statement to fetch the id from doctorinfo where regNo matches
    $sql = "SELECT id FROM doctorinfo WHERE regNo = ?";
    if ($stmt = $conn->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("s", $param_BMDCRegNumber);
        // Set parameters
        $param_BMDCRegNumber = $BMDCRegNumber;
        // Execute the statement
        if ($stmt->execute()) {
            // Store the result
            $stmt->store_result();
            // Check if a matching id was found
            if ($stmt->num_rows == 1) {
                // Bind the result to a variable
                $stmt->bind_result($id_from_doctorinfo);
                $stmt->fetch();
                // Use the fetched id for the INSERT into doctor_login
                $sql = "INSERT INTO Doctor_login (id, DoctorName, Gender, DoctorEmail, DoctorPhone, DoctorUsername, DoctorPassword, regNo) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                if ($stmt_insert = $conn->prepare($sql)) {
                    // Bind variables to the prepared statement as parameters
                    $stmt_insert->bind_param("isssssss", $id_from_doctorinfo, $param_doctorName, $param_gender, $param_doctorEmail, $param_doctorPhone, $param_doctorUsername, $param_doctorPassword, $param_BMDCRegNumber);
                    // Set the rest of the parameters as before
                    $param_doctorName = $doctorName;
                    $param_gender = $gender;
                    $param_doctorEmail = $doctorEmail;
                    $param_doctorPhone = $doctorPhone;
                    $param_doctorUsername = $doctorUsername;
                    $param_doctorPassword = $doctorPassword;
                    // Attempt to execute the prepared statement
                    if ($stmt_insert->execute()) {
                        // Redirect to login page
                        header("location: doctorLogin.php");
                    } else {
                        echo "Something went wrong. Please try again later.";
                    }
                    // Close the insert statement
                    $stmt_insert->close();
                }
            } else {
                echo "No matching id found in doctorinfo for the provided regNo.";
            }
        } else {
            echo "Something went wrong. Please try again later.";
        }
        // Close the select statement
        $stmt->close();
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
    <title>Doctor Registration Form | TreatWell</title>
    <link rel="stylesheet" href="css/patientLogin.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body style="background: url(image/doctor.jpg);background-size: cover;background-position: center;">
<div class="wrapper">
    <form class="mt-6" action="doctorRegister.php" method="POST">
        <h1>Doctor Registration</h1>
        <div class="input-container">
            <div class="input-column">
                <div class="input-box">
                    <input type="text" name ="DoctorName" placeholder="Doctor Name" required>
                    <i class='bx bxs-user'></i>
                </div>
                <div class="input-box">
                    <input type="text" name="Gender" placeholder="Gender" required>
                    <i class='bx bxs-user'></i>
                </div>



                <div class="input-box">
                    <input type="text" name="DoctorEmail" placeholder="Doctor Email" required>
                    <i class='bx bxs-user'></i>
                </div>
            </div>
            <div class="input-column">


                <div class="input-box">
                    <input type="text" name="DoctorPhone" placeholder="Doctor Phone" required>
                    <i class='bx bxs-user'></i>
                </div>


                <div class="input-box">
                    <input type="text" name="DoctorUsername" placeholder="Doctor Username" required>
                    <i class='bx bxs-user'></i>
                </div>
                <div class="input-box">
                    <input type="password" name="DoctorPassword" placeholder="Password" required>
                    <i class='bx bxs-lock-alt' ></i>
                </div>
                <div class="input-box">
                    <input type="password" name="ConfirmedPassword" placeholder="Confirm Password" required>
                    <i class='bx bxs-lock-alt' ></i>
                </div>
                <div class="input-box">
                    <input type="text" name="BMDCRegNumber" placeholder="BMDC Registration Number" required>
                    <i class='bx bxs-user'></i>
                </div>
            </div>
        </div>
        <button type="submit" class="submit" name="register">Register</button>
        <div class="register-link">
            <p>Already have an account? <a href="doctorLogin.php">Login</a></p>
        </div>
    </form>
</div>
</body>
</html>

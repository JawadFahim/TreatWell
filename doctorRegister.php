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

    if(empty(trim($_POST["DoctorDesignation"]))){
        $doctorDesignation_err = "Please enter a Doctor Designation.";
    } else {
        $doctorDesignation = trim($_POST["DoctorDesignation"]);
    }
    if(empty(trim($_POST["DoctorSpeciality"]))){
        $doctorSpeciality_err = "Please enter a Doctor Speciality.";
    } else {
        $doctorSpeciality = trim($_POST["DoctorSpeciality"]);
    }
    if(empty(trim($_POST["DoctorEducationalBackground"]))){
        $doctorEducationalBackground_err = "Please enter a Doctor Educational Background.";
    } else {
        $doctorEducationalBackground = trim($_POST["DoctorEducationalBackground"]);
    }
    if(empty(trim($_POST["DoctorChamber"]))){
        $doctorChamber_err = "Please enter a Doctor Chamber.";
    } else {
        $doctorChamber = trim($_POST["DoctorChamber"]);
    }
    if(empty(trim($_POST["DoctorEmail"]))){
        $doctorEmail_err = "Please enter a Doctor Email.";
    } else {
        $doctorEmail = trim($_POST["DoctorEmail"]);
    }
    if(empty(trim($_POST["DoctorAvailabilityDateTime"]))){
        $doctorAvailabilityDateTime_err = "Please enter a Doctor Availability Date Time.";
    } else {
        $doctorAvailabilityDateTime = trim($_POST["DoctorAvailabilityDateTime"]);
    }
    if(empty(trim($_POST["DoctorAppointmentType"]))){
        $doctorAppointmentType_err = "Please enter a Doctor Appointment Type.";
    } else {
        $doctorAppointmentType = trim($_POST["DoctorAppointmentType"]);
    }
    if(empty(trim($_POST["DoctorLocation"]))){
        $doctorLocation_err = "Please enter a Doctor Location.";
    } else {
        $doctorLocation = trim($_POST["DoctorLocation"]);
    }
    if(empty(trim($_POST["DoctorPhone"]))){
        $doctorPhone_err = "Please enter a Doctor Phone.";
    } else {
        $doctorPhone = trim($_POST["DoctorPhone"]);
    }
    if(empty(trim($_POST["DoctorChargingFee"]))){
        $doctorChargingFee_err = "Please enter a Doctor Charging Fee.";
    } else {
        $doctorChargingFee = trim($_POST["DoctorChargingFee"]);
    }
    if(empty(trim($_POST["MedicalLicenseNumber"]))){
        $medicalLicenseNumber_err = "Please enter a Medical License Number.";
    } else {
        $medicalLicenseNumber = trim($_POST["MedicalLicenseNumber"]);
    }
    if(empty(trim($_POST["Address"]))){
        $address_err = "Please enter a Address.";
    } else {
        $address = trim($_POST["Address"]);
    }
    if(empty(trim($_POST["DoctorSpeciality"]))){
        $doctorSpeciality_err = "Please enter a Doctor Speciality.";
    } else {
        $doctorSpeciality = trim($_POST["DoctorSpeciality"]);
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

    // Check input errors before inserting in database
    if (empty($doctorName_err) && empty($gender_err) && empty($doctorDesignation_err) && empty($doctorSpeciality_err) && empty($doctorEducationalBackground_err) && empty($doctorChamber_err) && empty($doctorEmail_err) && empty($doctorAvailabilityDateTime_err) && empty($doctorAppointmentType_err) && empty($doctorLocation_err) && empty($doctorPhone_err) && empty($doctorChargingFee_err) && empty($medicalLicenseNumber_err) && empty($address_err) && empty($doctorUsername_err) && empty($doctorPassword_err) && empty($confirmedPassword_err)) {
        // Prepare an INSERT statement
        $sql = "INSERT INTO Doctor_login (DoctorName, Gender,DoctorDesignation, DoctorSpeciality, DoctorEducationalBackground, DoctorChamber, DoctorEmail, DoctorAvailabilityDateTime, DoctorAppointmentType, DoctorLocation, DoctorPhone, DoctorChargingFee, MedicalLicenseNumber, Address, DoctorUsername,  DoctorPassword) VALUES (?, ?, ?, ?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)";

        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            //$stmt->bind_param("ss", $param_doctorName, $param_gender, /* ... */, $param_doctorPassword);
            $stmt->bind_param("ssssssssssssssss", $param_doctorName, $param_gender, $param_doctorDesignation, $param_doctorSpeciality, $param_doctorEducationalBackground, $param_doctorChamber, $param_doctorEmail, $param_doctorAvailabilityDateTime, $param_doctorAppointmentType, $param_doctorLocation, $param_doctorPhone, $param_doctorChargingFee, $param_medicalLicenseNumber, $param_address, $param_doctorUsername, $param_doctorPassword);
            // Set parameters
            $param_doctorName = $doctorName;
            $param_gender = $gender;
            $param_doctorDesignation = $doctorDesignation;
            $param_doctorSpeciality = $doctorSpeciality;
            $param_doctorEducationalBackground = $doctorEducationalBackground;
            $param_doctorChamber = $doctorChamber;
            $param_doctorEmail = $doctorEmail;
            $param_doctorAvailabilityDateTime = $doctorAvailabilityDateTime;
            $param_doctorAppointmentType = $doctorAppointmentType;
            $param_doctorLocation = $doctorLocation;
            $param_doctorPhone = $doctorPhone;
            $param_doctorChargingFee = $doctorChargingFee;
            $param_medicalLicenseNumber = $medicalLicenseNumber;
            $param_address = $address;
            $param_doctorUsername = $doctorUsername;
            $param_doctorPassword = $doctorPassword;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Redirect to login page
                header("location: doctorLogin.php");
            } else {
                echo "Something went wrong. Please try again later.";
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
                    <input type="text" name="DoctorDesignation" placeholder="Doctor Designation" required>
                    <i class='bx bxs-user'></i>
                </div>
                <div class="input-box">
                    <input type="text" name="DoctorSpeciality" placeholder="Doctor Speciality" required>
                    <i class='bx bxs-user'></i>
                </div>
                <div class="input-box">
                    <input type="text" name="DoctorEducationalBackground" placeholder="Doctor Educational Background" required>
                    <i class='bx bxs-user'></i>
                </div>
                <div class="input-box">
                    <input type="text" name="DoctorChamber" placeholder="Doctor Chamber" required>
                    <i class='bx bxs-user'></i>
                </div>
                <div class="input-box">
                    <input type="text" name="DoctorEmail" placeholder="Doctor Email" required>
                    <i class='bx bxs-user'></i>
                </div>
            </div>
            <div class="input-column">
                <div class="input-box">
                    <input type="text" name="DoctorAvailabilityDateTime" placeholder="Doctor Availability Date Time" required>
                    <i class='bx bxs-user'></i>
                </div>
                <div class="input-box">
                    <input type="text" name="DoctorAppointmentType" placeholder="Doctor Appointment Type" required>
                    <i class='bx bxs-user'></i>
                </div>
                <div class="input-box">
                    <input type="text" name="DoctorLocation" placeholder="Doctor Location" required>
                    <i class='bx bxs-user'></i>
                </div>
                <div class="input-box">
                    <input type="text" name="DoctorPhone" placeholder="Doctor Phone" required>
                    <i class='bx bxs-user'></i>
                </div>
                <div class="input-box">
                    <input type="text" name="DoctorChargingFee" placeholder="Doctor Charging Fee" required>
                    <i class='bx bxs-user'></i>
                </div>
                <div class="input-box">
                    <input type="text" name="MedicalLicenseNumber" placeholder="Medical License Number" required>
                    <i class='bx bxs-user'></i>
                </div>
                <div class="input-box">
                    <input type="text" name="Address" placeholder="Address" required>
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
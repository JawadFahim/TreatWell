<?php

include 'connection.php';
global $conn;




// Define variables and initialize with empty values
$fullname = $username = $dob = $gender = $national_id = $address = $phone = $email = $password = $confirm_password = "";
$fullname_err = $username_err = $dob_err = $gender_err = $national_id_err = $address_err = $phone_err = $email_err = $password_err = $confirm_password_err = "";
$temp_name=$extension=$new_name="";
// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate fullname
    if (empty(trim($_POST["fullname"]))) {
        $fullname_err = "Please enter a full name.";
    } else {
        $fullname = trim($_POST["fullname"]);
    }

    // Validate username
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter a username.";
    } else {
        $username = trim($_POST["username"]);
    }

    // Validate dob
    if (empty(trim($_POST["dob"]))) {
        $dob_err = "Please enter a date of birth.";
    } else {
        $dob = trim($_POST["dob"]);
    }

    // Validate gender
    if (empty(trim($_POST["gender"]))) {
        $gender_err = "Please select a gender.";
    } else {
        $gender = trim($_POST["gender"]);
    }

    // Validate national_id
    if (empty(trim($_POST["national_id"]))) {
        $national_id_err = "Please enter a national ID card number.";
    } else {
        $national_id = trim($_POST["national_id"]);
    }

    // Validate address
    if (empty(trim($_POST["address"]))) {
        $address_err = "Please enter an address.";
    } else {
        $address = trim($_POST["address"]);
    }

    // Validate phone
    if (empty(trim($_POST["phone"]))) {
        $phone_err = "Please enter a phone number.";
    } else {
        $phone = trim($_POST["phone"]);
    }

    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter an email ID.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate confirm_password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm the password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if($password != $confirm_password){
            $confirm_password_err = "Password did not match.";
        }
    }


    if (isset($_FILES['pic'])) {
        $target_file = basename($_FILES['pic']['name']);
        $filename = $username.".".strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        $tempname = $_FILES['pic']['tmp_name'];
        $new_name = "image/patientPhoto/".$filename;
        if (move_uploaded_file($tempname, $new_name)) {
            echo "File has been uploaded successfully.";
        } else {
            echo "Failed to upload file.";
        }
    }
    // Check input errors before inserting in database
    // Add this in your PHP code after validating all other inputs
    if (empty($fullname_err) && empty($username_err) && empty($dob_err) && empty($gender_err) && empty($national_id_err) && empty($address_err) && empty($phone_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err)) {

        // Handle the photo upload



                // Prepare an INSERT statement
                $sql = "INSERT INTO Patient_login (Patient_fullname, Patient_username, Patient_dob, Patient_gender, Patient_NID, Patient_address, Patient_phone,Patient_email, patient_pass, photo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

                if ($stmt = $conn->prepare($sql)) {
                    // Bind variables to the prepared statement as parameters
                    $stmt->bind_param("ssssssssss", $param_fullname, $param_username, $param_dob, $param_gender, $param_national_id, $param_address, $param_phone, $param_email, $param_password, $param_photo);

                    // Set parameters
                    $param_fullname = $fullname;
                    $param_username = $username;
                    $param_dob = $dob;
                    $param_gender = $gender;
                    $param_national_id = $national_id;
                    $param_address = $address;
                    $param_phone = $phone;
                    $param_email = $email;
                    $param_password = $password; // Creates a password hash
                    $param_photo = $new_name; // Set the photo path

                    if ($stmt->execute()) {
                        // Redirect to login page
                        header("location: patientLogin.php");
                    } else {
                        echo "Something went wrong. Please try again later.";
                    }
                }
    } else {
        die("Database connection is null.");
    }

                    // Close statement
                    $stmt->close();





    // Close connection
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Form | Treatwell</title>
    <link rel="stylesheet" href="css/patientRegister.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body style="background: url(image/patient.jpg);background-size: cover;background-position: center;">
<div class="wrapper">
    <form class="mt-6" action="patientRegister.php" method="POST" enctype="multipart/form-data">
        <h1>Patient Register</h1>
        <div class="input-container">
            <div class="input-column">
                <div class="input-box">
                    <input type="text" name="fullname" placeholder="Full Name" required>
                    <i class='bx bxs-user'></i>
                </div>
                <div class="input-box">
                    <input type="text" name="username" placeholder="Username" required>
                    <i class='bx bxs-user'></i>
                </div>
                <div class="input-box">
                    <input type="date" name="dob" placeholder="Date of Birth" required>
                    <i class='bx bxs-calendar'></i>
                </div>
                <div class="input-box">
                    <label>Gender </label>
                    <label><input type="radio" name="gender" value="male" required> Male</label>
                    <label><input type="radio" name="gender" value="female" required> Female</label>
                </div>
                <div class="input-box">
                    <input type="text" name="national_id" placeholder="National ID Card Number" required>
                    <i class='bx bxs-id-card'></i>
                </div>
            </div>
            <div class="input-column">
                <div class="input-box">
                    <input type="text" name="address" placeholder="Address" required>
                    <i class='bx bxs-map'></i>
                </div>
                <div class="input-box">
                    <input type="tel" name="phone" placeholder="Phone Number" required>
                    <i class='bx bxs-phone'></i>
                </div>
                <div class="input-box">
                    <input type="email" name="email" placeholder="Email ID" required>
                    <i class='bx bxs-envelope'></i>
                </div>
                <div class="input-box">
                    <input type="password" name="password" placeholder="Password" required>
                    <i class='bx bxs-lock-alt'></i>
                </div>
                <div class="input-box">
                    <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                    <i class='bx bxs-lock-alt'></i>
                </div>

                <div class="input-box">
                    <input type="file" name="pic" required>
                    <i class='bx bxs-image-add'></i>
                </div>
            </div>
        </div>
        <button type="submit" class="submit" name="register">Register</button>
        <div class="login-link">
            <p>Already have an account? <a href="patientLogin.php">Login</a></p>
        </div>
    </form>
</div>
</body>
</html>
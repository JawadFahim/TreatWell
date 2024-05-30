<?php
$pdo = new PDO('mysql:host=localhost;dbname=treatwell', 'root', 'root');

$doctor_id = isset($_GET['id']) ? $_GET['id'] : '';

// Prepare the SQL query
$sql = "SELECT * FROM doctorinfo WHERE id = :doctor_id";

// Prepare the statement
$stmt = $pdo->prepare($sql);

// Bind the parameters
$stmt->bindParam(':doctor_id', $doctor_id, PDO::PARAM_INT);

// Execute the statement
$stmt->execute();

// Fetch the result
$doctor = $stmt->fetch(PDO::FETCH_ASSOC);
// Fetch the doctor's available time
$sql = "SELECT visiting_time_start, visiting_time_end FROM doctorinfo WHERE id = :doctor_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':doctor_id', $doctor_id, PDO::PARAM_INT);
$stmt->execute();
$doctor_time = $stmt->fetch(PDO::FETCH_ASSOC);

// Convert the time to 24-hour format
$start_time = isset($doctor_time['visiting_time_start']) ? date("H:i:s", strtotime($doctor_time['visiting_time_start'])) : '00:00:00';
$end_time = isset($doctor_time['visiting_time_end']) ? date("H:i:s", strtotime($doctor_time['visiting_time_end'])) : '23:59:59';

// Fetch the doctor's available day
$sql = "SELECT visiting_day FROM doctorinfo WHERE id = :doctor_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':doctor_id', $doctor_id, PDO::PARAM_INT);
$stmt->execute();
$doctor_day = $stmt->fetch(PDO::FETCH_ASSOC);

// Convert the day to an array
// Convert the day to an array

$available_days = $doctor_day['visiting_day'] !== null ? explode(', ', $doctor_day['visiting_day']) : [];
if ($doctor === false) {
    echo "Doctor not found";
    exit;
}
?>
<!DOCTYPE html>


<html>
<head>
    <title>Book Appointment</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 80%;
            margin: 0 auto;
        }
        .header, .header__form {
            background-color: #fff;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .appointment-form {
            background-color: #fff;
            margin-top: 30px;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.1);
        }
        .appointment-form label {
            display: block;
            margin-bottom: 5px;
        }
        .appointment-form input[type="date"],
        .appointment-form input[type="time"],
        .appointment-form select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .appointment-form button {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #fb923c;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .appointment-form button:hover {
            background-color: #fb923c;
        }
        .info {
            text-align: center;
        }
        .info.bold {
            font-weight: bold;
        }
        .info.name {
            font-size: 2em; /* Adjust size as needed */
            color: rgb(251, 146, 60);
        }
        .info.speciality {
            color: red;
        }
        .back-button-container .btn {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            background-color: #fb923c;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .back-button-container .btn:hover {
            background-color: #c75f22;

        }
        .back-button-container {
            text-align: center;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            background-color: #fb923c;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #c75f22;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <div class="service__card">
            <span><i class="ri-microscope-line"></i></span>
            <h1 class="info">Book an Appointment</h1>
            <div class="info name bold"><?= htmlspecialchars($doctor['name']) ?></div>
            <div class="info bold"><?= htmlspecialchars($doctor['qualifications']) ?></div>
            <div class="info bold speciality"><?= htmlspecialchars($doctor['speciality']) ?></div>
            <div class="info"><?= htmlspecialchars($doctor['designation'] ?? '') ?></div>
            <div class="info"><?= htmlspecialchars($doctor['workplace']) ?></div>
            <div class="info"><strong>Address:</strong> <?= htmlspecialchars($doctor['address']) ?></div>
            <br>
            <div class="info"><strong>About:</strong><br> <?= htmlspecialchars($doctor['about_section']) ?></div>
            <br>
            <div class="info"><strong>Visiting Hour:</strong> <?= htmlspecialchars($doctor['visiting_hour']) ?></div>
            <div class="info bold">
                Appointment Number: <?= htmlspecialchars($doctor['appointment_number']) ?>
                <a href="tel:<?= htmlspecialchars($doctor['appointment_number']) ?>" class="call-now-button">Call Now</a>
            </div>

        </div>
    </div>
</div>

<!--<div class="appointment-form">
    <form action="submit_appointment.php" method="post">-->
        <!-- <label for="consultationType">Consultation Type:</label>
         <select id="consultationType" name="consultationType">
             <option value="In-Person">In-Person</option>
             <option value="Online">Online</option>
             <option value="Home Visit">Home Visit</option>
         </select>-->
        <!-- <label for="date">Select a date:</label>
         <input type="date" id="date" name="date">
         <label for="time">Select a time:</label>
         <input type="time" id="time" name="time">-->

        <div class="appointment-form">
            <form action="submit_appointment.php" method="post">
                <input type="hidden" name="id" value="<?php echo $doctor_id; ?>">
                <label for="time">Select a time:</label>
                <select id="time" name="time">
                    <?php
                    // Generate the options for the select menu
                    for ($i = strtotime($start_time); $i <= strtotime($end_time); $i += 1800) { // Change 1800 to the desired time interval in seconds
                        echo '<option value="' . date("H:i:s", $i) . '">' . date("H:i:s", $i) . '</option>';
                    }
                    ?>
                </select>
                <label for="day">Select a day:</label>
                <select id="day" name="day">
                    <?php
                    // Generate the options for the select menu
                    foreach ($available_days as $day) {
                        echo '<option value="' . $day . '">' . $day . '</option>';
                    }
                    ?>
                </select>
                <div class="back-button-container">
                    <button type="submit" class="btn">Book Appointment</button>
                </div>
            </form>
        </div>
</div>
</body>
</html>

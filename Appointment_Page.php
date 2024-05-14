<?php
$doctor_id = isset($_GET['doctor_id']) ? $_GET['doctor_id'] : '';
$doctor_name = isset($_GET['doctor_name']) ? $_GET['doctor_name'] : '';
$specialty = isset($_GET['specialty']) ? $_GET['specialty'] : '';
?><!DOCTYPE html>

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
            background-color: #c75f22;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <div class="service__card">
            <span><i class="ri-microscope-line"></i></span>
            <h1>Book an Appointment</h1>
            <p>Doctor's ID: <?= htmlspecialchars($doctor_id) ?></p>
            <p>Doctor's Name: <?= htmlspecialchars($doctor_name) ?></p>
            <p>Specialty: <?= htmlspecialchars($specialty) ?></p>
        </div>


        <div class="appointment-form">
            <form action="submit_appointment.php" method="post">
                <label for="consultationType">Consultation Type:</label>
                <select id="consultationType" name="consultationType">
                    <option value="In-Person">In-Person</option>
                    <option value="Online">Online</option>
                    <option value="Home Visit">Home Visit</option>
                </select>
                <label for="date">Select a date:</label>
                <input type="date" id="date" name="date">
                <label for="time">Select a time:</label>
                <input type="time" id="time" name="time">
                <button type="submit">Book Appointment</button>
            </form>
        </div>
    </div>
</body>
</html>
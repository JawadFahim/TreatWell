<?php
// Database credentials
$servername = "localhost";
$username = "root";
$password = "root";
$database = "TreatWell";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Start the session
session_start();

// Check if patient_id is set in session
if(isset($_SESSION['user_id'])){
    $patient_id = $_SESSION['user_id'];
} else {
    echo "Patient ID not found in session.";
    exit();
}

// Fetch appointments for the logged-in user
$sql = "SELECT * FROM appointments WHERE P_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$result = $stmt->get_result();

?>
<!DOCTYPE html>
<html>
<head>
    <title>Appointment Confirmation</title>
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
        .confirmation {
            background-color: #fff;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
            color: green;
            font-size: 1.5em;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 15px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #ddd;
        }
        .cancel-button {
            color: red;
            cursor: pointer;
        }
    </style>
    <script>
        function confirmCancel(apId) {
            if (confirm("Are you sure you want to cancel this appointment?")) {
                window.location.href = "cancel_appointment.php?id=" + apId;
            }
        }
    </script>
    <title> Appointment List </title>
</head>
<body>
<div class="confirmation">
    Your Appointment List
</div>
<table>
    <thead>
    <tr>
        <th>Appointment ID</th>
        <th>Speciality</th>
        <th>Qualification</th>
        <th>About</th>
        <th>Designation</th>
        <th>Patient Name</th>
        <th>Patient ID</th>
        <th>Doctor Name</th>
        <th>Doctor ID</th>
        <th>Appointment Time</th>
        <th>Appointment Day</th>
        <th>Appointment Date</th>
        <th>Cancel Your Booking</th>
    </tr>
    </thead>
    <tbody>
    <?php
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['AP_ID'] . "</td>";
            echo "<td>" . $row['Speciality'] . "</td>";
            echo "<td>" . $row['Qualification'] . "</td>";
            echo "<td>" . $row['About'] . "</td>";
            echo "<td>" . $row['Designation'] . "</td>";
            echo "<td>" . $row['P_Name'] . "</td>";
            echo "<td>" . $row['P_ID'] . "</td>";
            echo "<td>" . $row['D_Name'] . "</td>";
            echo "<td>" . $row['D_ID'] . "</td>";
            echo "<td>" . $row['appointment_time'] . "</td>";
            echo "<td>" . $row['appointment_day'] . "</td>";
            echo "<td>" . $row['appointment_date'] . "</td>";
            echo "<td><span class='cancel-button' onclick='confirmCancel(" . $row['AP_ID'] . ")'>Cancel</span></td>";
            echo "</tr>";
        }
    } else {
        echo "No appointments found";
    }
    ?>
    </tbody>
</table>
</body>
</html>
<?php
// Close connection
$conn->close();
?>

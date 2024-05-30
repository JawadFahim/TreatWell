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

// Check if doctor_id is set in GET parameters
if(isset($_POST['id'])){
    $doctor_id = $_POST['id'];
} else {
    echo "Doctor ID not found in GET parameters.";
    exit();
}
// Check if day is set in GET parameters
if(isset($_POST['day'])){
    $selected_day = $_POST['day'];
} else {
    echo "Day not found in GET parameters.";
    exit();
}
// Check if time is set in GET parameters
if(isset($_POST['time'])){
    $appointment_time = date('H:i:s', strtotime($_POST['time']));
} else {
    echo "Time not found in GET parameters.";
    exit();
}

$day_sql = "SELECT visiting_day FROM doctorinfo WHERE id = ?";
$day_stmt = $conn->prepare($day_sql);
$day_stmt->bind_param("i", $doctor_id);
$day_stmt->execute();
$doctor_day = $day_stmt->get_result()->fetch_assoc();

// Check if the selected day is within the doctor's available day
if (!in_array($_POST['day'], explode(', ', $doctor_day['visiting_day']))) {
    echo "The doctor is not available on this day.";
    exit();
}

// Check if an appointment with the same doctor and patient already exists
$check_sql = "SELECT * FROM appointments WHERE P_ID = ? AND D_ID = ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("ii", $patient_id, $doctor_id);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if ($check_result->num_rows > 0) {
    echo "You have already booked an appointment with this doctor. ";
    echo "<a href='appointment_list.php' class='btn'>View your appointments</a>";
    exit();
}

// Fetch patient and doctor info
$patient_sql = "SELECT * FROM patient_login WHERE Patient_ID = ?";
$doctor_sql = "SELECT * FROM doctorinfo WHERE id = ?";

$patient_stmt = $conn->prepare($patient_sql);
$patient_stmt->bind_param("i", $patient_id);
$patient_stmt->execute();
$patient = $patient_stmt->get_result()->fetch_assoc();

$doctor_stmt = $conn->prepare($doctor_sql);
$doctor_stmt->bind_param("i", $doctor_id);
$doctor_stmt->execute();
$doctor = $doctor_stmt->get_result()->fetch_assoc();

// Prepare an SQL INSERT statement
$sql = "INSERT INTO appointments (Speciality, Qualification, About, Designation, P_Name, P_ID, D_Name, D_ID, appointment_time, appointment_day, appointment_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

// Prepare the statement
if ($stmt = $conn->prepare($sql)) {
    // Bind the form data to the SQL statement
    $doctor_name = $doctor['name'];
    $appointment_time = date('H:i:s', strtotime($_POST['time']));
    $appointment_day = $_POST['day']; // Use the selected day directly
    $selected_day = $_POST['day']; // The selected day, e.g., 'Tuesday'
    $next_day_timestamp = strtotime('next ' . $selected_day);
    $appointment_date = date('Y-m-d', $next_day_timestamp); // The date of the next occurrence of the selected day
    $stmt->bind_param("sssssississ", $doctor['speciality'], $doctor['qualifications'], $doctor['about_section'], $doctor['designation'], $patient['Patient_Fullname'], $patient['Patient_ID'], $doctor_name, $doctor['id'], $appointment_time, $appointment_day, $appointment_date);
    // Execute the SQL statement
    if ($stmt->execute()) {
        // Successful insertion message
    } else {
        echo "Error: " . $stmt->error;
    }
    // Close statement
    $stmt->close();
} else {
    echo "Error: " . $conn->error;
}
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
        .btn {
            display: inline-block;
            margin: 10px;
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            background-color: #fb923c; /* You can replace this with your project's color code */
            border: none;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #c75f22; /* You can replace this with your project's color code */
        }
    </style>
    <script>
        function confirmCancel(apId) {
            if (confirm("Are you sure you want to cancel this appointment?")) {
                window.location.href = "cancel_appointment.php?id=" + apId;
            }
        }
    </script>
</head>
<body>
<div class="confirmation">
    Appointment booked successfully!
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
        <th>Appointment Time</th> <!-- New column -->
        <th>Appointment Day</th> <!-- New column -->
        <th>Appointment Date</th> <!-- New column -->
        <th>Cancel Your Booking</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $sql = "SELECT * FROM appointments WHERE P_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $patient_id);
    $stmt->execute();
    $result = $stmt->get_result();

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
            echo "<td>" . date('H:i:s', strtotime($row['appointment_time'])) . "</td>"; // New column
            echo "<td>" . $row['appointment_day'] . "</td>"; // New column
            echo "<td>" . $row['appointment_date'] . "</td>"; // New column
            echo "<td><span class='cancel-button' onclick='confirmCancel(" . $row['AP_ID'] . ")'>Cancel</span></td>";
            echo "</tr>";
        }
    } else {
        echo "No appointments found";
    }
    ?>
    </tbody>
</table>
<a href="appointment_list.php" class="btn">Back to Appointment List</a>

</body>
</html>
<?php
// Close connection
$conn->close();
?>


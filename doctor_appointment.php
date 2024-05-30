<?php
session_start();
include 'connection.php';
global $conn;

$doctorUsername = $_SESSION["username"]; // replace this with the actual session variable for the doctor's username

// Get the regNo of the logged-in doctor
$sql = "SELECT regNo FROM doctor_login WHERE DoctorUsername = :username";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':username', $doctorUsername);
$stmt->execute();
$doctorRegNo = $stmt->fetchColumn();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['reject'])) {
        $appointmentId = $_POST['appointmentId'];
        $sql = "UPDATE appointments SET status = 'Rejected' WHERE AP_ID = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $appointmentId);
        $stmt->execute();
    } elseif (isset($_POST['approve'])) {
        $appointmentId = $_POST['appointmentId'];
        $sql = "UPDATE appointments SET status = 'Approved' WHERE AP_ID = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $appointmentId);
        $stmt->execute();
    }
}

// Select the appointments for the logged-in doctor
// Select the appointments for the logged-in doctor
$sql = "SELECT a.AP_ID as id, a.P_ID as patient_id, a.P_Name as patient_name, a.appointment_time, a.appointment_date, a.appointment_day, a.status
        FROM appointments a
        JOIN doctorinfo d ON a.D_ID = d.id
        WHERE d.regNo = :regNo";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':regNo', $doctorRegNo);
$stmt->execute();
$appointments = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Doctor Appointments</title>
    <style>
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 15px;
            margin-top: 20px;
            font-family: Arial, sans-serif;
        }

        th {
            background-color: #f2f2f2;
            text-align: left;
        }

        th, td {
            padding: 10px;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:nth-child(odd) {
            background-color: #ffffff;
        }

        form {
            display: inline-block;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 6px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>


<div class="header__form">
    <h1>Appointments</h1>
    <button onclick="window.history.back();">Go Back</button>
    <table>
        <tr>
            <th>Appointment ID</th>
            <th>Patient Name</th>
            <th>Date</th>
            <th>Time</th>
            <th>Day</th>
            <th>Status</th>
            <th>Choose</th>
        </tr>
        <?php foreach ($appointments as $appointment): ?>
            <tr>
                <td><?= isset($appointment['id']) ? htmlspecialchars($appointment['id']) : '' ?></td>
                <td><?= isset($appointment['patient_name']) ? htmlspecialchars($appointment['patient_name']) : '' ?></td>
                <td><?= isset($appointment['appointment_date']) ? htmlspecialchars($appointment['appointment_date']) : '' ?></td>
                <td><?= isset($appointment['appointment_time']) ? htmlspecialchars($appointment['appointment_time']) : '' ?></td>
                <td><?= isset($appointment['appointment_day']) ? htmlspecialchars($appointment['appointment_day']) : '' ?></td>
                <td><?= isset($appointment['status']) ? htmlspecialchars($appointment['status']) : '' ?></td>
                <td>
                    <form method="post">
                        <input type="hidden" name="appointmentId" value="<?= isset($appointment['id']) ? $appointment['id'] : '' ?>">
                        <?php if ($appointment['status'] === 'Pending'): ?>
                            <input type="submit" name="approve" value="Approve">
                            <input type="submit" name="reject" value="Reject" onclick="return confirm('Are you sure you want to reject this appointment?')">
                        <?php else: ?>
                            <span class="disabled"><?= htmlspecialchars($appointment['status']) ?></span>
                        <?php endif; ?>
                        <button type="button" onclick="checkStatusAndRedirect('<?= $appointment['status'] ?>', '<?= $appointment['patient_id'] ?>')">Write Prescription</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

<script>
    function checkStatusAndRedirect(status, patientId) {
        if (status === 'Pending') {
            alert('You should either approve or reject first then write the prescription');
        } else {
            window.location.href = 'prescription.php?patient_id=' + patientId;
        }
    }
</script>

</body>
</html>
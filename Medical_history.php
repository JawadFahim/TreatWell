<?php
session_start();
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

// Fetch patient ID from session
$patient_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0;
if ($patient_id > 0) {
    // Fetch data from appointments and prescriptions
    $sql = "SELECT a.*, p.* FROM appointments a
            JOIN prescriptions p ON a.P_ID = p.patient_id
            WHERE a.P_ID = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    // Bind parameter
    $stmt->bind_param("i", $patient_id);

    // Execute statement
    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }

    // Get result
    $result = $stmt->get_result();
    if (!$result) {
        die("Getting result set failed: " . $stmt->error);
    }

    // Insert data into medical_history
    while ($row = $result->fetch_assoc()) {
        // Check if the record already exists in the medical_history table
        $sql = "SELECT * FROM medical_history WHERE p_ID = ? AND Ap_ID = ?";
        $checkStmt = $conn->prepare($sql);
        if (!$checkStmt) {
            die("Prepare failed: " . $conn->error);
        }

        // Bind parameters
        $checkStmt->bind_param("ii", $patient_id, $row['AP_ID']);

        // Execute statement
        if (!$checkStmt->execute()) {
            die("Execute failed: " . $checkStmt->error);
        }

        // Get result
        $checkResult = $checkStmt->get_result();
        if (!$checkResult) {
            die("Getting result set failed: " . $checkStmt->error);
        }

        // If the record does not exist in the medical_history table, insert it
        if ($checkResult->num_rows == 0) {
            $sql = "INSERT INTO medical_history (p_ID, Ap_ID, Symptoms, tests, advice, medicine_period, appointment_date, d_name, speciality, medicine_name)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $insertStmt = $conn->prepare($sql);
            if (!$insertStmt) {
                die("Prepare failed: " . $conn->error);
            }

            // Bind parameters
            $insertStmt->bind_param("iissssssss", $patient_id, $row['AP_ID'], $row['symptoms'], $row['tests'], $row['advice'], $row['medicine_period'], $row['appointment_date'], $row['D_Name'], $row['Speciality'], $row['medicine_name']);

            // Execute statement
            if (!$insertStmt->execute()) {
                die("Execute failed: " . $insertStmt->error);
            }
        }

        // Close the check statement
        $checkStmt->close();
    }

    // Fetch data from medical_history
    $sql = "SELECT * FROM medical_history WHERE p_ID = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    // Bind parameter
    $stmt->bind_param("i", $patient_id);

    // Execute statement
    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }

    // Get result
    $result = $stmt->get_result();
    if (!$result) {
        die("Getting result set failed: " . $stmt->error);
    }

    // Close statement
    $stmt->close();
} else {
    die("Invalid patient ID.");
}
?>

    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Medical History</title>
        <style>
            table {
                width: 100%;
                border-collapse: collapse;
            }
            th, td {
                border: 1px solid #dddddd;
                text-align: left;
                padding: 8px;
            }
            th {
                background-color: #f2f2f2;
            }
        </style>
    </head>
    <body>

    <h2>Medical History for Patient ID: <?php echo htmlspecialchars($patient_id); ?></h2>

    <table>
        <tr>
            <th>Record ID</th>
            <th>Patient ID</th>
            <th>Appointment ID</th>
            <th>Symptoms</th>
            <th>Tests</th>
            <th>Advice</th>
            <th>Medicine Period</th>
            <th>Appointment Date</th>
            <th>Doctor Name</th>
            <th>Speciality</th>
            <th>Prescription Tests</th>
            <th>Prescription Advice</th>
            <th>Medicine Name</th>
            <th>Medicine Period</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row["M_H_ID"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["p_ID"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["Ap_ID"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["Symptoms"] ?? '') . "</td>";
                echo "<td>" . htmlspecialchars($row["tests"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["advice"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["medicine_period"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["appointment_date"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["d_name"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["speciality"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["tests"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["advice"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["medicine_name"] ?? '') . "</td>";
                echo "<td>" . htmlspecialchars($row["medicine_period"]) . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='15'>No records found</td></tr>";
        }
        ?>
    </table>
<div class="back-button-container">
            <a href="health_tracker.php" class="btn">Back</a>
        </div>

    </body>
    </html>

<?php
// Close connection
$conn->close();
?>
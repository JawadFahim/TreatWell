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

// Check if appointment ID is set in GET parameters
if(isset($_GET['id'])){
    $appointment_id = $_GET['id'];

    // Prepare an SQL DELETE statement
    $sql = "DELETE FROM appointments WHERE AP_ID = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $appointment_id);

        // Execute the SQL statement
        if ($stmt->execute()) {
            echo "Appointment rejected successfully.";
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close statement
        $stmt->close();
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    echo "Appointment ID not found in GET parameters.";
}

// Close connection
$conn->close();
?>
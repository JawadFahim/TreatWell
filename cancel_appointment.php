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
            echo "Appointment cancelled successfully.";
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
<style>
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
    img {
        display: block;
        margin: 0 auto;
        border: 1px solid #ddd;
    }

</style>

<a href="patientHomepage.php" class="btn">Go to Homepage</a>
<a href="appointment_list.php" class="btn">View Appointment List</a>
<img src="image/header.jpg" alt="Header Image">

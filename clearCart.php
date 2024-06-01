<?php
session_start();
include 'connection.php';
global $conn;
// Get the user id from the session
$userId = $_SESSION['user_id'];

// Prepare the SQL statement
$stmt = $conn->prepare("DELETE FROM cart WHERE patient_id = ? AND buying_status = ?");
$stmt->bind_param("is", $userId, $status);

// Define the status
$status = 'unbought';

// Execute the statement
$stmt->execute();

// Redirect back to the medicineCart page
header("Location: medicineCart.php");
exit;
?>
<?php
session_start();
include 'connection.php';
global $conn;
// Get the user id from the session
$userId = $_SESSION['user_id'];

// Prepare the SQL statement
$stmt = $conn->prepare("UPDATE cart SET buying_status = ? WHERE patient_id = ? AND buying_status = ?");
$newStatus = 'ordered';
$oldStatus = 'unbought';
$stmt->bind_param("sis", $newStatus, $userId, $oldStatus);

// Execute the statement
$stmt->execute();

// Set the success message
$_SESSION['message'] = 'All the medicine has been ordered to your address';

// Redirect back to the medicineCart page
header("Location: medicineCart.php");
exit;
?>
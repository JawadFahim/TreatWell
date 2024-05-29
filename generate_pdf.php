<?php
// Include autoloader
require_once 'vendor/autoload.php';

// Include your existing connection file
include 'connection.php';

global $conn;

// Get the patient_id from the URL
$patient_id = $_GET['patient_id'];

// Fetch data from database
$stmt = $conn->prepare("SELECT * FROM prescriptions WHERE patient_id = ? ORDER BY id DESC");
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$result = $stmt->get_result(); // get the mysqli result
$prescriptions = $result->fetch_all(MYSQLI_ASSOC); // fetch data

// Create HTML content
$htmlContent = '
<html>
<head>
    <style>
        /* Add your CSS here */
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 5px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>Prescription</h1>
    <table>
        <tr>
            <th>Symptoms</th>
            <th>Tests</th>
            <th>Advice</th>
            <th>Medicine Name</th>
            <th>Medicine Period</th>
        </tr>';

foreach ($prescriptions as $prescription) {
    $htmlContent .= '
        <tr>
            <td>' . $prescription['symptoms'] . '</td>
            <td>' . $prescription['tests'] . '</td>
            <td>' . $prescription['advice'] . '</td>
            <td>' . $prescription['medicine_name'] . '</td>
            <td>' . $prescription['medicine_period'] . '</td>
        </tr>';
}

$htmlContent .= '
    </table>
</body>
</html>';

// Instantiate Dompdf with default configuration
$dompdf = new Dompdf\Dompdf();

// Load HTML content
$dompdf->loadHtml($htmlContent);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'portrait');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF (1 = download and 0 = preview)
$dompdf->stream("prescription.pdf", array("Attachment" => 1));
?>
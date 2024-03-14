<?php
$host = 'localhost';
$db   = 'TreatWell';
$user = 'root';
$pass = 'root';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$opt = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
$pdo = new PDO($dsn, $user, $pass, $opt);

// Check if the department key exists in the GET parameters
if (isset($_GET['department'])) {
    $selectedSpecialty = $_GET['department'];

    // Use a prepared statement to select only the doctors with the selected specialty
    $sql = "SELECT * FROM doctor WHERE specialty = :specialty";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['specialty' => $selectedSpecialty]);
    $doctorDetails = $stmt->fetchAll();
} elseif (isset($_GET['doctorName'])) {
    $selectedDoctorName = $_GET['doctorName'];

    // Use a prepared statement to select only the doctor with the selected name
    $sql = "SELECT * FROM doctor WHERE LOWER(name) LIKE LOWER(:name)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['name' => '%' . $selectedDoctorName . '%']);
    $doctorDetails = $stmt->fetchAll();
}else {
    // Handle the case where no specialty or doctor's name was selected
    $doctorDetails = [];
}
?>

<!DOCTYPE html>

<html>
<head>
    <title>Doctor List</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-family: Arial, sans-serif;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            color: #333;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

        h1 {
            font-family: Arial, sans-serif;
            color: #333;
            text-align: center;
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
<h1>Doctor List</h1>
<table>
    <tr>
        <th>Doctor ID</th>
        <th>Name</th>
        <th>Specialty</th>
    </tr>
    <?php foreach ($doctorDetails as $doctor): ?>
        <tr>
            <td><?= htmlspecialchars($doctor['doctor_id']) ?></td>
            <td><?= htmlspecialchars($doctor['name']) ?></td>
            <td><?= htmlspecialchars($doctor['specialty']) ?></td>
        </tr>
    <?php endforeach; ?>
</table>
</body>
</html>
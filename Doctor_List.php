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

// Check if the common_speciality key exists in the GET parameters
if (isset($_GET['common_speciality'])) {
    $selectedCommonSpeciality = $_GET['common_speciality'];

    // Use a prepared statement to select only the doctors with the selected common speciality
    $sql = "SELECT * FROM doctorinfo WHERE common_speciality = :common_speciality";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['common_speciality' => $selectedCommonSpeciality]);
    $doctorDetails = $stmt->fetchAll();
} elseif (isset($_GET['doctorName'])) {
    $selectedDoctorName = $_GET['doctorName'];

    // Use a prepared statement to select only the doctor with the selected name
    $sql = "SELECT * FROM doctorinfo WHERE LOWER(name) LIKE LOWER(:name)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['name' => '%' . $selectedDoctorName . '%']);
    $doctorDetails = $stmt->fetchAll();
}else {
    // Handle the case where no common speciality or doctor's name was selected
    $doctorDetails = [];
}
?>
<!DOCTYPE html>

<html>
<head>
    <title>Find A Doctor</title>
    <style>
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 15px;
            margin-top: 20px;
            font-family: Arial, sans-serif;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 15px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            color: #333;
            text-align: center;
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
            padding: 15px;
        }
        th {
            background-color: #f2f2f2;
            text-align: center;
        }
        td {
            text-align: center;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            background-color: #fb923c;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #c75f22;
        }
        tr:hover {
            background-color: #12ac8e;
            color: #fff;
        }

    </style>
</head>
<body>
<h1>List of Doctors</h1>
<table>
    <tr>
        <th>Doctor ID</th>
        <th>Name</th>
        <th>Specialty</th>
        <th>Action</th>
    </tr>
    <?php foreach ($doctorDetails as $doctor): ?>
        <tr>
            <td><?= htmlspecialchars($doctor['id']) ?></td> <!-- Updated column name here -->
            <td><?= htmlspecialchars($doctor['name']) ?></td>
            <td><?= htmlspecialchars($doctor['speciality']) ?></td> <!-- Updated column name here -->
            <td>
                <!-- "Book Appointment" button -->
                <a href="Appointment_Page.php?id=<?= urlencode($doctor['id']) ?>&doctor_name=<?= urlencode($doctor['name']) ?>&speciality=<?= urlencode($doctor['speciality']) ?>" class="btn">Book Appointment</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<p>For additional searching options, go to <a href="DB_of_Doctors.php">this link</a>.</p>
</body>
</html>

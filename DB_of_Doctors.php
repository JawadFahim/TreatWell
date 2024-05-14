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

$selectedSpecialty = isset($_POST['specialty']) ? $_POST['specialty'] : '';
$searchName = isset($_POST['searchName']) ? $_POST['searchName'] : '';

$sql = "SELECT * FROM doctor WHERE 1";
if ($selectedSpecialty !== '') {
    $sql .= " AND specialty = :specialty";
}
if ($searchName !== '') {
    $sql .= " AND name LIKE :name";
}
$stmt = $pdo->prepare($sql);
if ($selectedSpecialty !== '') {
    $stmt->bindParam(':specialty', $selectedSpecialty);
}
if ($searchName !== '') {
    $searchName = "%$searchName%";
    $stmt->bindParam(':name', $searchName);
}
$stmt->execute();
$doctorDetails = $stmt->fetchAll();

$sql = "SELECT DISTINCT specialty FROM doctor";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$specialties = $stmt->fetchAll();
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
        .search-container {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        #search-bar {
            width: 60%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        #search-button {
            margin-left: 10px;
            padding: 10px 20px;
            border: none;
            background-color: #fb923c;
            color: #fff;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        #search-button:hover {
            background-color: #c75f22;
        }

        .dropdown-container {
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
        }

        .dropdown-container select , #calendar-date{
            width: 60%; /* Adjust the width as needed */
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-right: 10px; /* Add right margin to each select element */
        }
        #search-bar, .dropdown-container select, #calendar-date {
            border: 3px solid #ddd; /* Adjust the pixel value as needed */
        }

    </style>
</head>
<body>
<h1>Find A Doctor</h1>
<div>
    <div class="search-container">
        <form method="post">
            <input type="text" id="search-bar" name="searchName" placeholder="Enter doctor's name here">
            <button class="btn form__btn">Search</button>
        </form>
    </div>

    <div class="dropdown-container">
        <form method="post">
            <select id="speciality" name="specialty" onchange="this.form.submit()">
                <option value="">Select Speciality</option>
                <?php foreach ($specialties as $specialty): ?>
                    <option value="<?= htmlspecialchars($specialty['specialty']) ?>" <?= $selectedSpecialty === $specialty['specialty'] ? 'selected' : '' ?>><?= htmlspecialchars($specialty['specialty']) ?></option>
                <?php endforeach; ?>
            </select>
            <select id="consultation-type">
                <option value="">Select Consultation Type</option>
            </select>
            <input type="date" id="calendar-date">
        </form>
    </div>
</div>

<table>
    <tr>
        <th>Doctor ID</th>
        <th>Name</th>
        <th>Specialty</th>
        <th>Action</th>
    </tr>
    <?php foreach ($doctorDetails as $doctor): ?>
        <tr>
            <td><?= htmlspecialchars($doctor['doctor_id']) ?></td>
            <td><?= htmlspecialchars($doctor['name']) ?></td>
            <td><?= htmlspecialchars($doctor['specialty']) ?></td>
            <td>
                <a href="Appointment_Page.php?doctor_id=<?= urlencode($doctor['doctor_id']) ?>" class="btn">Book Appointment</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
</body>
</html>
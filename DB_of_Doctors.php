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

$selectedCommonSpeciality = isset($_POST['common_speciality']) ? $_POST['common_speciality'] : '';
$searchName = isset($_POST['searchName']) ? $_POST['searchName'] : '';

$sort = isset($_GET['sort']) ? $_GET['sort'] : 'id';
$direction = isset($_GET['direction']) ? $_GET['direction'] : 'ASC';

$sql = "SELECT * FROM doctorinfo WHERE 1";
if ($selectedCommonSpeciality !== '') {
    $sql .= " AND common_speciality = :common_speciality";
}
if ($searchName !== '') {
    $sql .= " AND name LIKE :name";
}
$sql .= " ORDER BY $sort $direction";
$stmt = $pdo->prepare($sql);
if ($selectedCommonSpeciality !== '') {
    $stmt->bindParam(':common_speciality', $selectedCommonSpeciality);
}
if ($searchName !== '') {
    $searchName = "%$searchName%";
    $stmt->bindParam(':name', $searchName);
}
$stmt->execute();
$doctorDetails = $stmt->fetchAll();

$sql = "SELECT DISTINCT common_speciality FROM doctorinfo ORDER BY common_speciality ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$commonSpecialties = $stmt->fetchAll();

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
        .form-container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .search-container, .dropdown-container {
            flex: 1 0 auto;
            margin: 10px;
        }
        .search-bar-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .back-button-container {
            text-align: right; /* Align the button to the right */
            margin-bottom: 20px; /* Add some space below the button */
        }

        .back-button-container .btn {
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

        .back-button-container .btn:hover {
            background-color: #c75f22;
        }

    </style>
</head>
<body>
<h1>Find A Doctor</h1>
<div class="form-container">
    <div class="search-container">
        <form method="post">
            <div class="search-bar-container">
                <input type="text" id="search-bar" name="searchName" placeholder="Enter doctor's name">
                <button class="btn form__btn">Search</button>
            </div>
        </form>
    </div>

    <div class="dropdown-container">
        <form method="post">
            <select id="common_speciality" name="common_speciality" onchange="this.form.submit()">
                <option value="">Select Speciality</option>
                <?php foreach ($commonSpecialties as $commonSpeciality): ?>
                    <option value="<?= htmlspecialchars($commonSpeciality['common_speciality']) ?>" <?= $selectedCommonSpeciality === $commonSpeciality['common_speciality'] ? 'selected' : '' ?>><?= htmlspecialchars($commonSpeciality['common_speciality']) ?></option>
                <?php endforeach; ?>
            </select>
           <!-- <select id="consultation-type">
                <option value="">Select Consultation Type</option>
            </select>-->
        </form>
    </div>
</div>

<div class="back-button-container">
    <a href="patientHomepage.php" class="btn">Back to Homepage</a>
</div>
<table>
    <tr>
        <th>
            <a href="?sort=id&direction=ASC">ID &#9650;</a> |
            <a href="?sort=id&direction=DESC">ID &#9660;</a>
        </th>
        <th>
            <a href="?sort=name&direction=ASC">Name &#9650;</a> |
            <a href="?sort=name&direction=DESC">Name &#9660;</a>
        </th>
        <th>
            <a href="?sort=speciality&direction=ASC">Speciality &#9650;</a> |
            <a href="?sort=speciality&direction=DESC">Speciality &#9660;</a>
        </th>
        <th> </th>
    </tr>
    <?php foreach ($doctorDetails as $doctor): ?>
        <tr>
            <td><?= htmlspecialchars($doctor['id']) ?></td>
            <td><?= htmlspecialchars($doctor['name']) ?></td>
            <td><?= htmlspecialchars($doctor['speciality']) ?></td>
            <td>
                <a href="Appointment_Page.php?id=<?= urlencode($doctor['id']) ?>" class="btn">Book Appointment</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

</body>
</html>

<?php
$host = 'localhost';
$db   = 'treatwell';
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

// Get the generic name from the URL
$generic_name = isset($_GET['generic_name']) ? urldecode($_GET['generic_name']) : '';

// Prepare the SQL query
$sql = "SELECT brand_name, dosage_form, manufacturer FROM medicine_brand WHERE generic = :generic_name";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':generic_name', $generic_name, PDO::PARAM_STR);
$stmt->execute();

// Fetch all the records
$brands = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="css/medicineCat.css">
    <title>Alternate Medicine Brands</title>
</head>
<body>
<div class="topnav">
    <a href="patientHomepage.php">Home</a>
    <a href="medicineCat.php">Medicine Catalog</a>

    <div class="search-panel">
        <form action="" method="get" id="search_form">
            <select name="search_type" id="search_type">
                <option value="brand_name" selected>Brand Name</option>
                <option value="generic">Generic Name</option>
            </select>
            <input type="text" name="search_query" id="search_query" placeholder="Search...">
            <input type="hidden" name="selected_option" id="selected_option" value="brand_name">
            <script>
                document.getElementById('search_type').addEventListener('change', function() {
                    document.getElementById('selected_option').value = this.value;
                });
            </script>
            <button type="submit">Search</button>
        </form>
    </div>
</div>

<div class="container">
    <?php foreach ($brands as $brand): ?>
        <div class="box">
            <a href="brandPage.php?brand_name=<?php echo urlencode($brand['brand_name']); ?>" style="text-decoration: none; color: inherit;">
                <h2><?php echo htmlspecialchars($brand['brand_name']); ?></h2>
                <p><?php echo htmlspecialchars($brand['dosage_form']); ?></p>
                <p><?php echo htmlspecialchars($brand['manufacturer']); ?></p>
            </a>
        </div>
    <?php endforeach; ?>
</div>

</body>
</html>
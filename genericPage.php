<?php
$host = 'localhost';
$db   = 'treatwell';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$opt = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
$pdo = new PDO($dsn, $user, $pass, $opt);

// Get the brand name from the URL
$generic_name = isset($_GET['generic_name']) ? urldecode($_GET['generic_name']) : '';
// Prepare the SQL query
// Prepare the SQL query
$sql = "SELECT * FROM generic WHERE generic_name = :generic_name";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':generic_name', $generic_name, PDO::PARAM_STR);
$stmt->execute();

// Fetch the medicine details
$medicine = $stmt->fetch();


if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['selected_option'])) {
    $selected_option = $_GET['selected_option'];
    if ($selected_option == 'brand_name') {
        header('Location: brandNameSearch.php?search_query=' . urlencode($_GET['search_query']));
        exit;
    } elseif ($selected_option == 'generic') {
        header('Location: genericSearch.php?search_query=' . urlencode($_GET['search_query']));
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="css/brandPage.css">
    <title><?php echo htmlspecialchars($medicine['generic_name']); ?></title>
</head>
<body>
<div class="topnav">
    <a href="index.php">Home</a>
    <a href="medicineCat.php">Medicine Catalog</a>

    <div class="search-panel">
        <form action="" method="get" id="search_form">
            <select name="search_type" id="search_type">
                <option value="brand_name" selected>Brand Name</option>
                <option value="generic">Generic Name</option>
            </select>
            <input type="text" name="search_query" id="search_query" placeholder="Search...">
            <input type="hidden" name="selected_option" id="selected_option" value="brand_name">
            <button type="submit">Search</button>
        </form>
    </div>
</div>
<div class="container">
    <h1><?php echo htmlspecialchars($medicine['generic_name']); ?></h1>



    </div>
    <div class="info-section">
        <h2 class="info-title">Indication</h2>
        <p><?php echo $medicine['indication'] !== null ? htmlspecialchars($medicine['indication']) : 'There is no data for that'; ?></p></p>
    </div>
    <div class="info-section">
        <h2 class="info-title">Indication Description</h2>
        <p><?php echo $medicine['indication_description'] !== null ? htmlspecialchars($medicine['indication_description']) : 'There is no data for that'; ?></p>
    </div>
    <div class="info-section">
        <h2 class="info-title">Therapeutic Class Description</h2>
        <p><?php echo $medicine['therapeutic_class_description'] !== null ? htmlspecialchars($medicine['therapeutic_class_description']) : 'There is no data for that'; ?></p>
    </div>
    <div class="info-section">
        <h2 class="info-title">Pharmacology Description</h2>
        <p><?php echo $medicine['pharmacology_description'] !== null ? htmlspecialchars($medicine['pharmacology_description']) : 'There is no data for that'; ?></p>
    </div>
    <div class="info-section">
        <h2 class="info-title">Dosage Description</h2>
        <p><?php echo $medicine['dosage_description'] !== null ? htmlspecialchars($medicine['dosage_description']) : 'There is no data for that'; ?></p>
    </div>
    <div class="info-section">
        <h2 class="info-title">Administration Description</h2>
        <p><?php echo $medicine['administration_description'] !== null ? htmlspecialchars($medicine['administration_description']) : 'There is no data for that'; ?></p>    </div>
    <div class="info-section">
        <h2 class="info-title">Interaction Description</h2>
        <p><?php echo $medicine['interaction_description'] !== null ? htmlspecialchars($medicine['interaction_description']) : 'There is no data for that'; ?></p>    </div>
    <div class="info-section">
        <h2 class="info-title">Contraindications Description</h2>
        <p><?php echo $medicine['contraindications_description'] !== null ? htmlspecialchars($medicine['contraindications_description']) : 'There is no data for that'; ?></p>    </div>
    <div class="info-section">
        <h2 class="info-title">Side Effects Description</h2>
        <p><?php echo $medicine['side_effects_description'] !== null ? htmlspecialchars($medicine['side_effects_description']) : 'There is no data for that'; ?></p>    </div>
    <div class="info-section">
        <h2 class="info-title">Pregnancy Lactation Description</h2>
        <p><?php echo $medicine['pregnancy_lactation_description'] !== null ? htmlspecialchars($medicine['pregnancy_lactation_description']) : 'There is no data for that'; ?></p>    </div>
    <div class="info-section">
        <h2 class="info-title">Precautions Description</h2>
        <p><?php echo $medicine['precautions_description'] !== null ? htmlspecialchars($medicine['precautions_description']) : 'There is no data for that'; ?></p>    </div>
    <div class="info-section">
        <h2 class="info-title">Pediatric Usage Description</h2>
        <p><?php echo $medicine['pediatric_usage_description'] !== null ? htmlspecialchars($medicine['pediatric_usage_description']) : 'There is no data for that'; ?></p>
    </div>
    <div class="info-section">
        <h2 class="info-title">Overdose Effects Description</h2>
        <p><?php echo $medicine['overdose_effects_description'] !== null ? htmlspecialchars($medicine['overdose_effects_description']) : 'There is no data for that'; ?></p>    </div>
    <div class="info-section">
        <h2 class="info-title">Duration of Treatment Description</h2>
        <p><?php echo $medicine['duration_of_treatment_description'] !== null ? htmlspecialchars($medicine['duration_of_treatment_description']) : 'There is no data for that'; ?></p>    </div>
    <div class="info-section">
        <h2 class="info-title">Reconstitution Description</h2>
        <p><?php echo $medicine['reconstitution_description'] !== null ? htmlspecialchars($medicine['reconstitution_description']) : 'There is no data for that'; ?></p>    </div>
    <div class="info-section">
        <h2 class="info-title">Storage Conditions Description</h2>
        <p><?php echo $medicine['storage_conditions_description'] !== null ? htmlspecialchars($medicine['storage_conditions_description']) : 'There is no data for that'; ?></p>    </div>

</div>
</body>
</html>
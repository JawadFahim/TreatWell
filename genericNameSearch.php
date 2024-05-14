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

// Get the search query from the medicineCat.php page
$search_query = isset($_GET['search_query']) ? $_GET['search_query'] : '';

// Prepare the SQL query
$sql = "SELECT * FROM generic WHERE generic_name LIKE :search_query";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':search_query', '%' . $search_query . '%', PDO::PARAM_STR);
$stmt->execute();


// Fetch the results
$results = $stmt->fetchAll();
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
    <link rel="stylesheet" href="css/genericNameSearch.css">
    <title>Generic Name Search</title>
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
    <?php foreach ($results as $result): ?>
        <a style="text-decoration: none; color: black;" href="genericPage.php?generic_name=<?php echo urlencode($result['generic_name']); ?>">
            <div class="box">
                <h2><?php echo htmlspecialchars($result['generic_name']); ?></h2>
                <p><?php echo substr(htmlspecialchars($result['indication_description']), 0, 200); ?>...</p>
            </div>
        </a>
    <?php endforeach; ?>
</div>

</body>
</html>
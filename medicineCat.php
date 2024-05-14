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

// Get the current page number
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;

// Number of records to show on each page
$records_per_page = 100;

// Calculate the offset for the query
$offset = ($page-1) * $records_per_page;

// Prepare the SQL query
$sql = "SELECT brand_name, dosage_form, manufacturer FROM medicine_brand LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':limit', $records_per_page, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();

// Fetch all the records
$medicines = $stmt->fetchAll();

// Get the number of total records
$stmt = $pdo->query("SELECT COUNT(*) FROM medicine_brand");
$num_records = $stmt->fetchColumn();

// Calculate the number of pages required
$num_pages = ceil($num_records / $records_per_page);
$search_type = isset($_GET['search_type']) ? $_GET['search_type'] : 'brand_name';

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['selected_option'])) {
    $selected_option = $_GET['selected_option'];
    if ($selected_option == 'brand_name') {
        header('Location: brandNameSearch.php?search_query=' . urlencode($_GET['search_query']));
        exit;
    } elseif ($selected_option == 'generic') {
        header('Location: genericNameSearch.php?search_query=' . urlencode($_GET['search_query']));
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="css/medicineCat.css">
    <title></title>
</head>
<body>
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

<div class="container">
    <?php foreach ($medicines as $medicine): ?>
            <div class="box">
                <a href="brandPage.php?brand_name=<?php echo urlencode($medicine['brand_name']); ?>" style="text-decoration: none; color: inherit;">

                <h2><?php echo htmlspecialchars($medicine['brand_name']); ?></h2>
                <p><?php echo htmlspecialchars($medicine['dosage_form']); ?></p>
                <p><?php echo htmlspecialchars($medicine['manufacturer']); ?></p>
            </div>
        </a>
    <?php endforeach; ?>
</div>

<div class="pagination">
    <?php
    $start = max(1, $page);
    $end = min($start + 9, $num_pages);
    for ($p = $start; $p <= $end; $p++): ?>
        <a href="?page=<?php echo $p; ?>" class="<?php echo $p == $page ? 'active' : ''; ?>"><?php echo $p; ?></a>
    <?php endfor; ?>
    <?php if($num_pages > $end): ?>
        <span class="dots">...</span>
        <a href="?page=<?php echo $num_pages; ?>"><?php echo $num_pages; ?></a>
    <?php endif; ?>
    <?php if($page < $num_pages): ?>
        <a href="?page=<?php echo $page + 1; ?>" class="next">Next</a>
    <?php endif; ?>
</div>

</body>
</html>
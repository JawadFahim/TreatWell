<?php
session_start();
$username = $_SESSION['username'];
$user_id= $_SESSION['user_id'];
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

// Get the brand name from the URL
$brand_name = isset($_GET['brand_name']) ? urldecode($_GET['brand_name']) : '';

// Prepare the SQL query
$sql = "SELECT mb.*, d.indication, d.indication_description, d.therapeutic_class_description, d.pharmacology_description, d.dosage_description, d.administration_description, d.interaction_description, d.contraindications_description, d.side_effects_description, d.pregnancy_lactation_description, d.precautions_description, d.pediatric_usage_description, d.overdose_effects_description, d.duration_of_treatment_description, d.reconstitution_description, d.storage_conditions_description, d.descriptions_count FROM medicine_brand mb LEFT JOIN generic d ON mb.generic = d.generic_name WHERE mb.brand_name = :brand_name";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':brand_name', $brand_name, PDO::PARAM_STR);
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
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $patient_id = $_POST['patient_id'];
    $medicine_name = $_POST['medicine_name'];
    $manufacturer = $_POST['manufacturer'];
    $unit_price = $_POST['unit_price'];
    $quantity = $_POST['quantity'];

    // Check if the medicine already exists in the cart for the specific user
    $sql = "SELECT * FROM cart WHERE patient_id = ? AND medicine_name = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$patient_id, $medicine_name]);
    $existing_medicine = $stmt->fetch();

    if ($existing_medicine) {
        // The medicine already exists in the cart for the specific user
        $_SESSION['error_message'] = 'The medicine already exists in the cart.';
        header('Location: brandPage.php');
        exit;
    }

    // Calculate the total price
    $total_price = $unit_price * $quantity;

    $sql = "INSERT INTO cart (patient_id, medicine_name, manufacturer, unit_price, total_price, total_unit, buying_status) VALUES (?, ?, ?, ?, ?, ?, 'unbought')";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$patient_id, $medicine_name, $manufacturer, $unit_price, $total_price, $quantity]);
    header('Location: medicineCart.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="css/brandPage.css">
    <title><?php echo htmlspecialchars($medicine['brand_name']); ?></title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="topnav">
    <a href="patientHomepage.php">Home</a>
    <a href="medicineCat.php">Medicine Catalog</a>
    <a href="medicineCart.php">Go to Cart</a>

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

    <h1><?php echo htmlspecialchars($medicine['brand_name']); ?></h1>
    <a style="text-decoration: none; color: indianred;" href="genericPage.php?generic_name=<?php echo urlencode($medicine['generic']); ?>">
        <?php echo htmlspecialchars($medicine['generic']); ?>
    </a>
    <a style="text-decoration: none; color: indianred; margin-left: 10px;" href="alternateBrand.php?generic_name=<?php echo urlencode($medicine['generic']); ?>">
        View all brands with this generic
    </a>

    <br><a  style="text-decoration: none; color: indianred; " href="manufaCat.php?manufacturer=<?php echo urlencode($medicine['manufacturer']); ?>"><?php echo htmlspecialchars($medicine['manufacturer']); ?></a>
    <br>Strength: <?php echo $medicine['strength'] !== null ? htmlspecialchars($medicine['strength']) : 'There is no data for that'; ?>

    <br><small>Unit Price:
    <?php
        if (isset($medicine['package_container'])) {
            echo "Package Container: " . $medicine['package_container'];
            if (strpos($medicine['package_container'], 'Unit Price') !== false) {
                echo "" . str_replace('Unit Price', '', $medicine['package_container']);
            }
        }
        ?>
    </small>
    <div class="product-card">
        <h1><?php echo htmlspecialchars($medicine['brand_name']); ?> <span class="dosage"><?php echo htmlspecialchars($medicine['strength']); ?></span></h1>
        <div><a class="ingredient"><?php echo htmlspecialchars($medicine['generic']); ?></a></div>
        <div class="manufacturer"><?php echo htmlspecialchars($medicine['manufacturer']); ?></div>
        <div class="price"><?php echo htmlspecialchars($medicine['price']); ?> <span class="per-piece"><?php echo $medicine['unit'] == 1 ? '/Piece' : htmlspecialchars($medicine['unit']); ?></span></div>

        <form id="dataForm" action="brandPage.php" method="post">
            <input type="hidden" name="patient_id" value="<?php echo $user_id; ?>">
            <input type="hidden" name="medicine_name" value="<?php echo htmlspecialchars($medicine['brand_name']); ?>">
            <input type="hidden" name="manufacturer" value="<?php echo htmlspecialchars($medicine['manufacturer']); ?>">
            <input type="hidden" name="unit_price" value="<?php echo htmlspecialchars($medicine['price']); ?>">
            <input type="number" name="quantity" id="quantity" value="1" min="1">
            <button type="submit" class="added-to-cart">Add to cart</button>
        </form>
        <script>
            <?php if (isset($_SESSION['error_message'])): ?>
            var errorMessage = "<?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>";
            alert(errorMessage);
            <?php endif; ?>
        </script>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        function changeQuantity(amount) {
            var quantityInput = document.getElementById('quantity');
            var currentQuantity = parseInt(quantityInput.value);
            if (currentQuantity + amount >= 1) {
                quantityInput.value = currentQuantity + amount;
            }
            updateTotalPrice();
        }

        function updateTotalPrice() {
            var quantityInput = document.getElementById('quantity');
            var currentQuantity = parseInt(quantityInput.value);
            var pricePerUnit = <?php echo htmlspecialchars($medicine['price']); ?>;
            var totalPrice = currentQuantity * pricePerUnit;
            document.getElementById('total-price').textContent = totalPrice.toFixed(2);
        }

        // Call updateTotalPrice on page load to initialize the total price


        });
    </script>
    <div class="info-section">
        <h2 class="info-title">Dosage Form</h2>
        <p><?php echo htmlspecialchars($medicine['dosage_form']); ?></p>
    </div>
    <div class="info-section">
        <h2 class="info-title">Manufacturer</h2>
        <p><?php echo htmlspecialchars($medicine['manufacturer']); ?></p>


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
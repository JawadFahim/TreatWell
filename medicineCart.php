<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="css/medicineCart.css">

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

<div class="cart-container">
    <div class="cart-header">
        <?php
        session_start();
        include 'connection.php';
        global $conn;

        // Get the user id from the session
        $userId = $_SESSION['user_id'];
        if (isset($_SESSION['message'])) {
            echo "<p>" . htmlspecialchars($_SESSION['message']) . "</p>";
            unset($_SESSION['message']);
        }

        // Prepare the SQL statement
        $stmt = $conn->prepare("SELECT * FROM cart WHERE patient_id = ?");
        $stmt->bind_param("i", $userId);

        // Execute the statement
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        // Count the number of items in the cart
        $numItems = $result->num_rows;
        $stmtTotal = $conn->prepare("SELECT SUM(total_price) as total_price FROM cart WHERE patient_id = ?");
        $stmtTotal->bind_param("i", $userId);

        // Execute the statement
        $stmtTotal->execute();

        // Get the result
        $resultTotal = $stmtTotal->get_result();

        // Fetch the total price
        $totalPrice1 = $resultTotal->fetch_assoc()['total_price'];

        echo "<h2>" . $numItems . " Items</h2>";
        ?>
        <form method="POST" action="clearCart.php">
            <button type="submit" class="clear-all">Clear All</button>
        </form>
    </div>
    <?php
    // Loop through the result and display each item
    while ($row = $result->fetch_assoc()) {
        // Here you can access each column of the row by its name
        $medicineName = $row['medicine_name'];
        $manufacturer = $row['manufacturer'];
        $unitPrice = $row['unit_price'];
        $totalPrice = $row['total_price'];
        $totalUnit = $row['total_unit'];
        $buyingStatus = $row['buying_status'];

        // Now you can use these variables to populate the cart item in your HTML
        echo "<div class='cart-item'>";
        echo "<div class='cart-item-info'>";
        echo "<h3>" . htmlspecialchars($medicineName) . "</h3>";
        echo "<p>Manufacturer: " . htmlspecialchars($manufacturer) . "</p>";
        echo "<p>Unit Price: " . htmlspecialchars($unitPrice) . "</p>";
        echo "<p>Total Unit: " . htmlspecialchars($totalUnit) . "</p>";
        echo "<p>Status: " . htmlspecialchars($buyingStatus) . "</p>";
        echo "</div>";
        echo "<div class='cart-item-controls'>";
       echo "</div>";
        echo "<div class='cart-item-price'>";
        echo "<p>Price: " . htmlspecialchars($totalPrice) . "</p>";

        echo "</div>";
        echo "</div>";
    }
    ?>
    <div class="subtotal">

        <h3>৳ Total Price</h3>
        <h3>৳ <?php echo htmlspecialchars($totalPrice1); ?></h3>
    </div>
    <form method="POST" action="orderMedicine.php">
        <button type="submit" class="checkout-btn">Proceed To Checkout</button>
    </form>
</div>



</body>
</html>
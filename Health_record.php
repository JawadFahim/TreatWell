<?php
session_start(); // Ensure session is started
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "treatwell";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $height = $_POST['height'];
    $weight = $_POST['weight'];
    $height_unit = $_POST['height_unit'];
    $weight_unit = $_POST['weight_unit'];
    $systolic = $_POST['systolic'];
    $diastolic = $_POST['diastolic'];
    $patient_id = $_SESSION["user_id"]; // Fetch patient ID from session

    if ($height_unit == "inches") {
        $height = $height * 2.54;
    }

    if ($weight_unit == "pounds") {
        $weight = $weight * 0.453592;
    }

    if (is_numeric($height) && is_numeric($weight) && is_numeric($systolic) && is_numeric($diastolic) &&
        $height > 0 && $weight > 0 && $systolic > 0 && $diastolic > 0) {

        $height_in_meters = $height / 100;
        $bmi = $weight / ($height_in_meters * $height_in_meters);

        $stmt = $conn->prepare("INSERT INTO health_records(patient_id, height, weight, bmi, systolic, diastolic) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iddddd", $patient_id, $height, $weight, $bmi, $systolic, $diastolic);

        if ($stmt->execute()) {
            $message = "Record successfully inserted. BMI: " . number_format($bmi, 2);
        } else {
            $message = "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $message = "Please enter valid positive numbers for height, weight, systolic, and diastolic.";
    }
} else {
    // Fetch BMI, systolic, diastolic, and calculation_time data for the current patient from the database
    $patient_id = $_SESSION["user_id"];
    $sql = "SELECT bmi, systolic, diastolic, calculation_time FROM health_records WHERE patient_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $patient_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Initialize empty arrays to store data
    $bmi_data = array();
    $systolic_data = array();
    $diastolic_data = array();
    $calculation_time_data = array();

    // Fetch data and store it in the arrays
    while ($row = $result->fetch_assoc()) {
        $bmi_data[] = $row['bmi'];
        $systolic_data[] = $row['systolic'];
        $diastolic_data[] = $row['diastolic'];
        $calculation_time_data[] = $row['calculation_time'];
    }

    // Convert the data arrays to JSON
    $json_bmi_data = json_encode($bmi_data);
    $json_systolic_data = json_encode($systolic_data);
    $json_diastolic_data = json_encode($diastolic_data);
    $json_calculation_time_data = json_encode($calculation_time_data);

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Health Record</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: rgba(240, 128, 128, 0.9); /* Faded coral color */
            color: white;
            display: flex;
            flex-direction: column; /* Change to column layout */
            align-items: center; /* Center items horizontally */
            height: 100vh;
            margin: 0;
            padding: 20px; /* Optional padding for aesthetics */
        }
        .form-container, .image-container {
            background-color: rgba(0, 0, 0, 0.7);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            width: 600px;
            margin-bottom: 20px; /* Add margin to separate from graph containers */
        }
        .form-container {
            display: flex;
            flex-direction: column;
            align-items: center; /* Center items horizontally */
        }
        .image-container img {
            width: 100%; /* Make the image take up the full width of the container */
            height: 100%; /* Make the image take up the full height of the container */
            object-fit: cover; /* Ensure the image covers the container without distortion */
            border-radius: 10px;
        }
        h1 {
            text-align: center;
        }
        form {
            display: flex;
            flex-direction: column;
            width: 100%;
        }
        label {
            margin-top: 10px;
        }
        input[type="number"],
        input[type="submit"],
        select {
            padding: 10px;
            margin-top: 5px;
            border: none;
            border-radius: 5px;
        }
        input[type="submit"] {
            background-color: #f0a500;
            color: white;
            cursor: pointer;
            margin-top: 20px;
        }
        input[type="submit"]:hover {
            background-color: #d48f00;
        }
        p {
            text-align: center;
            margin-top: 20px;
        }
        .graph-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            width: 1200px;
            height: 500px;
            background-color: rgba(0, 0, 0, 0.7);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            margin-bottom: 20px; /* Add margin to separate from each other */
        }
        .chart-container {
            width: 45%;
            height: 100%;
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
    <div class="form-container">
        <h1>Health Record</h1>
        <form id="bmi-form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <label for="height">Height:</label>
            <input type="number" id="height" name="height" required>
            <select name="height_unit" required>
                <option value="cm">cm</option>
                <option value="inches">inches</option>
            </select>
            <label for="weight">Weight:</label>
            <input type="number" id="weight" name="weight" required>
            <select name="weight_unit" required>
                <option value="kg">kg</option>
                <option value="pounds">pounds</option>
            </select>
            <label for="systolic">Systolic BP (mm Hg):</label>
            <input type="number" id="systolic" name="systolic" required>
            <label for="diastolic">Diastolic BP (mm Hg):</label>
            <input type="number" id="diastolic" name="diastolic" required>
            <input type="submit" value="Calculate BMI">
            <br>
            <div class="back-button-container">
                <a href="health_tracker.php" class="btn">Back</a>
            </div>
        </form>
        <?php
        if ($message) {
            echo "<p>$message</p>";
        }
        ?>
    </div>

    <div class="image-container">
        <img src="image\bmi_height_weight_chart.webp" alt="BMI Height Weight Chart">
    </div>
 <div class="graph-container">
        <div class="chart-container">
            <button id="load-graph-btn">Load BMI Graph</button>
            <div id="chart_div"></div>
        </div>
		</div>
<div class="graph-container">
        <div class="chart-container">
            <button id="load-bp-graph-btn">Load BP Graph</button>
            <div id="bp_chart_div"></div>
        </div>
    </div>


    <!-- Load the Google Charts library -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        // Function to load the BMI graph
        document.getElementById('load-graph-btn').addEventListener('click', function() {
            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(drawBMIChart);
        });

        // Function to draw the BMI chart
        function drawBMIChart() {
            // Create data arrays from JSON data
            var bmiData = <?php echo $json_bmi_data; ?>;
            var calculationTimeData = <?php echo $json_calculation_time_data; ?>;

            // Create a DataTable object
            var data = new google.visualization.DataTable();
            data.addColumn('datetime', 'Calculation Time');
            data.addColumn('number', 'BMI');

            // Add data to the DataTable
            for (var i = 0; i < bmiData.length; i++) {
                data.addRow([new Date(calculationTimeData[i]), parseFloat(bmiData[i])]);
            }

            // Set chart options
            var options = {
                title: 'BMI Distribution Over Time',
                hAxis: {title: 'Calculation Time'},
                vAxis: {title: 'BMI'},
                legend: 'none',
                width: 600,
                height: 400
            };

            // Instantiate and draw the chart
            var chart = new google.visualization.ScatterChart(document.getElementById('chart_div'));
            chart.draw(data, options);
        }

        // Function to load the BP graph
        document.getElementById('load-bp-graph-btn').addEventListener('click', function() {
            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(drawBPChart);
        });

        // Function to draw the BP chart
        function drawBPChart() {
            // Create data arrays from JSON data
            var systolicData = <?php echo $json_systolic_data; ?>;
            var diastolicData = <?php echo $json_diastolic_data; ?>;
            var calculationTimeData = <?php echo $json_calculation_time_data; ?>;

            // Create a DataTable object
            var data = new google.visualization.DataTable();
            data.addColumn('datetime', 'Calculation Time');
            data.addColumn('number', 'Systolic BP');
            data.addColumn('number', 'Diastolic BP');

            // Add data to the DataTable
            for (var i = 0; i < systolicData.length; i++) {
                data.addRow([new Date(calculationTimeData[i]), parseFloat(systolicData[i]), parseFloat(diastolicData[i])]);
            }

            // Set chart options
            var options = {
                title: 'Blood Pressure Distribution Over Time',
                hAxis: {title: 'Calculation Time'},
                vAxis: {title: 'Blood Pressure (mm Hg)'},
                legend: 'none',
                width: 600,
                height: 400
            };

            // Instantiate and draw the chart
            var chart = new google.visualization.ScatterChart(document.getElementById('bp_chart_div'));
            chart.draw(data, options);
        }
    </script>
</body>
</html>
<?php
include 'connection.php';
global $conn;
session_start();
$username = $_SESSION["username"];
$user_id= $_SESSION["user_id"];
// Get the username from the session
$username = $_SESSION["username"];

// Prepare a SQL statement to fetch the doctor's details
$sql = "SELECT dl.DoctorName, di.address, dl.DoctorPhone
        FROM doctor_login dl
        JOIN doctorinfo di ON dl.regNo = di.regNo
        WHERE dl.DoctorUsername = :username";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':username', $username);
$stmt->execute();

// Fetch the doctor's details
$doctorDetails = $stmt->fetch(PDO::FETCH_ASSOC);

// Now you can use $doctorDetails['DoctorName'], $doctorDetails['address'], and $doctorDetails['DoctorPhone'] in your HTML

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['patient_id'])) {
        $patient_id = $_POST['patient_id'];
        $symptoms = $_POST['symptoms'];
        $tests = $_POST['tests'];
        $advice = $_POST['advice'];

        // Read the medicine data from formData.json
        $json_data = file_get_contents('formData.json');
        $medicines = json_decode($json_data, true);

        // Loop through the medicines array
        foreach ($medicines as $medicine) {$stmt = $conn->prepare("INSERT INTO prescriptions (patient_id, symptoms, tests, advice, medicine_name, medicine_period) VALUES (:patient_id, :symptoms, :tests, :advice, :medicine_name, :medicine_period)");

            $stmt->bindParam(':patient_id', $patient_id);
            $stmt->bindParam(':symptoms', $symptoms);
            $stmt->bindParam(':tests', $tests);
            $stmt->bindParam(':advice', $advice);
            $stmt->bindParam(':medicine_name', $medicine['name']);
            $stmt->bindParam(':medicine_period', $medicine['period']);

            $stmt->execute();
        }

        // Close the statement
        $stmt->close();

        // Clear the formData.json file
        file_put_contents('formData.json', '');
    }
}
if (isset($_GET['patient_id'])) {
    $patient_id = $_GET['patient_id'];
}
?>

<!DOCTYPE html>
<html lang="en" >

<head>
    <meta charset="UTF-8">


    <link rel="apple-touch-icon" type="image/png" href="https://cpwebassets.codepen.io/assets/favicon/apple-touch-icon-5ae1a0698dcc2402e9712f7d01ed509a57814f994c660df9f7a952f3060705ee.png" />

    <meta name="apple-mobile-web-app-title" content="CodePen">

    <link rel="shortcut icon" type="image/x-icon" href="https://cpwebassets.codepen.io/assets/favicon/favicon-aec34940fbc1a6e787974dcd360f2c6b63348d4b1f4e06c77743096d55480f33.ico" />

    <link rel="mask-icon" type="image/x-icon" href="https://cpwebassets.codepen.io/assets/favicon/logo-pin-b4b4269c16397ad2f0f7a01bcdf513a1994f4c94b8af2f191c09eb0d601762b1.svg" color="#111" />




    <script src="https://cpwebassets.codepen.io/assets/common/stopExecutionOnTimeout-2c7831bb44f98c1391d6a4ffda0e1fd302503391ca806e7fcc7b9b87197aec26.js"></script>


    <title>CodePen - Prescription Template</title>

    <link rel="canonical" href="https://codepen.io/sXakil/pen/VoWLKO">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>
    <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.4.1/css/all.css'>

    <style>
        body {
            padding: 0;
            margin: 0;
            transition: all 0.3s ease;
        }
        .wrapper {
            height: 100vh;
            width: 100vw;
            display: flex;
            flex-direction: column;
            margin: 50px;
        }
        .prescription_form {
            width: 100%;
            height: 100vh;

            background: white;
        }
        .prescription {
            width: 720px;
            height: 960px;
            margin: 0 auto;
            border: 1px solid lightgrey;
        }
        .prescription tr > td {
            padding: 15px;
        }
        .header {
            color: #333;
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logo {
            flex: 1;
        }
        .logo img {
            width: 120px;
            height: 120px;
            float: left;
        }
        .credentials {
            flex: 1;
        }
        .credentials h4 {
            line-height: 1em;
            letter-spacing: 1px;
            font-weight: 700;
            margin: 0px;
            padding: 0px;
        }
        .credentials p {
            margin: 0 0 5px 0;
            padding: 3px 0px;
        }
        .credentials small {
            margin: 0;
            padding: 0;
            letter-spacing: 1px;
            padding-right: 80px;
        }
        .d-header {
            width: 100%;
            text-align: center;
            background: mediumseagreen;
            padding: 5px;
            color: white;
            font-weight: 800;
        }
        .symptoms,
        .tests,
        .advice {
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .symptoms ul,
        .tests ul {
            list-style: square;
            margin: 0;
            padding-left: 10px;
            height: 100%;
        }
        .advice p {
            letter-spacing: 0;
            font-size: 14px;
        }
        .advice .adv_text {
            flex-grow: 1;
            width: 100%;
            height: 100%;
        }

        .desease_details {
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }
        .med_name {
            font-size: 16px;
            font-weight: bold;
            padding: 0;
        }
        .taking_time {
            display: flex;
            flex-direction: row;
            justify-content: flex-end;
            text-align: right;
        }
        .schedual {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
        }
        .sc_time {
            margin: 0;
            padding: 0;
            float: left;
        }
        .sc_time span {
            font-weight: bold;
            margin-right: 1rem;
        }
        .sc {
            border: none;
            outline: none;
            font-size: 15px;
        }

        select {
            -webkit-appearance: none;
            -moz-appearance: none;
            -ms-appearance: none;
            appearance: none;
            outline: 0;
            box-shadow: none;
            border: 0 !important;
            background: #fff;
            background-image: none;
        }
        select::-ms-expand {
            display: none;
        }
        .select {
            font-size: 15px;
            color: #335;
            position: relative;
            float: left;
            overflow: hidden;
        }
        select {
            font-weight: bold;
            padding: 0 0.5em;
            color: #333;
            cursor: pointer;
            outline: none;
        }
        .med_name {
            border: 0;
            outline: 0;
        }
        .period {
            font-size: 14px;
        }
        input[type="date"] {
            padding: 0;
            margin: 0;
            font-weight: bold;
            border: none;
        }
        .medicine {
            display: flex;
            flex-flow: column;
            height: 100%;
        }
        .med_name_action,
        .med_when_action,
        .med_period_action {
            display: none;
        }
        .med_meal_action .btn {
            margin: 2px;
        }
        .med_period {
            border: none;
            outline: none;
        }
        #add_med {
            margin: 20px 5px;
            flex-grow: 1;
        }
        #add_med {
            animation: shake 1.5s linear infinite;
        }

        @keyframes shake {
            10%,
            90% {
                transform: translate3d(-1px, 0, 0);
            }

            20%,
            80% {
                transform: translate3d(2px, 0, 0);
            }

            30%,
            50%,
            70% {
                transform: translate3d(-4px, 0, 0);
            }

            40%,
            60% {
                transform: translate3d(4px, 0, 0);
            }
            95% {
                opacity: 0;
            }
        }

        #add_symptoms {
            margin: 20px 5px;
            flex-grow: 1;
            opacity: 1;
        }
        .symp_action,
        .test_action,
        .adv_action {
            display: none;
        }
        .med_footer {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        hr {
            margin: 10px 0px;
        }
        .med:hover hr {
            border-top: 3px #111 solid;
        }
        .med_period_action {
            float: right;
        }
        span.date {
            color: #333399;
            float: right;
            clear: both;
        }
        .del_action {
            width: 100%;
            text-align: right;
        }
        .delete {
            width: 50px;
            opacity: 0;
            display: none;
        }
        .med:hover .delete {
            display: inline;
            opacity: 1;
        }
        .folded {
            visibility: hidden;
        }
        .button_group {
            position: fixed;
            right: 120px;
            bottom: 100px;
        }
        #snacking,
        #snacked {
            visibility: hidden;
            min-width: 250px;
            margin-left: -125px;
            background-color: #333;
            color: #fff;
            text-align: center;
            border-radius: 2px;
            padding: 16px;
            position: fixed;
            z-index: 1;
            left: 50%;
            bottom: 30px;
            font-size: 17px;
        }

        #snacking.show,
        #snacked.show {
            visibility: visible;
            -webkit-animation: fadein 0.5s, fadeout 0.5s 2.5s;
            animation: fadein 0.5s, fadeout 0.5s 2.5s;
        }

        @-webkit-keyframes fadein {
            from {
                bottom: 0;
                opacity: 0;
            }
            to {
                bottom: 30px;
                opacity: 1;
            }
        }

        @keyframes fadein {
            from {
                bottom: 0;
                opacity: 0;
            }
            to {
                bottom: 30px;
                opacity: 1;
            }
        }

        @-webkit-keyframes fadeout {
            from {
                bottom: 30px;
                opacity: 1;
            }
            to {
                bottom: 0;
                opacity: 0;
            }
        }

        @keyframes fadeout {
            from {
                bottom: 30px;
                opacity: 1;
            }
            to {
                bottom: 0;
                opacity: 0;
            }
        }
        #template {
            display: none;
        }
        #submitBtn, #printBtn {
            display: block;
            margin: 0 auto;
            padding: 10px 20px;
            background-color: #a9a1f1; /* Change this to your preferred color */
            color: #000000;
            border: none;
            text-align: center;
            text-decoration: none;
            font-size: 16px;
            transition-duration: 0.4s;
            cursor: pointer;
        }

        #submitBtn:hover, #printBtn:hover {
            background-color: #455fa0; /* Change this to your preferred color */
            color: white;
        }
    </style>

    <script>
        window.console = window.console || function(t) {};
    </script>


</head>

<body translate="no">
<div class="wrapper">
    <a href="doctor_appointment.php" class="btn">Go Back</a>
    <div class="prescription_form">
        <table class="prescription" data-prescription_id="{{ prescription_id }}" border="1">
            <tbody>
            <tr height="15%">
                <td colspan="2">
                    <div class="header">
                        <div class="logo"><img src="https://seeklogo.com/images/H/hospital-clinic-plus-logo-7916383C7A-seeklogo.com.png"/></div>
                        <div class="credentials">
                            <h4><?= htmlspecialchars($doctorDetails['DoctorName']) ?></h4>
                            <p><?= htmlspecialchars($doctorDetails['address']) ?></p>
                            <small>Mb. <?= htmlspecialchars($doctorDetails['DoctorPhone']) ?></small>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td width="40%">
                    <div class="desease_details">

                        <div class="patient_id">
                            <h4 class="d-header">Patient ID</h4>
                            <input class="pid" data-toggle="tooltip" data-placement="bottom" title="Enter patient ID." value="<?= isset($patient_id) ? htmlspecialchars($patient_id) : '' ?>"/>
                        </div>
                        <div class="symptoms">
                            <h4 class="d-header">Symptoms</h4>
                            <ul class="symp" data-toggle="tooltip" data-placement="bottom" title="Click to edit." contenteditable="true"></ul>
                            <div class="symp_action">
                                <button class="btn btn-sm btn-success save" id="symp_save" data-prescription_id="{{ prescription_id }}">Save</button>
                                <button class="btn btn-sm btn-danger cancel-btn">Cancel</button>
                            </div>
                        </div>
                        <div class="tests">
                            <h4 class="d-header">Tests</h4>
                            <ul class="tst" data-toggle="tooltip" data-placement="bottom" title="Click to edit." contenteditable="true"></ul>
                            <div class="test_action">
                                <button class="btn btn-sm btn-success save" id="test_save" data-prescription_id="{{ prescription_id }}">Save</button>
                                <button class="btn btn-sm btn-danger cancel-btn">Cancel</button>
                            </div>
                        </div>
                        <div class="advice">
                            <h4 class="d-header">Advice</h4>
                            <p class="adv_text" style="outline: 0" data-toggle="tooltip" data-placement="bottom" title="Click to edit." contenteditable="true"></p>
                            <div class="adv_action">
                                <button class="btn btn-sm btn-success save" id="adv_save" data-prescription_id="{{ prescription_id }}">Save</button>
                                <button class="btn btn-sm btn-danger cancel-btn">Cancel</button>
                            </div>
                        </div>
                    </div>
                </td>
                <td width="60%" valign="top"><span style="font-size: 2em">R<sub>x</sub></span>
                    <hr/>
                    <div class="medicine">
                        <section class="med_list"></section>
                        <div id="add_med" data-toggle="tooltip" data-placement="right" title="Click anywhere on the blank space to add more.">Click to add...</div>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
        <div id="snacking">Saving...</div>
        <div id="snacked">Saved!</div>
        <button id="printBtn">Print</button>
    </div>
</div>
<script id="new_medicine" type="text/template">
    <div class="med">&#x26AB;
        <input class="med_name" data-med_id="{{med_id}}" data-toggle="tooltip" title="Click to edit..." placeholder="Enter medicine name"/>
        <div class="med_name_action">

        </div>
        <div class="schedual">
            <div class="sc_time folded">
                <select class="sc" data-med_id="{{med_id}}">
                    <option value="1+1+1" selected="">1+1+1</option>
                    <option value="1+0+1">1+0+1</option>
                    <option value="0+1+1">1+1+1</option>
                    <option value="1+0+0">1+1+1</option>
                    <option value="0+0+1">1+1+1</option>

                </select>

            </div>
            <div class="taking_time select folded">
                <select class="meal" data-med_id="{{med_id}}">
                    <option value="1" selected="">After Meal</option>
                    <option value="2">Before Meal</option>
                    <option value="3">Take any time</option>
                </select>

            </div>
        </div>
        <div class="med_footer">
            <div class="period folded">Take for
                <input class="med_period" type="text" data-med_id="{{med_id}}" placeholder="? days/weeks..."/>

            </div>
            <div class="del_action">

            </div>
        </div>
        <hr/>
    </div>
</script>
<script src='https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js'></script>
<script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/mustache.js/3.0.1/mustache.min.js'></script>
<script id="rendered-js" >
    $(document).ready(function () {
        // Remove the check or submit button after every text input
        $(".med_name_action, .med_when_action, .med_meal_action, .med_period_action").remove();

        // Show all available options at once after clicking on "Click to add medicine"
        let med_id = 1;
        $("#add_med").click(function() {
            // Increment the medicine ID
            med_id++;

            // Render a new medicine field using the Mustache template
            let sourceTemplate = $("#new_medicine").html();
            Mustache.parse(sourceTemplate);
            let sourceHTML = Mustache.render(sourceTemplate, { med_id });

            // Append the new medicine field to the medicine list
            let medicine = $(".med_list");
            medicine.append(sourceHTML);

            // Show all available options
            $(".folded").removeClass("folded");

            // Add a "Remove" button to the new medicine field
            let removeButton = $('<button class="btn btn-sm btn-danger remove">Remove</button>');
            removeButton.click(function() {
                $(this).closest('.med').remove();
            });
            medicine.find('.med:last .del_action').append(removeButton);

            // Add a "Save" button to the new medicine field
            let saveButton = $('<button class="btn btn-sm btn-success save">Save</button>');
            saveButton.click(function() {
                // Collect data from the form
                let patient_id = $(".pid").val();
                let symptoms = $(".symptoms ul").text();
                let tests = $(".tests ul").text();
                let advice = $(".advice p").text();
                let medicine_name = $(this).closest('.med').find(".med_name").val();
                let medicine_period = $(this).closest('.med').find(".med_period").val();

                // Retrieve the selected values of the "sc" and "meal" dropdowns
                let sc_value = $(this).closest('.med').find(".sc").val();
                let meal_value = $(this).closest('.med').find(".meal").val();

                // Concatenate these values with the medicine period, separated by commas
                let combined_value = medicine_period + "," + sc_value + "," + meal_value;

                // Send data to the server
                $.post('save.php', {
                    patient_id: patient_id,
                    symptoms: symptoms,
                    tests: tests,
                    advice: advice,
                    name: medicine_name,
                    period: combined_value
                }, function(data) {
                    alert("Data saved successfully!");
                });
            });
            medicine.find('.med:last .del_action').append(saveButton);
        });
        // Add a submit button at the end of the whole prescription panel
        $(".prescription_form").append('<button id="submitBtn">Submit</button>');

        // Clicking the submit button will push the data to the MySQL table
        $("#submitBtn").click(function() {
            // Collect data from the form
            let patient_id = $(".pid").val();
            let symptoms = $(".symptoms ul").text();
            let tests = $(".tests ul").text();
            let advice = $(".advice p").text();

            // Create an array to hold the medicines
            let medicines = [];

            // Iterate over each medicine
            $(".medicine").each(function() {
                let medicine_name = $(this).find(".med_name").val();
                let medicine_period = $(this).find(".med_period").val();

                // Retrieve the selected values of the "sc" and "meal" dropdowns
                let sc_value = $(this).find(".sc").val();
                let meal_value = $(this).find(".meal").val();

                // Concatenate these values with the medicine period, separated by commas
                let combined_value = medicine_period + "," + sc_value + "," + meal_value;

                // Add the medicine to the array
                medicines.push({
                    name: medicine_name,
                    period: combined_value
                });
            });

            // Send data to the server
            $.post('prescription.php', {
                patient_id: patient_id,
                symptoms: symptoms,
                tests: tests,
                advice: advice,
                medicines: medicines  // Send the medicines array as an array of objects
            }, function(data) {
                alert("Data submitted successfully!");
            });
        });
        // Clicking print will take it to the generate_pdf.php page
        $("#printBtn").click(function() {
            let patient_id = $(".pid").val();
            window.location.href = 'generate_pdf.php?patient_id=' + patient_id;
        });
    });
    //# sourceURL=pen.js
</script>


<script>
    document.getElementById('printBtn').addEventListener('click', function() {
        window.location.href = 'generate_pdf.php';
    });
</script>
<button onclick="goBack()">Go Back</button>


</body>

</html>

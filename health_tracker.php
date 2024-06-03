<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Health Tracker</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: rgba(240, 128, 128, 0.9); /* Faded coral color */
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 100px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 30px;
        }
        ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }
        li {
            margin-bottom: 15px;
        }
        a {
            display: block;
            padding: 15px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            transition: background-color 0.3s;
        }
        a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Health Tracker</h1>

    <div>
        <p>Choose which record you want to check:</p>
        <ul>
            <li><a href="medical_history.php">Medical History</a></li>
            <li><a href="health_record.php">Health Record</a></li>
        </ul>
    </div>
</div>

</body>
</html>

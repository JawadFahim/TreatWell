<?php
// Check if request is a POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the data from the POST request
    $new_data = [
        'name' => $_POST['name'],
        'period' => $_POST['period']
    ];

    // Read the existing data from the file
    $json_data = file_get_contents('formData.json');

    // Decode the JSON data to a PHP array
    $data = json_decode($json_data, true);

    // If the data is not an array (i.e., the file is empty), initialize it as an empty array
    if (!is_array($data)) {
        $data = [];
    }

    // Append the new data to the array
    $data[] = $new_data;

    // Convert the data to JSON format
    $json_data = json_encode($data, JSON_PRETTY_PRINT);

    // Write the JSON data to formData.json file
    file_put_contents('formData.json', $json_data);
}
?>
<?php
    header('Content-Type: application/json');

    $servername = "http://localhost/projectphp/";
    $username = "root";
    $password = ""; 
    $dbname = "kidsgames";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get username from the AJAX request
    $username = $_GET['username'];

    // Query to check if username exists
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Username is taken
        echo json_encode(array("usernameTaken" => true));
    } else {
        // Username is not taken
        echo json_encode(array("usernameTaken" => false));
    }

    $conn->close();
?>

<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'DatabaseConnection.php';
$dbConnection = new DatabaseConnection('localhost', 'root', '');
session_start();
// Check the connection
if ($dbConnection->getConnection()->connect_error) {
    die("Connection failed: " . $dbConnection->getConnection()->connect_error);
}

try {
    // Get the data from the login form
    $username = $_POST['username'];
    $password = $_POST['password'];
    $dbConnection->selectDatabase('kidsgames');
    // Perform additional checks, for example, check if the username already exists in the database
    $stmt = $dbConnection->getConnection()->prepare("SELECT * FROM player WHERE userName = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows <= 0) {
        throw new Exception("Username doesn't exist!");
    }

    $dbConnection->verifyCredentials($username, $password);
    $_SESSION['username'] = $username;

    if ($dbConnection->verifyCredentials($username, $password) == true) {
        header("Location: game.php");
    } else {
        throw new Exception("Incorrect password!");
    }

    exit();
} catch (Exception $e) {
    $error = $e->getMessage();

    // Display the error message and provide a link to go back to the sign-up page
    echo json_encode(["success" => false, "message" => $error]);
    echo '
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Registration Error</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <style>
            .container {
                margin-top: 50px;
            }
            h1 {
                text-align: center;
                margin-bottom: 20px;
            }
            .error-message {
                text-align: center;
                margin-bottom: 20px;
                color: red;
                font-weight: bold;
            }
            .back-link {
                text-align: center;
                margin-bottom: 20px;
            }
        </style>
    </head>
    <body>
        <div class="container">
        
           <div class > <h1> Registration Error</h1></div>
            <div class="error-message">' . $e->getMessage() . '</div>
            <div class="back-link"><a href="index.html">go back to login</a></div>';

    if ($error == "Incorrect password!") {
        echo ' <div class="back-link"><a href="Mdp.html" title="Reset your password">Forgot password?</a></div>';
    }

    echo '
        </div>
    </body>
    </html>';
}
?>

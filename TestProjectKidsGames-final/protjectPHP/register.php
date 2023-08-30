<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'DatabaseConnection.php';

$dbConnection = new DatabaseConnection('localhost', 'root', '');

// Check the connection
if ($dbConnection->getConnection()->connect_error) {
    die("Connection failed: " . $dbConnection->getConnection()->connect_error);
}

$error = null;

try {
    // get the data from the form
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];

    // Check if password and confirm_password match
    if ($password !== $confirm_password) {
        throw new Exception("Passwords do not match!");
    }

    // Hash the password before storing it
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $dbConnection->selectDatabase('kidsgames');

    // Perform additional checks, for example, check if the username already exists in the database
    $stmt = $dbConnection->getConnection()->prepare("SELECT * FROM player WHERE userName = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        throw new Exception("Username already taken!");
    }

    // Insert the new user into the database and get the registrationOrder
    $registrationOrder = $dbConnection->insertPlayer($first_name, $last_name, $username);

    // Insert the foreign key into the authenticator table
    $dbConnection->insertAuthenticator($hashed_password, $registrationOrder);

    // Redirect the user to index.html after successful registration
    echo json_encode(["success" => true, "message" => "Registration successful!"]);
    echo "<script>window.location.href = 'index.html';</script>";
    exit();
} catch (Exception $e) {
    // Store the error message
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
            <h1>Registration Error</h1>
            <div class="error-message">' . $e->getMessage() . '</div>
            <div class="back-link"><a href="signup.html">Go back to Sign Up</a></div>
        </div>
    </body>
    </html>
    ';    exit();
}
?>

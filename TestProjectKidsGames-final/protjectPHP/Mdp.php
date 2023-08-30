<?php
require_once 'DatabaseConnection.php';
session_start();

$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'kidsgames';
$dbConnection = new DatabaseConnection($hostname, $username, $password);
$dbConnection->selectDatabase($database);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve values from the form
    $username = $_POST['username'];
    $newPassword = $_POST['NewPassword'];
    $confirmPassword = $_POST['ConfirmPassword'];

    // Password matching check
    if ($newPassword !== $confirmPassword) {
        echo "Passwords do not match!";
        header("Location: index.html");
    } elseif (strlen($newPassword) < 8) {
        echo "Password should be at least 8 characters long!";
        header("Location: index.html");
    } else {
        // Update the password
        $dbConnection->updatePassword($username, $newPassword);

        // Return success response
        echo "Password was changed with success";
        header("Location: index.html");
        exit();
    }
}
?>
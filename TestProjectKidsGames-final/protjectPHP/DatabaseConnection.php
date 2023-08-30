<?php
class DatabaseConnection
{

    private $connection;

    public function __construct($hostname, $username, $password)
    {
        $this->connection = new mysqli($hostname, $username, $password);

        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
    }

    public function getConnection()
    {
        return $this->connection;

    }

    public function createDatabase($database)
    {
        $sqlCreateDatabaseQuery = "CREATE DATABASE IF NOT EXISTS $database";

        if ($this->connection->query($sqlCreateDatabaseQuery) === FALSE) {
            echo "Failed to create database: " . $this->connection->error;
        }

        $this->connection->select_db($database);
    }

    public function selectDatabase($database)
    {
        $select = $this->connection->select_db($database);

        if ($select === FALSE) {
            $errorMessage = mysqli_connect_error();
            die("Selection of the database $database failed! <br>" . $errorMessage);
        }
    }

    //////////////////////////////////////////////////////DATABASE/////////////////////////////////////////////////////////////////////////////////////////
    public function createTablePlayer()
    {
        $sql = "CREATE TABLE IF NOT EXISTS player (
            fName VARCHAR(50) NOT NULL,
            lName VARCHAR(50) NOT NULL,
            userName VARCHAR(20) NOT NULL UNIQUE,
            registrationTime DATETIME NOT NULL,
            id VARCHAR(200) GENERATED ALWAYS AS (CONCAT(UPPER(LEFT(fName,2)),UPPER(LEFT(lName,2)),UPPER(LEFT(userName,3)),CAST(registrationTime AS SIGNED))),
            registrationOrder INTEGER AUTO_INCREMENT,
            PRIMARY KEY (registrationOrder)
        ) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci";

        if ($this->connection->query($sql) === FALSE) {
            echo "Failed to create table 'player': " . $this->connection->error;
        }
    }

    public function createTableAuthenticator()
    {
        $sql = "CREATE TABLE IF NOT EXISTS authenticator (
            passCode VARCHAR(255) NOT NULL,
            registrationOrder INTEGER,
            FOREIGN KEY (registrationOrder) REFERENCES player(registrationOrder)
        ) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci";

        if ($this->connection->query($sql) === FALSE) {
            echo "Failed to create table 'authenticator': " . $this->connection->error;
        }
    }

    public function createTableScore()
    {
        $sql = "CREATE TABLE IF NOT EXISTS score (
            scoreTime DATETIME NOT NULL,
            result ENUM('réussite', 'échec', 'incomplet'),
            livesUsed INTEGER NOT NULL,
            registrationOrder INTEGER,
            FOREIGN KEY (registrationOrder) REFERENCES player(registrationOrder)
        ) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci";

        if ($this->connection->query($sql) === FALSE) {
            echo "Failed to create table 'score': " . $this->connection->error;
        }
    }

    public function createViewHistory()
    {
        $sql = "CREATE OR REPLACE VIEW history AS
            SELECT s.scoreTime, p.id, p.fName, p.lName, s.result, s.livesUsed 
            FROM player p, score s
            WHERE p.registrationOrder = s.registrationOrder";

        if ($this->connection->query($sql) === FALSE) {
            echo "Failed to create view 'history': " . $this->connection->error;
        }
    }

    /////////////////////////////////////////////CREATETABLE////////////////////////////////////////////////////////////
    public function updatePassword($username, $newPassword)
    {
        $tableName = 'authenticator';
        $hashedNewPassword = password_hash($newPassword, PASSWORD_BCRYPT);

        // Get the registration order based on the username
        $registrationOrder = $this->getRegistrationOrderByUsername($username);

        if ($registrationOrder === null) {
            echo "Username not found.";
            return;
        }

        $sqlUpdateQuery = "UPDATE $tableName SET passCode='$hashedNewPassword' WHERE registrationOrder='$registrationOrder'";

        if ($this->connection->query($sqlUpdateQuery) === FALSE) {
            echo "Failed to update password: " . $this->connection->error;
        } else {
            echo "Password updated successfully.";
        }
    }

    public function verifyCredentials($username, $password)
    {
        $sqlQuery = "SELECT p.userName, a.passCode FROM player p
                     INNER JOIN authenticator a ON p.registrationOrder = a.registrationOrder
                     WHERE p.userName = ?";
        $statement = $this->connection->prepare($sqlQuery);
        $statement->bind_param("s", $username);
        $statement->execute();

        $result = $statement->get_result();

        if ($result === FALSE) {
            echo "Failed to fetch credentials: " . $this->connection->error;
            return false;
        }

        $row = $result->fetch_assoc();

        if ($row) {
            $storedUsername = $row['userName'];
            $hashedPassword = $row['passCode'];

            if ($storedUsername === $username && password_verify($password, $hashedPassword)) {
                echo "Credentials are valid.";
                return true;
            }
        }

        echo "Invalid credentials.";
        return false;
    }
    function getRegistrationOrderByUsername($username)
    {
        // Escape the username to prevent SQL injection
        $escapedUsername = $this->connection->real_escape_string($username);

        // Query to fetch the registration order by username
        $sqlQuery = "SELECT registrationOrder FROM player WHERE userName = '$escapedUsername'";

        $result = $this->connection->query($sqlQuery);

        if ($result === FALSE) {
            echo "Failed to fetch registration order: " . $this->connection->error;
        } else {
            $row = $result->fetch_assoc();
            if ($row) {
                $registrationOrder = $row['registrationOrder'];
                return $registrationOrder;
            } else {
                return null;
            }
        }
    }

    public function displayHistory($array)
    {
        echo "<table>";
        echo "<tr><th>Score Time</th><th>ID</th><th>First Name</th><th>Last Name</th><th>Result</th><th>Lives Used</th></tr>";

        foreach ($array as $item) {
            echo "<tr>";
            echo "<td>{$item['scoreTime']}</td>";
            echo "<td>{$item['id']}</td>";
            echo "<td>{$item['fName']}</td>";
            echo "<td>{$item['lName']}</td>";
            echo "<td>{$item['result']}</td>";
            echo "<td>{$item['livesUsed']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    }

    public function getHistory()
    {
        $tableName = 'history';
        $sqlQuery = "SELECT * FROM $tableName";

        $result = $this->connection->query($sqlQuery);

        if ($result === FALSE) {
            echo "Failed to fetch history data: " . $this->connection->error;
            return null;
        }

        $historyData = [];

        while ($row = $result->fetch_assoc()) {
            $historyData[] = $row;
        }

        return $historyData;
    }

    public function insertPlayer($fName, $lName, $userName)
    {
        $sqlQuery = "INSERT INTO player(fName, lName, userName, registrationTime) VALUES ('$fName', '$lName', '$userName', NOW())";

        if ($this->connection->query($sqlQuery) === FALSE) {
            echo "Failed to insert data into player table: " . $this->connection->error;
        }
        return $this->connection->insert_id;
    }

    public function insertAuthenticator($passCode, $registrationOrder)
    {
        $sqlQuery = "INSERT INTO authenticator(passCode, registrationOrder) VALUES ('$passCode', $registrationOrder)";

        if ($this->connection->query($sqlQuery) === FALSE) {
            echo "Failed to insert data into authenticator table: " . $this->connection->error;
        }
    }

    public function insertScore($scoreTime, $result, $livesUsed, $registrationOrder)
    {
        $sqlQuery = "INSERT INTO score(scoreTime, result, livesUsed, registrationOrder) VALUES ('$scoreTime', '$result', $livesUsed, $registrationOrder)";

        if ($this->connection->query($sqlQuery) === FALSE) {
            echo "Failed to insert data into score table: " . $this->connection->error;
        }
    }

    public function insertData() ////// for testing purpose //////
    {
        // Example usage
        $this->insertPlayer('Patrick', 'Saint-Louis', 'sonic12345');
        $this->insertPlayer('Marie', 'Jourdain', 'asterix2023');
        $this->insertPlayer('Jonathan', 'David', 'pokemon527');

        $this->insertAuthenticator('$2y$10$AMyb4cbGSWSvEcQxt91ZVu5r5OV7/3mMZl7tn8wnZrJ1ddidYfVYW', 1);
        $this->insertAuthenticator('$2y$10$Lpd3JsgFW9.x2ft6Qo9h..xmtm82lmSuv/vaQKs9xPJ4rhKlMJAF.', 2);
        $this->insertAuthenticator('$2y$10$FRAyAIK6.TYEEmbOHF4JfeiBCdWFHcqRTILM7nF/7CPjE3dNEWj3W', 3);

        $this->insertScore(date("Y-m-d H:i:s"), 'réussite', 4, 1);
        $this->insertScore(date("Y-m-d H:i:s"), 'échec', 6, 2);
        $this->insertScore(date("Y-m-d H:i:s"), 'incomplet', 5, 3);
    }

    public function setupDatabase($database)
    {
        $this->createDatabase($database);
        $this->selectDatabase($database);
        $this->createTablePlayer();
        $this->createTableAuthenticator();
        $this->createTableScore();
        $this->createViewHistory();
    }
}

$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'kidsgames';
$dbConnection = new DatabaseConnection($hostname, $username, $password);
$dbConnection->setupDatabase($database);
$dbConnection->selectDatabase($database);
// $dbConnection->insertData();

?>
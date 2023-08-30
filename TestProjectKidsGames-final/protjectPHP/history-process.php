<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="./css/history.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <title>LaSalleQuizApp</title>
</head>
<body id="home">
<header>
    <div class="navbar-container">
        <nav class="navbar navbar-expand-lg navbar-dark">
            <a class="navbar-brand" href="index.html">
                <span class="text-primary-logo"><strong>LaSalle</strong></span>
                <span class="text-Logo"><strong>QuizApp.</strong></span>
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="index.html">log out</a>
                    </li>
                   
                    <li class="nav-item">
                        <a class="nav-link" href="history-process.php">History</a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</header>
<main>
    <div class="game-container">
        <h1 class="heading">Game History</h1>
        <section class="gameform">
            <table>
                <tr>
                    <th>Score Time</th>
                    <th>Player ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Result</th>
                    <th>Lives Used</th>
                </tr>
                <?php
                // Connect to the database
                $servername = 'localhost';
                $username = 'root';
                $password = '';
                $dbname = 'kidsgames';

                $conn = new mysqli($servername, $username, $password, $dbname);

                if ($conn->connect_error) {
                    die('Connection failed: ' . $conn->connect_error);
                }

                // Fetch the game history from the history view
                $sql = 'SELECT * FROM history';
                $result = $conn->query($sql);

                $history = array();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $history[] = $row;
                    }
                }

                // Close the database connection
                $conn->close();

                foreach ($history as $entry) {
                    echo '<tr>';
                    echo '<td>' . $entry['scoreTime'] . '</td>';
                    echo '<td>' . $entry['id'] . '</td>';
                    echo '<td>' . $entry['fName'] . '</td>';
                    echo '<td>' . $entry['lName'] . '</td>';
                    echo '<td>' . $entry['result'] . '</td>';
                    echo '<td>' . $entry['livesUsed'] . '</td>';
                    echo '</tr>';
                }
                ?>
            </table>

            <div class="button-container">
                <button class="btn btn-loginPrimary" onclick="location.href='index.html'">Go Back to Login</button>
                <button class="btn btn-gamePrimary" onclick="location.href='game.php'">Play Another Game</button>
            </div>
        </section>
    </div>
</main>

<footer class="footer-container">
    <div class="col-md-12">
        <p class="text-center">LaSalle Quiz App &copy; 2023</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
<script src="./js/script.js"></script>
</body>
</html>
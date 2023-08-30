<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="./css/gameForm.css">
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
    <span class = "borderLine"></span>
    <section class="gameform">
    <h1 class="heading">Quiz App</h1>
        <?php
    session_start();

    require_once 'DatabaseConnection.php';

    if (!isset($_SESSION['time_start'])) {
        $_SESSION['time_start'] = time();
    }

    // Retrieve the levels
    $levels = include 'levels.php';

    // Retrieve the current level index from the POST data or set it to 0 by default
    $levelIndex = $_POST['levelIndex'] ?? 0;

    // Retrieve the current level
    $level = $levels[$levelIndex];

    date_default_timezone_set('America/Montreal');


    // Retrieve user input and expected answer from the POST data
    $userInput = $_POST['input'] ?? null;
    $expectedAnswer = $_POST['expectedAnswer'] ?? '';

    // Check if the user gave up
    $giveUp = isset($_POST['giveUp']);

 // Check if the session variable for lives is set
 $lives = $_SESSION['lives'] ?? 0;

    // Convert user's input array to a string
    $userInputString = implode('', (array)$userInput);

    if (isset($_POST['giveUp'])) {
        // The user gave up, so the result is incomplete
        $resultMessage = 'incomplet';
        $giveUp = true;
        echo "<h2>Incomplete part (abandoned part or user account logged out: cancel, time-out or sign-out)</h2>";
        echo "<p>Le joueur a abandonné la partie de jeu en cours.</p>";
        echo "<p>Partie terminée.</p>";
        $current_time = new DateTime();
        $current_time_str = $current_time->format('Y-m-d H:i:s');
        
              // Insert the score into the database
              $dbConnection = new DatabaseConnection("localhost", "root", "");
              $dbConnection->selectDatabase('kidsgames');
      
              $username = $_SESSION['username'];
              $registrationOrder = $dbConnection->getRegistrationOrderByUsername($username);
              $dbConnection->insertScore($current_time_str, $resultMessage, $lives, $registrationOrder);
              $_SESSION['lives'] = 0;


    } else {
        $giveUp = false;
    }
    

    

    if ($userInput !== null  ) {
        // Check if the user input matches the expected answer for the current level
        
// Check if the user input matches the expected answer for the current level
$isCorrect = (strcmp(trim($userInputString), trim($expectedAnswer)) === 0);
        if (!$isCorrect && !$giveUp) {
            // Increment lives if the answer is incorrect
            $lives++;
            $_SESSION['lives'] = $lives;

            if ($lives >= 6) {
                $resultMessage = 'échec';
                $current_time = new DateTime();
        
        $current_time_str = $current_time->format('Y-m-d H:i:s');
        
         
        
        
      
              // Insert the score into the database
              $dbConnection = new DatabaseConnection("localhost", "root", "");
              $dbConnection->selectDatabase('kidsgames');
      
              $username = $_SESSION['username'];
              $registrationOrder = $dbConnection->getRegistrationOrderByUsername($username);
              $dbConnection->insertScore($current_time_str, $resultMessage, $lives, $registrationOrder);
               $_SESSION['lives'] = 0;
                echo "<h2>Game Over!</h2>";
                echo "<p>Sorry, you ran out of lives.</p>";
                echo "<button onclick=\"location.href='game.php'\">Play Again</button>";
                exit; // Stop further processing
            }
        }

        // Display the result message based on correctness
        if ($isCorrect) {
            
            if ($levelIndex == count($levels) - 1) {
                // User completed all levels
                $resultMessage = 'réussite';
                $_SESSION['lives'] = 0;
                $current_time = new DateTime();
        
        $current_time_str = $current_time->format('Y-m-d H:i:s');

              // Insert the score into the database
              $dbConnection = new DatabaseConnection("localhost", "root", "");
              $dbConnection->selectDatabase('kidsgames');
             
              $username = $_SESSION['username'];
              $registrationOrder = $dbConnection->getRegistrationOrderByUsername($username);
              $dbConnection->insertScore($current_time_str, $resultMessage, $lives, $registrationOrder);
                echo "<h2>Congratulations! You completed all levels successfully.</h2>";
                echo "<p>Well done!</p>";
                echo "<button onclick=\"location.href='game.php'\">Play Again</button>";
            } else {
                // User completed the current level
                $nextLevelIndex = $levelIndex + 1;
                echo "<h2>Success!</h2>";
                echo "<p>Congratulations! You completed the level successfully.</p>";
                echo "<p>Proceed to the next level.</p>";
                echo "<form action=\"game.php\" method=\"post\">";
                echo "<input type=\"hidden\" name=\"levelIndex\" value=\"$nextLevelIndex\">";
                echo "<button type=\"submit\">Next Level</button>";
                echo "</form>";
                
            }
        } else {
            // User failed in the current level
            echo "<h2>Failure!</h2>";
            echo "<p>Oops! Your arrangement is incorrect.</p>";
            echo "<p>Please try again.</p>";
            echo "<p>Expected Answer: " . $expectedAnswer . "</p>";
            echo "<p>User Input: " . $userInputString . "</p>";
            echo "<form action=\"game.php\" method=\"post\">";
            echo "<input type=\"hidden\" name=\"levelIndex\" value=\"$levelIndex\">";
            echo "<button type=\"submit\">Try Again</button>";
            echo "</form>";
            $lives++;
            

        }
    } else {
        // Display the form for the current level
        echo "<h2 id=\"levelTitle\">" . $level['title'] . "</h2>";
        echo "<form id=\"gameForm\" class=\"level\" action=\"game.php\" method=\"POST\">";
        echo "<div class=\"header\">";
        echo "<div class=\"top-left\">";
        echo "User: " . $_SESSION['username'];
        echo "</div>";
        echo "<div class=\"top-right\">";
        echo "Lives used: " . $lives ."/6";
        echo "</div>";
        echo "</div>";

        $randomLettersSpaced = implode(' ', str_split($level['randomLetters']));
        echo "<p id=\"lettersToArrange\">" . $randomLettersSpaced . "</p>"; 
      
       
        $numInputBoxes = $level['numInputBoxes'];




                    for ($i = 0; $i < $numInputBoxes; $i++) {

                        echo "<input type=\"text\" name=\"input[]\" maxlength=\"1\" size=\"1\" style=\"width: auto; display: inline; text-align: center;\" required>";

                    }
        

        echo "<input type=\"hidden\" id=\"levelIndex\" name=\"levelIndex\" value=\"$levelIndex\">";
        echo "<input type=\"hidden\" name=\"expectedAnswer\" value=\"" . $level['expectedAnswer'] . "\">"; // Add hidden input for expected answer
        echo "<button type=\"submit\" name=\"submit\">Submit</button>";
        echo "<button id=\"giveUpButton\" type=\"submit\" name=\"giveUp\" formnovalidate>I Give Up!</button>";
        echo "</form>";
       
    }

        ?>
        </section>
    </div>
</main>
<footer class="footer-container">
    <div class="col-md-12">
        <p class="text-center">LaSalle Quiz App &copy; 2023</p>
    </div>
</footer>
<script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
<script src="./js/script.js"></script>
</body>
</html>
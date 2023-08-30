<?php
function generateRandomLetters($length)
{
    $letters = "";
    $possibleLetters = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";

    for ($i = 0; $i < $length; $i++) {
        $letters .= $possibleLetters[rand(0, strlen($possibleLetters) - 1)];
    }

    return $letters;
}

function sortuserinput($userinput){
    $sorteduserinput = str_split($userinput);

    usort($sorteduserinput, function ($a, $b) {
        return strcasecmp($a, $b);
    });

    return implode('', array_reverse($sorteduserinput));
}

function generateRandomNumbers($length)
{
    $numbers = "";
    $possibleNumbers = "0123456789";

    for ($i = 0; $i < $length; $i++) {
        $numbers .= $possibleNumbers[rand(0, strlen($possibleNumbers) - 1)];
    }

    return $numbers;
}

function sortLettersDescending($letters)
{
    $sortedLetters = str_split($letters);

    usort($sortedLetters, function ($a, $b) {
        return strcasecmp($a, $b);
    });

    return implode('', array_reverse($sortedLetters));
}

function sortLettersAscending($letters)
{
    $sortedLetters = str_split($letters);

    usort($sortedLetters, function ($a, $b) {
        return strcasecmp($a, $b);
    });

    return implode('', $sortedLetters);
}

function sortNumbersAscending($numbers)
{
    $sortedNumbers = str_split($numbers);

    usort($sortedNumbers, function ($a, $b) {
        return $a - $b;
    });

    return implode('', $sortedNumbers);
}

function sortNumbersDescending($numbers)
{
    $sortedNumbers = str_split($numbers);

    usort($sortedNumbers, function ($a, $b) {
        return $a - $b;
    });

    return implode('', array_reverse($sortedNumbers));
}

$levels = [
    [
        'title' => "Game Level 1 - Arrange 6 letters in ascending order",
        'randomLetters' => '',
        'numInputBoxes' => 6,
        'expectedAnswer' => '', // Will be set dynamically in the loop
    ],
    [
        'title' => "Game Level 2 - Arrange 6 letters in descending order",
        'randomLetters' => '',
        'numInputBoxes' => 6,
        'expectedAnswer' => '', // Will be set dynamically in the loop
    ],
    [
        'title' => "Game Level 3 - Arrange 6 numbers in ascending order",
        'randomLetters' => '',
        'numInputBoxes' => 6,
        'expectedAnswer' => '', // Will be set dynamically in the loop
    ],
    [
        'title' => "Game Level 4 - Arrange 6 numbers in descending order",
        'randomLetters' => '',
        'numInputBoxes' => 6,
        'expectedAnswer' => '', // Will be set dynamically in the loop
    ],
    [
        'title' => "Game Level 5 - Identify the first and last letter of a set of 6 letters",
        'randomLetters' => '',
        'numInputBoxes' => 2,
        'expectedAnswer' => '', // Will be set dynamically in the loop
    ],
    [
        'title' => "Game Level 6 - Identify the smallest and largest number of a set of 6 numbers",
        'randomLetters' => '',
        'numInputBoxes' => 2,
        'expectedAnswer' => '', // Will be set dynamically in the loop
    ],
];

foreach ($levels as &$level) {
    if (strpos($level['title'], "Game Level 1") !== false) {
        $level['randomLetters'] = generateRandomLetters(6);
        $level['expectedAnswer'] = sortLettersAscending($level['randomLetters']);
    } elseif (strpos($level['title'], "Game Level 2") !== false) {
        $level['randomLetters'] = generateRandomLetters(6);
        $level['expectedAnswer'] = sortLettersDescending($level['randomLetters']);
    } elseif (strpos($level['title'], "Game Level 3") !== false) {
        $level['randomLetters'] = generateRandomNumbers(6);
        $level['expectedAnswer'] = sortNumbersAscending($level['randomLetters']);
    } elseif (strpos($level['title'], "Game Level 4") !== false) {
        $level['randomLetters'] = generateRandomNumbers(6);
        $level['expectedAnswer'] = sortNumbersDescending($level['randomLetters']);
    }  elseif (strpos($level['title'], "Game Level 5") !== false) {
        $level['randomLetters'] = generateRandomLetters(6);
        $randomLettersArray = str_split($level['randomLetters']);
        $sortedLettersArray = $randomLettersArray; // Create a copy of the array to sort
        sort($sortedLettersArray, SORT_STRING); // Sort the letters in ascending order
        $level['expectedAnswer'] = $sortedLettersArray[0] . $sortedLettersArray[count($sortedLettersArray) - 1];
    }
    
     elseif (strpos($level['title'], "Game Level 6") !== false) {
        $level['randomLetters'] = generateRandomNumbers(6);
        $randomLettersArray = str_split($level['randomLetters']);
        $level['expectedAnswer'] = min($randomLettersArray) . max($randomLettersArray);
    }
}
return $levels;
?>

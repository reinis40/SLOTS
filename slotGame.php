<?php

$twoDArray = [];
$width = 3;
$height = 3;
$winnings = 0;
$balance = intval(readline("Enter your starting balance: "));
$bet = 10;

function randomizeCharacter(): string
{
    $rand = mt_rand(1, 100);
    if ($rand <= 50) {
        // 50% chance
        $characters = "A";
    } elseif ($rand <= 75) {
        // 25% chance
        $characters = "K";
    } elseif ($rand <= 95) {
        // 20% chance
        $characters = "X";
    } else {
        // 5% chance
        $characters = "7";
    }
    return $characters[rand(0, strlen($characters) - 1)];
}

function createSlot($height, $width, $twoDArray)
{
    for ($i = 0; $i < $height; $i++) {
        for ($j = 0; $j < $width; $j++) {
            $twoDArray[$i][$j] = randomizeCharacter();
        }
    }
    return $twoDArray;
}

function showSlot($height, $width, $twoDArray)
{
    for ($i = 0; $i < $height; $i++) {
        for ($j = 0; $j < $width; $j++) {
            echo "|" . $twoDArray[$i][$j];
        }
        echo "|";
        echo PHP_EOL;
    }
}

function calculateWinAmount($string, $int, $height, $width)
{
    $multiplier = [
          'A' => 0.5,
          'B' => 1,
          'X' => 2,
          '7' => 10
    ];
    return ($int * $multiplier[$string])/($height+$width);
}

function checkWin($height, $width, $twoDArray, $bet)
{
    $winAmount = 0;

    //horizontal
    for ($i = 0; $i < $height; $i++) {
        $count = 0;
        for ($j = 0; $j < $width; $j++) {
            if ($twoDArray[$i][0] !== $twoDArray[$i][$j]) {
                break;
            } else {
                $count++;
            }
        }
        if ($count >= 3) {
            $winAmount += $bet * calculateWinAmount($twoDArray[$i][0], $count, $height, $width);
        }
    }
    //vertical
    for ($j = 0; $j < $width; $j++) {
        $count = 0;
        for ($i = 0; $i < $height; $i++) {
            if ($twoDArray[0][$j] !== $twoDArray[$i][$j]) {
                break;
            } else {
                $count++;
            }
        }
        if ($count >= 3) {
            $winAmount += $bet * calculateWinAmount($twoDArray[0][$j], $count, $height, $width);
        }
    }
    return $winAmount;
}

function clearConsole()
{
    echo "\n \n \n \n \n \n";
}

while (true) {
    clearConsole();
    if (!empty($twoDArray)) {
        showSlot($height, $width, $twoDArray);
        echo "Winnings: " . $winnings . "$\n";
    }
    $winnings = 0;
    echo "Total Bet: $bet $ \n";
    echo "Balance: $balance $\n";
    $userInput = strtoupper(readline("Raise bet: + / Decrease bet: - / Spin: press enter / Quit Game: Q/ your choice:"));
    switch ($userInput) {
        case '+':
            if ($bet + 10 <= $balance) {
                $bet += 10;
            } else {
                echo "Insufficient balance to increase bet." . PHP_EOL;
            }
            break;
        case '-':
            if ($bet - 10 > 0) {
                $bet -= 10;
            } else {
                echo "Insufficient balance to decrease bet." . PHP_EOL;
            }
            break;
        case '':
            if ($balance - $bet < 0) {
                break;
            }
            $twoDArray = createSlot($height, $width, $twoDArray);
            $balance -= $bet;
            $winAmount = checkWin($height, $width, $twoDArray, $bet);
            if ($winAmount !== 0) {
                echo "Winnings: " . $winAmount . "\n";
                $balance += $winAmount;
                $winnings = $winAmount;
            }
            break;
        case 'Q':
            echo "Quit Game" . PHP_EOL;
            exit();
        default:
            echo "Invalid choice" . PHP_EOL;
    }
}

<?php

require_once 'vendor/autoload.php';


use Oopproj\Admin;
use Oopproj\Account;
use Oopproj\User;
use Oopproj\Word;
use Oopproj\Medium;
use Oopproj\Easy;
use Oopproj\Hard;
use Oopproj\Game;


session_start();
$admin = new Admin("admin", "admin", "het werkt");
$medium1 = new Medium("water");
$medium2 = new Medium("toren");
$medium3 = new Medium("kamer");


if (isset($_SESSION['users'])) {
    Account::$users = $_SESSION['users'];
}
if (isset($_SESSION['words'])) {
    Word::$words = $_SESSION['words'];
}
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
} else {
    $user = null;
}


$template = new Smarty();
$template->setTemplateDir('template');


if (isset($_GET['action'])) {
    $action = $_GET['action'];
} else {
    $action = null;
}


switch ($action) {
    case "registerForm":
        $template->display('register.tpl');
        break;

    case "register":
        if (!empty($_POST["username"]) && !empty($_POST['password1']) && !empty($_POST["password2"])) {
            $usernameExists = false;
            foreach (Account::$users as $accountinfo) {
                if ($accountinfo->getName() === $_POST["username"]) {
                    $usernameExists = true;
                    break;
                }
            }
            if ($usernameExists) {
                $template->assign("registerNoti", "Username already exists");
                $template->display("register.tpl");
            } elseif ($_POST['password1'] === $_POST['password2']) {
                // If the username doesn't exist, and passwords match, add the user
                $user = new User($_POST['username'], $_POST['password1']);
                $template->assign("registerSucces", "Your account has been created, you can now login");
                $template->display("login.tpl");
            } else {
                $template->assign("registerNoti", "Passwords do not match, make sure they are the same");
                $template->display("register.tpl");
            }
        } else {
            $template->assign("registerNoti", "Please fill in all fields");
            $template->display("register.tpl");
        }
        break;


    case "loginForm":
        $template->display('login.tpl');
        break;

    case "login":
        $usernameExists = false;
        if (!empty($_POST["username"]) && !empty($_POST['password1'])) {
            foreach (Account::$users as $accountinfo) {
                if ($accountinfo->getName() === $_POST["username"]) {
                    $usernameExists = true;
                    $user = $accountinfo;
                    if (password_verify($_POST["password1"], $accountinfo->getPassword())) {
                        $_SESSION['user'] = $user;
                        if ($accountinfo instanceof Admin) {
                            $_SESSION['role'] = "admin";
                            $template->assign("loginSucces", "Logged in succesfull");
                            $template->display('user.tpl');

                        } elseif ($accountinfo instanceof User) {
                            $_SESSION['role'] = "user";
                            $template->assign("loginSucces", "Logged in succesfull");
                            $template->display('user.tpl');
                        }
                    } else {
                        $template->assign("loginError", "Username or password is incorrect");
                        $template->display('login.tpl');
                    }
                }
            }
        } else {
            $template->assign("loginError", "Please fill in all fields");
            $template->display('login.tpl');
            break;
        }

        if (!$usernameExists) {
            $template->assign("loginError", "Username or password is incorrect");
            $template->display('login.tpl');
        }

        break;

    case "dashboard":
        $template->display('user.tpl');
        break;

    case "wordForm":
        if (isset ($_SESSION['role'])) {
            if ($_SESSION['role'] == "admin") {
                $template->display('addWords.tpl');
            } else {
                $template->assign("loginError", "You are not allowed to add words");
                $template->display('login.tpl');
            }
        } else {
            $template->assign("loginError", "You are not allowed to add words");
            $template->display('login.tpl');
        }
        break;

    case "addWord":
        if (!empty($_POST['word'])) {
            $wordExists = false;
            if (strlen($_POST['word']) === 5) {
                foreach (Word::$words as $word) {
                    if ($word->getName() === $_POST['word']) {
                        $wordExists = true;
                        break;
                    }
                }
                if ($wordExists) {
                    $template->assign("wordError", value: $_POST['word'] . " already exists");
                } else {
                    new Medium($_POST['word']);
                    $template->assign("wordSucces", value: $_POST['word'] . " has been added");
                }
            } else {
                $template->assign("wordError", "Word must be 5 characters long");
            }
            $template->display('addWords.tpl');
        } else {
            $template->assign("wordError", "Please fill in the field");
        }
//        else if (!in_array($_POST['word'], Word::$words)) {
//            $template->assign("wordError", value: $_POST['word'] . " already exists");
//            $template->display('addWords.tpl');
//        } else if (!empty($_POST['word'])) {
//            new Medium($_POST['word']);
//            $template->assign("wordSucces", value: $_POST['word'] . " has been added");
//        } else {
//            $template->assign("wordError", "Please fill in the field");
//        }

        break;

    case "logoutForm":
        $template->display('logout.tpl');
        break;


    default:
        $template->display('index.tpl');
        break;

    case "logout":
        if (isset($_SESSION['role']) && isset($_SESSION['user'])) {
            unset($_SESSION['role']);
            unset($_SESSION['user']);
            $user = null;
            $template->assign("logoutSucces", "Logged out successfully");
        } else {
            $template->assign("logoutError", "Something went wrong");
        }
        $template->display('login.tpl');
        break; // Move this break statement to the end of the switch block

    case "process":
//        checks if start game button on home page is pressed
        if (isset($_POST['startGame'])) {
//            checks if user is signed in
//           if not user is redirected to login page
            if (!isset($_SESSION['user'])) {
                $template->assign("loginError", "Please login first");
                $template->display('login.tpl');
            } //            if user is signed in game is started
            else {
//                checks if a game has already been created
                if (!isset($_SESSION['game'])) {
                    $game = new Game();
                    $_SESSION['game'] = $game;
//                    debug code
                    echo 'een game session bestond nog niet dus ik heb een nieuwe gemaakt';
                } else {
//                    if hame has been set it will be unset and a new game will be created
                    unset($_SESSION['game']);
                    $game = new Game();
                    $_SESSION['game'] = $game;
//                    debug code
//                    echo 'er is een bestaande game session dus ik heb hem ge unset en een nieuwe aangemaakt';
                    unset($_SESSION['guessedWords']);
                }
                echo '<br>';
//                debug code
//                var_dump($_SESSION['game']);
//                debug code
                echo 'game is begonnen';
                echo '<br>';
                $template->display('game.tpl');

            }

//         if the button play game is never pressed and people still land on the page this will happen
        } else {
            $template->assign("selectDifficultyError", "Something went wrong");
            $template->display('user.tpl');
        }
        break;

    case "game":
        if (isset($_POST['answer'])) {
            if (!isset($_SESSION['gameStarted']) || $_SESSION['gameStarted'] !== true) {
                $_SESSION['guessedWords'] = array();  // Initialize an array to store guessed words
                $_SESSION['gameStarted'] = true;  // Mark the game session as started
            }
            $wordToGuess = $_SESSION['game']->getWordToGuess()->getName();
            $userGuess1 = $_POST['answer'];
// Store the user's input in a separate variable for display
            $displayedGuess = $userGuess1;
            $template->assign("game", $_SESSION['game']);
            echo "<br>";
            echo "<br>";
            echo "<br>";
            echo "<br>";
            if ($_SESSION['game']->getAttempts() > 1) {
//               Guessed the word
                if ($wordToGuess === $userGuess1) {
                    $user->addStreak();
                    $user->addWin();
                    $user->addLongestStreak();
                    $template->assign("gameSucces", "You guessed the word");
                    $action = "result";
                    $_SESSION['game']->setUsedAttempts(7 - $_SESSION['game']->getAttempts());
                    $_SESSION['guessedWords'][] = $userGuess1;
                    echo "It took you " . $_SESSION['game']->getUsedAttempts() . " attempts to guess the word";
                    echo "<div style='white-space: nowrap;'>";
                    foreach ($_SESSION['guessedWords'] as $guessedWord) {
                        // Display each letter in the guessed word with colors
                        $lettersStatus = array();  // Initialize lettersStatus array for each guessed word
                        echo "<br>";
                        // Display each letter in the guessed word with colors
                        for ($i = 0; $i < mb_strlen($guessedWord); $i++) {
                            $letter = mb_substr($guessedWord, $i, 1);
                            // Identify letters in the correct spot and mark them as 2
                            if (isset($wordToGuess[$i]) && $wordToGuess[$i] === $letter) {
                                echo "<span style='font-size: 25px;color: white;'>$letter</span>";
                                $lettersStatus[$letter] = 2;
                            } else {
                                // Identify letters in the word but not in the correct spot and mark them as 1
                                if (mb_strpos($wordToGuess, $letter) !== false) {
                                    echo "<span style='font-size: 25px; color: white;'>$letter</span>";
                                    $lettersStatus[$letter] = 1;
                                } else {
                                    echo "<span style='font-size: 25px;color: white;'>$letter</span>";
                                }
                            }
                        }
                    }
                    echo "</div>";
                    echo '<form method="POST" action="/index.php?action=process"> <button type="submit" name="startGame" value="medium"> play again</button></form>';
//                    echo "<span style='font-size: 25px;color: white;'> $wordToGuess</span>";
                    $template->display('result.tpl');

                } else {
//                    wrong answer section but attempts are still left
                    // Save the current guess to the guessed words session variable
                    $_SESSION['guessedWords'][] = $userGuess1;

                    // Display all guessed words from the session
                    echo "<div style='white-space: nowrap;'>";
                    foreach ($_SESSION['guessedWords'] as $guessedWord) {
                        // Display each letter in the guessed word with colors
                        $lettersStatus = array();  // Initialize lettersStatus array for each guessed word
                        echo "<br>";
                        // Display each letter in the guessed word with colors
                        for ($i = 0; $i < mb_strlen($guessedWord); $i++) {
                            $letter = mb_substr($guessedWord, $i, 1);

                            // Identify letters in the correct spot and mark them as 2
                            if (isset($wordToGuess[$i]) && $wordToGuess[$i] === $letter) {
                                echo "<span style='background-color: #22ff22;font-size: 25px;color: black;'>$letter</span>";
                                $lettersStatus[$letter] = 2;
                            } else if (mb_strpos($wordToGuess, $letter) !== false) {
                                // Identify letters in the word but not in the correct spot and mark them as 1
                                echo "<span style='background-color: yellow;font-size: 25px;color: black;'>$letter</span>";
                                $lettersStatus[$letter] = 1;
                            } else {
                                echo "<span style='background-color: red;font-size: 25px;color: black;'>$letter</span>";
                            }
                        }
                    }
                    echo "</div>";
                    $template->assign("gameError", "Wrong answer");
                    // Use the $displayedGuess variable for displaying the user's input
                    $template->assign("userGuess", $displayedGuess);
                    $template->display('game.tpl');
                }
//                you lost the game section
            } else {
                $_SESSION['guessedWords'][] = $userGuess1;
//                clears streak
                $user->clearStreak();
                $user->addLost();
//                $user->addGame();
                $template->assign("gameError", "You have no attempts left, you lost the game");
                $_SESSION['game']->setUsedAttempts(7 - $_SESSION['game']->getAttempts());
                echo "The  " . $_SESSION['game']->getUsedAttempts() . " attempts to guess the word where";
                echo "<div style='white-space: nowrap;'>";

                if (isset($_SESSION['guessedWords'])){
                    foreach ($_SESSION['guessedWords'] as $guessedWord) {
                        // Display each letter in the guessed word with colors
                        $lettersStatus = array();  // Initialize lettersStatus array for each guessed word
                        echo "<br>";
                        // Display each letter in the guessed word with colors
                        for ($i = 0; $i < mb_strlen($guessedWord); $i++) {
                            $letter = mb_substr($guessedWord, $i, 1);

                            // Identify letters in the correct spot and mark them as 2
                            if (isset($wordToGuess[$i]) && $wordToGuess[$i] === $letter) {
                                echo "<span style='font-size: 25px;color: white;'>$letter</span>";
                                $lettersStatus[$letter] = 2;
                            } else {
                                // Identify letters in the word but not in the correct spot and mark them as 1
                                if (mb_strpos($wordToGuess, $letter) !== false) {
                                    echo "<span style='font-size: 25px; color: white;'>$letter</span>";
                                    $lettersStatus[$letter] = 1;
                                } else {
                                    echo "<span style='font-size: 25px;color: white;'>$letter</span>";
                                }
                            }
                        }
                    }
                }
                echo "</div>";
                echo '<form method="POST" action="/index.php?action=process"> <button type="submit" name="startGame" value="medium"> play again</button></form>';
                $template->display('result.tpl');

            }
            echo $_SESSION['game']->setAttempts($_SESSION['game']->getAttempts() - 1);
        } else {
            $template->assign("selectDifficultyError", "Something went wrong");
            $template->display('user.tpl');
        }
        break;

    case "scores":
        if (isset($_SESSION['role']) && isset($_SESSION['user'])) {
            $template->display('scores.tpl');
        } else {
            $template->assign("scores", "You are not allowed to view this page");
            $template->display('user.tpl');
        }
        break;

}

$_SESSION['user'] = $user;
//$_SESSION["users"] = Account::$users;
//$_SESSION["words"] = Word::$words;
//echo "<pre class='mt-5 pt-1'>";
echo '<pre>';
////debugging
var_dump($_SESSION);
//var_dump(Word::$words);
//var_dump($_SESSION['game']);
//var_dump($_SESSION['user']);
echo "</pre>";
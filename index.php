<?php

require_once 'vendor/autoload.php';


use Oopproj\Admin;
use Oopproj\Account;
use Oopproj\Db;
use Oopproj\User;
use Oopproj\Word;
use Oopproj\Medium;
use Oopproj\Easy;
use Oopproj\Hard;
use Oopproj\Game;


session_start();

$db = new Db();


//if (isset($_SESSION['words'])) {
//    Word::$words = $_SESSION['words'];
//}
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
} else {
    $user = null;
}

if (isset($_SESSION['game'])) {
    $game = $_SESSION['game'];
} else {
    $game = null;
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
                if (Account::nameExists($_POST["username"]) == true) {
                    $usernameExists = true;
                    break;
                }
            }
            if ($usernameExists) {
                $template->assign("registerNoti", "Username already exists");
                $template->display("register.tpl");
            } elseif ($_POST['password1'] === $_POST['password2']) {
                // If the username doesn't exist, and passwords match, add the user
                $users = new User(false, $_POST['username'], $_POST['password1'], false);
                User::register($_POST['username'], password_hash($_POST['password1'], PASSWORD_BCRYPT));
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

//redirect to login
    case "loginForm":
        if (isset($_SESSION['user'])) {
            $template->assign("loginError", "You are already logged in");
            $template->display('user.tpl');
            break;
        }
        $template->display('login.tpl');
        break;

    case "login":
        $usernameExists = false;
        if (!empty($_POST["username"]) && !empty($_POST['password1'])) {
            if (Account::nameExists($_POST["username"])) {
                $usernameExists = true;
//                    $user = $accountinfo;
                if (Account::passwordVerify($_POST["username"], $_POST['password1'])) {
                    if (Account::signIn($_POST["username"]) !== null) {
                        $_SESSION["user"] = Account::signIn($_POST["username"]);
                        if (User::getRole($_POST["username"])) {
                            $_SESSION["role"] = "admin";
                        } else {
                            $_SESSION["role"] = "user";
                        }
                        $template->assign("loginSucces", "Logged in succesfull");
                        $template->display('user.tpl');
                    }
                } else {
                    $template->assign("loginError", "Username or password is incorrect");
                    $template->display('login.tpl');
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
            if (strlen($_POST['word']) === 5) {
                if (Word::difference($_POST['word'])) {
                    $template->assign("wordError", value: $_POST['word'] . " already exists");
                } else {
                    $template->assign("wordSucces", value: $_POST['word'] . " has been added");
                    Word::addWord($_POST['word']);
                }

            } else {
                $template->assign("wordError", "Word must be 5 characters long");
            }
            $template->display('addWords.tpl');
            break;
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

    case "deleteForm":
        $template->display('deleteAccount.tpl');
        break;

    case "delete":
        if (isset($_SESSION['role']) && isset($_SESSION['user'])) {
            $_SESSION['user']->deleteUserGames();
            $_SESSION['user']->deleteAccount();
            unset($_SESSION['role']);
            unset($_SESSION['user']);
            $user = null;
            $template->assign("logoutSucces", "Logged out successfully");
        } else {
            $template->assign("logoutError", "Something went wrong");
        }
        $template->display('login.tpl');
        break;

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
//                die(var_dump(Game::checkCompleted()));
                if (Game::checkCompleted() != null) {
                    if (isset($_SESSION['game'])) {
                        unset($_SESSION['game']);
                        unset($_SESSION['guessedWords']);
                    }
//                    if game has been completed it will be unset and a new game will be created
                    $game = new Game();
                    Game::createGame();
                    $wordToGuess = Word::getNameFromId(Game::randomWord());
//                    debug code
                    echo 'een game session bestond nog niet dus ik heb een nieuwe gemaakt';
                    echo "<br>";
                    echo "<br>";
                    echo "<br>";
                    echo "<br>";
                } else {
//                    if game has been set it will be unset and a new game will be created
                    unset($_SESSION['game']);
                    unset($_SESSION['guessedWords']);

                    $DBGuessedWords = Game::getGuessedWords();
                    $game = new Game();
                    $game->setWordToGuess(Game::getWordToGuess(Game::getGameId()));
                    $wordToGuess = Game::getWordToGuess(Game::getGameId());
                    echo "<br>";
                    echo "<br>";
                    echo "<br>";
                    echo "<br>";
//                    die(var_dump($DBGuessedWords));
                    echo "<div style='white-space: nowrap;'>";
                    foreach ($DBGuessedWords as $DBGuessedWord) {
                        $guessedWord = $DBGuessedWord['name'];
                        $_SESSION["guessedWords"][] = $guessedWord;
                        $game->addGuessedWords($guessedWord);
                        //                  Display each letter in the guessed word with colors
                        $lettersStatus = array();  // Initialize lettersStatus array for each guessed word
                        echo "<br>";
                        //                      Display each letter in the guessed word with colors
                        $yellowMarkedLetters = array();

                        for ($i = 0; $i < mb_strlen($guessedWord); $i++) {
                            $letter = mb_substr($guessedWord, $i, 1);

                            if (isset($wordToGuess[$i]) && $wordToGuess[$i] === $letter) {
                                echo "<span style='background-color: #22ff22;font-size: 25px;color: black;'>$letter</span>";
                                $lettersStatus[$letter] = 2;
                            } else {
                                // Check if the letter is in the wordToGuess and has not been marked green or yellow
                                if (
                                    mb_strpos($wordToGuess, $letter) !== false &&
                                    (!isset($lettersStatus[$letter]) || $lettersStatus[$letter] !== 2) &&
                                    !in_array($letter, $yellowMarkedLetters)
                                ) {
                                    echo "<span style='background-color: yellow;font-size: 25px; color: black;'>$letter</span>";
                                    $lettersStatus[$letter] = 1; // Mark the letter as yellow
                                    $yellowMarkedLetters[] = $letter; // Mark the letter as yellow in the tracking array
                                } else {
                                    echo "<span style='background-color: red;font-size: 25px;color: black;'>$letter</span>";
                                }
                            }
                        }
                    }
//                    debug code
//                    echo 'er is een bestaande game session dus ik heb hem ge unset en een nieuwe aangemaakt';
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

            $wordToGuess = Game::getWordToGuess(Game::getGameId());

            if (Word::wordInDatabase($_POST['answer'])) {
                $game->setWordToGuess(Word::getIdFromName($_POST['answer']));
//                  echo "<pre>";
//                  die(var_dump($_SESSION['game']));
                if (!isset($_SESSION['gameStarted']) || $_SESSION['gameStarted'] !== true) {
//                  $_SESSION['guessedWords'] = array();  // Initialize an array to store guessed words
                    $_SESSION['gameStarted'] = true;  // Mark the game session as started
                }
//                  $wordToGuess = Word::getNameFromId(Game::randomWord());
                //              die($game->getWordToGuesss());

                //              Store the user's input in a separate variable for display
                $userGuess1 = strtolower($_POST['answer']);
                $displayedGuess = $userGuess1;
                $template->assign("game", $_SESSION['game']);

                if ($game->getAttempts() > 1) {
                    //               Guessed the word
                    if ($wordToGuess === $userGuess1) {
                        Game::setGameWon();
                        $game->setGameWon();
                        $user->addGame($game);
                        $user->addStreak();
                        $user->addWin();
                        $user->addLongestStreak();

                        //                    $user->addGame();
                        $template->assign("gameSucces", "You guessed the word");
                        $action = "result";
                        $game->setUsedAttempts(7 - $game->getAttempts());
                        $_SESSION['guessedWords'][] = $userGuess1;
                        $game->addGuessedWords($userGuess1);
                        Game::addGuessedWord($userGuess1);
                        echo "It took you " . $game->getUsedAttempts() . " attempts to guess the word";
                        echo "<div style='white-space: nowrap;'>";
                        foreach ($_SESSION['guessedWords'] as $guessedWord) {
                            //                  Display each letter in the guessed word with colors
                            $lettersStatus = array();  // Initialize lettersStatus array for each guessed word
                            echo "<br>";
                            //                      Display each letter in the guessed word with colors
                            $yellowMarkedLetters = array();
                            for ($i = 0; $i < mb_strlen($guessedWord); $i++) {
                                $letter = mb_substr($guessedWord, $i, 1);

                                if (isset($wordToGuess[$i]) && $wordToGuess[$i] === $letter) {
                                    echo "<span style='background-color: #22ff22;font-size: 25px;color: black;'>$letter</span>";
                                    $lettersStatus[$letter] = 2;
                                } else {
                                    // Check if the letter is in the wordToGuess and has not been marked green or yellow
                                    if (
                                        mb_strpos($wordToGuess, $letter) !== false &&
                                        (!isset($lettersStatus[$letter]) || $lettersStatus[$letter] !== 2) &&
                                        !in_array($letter, $yellowMarkedLetters)
                                    ) {
                                        echo "<span style='background-color: yellow;font-size: 25px; color: black;'>$letter</span>";
                                        $lettersStatus[$letter] = 1; // Mark the letter as yellow
                                        $yellowMarkedLetters[] = $letter; // Mark the letter as yellow in the tracking array
                                    } else {
                                        echo "<span style='background-color: red;font-size: 25px;color: black;'>$letter</span>";
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
                        $game->addGuessedWords($userGuess1);
                        Game::addGuessedWord($userGuess1);


//                      Display all guessed words from the session
                        echo "<div style='white-space: nowrap;'>";
                        foreach ($_SESSION['guessedWords'] as $guessedWord) {
                            //                  Display each letter in the guessed word with colors
                            $lettersStatus = array();  // Initialize lettersStatus array for each guessed word
                            echo "<br>";
                            //                      Display each letter in the guessed word with colors
                            $yellowMarkedLetters = array();

                            for ($i = 0; $i < mb_strlen($guessedWord); $i++) {
                                $letter = mb_substr($guessedWord, $i, 1);

                                if (isset($wordToGuess[$i]) && $wordToGuess[$i] === $letter) {
                                    echo "<span style='background-color: #22ff22;font-size: 25px;color: black;'>$letter</span>";
                                    $lettersStatus[$letter] = 2;
                                } else {
                                    // Check if the letter is in the wordToGuess and has not been marked green or yellow
                                    if (
                                        mb_strpos($wordToGuess, $letter) !== false &&
                                        (!isset($lettersStatus[$letter]) || $lettersStatus[$letter] !== 2) &&
                                        !in_array($letter, $yellowMarkedLetters)
                                    ) {
                                        echo "<span style='background-color: yellow;font-size: 25px; color: black;'>$letter</span>";
                                        $lettersStatus[$letter] = 1; // Mark the letter as yellow
                                        $yellowMarkedLetters[] = $letter; // Mark the letter as yellow in the tracking array
                                    } else {
                                        echo "<span style='background-color: red;font-size: 25px;color: black;'>$letter</span>";
                                    }
                                }
                            }
                        }
                        echo "</div>";
                        $template->assign("gameError", "Wrong answer");
//                  Use the $displayedGuess variable for displaying the user's input
                        $template->assign("userGuess", $displayedGuess);
                        $template->display('game.tpl');
                    }
//                you lost the game section
                } else {
                    $_SESSION['guessedWords'][] = $userGuess1;
                    $game->addGuessedWords($userGuess1);
                    Game::addGuessedWord($userGuess1);
//                clears streak
                    $user->clearStreak();
                    Game::setGameLost(Game::getGameId());
                    $user->addLost();
                    $user->addGame($game);
                    $template->assign("gameError", "You have no attempts left, you lost the game the word was " . $wordToGuess);
                    $game->setUsedAttempts(7 - $game->getAttempts());
                    echo "The  " . $_SESSION['game']->getUsedAttempts() . " attempts to guess the word";
                    echo "<div style='white-space: nowrap;'>";

                    if (isset($_SESSION['guessedWords'])) {
                        foreach ($_SESSION['guessedWords'] as $guessedWord) {
                            //                  Display each letter in the guessed word with colors
                            $lettersStatus = array();  // Initialize lettersStatus array for each guessed word
                            echo "<br>";
                            //                      Display each letter in the guessed word with colors
                            $yellowMarkedLetters = array();
                            for ($i = 0; $i < mb_strlen($guessedWord); $i++) {
                                $letter = mb_substr($guessedWord, $i, 1);

                                if (isset($wordToGuess[$i]) && $wordToGuess[$i] === $letter) {
                                    echo "<span style='background-color: #22ff22;font-size: 25px;color: black;'>$letter</span>";
                                    $lettersStatus[$letter] = 2;
                                } else {
                                    // Check if the letter is in the wordToGuess and has not been marked green or yellow
                                    if (
                                        mb_strpos($wordToGuess, $letter) !== false &&
                                        (!isset($lettersStatus[$letter]) || $lettersStatus[$letter] !== 2) &&
                                        !in_array($letter, $yellowMarkedLetters)
                                    ) {
                                        echo "<span style='background-color: yellow;font-size: 25px; color: black;'>$letter</span>";
                                        $lettersStatus[$letter] = 1; // Mark the letter as yellow
                                        $yellowMarkedLetters[] = $letter; // Mark the letter as yellow in the tracking array
                                    } else {
                                        echo "<span style='background-color: red;font-size: 25px;color: black;'>$letter</span>";
                                    }
                                }
                            }
                        }
                    }
                    echo "</div>";
                    echo '<form method="POST" action="/index.php?action=process"> <button type="submit" name="startGame" value="medium"> play again</button></form>';
                    $template->display('result.tpl');
                }

                $game->setAttempts($game->getAttempts() - 1);
            } else {
                echo "<div style='white-space: nowrap;'>";
                foreach ($_SESSION['guessedWords'] as $guessedWord) {
                    //                  Display each letter in the guessed word with colors
                    $lettersStatus = array();  // Initialize lettersStatus array for each guessed word
                    echo "<br>";
                    //                      Display each letter in the guessed word with colors
                    $yellowMarkedLetters = array();
                    for ($i = 0; $i < mb_strlen($guessedWord); $i++) {
                        $letter = mb_substr($guessedWord, $i, 1);

                        if (isset($wordToGuess[$i]) && $wordToGuess[$i] === $letter) {
                            echo "<span style='background-color: #22ff22;font-size: 25px;color: black;'>$letter</span>";
                            $lettersStatus[$letter] = 2;
                        } else {
                            // Check if the letter is in the wordToGuess and has not been marked green or yellow
                            if (
                                mb_strpos($wordToGuess, $letter) !== false &&
                                (!isset($lettersStatus[$letter]) || $lettersStatus[$letter] !== 2) &&
                                !in_array($letter, $yellowMarkedLetters)
                            ) {
                                echo "<span style='background-color: yellow;font-size: 25px; color: black;'>$letter</span>";
                                $lettersStatus[$letter] = 1; // Mark the letter as yellow
                                $yellowMarkedLetters[] = $letter; // Mark the letter as yellow in the tracking array
                            } else {
                                echo "<span style='background-color: red;font-size: 25px;color: black;'>$letter</span>";
                            }
                        }
                    }
                }
                echo "</div>";

                $template->assign("gameError", "Word does not exist in database");
                $template->display('game.tpl');
            }
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

$_SESSION['game'] = $game;
$_SESSION["users"] = Account::$users;
//$_SESSION["words"] = Word::$words;
//echo "<pre class='mt-5 pt-1'>";
echo '<pre>';
//////debugging
//var_dump($_SESSION);
//var_dump(Word::$words);
//var_dump($_SESSION['game']);
//var_dump($_SESSION['role']);
var_dump($_SESSION['user']);
//echo "</pre>";



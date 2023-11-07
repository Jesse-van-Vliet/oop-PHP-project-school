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
                    $user = $accountinfo->getName();
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
        if (isset ($_SESSION['role'])){
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
         if (isset($_SESSION['role']) && isset($_SESSION['user'])){
            unset($_SESSION['role']);
            unset($_SESSION['user']);
            $template->assign("logoutSucces", "Logged out succesfull");
        } else {
            $template->assign("logoutError", "Something went wrong");
        }
        $template->display('login.tpl');
        break;

    case "game":
//        checks if start game button on home page is pressed
        if (isset($_POST['startGame'])) {
//            checks if user is signed in
//           if not user is redirected to login page
            if (!isset($_SESSION['user'])){
                $template->assign("loginError", "Please login first");
                $template->display('login.tpl');
            }
//            if user is signed in game is started
            else {
//                checks if a game has already been created
                if (!isset($_SESSION['game'])) {
                    $game = new Game();
                    $_SESSION['game'] = $game;
//                    debug code
                    echo 'een game session bestond nog niet dus ik heb een nieuwe gemaakt';
                }
                else {
//                    if hame has been set it will be unset and a new game will be created
                    unset($_SESSION['game']);
                    $game = new Game();
                    $_SESSION['game'] = $game;
//                    debug code
                    echo 'er is een bestaande game session dus ik heb hem ge unset en een nieuwe aangemaakt';
                }
                echo '<br>';
//                debug code
                var_dump($_SESSION['game']);
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

}


$_SESSION["users"] = Account::$users;
$_SESSION["words"] = Word::$words;

//echo "<pre class='mt-5 pt-1'>";
//
////debugging
//var_dump($_SESSION);
//var_dump(Word::$words);
//
//echo "</pre>";
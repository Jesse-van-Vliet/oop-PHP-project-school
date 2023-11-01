<?php

require_once 'vendor/autoload.php';


use Oopproj\Admin;
use Oopproj\Account;
use Oopproj\User;
use Oopproj\Word;
use Oopproj\Medium;
use Oopproj\Easy;
use Oopproj\Hard;


session_start();
$admin = new Admin("admin", "admin", "het werkt");
$easy = new  Easy("kaas");
$medium = new Medium("water");
$hard = new Hard("koffie");

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

echo "<pre>";

//debugging
var_dump($_SESSION);



echo "</pre>";

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
                $user = new \Oopproj\User($_POST['username'], $_POST['password1']);
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
        }

        if (!$usernameExists) {
            $template->assign("loginError", "Username or password is incorrect");
            $template->display('login.tpl');
        }

        break;

        case "dashboard":
            $template->display('user.tpl');
            break;

    case "logoutForm":
        $template->display('logout.tpl');
        break;


    default:
        $template->display('layout.tpl');
        break;

    case "logout":

        if (isset($_SESSION['role']) && isset($_SESSION['user'])){
            unset($_SESSION['role']);
            unset($_SESSION['user']);
            $template->assign("logoutSucces", "Logged out succesfull");
            $template->display('login.tpl');
        } else {
            $template->assign("logoutError", "Something went wrong");
            $template->display('login.tpl');
        }
        break;
}


$_SESSION["users"] = Account::$users;
$_SESSION["words"] = Word::$words;


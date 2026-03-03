<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require('global.inc.php');
require('src/API.php');
require('src/Nav.php');
require('src/Footer.php');

use STiBaRC\STiBaRC;

$api = new STiBaRC\API($apiTarget, true);

$error = false;

if (!empty($_POST)) {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    if (empty($username) || empty($password)) {
        $error = "Username and password must be entered!";
    } else {

        $loginResponse = $api->login($username, $password);

        if ($loginResponse['error'] && $loginResponse['errorText']) {
            $error = $loginResponse['errorText'] . ' : ' . $loginResponse['error'];
        } else {
            header('Location: ./');
        }
    }
}

?>
<!DOCTYPE html>
<html>

<head>
    <title>STiBaRC</title>
    <link rel="stylesheet" href="./index.css">
</head>

<body>

    <?php

    $nav = new STiBaRC\Nav();
    echo $nav->nav();
    ?>
    <main>
        <div class="login">
            <h2>Login</h2>
            <form method="POST">
                <?= $error ? '<div class="errorBlock">' . $error . '</div>' : ''; ?>
                <div class="row">
                    <label for="username">Username:</label>
                    <input name="username" placeholder="Username" autofocus>
                </div>
                <div class="row">
                    <label for="password">Password:</label>
                    <input name="password" type="password" placeholder="Password">
                </div>
                <div class="row">
                    <button class="primary" type="submit">Login</button>
                </div>
            </form>
            <div class="row">
                <a class="loginWith" href="https://stibarc.com/oauth/?client_id=b5543b27a9fac3ad509d0168cee7d8cf&response_type=token&scope=all">
                    <img src="./img/Login-with-STiBaRC.png" alt="Login with STiBaRC" title="Login with STiBaRC OAuth">
                </a>
            </div>
        </div>
    </main>

    <?php
    $footer = new STiBaRC\Footer();
    echo $footer->footer();
    ?>

</body>

</html>
<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require('global.inc.php');
require('src/API.php');
require('src/Nav.php');

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

    <h2>Login</h2>
    <form method="POST">
        <?= $error ? '<div class="errorBlock">' . $error . '</div>' : ''; ?>
        <input name="username" placeholder="Username" autofocus>
        <input name="password" type="password" placeholder="Password">
        <button type="submit">Login</button>
    </form>

</body>

</html>
<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require('global.inc.php');
require('src/API.php');

use STiBaRC\STiBaRC;

$api = new STiBaRC\API($apiTarget, true);

$error = false;

$loggedOut = $api->logout();

if ($loggedOut) {
    header('Location: ./');
} else {
    $error = "Error logging out";
}

?>
<!DOCTYPE html>
<html>

<head>
    <title>STiBaRC</title>
    <link rel="stylesheet" href="./index.css">
</head>

<body>

    <h2>Logging out</h2>
    <?= $error ? '<div class="errorBlock">' . $error . '</div>' : ''; ?>

</body>

</html>
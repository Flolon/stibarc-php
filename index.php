<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require('global.inc.php');
require('src/API.php');
require('src/Nav.php');
require('src/PostBlock.php');

use STiBaRC\STiBaRC;

$api = new STiBaRC\API($apiTarget, true);

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

    if ($api->getAnnouncement()) {
        echo '<div class="announcement">' . htmlspecialchars($api->getAnnouncement()) . '</div>';
    }

    foreach ($api->getPosts() as $postData) {
        $postHtml = new STiBaRC\postBlock($postData, $showAttachments);
        echo $postHtml->post();
    }
    ?>

</body>

</html>
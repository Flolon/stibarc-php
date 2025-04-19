<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require('src/API.php');
require('src/PostBlock.php');

use STiBaRC\STiBaRC;

$api = new STiBaRC\API("development", true);

?>
<!DOCTYPE html>
<html>

<head>
    <title>STiBaRC</title>
    <link rel="stylesheet" href="./index.css">
</head>

<body>

    <div class="announcement"><?= $api->getAnnouncement(); ?></div>

    <?php foreach ($api->getPosts() as $postData) {
        $postHtml = new STiBaRC\PostBlock($postData);
        echo $postHtml->post();
     } ?>

</body>

</html>
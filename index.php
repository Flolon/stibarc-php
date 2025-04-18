<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require('src/API.php');

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

    <?php foreach ($api->getPosts() as $post) {
        $poster = $post->poster;
        $date = strtotime($post->date);
    ?>
        <div class="postblock">
            <div class="title"><?= $post->title ?></div>
            <a class="userlink" title="<?= $poster->verified ? "Verified" : $poster->username ?>">
                <img class="pfp" width="25px" src="<?= $poster->pfp ?>">
                <span class="username">
                    <?= $poster->username ?>
                    <span class="pronouns"><?= $poster->pronouns ? '(' . $poster->pronouns . ')' : "" ?></span>
                </span>
            </a>
            <div class="date" title="<?= $post->date ?>">
                <?= date("m/d/y h:i:s A", $date) ?>
            </div>
        </div>
    <?php } ?>

</body>

</html>
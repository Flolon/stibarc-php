<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require('src/API.php');

use STiBaRC\STiBaRC;

$api = new STiBaRC\API("development", true);

echo $api->getAnnouncement();

foreach ($api->getPosts() as $post) {
    echo $post->title;
}

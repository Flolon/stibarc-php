<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require('src/API.php');
require('src/Nav.php');
require('src/Post.php');
require('src/Attachment.php');

use STiBaRC\STiBaRC;

$api = new STiBaRC\API("development", true);

$postId = $_GET["id"];

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

		$postData = $api->getPost($postId);
        $postHtml = new STiBaRC\Post($postData);
        echo $postHtml->post();

		if ($postData->attachments) {
			foreach ($postData->attachments as $attachment) {
				$attachmentObj = new STiBaRC\Attachment($attachment);
				echo $attachmentObj->attachmentBlock();
			}
		}
    ?>

</body>

</html>
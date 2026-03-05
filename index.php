<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require('global.inc.php');
require('src/API.php');
require('src/Nav.php');
require('src/Footer.php');
require('src/PostBlock.php');

use STiBaRC\STiBaRC;

$api = new STiBaRC\API($apiTarget, true);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title>STiBaRC</title>
	<link rel="stylesheet" href="./index.css">
</head>

<body>

	<?php

	$nav = new STiBaRC\Nav();
	echo $nav->nav();

	if ($api->getAnnouncement())
		echo '<div class="announcement">' . htmlspecialchars($api->getAnnouncement()) . '</div>';

	if (!empty($_GET["lastSeenGlobalPost"])) {
		$lastSeenGlobalPost = $_GET["lastSeenGlobalPost"];
	} else {
		$lastSeenGlobalPost = false;
	}

	foreach ($api->getPosts(lastSeenGlobalPost: $lastSeenGlobalPost) as $postData) {
		$postHtml = new STiBaRC\PostBlock($postData, $showAttachments);
		echo $postHtml->post();
		$lastSeenGlobalPost = $postData->id;
	}
	// } else {
	// 	$config["lastSeenGlobalPost"] = $lastSeenGlobalPost;
	// 	foreach ($api->getPosts($config) as $postData) {
	// 		$postHtml = new STiBaRC\PostBlock($postData, $showAttachments);
	// 		echo $postHtml->post();
	// 		$lastSeenGlobalPost = $postData->id;
	// 	}
	// }

	if ($lastSeenGlobalPost) {
		echo '
	<div class="centerBlock">
		<a class="button primary" href="?lastSeenGlobalPost=' . $lastSeenGlobalPost - 20 . '"><</a>
		<a class="button primary" href="?lastSeenGlobalPost=' . $lastSeenGlobalPost . '">></a>
	</div>		
		';
	}

	$footer = new STiBaRC\Footer();
	echo $footer->footer();
	?>

</body>

</html>
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
	$feed = false;
	$lastSeenGlobalPost = false;
	$lastSeenFollowedPost = false;
	if (!empty($_GET["feed"]))
		$feed = $_GET["feed"];
	if (!empty($_GET["lastSeenGlobalPost"]))
		$lastSeenGlobalPost = $_GET["lastSeenGlobalPost"];
	if (!empty($_GET["lastSeenFollowedPost"]))
		$lastSeenFollowedPost = $_GET["lastSeenFollowedPost"];

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
	?>
	<div class="pageTabs">
		<ul>
			<li><a class="tab <?= ($feed == "global" || !$feed) ? 'active' : '' ?>" href="?feed=global" title="Global feed">Global</a></li>
			<li><a class="tab <?= ($feed == "following") ? 'active' : '' ?>" href="?feed=following" title="Following feed">Following</a></li>
		</ul>
	</div>
	<?php

	$posts = $api->getPosts(lastSeenGlobalPost: $lastSeenGlobalPost, lastSeenFollowedPost: $lastSeenFollowedPost);

	if (!$feed || $feed == "global") {
		foreach ($posts->globalPosts as $postData) {
			$postHtml = new STiBaRC\PostBlock($postData, $showAttachments);
			echo $postHtml->post();
			$lastSeenGlobalPost = $postData->id;
		}
		echo '
	<div class="centerBlock">
		<a class="button primary" href="?feed=global" title="Latest posts"><<</a>
		<a class="button primary" href="?feed=global&lastSeenGlobalPost=' . ($lastSeenGlobalPost + 41) . '" title="Newer posts"><</a>
		<a class="button primary" href="?feed=global&lastSeenGlobalPost=' . $lastSeenGlobalPost . '" title="Older posts">></a>
		<a class="button primary" href="?feed=global&lastSeenGlobalPost=20" title="Oldest posts">>></a>
	</div>		
		';
	}

	if (empty($_SESSION['sess']) && $feed == "following") {
		echo '<h2>Not logged in</h2>';
		echo '<p>Login in to view followed users\' posts.</p>';
		echo '<div><a class="button primary" href="./login.php">Login</a></div>';
	} else if ($feed == "following") {
		foreach ($posts->followedPosts as $postData) {
			$postHtml = new STiBaRC\PostBlock($postData, $showAttachments);
			echo $postHtml->post();
			$lastSeenFollowedPost = $postData->id;
		}
		echo '
	<div class="centerBlock">
		<a class="button primary" href="?feed=following" title="Latest posts"><<</a>
		<a class="button primary" href="?feed=following&lastSeenFollowedPost=' . ($lastSeenFollowedPost + 41) . '" title="Newer posts"><</a>
		<a class="button primary" href="?feed=following&lastSeenFollowedPost=' . $lastSeenFollowedPost . '" title="Older posts">></a>
		<a class="button primary" href="?feed=following&lastSeenFollowedPost=20" title="Oldest posts">>></a>
	</div>		
		';
	}

	$footer = new STiBaRC\Footer();
	echo $footer->footer();
	?>

</body>

</html>
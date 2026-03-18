<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require('global.inc.php');
require('src/API.php');
require('src/Nav.php');
require('src/Footer.php');
require('src/Post.php');
require('src/Comment.php');

use STiBaRC\STiBaRC;

$api = new STiBaRC\API($apiTarget, true);

$error = false;
$action = false;
$id = false;
$commentId = false;
if (!empty($_GET["action"]))
	$action = $_GET["action"];
if (!empty($_GET["id"]))
	$id = $_GET["id"];
if (!empty($_GET["commentId"]))
	$commentId = $_GET["commentId"];
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title>STiBaRC</title>
	<link rel="icon" type="image/png" href="/img/icon.png">
	<!-- Open Graph -->
	<meta property="og:title" content="STiBaRC" />
	<meta property="og:description" content="STiBaRC PHP Client" />
	<meta property="og:type" content="website" />
	<meta property="og:url" content="<?= htmlspecialchars($url, ENT_QUOTES, 'UTF-8') ?>" />
	<meta property="og:site_name" content="STiBaRC">
	<meta property="og:logo" content="https://stibarc.sopaws.com/img/icon.png">
	<meta name="theme-color" content="#3ea1b1" />
	<meta name="application-name" content="STiBaRC">
	<meta name="twitter:site" content="STiBaRC" />
	<meta name="twitter:title" content="STiBaRC" />
	<meta name="twitter:description" content="STiBaRC PHP Client" />
	<meta name="twitter:card" content="summary" />
	<link rel="stylesheet" href="./index.css">
</head>

<body>

	<?php
	$nav = new STiBaRC\Nav();
	echo $nav->nav();

	if (empty($_SESSION['sess'])) {
		header('Location: ./login.php');
	}

	if ($action && $action == "vote") {
		$vote = false;
		$target = false;
		if (!empty($_GET["vote"]))
			$vote = $_GET["vote"];
		if (!empty($_GET["target"]))
			$target = $_GET["target"];
		$voteData = $api->vote($id, $target, $vote, $commentId);

		if (!empty($voteData->error)) {
			$error = $voteData->error . ', Error code: ' . $voteData->errorCode;
		} else {
			if ($commentId)
				header('Location: ./post.php?id=' . $id . '#comment-' . $commentId);
			else
				header('Location: ./post.php?id=' . $id);
		}
	}

	if ($action && $action == "follow") {
		$username = false;
		$followData = false;
		if (!empty($_GET["username"]))
			$username = $_GET["username"];

		$followData = $api->followUser($username);

		if (!empty($followData->error)) {
			$error = $followData->error . ', Error code: ' . $followData->errorCode;
		} else {
			header('Location: ./user.php?username=' . $username);
		}
	}

	if ($error)
		echo '<div class="errorBlock">' . $error . '</div>';

	?>

	<div class="centerBlock">
		<a class="button primary" href="./">Home</a>
	</div>

	<?php

	$footer = new STiBaRC\Footer();
	echo $footer->footer();
	?>

</body>

</html>
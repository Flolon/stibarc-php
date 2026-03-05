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

	if (empty($_SESSION['sess'])) {
		header('Location: ./login.php');
	}

	if ($_GET["action"] == "vote") {
		$commentId = false;
		if (!empty($_GET["commentId"]))
			$commentId = $_GET["commentId"];

		$vote = $api->vote($_GET["id"], $_GET["target"], $_GET["vote"], $commentId);

		if (!empty($vote['error']) && !empty($vote['errorText'])) {
			$error = $vote['errorText'] . ' : ' . $vote['error'];
			echo '<div class="errorBlock">' . $error . '</div>';
		} else {
			if ($commentId)
				header('Location: ./post.php?id=' . $_GET["id"] . '#comment-' . $commentId);
			else
				header('Location: ./post.php?id=' . $_GET["id"]);
		}
	}

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
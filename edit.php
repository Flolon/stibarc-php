<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require('global.inc.php');
require('src/API.php');
require('src/Nav.php');
require('src/Footer.php');
require('src/User.php');
require('src/PostBlock.php');

use STiBaRC\STiBaRC;

$api = new STiBaRC\API($apiTarget, true);

$error = false;
$title = false;
$content = false;

if (empty($_SESSION['sess'])) {
	header('Location: ./login.php');
}

$postId = false;
$target = "post";
// GET
if (!empty($_GET["id"]))
	$postId = $_GET["id"];
if (!empty($_GET["target"]))
	$target = $_GET["target"];
// POST
$newTitle = false;
$newContent = false;
$commentId = false;
$newAttachments = false;
$deleted = false;
$privatePost = false;
if (!empty($_POST["title"]))
	$newTitle = $_POST["title"];
if (!empty($_POST["content"]))
	$newContent = $_POST["content"];
if (!empty($_POST["commentId"]))
	$commentId = $_POST["commentId"];
if (!empty($_POST["attachments"]))
	$newAttachments = $_POST["attachments"];
if(!empty($_POST["deleted"]))
	$deleted = $_POST["deleted"];
if (!empty($_POST["privatePost"]))
	$privatePost = $_POST["privatePost"];

if ($newTitle) {
	$editPost = $api->edit($postId, $target, $newTitle, $commentId, $newContent, $newAttachments, $deleted, $privatePost);
	if (!empty($editPost->error))
		$error = $editPost->error . ', Error code: ' . $editPost->errorCode;
	if ($editPost->status == "ok") {
		header('Location: ./post.php?id=' . $postId . ($commentId) ? "#comment-" . $commentId : '');
	}
}

$targetTitle = ' ' . htmlspecialchars(ucfirst($target)) ?? '';

if ($postId && $target) {
	$postData = $api->getPost($postId);
	if (!empty($postData->error))
		$postError = $postData->error . ', Error code: ' . $postData->errorCode;
	if ($postData->status == "ok") {
		$date = strtotime($postData->post->date);
		$title = $postData->post->title;
		$content = $postData->post->content;
	}
} else {
	$error = "Post not found";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title>Edit<?= $targetTitle  ?> | STiBaRC</title>
	<link rel="icon" type="image/png" href="/img/icon.png">
	<!-- Open Graph -->
	<meta property="og:title" content="Edit<?= $targetTitle ?> | STiBaRC" />
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
	?>

	<h2 style="margin-bottom: 8px;">Edit<?= $targetTitle ?></h2>
	<?= ($error) ? '<div class="errorBlock">' . $error . '</div>' : '' ?>
	<?php
	if (!$error && $postData) { ?>
		<div class="userLink" title="<?= htmlspecialchars($postData->post->poster->username) ?>">
			<img class="pfp" width="30px" height="30px" src="<?= $postData->post->poster->pfp ?>">
			<span class="username"><?= htmlspecialchars($postData->post->poster->username) ?></span>
		</div>
		<div>
			<span class="date" title="<?= $postData->post->date ?>"><?= date("m/d/y, g:i A", $date) ?></span>
		</div>
	<?php } ?>
	<form class="postForm" method="POST">
		<div>
			<label for="title">Title:</label>
			<input id="title" name="title" type="text" class="input" autocomplete="off" <?= ($title) ? 'value="' . htmlspecialchars($title) . '"' : '' ?>>
		</div>
		<div>
			<label for="content">Content:</label>
			<textarea id="content" name="content" class="input" autocomplete="off" rows="5"><?= ($content) ? htmlspecialchars($content) : '' ?></textarea>
		</div>
		<div>
			<button type="submit" class="button primary">Save</button>
		</div>
	</form>

	<?php
	$footer = new STiBaRC\Footer();
	echo $footer->footer();
	?>

</body>

</html>
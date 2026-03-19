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

if (empty($_SESSION['sess'])) {
	header('Location: ./login.php');
}

$postError = false;
$title = "";
$content = "";
$attachments = false;
$attachmentUrls = false;
if (!empty($_POST["title"]))
	$title = $_POST["title"];
if (!empty($_POST["content"]))
	$content = $_POST["content"];
if (!empty($_FILES["attachments"]["tmp_name"][0])) {
	$attachments = fixFilesArray($_FILES["attachments"]);
}
if ($attachments) {
	$attachmentUrls = array();
	foreach ($attachments as $file) {
		$attachmentUrl = $api->uploadFile($file, "attachment");
		if (!empty($attachmentUrl->error))
			$postError = $attachmentUrl->error . ', Error code: ' . $attachmentUrl->errorCode;
		if (isset($attachmentUrl->file))
			array_push($attachmentUrls, $attachmentUrl->file);
	}
	$attachments = $attachmentUrls;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$post = $api->newPost(title: $title, content: $content, attachments: $attachmentUrls);
	if (!empty($post->error))
		$postError = $post->error . ', Error code: ' . $post->errorCode;
	if ($post->status == "ok")
		header('Location: ./post.php?id=' . $post->id . '');
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title>New Post | STiBaRC</title>
	<link rel="icon" type="image/png" href="/img/icon.png">
	<!-- Open Graph -->
	<meta property="og:title" content="New Post | STiBaRC" />
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

	<h2 style="margin-bottom: 8px;">New Post</h2>
	<div>Posting as:</div>
	<div class="userLink" title="<?= htmlspecialchars($_SESSION["username"]) ?>">
		<img class="pfp" width="30px" height="30px" src="<?= $_SESSION["pfp"] ?>">
		<span class="username"><?= htmlspecialchars($_SESSION["username"]) ?></span>
	</div>
	<?= ($error) ? '<div class="errorBlock">' . $error . '</div>' : '' ?>
	<?= ($postError) ? '<div class="errorBlock">' . $postError . '</div>' : '' ?>
	<form class="postForm" method="POST" enctype="multipart/form-data">
		<div>
			<label for="title">Title:</label>
			<input id="title" name="title" type="text" class="input" autocomplete="off" autofocus
				<?= ($title) ? 'value="' . htmlspecialchars($title) . '"' : '' ?>>
		</div>
		<div>
			<label for="content">Content:</label>
			<textarea id="content" name="content" class="input" autocomplete="off" rows="5"><?= ($content) ? htmlspecialchars($content) : '' ?></textarea>
		</div>
		<div>
			<label for="attachments">Add attachments:</label>
			<input type="file" id="attachments" name="attachments[]" accept="image/*,audio/*,video/*" multiple />
		</div>
		<div>
			<button type="submit" class="button primary">Post</button>
			<a class="button" href="./">Cancel</a>
		</div>
	</form>

	<?php
	$footer = new STiBaRC\Footer();
	echo $footer->footer();
	?>

</body>

</html>
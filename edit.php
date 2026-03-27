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

$postId = false;
$target = "post";
$commentId = false;
// GET
if (!empty($_GET["id"]))
	$postId = $_GET["id"];
if (!empty($_GET["commentId"])) {
	$commentId = $_GET["commentId"];
	$target = "comment";
}
// POST
$newTitle = false;
$newContent = false;
$attachmentUrls = [];
$newAttachments = false;
if (!empty($_POST["title"]))
	$newTitle = $_POST["title"];
if (!empty($_POST["content"]))
	$newContent = $_POST["content"];
$deleted = $_POST["deleted"] ?? false;
$newPrivatePost = $_POST["privatePost"] ?? false;
if (!empty($_POST['attachmentSelect'])) {
	foreach ($_POST['attachmentSelect'] as $url)
		array_push($attachmentUrls, $url);
}
if (!empty($_FILES["attachments"]["tmp_name"][0])) {
	$newAttachments = fixFilesArray($_FILES["attachments"]);
}
if ($newAttachments) {
	foreach ($newAttachments as $file) {
		$attachmentUrl = $api->uploadFile($file, "attachment");
		if (!empty($attachmentUrl->error))
			$postError = $attachmentUrl->error . ', Error code: ' . $attachmentUrl->errorCode;
		if (isset($attachmentUrl->file))
			array_push($attachmentUrls, $attachmentUrl->file);
	}
}
$newAttachments = $attachmentUrls;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$editPost = $api->edit($postId, $target, $newTitle, $commentId, $newContent, $newAttachments, $deleted, $newPrivatePost);
	if (!empty($editPost->error))
		$error = $editPost->error . ', Error code: ' . $editPost->errorCode;
	if ($editPost->status == "ok") {
		if ($deleted && $target == "post") {
			header('Location: ./');
		} else {
			header('Location: ./post.php?id=' . $postId . (($commentId && !$deleted) ? "#comment-" . $commentId : (($deleted) ? '#comments' : '')));
		}
	}
}

$targetTitle = ' ' . htmlspecialchars(ucfirst($target)) ?? '';

$title = false;
$date = false;
$content = false;
$privatePost = null;
if ($postId && $target) {
	$postData = $api->getPost($postId);
	if (!empty($postData->error))
		$postError = $postData->error . ', Error code: ' . $postData->errorCode;
	if ($postData->status == "ok") {
		if ($target == "post") {
			$username = $postData->post->poster->username;
			$pfp = $postData->post->poster->pfp;
			$date = strtotime($postData->post->date);
			$title = $postData->post->title;
			$content = $postData->post->content;
			$privatePost = $postData->post->private;
		} elseif ($target == "comment" && $commentId) {
			$comments = $postData->post->comments;
			$commentKeys = array_column($comments, "id");
			$index = array_search($commentId, $commentKeys);
			$comment = $comments[$index] ?? false;
			$content = $comment->content;
			$username = $comment->poster->username;
			$pfp = $comment->poster->pfp;
			$date = strtotime($comment->date);
		}
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

	<div class="card">
		<div style="margin-top: 20px">
			<form class="right" method="POST">
				<input type="hidden" name="deleted" value="true">
				<button class="button danger" type="submit" title="Delete<?= $targetTitle ?>">Delete</button>
			</form>
		</div>
		<h1 style="margin: 8px 0;">Edit<?= $targetTitle ?></h1>
		<?= ($error) ? '<div class="errorBlock">' . $error . '</div>' : '' ?>
		<?= ($error) ? '<div style="margin: 12px 0;"><a class="button primary" href="./">Home</a></div>' : '' ?>
		<?php
		if (!$error && $postData) { ?>
			<div class="userLink" title="<?= htmlspecialchars($username) ?>">
				<img class="pfp" width="30px" height="30px" src="<?= $pfp ?>">
				<span class="username"><?= htmlspecialchars($username) ?></span>
			</div>
			<div>
				<span class="date" title="<?= $postData->post->date ?>"><?= date("m/d/y, g:i A", $date) ?></span>
			</div>
		<?php } ?>
		<form class="postForm" method="POST" enctype="multipart/form-data">
			<?php if ($target == "post") { ?>
				<div>
					<label for="title">Title:</label>
					<input id="title" name="title" type="text" class="input" autocomplete="off" value="<?= ($newTitle) ? htmlspecialchars($newTitle) : htmlspecialchars($title) ?>">
				</div>
			<?php } ?>
			<div>
				<label for=" content">Content:</label>
				<textarea id="content" name="content" class="input" autocomplete="off" rows="5"><?= ($newContent) ? htmlspecialchars($newContent) : htmlspecialchars($content) ?></textarea>
			</div>
			<div>
				<label for="private" style="display: inline-block;">Private post:</label>
				<input id="private" name="privatePost" type="checkbox" <?= ((isset($newPrivatePost) && $newPrivatePost) || $privatePost) ? 'checked' : '' ?>>
			</div>
			<div style="margin-bottom: 8px;">
				<?php
				if ($postData->post->attachments) {
				?>
					<label>Attachments:</label>
					<i>Uncheck to remove an attachment.</i>
					<div class="attachmentSelect">
						<?php
						foreach ($postData->post->attachments as $attachment) {
							$attachmentObj = new STiBaRC\Attachment($attachment, false);
							echo '
					<label for="attachment" class="card">
						<input id="attachment" type="checkbox" name="attachmentSelect[]" value="' . $attachment . '" checked \>'
								. $attachmentObj->attachmentBlock() . '
					</label>';
						}
						?>
					</div>
				<?php	}
				?>
				<div>
					<label for="attachments">Add attachments:</label>
					<input type="file" id="attachments" name="attachments[]" accept="image/*,audio/*,video/*" multiple />
				</div>
			</div>
			<div style="margin-bottom: 8px;">
				<button type="submit" class="button primary">Save</button>
				<a class="button" href="./">Cancel</a>
			</div>
		</form>
	</div>

	<?php
	$footer = new STiBaRC\Footer();
	echo $footer->footer();
	?>

</body>

</html>
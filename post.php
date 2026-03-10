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

$postId = false;
if (!empty($_GET["id"]))
	$postId = $_GET["id"];

$error = false;
$postData = false;
$title = false;
$username = false;
$pfp = false;
$contentPreview = false;
$postDate = false;

if ($postId) {
	$postData = $api->getPost($postId);
	if (!empty($postData->error))
		$error = $postData->error . ', Error code: ' . $postData->errorCode;
	if (!empty($postData->post))
		$postData = $postData->post;
}

if ($postData && !$error) {
	$title = htmlspecialchars($postData->title);
	$username = htmlspecialchars($postData->poster->username);
	$postDate = $postData->date;
	$pfp = $postData->poster->pfp;
	$contentPreview = htmlspecialchars($postData->content);
	$maxCharLength = 150;
	if (strlen($postData->title) > $maxCharLength)
		$title = htmlspecialchars(substr($postData->title, 0, ($maxCharLength - 3)) . '...');
	if (strlen($postData->content) > $maxCharLength)
		$contentPreview = htmlspecialchars(substr($postData->content, 0, ($maxCharLength - 3)) . '...');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title><?= ($title) ? htmlspecialchars($title) . ' | ' : '' ?>STiBaRC</title>
	<meta property="description" content="<?= ($contentPreview) ? htmlspecialchars($contentPreview) : '' ?>" />
	<link rel="icon" type="image/png" href="/img/icon.png">
	<!-- Open Graph -->
	<meta property="og:title" content="<?= ($title) ? htmlspecialchars($title) . ' | ' : '' ?>STiBaRC" />
	<meta property="og:description" content="<?= ($contentPreview) ? htmlspecialchars($contentPreview) : '' ?>" />
	<meta property="og:type" content="website" />
	<meta property="og:url" content="<?= htmlspecialchars($url, ENT_QUOTES, 'UTF-8') ?>" />
	<meta property="og:site_name" content="STiBaRC">
	<meta property="og:logo" content="https://stibarc.sopaws.com/img/icon.png">
	<meta property="profile:username" content="<?= ($username) ? htmlspecialchars($username) : '' ?>">
	<meta name="theme-color" content="#3ea1b1" />
	<meta name="application-name" content="STiBaRC">
	<meta name="twitter:site" content="STiBaRC" />
	<meta name="twitter:title" content="<?= ($title) ? htmlspecialchars($title) : '' ?>" />
	<meta name="twitter:description" content="<?= ($contentPreview) ? htmlspecialchars($contentPreview) : '' ?>" />
	<meta name="twitter:card" content="summary_large_image" />
	<meta property="article:published_time" content="<?= ($postDate) ? htmlspecialchars($postDate) : '' ?>">
	<?php if (!$error && $postData && $postData->attachments) {
		$attachment = $postData->attachments[0];
		$attachmentObj = new STiBaRC\Attachment($attachment, true);
		$attachmentObj->type;
	?>
		<meta property="og:<?= $type ?>" content="<?= $attachment ?>" />
		<meta name="twitter:image" content="<?= $attachment ?>" />
	<?php } else { ?>
		<meta name="twitter:image" content="<?= ($pfp) ? htmlspecialchars($pfp) : '' ?>" />
	<?php } ?>
	<link rel="stylesheet" href="./index.css">
</head>

<body>

	<?php
	$nav = new STiBaRC\Nav();
	echo $nav->nav();

	if ($postData && !$error) {
		$postObj = new STiBaRC\Post($postData);
		echo $postObj->post();

		if ($postData->comments) {
			echo '<h2 style="margin-bottom: 8px;">' . count($postData->comments) . ' Comment' . ((count($postData->comments) == 1) ? '' : 's') . '</h2><hr class="light" />';
		} else {
			echo '<h2>0 Comments</h2><hr class="light" />';
		}

		if (!empty($_SESSION["sess"])) {
			echo '<form id="newComment" method="POST">
				<label for="comment">
					<h3>New comment:</h3>
				</label>
				<textarea class="input" id="comment" name="comment"></textarea>
				<button class="button primary" type="submit">Comment</button>
			</form>';
		} else {
			echo '<div style="margin-top: 12px;">';
		}

		if ($postData->comments) {
			foreach ($postData->comments as $comment) {
				$commentObj = new STiBaRC\Comment($comment, $postData->id);
				echo $commentObj->comment();
			}
		}

		if(empty($_SESSION["sess"]))
			echo '</div>';

	} else {
		echo '<h1 style="margin-bottom: 0;">Post not found</h1>';
		if (!$postId)
			echo '<p style="margin-top: 12px;">Post ID cannot be empty.</p>';
		if ($error)
			echo '<div class="errorBlock">' . $error . '</div>';
		echo '<div style="margin: 12px 0;"><a class="button primary" href="./">Home</a></div>';
		echo '<img style="display: block;max-width: 100%;" src="./img/jimbomournsyourmisfortune.png" height="150px" alt="jimbomournsyourmisfortune.png" title="jimbomournsyourmisfortune.png"">';
	}

	$footer = new STiBaRC\Footer();
	echo $footer->footer();
	?>

</body>

</html>
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

if (!empty($_GET["id"])) {
	$postId = $_GET["id"];
} else {
	header('Location: ./404.php?url=' . $_SERVER['REQUEST_URI']);
}

$postData = $api->getPost($postId);

$maxCharLength = 150;
$title = htmlspecialchars($postData->title);
$contentPreview = htmlspecialchars($postData->content);
if (strlen($postData->title) > $maxCharLength)
	$title = htmlspecialchars(substr($postData->title, 0, ($maxCharLength - 3)) . '...');
if (strlen($postData->content) > $maxCharLength)
	$contentPreview = htmlspecialchars(substr($postData->content, 0, ($maxCharLength - 3)) . '...');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title><?= $title ?> | STiBaRC</title>
	<meta property="description" content="<?= $contentPreview ?>" />
	<!-- Open Graph -->
	<meta property="og:title" content="<?= $title ?> | STiBaRC" />
	<meta property="og:description" content="<?= $contentPreview ?>" />
	<meta property="og:type" content="website" />
	<meta property="og:url" content="<?= htmlspecialchars($url, ENT_QUOTES, 'UTF-8') ?>" />
	<meta name="twitter:site" content="STiBaRC" />
	<meta name="twitter:title" content="<?= $title ?>" />
	<meta name="twitter:description" content="<?= $contentPreview ?>" />
	<?php if ($postData->attachments) {
		$attachment = $postData->attachments[0];
		$attachmentObj = new STiBaRC\Attachment($attachment, true);
		$attachmentObj->type;
	?>
		<meta property="og:<?= $type ?>" content="<?= $attachment ?>" />
		<meta name="twitter:card" content="summary_large_image" />
		<meta name="twitter:image" content="<?= $attachment ?>" />
	<?php } else { ?>
		<meta name="twitter:card" content="summary" />
		<meta name="twitter:image" content="<?= $postData->poster->pfp ?>" />
	<?php } ?>
	<link rel="stylesheet" href="./index.css">
</head>

<body>

	<?php
	$nav = new STiBaRC\Nav();
	echo $nav->nav();

	$postObj = new STiBaRC\Post($postData);
	echo $postObj->post();

	if ($postData->comments) {
		echo '<h2>' . count($postData->comments) . ' Comments</h2>';

		foreach ($postData->comments as $comment) {
			$commentObj = new STiBaRC\Comment($comment, $postData->id);
			echo $commentObj->comment();
		}
	}

	$footer = new STiBaRC\Footer();
	echo $footer->footer();
	?>

</body>

</html>
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
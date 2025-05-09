<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require('global.inc.php');
require('src/API.php');
require('src/Nav.php');
require('src/UserBlock.php');
require('src/PostBlock.php');

use STiBaRC\STiBaRC;

$api = new STiBaRC\API($apiTarget, true);

$username = $_GET["username"];

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

	$userData = $api->getUser($username);

	$userBlockObj = new STiBaRC\UserBlock($userData);
	echo $userBlockObj->user();

	if ($userData->posts) {
		echo "<h2>Posts</h2>";

		foreach ($userData->posts as $postData) {
			$postHtml = new STiBaRC\PostBlock($postData, $showAttachments);
			echo $postHtml->post();
		}
	} else {
		echo "<h2>No Posts</h2>";
	}
	?>

</body>

</html>
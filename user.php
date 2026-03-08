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

$username = false;
if (!empty($_GET["username"]))
	$username = $_GET["username"];

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title><?= ($username) ? htmlspecialchars($username) . ' | ' : '' ?>STiBaRC</title>
	<link rel="icon" type="image/png" href="/img/icon.png">
	<!-- Open Graph -->
	<meta property="og:title" content="<?= ($username) ? htmlspecialchars($username) . ' ' : '' ?>STiBaRC" />
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

	if ($username) {
		$userData = $api->getUser($username);
	}

	if ($username && $userData) {
		$userBlockObj = new STiBaRC\UserBlock($userData);
		echo $userBlockObj->user();

		if ($userData->posts) {
			echo '<h2>' . count($userData->posts) . ' Post' . ((count($userData->posts) == 1) ? '' : 's') . '</h2>';

			foreach ($userData->posts as $postData) {
				$postHtml = new STiBaRC\PostBlock($postData, $showAttachments);
				echo $postHtml->post();
			}
		} else {
			echo "<h2>No posts</h2>";
		}
	} else {
		echo '<h1 style="margin-bottom: 0;">User not found</h1>';
		if (!$username)
			echo '<p>No username provided.</p>';
		echo '<div style="margin-bottom: 12px"><a class="button primary" href="./">Home</a></div>';
		echo '<img style="display: block;max-width: 100%;" src="./img/jimbomournsyourmisfortune.png" height="150px" alt="jimbomournsyourmisfortune.png" title="jimbomournsyourmisfortune.png"">';
	}

	$footer = new STiBaRC\Footer();
	echo $footer->footer();
	?>

</body>

</html>
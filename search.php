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

$search_query = $_GET["q"];

?>
<!DOCTYPE html>
<html>

<head>
	<title>STiBaRC</title>
	<link rel="stylesheet" href="./index.css">
</head>

<body>

	<?php
	$nav = new STiBaRC\Nav($search_query);
	echo $nav->nav();

	$searchData = $api->search($search_query);
	$resultCount =  count($searchData->users) + count($searchData->posts) ?? 0;

	echo '<h2 style="margin-bottom: 0;">' . htmlspecialchars($search_query) . '</h2>';
	echo '<p style="margin-top: 8px;">' . $resultCount . ' Results' . '</p>';

	if ($searchData->users) {
		echo "<h3>Users</h3>";

		foreach ($searchData->users as $userData) {
			$userHTML = new STiBaRC\UserBlock($userData);
			echo $userHTML->user();
		}
	}

	if ($searchData->posts) {
		echo "<h3>Posts</h3>";

		foreach ($searchData->posts as $postData) {
			$postHtml = new STiBaRC\PostBlock($postData, $showAttachments);
			echo $postHtml->post();
		}
	}
	?>

</body>

</html>
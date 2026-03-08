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

$search_query = false;
if ($_GET["q"])
	$search_query = $_GET["q"];

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title>STiBaRC</title>
	<link rel="stylesheet" href="./index.css">
</head>

<body>

	<?php
	$nav = new STiBaRC\Nav($search_query);
	echo $nav->nav();

	if ($search_query) {
		$searchData = $api->search($search_query);
	}

	if ($search_query && $searchData) {
		
		$resultCount =  count($searchData->users) + count($searchData->posts) ?? 0;

		echo '<h1 style="margin-bottom: 0;">' . htmlspecialchars($search_query) . '</h1>';
		echo '<p style="margin-top: 12px;">' . $resultCount . ' Result' . (($resultCount == 1) ? '' : 's') . '</p>';

		if ($searchData->users) {
			echo '<h2>' . count($searchData->users) . ' User' . ((count($searchData->users) == 1) ? '' : 's') . '</h2>';

			foreach ($searchData->users as $userData) {
				$userHTML = new STiBaRC\UserBlock($userData);
				echo $userHTML->userBlock();
			}
		}

		if ($searchData->posts) {
			echo '<h2>' . count($searchData->posts) . ' Post' . ((count($searchData->posts) == 1) ? '' : 's') . '</h2>';

			foreach ($searchData->posts as $postData) {
				$postHtml = new STiBaRC\PostBlock($postData, $showAttachments);
				echo $postHtml->post();
			}
		}
	} else {
		echo '<h1 style="margin-bottom: 0;">No results</h1>';
		if (!$search_query)
			echo '<p style="margin-top: 12px;">Search query empty. Try typing something in the search bar first.</p>';
		echo '<div style="margin-bottom: 12px"><a class="button primary" href="./">Home</a></div>';
		echo '<img style="display: block;max-width: 100%;" src="./img/jimbomournsyourmisfortune.png" height="150px" alt="jimbomournsyourmisfortune.png" title="jimbomournsyourmisfortune.png"">';
	}

	$footer = new STiBaRC\Footer();
	echo $footer->footer();
	?>

</body>

</html>
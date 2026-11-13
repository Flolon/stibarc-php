<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require('../global.inc.php');
require('../src/API.php');
require('../src/Nav.php');
require('../src/Footer.php');

use STiBaRC\STiBaRC;

$api = new STiBaRC\API($apiTarget, true);

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title>STiBaRC</title>
	<link rel="stylesheet" href="../index.css">
</head>

<body>

	<?php

	$nav = new STiBaRC\Nav();
	echo $nav->nav();

?>
<h1>Developer Portal</h1>
<h2>My Applications</h2>
<hr class="light">
<?php
	$footer = new STiBaRC\Footer();
	echo $footer->footer();
	?>

</body>

</html>
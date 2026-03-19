<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require('global.inc.php');
require('src/API.php');
require('src/Nav.php');
require('src/Footer.php');

use STiBaRC\STiBaRC;

$api = new STiBaRC\API($apiTarget, true);

$error = false;
$username = "";
$password = "";
if (!empty($_POST["username"]))
	$username = trim($_POST["username"]);
if (!empty($_POST["password"]))
	$password = trim($_POST["password"]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if (empty($username) || empty($password)) {
		$error = "Username and password must be entered!";
	} else {
		$loginResponse = $api->login($username, $password);

		if (!empty($loginResponse['error']) && !empty($loginResponse['errorText'])) {
			$error = $loginResponse['errorText'] . ' : ' . $loginResponse['error'];
		} else {
			header('Location: ./');
		}
	}
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title>STiBaRC</title>
	<link rel="icon" type="image/png" href="/img/icon.png">
	<!-- Open Graph -->
	<meta property="og:title" content="STiBaRC" />
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
	<div class="login">
		<h2>Login</h2>
		<form method="POST">
			<?= $error ? '<div class="errorBlock">' . $error . '</div>' : ''; ?>
			<div class="row">
				<label for="username">Username:</label>
				<input name="username" placeholder="Username" autofocus <?= ($username) ? 'value="' . htmlspecialchars($username) . '"' : '' ?>>
			</div>
			<div class="row">
				<label for="password">Password:</label>
				<input name="password" type="password" placeholder="Password" <?= ($password) ? 'value="' . htmlspecialchars($password) . '"' : '' ?>>
			</div>
			<div class="row">
				<button class="primary" type="submit">Login</button>
			</div>
		</form>
		<hr class="row" style="margin-top: 16px;">
		<div class="row">
			<a class="loginWith" href="https://stibarc.com/oauth/?client_id=b5543b27a9fac3ad509d0168cee7d8cf&response_type=token&scope=all">
				<img src="./img/Login-with-STiBaRC.png" alt="Login with STiBaRC" title="Login with STiBaRC OAuth">
			</a>
		</div>
	</div>

	<?php
	$footer = new STiBaRC\Footer();
	echo $footer->footer();
	?>

</body>

</html>
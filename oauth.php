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


if (!empty($_POST['fragment'])) {

	$urlQuery = str_replace("#", "", $_POST['fragment']);

	parse_str($urlQuery, $oauth);
	$token = $oauth['access_token'];

	$api->setSess($token);
	$loginResponse = $api->getPrivateData();

	if ($loginResponse->status->error && $loginResponse->status->errorText) {
		echo $loginResponse->status->errorText . ' : ' . $loginResponse->status->error;
		echo '<div><a href="./">Home</a></div>';
	} else {
		header('Location: ./');
	}
	die;
}
?>
Loading...
<form method="POST" id="form">
	<input type="hidden" name="fragment" id="fragment">
</form>
<script>
	document.getElementById("fragment").value = window.location.hash;
	document.getElementById("form").submit();
</script>
<?php

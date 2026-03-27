<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require('global.inc.php');
require('src/API.php');
require('src/Nav.php');
require('src/Footer.php');
require('src/User.php');

use STiBaRC\STiBaRC;

$api = new STiBaRC\API($apiTarget, true);

$error = false;
if (empty($_SESSION['sess'])) {
	header('Location: ./login.php');
}

// Current data
$user = false;
$birthdayValue = false;
$userData = $api->getPrivateData();
if (!empty($userData->error))
	$error = $userData->error . ', Error code: ' . $userData->errorCode;
if (!empty($userData->user) && !$error) {
	$user = $userData->user;
	if ($user->birthday) {
		$birthday = strtotime($user->birthday);
		$birthdayValue = date("Y-m-d", $birthday);
	}
}

// POST
$attachmentUrl = '';
$newPfp = false;
$name = $_POST['name'] ?? false;
if (!empty($_FILES['newPfp']['tmp_name'])) {
	$newPfp = $_FILES['newPfp'];
}
if ($newPfp) {
	$attachmentUrl = $api->uploadFile($newPfp, "pfp");
	if (!empty($attachmentUrl->error))
		$error = $attachmentUrl->error . ', Error code: ' . $attachmentUrl->errorCode;
}
$newPfp = $attachmentUrl;

$name = $_POST['name'] ?? '';
$displayName = $_POST['displayName'] ?? false;
$pronouns = $_POST['pronouns'] ?? '';
$displayPronouns = $_POST['displayPronouns'] ?? false;
$email = $_POST['email'] ?? '';
$displayEmail = $_POST['displayEmail'] ?? false;
$birthday = $_POST['birthday'] ?? '';
$displayBirthday = $_POST['displayBirthday'] ?? false;
$bio = $_POST['bio'] ?? '';
$displayBio = $_POST['displayBio'] ?? false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$editProfile = $api->editProfile(
		pfp: $newPfp,
		name: $name,
		email: $email,
		birthday: $birthday,
		bio: $bio,
		pronouns: $pronouns,
		displayName: $displayName,
		displayEmail: $displayEmail,
		displayBirthday: $displayBirthday,
		displayPronouns: $displayPronouns,
		displayBio: $displayBio
		);
	if (!empty($editProfile->error))
		$error = $editProfile->error . ', Error code: ' . $editProfile->errorCode;
	if ($editProfile->status == "ok") {
		// header('Location: ./');
	}
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title>Edit Profile | STiBaRC</title>
	<link rel="icon" type="image/png" href="/img/icon.png">
	<!-- Open Graph -->
	<meta property="og:title" content="Edit Profile | STiBaRC" />
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

	<form class="card" method="POST" enctype="multipart/form-data">
		<h1 style="margin-bottom: 8px;">Edit Profile</h1>
		<?= ($error) ? '<div class="errorBlock">' . $error . '</div>' : '' ?>
		<div style="margin-bottom: 8px;">
			<a href="<?= $user->pfp ?>" target="_blank"><img class="pfp" width="50px" height="50px" src="<?= $user->pfp ?>"></a>
			<div style="margin-top: 8px;">
				<span class="username"><?= htmlspecialchars($user->username) ?></span>
				<?= ($user->verified ? '<span class="verified" title="Verified user">
				<img class="icon" src="./img/icon/verified.png" height="14px" alt="Verified"></span>' : '') ?>
			</div>
			<span>
				<label style="display: block;margin: 6px 0;" for="newPfp">Replace profile picture:</label>
				<input type="file" id="newPfp" name="newPfp" accept="image/*" />
			</span>
		</div>
		<div style="margin-bottom: 8px;">
			<label for="name">Name:</label>
			<input type="text" id="name" name="name" value="<?= ($user->name) ? htmlspecialchars($user->name) : '' ?>">
			<span><label for="displayName">Show Name:</label>
				<input type="checkbox" name="displayName" id="displayName"
					<?= ((isset($displayName) && $displayName) || $user->displayName) ? 'checked' : '' ?>></span>
		</div>
		<div style="margin-bottom: 8px;">
			<label for="pronounsInput">Pronouns:</label>
			<input list="commonPronouns" id="pronounsInput" type="text" placeholder="Pronouns" autocomplete="off"
				autocapitalize="none" maxlength="40" value="<?= ($user->pronouns ? htmlspecialchars($user->pronouns) : "") ?>">
			<datalist id="commonPronouns">
				<option value="she/her"></option>
				<option value="he/him"></option>
				<option value="they/them"></option>
				<option value="it/its"></option>
			</datalist>
			<span><label for="displayPronouns">Show Pronouns:</label>
				<input type="checkbox" name="displayPronouns" id="displayPronouns"
					<?= ((isset($displaydisplayPronounsPronounes) && $displayPronouns) || $user->displayPronouns) ? 'checked' : '' ?>></span>
		</div>
		<div style="margin-bottom: 8px;">
			<label for="name">Email:</label>
			<input type="text" id="email" name="email" value="<?= ($user->email) ? htmlspecialchars($user->email) : '' ?>">
			<span><label for="displayEmail">Show Email:</label>
				<input type="checkbox" name="displayEmail" id="displayEmail"
					<?= ((isset($displayEmail) && $displayEmail) || $user->displayEmail) ? 'checked' : '' ?>></span>
		</div>
		<div style="margin-bottom: 8px;">
			<label for="name">Birthday:</label>
			<input type="date" id="birthday" name="birthday" placeholder="Birthday" autocomplete="bday"
				value="<?= ($birthdayValue) ? htmlspecialchars($birthdayValue) : '' ?>">
			<span><label for="displayBirthday">Show Birthday:</label>
				<input type="checkbox" name="displayBirthday" id="displayBirthday"
					<?= ((isset($displayBirthday) && $displayBirthday) || $user->displayBirthday) ? 'checked' : '' ?>></span>
		</div>
		<div style="margin-bottom: 8px;">
			<label style="display: block;" for="name">Bio:</label>
			<textarea id="bio" name="bio" autocomplete="off" rows="5" cols="50"><?= ($user->bio) ? htmlspecialchars($user->bio) : '' ?></textarea>
			<div><label for="displayBio">Show Bio:</label>
				<input type="checkbox" name="displayBio" id="displayBio"
					<?= ((isset($displayBio) && $displayBio) || $user->displayBio) ? 'checked' : '' ?>>
			</div>
		</div>
		<div style="margin-bottom: 8px;">
			<button type="submit" class="button primary">Save</button>
			<a class="button" href="./">Cancel</a>
		</div>
	</form>

	<?php
	$footer = new STiBaRC\Footer();
	echo $footer->footer();
	?>

</body>

</html>
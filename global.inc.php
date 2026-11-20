<?php
session_start();
/*
    Config
*/
// API to use
$apiTarget = "production";
// show attachments in post previews
$showAttachments = true;
// thumbnail file type
$thumbType = 'png';

/*
    Common shit
*/
if (isset($_SERVER['HTTPS']) &&
    ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) ||
    isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
    $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
  $protocol = 'https://';
}
else {
  $protocol = 'http://';
}

$url =  "{$protocol}{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";

function fixFilesArray($arr = [])
{
	$new = null;
	foreach ($arr as $key => $all) {
		foreach ($all as $i => $val) {
			$new[$i][$key] = $val;
		}
	}
	return $new;
}

require('src/Attachment.php');
<?php
session_start();
/*
    Config
*/
// API to use
$apiTarget = "production";
// show attachments in post previews
$showAttachments = true;


/*
    Common shit
*/
$url =  "https://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";

function fixFilesArray($arr)
{
	foreach ($arr as $key => $all) {
		foreach ($all as $i => $val) {
			$new[$i][$key] = $val;
		}
	}
	return $new;
}

require('./src/Attachment.php');

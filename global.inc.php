<?php
session_start();
$url =  "https://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";

/*
    Config
*/
// API to use
$apiTarget = "development";
// show attachments in post previews
$showAttachments = true;
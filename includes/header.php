<?php
/*
 * @ PHP 5.6
 * @ Decoder version : 1.0.0.1
 */

session_start();
include_once "includes/functions.php";
$checkLicense = "";
$bar = "/";
$XCStreamHostUrl = isset($XCStreamHostUrl) ? $XCStreamHostUrl : "";
$XClicenseIsval = isset($XClicenseIsval) ? $XClicenseIsval : "";
$XClocalKey = isset($XClocalKey) ? $XClocalKey : "";
$SessioStoredUsername = !empty($_SESSION["webTvplayer"]["username"]) ? $_SESSION["webTvplayer"]["username"] : "";
if (substr($XCStreamHostUrl, -1) == "/") {
    $bar = "";
}
if ($configFileCheck["result"] == "success") {
    if ($configFileCheck["permission"] == "0777" || $configFileCheck["permission"] == "0755") {
        require "configuration.php";
    } else {
        require "configuration.php";
    }
} else {
    if (!file_exists("configuration.php")) {
        $my_file = "configuration.php";
        $handle = fopen($my_file, "w") or exit("Cannot open file:  " . $my_file);
    }
}
if (!isset($_SESSION["webTvplayer"]) && empty($_SESSION["webTvplayer"]) && $activePage !== "index") {
    echo "<script>window.location.href = 'index.php';</script>";
    exit;
}
if (empty($XClicenseIsval) && empty($XClocalKey)) {
    echo "<script>window.location.href = 'player_install.php';</script>";
    exit;
}
$checkLicense = webtvpanel_CheckLicense($XClicenseIsval, $XClocalKey);
if ($checkLicense["status"] == "Active" && isset($checkLicense["localkey"]) && !empty($checkLicense["localkey"])) {
    $New_XCStreamHostUrl = $XCStreamHostUrl;
    $New_XClogoLinkval = $XClogoLinkval;
    $New_XCcopyrighttextval = $XCcopyrighttextval;
    $New_XCcontactUslinkval = $XCcontactUslinkval;
    $New_XChelpLinkval = $XChelpLinkval;
    $New_XClicenseIsval = $XClicenseIsval;
    $New_XClocalKey = $checkLicense["localkey"];
    $New_XCsitetitleval = $XCsitetitleval;
    $response["result"] = "no";
    $content = "<?php \n";
    $content .= "\$XCStreamHostUrl = \"" . $New_XCStreamHostUrl . "\";" . "\n";
    $content .= "\$XClogoLinkval = \"" . $New_XClogoLinkval . "\";" . "\n";
    $content .= "\$XCcopyrighttextval = \"" . $New_XCcopyrighttextval . "\";" . "\n";
    $content .= "\$XCcontactUslinkval = \"" . $New_XCcontactUslinkval . "\";" . "\n";
    $content .= "\$XChelpLinkval = \"" . $New_XChelpLinkval . "\";" . "\n";
    $content .= "\$XClicenseIsval = \"" . $New_XClicenseIsval . "\";" . "\n";
    $content .= "\$XClocalKey = \"" . $New_XClocalKey . "\";" . "\n";
    $content .= "\$XCsitetitleval = \"" . $New_XCsitetitleval . "\";" . "\n";
    $content .= "?>";
    if (file_exists("configuration.php")) {
        unlink("configuration.php");
    }
    $fp = fopen("configuration.php", "w");
    fwrite($fp, $content);
    fclose($fp);
    chmod("configuration.php", 420);
    if (file_exists("configuration.php")) {
        echo "<script>location.reload();</script>";
        exit;
    }
}
if ($checkLicense["status"] !== "Active" && $activePage !== "player_install") {
    echo "<script>window.location.href = 'player_install.php';</script>";
    exit;
}
if (isset($_SESSION["webTvplayer"])) {
    $username = $_SESSION["webTvplayer"]["username"];
    $password = $_SESSION["webTvplayer"]["password"];
    $hostURL = $XCStreamHostUrl;
}
$ShiftedTimeEPG = 0;
$headerparentcondition = "";
$GlobalTimeFormat = "12";
if (isset($_COOKIE["settings_array"])) {
    $SettingArray = json_decode($_COOKIE["settings_array"]);
    if (isset($SettingArray->{$SessioStoredUsername}) && !empty($SettingArray->{$SessioStoredUsername})) {
        $ShiftedTimeEPG = $SettingArray->{$SessioStoredUsername}->epgtimeshift;
        $GlobalTimeFormat = $SettingArray->{$SessioStoredUsername}->timeformat;
        $headerparentcondition = $SettingArray->{$SessioStoredUsername}->parentpassword;
    }
}
echo "<!DOCTYPE html>
<html lang=\"en\">
<head>
<meta charset=\"utf-8\">
<meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">
<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
<title>";
echo isset($XCsitetitleval) ? $XCsitetitleval : "";
echo "</title>

<!-- Bootstrap -->
<style>
:root {
  --primary-color: #fff;
  --dark-gray: #222;
  --almost-black: #111;
  --semi-white: #ccc;
  --blue: #3498db;
  --red: #e74c3c;

  --standard: 1.25rem;
  --big: 2rem;
  --small: 0.7rem;

  --serif: Georgia, serif;
}
</style>
<link href=\"css/bootstrap.css\" rel=\"stylesheet\">
<link href=\"css/style.css\" rel=\"stylesheet\">
<link href=\"css/owl.carousel.css\" rel=\"stylesheet\">
<link href=\"css/font-awesome.min.css\" rel=\"stylesheet\">
<link href=\"css/scrollbar.css\" rel=\"stylesheet\">

<script src=\"js/jquery-1.11.3.min.js\"></script> 
<script></script>
<link rel=\"stylesheet\" type=\"text/css\" href=\"css/rippler.css\" />


<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
      <script src=\"https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js\"></script>
      <script src=\"https://oss.maxcdn.com/respond/1.4.2/respond.min.js\"></script>
    <![endif]-->
    <style>
    #cbp-spmenu-s1
    {
      padding-bottom: 80px;
    }
  </style>
</head>
<body>

\t<div class=\"body-content\">
  \t<div class=\"overlay\"></div>

  \t";

?>
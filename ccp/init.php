<?php
declare(strict_types = 1);
namespace ccp;
use Poker\Ccp\Model\Constant;
use Poker\Ccp\Utility\SessionUtility;
require_once "bootstrap.php";
// check if site is down
$file_handle = fopen(filename: "status.txt", mode: "a+");
$contents = file_get_contents(filename: "status.txt");
//fwrite($file_handle, "status=503.php");
$values = explode(separator: "=", string: $contents);
// echo $values[0] . " = " . $values[1];
if ($values[1] != "") {
  header(header: "Location: " . $values[1]);
}
// TODO: NOT SURE WHERE TO PUT THIS
date_default_timezone_set(timezoneId: Constant::NAME_TIME_ZONE);
if (strpos(haystack: $_SERVER["SCRIPT_NAME"], needle: "index.php") === false) {
  require_once "initDefine.php";
  require_once "initSmarty.php";
  require_once "initTidy.php";
}
if (strpos(haystack: $_SERVER["SCRIPT_NAME"], needle: "index.php") === false && strpos(haystack: $_SERVER["SCRIPT_NAME"], needle: "logout.php") === false) {
  SessionUtility::startSession();
  // if no session and not login or password reset pages capture page to redirect after login
  if (!SessionUtility::existsSecurity() && strpos(haystack: $_SERVER["SCRIPT_NAME"], needle: "login.php") === false && strpos(haystack: $_SERVER["SCRIPT_NAME"], needle: "resetPassword.php") === false && strpos(haystack: $_SERVER["SCRIPT_NAME"], needle: "signup.php") === false) {
    $scriptName = explode(separator: "/", string: $_SERVER["SCRIPT_NAME"]);
    // echo $scriptName[count($scriptName) - 1];
    header(header: "Location: login.php?" . $scriptName[count($scriptName) - 1]);
  }
}
// if any auto or manage pages and not administrator display not authorized message
//if (strpos($_SERVER["SCRIPT_NAME"], "auto") !== false || strpos($_SERVER["SCRIPT_NAME"], "manage") !== false) {
  //echo "<br>adm -> " . SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_ADMINISTRATOR);
  //echo "<br>uid -> " . SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_PLAYERID);
  //echo "<BR>GET -> " . print_r($_GET, true);
  //echo "<br>get id -> " . $_GET["id"];
//if (SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_ADMINISTRATOR) != Constant::FLAG_YES_DATABASE && (!isset($_GET["id"]) || SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_PLAYERID) != $_GET["id"])) {
    //echo "<h1 style=\"color: red;\">You are not authorized to access this page!!</h1>";
    //die();
  //}
//}
require_once "initDefaults.php";
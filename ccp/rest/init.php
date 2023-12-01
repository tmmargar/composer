<?php
declare(strict_types = 1);
namespace ccp;
use Poker\Ccp\classes\model\Constant;
use Poker\Ccp\classes\utility\SessionUtility;
require_once "../autoload.php";
// check if site is down
$file_handle = fopen(filename: "../status.txt", mode: "a+");
$contents = file_get_contents(filename: "../status.txt");
//fwrite($file_handle, "status=503.php");
$values = explode(separator: "=", string: $contents);
// echo $values[0] . " = " . $values[1];
if ($values[1] != "") {
  header(header: "Location: " . $values[1]);
}
// TODO: NOT SURE WHERE TO PUT THIS
date_default_timezone_set(timezoneId: Constant::NAME_TIME_ZONE);
if (strpos(haystack: $_SERVER["SCRIPT_NAME"], needle: "index.php") === false) {
  require_once "../initDefine.php";
  require_once "initSmarty.php";
  require_once "../initTidy.php";
}
require_once "initDefaults.php";
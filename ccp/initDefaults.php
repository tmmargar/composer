<?php
declare(strict_types = 1);
namespace ccp;
use Poker\Ccp\classes\model\Constant;
use Poker\Ccp\classes\model\DatabaseResult;
use Poker\Ccp\classes\model\DateTime;
use Poker\Ccp\classes\utility\SessionUtility;
require_once "vendor/autoload.php";
$header = "";
$databaseResult = new DatabaseResult(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG));
// $databaseResult = new DatabaseResult(true);
if (strpos($_SERVER["SCRIPT_NAME"], "login.php") === false && strpos($_SERVER["SCRIPT_NAME"], "logout.php") === false && strpos($_SERVER["SCRIPT_NAME"], "resetPassword.php") === false) {
  require_once "navigation.php";
  $now = new DateTime(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: NULL, time: "now");
  $params = array($now->getDatabaseDateTimeFormat());
  $resultList = $databaseResult->getNotification(params: $params, returnQuery: false);
  foreach ($resultList as $notification) {
    if ("" != $header) {
      $header .= "<br>";
    }
    $header .= "***" . $notification->getDescription() . "***";
  }
}
$smarty->assign("header", $header);
$aryScriptFull = explode(separator: "/", string: $_SERVER["SCRIPT_NAME"]);
$scriptName = $aryScriptFull[count($aryScriptFull) - 1];
$aryScript = explode(separator: ".", string: $scriptName);
$scriptNameNoExtension = $aryScript[0];
$smarty->assign("script", "<script src=\"scripts/" . $scriptNameNoExtension . ".js\" type=\"module\"></script>\n");
$smarty->assign("style", "");
$smarty->assign("action", $_SERVER["SCRIPT_NAME"]);
$mode = isset($_POST[Constant::FIELD_NAME_MODE]) ? $_POST[Constant::FIELD_NAME_MODE] : (isset($_GET[Constant::FIELD_NAME_MODE]) ? $_GET[Constant::FIELD_NAME_MODE] : Constant::MODE_VIEW);
$smarty->assign("mode", $mode);
$ids = isset($_POST[SELECTED_ROWS_FIELD_NAME]) ? $_POST[SELECTED_ROWS_FIELD_NAME] : DEFAULT_VALUE_BLANK;
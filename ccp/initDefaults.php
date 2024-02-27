<?php
declare(strict_types = 1);
namespace ccp;
use DateTime;
use Poker\Ccp\Model\Constant;
use Poker\Ccp\Utility\SessionUtility;
require_once "vendor/autoload.php";
$header = "";
if (strpos($_SERVER["SCRIPT_NAME"], "login.php") === false && strpos($_SERVER["SCRIPT_NAME"], "logout.php") === false && strpos($_SERVER["SCRIPT_NAME"], "resetPassword.php") === false) {
  require_once "navigation.php";
  $resultList = $entityManager->getRepository(Constant::ENTITY_NOTIFICATIONS)->getByDate(date: new DateTime());
  foreach ($resultList as $notification) {
    if ("" != $header) {
      $header .= "<br>";
    }
    $header .= "***" . $notification->getNotificationDescription() . "***";
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
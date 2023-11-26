<?php
declare(strict_types = 1);
namespace Poker\Ccp;
use Poker\Ccp\classes\model\DatabaseResult;
use Poker\Ccp\classes\utility\SessionUtility;
require_once "init.php";
if (!defined("USER_ID_PARAM_NAME")) {define("USER_ID_PARAM_NAME", "userId");}
$smarty->assign("title", "Chip Chair and a Prayer My Stats");
$outputPersonalize = 
  "<div class=\"responsive responsive--4cols responsive--collapse\">\n";
$heading = "";
$userId = (isset($_POST[USER_ID_PARAM_NAME]) ? $_POST[USER_ID_PARAM_NAME] : isset($_GET[USER_ID_PARAM_NAME])) ? $_GET[USER_ID_PARAM_NAME] : "";
if ($userId == "" || SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_USERID) == $userId) {
  $heading .= "My";
} else {
  $params = array($userId);
  $resultList = $databaseResult->getUserById(params: $params);
  $heading .= $resultList[0]->getName() . "'s";
}
$heading .= " Stats";
$outputPersonalize .=
  " <div class=\"responsive-cell\">\n";
$reportId = "tournamentsPlayedForUser";
$parentObjectId = "widget1";
$outputPersonalize .= include "top5.php";
$outputPersonalize .=
  " </div>\n" .
  " <div class=\"responsive-cell\">\n";
$reportId = "winsForUser";
$parentObjectId = "widget2";
$outputPersonalize .= include "top5.php";
$outputPersonalize .=
  " </div>\n" .
  " <div class=\"responsive-cell\">\n";
$reportId = "pointsTotalForUser";
$parentObjectId = "widget3";
$outputPersonalize .= include "top5.php";
$outputPersonalize .=
  " </div>\n" .
  " <div class=\"responsive-cell\">\n";
$reportId = "earningsTotalForUser";
$parentObjectId = "widget4";
$outputPersonalize .= include "top5.php";
$outputPersonalize .=
  " </div>\n" .
  " <div class=\"responsive-cell\">\n";
$reportId = "pointsTotalForSeasonForUser";
$parentObjectId = "widget5";
$outputPersonalize .= include "top5.php";
$outputPersonalize .=
  " </div>\n" .
  " <div class=\"responsive-cell\">\n";
$reportId = "earningsTotalForSeasonForUser";
$parentObjectId = "widget6";
$outputPersonalize .= include "top5.php";
$outputPersonalize .=
  " </div>\n" .
  " <div class=\"responsive-cell\">\n";
$reportId = "winsTotalForSeasonForUser";
$parentObjectId = "widget7";
$outputPersonalize .= include "top5.php";
$outputPersonalize .=
  " </div>\n" .
  " <div class=\"responsive-cell\">\n";
$reportId = "knockoutsTotalForSeasonForUser";
$parentObjectId = "widget8";
$outputPersonalize .= include "top5.php";
$outputPersonalize .=
  " </div>\n" .
  " <div class=\"responsive-cell\">\n";
$reportId = "knockoutsTotalForUser";
$parentObjectId = "widget9";
$outputPersonalize .= include "top5.php";
$outputPersonalize .=
  " </div>\n" .
  " <div class=\"responsive-cell\">\n";
$reportId = "nemesisForUser";
$parentObjectId = "widget10";
$outputPersonalize .= include "top5.php";
$outputPersonalize .=
  " </div>\n" .
  " <div class=\"responsive-cell\">\n";
$reportId = "bullyForUser";
$parentObjectId = "widget11";
$outputPersonalize .= include "top5.php";
$outputPersonalize .=
  " </div>\n" .
  " <div class=\"responsive-cell\">\n";
$reportId = "finishesForUser";
$parentObjectId = "widget14";
$outputPersonalize .= include "top5.php";
$outputPersonalize .= 
  " </div>\n" .
  " <div class=\"responsive-cell\">\n";
$reportId = "tournamentsWonForUser";
$parentObjectId = "widget13";
$outputPersonalize .= include "top5.php";
$outputPersonalize .= 
  " </div>\n" .
  " <div class=\"responsive-cell\">\n" .
//   " </div>\n" .
//   " <div class=\"responsive-cell\">\n" .
  " </div>\n";
$smarty->assign("heading", $heading);
$smarty->assign("content", $outputPersonalize);
$smarty->display("personalize.tpl");
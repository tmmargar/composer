<?php
declare(strict_types = 1);
namespace ccp;
use Poker\Ccp\classes\model\Constant;
use Poker\Ccp\classes\utility\SessionUtility;
require_once "init.php";
if (!defined("USER_ID_PARAM_NAME")) {define("USER_ID_PARAM_NAME", "userId");}
$smarty->assign("title", "Chip Chair and a Prayer My Stats");
$outputPersonalize =
  "<div class=\"responsive responsive--4cols responsive--collapse\">\n";
$heading = "";
$userId = (int) ((isset($_POST[USER_ID_PARAM_NAME]) ? $_POST[USER_ID_PARAM_NAME] : isset($_GET[USER_ID_PARAM_NAME])) ? $_GET[USER_ID_PARAM_NAME] : 0);
if ($userId == "" || SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_USERID) == $userId) {
  $heading .= "My";
} else {
  $params = array($userId);
  $resultList = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getById(playerId: $userId);
  $heading .= $resultList[0]->getPlayerName() . "'s";
}
$heading .= " Stats";
$outputPersonalize .=
  " <div class=\"responsive-cell\">\n";
$reportId = "tournamentsPlayedForPlayer";
$parentObjectId = "widget1";
$outputPersonalize .= include "top5.php";
$outputPersonalize .=
  " </div>\n" .
  " <div class=\"responsive-cell\">\n";
$reportId = "winsForPlayer";
$parentObjectId = "widget2";
$outputPersonalize .= include "top5.php";
$outputPersonalize .=
  " </div>\n" .
  " <div class=\"responsive-cell\">\n";
$reportId = "pointsTotalForPlayer";
$parentObjectId = "widget3";
$outputPersonalize .= include "top5.php";
$outputPersonalize .=
  " </div>\n" .
  " <div class=\"responsive-cell\">\n";
$reportId = "earningsTotalForPlayer";
$parentObjectId = "widget4";
$outputPersonalize .= include "top5.php";
$outputPersonalize .=
  " </div>\n" .
  " <div class=\"responsive-cell\">\n";
$reportId = "pointsTotalForSeasonForPlayer";
$parentObjectId = "widget5";
$outputPersonalize .= include "top5.php";
$outputPersonalize .=
  " </div>\n" .
  " <div class=\"responsive-cell\">\n";
$reportId = "earningsTotalForSeasonForPlayer";
$parentObjectId = "widget6";
$outputPersonalize .= include "top5.php";
$outputPersonalize .=
  " </div>\n" .
  " <div class=\"responsive-cell\">\n";
$reportId = "winsTotalForSeasonForPlayer";
$parentObjectId = "widget7";
$outputPersonalize .= include "top5.php";
$outputPersonalize .=
  " </div>\n" .
  " <div class=\"responsive-cell\">\n";
$reportId = "knockoutsTotalForSeasonForPlayer";
$parentObjectId = "widget8";
$outputPersonalize .= include "top5.php";
$outputPersonalize .=
  " </div>\n" .
  " <div class=\"responsive-cell\">\n";
$reportId = "knockoutsTotalForPlayer";
$parentObjectId = "widget9";
$outputPersonalize .= include "top5.php";
$outputPersonalize .=
  " </div>\n" .
  " <div class=\"responsive-cell\">\n";
$reportId = "nemesisForPlayer";
$parentObjectId = "widget10";
$outputPersonalize .= include "top5.php";
$outputPersonalize .=
  " </div>\n" .
  " <div class=\"responsive-cell\">\n";
$reportId = "bullyForPlayer";
$parentObjectId = "widget11";
$outputPersonalize .= include "top5.php";
$outputPersonalize .=
  " </div>\n" .
  " <div class=\"responsive-cell\">\n";
$reportId = "finishesForPlayer";
$parentObjectId = "widget14";
$outputPersonalize .= include "top5.php";
$outputPersonalize .=
  " </div>\n" .
  " <div class=\"responsive-cell\">\n";
$reportId = "tournamentsWonForPlayer";
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
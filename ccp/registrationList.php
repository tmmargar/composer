<?php
declare(strict_types = 1);
namespace Poker\Ccp;
use Poker\Ccp\classes\model\Constant;
use Poker\Ccp\classes\model\DatabaseResult;
use Poker\Ccp\classes\model\DateTime;
use Poker\Ccp\classes\utility\SessionUtility;
use DateInterval;
require_once "init.php";
define("LIMIT_COUNT_PARAMETER_NAME", "limitCount");
$smarty->assign("title", "Chip Chair and a Prayer Events");
$smarty->assign("heading", "");
if (! isset($limitCount)) {
  $limitCount = (isset($_POST[LIMIT_COUNT_PARAMETER_NAME]) ? $_POST[LIMIT_COUNT_PARAMETER_NAME] : isset($_GET[LIMIT_COUNT_PARAMETER_NAME])) ? $_GET[LIMIT_COUNT_PARAMETER_NAME] : NULL;
}
$output = "";
if (NULL != $limitCount) {
  $output .= "<div class=\"title\">Upcoming Events</div>\n";
}
$now = new DateTime(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: NULL, time: "now");
$params = array($now->getDatabaseFormat(), $now->getDatabaseTimeFormat());
$paramsNested = array(SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_START_DATE)->getDatabaseFormat(), SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_END_DATE)->getDatabaseFormat(), SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_CHAMPIONSHIP_QUALIFY));
$resultList = $databaseResult->getTournamentByDateAndStartTime(params: $params, paramsNested: $paramsNested, limitCount: $limitCount);
foreach ($resultList as $tournament) {
  $startTime = $tournament->getStartTime()->getDisplayAmPmFormat();
  $registrationOpenDate = new DateTime(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: NULL, time: $tournament->getDate()->getDatabaseFormat() . " " . $tournament->getRegistrationOpen()->getDisplayAmPmFormat());
  $interval = new DateInterval(Constant::INTERVAL_DATE_REGISTRATION_OPEN);
  $registrationOpenDateTemp = $registrationOpenDate->getTime();
  $registrationOpenDateTemp->sub($interval);
  $registrationOpen = ($now >= $registrationOpenDate);
  $url = "registration.php?tournamentId=" . $tournament->getId();
  $output .= "<div>";
  if ($registrationOpen) {
    $output .= "<a href=\"" . $url . "\">";
  }
  if ($registrationOpen) {
    $output .= "</a>";
  }
//   $description = explode(", ", $tournament->getDescription());
//   if (count($description) == 1) {
//     $description = explode(" - ", $tournament->getDescription());
//     if (count($description) > 1) {
//       $description = explode("<br>", $description[1]);
//     }
//   }
  if ($registrationOpen) {
    $output .= "<a href=\"" . $url . "\">";
  }
//   $output .= $description[0];
  $output .= $tournament->getDescription();
  if (NULL != $tournament->getSpecialType()->getDescription()) {
    $output .= " (" . $tournament->getSpecialType()->getDescription() . ")";
  }
  if ($registrationOpen) {
    $output .= "</a>";
  }
  $output .= "<br />\n";
  $output .= $tournament->getDate()->getDisplayFormat() ." " . $startTime;
  $output .= "<br />\n";
  if ($registrationOpen) {
    $output .= "<a href=\"" . $url . "\">";
    $output .= $tournament->getLimitType()->getName();
    $output .= " ";
    $output .= $tournament->getGameType()->getName();
    $output .= ($tournament->getRebuyAmount() != 0 ? Constant::DISPLAY_REBUY : "");
    if ($tournament->getRebuyAmount() == 0 && $tournament->getAddonAmount() != 0) {
      $output .= " ";
    }
    $output .= ($tournament->getAddonAmount() != 0 ? Constant::DISPLAY_ADDON : "");
    $waitListCnt = $tournament->getRegisteredCount() - $tournament->getMaxPlayers();
    $output .= "<br />\n";
    $output .= $tournament->getLocation()->getName();
    $output .= "<br />\n";
    if ($waitListCnt > 0) {
      $output .= "Seating " . $tournament->getMaxPlayers() . " of " . $tournament->getMaxPlayers();
      $output .= " claimed";
      $output .= "<br />\n";
      $output .= $waitListCnt . " wait listed</a>\n";
    } else {
      $output .= "Seating " . $tournament->getRegisteredCount() . " of " . $tournament->getMaxPlayers() . " claimed</a>\n";
    }
  } else {
    $output .= $tournament->getLimitType()->getName();
    $output .= " ";
    $output .= $tournament->getGameType()->getName();
    $output .= ($tournament->getRebuyAmount() != 0 ? Constant::DISPLAY_REBUY : "");
    if ($tournament->getRebuyAmount() == 0 && $tournament->getAddonAmount() != 0) {
      $output .= " ";
    }
    $output .= ($tournament->getAddonAmount() != 0 ? Constant::DISPLAY_ADDON : "");
    $output .= "<br />\n";
    $output .= $tournament->getLocation()->getName();
    $output .= "<br />\n";
    $output .= "Registration Begins " . $registrationOpenDate->getDisplayRegistrationNotOpenFormat() . " @ Noon";
  }
  $output .= "</div>\n<hr />";
}
if (! $resultList) {
  $output .= "No scheduled tournaments";
}
if (isset($parentObjectId)) {
  return $output;
} else {
  $smarty->assign("content", $output);
  $smarty->display("registrationList.tpl");
}
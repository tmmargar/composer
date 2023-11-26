<?php
declare(strict_types = 1);
namespace Poker\Ccp;
use Poker\Ccp\classes\model\Constant;
require_once "init.php";
$params = array(Constant::FLAG_YES_DATABASE);
$resultList = $databaseResult->getSeasonByActive(params: $params);
$season = $resultList[0];
$params = array($_POST["tournamentDate"],$_POST["tournamentDate"]);
$paramsNested = array($season->getStartDate()->getDatabaseFormat(),$season->getEndDate()->getDatabaseFormat(),$season->getChampionshipQualify());
$resultList = $databaseResult->getTournamentByDateAndStartTime(params: $params, paramsNested: $paramsNested, limitCount: NULL);
$tournament = $resultList[$_POST["first"]];
$params = array(NULL,NULL,NULL,Constant::CODE_STATUS_PAID,0,NULL,$tournament->getId()); // , $maxPlace);
$rowCount = $databaseResult->updateResultByTournamentIdAndPlace(params: $params);
echo "<br>" . chr(13) . chr(10) . $rowCount . " rows updated to paid";
$index = - 1;
foreach ($_POST["firstName"] as $firstName) {
  if ($firstName == "") {
    break;
  }
  $index ++;
  echo "<br>" . chr(13) . chr(10) . "idx->" . $index;
  $fullName = $firstName . " " . $_POST["lastName"][$index];
  $params = array($fullName);
  $resultList = $databaseResult->getUserByName(params: $params);
  if (0 < count($resultList)) {
    $user = $resultList[0];
  }
  if ("N/A" != $_POST["feePaid"][$index]) {
    $params = array($_POST["feePaid"][$index],$season->getId(),$user->getId(),$tournament->getId());
    $rowCount = $databaseResult->updateFees(params: $params);
  }
  $fullNameKO = $_POST["knockedOut"][$index];
  $params = array($fullNameKO);
  $resultList = $databaseResult->getUserByName(params: $params);
  if (0 < count($resultList)) {
    $userKO = $resultList[0];
  } else {
    $userKO = NULL;
  }
  $params = array($tournament->getId(),$user->getId());
  $resultList = $databaseResult->getResultByTournamentIdAndPlayerId(params: $params);
  $userResult = $resultList[0];
  // rebuycount, rebuypaid, addonpaid, statuscode, place, kobyid, playerid
  $params = array($_POST["rebuyCount"][$index],$_POST["rebuy"][$index],$_POST["addon"][$index],Constant::CODE_STATUS_FINISHED,$_POST["place"][$index],NULL == $userKO ? NULL : $userKO->getId(),$tournament->getId(),$user->getId());
  $rowCount = $databaseResult->updateResult(params: $params);
  echo "<br>" . chr(13) . chr(10) . $rowCount . " rows updated to finished for tournament id " . $tournament->getId() . " and user id " . $user->getId();
}
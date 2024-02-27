<?php
declare(strict_types = 1);
namespace ccp;
use DateTime;
use Poker\Ccp\Model\Constant;
use Poker\Ccp\Entity\Fees;
require_once "init.php";
$entityManager = getEntityManager();
$resultList = $entityManager->getRepository(Constant::ENTITY_SEASONS)->getActives();
$season = $resultList;
$resultList = $entityManager->getRepository(Constant::ENTITY_TOURNAMENTS)->getAllMultiple(championship: false, tournamentDate: new DateTime(datetime: $_POST["tournamentDate"]), startTime: new DateTime(datetime: $_POST["tournamentDate"]), tournamentId: NULL, notEntered: false, ordered: false, mode: NULL, interval: NULL, limitCount: NULL);
// first tournament is 0 second is 1
$row = $resultList[$_POST["first"]];
$rowCount = $entityManager->getRepository(Constant::ENTITY_RESULTS)->updateFinish(rebuyCount: NULL, rebuyPaidFlag: NULL, addonPaidFlag: NULL, addonFlag: NULL, statusCode: Constant::CODE_STATUS_PAID, place: 0, playerIdKo: NULL, tournamentId: $row["id"], playerId: NULL);
echo "<br>" . chr(13) . chr(10) . $rowCount . " rows updated to paid";
$index = - 1;
foreach ($_POST["firstName"] as $firstName) {
    if ($firstName == "") {
        break;
    }
    $index++;
    echo "<br>" . chr(13) . chr(10) . "idx->" . $index;
    $fullName = $firstName . " " . $_POST["lastName"][$index];
    $resultList = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getByName(name: $fullName);
    if (0 < count($resultList)) {
        $player = $resultList[0];
    }
    if ("N/A" != $_POST["feePaid"][$index]) {
        $seasonFind = $entityManager->find(Constant::ENTITY_SEASONS, $season->getSeasonId());
        $playerFind = $entityManager->find(Constant::ENTITY_PLAYERS, $player->getPlayerId());
        $tournamentFind = $entityManager->find(Constant::ENTITY_TOURNAMENTS, $row["id"]);
        $feeFind = $entityManager->getRepository(Constant::ENTITY_FEES)->findOneBy(array("seasons" => $seasonFind, "players" => $playerFind, "tournaments" => $tournamentFind));
        if (NULL !== $feeFind) {
            $feeFind->setFeeAmount((int) $_POST["feePaid"][$index]);
            $entityManager->persist($feeFind);
            try {
                $entityManager->flush();
            } catch (Exception $e) {
                echo "<br>" . $e->getMessage();
            }
        } else {
            $rowCount = $entityManager->getRepository(Constant::ENTITY_FEES)->deleteForSeasonAndPlayer(seasonId: $season->getSeasonId(), playerId: $player->getPlayerId());
            $fe = new Fees();
            $fe->setFeeAmount((int) $_POST["feePaid"][$index]);
            $player = $entityManager->find(Constant::ENTITY_PLAYERS, $player->getPlayerId());
            $fe->setPlayers($player);
            $season = $entityManager->find(Constant::ENTITY_SEASONS, $season->getSeasonId());
            $fe->setSeasons($season);
            $tournament = $entityManager->find(Constant::ENTITY_TOURNAMENTS, $row["id"]);
            $fe->setTournaments($tournament);
            $entityManager->persist($fe);
            try {
                $entityManager->flush();
            } catch (Exception $e) {
                $errors = $e->getMessage();
            }
        }
    }
    $fullNameKO = $_POST["knockedOut"][$index];
    if ("" == $fullNameKO) {
        $userKO = NULL;
    } else {
        $params = array($fullNameKO);
        $resultList = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getByName(name: $fullNameKO);
        if (0 < count($resultList)) {
            $userKO = $resultList[0];
        }
    }
    $resultList = $entityManager->getRepository(Constant::ENTITY_RESULTS)->getPaidOrRegisteredOrFinished(tournamentId: $row["id"], playerId: $player->getPlayerId(), paid: false, registered: false, finished: false, indexed: false);
    $userResult = $resultList[0];
    $rowCount = $entityManager->getRepository(Constant::ENTITY_RESULTS)->updateFinish(rebuyCount: (int) $_POST["rebuyCount"][$index], rebuyPaidFlag: $_POST["rebuy"][$index], addonPaidFlag: $_POST["addon"][$index], addonFlag: NULL, statusCode: Constant::CODE_STATUS_FINISHED, place: (int) $_POST["place"][$index], playerIdKo: NULL == $userKO ? NULL : $userKO->getPlayerId(), tournamentId: $row["id"], playerId: $player->getPlayerId());
    echo "<br>" . chr(13) . chr(10) . $rowCount . " rows updated to finished for tournament id " . $row["id"] . " and player id " . $player->getPlayerId();
}
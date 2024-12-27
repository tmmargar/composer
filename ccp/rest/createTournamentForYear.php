<?php
declare(strict_types = 1);
namespace ccp;
use DateTime;
use Poker\Ccp\Model\Constant;
use Poker\Ccp\Entity\Tournaments;
require_once "init.php";
$entityManager = getEntityManager();
$resultList = $entityManager->getRepository(entityName: Constant::ENTITY_SEASONS)->getMaxId();
$seasonId = ((int) $resultList[1]) + 1;
$index = - 1;
$tournamentIndex = 0;
foreach ($_POST["host"] as $fullName) {
    if ($fullName == "") {
        break;
    }
    $index++;
    $tournamentIndex++;
//     echo "<br>" . chr(13) . chr(10) . "idx->" . $index . "-->" . $fullName;
    $resultList = $entityManager->getRepository(entityName: Constant::ENTITY_PLAYERS)->getByName(name: $fullName);
    if (0 < count($resultList)) {
        $player = $resultList[0];
    }
    $specialTypes = $entityManager->getRepository(entityName: Constant::ENTITY_SPECIAL_TYPES)->getByName(specialTypeDescription: $_POST["specialType"][$index]);
    $to = new Tournaments();
    $gameTypes = $entityManager->getRepository(entityName: Constant::ENTITY_GAME_TYPES)->getByName(gameTypeName: "Hold 'Em");
    $to->setGameTypes(gameTypes: $gameTypes[0]);
    $groups = $entityManager->getRepository(entityName: Constant::ENTITY_GROUPS)->getByName(groupName: "Standard");
    $to->setGroups(groups: $groups[0]);
    $limitTypes = $entityManager->getRepository(entityName: Constant::ENTITY_LIMIT_TYPES)->getByName(limitTypeName: "No Limit (NL)");
    $to->setLimitTypes(limitTypes: $limitTypes[0]);
    $location = $entityManager->getRepository(entityName: Constant::ENTITY_LOCATIONS)->getByPlayerId(playerId: $player->getPlayerId());
    $to->setLocations(locations: $location[0]);
    $to->setTournamentAddonAmount(tournamentAddonAmount: 1 < $_POST["numEvents"][$index] ? 10 : 0);
    $to->setTournamentAddonChipCount(tournamentAddonChipCount: 1 < $_POST["numEvents"][$index] ? 1500 : 0);
    $to->setTournamentBuyinAmount(tournamentBuyinAmount: 1 < $_POST["numEvents"][$index] ? 30 : ($_POST["specialType"][$index] == "Main Event" ? 100 : 60));
    $to->setTournamentChipCount(tournamentChipCount: 1 < $_POST["numEvents"][$index] ? 3000 : ($_POST["specialType"][$index] == "Main Event" ? 15000 : 10000));
    $to->setTournamentComment(tournamentComment: NULL);
    $to->setTournamentDate(tournamentDate: new DateTime(datetime: $_POST["tournamentDate"][$index]));
    $to->setTournamentDescription(tournamentDescription: "S" . $seasonId . " - T" . $tournamentIndex);
    $to->setTournamentMap(tournamentMap: NULL);
    $to->setTournamentMaxPlayers(tournamentMaxPlayers: (int) $_POST["maxPlayers"][$index]);
    $to->setTournamentMaxRebuys(tournamentMaxRebuys: 1 < $_POST["numEvents"][$index] ? 99 : 0);
    $to->setTournamentRake(tournamentRake: ".2");
    $to->setTournamentRebuyAmount(tournamentRebuyAmount: 1 < $_POST["numEvents"][$index] ? 30 : 0);
    $to->setTournamentStartTime(tournamentStartTime: new DateTime(datetime: 1 < $_POST["numEvents"][$index] ? "14:00" : "17:00"));
    $to->setSpecialTypes(0 == count($specialTypes) ? NULL : $specialTypes[0]);
    $entityManager->persist(entity: $to);
    $entityManager->flush();
    if (1 < $_POST["numEvents"][$index]) {
        $tournamentIndex++;
        $to2 = clone ($to);
        $to2->setTournamentAddonAmount(tournamentAddonAmount: 0);
        $to2->setTournamentAddonChipCount(tournamentAddonChipCount: 0);
        $to2->setTournamentBuyinAmount(tournamentBuyinAmount: 30);
        $to2->setTournamentChipCount(tournamentChipCount: 5000);
        $to2->setTournamentComment(tournamentComment: NULL);
        $to2->setTournamentDate(tournamentDate: new DateTime(datetime: $_POST["tournamentDate"][$index]));
        $to2->setTournamentDescription(tournamentDescription: "S" . $seasonId . " - T" . $tournamentIndex);
        $to2->setTournamentMap(tournamentMap: NULL);
        $to2->setTournamentMaxRebuys(tournamentMaxRebuys: 0);
        $to2->setTournamentRake(tournamentRake: ".2");
        $to2->setTournamentRebuyAmount(tournamentRebuyAmount: 0);
        $to2->setTournamentStartTime(tournamentStartTime: new DateTime(datetime: "19:30"));
        $entityManager->persist(entity: $to2);
        $entityManager->flush();
    }
    echo "<br>" . chr(13) . chr(10) . (1 < $_POST["numEvents"][$index] ? 2 : 1) . " rows inserted for tournament date = " . $_POST["tournamentDate"][$index] . ", host = " . $fullName . ", max players = " . $_POST["maxPlayers"][$index] . ", special type = " . $_POST["specialType"][$index];
}
// update last which is championship
$to->setTournamentAddonChipCount(tournamentAddonChipCount: 0);
$to->setTournamentBuyinAmount(tournamentBuyinAmount: 0);
$to->setTournamentChipCount(tournamentChipCount: 0);
$to->setTournamentRake(tournamentRake: "0");
$groups = $entityManager->getRepository(entityName: Constant::ENTITY_GROUPS)->getByName(groupName: "Championship FT9");
$to->setGroups(groups: $groups[0]);
$entityManager->persist(entity: $to);
$entityManager->flush();
<?php
declare(strict_types = 1);
namespace ccp;
use DateTime;
use Poker\Ccp\classes\model\Constant;
use Poker\Ccp\classes\model\Email;
use Poker\Ccp\classes\model\GameType;
use Poker\Ccp\classes\model\Group;
use Poker\Ccp\classes\model\GroupPayout;
use Poker\Ccp\classes\model\LimitType;
use Poker\Ccp\classes\model\Location;
use Poker\Ccp\classes\model\Payout;
use Poker\Ccp\classes\model\Phone;
use Poker\Ccp\classes\model\Player;
use Poker\Ccp\classes\model\Result;
use Poker\Ccp\classes\model\SpecialType;
use Poker\Ccp\classes\model\Status;
use Poker\Ccp\classes\model\Structure;
use Poker\Ccp\classes\model\Tournament;
use Poker\Ccp\classes\utility\DateTimeUtility;
use Poker\Ccp\classes\utility\SessionUtility;
require_once "init.php";
if (Constant::FLAG_LOCAL()) {
    set_time_limit(240);
}
$now = new DateTime();
$output .=
    "<script type=\"module\">\n" .
    "  import { dataTable, display, input } from \"./scripts/import.js\";\n" .
    "  let aryMessages = [];\n";
$output .= isset($mode) ? "  aryMessages.push(\"###Run at " . DateTimeUtility::formatDisplayLong(value: $now) . "###\");\n" : "\r";
$days = 14;
$resultList = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getActivesForNotification();
$resultList2 = $entityManager->getRepository(Constant::ENTITY_TOURNAMENTS)->getAllMultiple(championship: false, tournamentDate: NULL, startTime: NULL, tournamentId: NULL, notEntered: false, ordered: false, mode: NULL, interval: $days, limitCount: NULL);
if (count($resultList2) == 0) {
    $output .= isset($mode) ? "  aryMessages.push(\"No tournaments needing auto email notification for registration open (" . $days . " days before)\");\n" : "\r";
} else {
    foreach ($resultList as $player) {
        foreach ($resultList2 as $tournaments) {
            $row = $tournaments;
            $limitType = new LimitType(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: $row["limit_type_id"], name: $row["name"]);
            $gameType = new GameType(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: $row["game_type_id"], name: $row["type"]);
            $specialType = new SpecialType(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: $row["special_type_id"], description: $row["std"], multiplier: $row["special_type_multiplier"]);
            $playerLocation = new Player(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: 0, name: "", username: NULL, password: NULL, email: NULL, phone: NULL, administrator: "0", registrationDate: new \DateTime(), approvalDate: NULL, approvalUserid: NULL, approvalName: NULL, rejectionDate: NULL, rejectionUserid: NULL, rejectionName: NULL, active: "0", resetSelector: NULL, resetToken: NULL, resetExpires: NULL, rememberSelector: NULL, rememberToken: NULL, rememberExpires: NULL);
            $result = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getById(playerId: $row["player_id"]);
            $playerLocation->createFromEntity(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), players: $result[0]);
            $location = new Location(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: $row["location_id"], name: $row["location"], address: "", city: "", state: "", zipCode: 00000, player: $playerLocation, count: 0, active: "0", map: NULL, mapName: NULL, tournamentCount: 0);
            $result = $entityManager->getRepository(Constant::ENTITY_LOCATIONS)->getById(locationId: $row["location_id"]);
            $location->createFromEntity(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), locations: $result[0]);
            $group = new Group(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: 0, name: "");
            $result = $entityManager->getRepository(Constant::ENTITY_GROUPS)->getById(groupId: $row["group_id"]);
            $group->createFromEntity(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), groups: $result[0]);
            $structure = new Structure(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: 0, place: 0, percentage: 0);
            $result = $entityManager->getRepository(Constant::ENTITY_STRUCTURES)->getById(payoutId: $group->getGroups()->getGroupPayouts()[0]->getPayouts()->getPayoutId());
            $structure->createFromEntity(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), structures: $result[0]);
            $payout = new Payout(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: 0, name: "", minPlayers: 0, maxPlayers: 0, structures: array($structure));
            $result = $entityManager->getRepository(Constant::ENTITY_PAYOUTS)->getById(payoutId: $group->getGroups()->getGroupPayouts()[0]->getPayouts()->getPayoutId());
            $payout->createFromEntity(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), payouts: $result[0]);
            $groupPayout = new GroupPayout(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: "", group: $group, payouts: array($payout));
            $tournament = new Tournament(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: $row["id"], description: $row["description"], comment: $row["comment"], limitType: $limitType, gameType: $gameType, specialType: $specialType, chipCount: $row["chips"], location: $location, date: new \Poker\Ccp\classes\model\DateTime(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: NULL, time: $row["date"]), startTime: new \Poker\Ccp\classes\model\DateTime(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: NULL, time: $row["start"]), buyinAmount: $row["buyin"] == 0 ? 0 : -$row["buyin"], maxPlayers: $row["max players"], maxRebuys: $row["max"], rebuyAmount: $row["amt"] == 0 ? 0 : -$row["amt"], addonAmount: $row["amt "] == 0 ? 0 : -$row["amt "], addonChipCount: $row["chips "], groupPayout: $groupPayout, rake: (float) ($row["rake"] * 100), registeredCount: $row["registeredCount"], buyinsPaid: $row["buyinsPaid"], rebuysPaid: $row["rebuysPaid"], rebuysCount: (int) $row["rebuysCount"], addonsPaid: $row["addonsPaid"], enteredCount: $row["enteredCount"], earnings: 0);
            $email = new Email(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), fromName: array(Constant::NAME_STAFF), fromEmail: array(Constant::EMAIL_STAFF()), toName: array($player->getPlayerName()), toEmail: array($player->getPlayerEmail()), ccName: NULL, ccEmail: NULL, bccName: NULL, bccEmail: NULL, subject: NULL, body: NULL);
            $output .= isset($mode) ? "  aryMessages.push(\"" . $email->sendReminderEmail(location: $tournament->getLocation(), tournament: $tournament, waitListCount: 0) . "\");\n" : "\r";
        }
    }
}
$days = 7;
$resultList = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getActivesForNotification();
$resultList2 = $entityManager->getRepository(Constant::ENTITY_TOURNAMENTS)->getAllMultiple(championship: false, tournamentDate: NULL, startTime: NULL, tournamentId: NULL, notEntered: false, ordered: false, mode: NULL, interval: $days, limitCount: NULL);
if (count($resultList2) == 0) {
    $output .= isset($mode) ? "  aryMessages.push(\"No tournaments needing auto email notification for registration open (" . $days . " days before)\");\n" : "\r";
} else {
    foreach ($resultList as $player) {
        foreach ($resultList2 as $tournaments) {
            $row = $tournaments;
            $limitType = new LimitType(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: $row["limit_type_id"], name: $row["name"]);
            $gameType = new GameType(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: $row["game_type_id"], name: $row["type"]);
            $specialType = new SpecialType(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: $row["special_type_id"], description: $row["std"], multiplier: $row["special_type_multiplier"]);
            $playerLocation = new Player(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: 0, name: "", username: NULL, password: NULL, email: NULL, phone: NULL, administrator: "0", registrationDate: new \DateTime(), approvalDate: NULL, approvalUserid: NULL, approvalName: NULL, rejectionDate: NULL, rejectionUserid: NULL, rejectionName: NULL, active: "0", resetSelector: NULL, resetToken: NULL, resetExpires: NULL, rememberSelector: NULL, rememberToken: NULL, rememberExpires: NULL);
            $result = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getById(playerId: $row["player_id"]);
            $playerLocation->createFromEntity(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), players: $result[0]);
            $location = new Location(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: $row["location_id"], name: $row["location"], address: "", city: "", state: "", zipCode: 00000, player: $playerLocation, count: 0, active: "0", map: NULL, mapName: NULL, tournamentCount: 0);
            $result = $entityManager->getRepository(Constant::ENTITY_LOCATIONS)->getById(locationId: $row["location_id"]);
            $location->createFromEntity(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), locations: $result[0]);
            $group = new Group(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: 0, name: "");
            $result = $entityManager->getRepository(Constant::ENTITY_GROUPS)->getById(groupId: $row["group_id"]);
            $group->createFromEntity(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), groups: $result[0]);
            $structure = new Structure(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: 0, place: 0, percentage: 0);
            $result = $entityManager->getRepository(Constant::ENTITY_STRUCTURES)->getById(payoutId: $group->getGroups()->getGroupPayouts()[0]->getPayouts()->getPayoutId());
            $structure->createFromEntity(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), structures: $result[0]);
            $payout = new Payout(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: 0, name: "", minPlayers: 0, maxPlayers: 0, structures: array($structure));
            $result = $entityManager->getRepository(Constant::ENTITY_PAYOUTS)->getById(payoutId: $group->getGroups()->getGroupPayouts()[0]->getPayouts()->getPayoutId());
            $payout->createFromEntity(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), payouts: $result[0]);
            $groupPayout = new GroupPayout(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: "", group: $group, payouts: array($payout));
            $tournament = new Tournament(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: $row["id"], description: $row["description"], comment: $row["comment"], limitType: $limitType, gameType: $gameType, specialType: $specialType, chipCount: $row["chips"], location: $location, date: new \Poker\Ccp\classes\model\DateTime(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: NULL, time: $row["date"]), startTime: new \Poker\Ccp\classes\model\DateTime(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: NULL, time: $row["start"]), buyinAmount: $row["buyin"] == 0 ? 0 : -$row["buyin"], maxPlayers: $row["max players"], maxRebuys: $row["max"], rebuyAmount: $row["amt"] == 0 ? 0 : -$row["amt"], addonAmount: $row["amt "] == 0 ? 0 : -$row["amt "], addonChipCount: $row["chips "], groupPayout: $groupPayout, rake: (float) ($row["rake"] * 100), registeredCount: $row["registeredCount"], buyinsPaid: $row["buyinsPaid"], rebuysPaid: $row["rebuysPaid"], rebuysCount: (int) $row["rebuysCount"], addonsPaid: $row["addonsPaid"], enteredCount: $row["enteredCount"], earnings: 0);
            $email = new Email(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), fromName: array(Constant::NAME_STAFF), fromEmail: array(Constant::EMAIL_STAFF()), toName: array($player->getPlayerName()), toEmail: array($player->getPlayerEmail()), ccName: NULL, ccEmail: NULL, bccName: NULL, bccEmail: NULL, subject: NULL, body: NULL);
            $output .= isset($mode) ? "  aryMessages.push(\"" . $email->sendReminderEmail(location: $tournament->getLocation(), tournament: $tournament, waitListCount: 0) . "\");\n" : "\r";
        }
    }
}
$days = 2;
$resultList = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getActivesForNotification();
$resultList2 = $entityManager->getRepository(Constant::ENTITY_TOURNAMENTS)->getAllMultiple(championship: false, tournamentDate: NULL, startTime: NULL, tournamentId: NULL, notEntered: false, ordered: false, mode: NULL, interval: $days, limitCount: NULL);
if (count($resultList2) == 0) {
    $output .= isset($mode) ? "  aryMessages.push(\"No tournaments needing auto email notification for registration open (" . $days . " days before)\");\n" : "\r";
} else {
    foreach ($resultList as $player) {
        foreach ($resultList2 as $tournaments) {
            $row = $tournaments;
            $limitType = new LimitType(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: $row["limit_type_id"], name: $row["name"]);
            $gameType = new GameType(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: $row["game_type_id"], name: $row["type"]);
            $specialType = new SpecialType(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: $row["special_type_id"], description: $row["std"], multiplier: $row["special_type_multiplier"]);
            $playerLocation = new Player(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: 0, name: "", username: NULL, password: NULL, email: NULL, phone: NULL, administrator: "0", registrationDate: new \DateTime(), approvalDate: NULL, approvalUserid: NULL, approvalName: NULL, rejectionDate: NULL, rejectionUserid: NULL, rejectionName: NULL, active: "0", resetSelector: NULL, resetToken: NULL, resetExpires: NULL, rememberSelector: NULL, rememberToken: NULL, rememberExpires: NULL);
            $result = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getById(playerId: $row["player_id"]);
            $playerLocation->createFromEntity(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), players: $result[0]);
            $location = new Location(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: $row["location_id"], name: $row["location"], address: "", city: "", state: "", zipCode: 00000, player: $playerLocation, count: 0, active: "0", map: NULL, mapName: NULL, tournamentCount: 0);
            $result = $entityManager->getRepository(Constant::ENTITY_LOCATIONS)->getById(locationId: $row["location_id"]);
            $location->createFromEntity(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), locations: $result[0]);
            $group = new Group(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: 0, name: "");
            $result = $entityManager->getRepository(Constant::ENTITY_GROUPS)->getById(groupId: $row["group_id"]);
            $group->createFromEntity(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), groups: $result[0]);
            $structure = new Structure(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: 0, place: 0, percentage: 0);
            $result = $entityManager->getRepository(Constant::ENTITY_STRUCTURES)->getById(payoutId: $group->getGroups()->getGroupPayouts()[0]->getPayouts()->getPayoutId());
            $structure->createFromEntity(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), structures: $result[0]);
            $payout = new Payout(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: 0, name: "", minPlayers: 0, maxPlayers: 0, structures: array($structure));
            $result = $entityManager->getRepository(Constant::ENTITY_PAYOUTS)->getById(payoutId: $group->getGroups()->getGroupPayouts()[0]->getPayouts()->getPayoutId());
            $payout->createFromEntity(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), payouts: $result[0]);
            $groupPayout = new GroupPayout(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: "", group: $group, payouts: array($payout));
            $tournament = new Tournament(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: $row["id"], description: $row["description"], comment: $row["comment"], limitType: $limitType, gameType: $gameType, specialType: $specialType, chipCount: $row["chips"], location: $location, date: new \Poker\Ccp\classes\model\DateTime(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: NULL, time: $row["date"]), startTime: new \Poker\Ccp\classes\model\DateTime(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: NULL, time: $row["start"]), buyinAmount: $row["buyin"] == 0 ? 0 : -$row["buyin"], maxPlayers: $row["max players"], maxRebuys: $row["max"], rebuyAmount: $row["amt"] == 0 ? 0 : -$row["amt"], addonAmount: $row["amt "] == 0 ? 0 : -$row["amt "], addonChipCount: $row["chips "], groupPayout: $groupPayout, rake: (float) ($row["rake"] * 100), registeredCount: $row["registeredCount"], buyinsPaid: $row["buyinsPaid"], rebuysPaid: $row["rebuysPaid"], rebuysCount: (int) $row["rebuysCount"], addonsPaid: $row["addonsPaid"], enteredCount: $row["enteredCount"], earnings: 0);
            $email = new Email(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), fromName: array(Constant::NAME_STAFF), fromEmail: array(Constant::EMAIL_STAFF()), toName: array($player->getPlayerName()), toEmail: array($player->getPlayerEmail()), ccName: NULL, ccEmail: NULL, bccName: NULL, bccEmail: NULL, subject: NULL, body: NULL);
            $output .= isset($mode) ? "  aryMessages.push(\"" . $email->sendReminderEmail(location: $tournament->getLocation(), tournament: $tournament, waitListCount: 0) . "\");\n" : "\r";
        }
    }
}
$output .= "  if (aryMessages.length > 0) {display.showMessages({messages: aryMessages});}\n</script>\n";
if (isset($_GET[Constant::FIELD_NAME_MODE])) {
    $smarty->assign("title", "Chip Chair and a Prayer Auto Reminder");
    $smarty->assign("heading", "Auto Reminder");
    $smarty->assign("mode", Constant::MODE_VIEW);
    $smarty->assign("action", $_SERVER["SCRIPT_NAME"]);
    $smarty->assign("formName", "frmAutoReminder");
    $smarty->assign("content", $output);
    $smarty->display("base.tpl");
} else {
    echo $output;
}
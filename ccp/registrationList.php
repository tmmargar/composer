<?php
declare(strict_types = 1);
namespace ccp;
use DateInterval;
use DateTime;
use Poker\Ccp\Model\Constant;
use Poker\Ccp\Model\GameType;
use Poker\Ccp\Model\GroupPayout;
use Poker\Ccp\Model\Group;
use Poker\Ccp\Model\LimitType;
use Poker\Ccp\Model\Location;
use Poker\Ccp\Model\Payout;
use Poker\Ccp\Model\Player;
use Poker\Ccp\Model\SpecialType;
use Poker\Ccp\Model\Structure;
use Poker\Ccp\Model\Tournament;
use Poker\Ccp\Utility\SessionUtility;
use Poker\Ccp\Utility\DateTimeUtility;
require_once "init.php";
define("LIMIT_COUNT_PARAMETER_NAME", "limitCount");
$smarty->assign("title", "Chip Chair and a Prayer Events");
$smarty->assign("heading", "");
if (!isset($limitCount)) {
    $limitCount = (isset($_POST[LIMIT_COUNT_PARAMETER_NAME]) ? $_POST[LIMIT_COUNT_PARAMETER_NAME] : isset($_GET[LIMIT_COUNT_PARAMETER_NAME])) ? $_GET[LIMIT_COUNT_PARAMETER_NAME] : NULL;
}
$output = "";
if (NULL != $limitCount) {
    $output .= "<div class=\"title\">Upcoming Events</div>\n";
}
$now = new DateTime();
$resultList = $entityManager->getRepository(entityName: Constant::ENTITY_TOURNAMENTS)->getAllMultiple(championship: false, tournamentDate: new DateTime(), startTime: new DateTime(), tournamentId: NULL, notEntered: false, ordered: false, mode: NULL, interval: NULL, limitCount: $limitCount);
foreach ($resultList as $row) {
    $limitType = new LimitType(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), id: $row["limit_type_id"], name: $row["limit"]);
    $gameType = new GameType(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), id: $row["game_type_id"], name: $row["type"]);
    $specialType = new SpecialType(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), id: $row["special_type_id"], description: $row["std"], multiplier: $row["special_type_multiplier"]);
    $player = new Player(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), id: 0, name: "", username: NULL, password: NULL, email: NULL, phone: NULL, administrator: false, registrationDate: new DateTime(), approvalDate: NULL, approvalUserid: NULL, approvalName: NULL, rejectionDate: NULL, rejectionUserid: NULL, rejectionName: NULL, active: false, resetSelector: NULL, resetToken: NULL, resetExpires: NULL, rememberSelector: NULL, rememberToken: NULL, rememberExpires: NULL);
    $result = $entityManager->getRepository(entityName: Constant::ENTITY_PLAYERS)->getById(playerId: $row["player_id"]);
    $player->createFromEntity(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), players: $result[0]);
    $location = new Location(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), id: $row["location_id"], name: $row["location"], address: "", city: "", state: "", zipCode: 00000, player: $player, count: 0, active: false, map: NULL, mapName: NULL, tournamentCount: 0);
    $result = $entityManager->getRepository(entityName: Constant::ENTITY_LOCATIONS)->getById(locationId: $row["location_id"]);
    $location->createFromEntity(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), locations: $result[0]);
    $group = new Group(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), id: 0, name: "");
    $result = $entityManager->getRepository(entityName: Constant::ENTITY_GROUPS)->getById(groupId: $row["group_id"]);
    $group->createFromEntity(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), groups: $result[0]);
    $structure = new Structure(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), id: 0, place: 0, percentage: 0);
    $result = $entityManager->getRepository(entityName: Constant::ENTITY_STRUCTURES)->getById(payoutId: $group->getGroups()->getGroupPayouts()[0]->getPayouts()->getPayoutId());
    $structure->createFromEntity(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), structures: $result[0]);
    $payout = new Payout(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), id: 0, name: "", minPlayers: 0, maxPlayers: 0, structures: array($structure));
    $result = $entityManager->getRepository(entityName: Constant::ENTITY_PAYOUTS)->getById(payoutId: $group->getGroups()->getGroupPayouts()[0]->getPayouts()->getPayoutId());
    $payout->createFromEntity(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), payouts: $result[0]);
    $groupPayout = new GroupPayout(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), id: "", group: $group, payouts: array($payout));
    $tournament = new Tournament(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), id: $row["id"], description: $row["description"], comment: $row["comment"], limitType: $limitType, gameType: $gameType, specialType: $specialType, chipCount: $row["chips"], location: $location, date: new DateTime(datetime: $row["date"]), startTime: new DateTime(datetime: $row["start"]), buyinAmount: $row["buyin"], maxPlayers: $row["max players"], maxRebuys: $row["max"], rebuyAmount: $row["amt"], addonAmount: $row["amt "], addonChipCount: $row["chips "], groupPayout: $groupPayout, rake: (float) ($row["rake"] * 100), registeredCount: $row["registeredCount"], buyinsPaid: $row["buyinsPaid"], rebuysPaid: $row["rebuysPaid"], rebuysCount: (int) $row["rebuysCount"], addonsPaid: $row["addonsPaid"], enteredCount: $row["enteredCount"], earnings: 0);
    $startTime = DateTimeUtility::formatDisplayTime(value: $tournament->getStartTime());
    $registrationOpenDate = new DateTime(datetime: DateTimeUtility::formatDatabaseDate(value: $tournament->getDate()) . " " . DateTimeUtility::formatDisplayTime(value: $tournament->getRegistrationOpen()));
    $interval = new DateInterval(Constant::INTERVAL_DATE_REGISTRATION_OPEN);
    $registrationOpenDateTemp = $registrationOpenDate;
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
    if ($registrationOpen) {
        $output .= "<a href=\"" . $url . "\">";
    }
    $output .= $tournament->getDescription();
    if (NULL != $tournament->getSpecialType()->getDescription()) {
        $output .= " (" . $tournament->getSpecialType()->getDescription() . ")";
    }
    if ($registrationOpen) {
        $output .= "</a>";
    }
    $output .= "<br />\n";
    $output .= DateTimeUtility::formatDisplayDate(value: $tournament->getDate()) ." " . $startTime;
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
        $output .= "Registration Begins " . DateTimeUtility::formatDisplayRegistrationNotOpen(value: $registrationOpenDate) . " @ Noon";
    }
    $output .= "</div>\n<hr />";
}
if (!$resultList) {
    $output .= "No scheduled tournaments";
}
if (isset($parentObjectId)) {
    return $output;
} else {
    $smarty->assign("content", $output);
    $smarty->display("registrationList.tpl");
}
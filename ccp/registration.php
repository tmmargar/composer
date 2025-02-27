<?php
declare(strict_types = 1);
namespace ccp;
use DateInterval;
use DateTime;
use Poker\Ccp\Model\BooleanString;
use Poker\Ccp\Model\Constant;
use Poker\Ccp\Model\Email;
use Poker\Ccp\Model\FormControl;
use Poker\Ccp\Model\GameType;
use Poker\Ccp\Model\Group;
use Poker\Ccp\Model\GroupPayout;
use Poker\Ccp\Model\LimitType;
use Poker\Ccp\Model\Location;
use Poker\Ccp\Model\Payout;
use Poker\Ccp\Model\Phone;
use Poker\Ccp\Model\Player;
use Poker\Ccp\Model\Result;
use Poker\Ccp\Model\SpecialType;
use Poker\Ccp\Model\Status;
use Poker\Ccp\Model\Structure;
use Poker\Ccp\Model\Tournament;
use Poker\Ccp\Utility\SessionUtility;
use Poker\Ccp\Entity\Results;
use Poker\Ccp\Utility\DateTimeUtility;
require_once "init.php";
define("TOURNAMENT_ID_PARAMETER_NAME", "tournamentId");
define("PLAYER_ID_PARAMETER_NAME", "playerId");
define("FOOD_FIELD_NAME", "food");
define("REGISTERED_FIELD_NAME", "registered");
define("REGISTERED_LABEL", "Registered");
define("UPDATE_REGISTER_TEXT", "Update");
define("WAIT_LIST_COUNT_FIELD_NAME", "waitListCount");
$smarty->assign("title", "Chip Chair and a Prayer Registration");
$output = "";
$smarty->assign("formName", "frmRegistration");
$tournamentId = (int) ((isset($_POST[TOURNAMENT_ID_PARAMETER_NAME]) ? $_POST[TOURNAMENT_ID_PARAMETER_NAME] : isset($_GET[TOURNAMENT_ID_PARAMETER_NAME])) ? $_GET[TOURNAMENT_ID_PARAMETER_NAME] : 0);
$urlAction = $_SERVER["SCRIPT_NAME"] . "?tournamentId=" . $tournamentId;
$smarty->assign("action", $urlAction);
$smarty->assign("heading", "Registration");
$output .=
    "<script type=\"module\">\n" .
    "  import { dataTable, display, input } from \"./scripts/import.js\";\n" .
    "  let aryMessages = [];\n" .
    "  let aryErrors = [];\n";
if (!isset($tournamentId) || "" == $tournamentId) {
    $output .= " aryErrors.push(\"Unable to identify tournament to register for.\");\n";
} else {
    $playerId = (int) ((isset($_POST[PLAYER_ID_PARAMETER_NAME]) ? $_POST[PLAYER_ID_PARAMETER_NAME] : isset($_GET[PLAYER_ID_PARAMETER_NAME])) ? $_GET[PLAYER_ID_PARAMETER_NAME] : SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_PLAYERID));
    $mode = isset($_POST[Constant::FIELD_NAME_MODE]) ? $_POST[Constant::FIELD_NAME_MODE] : Constant::MODE_VIEW;
    $now = new DateTime();
    $registerText = Constant::TEXT_REGISTER;
    $registered = false;
    if (isset($_POST[REGISTERED_FIELD_NAME]) && (1 == $_POST[REGISTERED_FIELD_NAME])) {
        $registered = true;
    }
    $waitListCount = isset($_POST["waitListCount"]) ? $_POST["waitListCount"] : 0;
    if (Constant::MODE_SAVE_CREATE == $mode || Constant::MODE_SAVE_MODIFY == $mode) {
        $food = isset($_POST[FOOD_FIELD_NAME]) ? $_POST[FOOD_FIELD_NAME] : NULL;
        if (Constant::MODE_SAVE_CREATE == $mode) {
            if ($registered) {
                $tournamentFind = $entityManager->find(className: Constant::ENTITY_TOURNAMENTS, id: $tournamentId);
                $playerFind = $entityManager->find(className: Constant::ENTITY_PLAYERS, id: $playerId);
                $results = $entityManager->find(className: Constant::ENTITY_RESULTS, id: ["tournaments" => $tournamentFind, "players" => $playerFind]);
                $results->setResultRegistrationFood(resultRegistrationFood: $food);
                $entityManager->persist(entity: $results);
                $entityManager->flush();
                $state = "updating your registration";
            } else {
                $re = new Results();
                $player = $entityManager->find(className: Constant::ENTITY_PLAYERS, id: $playerId);
                $re->setPlayers(players: $player);
                $re->setPlayerKos(playerKos: null);
                $re->setResultAddonFlag(resultAddonFlag: Constant::FLAG_NO);
                $re->setResultPaidAddonFlag(resultPaidAddonFlag: Constant::FLAG_NO);
                $re->setResultPaidBuyinFlag(resultPaidBuyinFlag: Constant::FLAG_NO);
                $re->setResultPaidRebuyFlag(resultPaidRebuyFlag: Constant::FLAG_NO);
                $re->setResultPlaceFinished(resultPlaceFinished: 0);
                $re->setResultRebuyCount(resultRebuyCount: 0);
                $re->setResultRegistrationFood(resultRegistrationFood: $food);
                $resultList = $entityManager->getRepository(entityName: Constant::ENTITY_RESULTS)->getMaxRegistrationOrder(tournamentId: $tournamentId);
                $re->setResultRegistrationOrder($resultList[0]["resultRegistrationOrderMax"]);
                $statusCode = $entityManager->find(className: Constant::ENTITY_STATUS_CODES, id: Constant::CODE_STATUS_REGISTERED);
                $re->setStatusCodes(statusCodes: $statusCode);
                $tournament = $entityManager->find(className: Constant::ENTITY_TOURNAMENTS, id: $tournamentId);
                $re->setTournaments(tournaments: $tournament);
                $entityManager->persist(entity: $re);
                $entityManager->flush();
                $state = "registering";
            }
        } else {
            $tournamentFind = $entityManager->find(className: Constant::ENTITY_TOURNAMENTS, id: $tournamentId);
            $playerFind = $entityManager->find(className: Constant::ENTITY_PLAYERS, id: $playerId);
            $results = $entityManager->getRepository(entityName: Constant::ENTITY_RESULTS)->findOneBy(array("tournaments" => $tournamentFind, "players" => $playerFind));
            $registerOrder = $results->getResultRegistrationOrder();
            $entityManager->remove(entity: $results);
            $entityManager->flush();
            $registered = false;
            $state = "cancelling";
        }
        if (Constant::MODE_SAVE_CREATE != $mode) {
            $rowCount = $entityManager->getRepository(entityName: Constant::ENTITY_RESULTS)->updateRegistrationOrder(tournamentId: $tournamentId, resultRegistrationOrder: $registerOrder);
            $waitListEmail = true;
        }
        $mode = Constant::MODE_SEND_EMAIL;
        $output .= "aryMessages.push(\"Thank you for " . $state . ". <a href='registrationList.php'>Click here</a> to register for more tournaments.\");\n";
    }
    if ($mode == Constant::MODE_VIEW || $mode == Constant::MODE_SEND_EMAIL) {
        $resultList = $entityManager->getRepository(entityName: Constant::ENTITY_TOURNAMENTS)->getAllMultiple(championship: false, tournamentDate: NULL, startTime: NULL, tournamentId: $tournamentId, notEntered: false, ordered: false, mode: NULL, interval: NULL, limitCount: NULL);
        if (0 < count(value: $resultList)) {
            $row = $resultList[0];
            $limitType = new LimitType(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), id: $row["limit_type_id"], name: $row["name"]);
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
            $tournamentLocationPlayer = $tournament->getLocation()->getPlayer();
            $tournamentLocation = $tournament->getLocation();
            $waitListCount = ($tournament->getRegisteredCount() > $tournament->getMaxPlayers()) ? ($tournament->getRegisteredCount() - $tournament->getMaxPlayers()) : 0;
            if ($mode == Constant::MODE_SEND_EMAIL) {
                // if set means email notification required and need location information from view query
                if (isset($state)) {
                    $resultList = $entityManager->getRepository(entityName: Constant::ENTITY_PLAYERS)->getById(playerId: $playerId);
                    if (0 < count(value: $resultList)) {
                        $player = $resultList[0];
                        $resultListNested = $entityManager->getRepository(entityName: Constant::ENTITY_PLAYERS)->getFeeForPlayerAndTournament(playerId: $playerId, tournamentId: $tournamentId);
                        $feeStatus = $resultListNested[0]["status"];
                        $email = new Email(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), fromName: array(Constant::NAME_STAFF), fromEmail: array(Constant::EMAIL_STAFF()), toName: array($player->getPlayerName()), toEmail: array($player->getPlayerEmail()), ccName: NULL, ccEmail: NULL, bccName: NULL, bccEmail: NULL, subject: NULL, body: NULL);
//                         $emailTournament = new Tournament(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), id: $tournament->getId(), description: NULL, comment: NULL, limitType: NULL, gameType: NULL, specialType: NULL, chipCount: 0, location: NULL, date: $tournament->getDate(), startTime: $tournament->getStartTime(), buyinAmount: 0, maxPlayers: 0, maxRebuys: 0, rebuyAmount: 0, addonAmount: 0, addonChipCount: 0, groupPayout: NULL, rake: 0, registeredCount: 0, buyinsPaid: 0, rebuysPaid: 0, rebuysCount: 0, addonsPaid: 0, enteredCount: 0);
                        $tournaments = $entityManager->getRepository(entityName: Constant::ENTITY_TOURNAMENTS)->getById(tournamentId: $tournamentId);
                        $emailTournament = new Tournament(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), id: 0, description: NULL, comment: NULL, limitType: NULL, gameType: NULL, specialType: NULL, chipCount: 0, location: NULL, date: NULL, startTime: NULL, buyinAmount: 0, maxPlayers: 0, maxRebuys: 0, rebuyAmount: 0, addonAmount: 0, addonChipCount: 0, groupPayout: NULL, rake: 0, registeredCount: 0, buyinsPaid: 0, rebuysPaid: 0, rebuysCount: 0, addonsPaid: 0, enteredCount: 0, earnings: 0);
                        $emailTournament->createFromEntity(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), tournaments: $tournaments);
                        $emailLocation = new Location(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), id: NULL, name: "", address: "", city: "", state: "", zipCode: 00000, player: NULL, count: 0, active: false, map: NULL, mapName: NULL, tournamentCount: 0);
                        $emailLocation->createFromEntity(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), locations: $tournaments->getLocations());
                        if ("cancelling" == $state) {
                            $output .= "aryMessages.push(\"" . $email->sendCancelledEmail(location: $emailLocation, tournament: $emailTournament) . "\");\n";
                        } else {
                            $output .= "aryMessages.push(\"" . $email->sendRegisteredEmail(location: $emailLocation, tournament: $emailTournament, feeStatus: $feeStatus, waitList: $waitListCount) . "\");\n";
                        }
                    }
                    if (isset($waitListEmail)) {
                        // send email to person who moved from wait list to registered
                        $resultList = $entityManager->getRepository(entityName: Constant::ENTITY_PLAYERS)->getWaitListed(tournamentId: $tournamentId);
                        if (0 < count(value: $resultList)) {
                            $waitListName = $resultList[0]->getPlayerName();
                            $waitListEmail = $resultList[0]->getPlayerEmail();
                            $resultListNested = $entityManager->getRepository(entityName: Constant::ENTITY_PLAYERS)->getFeeForPlayerAndTournament(playerId: $resultList[0]->getPlayerId(), tournamentId: $tournamentId);
                            $feeStatus = $resultListNested[0]["status"];
                            $email = new Email(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), fromName: array(Constant::NAME_STAFF), fromEmail: array(Constant::EMAIL_STAFF()), toName: array($waitListName), toEmail: array($waitListEmail), ccName: NULL, ccEmail: NULL, bccName: NULL, bccEmail: NULL, subject: NULL, body: NULL);
                            $tournaments = $entityManager->getRepository(entityName: Constant::ENTITY_TOURNAMENTS)->getById(tournamentId: $tournamentId);
                            $emailTournament = new Tournament(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), id: $tournament->getId(), description: NULL, comment: NULL, limitType: NULL, gameType: NULL, specialType: NULL, chipCount: 0, location: NULL, date: $tournament->getDate(), startTime: $tournament->getStartTime(), buyinAmount: 0, maxPlayers: 0, maxRebuys: 0, rebuyAmount: 0, addonAmount: 0, addonChipCount: 0, groupPayout: NULL, rake: 0, registeredCount: 0, buyinsPaid: 0, rebuysPaid: 0, rebuysCount: 0, addonsPaid: 0, enteredCount: 0);
                            $emailTournament->createFromEntity(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), tournaments: $tournaments[0]);
                            $emailLocation = new Location(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), id: NULL, name: "", address: "", city: "", state: "", zipCode: 00000, player: NULL, count: 0, active: false, map: NULL, mapName: NULL, tournamentCount: 0);
                            $emailLocation->createFromEntity(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), locations: $tournaments[0]->getLocations());
                            $output .= "aryMessages.push(\"" . $email->sendRegisteredEmail(location: $emailLocation, tournament: $emailTournament, feeStatus: $feeStatus, waitList: -99) . "\");\n";
                            // send email to CCP staff
                            $email = new Email(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), fromName: array(Constant::NAME_STAFF), fromEmail: array(Constant::EMAIL_STAFF()), toName: array(Constant::NAME_STAFF), toEmail: array(Constant::EMAIL_STAFF()), ccName: NULL, ccEmail: NULL, bccName: NULL, bccEmail: NULL, subject: NULL, body: NULL);
                            $output .= "aryMessages.push(\"" . $email->sendRegisteredEmail(location: $emailLocation, tournament: $emailTournament, feeStatus: $feeStatus, waitList: $player->getPlayerName() . " un-registered and " . $waitListName) . "\");\n";
                        }
                    }
                }
                $output .= "  if (aryErrors.length > 0) {display.showErrors({errors: aryErrors});}\n";
                $output .= "  if (aryMessages.length > 0) {display.showMessages({messages: aryMessages});}\n</script>\n";
            } else {
                $output .= "  if (aryErrors.length > 0) {display.showErrors({errors: aryErrors});}\n";
                $output .= "  if (aryMessages.length > 0) {display.showMessages({messages: aryMessages});}\n</script>\n";
                $output .= "  <div class=\"column2\">" . $tournament->getDescription() . "</div>\n";
                $output .= "  <div class=\"clear\"></div>\n";
                $output .= "  <div class=\"column2\">" . $tournament->getComment() . "</div>\n";
                $output .= "  <div class=\"clear\"></div>\n";
                $output .= "  <div class=\"column2\">Hosted by " . $tournament->getLocation()->getPlayer()->getName() . "</div>\n";
                $output .= "  <div class=\"clear\"></div>\n";
                $output .= "  <div class=\"column2\">" . $tournament->getLimitType()->getName() . " " . $tournament->getGameType()->getName() . "</div>\n";
                $output .= "  <div class=\"clear\"></div>\n";
                $output .= "  <div class=\"column2\">$" . - $tournament->getBuyinAmount() . " for " . $tournament->getChipCount() . " chips</div>\n";
                $output .= "  <div class=\"clear\"></div>\n";
                $output .= "  <div class=\"column2\">" . $tournament->getMaxRebuys() . " rebuy(s), $" . - $tournament->getRebuyAmount() . " for " . $tournament->getChipCount() . " chips</div>\n";
                if (0 != $tournament->getAddonAmount()) {
                    $output .= "  <div class=\"clear\"></div>\n";
                    $output .= "  <div class=\"column2\">addon, $" . - $tournament->getAddonAmount() . " for " . $tournament->getAddonChipCount() . " chips</div>\n";
                }
                $output .= "  <div class=\"clear\"></div>\n";
                $output .= "  <br />\n";
                $output .= "  <div class=\"column\">Date / Time:</div>\n";
                $output .= "  <div>" . DateTimeUtility::formatDisplayLong(value: $tournament->getDate()) . " " . DateTimeUtility::formatDisplayTime(value: $tournament->getStartTime()) . "</div>\n";
                $output .= "  <div class=\"clear\"></div>\n";
                $output .= "  <div class=\"column\">Map:</div>\n";
                $output .= "  <div>" . $tournament->getLocation()->buildMapUrl() . "</div>\n";
                $output .= "  <div class=\"clear\"></div>\n";
                $output .= "  <div class=\"column\">Location:</div>\n";
                $phone = new Phone(SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), NULL, (string) $tournamentLocationPlayer->getPhone()->getValue());
                $output .= "  <div style=\"float: left;\">" . $tournamentLocation->getAddress() . "<br />" . $tournamentLocation->getCity() . ", " . $tournamentLocation->getState() . " " . $tournamentLocation->getZipCode() . "<br />" . $phone->getDisplayFormatted() . "</div>\n";
                $output .= "  <div class=\"clear\"></div>\n";
                $output .= "  <div class=\"column\">Available:</div>\n";
                if ($tournament->getDescription() == "Championship") {
                    $maxPlayers = 36;
                } else {
                    $maxPlayers = $tournament->getMaxPlayers();
                }
                $dateTimeRegistrationClose = new DateTime(datetime: DateTimeUtility::formatDatabaseDate(value: $tournament->getDate()) . " " . DateTimeUtility::formatDisplayTime(value: $tournament->getRegistrationClose()));
                // $registrationCloseDate = $dateTimeRegistrationClose->getDatabaseFormat();
                $tournamentDateClone = clone $tournament->getDate();
                $registrationOpenDate = new DateTime(datetime: DateTimeUtility::formatDatabaseDate(value: $tournamentDateClone) . " 12:00:00");
                $interval = new DateInterval(Constant::INTERVAL_DATE_REGISTRATION_OPEN);
                $registrationOpenDate->sub($interval);
                if ($tournament->getDescription() == Constant::DESCRIPTION_CHAMPIONSHIP) {
                    $registeredCount = 0;
                } else {
                    if ($now < $dateTimeRegistrationClose) {
                        $registeredCount = ($tournament->getRegisteredCount() <= $maxPlayers) ? ($maxPlayers - $tournament->getRegisteredCount()) : 0;
                    } else {
                        $registeredCount = 0;
                    }
                }
                $output .= "  <div>" . $registeredCount . " out of " . $maxPlayers . "</div>\n";
                $output .= "  <div class=\"clear\"></div>\n";
                $output .= "  <div class=\"column\">Wait list #:</div>\n";
                $output .= "  <div>" . $waitListCount . "</div>\n";
                $output .= "  <div class=\"clear\"></div>\n";
                $output .= "  <div class=\"column\">Deadline:</div>\n";
                $output .= "  <div>" . DateTimeUtility::formatDisplayLong(value: $dateTimeRegistrationClose) . "</div>\n";
                $output .= "  <div class=\"clear\"></div>\n";
                $resultList = $entityManager->getRepository(entityName: Constant::ENTITY_PLAYERS)->getResults(tournamentId: $tournamentId, playerId: $playerId);
                $output .= "  <br />\n";
                if (0 < count(value: $resultList)) {
                    $output .= "  <div class=\"column\">Name:</div>\n";
                    $output .= "  <div>" . $resultList[0]->getPlayerName() . "</div>\n";
                    $output .= "  <div class=\"clear\"></div>\n";
                    $output .= "  <div class=\"column\">Email:</div>\n";
                    $output .= "  <div>" . $resultList[0]->getPlayerEmail() . "</div>\n";
                    $output .= "  <div class=\"clear\"></div>\n";
                    $output .= "  <div class=\"column\">Dish:</div>\n";
                    $output .= "  <div>";
                    $food = count(value: $resultList[0]->getResults()) > 0 ? $resultList[0]->getResults()[0]->getResultRegistrationFood() : "";
                    if (($now >= $registrationOpenDate) && ($now <= $dateTimeRegistrationClose)) {
                        $textBoxName = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_FOOD, autoComplete: NULL, autoFocus: true, checked: NULL, class: NULL, cols: NULL, disabled: false, id: FOOD_FIELD_NAME, maxLength: NULL, name: FOOD_FIELD_NAME, onClick: NULL, placeholder: NULL, readOnly: false, required: true, rows: NULL, size: 20, suffix: NULL, type: FormControl::TYPE_INPUT_TEXTBOX, value: $food, wrap: NULL, noValidate: false);
                        $output .= $textBoxName->getHtml();
                    } else {
                        $output .= $food;
                    }
                    $output .= "</div>\n";
                    $output .= "  <div class=\"clear\"></div>\n";
                }
                $output2 = "  <h3 class=\"center\">" . REGISTERED_LABEL . "</h3>\n";
                $output2 .= "  <div class=\"columnHeader\">Name</div>\n";
                $output2 .= "  <div class=\"column1Header\">Season Fee</div>\n";
                $output2 .= "  <div class=\"columnHeader\">Food</div>\n";
                $output2 .= "  <div class=\"clear\" style=\"padding-bottom: 2px;\"></div>\n";
                $resultList = $entityManager->getRepository(entityName: Constant::ENTITY_RESULTS)->getPaidOrRegisteredOrFinished(tournamentId: $tournamentId, playerId: NULL, paid: false, registered: true, finished: false, indexed: false);
                if (0 < count(value: $resultList)) {
                    $count = 0;
                    $registered = false;
                    foreach ($resultList as $result) {
                        $status = new Status(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), id: NULL, code: $result["status_code"], name: $result["status"]);
                        $tournament = new Tournament(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), id: (int) $result["tournament_id"], description: NULL, comment: NULL, limitType: NULL, gameType: NULL, specialType: NULL, chipCount: 0, location: NULL, date: NULL, startTime: NULL, buyinAmount: 0, maxPlayers: 0, maxRebuys: 0, rebuyAmount: 0, addonAmount: 0, addonChipCount: 0, groupPayout: NULL, rake: 0, registeredCount: 0, buyinsPaid: 0, rebuysPaid: 0, rebuysCount: 0, addonsPaid: 0, enteredCount: 0);
                        $player = new Player(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), id: (int) $result["player_id"], name: $result["name"], username: NULL, password: NULL, email: $result["player_email"], phone: NULL, administrator: false, registrationDate: new DateTime(), approvalDate: NULL, approvalUserid: NULL, approvalName: NULL, rejectionDate: NULL, rejectionUserid: NULL, rejectionName: NULL, active: (bool) $result["player_active_flag"], resetSelector: NULL, resetToken: NULL, resetExpires: NULL, rememberSelector: NULL, rememberToken: NULL, rememberExpires: NULL);
                        if (isset($result["knocked out by"])) {
                            $nameKnockedOutBy = $result["knocked out by"];
                        } else {
                            $nameKnockedOutBy = "";
                        }
                        $knockedOutBy = new Player(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), id: (int) $result["player_id_ko"], name: $nameKnockedOutBy, username: NULL, password: NULL, email: NULL, phone: NULL, administrator: false, registrationDate: new DateTime(), approvalDate: NULL, approvalUserid: NULL, approvalName: NULL, rejectionDate: NULL, rejectionUserid: NULL, rejectionName: NULL, active: isset($result["knockedOutActive"]) ? $result["knockedOutActive"] : false, resetSelector: NULL, resetToken: NULL, resetExpires: NULL, rememberSelector: NULL, rememberToken: NULL, rememberExpires: NULL);
                        $buyinPaid = new BooleanString(value: $result["result_paid_buyin_flag"]);
                        $rebuyPaid = new BooleanString(value: $result["result_paid_rebuy_flag"]);
                        $addonPaid = new BooleanString(value: $result["addon"]);
                        $addonFlag = new BooleanString(value: $result["result_addon_flag"]);
                        $result = new Result(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), id: NULL, tournament: $tournament, player: $player, status: $status, registerOrder: (int) $result["result_registration_order"], buyinPaid: $buyinPaid->getBoolean(), rebuyPaid: $rebuyPaid->getBoolean(), addonPaid: $addonPaid->getBoolean(), rebuyCount: (int) $result["rebuy"], addonFlag: $addonFlag->getBoolean(), place: (int) $result["place"], knockedOutBy: $knockedOutBy, food: $result["result_registration_food"], feeStatus: $result["feeStatus"]);
                        if ($playerId == $result->getPlayer()->getId()) {
                          $registered = true;
                          $registerText = UPDATE_REGISTER_TEXT;
                        }
                        // wait list goes here
                        if ($count == $maxPlayers) {
                            $output2 .= " <h3>Wait List</h3>";
                        }
                        $output2 .= "  <div class=\"column\">" . $result->getPlayer()->getName() . "</div>\n";
                        $output2 .= "  <div class=\"column1\">" . $result->getFeeStatus() . "</div>\n";
                        $output2 .= "  <div>" . NULL !== $result->getFood() ? $result->getFood() : "" . "</div>\n";
                        $output2 .= "  <div class=\"clear\" style=\"padding-bottom: 2px;\"></div>\n";
                        $count++;
                    }
                } else {
                    $output2 .= "  None\n";
                }
                // if not registered and there is a wait list
                if (!$registered && isset($count) && $count >= $maxPlayers) {
                    $registerText = "Add to wait list";
                }
                $resultList = $entityManager->getRepository(entityName: Constant::ENTITY_TOURNAMENTS)->getForIdAndDates(playerId: $playerId, startDate: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_START_DATE), endDate: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_END_DATE));
                if (0 < count(value: $resultList)) {
                    $numPlayed = $resultList[0];
                }
                // check in registration range and not full
                if (($now >= $registrationOpenDate) && ($now <= $dateTimeRegistrationClose)) {
                    if ($tournament->getDescription() == Constant::DESCRIPTION_CHAMPIONSHIP && SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_CHAMPIONSHIP_QUALIFY) > $numPlayed) {
                        $output .=
                            "<script type=\"module\">\n" .
                            "  import { dataTable, display, input } from \"./scripts/import.js\";\n" .
                            "  aryMessages = [];\n" .
                            "  aryErrors = [];\n";
                        $output .= "  aryErrors.push(\"You are not allowed to register for the Championship because you only played " . $numPlayed . " tournaments and did not meet the " . SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_CHAMPIONSHIP_QUALIFY) . " tournament minimum to qualify.\");\n";
                        $output .= "  if (aryErrors.length > 0) {display.showErrors({errors: aryErrors});}\n";
                        $output .= "  if (aryMessages.length > 0) {display.showMessages({messages: aryMessages});}\n</script>\n";
                    } else {
                        $output .= " <div class=\"center\">\n";
                        $buttonRegister = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_REGISTER, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_REGISTER, maxLength: NULL, name: Constant::TEXT_REGISTER, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: $registerText, wrap: NULL, noValidate: false);
                        $output .= $buttonRegister->getHtml();
                        $buttonUnregister = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_UNREGISTER, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: (!$registered ? true : false), id: Constant::TEXT_UNREGISTER, maxLength: NULL, name: Constant::TEXT_UNREGISTER, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_UNREGISTER, wrap: NULL, import: NULL, noValidate: true);
                        $output .= $buttonUnregister->getHtml();
                        $output .= " </div>\n";
                    }
                }
                $output .= $output2;
                $hiddenMode = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::FIELD_NAME_MODE, maxLength: NULL, name: Constant::FIELD_NAME_MODE, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: $mode, wrap: NULL, noValidate: false);
                $output .= $hiddenMode->getHtml();
                $hiddenRegistered = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: REGISTERED_FIELD_NAME, maxLength: NULL, name: REGISTERED_FIELD_NAME, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: (string) $registered, wrap: NULL, noValidate: false);
                $output .= $hiddenRegistered->getHtml();
                $hiddenWaitListCount = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: WAIT_LIST_COUNT_FIELD_NAME, maxLength: NULL, name: WAIT_LIST_COUNT_FIELD_NAME, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: (string) $waitListCount, wrap: NULL, noValidate: false);
                $output .= $hiddenWaitListCount->getHtml();
            }
        }
    }
}
$smarty->assign("content", $output);
$smarty->display("registration.tpl");
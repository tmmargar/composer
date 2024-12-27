<?php
declare(strict_types = 1);
namespace ccp;
use DateTime;
use Exception;
use Poker\Ccp\Model\Address;
use Poker\Ccp\Model\Constant;
use Poker\Ccp\Model\Email;
use Poker\Ccp\Model\FormControl;
use Poker\Ccp\Model\FormOption;
use Poker\Ccp\Model\FormSelect;
use Poker\Ccp\Model\HtmlTable;
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
use Poker\Ccp\Entity\Results;
require_once "init.php";
define("TOURNAMENT_FIELD_LABEL", "Tournament");
define("TOURNAMENT_ID_FIELD_NAME", "tournamentId");
define("SELECTED_ROWS_TOURNAMENT_PLAYER_STATUS_FIELD_NAME", "tournamentPlayerStatus");
define("SELECT_COLUMN_PREFIX_FIELD_NAME", "select");
define("TOURNAMENT_PLAYER_ID_FIELD_NAME", "tournamentPlayerId");
define("DEFAULT_VALUE_TOURNAMENT_ID", "-1");
define("REGISTER_TEXT", "Register");
define("UNREGISTER_TEXT", "Un-register");
$smarty->assign("title", "Manage Registration");
$smarty->assign("heading", "Manage Registration");
$smarty->assign("style", "<link href=\"css/manageRegistration.css\" rel=\"stylesheet\">");
$errors = NULL;
$tournamentId = (int) (isset($_POST[TOURNAMENT_ID_FIELD_NAME]) ? $_POST[TOURNAMENT_ID_FIELD_NAME] : DEFAULT_VALUE_TOURNAMENT_ID);
$tournamentPlayerStatus = isset($_POST[SELECTED_ROWS_TOURNAMENT_PLAYER_STATUS_FIELD_NAME]) ? $tournamentPlayerStatus = $_POST[SELECTED_ROWS_TOURNAMENT_PLAYER_STATUS_FIELD_NAME] : DEFAULT_VALUE_BLANK;
if (Constant::MODE_CREATE == $mode || Constant::MODE_MODIFY == $mode) {
    $ary = explode(separator: Constant::DELIMITER_DEFAULT, string: $ids);
    $aryStatus = explode(separator: Constant::DELIMITER_DEFAULT, string: $tournamentPlayerStatus);
    $runOnce = true;
    // get number of registered to determine max number of wait list to process
    $valuesCount = array_count_values(array: $aryStatus);
    $numRows = (int) (array_key_exists(key: Constant::NAME_STATUS_REGISTERED, array: $valuesCount) ? $valuesCount[Constant::NAME_STATUS_REGISTERED] : 0);
    $output .=
        "<script type=\"module\">\n" .
        "  import { dataTable, display, input } from \"./scripts/import.js\";\n" .
        "  let aryMessages = [];\n";
    foreach ($ary as $index => $id) {
        $players = $entityManager->getRepository(entityName: Constant::ENTITY_PLAYERS)->getById(playerId: (int) $id);
        if (NULL !== $players) {
            $cnt = 0;
            $playerId = $players[0]->getPlayerId();
            $userName = $players[0]->getPlayerName();
            $userEmail = $players[0]->getPlayerEmail();
        }
        if ($aryStatus[$index] == Constant::NAME_STATUS_NOT_REGISTERED) {
            $re = new Results();
            $player = $entityManager->find(className: Constant::ENTITY_PLAYERS, id: $players[0]->getPlayerId());
            $re->setPlayers(players: $player);
            $re->setPlayerKos(playerKos: null);
            $re->setResultAddonFlag(resultAddonFlag: Constant::FLAG_NO);
            $re->setResultPaidAddonFlag(resultPaidAddonFlag: Constant::FLAG_NO);
            $re->setResultPaidBuyinFlag(resultPaidBuyinFlag: Constant::FLAG_NO);
            $re->setResultPaidRebuyFlag(resultPaidRebuyFlag: Constant::FLAG_NO);
            $re->setResultPlaceFinished(resultPlaceFinished: 0);
            $re->setResultRebuyCount(resultRebuyCount: 0);
            $re->setResultRegistrationFood(resultRegistrationFood: NULL);
            $results = $entityManager->getRepository(entityName: Constant::ENTITY_RESULTS)->getMaxRegistrationOrder(tournamentId: $tournamentId);
            $re->setResultRegistrationOrder($results[0]["resultRegistrationOrderMax"]);
            $statusCode = $entityManager->find(className: Constant::ENTITY_STATUS_CODES, id: Constant::CODE_STATUS_REGISTERED);
            $re->setStatusCodes(statusCodes: $statusCode);
            $tournament = $entityManager->find(className: Constant::ENTITY_TOURNAMENTS, id: $tournamentId);
            $re->setTournaments(tournaments: $tournament);
            $entityManager->persist(entity: $re);
            try {
                $entityManager->flush();
            } catch (Exception $e) {
                $errors = $e->getMessage();
            }
            if (NULL !== $errors) {
                $output .= "  aryMessages.push(\"" . $errors . "\");\n";
            }
            $state = "registering";
        } else {
            $resultList = $entityManager->getRepository(entityName: Constant::ENTITY_RESULTS)->getPaidOrRegisteredOrFinished(tournamentId: $tournamentId, playerId: (int) $id, paid: false, registered: false, finished: false, indexed: false);
            $registerOrder = (int) $resultList[0]["result_registration_order"];
            $rowCount = $entityManager->getRepository(entityName: Constant::ENTITY_RESULTS)->deleteForTournamentAndPlayer(tournamentId: $tournamentId, playerId: (int) $id);
            if (!is_numeric(value: $rowCount)) {
                $output .= "  aryMessages.push(\"" . $rowCount . "\");\n";
            }
            $state = "cancelling";
        }
        if ($aryStatus[$index] != Constant::NAME_STATUS_NOT_REGISTERED) {
            if ($runOnce) {
//                 player_id, name, player_email, tournament_max_players, player_active_flag
                $resultList = $entityManager->getRepository(entityName: Constant::ENTITY_TOURNAMENTS)->getRegistrationWaitList(tournamentId: $tournamentId, registerOrder: $registerOrder);
                // grab information for people moved from wait list to registered to send emails
                if (count(value: $resultList) > 0) {
                    $cnt = 0;
                        while ($cnt < count(value: $resultList)) {
                            $tournaments = $resultList[$cnt];
                            // make sure not a wait listed person (register order <= max players)
                            if ($registerOrder <= $tournaments->getTournamentMaxPlayers()) {
                                $emailInfo[$cnt] = array($tournaments->getResults()[0]->getPlayers()->getPlayerId(), $tournaments->getResults()[0]->getPlayers()->getPlayerName(), $tournaments->getResults()[0]->getPlayers()->getPlayerEmail());
                            }
                        $cnt++;
                     }
                }
                $runOnce = false;
            }
            $rowCount = $entityManager->getRepository(entityName: Constant::ENTITY_RESULTS)->updateForCancellation(tournamentId: $tournamentId, registerOrder: $registerOrder);
            if (!is_numeric($rowCount)) {
                $output .= "  aryMessages.push(\"" . $rowCount . "\");\n";
            }
        }
        // for email ONLY
        $resultList = $entityManager->getRepository(entityName: Constant::ENTITY_TOURNAMENTS)->getAllMultiple(championship: false, tournamentDate: NULL, startTime: NULL, tournamentId: $tournamentId, notEntered: false, ordered: false, mode: NULL, interval: NULL, limitCount: NULL);
        if (0 < count($resultList)) {
            $row = $resultList[0];
            $limitType = new LimitType(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), id: $row["limit_type_id"], name: $row["limit"]);
            $gameType = new GameType(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), id: $row["game_type_id"], name: $row["type"]);
            $specialType = new SpecialType(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), id: $row["special_type_id"], description: $row["std"], multiplier: $row["special_type_multiplier"]);
            $player = new Player(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), id: 0, name: "", username: NULL, password: NULL, email: NULL, phone: NULL, administrator: "0", registrationDate: new DateTime(), approvalDate: NULL, approvalUserid: NULL, approvalName: NULL, rejectionDate: NULL, rejectionUserid: NULL, rejectionName: NULL, active: "0", resetSelector: NULL, resetToken: NULL, resetExpires: NULL, rememberSelector: NULL, rememberToken: NULL, rememberExpires: NULL);
            $result = $entityManager->getRepository(entityName: Constant::ENTITY_PLAYERS)->getById(playerId: $row["player_id"]);
            $player->createFromEntity(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), players: $result[0]);
            $location = new Location(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), id: $row["location_id"], name: $row["location"], address: "", city: "", state: "", zipCode: 00000, player: $player, count: 0, active: "0", map: NULL, mapName: NULL, tournamentCount: 0);
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
            $waitListCount = ($tournament->getMaxPlayers() - $tournament->getRegisteredCount()) < 0 ? ($tournament->getRegisteredCount() - $tournament->getMaxPlayers()) : 0;
            $resultListNested = $entityManager->getRepository(entityName: Constant::ENTITY_PLAYERS)->getFeeForPlayerAndTournament(playerId: $playerId, tournamentId: $tournamentId);
            $feeStatus = $resultListNested[0]["status"];
            $email = new Email(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), fromName: array(Constant::NAME_STAFF), fromEmail: array(Constant::EMAIL_STAFF()), toName: array($userName), toEmail: array($userEmail), ccName: NULL, ccEmail: NULL, bccName: NULL, bccEmail: NULL, subject: NULL, body: NULL);
            if ("cancelling" == $state) {
                $message = $email->sendCancelledEmail(location: $location, tournament: $tournament);
            } else {
                $message = $email->sendRegisteredEmail(location: $location, tournament: $tournament, feeStatus: $feeStatus, waitList: $waitListCount);
            }
            $output .= "aryMessages.push(\"" . $message . "\");\n";
        }
    }
    if (isset($emailInfo)) {
        $cnt = 0;
        // send email to people moved from wait list to registered
        while ($cnt < count(value: $emailInfo)) {
            $resultListNested = $entityManager->getRepository(entityName: Constant::ENTITY_PLAYERS)->getFeeForPlayerAndTournament(playerId: (int) $emailInfo[$cnt][0], tournamentId: $tournamentId);
            $feeStatus = $resultListNested[0]["status"];
            $email = new Email(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), fromName: array(Constant::NAME_STAFF), fromEmail: array(Constant::EMAIL_STAFF()), toName: array($emailInfo[$cnt][1]), toEmail: array($emailInfo[$cnt][2]), ccName: NULL, ccEmail: NULL, bccName: NULL, bccEmail: NULL, subject: NULL, body: NULL);
            $emailTournament = new Tournament(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), id: $tournament->getId(), description: NULL, comment: NULL, limitType: NULL, gameType: NULL, specialType: NULL, chipCount: 0, location: NULL, date: $tournament->getDate(), startTime: $tournament->getStartTime(), buyinAmount: 0, maxPlayers: 0, maxRebuys: 0, rebuyAmount: 0, addonAmount: 0, addonChipCount: 0, groupPayout: NULL, rake: 0, registeredCount: 0, buyinsPaid: 0, rebuysPaid: 0, rebuysCount: 0, addonsPaid: 0, enteredCount: 0);
            $output .= "aryMessages.push(\"" . $email->sendRegisteredEmail(location: $location, tournament: $emailTournament, feeStatus: $feeStatus, waitList: -99) . "\");\n";
            $cnt++;
        }
    }
    $output .= "  if (aryMessages.length > 0) {display.showMessages({messages: aryMessages});}\n</script>\n";
    $ids = DEFAULT_VALUE_BLANK;
    $mode = Constant::MODE_VIEW;
}
if ($mode == Constant::MODE_VIEW) {
    $output .= "<div class=\"responsive responsive--3cols responsive--collapse\">";
    $resultList = $entityManager->getRepository(entityName: Constant::ENTITY_TOURNAMENTS)->getAllMultiple(championship: false, tournamentDate: NULL, startTime: NULL, tournamentId: NULL, notEntered: true, ordered: false, mode: NULL, interval: NULL, limitCount: NULL);
    if (count(value: $resultList) > 0) {
        $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . TOURNAMENT_ID_FIELD_NAME . "\">" . TOURNAMENT_FIELD_LABEL . ": </label></div>\n";
        $selectTournament = new FormSelect(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_TOURNAMENT_ID, class: NULL, disabled: false, id: TOURNAMENT_ID_FIELD_NAME, multiple: false, name: TOURNAMENT_ID_FIELD_NAME, onClick: NULL, readOnly: false, size: 1, suffix: NULL, value: NULL);
        $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $selectTournament->getHtml();
        $option = new FormOption(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), class: NULL, disabled: false, id: NULL, name: NULL, selectedValue: !isset($tournamentId) ? DEFAULT_VALUE_TOURNAMENT_ID : "", suffix: NULL, text: Constant::TEXT_NONE, value: DEFAULT_VALUE_TOURNAMENT_ID);
        $output .= $option->getHtml();
        foreach ($resultList as $row) {
            $limitType = new LimitType(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), id: $row["limit_type_id"], name: $row["limit"]);
            $gameType = new GameType(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), id: $row["game_type_id"], name: $row["type"]);
            $specialType = new SpecialType(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), id: $row["special_type_id"], description: $row["std"], multiplier: $row["special_type_multiplier"]);
            $player = new Player(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), id: 0, name: "", username: NULL, password: NULL, email: NULL, phone: NULL, administrator: "0", registrationDate: new DateTime(), approvalDate: NULL, approvalUserid: NULL, approvalName: NULL, rejectionDate: NULL, rejectionUserid: NULL, rejectionName: NULL, active: "0", resetSelector: NULL, resetToken: NULL, resetExpires: NULL, rememberSelector: NULL, rememberToken: NULL, rememberExpires: NULL);
            $result = $entityManager->getRepository(entityName: Constant::ENTITY_PLAYERS)->getById(playerId: $row["player_id"]);
            $player->createFromEntity(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), players: $result[0]);
            $location = new Location(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), id: $row["location_id"], name: $row["location"], address: "", city: "", state: "", zipCode: 00000, player: $player, count: 0, active: "0", map: NULL, mapName: NULL, tournamentCount: 0);
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
            $option = new FormOption(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), class: NULL, disabled: false, id: NULL, name: NULL, selectedValue: $tournamentId, suffix: NULL, text: $tournament->getDisplayDetails(), value: $tournament->getId());
            $output .= $option->getHtml();
        }
        $output .= "    </select>\n";
        $output .= "   </div>\n";
        $buttonView = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_VIEW, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_VIEW, maxLength: NULL, name: Constant::TEXT_VIEW, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_VIEW, wrap: NULL, noValidate: false);
        $output .= " <div class=\"responsive-cell responsive-cell-button\">" . $buttonView->getHtml() . "</div>\n";
        $hiddenMode = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::FIELD_NAME_MODE, maxLength: NULL, name: Constant::FIELD_NAME_MODE, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: $mode, wrap: NULL, noValidate: false);
        $output .= $hiddenMode->getHtml();
        $hiddenSelectedRowsPlayerId = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: SELECTED_ROWS_FIELD_NAME, maxLength: NULL, name: SELECTED_ROWS_FIELD_NAME, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: $ids, wrap: NULL, noValidate: false);
        $output .= $hiddenSelectedRowsPlayerId->getHtml();
        $hiddenSelectedRowsPlayerStatus = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: SELECTED_ROWS_TOURNAMENT_PLAYER_STATUS_FIELD_NAME, maxLength: NULL, name: SELECTED_ROWS_TOURNAMENT_PLAYER_STATUS_FIELD_NAME, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: $tournamentPlayerStatus, wrap: NULL, noValidate: false);
        $output .= $hiddenSelectedRowsPlayerStatus->getHtml();
        $output .= "</div>\n";
    } else {
        $output .= "No tournaments available to manage registrations";
    }
    if ($tournamentId != DEFAULT_VALUE_TOURNAMENT_ID) {
        $output .= " <div class=\"buttons center\">\n";
        $buttonRegister = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_REGISTER, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_REGISTER, maxLength: NULL, name: Constant::TEXT_REGISTER, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_REGISTER_UNREGISTER, wrap: NULL, noValidate: false);
        $output .= $buttonRegister->getHtml();
        $output .= " </div>\n";
        $result = $entityManager->getRepository(entityName: Constant::ENTITY_TOURNAMENTS)->getRegistrationStatus(tournamentId: $tournamentId, indexed: true);
        $resultHeaders = $entityManager->getRepository(entityName: Constant::ENTITY_TOURNAMENTS)->getRegistrationStatus(tournamentId: $tournamentId, indexed: false);
        $colFormats = array(array(3, "right", 0));
        $hideColIndexes = array(0);
        $htmlTable = new HtmlTable(caption: NULL, class: NULL, colspan: NULL, columnFormat: $colFormats, debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), delimiter: Constant::DELIMITER_DEFAULT, foreignKeys: NULL, header: true, hiddenAdditional: NULL, hiddenId: TOURNAMENT_PLAYER_ID_FIELD_NAME, hideColumnIndexes: $hideColIndexes, html: NULL, id: NULL, link: NULL, note: true, selectedRow: $ids, suffix: NULL, width: "100%");
        $output .= $htmlTable->getHtml(results: $result, resultHeaders: $resultHeaders);
    }
}
$smarty->assign("content", $output);
$smarty->display("manage.tpl");
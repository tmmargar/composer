<?php
declare(strict_types = 1);
namespace ccp;
use DateTime;
use Exception;
use PDO;
use Poker\Ccp\classes\model\BooleanString;
use Poker\Ccp\classes\model\Constant;
use Poker\Ccp\classes\model\FormControl;
use Poker\Ccp\classes\model\FormOption;
use Poker\Ccp\classes\model\FormSelect;
use Poker\Ccp\classes\model\GameType;
use Poker\Ccp\classes\model\Group;
use Poker\Ccp\classes\model\GroupPayout;
use Poker\Ccp\classes\model\HtmlTable;
use Poker\Ccp\classes\model\LimitType;
use Poker\Ccp\classes\model\Location;
use Poker\Ccp\classes\model\Payout;
use Poker\Ccp\classes\model\Player;
use Poker\Ccp\classes\model\Result;
use Poker\Ccp\classes\model\SpecialType;
use Poker\Ccp\classes\model\Status;
use Poker\Ccp\classes\model\Structure;
use Poker\Ccp\classes\model\Tournament;
use Poker\Ccp\classes\utility\SessionUtility;
use Poker\Ccp\Entity\Results;
require_once "init.php";
define("TOURNAMENT_ID_FIELD_LABEL", "Tournament");
define("TOURNAMENT_PLAYER_ID_FIELD_LABEL", "Player id");
define("TOURNAMENT_REBUY_FIELD_LABEL", "Rebuy");
define("TOURNAMENT_ADDON_FIELD_LABEL", "Addon");
define("TOURNAMENT_PLACE_FIELD_LABEL", "Place");
define("TOURNAMENT_KNOCKOUT_BY_FIELD_LABEL", "Knockout by");
define("TOURNAMENT_ID_FIELD_NAME", "tournamentId");
define("TOURNAMENT_PLAYER_ID_FIELD_NAME", "tournamentPlayerId");
define("TOURNAMENT_REBUY_FIELD_NAME", "tournamentRebuy");
define("TOURNAMENT_REBUY_COUNT_FIELD_NAME", "tournamentRebuyCount");
define("TOURNAMENT_ADDON_FIELD_NAME", "tournamentAddon");
define("TOURNAMENT_PLACE_FIELD_NAME", "tournamentPlace");
define("TOURNAMENT_KNOCKOUT_BY_FIELD_NAME", "tournamentKnockoutBy");
define("TOURNAMENT_FOOD_FIELD_NAME", "tournamentFood");
define("SELECTED_ROWS_TOURNAMENT_KNOCKOUT_BY_FIELD_NAME", "tournamentKnockoutBys");
define("HIDDEN_ROW_TOURNAMENT_PLAYER_ID_FIELD_NAME", "rowTournamentPlayerId");
define("HIDDEN_ROW_TOURNAMENT_KNOCKOUT_BY_FIELD_NAME", "rowTournamentKnockoutBy");
define("MAX_REBUYS_FIELD_NAME", "maxRebuys");
define("ADDON_AMOUNT_FIELD_NAME", "addonAmount");
define("SELECT_COLUMN_PREFIX_FIELD_NAME", "select");
define("DEFAULT_VALUE_TOURNAMENT_ID", "0");
define("DEFAULT_VALUE_TOURNAMENT_REBUY_COUNT", 0);
$smarty->assign("title", "Manage Results");
$smarty->assign("heading", "Manage Results");
$smarty->assign("style", "<link href=\"css/manageResults.css\" rel=\"stylesheet\">");
$smarty->assign("jqueryLocalAdditional", "N");
$errors = NULL;
$tournamentKnockoutBys = isset($_POST[SELECTED_ROWS_TOURNAMENT_KNOCKOUT_BY_FIELD_NAME]) ? $_POST[SELECTED_ROWS_TOURNAMENT_KNOCKOUT_BY_FIELD_NAME] : DEFAULT_VALUE_BLANK;
$tournamentIdString = isset($_POST[TOURNAMENT_ID_FIELD_NAME]) ? $_POST[TOURNAMENT_ID_FIELD_NAME] : DEFAULT_VALUE_TOURNAMENT_ID;
// id::rebuy count::addon amount (100:1:0)
$tournamentIdVals = explode("::", $tournamentIdString);
$tournamentId = (int) $tournamentIdVals[0];
$tournamentPlace = (int) isset($_POST[TOURNAMENT_PLACE_FIELD_NAME]) ? $_POST[TOURNAMENT_PLACE_FIELD_NAME] : DEFAULT_VALUE_BLANK;
$tournamentRebuyCount = (int) isset($_POST[TOURNAMENT_REBUY_FIELD_NAME]) ? $_POST[TOURNAMENT_REBUY_FIELD_NAME] : DEFAULT_VALUE_TOURNAMENT_REBUY_COUNT;
if (Constant::MODE_CREATE == $mode || Constant::MODE_MODIFY == $mode) {
    $resultList2 = $entityManager->getRepository(Constant::ENTITY_TOURNAMENTS)->getAllMultiple(championship: false, tournamentDate: NULL, startTime: NULL, tournamentId: NULL, notEntered: false, ordered: false, mode: $mode, interval: NULL, limitCount: NULL);
    if (count($resultList2) > 0) {
        $row = $resultList2[0];
        $limitType = new LimitType(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: $row["limit_type_id"], name: $row["limit"]);
        $gameType = new GameType(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: $row["game_type_id"], name: $row["type"]);
        $specialType = new SpecialType(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: $row["special_type_id"], description: $row["std"], multiplier: $row["special_type_multiplier"]);
        $player = new Player(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: 0, name: "", username: NULL, password: NULL, email: NULL, phone: NULL, administrator: "0", registrationDate: new \DateTime(), approvalDate: NULL, approvalUserid: NULL, approvalName: NULL, rejectionDate: NULL, rejectionUserid: NULL, rejectionName: NULL, active: "0", resetSelector: NULL, resetToken: NULL, resetExpires: NULL, rememberSelector: NULL, rememberToken: NULL, rememberExpires: NULL);
        $result = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getById(playerId: $row["player_id"]);
        $player->createFromEntity(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), players: $result[0]);
        $location = new Location(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: $row["location_id"], name: $row["location"], address: "", city: "", state: "", zipCode: 00000, player: $player, count: 0, active: "0", map: NULL, mapName: NULL, tournamentCount: 0);
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
        $tournament = new Tournament(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: $row["id"], description: $row["description"], comment: $row["comment"], limitType: $limitType, gameType: $gameType, specialType: $specialType, chipCount: $row["chips"], location: $location, date: new \Poker\Ccp\classes\model\DateTime(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: NULL, time: $row["date"]), startTime: new \Poker\Ccp\classes\model\DateTime(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: NULL, time: $row["start"]), buyinAmount: $row["buyin"], maxPlayers: $row["max players"], maxRebuys: $row["max"], rebuyAmount: $row["amt"], addonAmount: $row["amt "], addonChipCount: $row["chips "], groupPayout: $groupPayout, rake: (float) ($row["rake"] * 100), registeredCount: $row["registeredCount"], buyinsPaid: $row["buyinsPaid"], rebuysPaid: $row["rebuysPaid"], rebuysCount: (int) $row["rebuysCount"], addonsPaid: $row["addonsPaid"], enteredCount: $row["enteredCount"], earnings: 0);
        $maxPlayers = $tournament->getBuyinsPaid();
        $rebuyFlag = $tournament->getRebuyAmount() == 0 ? true : false;
        $addonFlag = $tournament->getAddonAmount() == 0 ? true : false;
        $maxRebuys = $tournament->getMaxRebuys();
    }
    if (count($resultList2) > 0) {
        if ($mode == Constant::MODE_CREATE) {
            $rowCount = 1;
        } elseif ($mode == Constant::MODE_MODIFY) {
            $rowCount = count($resultList2);
        }
        $ctr = 0;
        if ($ctr < $rowCount) {
            $output .= " <div class=\"responsive responsive--3cols responsive--collapse\">";
            $output .= "  <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . TOURNAMENT_ID_FIELD_NAME . "\">" . TOURNAMENT_ID_FIELD_LABEL . ": </label></div>\n";
            $selectTournament = new FormSelect(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_TOURNAMENT_ID, class: NULL, disabled: false, id: TOURNAMENT_ID_FIELD_NAME, multiple: false, name: TOURNAMENT_ID_FIELD_NAME, onClick: NULL, readOnly: false, size: 1, suffix: NULL, value: NULL);
            $output .= "  <div class=\"responsive-cell responsive-cell-value\">" . $selectTournament->getHtml();
            $option = new FormOption(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), class: NULL, disabled: false, id: NULL, name: NULL, selectedValue: !isset($tournamentId) ? DEFAULT_VALUE_TOURNAMENT_ID : "", suffix: NULL, text: Constant::TEXT_NONE, value: DEFAULT_VALUE_TOURNAMENT_ID);
            $output .= $option->getHtml();
            $cnt = 0;
            while ($cnt < count($resultList2)) {
                $row = $resultList2[$cnt];
                $limitType = new LimitType(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: $row["limit_type_id"], name: $row["limit"]);
                $gameType = new GameType(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: $row["game_type_id"], name: $row["type"]);
                $specialType = new SpecialType(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: $row["special_type_id"], description: $row["std"], multiplier: $row["special_type_multiplier"]);
                $player = new Player(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: 0, name: "", username: NULL, password: NULL, email: NULL, phone: NULL, administrator: "0", registrationDate: new \DateTime(), approvalDate: NULL, approvalUserid: NULL, approvalName: NULL, rejectionDate: NULL, rejectionUserid: NULL, rejectionName: NULL, active: "0", resetSelector: NULL, resetToken: NULL, resetExpires: NULL, rememberSelector: NULL, rememberToken: NULL, rememberExpires: NULL);
                $result = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getById(playerId: $row["player_id"]);
                $player->createFromEntity(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), players: $result[0]);
                $location = new Location(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: $row["location_id"], name: $row["location"], address: "", city: "", state: "", zipCode: 00000, player: $player, count: 0, active: "0", map: NULL, mapName: NULL, tournamentCount: 0);
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
                $tournament = new Tournament(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: $row["id"], description: $row["description"], comment: $row["comment"], limitType: $limitType, gameType: $gameType, specialType: $specialType, chipCount: $row["chips"], location: $location, date: new \Poker\Ccp\classes\model\DateTime(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: NULL, time: $row["date"]), startTime: new \Poker\Ccp\classes\model\DateTime(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: NULL, time: $row["start"]), buyinAmount: $row["buyin"], maxPlayers: $row["max players"], maxRebuys: $row["max"], rebuyAmount: $row["amt"], addonAmount: $row["amt "], addonChipCount: $row["chips "], groupPayout: $groupPayout, rake: (float) ($row["rake"] * 100), registeredCount: $row["registeredCount"], buyinsPaid: $row["buyinsPaid"], rebuysPaid: $row["rebuysPaid"], rebuysCount: (int) $row["rebuysCount"], addonsPaid: $row["addonsPaid"], enteredCount: $row["enteredCount"], earnings: 0);
                if (($tournamentId == $tournament->getId())) {
                    $endDateTemp = clone ($tournament->getDate());
                    $endDateTemp->getTime()->modify('-1 day');
                }
                $option = new FormOption(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), class: NULL, disabled: false, id: NULL, name: NULL, selectedValue: $tournamentId . "::" . $tournament->getMaxRebuys() . "::" . $tournament->getAddonAmount(), suffix: NULL, text: $tournament->getDisplayDetails(), value: $tournament->getId() . "::" . $tournament->getMaxRebuys() . "::" . $tournament->getAddonAmount());
                $output .= $option->getHtml();
                $cnt++;
            }
            $output .= "   </select>\n";
            $output .= "  </div>\n";
            $output .= "  <div class=\"responsive-cell responsive-cell-button\">\n";
            $buttonGo = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_GO, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_GO, maxLength: NULL, name: Constant::TEXT_GO, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_GO, wrap: NULL);
            $output .= $buttonGo->getHtml();
            $buttonCancel = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_CANCEL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_CANCEL, maxLength: NULL, name: Constant::TEXT_CANCEL, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_CANCEL, wrap: NULL);
            $output .= $buttonCancel->getHtml();
            $output .= "  </div>\n";
            $hiddenMode = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::FIELD_NAME_MODE, maxLength: NULL, name: Constant::FIELD_NAME_MODE, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: $mode, wrap: NULL);
            $output .= $hiddenMode->getHtml();
            if (DEFAULT_VALUE_TOURNAMENT_ID != $tournamentId) {
                $output .= " </div>\n";
                $output .= " <div class=\"buttons center\">\n";
                $buttonAddRow = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_ADD_ROW, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_ADD_ROW . "_2", maxLength: NULL, name: Constant::TEXT_ADD_ROW . "_2", onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_BUTTON, value: Constant::TEXT_ADD_ROW, wrap: NULL);
                $output .= $buttonAddRow->getHtml();
                $buttonRemoveRow = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_REMOVE_ROW, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_REMOVE_ROW . "_2", maxLength: NULL, name: Constant::TEXT_REMOVE_ROW . "_2", onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_BUTTON, value: Constant::TEXT_REMOVE_ROW, wrap: NULL);
                $output .= $buttonRemoveRow->getHtml();
                $buttonSave = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_SAVE, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_SAVE . "_2", maxLength: NULL, name: Constant::TEXT_SAVE . "_2", onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_SAVE, wrap: NULL);
                $output .= $buttonSave->getHtml();
                $buttonReset = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_RESET, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_RESET . "_2", maxLength: NULL, name: Constant::TEXT_RESET . "_2", onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_RESET, value: Constant::TEXT_RESET, wrap: NULL, noValidate: true);
                $output .= $buttonReset->getHtml();
                $buttonCancel = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_CANCEL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_CANCEL . "_2", maxLength: NULL, name: Constant::TEXT_CANCEL . "_2", onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_CANCEL, wrap: NULL, noValidate: true);
                $output .= $buttonCancel->getHtml();
                $output .= "  </div>\n";
                $result99 = $entityManager->getRepository(Constant::ENTITY_RESULTS)->getPaidOrRegisteredOrFinished(tournamentId: $tournamentId, playerId: NULL, paid: false, registered: false, finished: true, indexed: true);
                if ($mode == Constant::MODE_CREATE) {
                    $rowCount = 1;
                } elseif ($mode == Constant::MODE_MODIFY) {
                    $rowCount = count($result99);
                }
                $hideColIndexes = array(0, 1, 3, 4, 5, 6, 7, 8, 11, 13, 15, 16, 17, 18);
                $output .= "   <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"display\" id=\"" . Constant::ID_TABLE_INPUT . "\" style=\"margin: 0;\" width=\"100%\">\n";
                $ctrTemp = 0;
                while ($ctrTemp < $rowCount) {
                    if ($ctrTemp == 0) {
                        $result2 = $entityManager->getRepository(Constant::ENTITY_RESULTS)->getPaidOrRegisteredOrFinished(tournamentId: $tournamentId, playerId: NULL, paid: true, registered: false, finished: false, indexed: true);
                        $result2Headers = $entityManager->getRepository(Constant::ENTITY_RESULTS)->getPaidOrRegisteredOrFinished(tournamentId: $tournamentId, playerId: NULL, paid: true, registered: false, finished: false, indexed: false);
                        $output .= "    <thead>\n";
                        $output .= "     <tr>\n";
                        $columnCount = count($result2[0]);
                        for ($idx = 0; $idx < $columnCount; $idx ++) {
                            if (!in_array($idx, $hideColIndexes)) {
                                $headers = array_keys($result2Headers[$ctrTemp]);
                                $output .= "      <th>" . ucwords($headers[$idx]) . "</th>\n";
                            }
                        }
                        $output .= "     </tr>\n";
                        $output .= "    </thead>\n";
                        $output .= "    <tbody>\n";
                    }
                    $row = $mode == Constant::MODE_CREATE ? false : $result99[$ctrTemp];
                    $ctrTemp++;
                    if ($mode == Constant::MODE_MODIFY) {
                        if (0 < strlen($ids)) {
                            $ids .= Constant::DELIMITER_DEFAULT;
                        }
                        $ids .= $ctrTemp;
                    }
                    $output .= "     <tr>\n";
                    $resultList7 = $entityManager->getRepository(Constant::ENTITY_RESULTS)->getPaidOrRegisteredOrFinished(tournamentId: $tournamentId, playerId: NULL, paid: true, registered: false, finished: false, indexed: false);
                    if (count($resultList7) > 0) {
                        $index = 0;
                        while ($index < count($resultList7)) {
                            $result = $resultList7[$index];
                            $status = new Status(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: NULL, code: $result["status_code"], name: $result["status"]);
                            $tournament = new Tournament(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: (int) $result["tournament_id"], description: NULL, comment: NULL, limitType: NULL, gameType: NULL, specialType: NULL, chipCount: 0, location: NULL, date: NULL, startTime: NULL, buyinAmount: 0, maxPlayers: 0, maxRebuys: 0, rebuyAmount: 0, addonAmount: 0, addonChipCount: 0, groupPayout: NULL, rake: 0, registeredCount: 0, buyinsPaid: 0, rebuysPaid: 0, rebuysCount: 0, addonsPaid: 0, enteredCount: 0);
                            $player = new Player(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: (int) $result["player_id"], name: $result["name"], username: NULL, password: NULL, email: $result["player_email"], phone: NULL, administrator: "0", registrationDate: new DateTime(), approvalDate: NULL, approvalUserid: NULL, approvalName: NULL, rejectionDate: NULL, rejectionUserid: NULL, rejectionName: NULL, active: $result["player_active_flag"], resetSelector: NULL, resetToken: NULL, resetExpires: NULL, rememberSelector: NULL, rememberToken: NULL, rememberExpires: NULL);
                            if (isset($result["knocked out by"])) {
                                $nameKnockedOutBy = $result["knocked out by"];
                            } else {
                                $nameKnockedOutBy = "";
                            }
                            $knockedOutBy = new Player(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: (int) $result["player_id_ko"], name: $nameKnockedOutBy, username: NULL, password: NULL, email: NULL, phone: NULL, administrator: "0", registrationDate: new DateTime(), approvalDate: NULL, approvalUserid: NULL, approvalName: NULL, rejectionDate: NULL, rejectionUserid: NULL, rejectionName: NULL, active: isset($result["knockedOutActive"]) ? $result["knockedOutActive"] : "0", resetSelector: NULL, resetToken: NULL, resetExpires: NULL, rememberSelector: NULL, rememberToken: NULL, rememberExpires: NULL);
                            $buyinPaid2 = new BooleanString($result["result_paid_buyin_flag"]);
                            $rebuyPaid2 = new BooleanString($result["result_paid_rebuy_flag"]);
                            $addonPaid2 = new BooleanString($result["addon"]);
                            $addonFlag2 = new BooleanString($result["result_addon_flag"]);
                            $result = new Result(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: NULL, tournament: $tournament, player: $player, status: $status, registerOrder: (int) $result["result_registration_order"], buyinPaid: $buyinPaid2->getBoolean(), rebuyPaid: $rebuyPaid2->getBoolean(), addonPaid: $addonPaid2->getBoolean(), rebuyCount: (int) $result["rebuy"], addonFlag: $addonFlag2->getBoolean(), place: (int) $result["place"], knockedOutBy: $knockedOutBy, food: $result["result_registration_food"], feeStatus: $result["feeStatus"]);
                            $aryPlayerInfo[$index] = array($result->getPlayer()->getId(), $result->getPlayer()->getName(), $result->getPlayer()->getId() . "::" . ($result->isRebuyPaid() ? Constant::FLAG_YES : Constant::FLAG_NO) . "::" . $result->getRebuyCount() . "::" . ($result->isAddonPaid() ? Constant::FLAG_YES : Constant::FLAG_NO));
                            $index++;
                        }
                    }
                    if (count($aryPlayerInfo) > 0) {
                        $index = 0;
                        $output .= "      <td class=\"center\">\n";
                        $selectPlayer = new FormSelect(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_PLAYER_ID, class: NULL, disabled: false, id: TOURNAMENT_PLAYER_ID_FIELD_NAME . "_" . $ctrTemp, multiple: false, name: TOURNAMENT_PLAYER_ID_FIELD_NAME . "_" . $ctrTemp, onClick: NULL, readOnly: false, size: 1, suffix: NULL, value: NULL);
                        $output .= $selectPlayer->getHtml();
                        $option = new FormOption(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), class: NULL, disabled: false, id: NULL, name: NULL, selectedValue: DEFAULT_VALUE_BLANK, suffix: NULL, text: Constant::TEXT_NONE, value: "");
                        $output .= $option->getHtml();
                        while ($index < count($aryPlayerInfo)) {
                            $option = new FormOption(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), class: NULL, disabled: false, id: NULL, name: NULL, selectedValue: $row !== false && $row[1] == $aryPlayerInfo[$index][0] ? $aryPlayerInfo[$index][2] : "", suffix: NULL, text: $aryPlayerInfo[$index][1], value: $aryPlayerInfo[$index][2]);
                            $output .= $option->getHtml();
                            $index ++;
                        }
                        $output .= "       </select>\n";
                        $output .= "      </td>\n";
                    }
                    $output .= "      <td class=\"center\">\n";
                    $booleanString = new BooleanString(value: $row !== false ? $row[8] : "");
                    $checkboxRebuy = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: $booleanString->getBoolean(), class: NULL, cols: NULL, disabled: $rebuyFlag, id: TOURNAMENT_REBUY_FIELD_NAME . "_" . $ctrTemp, maxLength: NULL, name: TOURNAMENT_REBUY_FIELD_NAME . "_" . $ctrTemp, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_CHECKBOX, value: NULL, wrap: NULL);
                    $output .= $checkboxRebuy->getHtml();
                    $textBoxRebuyCount = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_REBUY_COUNT, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: ($row !== false && 0 == $row[9] ? true : false), id: TOURNAMENT_REBUY_COUNT_FIELD_NAME . "_" . $ctrTemp, maxLength: 2, name: TOURNAMENT_REBUY_COUNT_FIELD_NAME . "_" . $ctrTemp, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: 2, suffix: NULL, type: FormControl::TYPE_INPUT_NUMBER, value: $row !== false ? $row[9] : "", wrap: NULL);
                    $output .= $textBoxRebuyCount->getHtml();
                    $output .= "      </td>\n";
                    $output .= "      <td class=\"center\">\n";
                    $booleanString = new BooleanString(value: $row !== false ? $row[10] : "");
                    $checkboxAddon = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: $booleanString->getBoolean(), class: NULL, cols: NULL, disabled: $addonFlag, id: TOURNAMENT_ADDON_FIELD_NAME . "_" . $ctrTemp, maxLength: NULL, name: TOURNAMENT_ADDON_FIELD_NAME . "_" . $ctrTemp, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_CHECKBOX, value: Constant::FLAG_YES, wrap: NULL);
                    $output .= $checkboxAddon->getHtml();
                    $output .= "      </td>\n";
                    if ($row === false || "" == $row[1]) {
                        $player = 0;
                    } else {
                        $player = $row[1];
                    }
                    $output .= "      <td class=\"center\">\n";
                    $textBoxPlace = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: true, id: TOURNAMENT_PLACE_FIELD_NAME . "_" . $ctrTemp, maxLength: 2, name: TOURNAMENT_PLACE_FIELD_NAME . "_" . $ctrTemp, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: 2, suffix: NULL, type: FormControl::TYPE_INPUT_TEXTBOX, value: (string) (isset($row[12]) ? $row[12] : $maxPlayers), wrap: NULL);
                    $output .= $textBoxPlace->getHtml();
                    $output .= "      </td>\n";
                    if (count($aryPlayerInfo) > 0) {
                        $index = 0;
                        $output .= "      <td class=\"center\">\n";
                        $selectPlayer = new FormSelect(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_KNOCKOUT_ID, class: NULL, disabled: false, id: TOURNAMENT_KNOCKOUT_BY_FIELD_NAME . "_" . $ctrTemp, multiple: false, name: TOURNAMENT_KNOCKOUT_BY_FIELD_NAME . "_" . $ctrTemp, onClick: NULL, readOnly: false, size: 1, suffix: NULL, value: NULL);
                        $output .= $selectPlayer->getHtml();
                        $option = new FormOption(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), class: NULL, disabled: false, id: NULL, name: NULL, selectedValue: $row !== false ? $row[13] : "", suffix: NULL, text: Constant::TEXT_NONE, value: DEFAULT_VALUE_BLANK);
                        $output .= $option->getHtml();
                        while ($index < count($aryPlayerInfo)) {
                            $option = new FormOption(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), class: NULL, disabled: false, id: NULL, name: NULL, selectedValue: $row !== false && $row[13] == $aryPlayerInfo[$index][0] ? $aryPlayerInfo[$index][2] : "", suffix: NULL, text: $aryPlayerInfo[$index][1], value: $aryPlayerInfo[$index][2]);
                            $output .= $option->getHtml();
                            $index++;
                        }
                        $output .= "       </select>\n";
                        $output .= "      </td>\n";
                    }
                    $output .= "     </tr>\n";
                }
                $output .= "    </tbody>\n";
                $output .= "   </table>\n";
                $output .= " <div class=\"buttons center\">\n";
                $buttonAddRow = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_ADD_ROW, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_ADD_ROW, maxLength: NULL, name: Constant::TEXT_ADD_ROW, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_BUTTON, value: Constant::TEXT_ADD_ROW, wrap: NULL);
                $output .= $buttonAddRow->getHtml();
                $buttonRemoveRow = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_REMOVE_ROW, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_REMOVE_ROW, maxLength: NULL, name: Constant::TEXT_REMOVE_ROW, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_BUTTON, value: Constant::TEXT_REMOVE_ROW, wrap: NULL);
                $output .= $buttonRemoveRow->getHtml();
                $buttonSave = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_SAVE, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_SAVE, maxLength: NULL, name: Constant::TEXT_SAVE, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_SAVE, wrap: NULL);
                $output .= $buttonSave->getHtml();
                $buttonReset = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_RESET, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_RESET, maxLength: NULL, name: Constant::TEXT_RESET, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_RESET, value: Constant::TEXT_RESET, wrap: NULL);
                $output .= $buttonReset->getHtml();
                $buttonCancel = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_CANCEL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_CANCEL, maxLength: NULL, name: Constant::TEXT_CANCEL, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_CANCEL, wrap: NULL);
                $output .= $buttonCancel->getHtml();
                $output .= " </div>\n";
            }
        } else {
          $output .= "The tournament selected does not have any entered results to modify.\n";
          $hiddenTournamentId = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: TOURNAMENT_ID_FIELD_NAME, maxLength: NULL, name: TOURNAMENT_ID_FIELD_NAME, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: $tournamentId, wrap: NULL);
          $output .= $hiddenTournamentId->getHtml();
        }
    } else {
        $output .= "No tournaments found with paid buyins and without any results\n";
        $output .= "<br />\n";
        $buttonCancel = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_CANCEL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_CANCEL, maxLength: NULL, name: Constant::TEXT_CANCEL, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_CANCEL, wrap: NULL);
        $output .= $buttonCancel->getHtml();
    }
    $hiddenSelectedRowsPlayerId = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: SELECTED_ROWS_FIELD_NAME, maxLength: NULL, name: SELECTED_ROWS_FIELD_NAME, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: NULL, wrap: NULL);
    $output .= $hiddenSelectedRowsPlayerId->getHtml();
    $hiddenRebuys = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: MAX_REBUYS_FIELD_NAME, maxLength: NULL, name: MAX_REBUYS_FIELD_NAME, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: (isset($maxRebuys) ? (string) $maxRebuys : ""), wrap: NULL);
    $output .= $hiddenRebuys->getHtml();
    $hiddenAddonAmount = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: ADDON_AMOUNT_FIELD_NAME, maxLength: NULL, name: ADDON_AMOUNT_FIELD_NAME, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: NULL, wrap: NULL);
    $output .= $hiddenAddonAmount->getHtml();
} elseif ($mode == Constant::MODE_SAVE_CREATE || $mode == Constant::MODE_SAVE_MODIFY) {
    $ary = explode(Constant::DELIMITER_DEFAULT, $ids);
    // clear all rows
    $rowCount = $entityManager->getRepository(Constant::ENTITY_RESULTS)->updateFinish(rebuyCount: NULL, rebuyPaidFlag: NULL, addonPaidFlag: NULL, addonFlag: NULL, statusCode: Constant::CODE_STATUS_PAID, place: 0, playerIdKo: NULL, tournamentId: $tournamentId, playerId: NULL);
    $numRows = count($ary) + 1;
    $ctr = 1;
    while ($ctr < $numRows) {
        if (isset($_POST[TOURNAMENT_KNOCKOUT_BY_FIELD_NAME . "_" . $ctr])) {
          // knockout value is id, rebuyPaid, rebuyCount, addonPaid (100::N::0::N)
            $knockout = $_POST[TOURNAMENT_KNOCKOUT_BY_FIELD_NAME . "_" . $ctr];
            $aryKnockout = explode("::", $knockout);
            $tournamentTempKnockout = ($aryKnockout[0] == "") ? NULL : (int) $aryKnockout[0];
        } else {
            $tournamentTempKnockout = NULL;
        }
        if (isset($_POST[TOURNAMENT_PLAYER_ID_FIELD_NAME . "_" . $ctr])) {
          // player value is id, rebuyPaid, rebuyCount, addonPaid (100::N::0::N)
            $player = $_POST[TOURNAMENT_PLAYER_ID_FIELD_NAME . "_" . $ctr];
            $aryPlayer = explode("::", $player);
            $tournamentTempPlayerId = (int) $aryPlayer[0];
        }
        $tournamentRebuyCount = (int) (isset($_POST[TOURNAMENT_REBUY_COUNT_FIELD_NAME . "_" . $ctr]) ? $_POST[TOURNAMENT_REBUY_COUNT_FIELD_NAME . "_" . $ctr] : DEFAULT_VALUE_TOURNAMENT_REBUY_COUNT);
        if (isset($_POST[TOURNAMENT_ADDON_FIELD_NAME . "_" . $ctr])) {
            $tournamentAddonAmount = $_POST[TOURNAMENT_ADDON_FIELD_NAME . "_" . $ctr];
            if ($tournamentAddonAmount == Constant::VALUE_DEFAULT_CHECKBOX || $tournamentAddonAmount == Constant::FLAG_YES) {
                $tournamentAddonAmount = Constant::FLAG_YES;
            } else {
                $tournamentAddonAmount = Constant::FLAG_NO;
            }
        } else {
            $tournamentAddonAmount = Constant::FLAG_NO;
        }
        $tournamentPlace = (int) (isset($_POST[TOURNAMENT_PLACE_FIELD_NAME . "_" . $ctr]) ? $_POST[TOURNAMENT_PLACE_FIELD_NAME . "_" . $ctr] : 0);
        // registration creates the record so just need to update the record for CREATE
        // instead of a normal INSERT so CREATE AND MODIFY are the same
        $rowCount = $entityManager->getRepository(Constant::ENTITY_RESULTS)->updateFinish(rebuyCount: $tournamentRebuyCount, rebuyPaidFlag: ($tournamentRebuyCount == 0 ? Constant::FLAG_NO : Constant::FLAG_YES), addonPaidFlag: $tournamentAddonAmount, addonFlag: NULL, statusCode: Constant::CODE_STATUS_FINISHED, place: $tournamentPlace, playerIdKo: $tournamentTempKnockout, tournamentId: $tournamentId, playerId: $tournamentTempPlayerId);
        $ctr++;
    }
    $ids = DEFAULT_VALUE_BLANK;
    $mode = Constant::MODE_VIEW;
}
if ($mode == Constant::MODE_VIEW || $mode == Constant::MODE_DELETE || $mode == Constant::MODE_CONFIRM) {
    if ($mode == Constant::MODE_CONFIRM) {
        if ($tournamentId != DEFAULT_VALUE_TOURNAMENT_ID) {
            $rowCount = $entityManager->getRepository(Constant::ENTITY_RESULTS)->updateFinish(rebuyCount: NULL, rebuyPaidFlag: NULL, addonPaidFlag: NULL, addonFlag: NULL, statusCode: Constant::CODE_STATUS_PAID, place: 0, playerIdKo: NULL, tournamentId: $tournamentId, playerId: NULL);
            $ids = DEFAULT_VALUE_BLANK;
        }
        $mode = Constant::MODE_VIEW;
    }
    $resultList = $entityManager->getRepository(Constant::ENTITY_TOURNAMENTS)->getAllMultiple(championship: false, tournamentDate: NULL, startTime: NULL, tournamentId: NULL, notEntered: false, ordered: true, mode: NULL, interval: NULL, limitCount: NULL);
    if (0 < count($resultList)) {
        $output .= " <div class=\"responsive responsive--3cols responsive--collapse\">";
        $output .= "  <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . TOURNAMENT_ID_FIELD_NAME . "\">" . TOURNAMENT_ID_FIELD_LABEL . ": </label></div>\n";
        $selectTournament = new FormSelect(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_TOURNAMENT_ID, class: NULL, disabled: false, id: TOURNAMENT_ID_FIELD_NAME, multiple: false, name: TOURNAMENT_ID_FIELD_NAME, onClick: NULL, readOnly: false, size: 1, suffix: NULL, value: NULL);
        $output .= "  <div class=\"responsive-cell responsive-cell-value\">" . $selectTournament->getHtml();
        $option = new FormOption(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), class: NULL, disabled: false, id: NULL, name: NULL, selectedValue: $tournamentId, suffix: NULL, text: Constant::TEXT_NONE, value: DEFAULT_VALUE_TOURNAMENT_ID);
        $output .= $option->getHtml();
        $cnt = 0;
        while ($cnt < count($resultList)) {
            $row = $resultList[$cnt];
            $limitType = new LimitType(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: $row["limit_type_id"], name: $row["limit"]);
            $gameType = new GameType(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: $row["game_type_id"], name: $row["type"]);
            $specialType = new SpecialType(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: $row["special_type_id"], description: $row["std"], multiplier: $row["special_type_multiplier"]);
            $player = new Player(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: 0, name: "", username: NULL, password: NULL, email: NULL, phone: NULL, administrator: "0", registrationDate: new \DateTime(), approvalDate: NULL, approvalUserid: NULL, approvalName: NULL, rejectionDate: NULL, rejectionUserid: NULL, rejectionName: NULL, active: "0", resetSelector: NULL, resetToken: NULL, resetExpires: NULL, rememberSelector: NULL, rememberToken: NULL, rememberExpires: NULL);
            $result = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getById(playerId: $row["player_id"]);
            $player->createFromEntity(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), players: $result[0]);
            $location = new Location(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: $row["location_id"], name: $row["location"], address: "", city: "", state: "", zipCode: 00000, player: $player, count: 0, active: "0", map: NULL, mapName: NULL, tournamentCount: 0);
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
            $tournament = new Tournament(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: $row["id"], description: $row["description"], comment: $row["comment"], limitType: $limitType, gameType: $gameType, specialType: $specialType, chipCount: $row["chips"], location: $location, date: new \Poker\Ccp\classes\model\DateTime(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: NULL, time: $row["date"]), startTime: new \Poker\Ccp\classes\model\DateTime(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: NULL, time: $row["start"]), buyinAmount: $row["buyin"], maxPlayers: $row["max players"], maxRebuys: $row["max"], rebuyAmount: $row["amt"], addonAmount: $row["amt "], addonChipCount: $row["chips "], groupPayout: $groupPayout, rake: (float) ($row["rake"] * 100), registeredCount: $row["registeredCount"], buyinsPaid: $row["buyinsPaid"], rebuysPaid: $row["rebuysPaid"], rebuysCount: (int) $row["rebuysCount"], addonsPaid: $row["addonsPaid"], enteredCount: $row["enteredCount"], earnings: 0);
            $optionText = $tournament->getDate()->getDisplayFormat();
            $optionText .= "@" . $tournament->getStartTime()->getDisplayAmPmFormat();
            $optionText .= " (" . $tournament->getLocation()->getName() . ")";
            $optionText .= " " . $tournament->getLimitType()->getName();
            $optionText .= " " . $tournament->getGameType()->getName();
            $optionText .= " " . $tournament->getMaxRebuys() . "r" . (0 < $tournament->getAddonAmount() ? "+a" : "");
            $waitListCnt = $tournament->getRegisteredCount() - $tournament->getMaxPlayers();
            $optionText .= " (" . $tournament->getRegisteredCount() . ($waitListCnt > 0 ? "+" . $waitListCnt . "wl" : "") . "np/" . $tournament->getBuyinsPaid() . "p";
            $optionText .= "+" . $tournament->getRebuysPaid() . "rp";
            $optionText .= "+" . $tournament->getAddonsPaid() . "ap";
            $optionText .= "/" . $tournament->getEnteredCount() . "ent)";
            $option = new FormOption(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), class: NULL, disabled: false, id: NULL, name: NULL, selectedValue: $tournamentId, suffix: NULL, text: $optionText, value: $tournament->getId());
            $output .= $option->getHtml();
            $cnt++;
        }
        $output .= "   </select>\n";
        $output .= "  </div>\n";
        $output .= "  <div class=\"responsive-cell responsive-cell-button\">\n";
        if ($mode == Constant::MODE_VIEW) {
            $buttonView = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_VIEW, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_VIEW, maxLength: NULL, name: Constant::TEXT_VIEW, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_VIEW, wrap: NULL);
            $output .= $buttonView->getHtml();
            $buttonModify = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_UPDATE, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_UPDATE, maxLength: NULL, name: Constant::TEXT_UPDATE, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_UPDATE, wrap: NULL);
            $output .= $buttonModify->getHtml();
            if (DEFAULT_VALUE_TOURNAMENT_ID != $tournamentId) {
                $buttonDelete = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_DELETE, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_DELETE, maxLength: NULL, name: Constant::TEXT_DELETE, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_DELETE, wrap: NULL);
                $output .= $buttonDelete->getHtml();
            }
            $buttonCreate = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_CREATE, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_CREATE, maxLength: NULL, name: Constant::TEXT_CREATE, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_CREATE, wrap: NULL);
            $output .= $buttonCreate->getHtml();
        }
        if ($mode == Constant::MODE_DELETE) {
            $buttonConfirmDelete = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_CONFIRM_DELETE, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_CONFIRM_DELETE, maxLength: NULL, name: Constant::TEXT_CONFIRM_DELETE, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_CONFIRM_DELETE, wrap: NULL);
            $output .= $buttonConfirmDelete->getHtml();
            $buttonCancel = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_CANCEL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_CANCEL, maxLength: NULL, name: Constant::TEXT_CANCEL, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_CANCEL, wrap: NULL);
            $output .= $buttonCancel->getHtml();
        }
        $output .= "  </div>\n";
        if ($mode == Constant::MODE_VIEW || $mode == Constant::MODE_DELETE) {
            $hiddenMode = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::FIELD_NAME_MODE, maxLength: NULL, name: Constant::FIELD_NAME_MODE, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: $mode, wrap: NULL);
            $output .= $hiddenMode->getHtml();
        }
        $hiddenPlayerId = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: SELECTED_ROWS_FIELD_NAME, maxLength: NULL, name: SELECTED_ROWS_FIELD_NAME, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: $ids, wrap: NULL);
        $output .= $hiddenPlayerId->getHtml();
        $hiddenKnockout = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: SELECTED_ROWS_TOURNAMENT_KNOCKOUT_BY_FIELD_NAME, maxLength: NULL, name: SELECTED_ROWS_TOURNAMENT_KNOCKOUT_BY_FIELD_NAME, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: $tournamentKnockoutBys, wrap: NULL);
        $output .= $hiddenKnockout->getHtml();
    }
    $output .= " </div>\n";
    if ($tournamentId != DEFAULT_VALUE_TOURNAMENT_ID) {
        $params = array($tournamentId);
        if ($mode == Constant::MODE_VIEW || $mode == Constant::MODE_DELETE) {
            $result = $entityManager->getRepository(Constant::ENTITY_RESULTS)->getPaidOrRegisteredOrFinished(tournamentId: $tournamentId, playerId: NULL, paid: true, registered: false, finished: false, indexed: true);
            $resultHeaders = $entityManager->getRepository(Constant::ENTITY_RESULTS)->getPaidOrRegisteredOrFinished(tournamentId: $tournamentId, playerId: NULL, paid: true, registered: false, finished: false, indexed: false);
        }
        $colFormats = array(array(9, "number", 0), array(12, "number", 0));
        $hiddenAdditional = array(array("tournamentPlayerId", 1));
        $hideColIndexes = array(0, 1, 3, 4, 6, 7, 8, 11, 13, 15, 16, 17, 18);
        $htmlTable = new HtmlTable(caption: NULL, class: NULL, colspan: NULL, columnFormat: $colFormats, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), delimiter: Constant::DELIMITER_DEFAULT, foreignKeys: NULL, header: true, hiddenAdditional: $hiddenAdditional, hiddenId: NULL, hideColumnIndexes: $hideColIndexes, html: NULL, id: NULL, link: NULL, note: true, selectedRow: $ids, suffix: NULL, width: "100%");
        $output .= $htmlTable->getHtml(results: $result, resultHeaders: $resultHeaders);
    }
}
$smarty->assign("content", $output);
$smarty->display("manage.tpl");
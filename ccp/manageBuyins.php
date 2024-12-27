<?php
declare(strict_types = 1);
namespace ccp;
use DateTime;
use Exception;
use Poker\Ccp\Model\Base;
use Poker\Ccp\Model\Constant;
use Poker\Ccp\Model\FormControl;
use Poker\Ccp\Model\FormOption;
use Poker\Ccp\Model\FormSelect;
use Poker\Ccp\Model\GameType;
use Poker\Ccp\Model\Group;
use Poker\Ccp\Model\GroupPayout;
use Poker\Ccp\Model\HtmlTable;
use Poker\Ccp\Model\LimitType;
use Poker\Ccp\Model\Location;
use Poker\Ccp\Model\Payout;
use Poker\Ccp\Model\Player;
use Poker\Ccp\Model\Result;
use Poker\Ccp\Model\SpecialType;
use Poker\Ccp\Model\Status;
use Poker\Ccp\Model\Structure;
use Poker\Ccp\Model\Tournament;
use Poker\Ccp\Utility\SessionUtility;
use Poker\Ccp\Entity\Fees;
use Poker\Ccp\Entity\Results;
require_once "init.php";
define("TOURNAMENT_ID_FIELD_LABEL", "Tournament");
define("TOURNAMENT_ID_FIELD_NAME", "tournamentId");
define("HIDDEN_ROW_FEE_PAID_FIELD_NAME", "feePaid");
define("HIDDEN_ROW_BUYIN_PAID_FIELD_NAME", "buyinPaid");
define("HIDDEN_ROW_REBUY_PAID_FIELD_NAME", "rebuyPaid");
define("HIDDEN_ROW_REBUY_COUNT_FIELD_NAME", "rebuyCount");
define("HIDDEN_ROW_ADDON_PAID_FIELD_NAME", "addonPaid");
define("DEFAULT_VALUE_TOURNAMENT_ID", "-1");
define("PAID_TEXT", "Paid");
define("REBUY_FLAG_FIELD_NAME", "rebuyFlag");
define("ADDON_FLAG_FIELD_NAME", "addonFlag");
define("SEASON_FEE_FIELD_NAME", "seasonFee");
define("TOTAL_SEASON_FEE_FIELD_NAME", "totalSeasonFee");
define("PLAYER_SEASON_FEE_FIELD_NAME", "playerSeasonFee");
$style =
  "<style type=\"text/css\">\n" .
  ".label {\n" .
  "  float: left;\n" .
  "  width: 150px;\n" .
  "  text-align: right;\n" .
  "}\n" .
  ".value {\n" .
  "  float: left;\n" .
  "  text-align: right;\n" .
  "  width: 50px;\n" .
  "}\n" .
  ".valueAfter {\n" .
  "  float: left;\n" .
  "  padding-left: 5px;\n" .
  "  text-align: right;\n" .
  "  width: 150px;\n" .
  "}\n" .
  "p {\n" .
  "  margin: 0;\n" .
  "  padding: 0;\n" .
"}\n" . "</style>\n";
$smarty->assign("title", "Manage Buyins");
$smarty->assign("heading", "Manage Buyins");
$smarty->assign("style", "<link href=\"css/manageBuyins.css\" rel=\"stylesheet\">");
$errors = NULL;
$import = "  import { inputLocal } from \"./scripts/manageBuyins.js\";\n";
$tournamentId = (int) (isset($_POST[TOURNAMENT_ID_FIELD_NAME]) ? $_POST[TOURNAMENT_ID_FIELD_NAME] : DEFAULT_VALUE_TOURNAMENT_ID);
if (Constant::MODE_SAVE_CREATE == $mode || Constant::MODE_SAVE_MODIFY == $mode) {
    $aryPlayers = explode(separator: Constant::DELIMITER_DEFAULT, string: $_POST[SELECTED_ROWS_FIELD_NAME]);
    $aryFees = explode(separator: Constant::DELIMITER_DEFAULT, string: $_POST[HIDDEN_ROW_FEE_PAID_FIELD_NAME]);
    $aryBuyins = explode(separator: Constant::DELIMITER_DEFAULT, string: $_POST[HIDDEN_ROW_BUYIN_PAID_FIELD_NAME]);
    $aryRebuys = explode(separator: Constant::DELIMITER_DEFAULT, string: $_POST[HIDDEN_ROW_REBUY_PAID_FIELD_NAME]);
    $aryRebuyCounts = explode(separator: Constant::DELIMITER_DEFAULT, string: $_POST[HIDDEN_ROW_REBUY_COUNT_FIELD_NAME]);
    $aryAddons = explode(separator: Constant::DELIMITER_DEFAULT, string: $_POST[HIDDEN_ROW_ADDON_PAID_FIELD_NAME]);
    $output .=
        "<script type=\"module\">\n" .
        "  import { dataTable, display, input } from \"./scripts/import.js\";\n" .
        "  let aryErrors = [];\n";
    $params = array($tournamentId);
    $resultList = $entityManager->getRepository(entityName: Constant::ENTITY_SEASONS)->getForTournament(tournamentId: $tournamentId);
    if (count(value: $resultList) > 0) {
        $seasonId = (int) $resultList[0]["season_id"];
    }
    for ($index = 0; $index < count(value: $aryPlayers); $index++) {
        if (" " != $aryFees[$index]) {
            $fe = $entityManager->getRepository(entityName: Constant::ENTITY_FEES)->findOneBy(array("seasons" => (int) $seasonId, "players" => (int) $aryPlayers[$index], "tournaments" => $tournamentId));
            if (NULL !== $fe) {
                $fe->setFeeAmount(feeAmount: (int) $aryFees[$index]);
                $entityManager->persist(entity: $fe);
                try {
                    $entityManager->flush();
                } catch (Exception $e) {
                    $errors = $e->getMessage();
                }
            } else {
                $rowCount = $entityManager->getRepository(entityName: Constant::ENTITY_FEES)->deleteForSeasonAndPlayer(seasonId: $seasonId, playerId: (int) $aryPlayers[$index]);
                $fe = new Fees();
                $fe->setFeeAmount(feeAmount: (int) $aryFees[$index]);
                $player = $entityManager->find(className: Constant::ENTITY_PLAYERS, id: (int) $aryPlayers[$index]);
                $fe->setPlayers(players: $player);
                $season = $entityManager->find(className: Constant::ENTITY_SEASONS, id: (int) $seasonId);
                $fe->setSeasons(seasons: $season);
                $tournament = $entityManager->find(className: Constant::ENTITY_TOURNAMENTS, id: $tournamentId);
                $fe->setTournaments(tournaments: $tournament);
                $entityManager->persist(entity: $fe);
                try {
                    $entityManager->flush();
                } catch (Exception $e) {
                    $errors = $e->getMessage();
                }
            }
            if (NULL !== $errors) {
                $output .= "  aryErrors.push(\"" . $errors . "\");\n";
            }
        }
        if ($aryBuyins[$index] == Constant::TEXT_TRUE) {
            $statusCode = Constant::CODE_STATUS_PAID;
            $buyinPaid = Constant::FLAG_YES;
        } else {
            $statusCode = Constant::CODE_STATUS_REGISTERED;
            $buyinPaid = Constant::FLAG_NO;
        }
        if ($aryRebuys[$index] == Constant::TEXT_TRUE) {
            $rebuyPaid = Constant::FLAG_YES;
            $rebuyCount = (int) $aryRebuyCounts[$index];
        } else {
            $rebuyPaid = Constant::FLAG_NO;
            $rebuyCount = (int) 0;
        }
        if ($aryAddons[$index] == Constant::TEXT_TRUE) {
            $addonPaid = Constant::FLAG_YES;
        } else {
            $addonPaid = Constant::FLAG_NO;
        }
        $re = $entityManager->getRepository(entityName: Constant::ENTITY_RESULTS)->findOneBy(array("players" => (int) $aryPlayers[$index], "tournaments" => $tournamentId));
        $player = $entityManager->find(className: Constant::ENTITY_PLAYERS, id: (int) $aryPlayers[$index]);
        $re->setPlayers(players: $player);
        $re->setResultPaidAddonFlag(resultPaidAddonFlag: $addonPaid);
        $re->setResultPaidBuyinFlag(resultPaidBuyinFlag: $buyinPaid);
        $re->setResultPaidRebuyFlag(resultPaidRebuyFlag: $rebuyPaid);
        $re->setResultRebuyCount(resultRebuyCount: $rebuyCount);
        $statusCode = $entityManager->find(className: Constant::ENTITY_STATUS_CODES, id: $statusCode);
        $re->setStatusCodes(statusCodes: $statusCode);
        $tournament = $entityManager->find(className: Constant::ENTITY_TOURNAMENTS, id: $tournamentId);
        $re->setTournaments(tournaments: $tournament);
        $entityManager->persist(entity: $re);
        try {
            $entityManager->flush();
        } catch (Exception $e) {
            $errors = $e->getMessage();
        }
    }
    if (NULL !== $errors) {
        $output .= "  aryErrors.push(\"" . $errors . "\");\n";
    }
    $output .= "  if (aryErrors.length > 0) {display.showErrors({errors: aryErrors});}\n</script>\n";
    $mode = Constant::MODE_VIEW;
}
if ($mode == Constant::MODE_VIEW) {
    $resultList = $entityManager->getRepository(entityName: Constant::ENTITY_TOURNAMENTS)->getAllMultiple(championship: false, tournamentDate: NULL, startTime: NULL, tournamentId: NULL, notEntered: true, ordered: false, mode: NULL, interval: NULL, limitCount: NULL);
    if (count($resultList) > 0) {
        $output .= "<div class=\"responsive responsive--3cols responsive--collapse\">";
        $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . TOURNAMENT_ID_FIELD_NAME . "\">" . TOURNAMENT_ID_FIELD_LABEL . ": </label></div>\n";
        $selectTournament = new FormSelect(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_TOURNAMENT_ID, class: NULL, disabled: false, id: TOURNAMENT_ID_FIELD_NAME, multiple: false, name: TOURNAMENT_ID_FIELD_NAME, onClick: NULL, readOnly: false, size: 1, suffix: NULL, value: NULL);
        $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $selectTournament->getHtml();
        $option = new FormOption(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), class: NULL, disabled: false, id: NULL, name: NULL, selectedValue: !isset($tournamentId) ? DEFAULT_VALUE_TOURNAMENT_ID : "", suffix: NULL, text: Constant::TEXT_NONE, value: DEFAULT_VALUE_TOURNAMENT_ID);
        $output .= $option->getHtml();
        $cnt = 0;
        while ($cnt < count($resultList)) {
            $row = $resultList[$cnt];
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
            $payoutsTemp = array();
            $counterPayout = 0;
            foreach($group->getGroups()->getGroupPayouts() as $groupPayout) {
                $result = $entityManager->getRepository(entityName: Constant::ENTITY_STRUCTURES)->getById(payoutId: $groupPayout->getPayouts()->getPayoutId());
                $structure->createFromEntity(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), structures: $result[0]);
                $payout = new Payout(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), id: 0, name: "", minPlayers: 0, maxPlayers: 0, structures: array($structure));
                $result = $entityManager->getRepository(entityName: Constant::ENTITY_PAYOUTS)->getById(payoutId: $groupPayout->getPayouts()->getPayoutId());
                $payout->createFromEntity(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), payouts: $result[0]);
                $payoutsTemp[$counterPayout] = $payout;
                $counterPayout++;
            }
            $groupPayout = new GroupPayout(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), id: "", group: $group, payouts: $payoutsTemp);
            $tournament = new Tournament(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), id: $row["id"], description: $row["description"], comment: $row["comment"], limitType: $limitType, gameType: $gameType, specialType: $specialType, chipCount: $row["chips"], location: $location, date: new DateTime(datetime: $row["date"]), startTime: new DateTime(datetime: $row["start"]), buyinAmount: $row["buyin"], maxPlayers: $row["max players"], maxRebuys: $row["max"], rebuyAmount: $row["amt"], addonAmount: $row["amt "], addonChipCount: $row["chips "], groupPayout: $groupPayout, rake: (float) ($row["rake"] * 100), registeredCount: $row["registeredCount"], buyinsPaid: $row["buyinsPaid"], rebuysPaid: $row["rebuysPaid"], rebuysCount: (int) $row["rebuysCount"], addonsPaid: $row["addonsPaid"], enteredCount: $row["enteredCount"], earnings: 0);
            $option = new FormOption(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), class: NULL, disabled: false, id: NULL, name: NULL, selectedValue: $tournamentId, suffix: NULL, text: $tournament->getDisplayDetails(), value: $tournament->getId());
            $output .= $option->getHtml();
            $cnt++;
            if ($tournamentId == $tournament->getId()) {
                $maxRebuys = (int) $tournament->getMaxRebuys();
                $addonAmount = (int) $tournament->getAddonAmount();
            }
            $totalBuyin[$tournament->getId()] = array((int) $tournament->getBuyinsPaid(), (int) -$tournament->getBuyinAmount());
            $totalRebuy[$tournament->getId()] = array((int) $tournament->getRebuysPaid(), (int) $tournament->getRebuysCount(), (int) -$tournament->getRebuyAmount());
            $totalAddon[$tournament->getId()] = array((int) $tournament->getAddonsPaid(),(int) -$tournament->getAddonAmount());
            if (strpos($tournament->getDescription(), "Championship") === false) {
                $championshipFlag[$tournament->getId()] = false;
                $total[$tournament->getId()] = (int) (($totalBuyin[$tournament->getId()][0] * $totalBuyin[$tournament->getId()][1]) + ($totalRebuy[$tournament->getId()][1] * $totalRebuy[$tournament->getId()][2]) + ($totalAddon[$tournament->getId()][0] * $totalAddon[$tournament->getId()][1]));
            } else {
                $championshipFlag[$tournament->getId()] = true;
                $resultListNested = $entityManager->getRepository(entityName: Constant::ENTITY_TOURNAMENTS)->getPrizePool(startDate: new DateTime(SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_START_DATE)), endDate: new DateTime(SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_END_DATE)));
                if (0 < count($resultListNested)) {
                    $total[$tournament->getId()] = (int) str_replace(search: ",", replace: "", subject: number_format((float) $resultListNested[0]["total"], 0));
                }
            }
            $rake[$tournament->getId()] = ceil(num: $total[$tournament->getId()] * $tournament->getRakeForCalculation());
            $rakePercent[$tournament->getId()] = $tournament->getRakeForCalculation();
            $tournamentPayouts = $tournament->getGroupPayout()->getPayouts();
            $payouts[$tournament->getId()] = $tournamentPayouts;
        }
        $output .= "   </select>\n";
        $output .= "  </div>\n";
        $output .= " <div class=\"responsive-cell responsive-cell-button\">\n";
        $buttonView = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_VIEW, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_VIEW, maxLength: NULL, name: Constant::TEXT_VIEW, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_VIEW, wrap: NULL, import: NULL, noValidate: true);
        $output .= $buttonView->getHtml();
        $output .= " </div>\n";
        $hiddenSelectedRowsPlayerId = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: SELECTED_ROWS_FIELD_NAME, maxLength: NULL, name: SELECTED_ROWS_FIELD_NAME, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: NULL, wrap: NULL, noValidate: false);
        $output .= $hiddenSelectedRowsPlayerId->getHtml();
        $hiddenFeePaid = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: HIDDEN_ROW_FEE_PAID_FIELD_NAME, maxLength: NULL, name: HIDDEN_ROW_FEE_PAID_FIELD_NAME, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: NULL, wrap: NULL, noValidate: false);
        $output .= $hiddenFeePaid->getHtml();
        $hiddenBuyinPaid = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: HIDDEN_ROW_BUYIN_PAID_FIELD_NAME, maxLength: NULL, name: HIDDEN_ROW_BUYIN_PAID_FIELD_NAME, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: NULL, wrap: NULL, noValidate: false);
        $output .= $hiddenBuyinPaid->getHtml();
        $hiddenRebuyPaid = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: HIDDEN_ROW_REBUY_PAID_FIELD_NAME, maxLength: NULL, name: HIDDEN_ROW_REBUY_PAID_FIELD_NAME, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: NULL, wrap: NULL, noValidate: false);
        $output .= $hiddenRebuyPaid->getHtml();
        $hiddenRebuyCount = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: HIDDEN_ROW_REBUY_COUNT_FIELD_NAME, maxLength: NULL, name: HIDDEN_ROW_REBUY_COUNT_FIELD_NAME, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: NULL, wrap: NULL, noValidate: false);
        $output .= $hiddenRebuyCount->getHtml();
        $hiddenAddonPaid = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: HIDDEN_ROW_ADDON_PAID_FIELD_NAME, maxLength: NULL, name: HIDDEN_ROW_ADDON_PAID_FIELD_NAME, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: NULL, wrap: NULL, noValidate: false);
        $output .= $hiddenAddonPaid->getHtml();
        $hiddenRebuyFlag = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: REBUY_FLAG_FIELD_NAME, maxLength: NULL, name: REBUY_FLAG_FIELD_NAME, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: (isset($maxRebuys) ? (string) $maxRebuys : ""), wrap: NULL, noValidate: false);
        $output .= $hiddenRebuyFlag->getHtml();
        $hiddenAddonFlag = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: ADDON_FLAG_FIELD_NAME, maxLength: NULL, name: ADDON_FLAG_FIELD_NAME, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: (isset($addonAmount) ? (string) $addonAmount : ""), wrap: NULL, noValidate: false);
        $output .= $hiddenAddonFlag->getHtml();
        $hiddenSeasonFee = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: SEASON_FEE_FIELD_NAME, maxLength: NULL, name: SEASON_FEE_FIELD_NAME, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_FEE), wrap: NULL, noValidate: false);
        $output .= $hiddenSeasonFee->getHtml();
    } else {
      $output .= "No tournaments available to manage buyins";
    }
    $hiddenMode = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::FIELD_NAME_MODE, maxLength: NULL, name: Constant::FIELD_NAME_MODE, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL,suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: $mode, wrap: NULL, noValidate: false);
    $output .= $hiddenMode->getHtml();
    if ($tournamentId != DEFAULT_VALUE_TOURNAMENT_ID) {
        $result = $entityManager->getRepository(entityName: Constant::ENTITY_PLAYERS)->getStatuses(tournamentId: $tournamentId, indexed: true);
        $resultHeaders = $entityManager->getRepository(entityName: Constant::ENTITY_PLAYERS)->getStatuses(tournamentId: $tournamentId, indexed: false);
        $textBoxFeePaid = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_FEE_PAID, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_FEE_PAID . "_?2", maxLength: 2, name: Constant::TEXT_FEE_PAID . "_?2", import: $import, onClick: NULL, placeholder: NULL, readOnly: false, required: true, rows: NULL, size: 2, suffix: "_?2", type: FormControl::TYPE_INPUT_NUMBER, value: "?6", wrap: NULL, noValidate: false);
        $checkboxBuyinButton = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_BUYIN . "_?2", maxLength: NULL, name: Constant::TEXT_BUYIN . "_?2", import: $import, onClick: "input.changeState({checkId: '" . Base::build(Constant::TEXT_BUYIN, NULL) . "_?2', aryChangeId: new Array(" . (0 == $maxRebuys ? "''" : "'" . Base::build(Constant::TEXT_REBUY, NULL) . "_?2'") . (0 == $addonAmount ? "" : ", '" . Base::build(Constant::TEXT_ADDON, NULL) . "_?2'") . ", '', '')}); document.querySelector('#" . HIDDEN_ROW_BUYIN_PAID_FIELD_NAME . "').value = '?3';", placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: "_?2", type: FormControl::TYPE_INPUT_CHECKBOX, value: "?1", wrap: NULL, noValidate: false);
        $textBoxRebuyCount = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_REBUY_COUNT, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: (0 == $maxRebuys ? true : false), id: Constant::TEXT_REBUY_COUNT . "_?2", maxLength: 2, name: Constant::TEXT_REBUY_COUNT . "_?2", import: $import, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: 2, suffix: "_?2", type: FormControl::TYPE_INPUT_NUMBER, value: "?4", wrap: NULL, noValidate: false);
        $checkboxRebuyButton = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: (0 == $maxRebuys ? true : false), id: Constant::TEXT_REBUY . "_?2", maxLength: NULL, name: Constant::TEXT_REBUY . "_?2", import: $import, onClick: "document.querySelector('#" . HIDDEN_ROW_REBUY_PAID_FIELD_NAME . "').value = '?3';", placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: "_?2", type: FormControl::TYPE_INPUT_CHECKBOX, value: "?1", wrap: NULL, noValidate: false);
        $checkboxAddonButton = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: (0 == $addonAmount ? true : false), id: Constant::TEXT_ADDON . "_?2", maxLength: NULL, name: Constant::TEXT_ADDON . "_?2", import: $import, onClick: "document.querySelector('#" . HIDDEN_ROW_ADDON_PAID_FIELD_NAME . "').value = '?3';", placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: "_?2", type: FormControl::TYPE_INPUT_CHECKBOX, value: "?1", wrap: NULL, noValidate: false);
        $buttons = array(" <span id=\"feePaidOriginal_?2\">?5</span> " . $textBoxFeePaid->getHtml(),"    " . $checkboxBuyinButton->getHtml(), "    " . $checkboxRebuyButton->getHtml() . " " . $textBoxRebuyCount->getHtml(),"    " . $checkboxAddonButton->getHtml());
        $allButtons = $buttons;
        $checkboxBuyinColumnName = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_BUYIN . "CheckAll", maxLength: NULL, name: Constant::TEXT_BUYIN . "CheckAll", onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: Constant::FIELD_NAME_SUFFIX_CHECKBOX_ALL, type: FormControl::TYPE_INPUT_CHECKBOX, value: NULL, wrap: NULL, noValidate: false);
        $checkboxRebuyColumnName = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: (0 == $maxRebuys ? true : false), id: Constant::TEXT_REBUY . "CheckAll", maxLength: NULL, name: Constant::TEXT_REBUY . "CheckAll", onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: Constant::FIELD_NAME_SUFFIX_CHECKBOX_ALL, type: FormControl::TYPE_INPUT_CHECKBOX, value: NULL, wrap: NULL, noValidate: false);
        $checkboxAddonColumnName = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: (0 == $addonAmount ? true : false), id: Constant::TEXT_ADDON . "CheckAll", maxLength: NULL, name: Constant::TEXT_ADDON . "CheckAll", onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: Constant::FIELD_NAME_SUFFIX_CHECKBOX_ALL, type: FormControl::TYPE_INPUT_CHECKBOX, value: NULL, wrap: NULL, noValidate: false);
        $columnNames = array(Constant::TEXT_FEE_PAID_DISPLAY,Constant::TEXT_BUYIN . "<br />" . $checkboxBuyinColumnName->getHtml(),Constant::TEXT_REBUY . "<br />" . $checkboxRebuyColumnName->getHtml(), Constant::TEXT_ADDON . "<br />" . $checkboxAddonColumnName->getHtml());
        $allColNames = $columnNames;
        // 0=id, 1=name, 2=buyin status, 3=rebuy status, 4=rebuy count, 5=addon status
        // 0=id, 1=name, 2=fee status, 3=fee status display, 4=amount, 5=amount2, 6=buyin status, 7=rebuy status, 8=rebuy count, 9=addon status
        $colIndexes = array(2,6,7,9); // 2,3,5
        $allIndexes = $colIndexes;
        // 0 is html, 1 is headers, 2 is index, 3 is status name/button text, 4 is player/rebuy count/fee paid/fee paid for tournament index
        $html = array($allButtons,$allColNames,$allIndexes,array(array(Constant::NAME_STATUS_PAID,"Not paid"),array(Constant::NAME_STATUS_NOT_PAID,"Paid")),array(0,8,4,5)); // 0,4
        $hideColIndexes = array(0,2,4,5,6,7,8,9); // 0,2,3,4,5
        $output .= "</div>\n";
        $htmlTable = new HtmlTable(caption: NULL, class: NULL, colspan: NULL, columnFormat: NULL, debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), delimiter: Constant::DELIMITER_DEFAULT, foreignKeys: NULL, header: true, hiddenAdditional: NULL, hiddenId: HIDDEN_ROW_FIELD_NAME, hideColumnIndexes: $hideColIndexes, html: $html, id: NULL, link: NULL, note: true, selectedRow: NULL, suffix: NULL, width: "100%");
        $temp = $htmlTable->getHtml(results: $result, resultHeaders: $resultHeaders);
        if ("" != $temp) {
            $output .= " <div class=\"buttons center\">\n";
            $buttonSave = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_SAVE, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_SAVE . "_2", maxLength: NULL, name: Constant::TEXT_SAVE . "_2", import: $import, onClick: "inputLocal.buildData({objTableId: '" . Constant::ID_TABLE_DATA . "', mode: '" . Constant::MODE_MODIFY . "'});", placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_SAVE, wrap: NULL, noValidate: false);
            $output .= $buttonSave->getHtml() . "</div>\n";
            $output .= $temp;
            $output .= " <div class=\"buttons center\">\n";
            $buttonSave = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_SAVE, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_SAVE, maxLength: NULL, name: Constant::TEXT_SAVE, import: $import, onClick: "inputLocal.buildData({objTableId: '" . Constant::ID_TABLE_DATA . "', mode: '" . Constant::MODE_MODIFY . "'});", placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_SAVE, wrap: NULL, noValidate: false);
            $output .= $buttonSave->getHtml() . "</div>\n";
        }
        if ("" != $temp) {
            $output .= "<div class=\"responsive responsive--3cols responsive--collapse\">\n";
            $resultList = $entityManager->getRepository(entityName: Constant::ENTITY_FEES)->getAmountForTournament(tournamentId: $tournamentId);
            $feeCalc = " (";
            $feeTotal = 0;
            $feePlayerIds = "";
            foreach ($resultList as $row) {
                $feePlayerIds .= ($feeTotal == 0 ? "" : ",") . $row["player_id"];
                $feeCalc .= ($feeTotal == 0 ? "$" : " + $") . $row["fee_amount"];
                $feeTotal += $row["fee_amount"];
            }
            $feeCalc .= ")";
            $hiddenPlayerSeasonFee = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: PLAYER_SEASON_FEE_FIELD_NAME, maxLength: NULL, name: PLAYER_SEASON_FEE_FIELD_NAME, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: $feePlayerIds, wrap: NULL, noValidate: false);
            $output .= $hiddenPlayerSeasonFee->getHtml();
            $output .= " <div class=\"responsive-cell responsive-cell-label-footer responsive-cell--head\">Total fees:</div>\n";
            $output .= " <div class=\"responsive-cell responsive-cell-value-footer negative\" id=\"feePaidTotal\">$" . $feeTotal . "</div>\n";
            $hiddenTotalSeasonFee = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: TOTAL_SEASON_FEE_FIELD_NAME, maxLength: NULL, name: TOTAL_SEASON_FEE_FIELD_NAME, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: 0, wrap: NULL, noValidate: false);
            $output .= $hiddenTotalSeasonFee->getHtml();
            $output .= " <div class=\"responsive-cell responsive-cell-value-after-footer\" id=\"feePaidTotalCalculation\">" . $feeCalc . "</div>\n";
            if ($championshipFlag[$tournamentId]) {
                $output .= " <div class=\"responsive-cell responsive-cell-label-footer responsive-cell--head\">Total buyins:</div>\n";
                $output .= " <div class=\"responsive-cell responsive-cell-value-footer\">" . $totalBuyin[$tournamentId][0] . "</div>\n";
            } else {
                $output .= " <div class=\"responsive-cell responsive-cell-label-footer responsive-cell--head\">Total buyins:</div>\n";
                $totalBuyins = $totalBuyin[$tournamentId][0] * $totalBuyin[$tournamentId][1];
                $output .= " <div class=\"responsive-cell responsive-cell-value-footer negative\">$" . $totalBuyins . "</div>\n";
                $output .= " <div class=\"responsive-cell responsive-cell-value-after-footer\">(" . $totalBuyin[$tournamentId][0] . " x $" . $totalBuyin[$tournamentId][1] . ")</div>\n";
                $output .= " <div class=\"responsive-cell responsive-cell-label-footer responsive-cell--head\">Total rebuys:</div>\n";
                $totalRebuys = $totalRebuy[$tournamentId][1] * $totalRebuy[$tournamentId][2];
                $output .= " <div class=\"responsive-cell responsive-cell-value-footer negative\">$" . $totalRebuys . "</div>\n";
                $output .= " <div class=\"responsive-cell responsive-cell-value-after-footer\">(" . $totalRebuy[$tournamentId][1] . " x $" . $totalRebuy[$tournamentId][2] . ")</div>\n";
                $output .= " <div class=\"responsive-cell responsive-cell-label-footer responsive-cell--head\">Total addons:</div>\n";
                $totalAddons = $totalAddon[$tournamentId][0] * $totalAddon[$tournamentId][1];
                $output .= " <div class=\"responsive-cell responsive-cell-value-footer negative\">$" . $totalAddons . "</div>\n";
                $output .= " <div class=\"responsive-cell responsive-cell-value-after-footer\">(" . $totalAddon[$tournamentId][0] . " x $" . $totalAddon[$tournamentId][1] . ")</div>\n";
                $output .= " <div class=\"responsive-cell responsive-cell-label-footer responsive-cell--head\">Total paid in:</div>\n";
                $output .= " <div class=\"responsive-cell responsive-cell-value-footer negative\">$" . $total[$tournamentId] . "</div>\n";
                $output .= " <div class=\"responsive-cell responsive-cell-value-after-footer\">($" . $totalBuyins . " + $" . $totalRebuys . " + $" . $totalAddons . ")</div>\n";
                $output .= " <div class=\"responsive-cell responsive-cell-label-footer responsive-cell--head\">Total rake (" . ($rakePercent[$tournamentId] * 100) . "%):</div>\n";
                $output .= " <div class=\"responsive-cell responsive-cell-value-footer negative\">$" . $rake[$tournamentId] . "</div>\n";
                $output .= " <div class=\"responsive-cell responsive-cell-value-after-footer\">(" . ($rakePercent[$tournamentId] * 100) . "% x $" . $total[$tournamentId] . ")</div>\n";
            }
            $output .= " <div class=\"responsive-cell responsive-cell-label-footer responsive-cell--head\">Total paid out:</div>\n";
            $output .= " <div class=\"responsive-cell responsive-cell-value-footer positive\">$" . ceil(num: ($total[$tournamentId]) - ($rake[$tournamentId])) . "</div>\n";
            if (!$championshipFlag[$tournamentId]) {
                $output .= " <div class=\"responsive-cell responsive-cell-value-after-footer\">($" . ($total[$tournamentId]) . " - $" . ($rake[$tournamentId]) . ")</div>\n";
            }
            $resultList = $entityManager->getRepository(entityName: Constant::ENTITY_PLAYERS)->getStatuses(tournamentId: $tournamentId, indexed: false);
            $countPaid = 0;
            if (count(value: $resultList) > 0) {
                $ctr = 0;
                while ($ctr < count(value: $resultList)) {
                    if ($resultList[$ctr]["buyin_status"] == "Paid") {
                        $countPaid++;
                    }
                  $ctr++;
                }
            }
            $structures = "";
            foreach ($payouts[$tournamentId] as $payout) {
                if ($countPaid >= $payout->getMinPlayers() && $countPaid <= $payout->getMaxPlayers()) {
                    $structures = $payout->getStructures();
                    break;
                }
            }
            if ($structures != "") {
                foreach ($structures as $structure) {
                    $output .= " <div class=\"responsive-cell responsive-cell-label-footer responsive-cell--head\">Place " . $structure->getStructurePlace() . " (" . ($structure->getStructurePercentage() * 100) . "%):</div>\n";
                    $output .= " <div class=\"responsive-cell responsive-cell-value-footer positive\">$" . number_format((($total[$tournamentId] - $rake[$tournamentId]) * $structure->getStructurePercentage()), 0, ".", "") . "</div>\n";
                    $output .= " <div class=\"responsive-cell responsive-cell-value-after-footer\">(" . ($structure->getStructurePercentage() * 100) . "% x $" . ($total[$tournamentId] - $rake[$tournamentId]) . ")</div>\n";
                    $ctr++;
                }
            }
        }
        $output .= "</div>\n";
    } else {
        if (DEFAULT_VALUE_TOURNAMENT_ID != $tournamentId) {
            $output .= "No tournaments available to manage buyins";
        }
    }
}
$smarty->assign("content", $output);
$smarty->display("manage.tpl");
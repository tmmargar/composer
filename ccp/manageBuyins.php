<?php
declare(strict_types = 1);
namespace Poker\Ccp;
use Poker\Ccp\classes\model\Base;
use Poker\Ccp\classes\model\Constant;
use Poker\Ccp\classes\model\DatabaseResult;
use Poker\Ccp\classes\model\FormControl;
use Poker\Ccp\classes\model\FormOption;
use Poker\Ccp\classes\model\FormSelect;
use Poker\Ccp\classes\model\HtmlTable;
use Poker\Ccp\classes\utility\SessionUtility;
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
$style = "<style type=\"text/css\">\n" . ".label {\n" . "  float: left;\n" . "  width: 150px;\n" . "  text-align: right;\n" . "}\n" . ".value {\n" . "  float: left;\n" . "  text-align: right;\n" .
  "  width: 50px;\n" . "}\n" . ".valueAfter {\n" . "  float: left;\n" . "  padding-left: 5px;\n" . "  text-align: right;\n" . "  width: 150px;\n" . "}\n" . "p {\n" . "  margin: 0;\n" .
  "  padding: 0;\n" . "}\n" . "</style>\n";
$smarty->assign("title", "Manage Buyins");
$smarty->assign("heading", "Manage Buyins");
$smarty->assign("style", "<link href=\"css/manageBuyins.css\" rel=\"stylesheet\">");
$import = "  import { inputLocal } from \"./scripts/manageBuyins.js\";\n";
$tournamentId = isset($_POST[TOURNAMENT_ID_FIELD_NAME]) ? $_POST[TOURNAMENT_ID_FIELD_NAME] : DEFAULT_VALUE_TOURNAMENT_ID;
if (Constant::MODE_SAVE_CREATE == $mode || Constant::MODE_SAVE_MODIFY == $mode) {
  $aryPlayers = explode(Constant::DELIMITER_DEFAULT, $_POST[SELECTED_ROWS_FIELD_NAME]);
  $aryFees = explode(Constant::DELIMITER_DEFAULT, $_POST[HIDDEN_ROW_FEE_PAID_FIELD_NAME]);
  $aryBuyins = explode(Constant::DELIMITER_DEFAULT, $_POST[HIDDEN_ROW_BUYIN_PAID_FIELD_NAME]);
  $aryRebuys = explode(Constant::DELIMITER_DEFAULT, $_POST[HIDDEN_ROW_REBUY_PAID_FIELD_NAME]);
  $aryRebuyCounts = explode(Constant::DELIMITER_DEFAULT, $_POST[HIDDEN_ROW_REBUY_COUNT_FIELD_NAME]);
  $aryAddons = explode(Constant::DELIMITER_DEFAULT, $_POST[HIDDEN_ROW_ADDON_PAID_FIELD_NAME]);
  $output .= "<script type=\"module\">\n" . "  import { dataTable, display, input } from \"./scripts/import.js\";\n" . "  let aryErrors = [];\n";
  $params = array($tournamentId);
  $resultList = $databaseResult->getSeasonByTournamentId(params: $params);
  if (count($resultList) > 0) {
    $seasonId = $resultList[0];
  }
  for ($index = 0; $index < count($aryPlayers); $index++) {
    if (" " != $aryFees[$index]) {
      $params = array($aryFees[$index],$seasonId,$aryPlayers[$index],$tournamentId);
      $rowCount = $databaseResult->updateFees(params: $params);
      if (0 == $rowCount) {
        $params = array($seasonId,$aryPlayers[$index]);
        $rowCount = $databaseResult->deleteFeeBySeasonAndPlayer(params: $params);
        $params = array($seasonId,$aryPlayers[$index],$tournamentId,$aryFees[$index]);
        $rowCount = $databaseResult->insertFee(params: $params);
      }
      if (!is_numeric($rowCount)) {
        $output .= "  aryErrors.push(\"" . $rowCount . "\");\n";
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
      $rebuyCount = $aryRebuyCounts[$index];
    } else {
      $rebuyPaid = Constant::FLAG_NO;
      $rebuyCount = 0;
    }
    if ($aryAddons[$index] == Constant::TEXT_TRUE) {
      $addonPaid = Constant::FLAG_YES;
    } else {
      $addonPaid = Constant::FLAG_NO;
    }
    $params = array($statusCode,$buyinPaid,$rebuyPaid,$addonPaid,$rebuyCount,$tournamentId,$aryPlayers[$index]);
    $rowCount = $databaseResult->updateBuyins(params: $params);
    if (!is_numeric($rowCount)) {
      $output .= "  aryErrors.push(\"" . $rowCount . "\");\n";
    }
  }
  $output .= "  if (aryErrors.length > 0) {display.showErrors({errors: aryErrors});}\n</script>\n";
  $mode = Constant::MODE_VIEW;
}
if ($mode == Constant::MODE_VIEW) {
  $params = array("CURRENT_DATE","DATE_ADD(t.tournamentDate, INTERVAL 28 DAY)");
  $paramsNested = array(SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_START_DATE)->getDatabaseFormat(),
    SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_END_DATE)->getDatabaseFormat(),SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_CHAMPIONSHIP_QUALIFY));
  $resultList = $databaseResult->getTournamentForBuyins(params: $params, paramsNested: $paramsNested);
  if (count($resultList) > 0) {
    $output .= "<div class=\"responsive responsive--3cols responsive--collapse\">";
    $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . TOURNAMENT_ID_FIELD_NAME . "\">" . TOURNAMENT_ID_FIELD_LABEL . ": </label></div>\n";
    // $debug, $accessKey, $class, $disabled, $id, $multiple, $name, $onClick, $readOnly, $size, $suffix, $value
    $selectTournament = new FormSelect(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_TOURNAMENT_ID, class: NULL, disabled: false,
      id: TOURNAMENT_ID_FIELD_NAME, multiple: false, name: TOURNAMENT_ID_FIELD_NAME, onClick: NULL, readOnly: false, size: 1, suffix: NULL, value: NULL);
    $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $selectTournament->getHtml();
    // $debug, $class, $disabled, $id, $name, $selectedValue, $suffix, $text, $value) {
    $option = new FormOption(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), class: NULL, disabled: false, id: NULL, name: NULL,
      selectedValue: !isset($tournamentId) ? DEFAULT_VALUE_TOURNAMENT_ID : "", suffix: NULL, text: Constant::TEXT_NONE, value: DEFAULT_VALUE_TOURNAMENT_ID);
    $output .= $option->getHtml();
    $cnt = 0;
    while ($cnt < count($resultList)) {
      $tournament = $resultList[$cnt];
      $option = new FormOption(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), class: NULL, disabled: false, id: NULL, name: NULL, selectedValue: $tournamentId, suffix: NULL,
        text: $tournament->getDisplayDetails(), value: $tournament->getId());
      $output .= $option->getHtml();
      $cnt++;
      if ($tournamentId == $tournament->getId()) {
        $maxRebuys = $tournament->getMaxRebuys();
        $addonAmount = $tournament->getAddonAmount();
      }
      $totalBuyin[$tournament->getId()] = array($tournament->getBuyinsPaid(),-$tournament->getBuyinAmount());
      $totalRebuy[$tournament->getId()] = array($tournament->getRebuysPaid(),$tournament->getRebuysCount(),-$tournament->getRebuyAmount());
      $totalAddon[$tournament->getId()] = array($tournament->getAddonsPaid(),-$tournament->getAddonAmount());
      if (strpos($tournament->getDescription(), "Championship") === false) {
        $championshipFlag[$tournament->getId()] = false;
        $total[$tournament->getId()] = ($totalBuyin[$tournament->getId()][0] * $totalBuyin[$tournament->getId()][1]) + ($totalRebuy[$tournament->getId()][1] * $totalRebuy[$tournament->getId()][2]) +
          ($totalAddon[$tournament->getId()][0] * $totalAddon[$tournament->getId()][1]);
      } else {
        $championshipFlag[$tournament->getId()] = true;
        $params = array(SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_START_DATE)->getDatabaseFormat(),SessionUtility::getValue(SessionUtility::OBJECT_NAME_END_DATE)->getDatabaseFormat());
        $resultListNested = $databaseResult->getPrizePoolForSeason(params: $params, returnQuery: false);
        if (0 < count($resultListNested)) {
          $total[$tournament->getId()] = str_replace(search: ",", replace: "", subject: number_format((float) $resultListNested[0], 0));
        }
      }
      $rake[$tournament->getId()] = ceil($total[$tournament->getId()] * $tournament->getRakeForCalculation());
      $rakePercent[$tournament->getId()] = $tournament->getRakeForCalculation();
      $tournamentPayouts = $tournament->getGroupPayout()->getPayouts();
      $payouts[$tournament->getId()] = $tournamentPayouts;
    }
    $output .= "   </select>\n";
    $output .= "  </div>\n";
    $output .= " <div class=\"responsive-cell responsive-cell-button\">\n";
    // ($debug, $accessKey, $autoComplete, $autoFocus, $checked, $class, $cols, $disabled, $id, $maxLength, $name, $onClick, $placeholder, $readOnly, $required, $rows, $size, $suffix, $type, $value, $wrap
    $buttonView = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_VIEW, autoComplete: NULL, autoFocus: false, checked: NULL,
      class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_VIEW, maxLength: NULL, name: Constant::TEXT_VIEW, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL,
      size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_VIEW, wrap: NULL, import: NULL, noValidate: true);
    $output .= $buttonView->getHtml();
    $output .= " </div>\n";
    $hiddenSelectedRowsPlayerId = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL,
      cols: NULL, disabled: false, id: SELECTED_ROWS_FIELD_NAME, maxLength: NULL, name: SELECTED_ROWS_FIELD_NAME, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL,
      size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: NULL, wrap: NULL);
    $output .= $hiddenSelectedRowsPlayerId->getHtml();
    $hiddenFeePaid = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL,
      disabled: false, id: HIDDEN_ROW_FEE_PAID_FIELD_NAME, maxLength: NULL, name: HIDDEN_ROW_FEE_PAID_FIELD_NAME, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL,
      size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: NULL, wrap: NULL);
    $output .= $hiddenFeePaid->getHtml();
    $hiddenBuyinPaid = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL,
      disabled: false, id: HIDDEN_ROW_BUYIN_PAID_FIELD_NAME, maxLength: NULL, name: HIDDEN_ROW_BUYIN_PAID_FIELD_NAME, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL,
      size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: NULL, wrap: NULL);
    $output .= $hiddenBuyinPaid->getHtml();
    $hiddenRebuyPaid = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL,
      disabled: false, id: HIDDEN_ROW_REBUY_PAID_FIELD_NAME, maxLength: NULL, name: HIDDEN_ROW_REBUY_PAID_FIELD_NAME, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL,
      size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: NULL, wrap: NULL);
    $output .= $hiddenRebuyPaid->getHtml();
    $hiddenRebuyCount = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL,
      cols: NULL, disabled: false, id: HIDDEN_ROW_REBUY_COUNT_FIELD_NAME, maxLength: NULL, name: HIDDEN_ROW_REBUY_COUNT_FIELD_NAME, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL,
      rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: NULL, wrap: NULL);
    $output .= $hiddenRebuyCount->getHtml();
    $hiddenAddonPaid = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL,
      disabled: false, id: HIDDEN_ROW_ADDON_PAID_FIELD_NAME, maxLength: NULL, name: HIDDEN_ROW_ADDON_PAID_FIELD_NAME, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL,
      size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: NULL, wrap: NULL);
    $output .= $hiddenAddonPaid->getHtml();
    $hiddenRebuyFlag = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL,
      disabled: false, id: REBUY_FLAG_FIELD_NAME, maxLength: NULL, name: REBUY_FLAG_FIELD_NAME, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL,
      type: FormControl::TYPE_INPUT_HIDDEN, value: (isset($maxRebuys) ? (string) $maxRebuys : ""), wrap: NULL);
    $output .= $hiddenRebuyFlag->getHtml();
    $hiddenAddonFlag = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL,
      disabled: false, id: ADDON_FLAG_FIELD_NAME, maxLength: NULL, name: ADDON_FLAG_FIELD_NAME, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL,
      type: FormControl::TYPE_INPUT_HIDDEN, value: (isset($addonAmount) ? (string) $addonAmount : ""), wrap: NULL);
    $output .= $hiddenAddonFlag->getHtml();
    $hiddenSeasonFee = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL,
      disabled: false, id: SEASON_FEE_FIELD_NAME, maxLength: NULL, name: SEASON_FEE_FIELD_NAME, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL,
      type: FormControl::TYPE_INPUT_HIDDEN, value: SessionUtility::getValue(SessionUtility::OBJECT_NAME_FEE), wrap: NULL);
    $output .= $hiddenSeasonFee->getHtml();
  } else {
    $output .= "No tournaments available to manage buyins";
  }
  $hiddenMode = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL,
    disabled: false, id: Constant::FIELD_NAME_MODE, maxLength: NULL, name: Constant::FIELD_NAME_MODE, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL,
    suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: $mode, wrap: NULL);
  $output .= $hiddenMode->getHtml();
  if ($tournamentId != DEFAULT_VALUE_TOURNAMENT_ID) {
    $params = array($tournamentId);
    $pdoStatementAndQuery = $databaseResult->getStatusPaid(params: $params, returnQuery: true);
    $pdoStatement = $pdoStatementAndQuery[0];
    $query = $pdoStatementAndQuery[1];
    $textBoxFeePaid = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_FEE_PAID, autoComplete: NULL, autoFocus: false, checked: NULL,
      class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_FEE_PAID . "_?2", maxLength: 2, name: Constant::TEXT_FEE_PAID . "_?2", import: $import, onClick: NULL, placeholder: NULL,
      readOnly: false, required: true, rows: NULL, size: 2, suffix: "_?2", type: FormControl::TYPE_INPUT_NUMBER, value: "?6", wrap: NULL);
    $checkboxBuyinButton = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL,
      cols: NULL, disabled: false, id: Constant::TEXT_BUYIN . "_?2", maxLength: NULL, name: Constant::TEXT_BUYIN . "_?2", import: $import,
      onClick: "input.changeState({checkId: '" . Base::build(Constant::TEXT_BUYIN, NULL) . "_?2', aryChangeId: new Array(" .
      (0 == $maxRebuys ? "''" : "'" . Base::build(Constant::TEXT_REBUY, NULL) . "_?2'") . (0 == $addonAmount ? "" : ", '" . Base::build(Constant::TEXT_ADDON, NULL) . "_?2'") .
      ", '', '')}); document.querySelector('#" . HIDDEN_ROW_BUYIN_PAID_FIELD_NAME . "').value = '?3';", placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: "_?2",
      type: FormControl::TYPE_INPUT_CHECKBOX, value: "?1", wrap: NULL);
    $textBoxRebuyCount = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_REBUY_COUNT, autoComplete: NULL, autoFocus: false,
      checked: NULL, class: NULL, cols: NULL, disabled: (0 == $maxRebuys ? true : false), id: Constant::TEXT_REBUY_COUNT . "_?2", maxLength: 2, name: Constant::TEXT_REBUY_COUNT . "_?2",
      import: $import, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: 2, suffix: "_?2", type: FormControl::TYPE_INPUT_NUMBER, value: "?4", wrap: NULL);
    $checkboxRebuyButton = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL,
      cols: NULL, disabled: (0 == $maxRebuys ? true : false), id: Constant::TEXT_REBUY . "_?2", maxLength: NULL, name: Constant::TEXT_REBUY . "_?2", import: $import,
      onClick: "document.querySelector('#" . HIDDEN_ROW_REBUY_PAID_FIELD_NAME . "').value = '?3';", placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: "_?2",
      type: FormControl::TYPE_INPUT_CHECKBOX, value: "?1", wrap: NULL);
    $checkboxAddonButton = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL,
      cols: NULL, disabled: (0 == $addonAmount ? true : false), id: Constant::TEXT_ADDON . "_?2", maxLength: NULL, name: Constant::TEXT_ADDON . "_?2", import: $import,
      onClick: "document.querySelector('#" . HIDDEN_ROW_ADDON_PAID_FIELD_NAME . "').value = '?3';", placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: "_?2",
      type: FormControl::TYPE_INPUT_CHECKBOX, value: "?1", wrap: NULL);
    $buttons = array(" <span id=\"feePaidOriginal_?2\">?5</span> " . $textBoxFeePaid->getHtml(),"    " . $checkboxBuyinButton->getHtml(),
      "    " . $checkboxRebuyButton->getHtml() . " " . $textBoxRebuyCount->getHtml(),"    " . $checkboxAddonButton->getHtml());
    $allButtons = $buttons;
    $checkboxBuyinColumnName = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL,
      cols: NULL, disabled: false, id: Constant::TEXT_BUYIN . "CheckAll", maxLength: NULL, name: Constant::TEXT_BUYIN . "CheckAll", onClick: NULL, placeholder: NULL, readOnly: false, required: NULL,
      rows: NULL, size: NULL, suffix: Constant::FIELD_NAME_SUFFIX_CHECKBOX_ALL, type: FormControl::TYPE_INPUT_CHECKBOX, value: NULL, wrap: NULL);
    $checkboxRebuyColumnName = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL,
      cols: NULL, disabled: (0 == $maxRebuys ? true : false), id: Constant::TEXT_REBUY . "CheckAll", maxLength: NULL, name: Constant::TEXT_REBUY . "CheckAll", onClick: NULL, placeholder: NULL,
      readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: Constant::FIELD_NAME_SUFFIX_CHECKBOX_ALL, type: FormControl::TYPE_INPUT_CHECKBOX, value: NULL, wrap: NULL);
    $checkboxAddonColumnName = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL,
      cols: NULL, disabled: (0 == $addonAmount ? true : false), id: Constant::TEXT_ADDON . "CheckAll", maxLength: NULL, name: Constant::TEXT_ADDON . "CheckAll", onClick: NULL, placeholder: NULL,
      readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: Constant::FIELD_NAME_SUFFIX_CHECKBOX_ALL, type: FormControl::TYPE_INPUT_CHECKBOX, value: NULL, wrap: NULL);
    $columnNames = array(Constant::TEXT_FEE_PAID_DISPLAY,Constant::TEXT_BUYIN . "<br />" . $checkboxBuyinColumnName->getHtml(),Constant::TEXT_REBUY . "<br />" . $checkboxRebuyColumnName->getHtml(),
      Constant::TEXT_ADDON . "<br />" . $checkboxAddonColumnName->getHtml());
    $allColNames = $columnNames;
    // 0=id, 1=name, 2=buyin status, 3=rebuy status, 4=rebuy count, 5=addon status
    // 0=id, 1=name, 2=fee status, 3=fee status display, 4=amount, 5=amount2, 6=buyin status, 7=rebuy status, 8=rebuy count, 9=addon status
    $colIndexes = array(2,6,7,9); // 2,3,5
    $allIndexes = $colIndexes;
    // 0 is html, 1 is headers, 2 is index, 3 is status name/button text, 4 is player/rebuy count/fee paid/fee paid for tournament index
    $html = array($allButtons,$allColNames,$allIndexes,array(array(Constant::NAME_STATUS_PAID,"Not paid"),array(Constant::NAME_STATUS_NOT_PAID,"Paid")),array(0,8,4,5)); // 0,4
    $hideColIndexes = array(0,2,4,5,6,7,8,9); // 0,2,3,4,5
    $output .= "</div>\n";
    // $caption, $class, $colspan, $columnFormat, $debug, $delimiter, $foreignKeys, $header, $hiddenAdditional, $hiddenId, $hideColumnIndexes, $html, $id, $link, $note, $query, $selectedRow, $suffix, $width
    $htmlTable = new HtmlTable(caption: NULL, class: NULL, colspan: NULL, columnFormat: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), delimiter: Constant::DELIMITER_DEFAULT, foreignKeys: NULL, header: true, hiddenAdditional: NULL, hiddenId: HIDDEN_ROW_FIELD_NAME, hideColumnIndexes: $hideColIndexes, html: $html, id: NULL, link: NULL, note: true, pdoStatement: $pdoStatement, query: $query, selectedRow: NULL, suffix: NULL, width: "100%");
    $temp = $htmlTable->getHtml();
    if ("" != $temp) {
      $output .= " <div class=\"buttons center\">\n";
      $buttonSave = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_SAVE, autoComplete: NULL, autoFocus: false, checked: NULL,
        class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_SAVE . "_2", maxLength: NULL, name: Constant::TEXT_SAVE . "_2", import: $import,
        onClick: "inputLocal.buildData({objTableId: '" . Constant::ID_TABLE_DATA . "', mode: '" . Constant::MODE_MODIFY . "'});", placeholder: NULL, readOnly: false, required: NULL, rows: NULL,
        size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_SAVE, wrap: NULL);
      $output .= $buttonSave->getHtml() . "</div>\n";
      $output .= $temp;
      $output .= " <div class=\"buttons center\">\n";
      $buttonSave = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_SAVE, autoComplete: NULL, autoFocus: false, checked: NULL,
        class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_SAVE, maxLength: NULL, name: Constant::TEXT_SAVE, import: $import,
        onClick: "inputLocal.buildData({objTableId: '" . Constant::ID_TABLE_DATA . "', mode: '" . Constant::MODE_MODIFY . "'});", placeholder: NULL, readOnly: false, required: NULL, rows: NULL,
        size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_SAVE, wrap: NULL);
      $output .= $buttonSave->getHtml() . "</div>\n";
    }
    if ("" != $temp) {
      $output .= "<div class=\"responsive responsive--3cols responsive--collapse\">";
      $output .= " <div class=\"responsive-cell responsive-cell-label-footer responsive-cell--head\">Total fees:</div>\n";
      $output .= " <div class=\"responsive-cell responsive-cell-value-footer negative\" id=\"feePaidTotal\">$0</div>\n";
      $hiddenTotalSeasonFee = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL,
        cols: NULL, disabled: false, id: TOTAL_SEASON_FEE_FIELD_NAME, maxLength: NULL, name: TOTAL_SEASON_FEE_FIELD_NAME, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL,
        size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: 0, wrap: NULL);
      $output .= $hiddenTotalSeasonFee->getHtml();
      $output .= " <div class=\"responsive-cell responsive-cell-value-after-footer\" id=\"feePaidTotalCalculation\"></div>\n";
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
      $output .= " <div class=\"responsive-cell responsive-cell-value-footer positive\">$" . ceil(($total[$tournamentId]) - ($rake[$tournamentId])) . "</div>\n";
      if (!$championshipFlag[$tournamentId]) {
        $output .= " <div class=\"responsive-cell responsive-cell-value-after-footer\">($" . ($total[$tournamentId]) . " - $" . ($rake[$tournamentId]) . ")</div>\n";
      }
      $resultList = $databaseResult->getStatusPaid(params: $params, returnQuery: false);
      $countPaid = 0;
      if (count($resultList) > 0) {
        $ctr = 0;
        while ($ctr < count($resultList)) {
          if ($resultList[$ctr][6] == "Paid") {
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
          $output .= " <div class=\"responsive-cell responsive-cell-label-footer responsive-cell--head\">Place " . $structure->getPlace() . " (" . ($structure->getPercentage() * 100) . "%):</div>\n";
          $output .= " <div class=\"responsive-cell responsive-cell-value-footer positive\">$" .
            number_format((($total[$tournamentId] - $rake[$tournamentId]) * $structure->getPercentage()), 0, ".", "") . "</div>\n";
          $output .= " <div class=\"responsive-cell responsive-cell-value-after-footer\">(" . ($structure->getPercentage() * 100) . "% x $" . ($total[$tournamentId] - $rake[$tournamentId]) .
            ")</div>\n";
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
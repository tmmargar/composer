<?php
declare(strict_types = 1);
namespace ccp;
use Poker\Ccp\classes\model\Constant;
use Poker\Ccp\classes\model\FormControl;
use Poker\Ccp\classes\model\FormOption;
use Poker\Ccp\classes\model\FormSelect;
use Poker\Ccp\classes\model\HtmlTable;
use Poker\Ccp\classes\utility\SessionUtility;
require_once "init.php";
define("TOURNAMENT_FIELD_LABEL", "Tournament");
define("TOURNAMENT_ID_FIELD_NAME", "tournamentId");
define("SELECTED_ROWS_TOURNAMENT_PLAYER_STATUS_FIELD_NAME", "tournamentPlayerStatus");
define("SELECT_COLUMN_PREFIX_FIELD_NAME", "select");
define("TOURNAMENT_PLAYER_ID_FIELD_NAME", "tournamentPlayerId");
define("DEFAULT_VALUE_TOURNAMENT_ID", "-1");
define("ATTENDING_TEXT", "Attending");
define("UNABLE_TO_ATTEND_TEXT", "Unable to attend");
$smarty->assign("title", "Manage Tournament Absence");
$smarty->assign("heading", "Manage Tournament Absence");
$smarty->assign("style", "<link href=\"css/manageTournamentAbsence.css\" rel=\"stylesheet\">");
$tournamentId = (int) (isset($_POST[TOURNAMENT_ID_FIELD_NAME]) ? $_POST[TOURNAMENT_ID_FIELD_NAME] : DEFAULT_VALUE_TOURNAMENT_ID);
$tournamentPlayerStatus = isset($_POST[SELECTED_ROWS_TOURNAMENT_PLAYER_STATUS_FIELD_NAME]) ? $tournamentPlayerStatus = $_POST[SELECTED_ROWS_TOURNAMENT_PLAYER_STATUS_FIELD_NAME] : DEFAULT_VALUE_BLANK;
if (Constant::MODE_CREATE == $mode || Constant::MODE_MODIFY == $mode) {
  $ary = explode(Constant::DELIMITER_DEFAULT, $ids);
  $aryStatus = explode(Constant::DELIMITER_DEFAULT, $tournamentPlayerStatus);
  $runOnce = true;
  // get number of registered to determine max number of wait list to process
  $valuesCount = array_count_values($aryStatus);
  $numRows = array_key_exists(Constant::NAME_STATUS_REGISTERED, $valuesCount) ? $valuesCount[Constant::NAME_STATUS_REGISTERED] : 0;
  $output .=
    "<script type=\"module\">\n" .
    "  import { dataTable, display, input } from \"./scripts/import.js\";\n" .
    "  let aryMessages = [];\n";
  foreach ($ary as $index => $id) {
    $params = array($id);
    $resultList = $databaseResult->getPlayerById(params: $params);
    if (count($resultList) > 0) {
      $cnt = 0;
      $userId = (int) $resultList[0]->getId();
      $userName = $resultList[0]->getName();
    }
    if ($aryStatus[$index] == ATTENDING_TEXT) {
      $params = array($tournamentId, $userId);
      $rowCount = $databaseResult->insertTournamentAbsence(params: $params);
      if (!is_numeric($rowCount)) {
        $output .= "  aryMessages.push(\"" . $rowCount . "\");\n";
      }
    } else {
      $params = array($tournamentId, $userId);
      $rowCount = $databaseResult->deleteTournamentAbsence(params: $params);
      if (!is_numeric($rowCount)) {
        $output .= "  aryMessages.push(\"" . $rowCount . "\");\n";
      }
    }
  }
  $output .= "  if (aryMessages.length > 0) {display.showMessages({messages: aryMessages});}\n</script>\n";
  $ids = DEFAULT_VALUE_BLANK;
  $mode = Constant::MODE_VIEW;
}
if ($mode == Constant::MODE_VIEW) {
  $output .= "<div class=\"responsive responsive--3cols responsive--collapse\">";
  $params = array();
  $paramsNested = array(SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_START_DATE)->getDatabaseFormat(), SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_END_DATE)->getDatabaseFormat(), SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_CHAMPIONSHIP_QUALIFY));
  $resultList = $databaseResult->getTournamentForChampionship(params: $params, paramsNested: $paramsNested);
  if (count($resultList) > 0) {
    $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . TOURNAMENT_ID_FIELD_NAME . "\">" . TOURNAMENT_FIELD_LABEL . ": </label></div>\n";
    //     $debug, $accessKey, $class, $disabled, $id, $multiple, $name, $onClick, $readOnly, $size, $suffix, $value
    $selectTournament = new FormSelect(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_TOURNAMENT_ID, class: NULL, disabled: false, id: TOURNAMENT_ID_FIELD_NAME, multiple: false, name: TOURNAMENT_ID_FIELD_NAME, onClick: NULL, readOnly: false, size: 1, suffix: NULL, value: NULL);
    $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $selectTournament->getHtml();
    //     $debug, $class, $disabled, $id, $name, $selectedValue, $suffix, $text, $value) {
    $option = new FormOption(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), class: NULL, disabled: false, id: NULL, name: NULL, selectedValue: !isset($tournamentId) ? DEFAULT_VALUE_TOURNAMENT_ID : "", suffix: NULL, text: Constant::TEXT_NONE, value: DEFAULT_VALUE_TOURNAMENT_ID);
    $output .= $option->getHtml();
    foreach ($resultList as $tournament) {
      $option = new FormOption(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), class: NULL, disabled: false, id: NULL, name: NULL, selectedValue: $tournamentId, suffix: NULL, text: $tournament->getDisplayDetails(), value: $tournament->getId());
      $output .= $option->getHtml();
    }
    $output .= "    </select>\n";
    $output .= "   </div>\n";
    // ($debug, $accessKey, $autoComplete, $autoFocus, $checked, $class, $cols, $disabled, $id, $maxLength, $name, $onClick, $placeholder, $readOnly, $required, $rows, $size, $suffix, $type, $value, $wrap
    $buttonView = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_VIEW, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_VIEW, maxLength: NULL, name: Constant::TEXT_VIEW, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_VIEW, wrap: NULL);
    $output .= " <div class=\"responsive-cell responsive-cell-button\">" . $buttonView->getHtml() . "</div>\n";
    $hiddenMode = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::FIELD_NAME_MODE, maxLength: NULL, name: Constant::FIELD_NAME_MODE, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: $mode, wrap: NULL);
    $output .= $hiddenMode->getHtml();
    $hiddenSelectedRowsPlayerId = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: SELECTED_ROWS_FIELD_NAME, maxLength: NULL, name: SELECTED_ROWS_FIELD_NAME, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: $ids, wrap: NULL);
    $output .= $hiddenSelectedRowsPlayerId->getHtml();
    $hiddenSelectedRowsPlayerStatus = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: SELECTED_ROWS_TOURNAMENT_PLAYER_STATUS_FIELD_NAME, maxLength: NULL, name: SELECTED_ROWS_TOURNAMENT_PLAYER_STATUS_FIELD_NAME, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: $tournamentPlayerStatus, wrap: NULL);
    $output .= $hiddenSelectedRowsPlayerStatus->getHtml();
    $output .= "</div>\n";
  } else {
    $output .= "No tournaments available to manage tournament absences";
  }
  if ($tournamentId != DEFAULT_VALUE_TOURNAMENT_ID) {
    $output .= " <div class=\"buttons center\">\n";
    $buttonRegister = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_ATTEND, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_ATTEND, maxLength: NULL, name: Constant::TEXT_ATTEND, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_ATTEND_UNATTEND, wrap: NULL);
    $output .= $buttonRegister->getHtml();
    $output .= " </div>\n";
    $params = array(SessionUtility::getValue(SessionUtility::OBJECT_NAME_START_DATE)->getDatabaseFormat(), SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_END_DATE)->getDatabaseFormat(), SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_CHAMPIONSHIP_QUALIFY));
    $pdoStatementAndQuery = $databaseResult->getChampionshipQualifiedPlayers(params: $params, returnQuery: true);
    $pdoStatement = $pdoStatementAndQuery[0];
    $query = $pdoStatementAndQuery[1];
    $colFormats = NULL; // array(array(3, "right", 0));
    $hideColIndexes = array(0,2,3,4,5,6);
    $htmlTable = new HtmlTable(caption: NULL, class: NULL, colspan: NULL, columnFormat: $colFormats, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), delimiter: Constant::DELIMITER_DEFAULT, foreignKeys: NULL, header: true, hiddenAdditional: NULL, hiddenId: TOURNAMENT_PLAYER_ID_FIELD_NAME, hideColumnIndexes: $hideColIndexes, html: NULL, id: NULL, link: NULL, note: true, pdoStatement: $pdoStatement, query: $query, selectedRow: $ids, suffix: NULL, width: "100%");
    $output .= $htmlTable->getHtml();
  }
}
$smarty->assign("content", $output);
$smarty->display("manage.tpl");
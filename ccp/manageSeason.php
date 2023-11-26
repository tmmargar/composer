<?php
declare(strict_types = 1);
namespace Poker\Ccp;
use Poker\Ccp\classes\model\Constant;
use Poker\Ccp\classes\model\DateTime;
use Poker\Ccp\classes\model\FormControl;
use Poker\Ccp\classes\model\HtmlTable;
use Poker\Ccp\classes\utility\SessionUtility;
require_once "init.php";
define("SEASON_DESCRIPTION_FIELD_LABEL", "Description");
define("SEASON_START_DATE_FIELD_LABEL", "Start date");
define("SEASON_END_DATE_FIELD_LABEL", "End date");
define("SEASON_CHAMPIONSHIP_QUALIFY_FIELD_LABEL", "# to qualify");
define("SEASON_FINAL_TABLE_BONUS_POINTS_FIELD_LABEL", "Final Table Players");
define("SEASON_FINAL_TABLE_PLAYERS_FIELD_LABEL", "Final Table Bonus Points");
define("SEASON_FEE_FIELD_LABEL", "Fee");
define("SEASON_ACTIVE_FIELD_LABEL", "Active");
define("SEASON_DESCRIPTION_FIELD_NAME", "seasonDescription");
define("SEASON_START_DATE_FIELD_NAME", "seasonStartDate");
define("SEASON_END_DATE_FIELD_NAME", "seasonEndDate");
define("SEASON_FEE_FIELD_NAME", "seasonFee");
define("SEASON_FINAL_TABLE_PLAYERS_FIELD_NAME", "seasonFinalTablePlayers");
define("SEASON_FINAL_TABLE_BONUS_POINTS_FIELD_NAME", "seasonFinalTableBonusPoints");
define("SEASON_ACTIVE_FIELD_NAME", "seasonActive");
define("SEASON_CHAMPIONSHIP_QUALIFY_FIELD_NAME", "seasonChampionshipQualify");
define("DEFAULT_VALUE_CHAMPIONSHIP_QUALIFY", 8);
define("DEFAULT_VALUE_FINAL_TABLE_PLAYERS", 8);
define("DEFAULT_VALUE_FINAL_TABLE_BONUS_POINTS", 3);
define("DEFAULT_VALUE_SEASON_ACTIVE", "0");
$smarty->assign("title", "Manage Season");
$smarty->assign("heading", "Manage Season");
$smarty->assign("style", "<link href=\"css/manageSeason.css\" rel=\"stylesheet\">");
if (Constant::MODE_CREATE == $mode || Constant::MODE_MODIFY == $mode) {
  $params = Constant::MODE_MODIFY == $mode ? array($ids) : array(0);
  $resultList = $databaseResult->getSeasonById(params: $params);
  $output .= " <div class=\"buttons center\">\n";
  $buttonSave = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_SAVE, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_SAVE . "_2", maxLength: NULL, name: Constant::TEXT_SAVE . "_2", onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_SAVE, wrap: NULL);
  $output .= $buttonSave->getHtml();
  $buttonReset = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_RESET, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_RESET . "_2", maxLength: NULL, name: Constant::TEXT_RESET . "_2", onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_RESET, value: Constant::TEXT_RESET, wrap: NULL, noValidate: true);
  $output .= $buttonReset->getHtml();
  $buttonCancel = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_CANCEL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_CANCEL . "_2", maxLength: NULL, name: Constant::TEXT_CANCEL . "_2", onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_CANCEL, wrap: NULL, noValidate: true);
  $output .= $buttonCancel->getHtml();
  $output .= " </div>\n";
  $output .= "<div class=\"responsive responsive--2cols responsive--collapse\">";
  if (Constant::MODE_CREATE == $mode || (Constant::MODE_MODIFY == $mode && DEFAULT_VALUE_BLANK != $ids)) {
    $ctr = 0;
    $ary = explode(Constant::DELIMITER_DEFAULT, $ids);
    foreach ($ary as $id) {
      $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . SEASON_DESCRIPTION_FIELD_NAME . "_" . $id . "\">" . SEASON_DESCRIPTION_FIELD_LABEL . ($id != "" ? " " . $id : "") . ": </label></div>\n";
      $textBoxDescription = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_DESCRIPTION, autoComplete: NULL, autoFocus: true, checked: NULL, class: NULL, cols: 30, disabled: false, id: SEASON_DESCRIPTION_FIELD_NAME . "_" . $id, maxLength: 200, name: SEASON_DESCRIPTION_FIELD_NAME . "_" . $id, onClick: NULL, placeholder: NULL, readOnly: false, required: true, rows: 6, size: 100, suffix: NULL, type: FormControl::TYPE_INPUT_TEXTAREA, value: ((count($resultList) > 0) ? $resultList[$ctr]->getDescription() : ""), wrap: NULL);
      $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $textBoxDescription->getHtml() . "</div>\n";
      $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . SEASON_START_DATE_FIELD_NAME . "_" . $id . "\">" . SEASON_START_DATE_FIELD_LABEL . ($id != "" ? " " . $id : "") . ": </label></div>\n";
      $textBoxStartDate = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_START_TIME, autoComplete: NULL, autoFocus: false, checked: NULL, class: array("timePicker"), cols: NULL, disabled: false, id: SEASON_START_DATE_FIELD_NAME . "_" . $id, maxLength: 30, name: SEASON_START_DATE_FIELD_NAME . "_" . $id, onClick: NULL, placeholder: NULL, readOnly: false, required: true, rows: NULL, size: 20, suffix: NULL, type: FormControl::TYPE_INPUT_DATE_TIME, value: ((count($resultList) > 0) ? $resultList[$ctr]->getStartDate()->getDisplayDateTimePickerFormat() : ""), wrap: NULL);
      $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $textBoxStartDate->getHtml() . "</div>\n";
      $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . SEASON_END_DATE_FIELD_NAME . "_" . $id . "\">" . SEASON_END_DATE_FIELD_LABEL . ($id != "" ? " " . $id : "") . ": </label></div>\n";
      $textBoxEndDate = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_START_TIME, autoComplete: NULL, autoFocus: false, checked: NULL, class: array("timePicker"), cols: NULL, disabled: false, id: SEASON_END_DATE_FIELD_NAME . "_" . $id, maxLength: 30, name: SEASON_END_DATE_FIELD_NAME . "_" . $id, onClick: NULL, placeholder: NULL, readOnly: false, required: true, rows: NULL, size: 20, suffix: NULL, type: FormControl::TYPE_INPUT_DATE_TIME, value: ((count($resultList) > 0) ? $resultList[$ctr]->getEndDate()->getDisplayDateTimePickerFormat() : ""), wrap: NULL);
      $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $textBoxEndDate->getHtml() . "</div>\n";
      $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . SEASON_CHAMPIONSHIP_QUALIFY_FIELD_NAME . "_" . $id . "\">" . SEASON_CHAMPIONSHIP_QUALIFY_FIELD_LABEL . ($id != "" ? " " . $id : "") . ": </label></div>\n";
      $textBoxChampionshipQualify = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: true, checked: NULL, class: NULL, cols: NULL, disabled: false, id: SEASON_CHAMPIONSHIP_QUALIFY_FIELD_NAME . "_" . $id, maxLength: 2, name: SEASON_CHAMPIONSHIP_QUALIFY_FIELD_NAME . "_" . $id, onClick: NULL, placeholder: NULL, readOnly: false, required: true, rows: NULL, size: 3, suffix: NULL, type: FormControl::TYPE_INPUT_NUMBER, value: (int) ((count($resultList) > 0) ? $resultList[$ctr]->getChampionshipQualify() : ""), wrap: NULL);
      $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $textBoxChampionshipQualify->getHtml() . "</div>\n";
      $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . SEASON_FINAL_TABLE_PLAYERS_FIELD_NAME . "_" . $id . "\">" . SEASON_FINAL_TABLE_PLAYERS_FIELD_LABEL . ($id != "" ? " " . $id : "") . ": </label></div>\n";
      $textBoxFinalTablePlayers = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: true, checked: NULL, class: NULL, cols: NULL, disabled: false, id: SEASON_FINAL_TABLE_PLAYERS_FIELD_NAME . "_" . $id, maxLength: 2, name: SEASON_FINAL_TABLE_PLAYERS_FIELD_NAME . "_" . $id, onClick: NULL, placeholder: NULL, readOnly: false, required: true, rows: NULL, size: 3, suffix: NULL, type: FormControl::TYPE_INPUT_NUMBER, value: (int) ((count($resultList) > 0) ? $resultList[$ctr]->getFinalTablePlayers() : ""), wrap: NULL);
      $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $textBoxFinalTablePlayers->getHtml() . "</div>\n";
      $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . SEASON_FINAL_TABLE_BONUS_POINTS_FIELD_NAME . "_" . $id . "\">" . SEASON_FINAL_TABLE_BONUS_POINTS_FIELD_LABEL . ($id != "" ? " " . $id : "") . ": </label></div>\n";
      $textBoxFinalTableBonusPoints = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: true, checked: NULL, class: NULL, cols: NULL, disabled: false, id: SEASON_FINAL_TABLE_BONUS_POINTS_FIELD_NAME . "_" . $id, maxLength: 2, name: SEASON_FINAL_TABLE_BONUS_POINTS_FIELD_NAME . "_" . $id, onClick: NULL, placeholder: NULL, readOnly: false, required: true, rows: NULL, size: 3, suffix: NULL, type: FormControl::TYPE_INPUT_NUMBER, value: (int) ((count($resultList) > 0) ? $resultList[$ctr]->getFinalTableBonusPoints() : ""), wrap: NULL);
      $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $textBoxFinalTableBonusPoints->getHtml() . "</div>\n";
      $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . SEASON_FEE_FIELD_NAME . "_" . $id . "\">" . SEASON_FEE_FIELD_LABEL . ($id != "" ? " " . $id : "") . ": </label></div>\n";
      $textBoxFee = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: true, checked: NULL, class: NULL, cols: NULL, disabled: false, id: SEASON_FEE_FIELD_NAME . "_" . $id, maxLength: 2, name: SEASON_FEE_FIELD_NAME . "_" . $id, onClick: NULL, placeholder: NULL, readOnly: false, required: true, rows: NULL, size: 3, suffix: NULL, type: FormControl::TYPE_INPUT_NUMBER, value: (string) ((count($resultList) > 0) ? $resultList[$ctr]->getFee() : ""), wrap: NULL);
      $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $textBoxFee->getHtml() . "</div>\n";
      $resultList2 = $databaseResult->getSeasonActiveCount();
      $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . SEASON_ACTIVE_FIELD_NAME . "_" . $id . "\">" . SEASON_ACTIVE_FIELD_LABEL . ($id != "" ? " " . $id : "") . ": </label></div>\n";
      $checkboxActive = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: ((count($resultList) > 0) && Constant::FLAG_YES_DATABASE == $resultList[$ctr]->getActive() ? true : false), class: NULL, cols: NULL, disabled: (count($resultList) > 0 && $resultList2[0] == 0) || (count($resultList) > 0 && $resultList2[0] != 0 && Constant::FLAG_YES_DATABASE == $resultList[$ctr]->getActive())  ? false : true, id: SEASON_ACTIVE_FIELD_NAME . "_" . $id, maxLength: NULL, name: SEASON_ACTIVE_FIELD_NAME . "_" . $id, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix:NULL, type: FormControl::TYPE_INPUT_CHECKBOX, value: (string) Constant::FLAG_YES_DATABASE, wrap: NULL);
      $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $checkboxActive->getHtml() . "</div>\n";
      $hiddenRow = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: HIDDEN_ROW_FIELD_NAME . "_" . $id, maxLength: NULL, name: HIDDEN_ROW_FIELD_NAME . "_" . $id, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: ((count($resultList) > 0) ? $resultList[$ctr]->getId() : ""), wrap: NULL);
      $output .= $hiddenRow->getHtml();
      $ctr++;
    }
    $output .= " <div class=\"buttons center\">\n";
    $buttonSave = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_SAVE, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_SAVE, maxLength: NULL, name: Constant::TEXT_SAVE, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_SAVE, wrap: NULL);
    $output .= $buttonSave->getHtml();
    $buttonReset = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_RESET, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_RESET, maxLength: NULL, name: Constant::TEXT_RESET, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_RESET, value: Constant::TEXT_RESET, wrap: NULL, noValidate: true);
    $output .= $buttonReset->getHtml();
  }
  $buttonCancel = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_CANCEL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_CANCEL, maxLength: NULL, name: Constant::TEXT_CANCEL, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_CANCEL, wrap: NULL, noValidate: true);
  $output .= $buttonCancel->getHtml();
  $output .= " </div>\n";
  $hiddenMode = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::FIELD_NAME_MODE, maxLength: NULL, name: Constant::FIELD_NAME_MODE, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: $mode, wrap: NULL);
  $output .= $hiddenMode->getHtml();
  $hiddenSelectedRows = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: SELECTED_ROWS_FIELD_NAME, maxLength: NULL, name: SELECTED_ROWS_FIELD_NAME, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: $ids, wrap: NULL);
  $output .= $hiddenSelectedRows->getHtml();
  $output .= "</div>\n";
} elseif (Constant::MODE_SAVE_CREATE == $mode || Constant::MODE_SAVE_MODIFY == $mode) {
  $ary = explode(Constant::DELIMITER_DEFAULT, $ids);
  foreach ($ary as $id) {
    $seasonId = (isset($_POST[HIDDEN_ROW_FIELD_NAME . "_" . $id])) ? $_POST[HIDDEN_ROW_FIELD_NAME . "_" . $id] : DEFAULT_VALUE_BLANK;
    $seasonDescription = (isset($_POST[SEASON_DESCRIPTION_FIELD_NAME . "_" . $id])) ? $_POST[SEASON_DESCRIPTION_FIELD_NAME . "_" . $id] : DEFAULT_VALUE_BLANK;
    $seasonStartDate = isset($_POST[SEASON_START_DATE_FIELD_NAME . "_" . $id]) ? $_POST[SEASON_START_DATE_FIELD_NAME . "_" . $id] : DEFAULT_VALUE_BLANK;
    $seasonEndDate = isset($_POST[SEASON_END_DATE_FIELD_NAME . "_" . $id]) ? $_POST[SEASON_END_DATE_FIELD_NAME . "_" . $id] : DEFAULT_VALUE_BLANK;
    $seasonChampionshipQualify = isset($_POST[SEASON_CHAMPIONSHIP_QUALIFY_FIELD_NAME . "_" . $id]) ? $_POST[SEASON_CHAMPIONSHIP_QUALIFY_FIELD_NAME . "_" . $id] : DEFAULT_VALUE_CHAMPIONSHIP_QUALIFY;
    $seasonFinalTablePlayers = isset($_POST[SEASON_FINAL_TABLE_PLAYERS_FIELD_NAME . "_" . $id]) ? $_POST[SEASON_FINAL_TABLE_PLAYERS_FIELD_NAME . "_" . $id] : DEFAULT_VALUE_FINAL_TABLE_PLAYERS;
    $seasonFinalTablePlayersBonusPoints = isset($_POST[SEASON_FINAL_TABLE_BONUS_POINTS_FIELD_NAME . "_" . $id]) ? $_POST[SEASON_FINAL_TABLE_BONUS_POINTS_FIELD_NAME . "_" . $id] : DEFAULT_VALUE_FINAL_TABLE_BONUS_POINTS;
    $seasonFee = isset($_POST[SEASON_FEE_FIELD_NAME . "_" . $id]) ? $_POST[SEASON_FEE_FIELD_NAME . "_" . $id] : DEFAULT_VALUE_SEASON_FEE;
    $seasonActive = isset($_POST[SEASON_ACTIVE_FIELD_NAME . "_" . $id]) ? $_POST[SEASON_ACTIVE_FIELD_NAME . "_" . $id] : DEFAULT_VALUE_SEASON_ACTIVE;
    $seasonActive = DEFAULT_VALUE_BLANK == $seasonActive ? DEFAULT_VALUE_SEASON_ACTIVE : $seasonActive;
    $seasonStartDateTime = new DateTime(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: NULL, time: $seasonStartDate);
    $seasonEndDateTime = new DateTime(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: NULL, time: $seasonEndDate);
    $params = array($seasonStartDateTime->getDatabaseFormat(), $seasonEndDateTime->getDatabaseFormat(), $seasonId == "" ? NULL : $seasonId);
    $rowCount = $databaseResult->getSeasonDateCheckCount(params: $params);
    if ($rowCount[0] > 0) {
      $output .=
        "<script type=\"module\">\n" .
        "  import { dataTable, display, input } from \"./scripts/import.js\";\n" .
        "  display.showErrors({errors: [ \"You must enter a start date (" . $seasonStartDateTime->getDisplayFormat() . ") and end date  (" . $seasonEndDateTime->getDisplayFormat() . ") that do not overlap with an existing season\" ]});\n" .
        "</script>\n";
    } else {
      if (Constant::MODE_SAVE_CREATE == $mode) {
        $params = array($seasonDescription, $seasonStartDateTime->getDatabaseFormat(), $seasonEndDateTime->getDatabaseFormat(), $seasonChampionshipQualify, $seasonFinalTablePlayers, $seasonFinalTablePlayersBonusPoints, $seasonFee, isset($seasonActive) ? $seasonActive : 0);
        $rowCount = $databaseResult->insertSeason(params: $params);
        $resultList = $databaseResult->getSeasonMaxId();
        $params = array($resultList[0] - 1);
        $resultList2 = $databaseResult->getTournamentIdMax(params: $params);
        $params = array($resultList[0], $resultList2[0] + 1);
        $rowCount = $databaseResult->insertFeeUsersForYear(params: $params);
      } elseif (Constant::MODE_SAVE_MODIFY == $mode) {
        $params = array($seasonId, $seasonDescription, $seasonStartDateTime->getDatabaseFormat(), $seasonEndDateTime->getDatabaseFormat(), $seasonChampionshipQualify, $seasonFinalTablePlayers, $seasonFinalTablePlayersBonusPoints, $seasonFee, isset($seasonActive) ? $seasonActive : 0);
        $rowCount = $databaseResult->updateSeason(params: $params);
      }
      if (!is_numeric($rowCount)) {
        $output .=
          "<script type=\"module\">\n" .
          "  import { dataTable, display, input } from \"./scripts/import.js\";\n" .
          "  display.showErrors({errors: [ \"" . $rowCount . "\" ]});\n" .
          "</script>\n";
      }
    }
    $ids = DEFAULT_VALUE_BLANK;
  }
  $params = array(1);
  $resultList = $databaseResult->getSeasonByActive(params: $params);
  if (count($resultList) > 0) {
    SessionUtility::regenerateAllSessions(seasonNew: $resultList[0]);
  }
  $mode = Constant::MODE_VIEW;
}
if (Constant::MODE_VIEW == $mode || Constant::MODE_DELETE == $mode || Constant::MODE_CONFIRM == $mode) {
  if (Constant::MODE_CONFIRM == $mode) {
    if ($ids != DEFAULT_VALUE_BLANK) {
      $params = array($ids);
      $rowCount = $databaseResult->deleteFeeBySeason(params: $params);
      $rowCount = $databaseResult->deleteSeason(params: $params);
      if (!is_numeric($rowCount)) {
        $output .=
          "<script type=\"module\">\n" .
          "  import { dataTable, display, input } from \"./scripts/import.js\";\n" .
          "  display.showErrors({errors: [ \"" . $rowCount . "\" ]});\n" .
          "</script>\n";
      }
      $ids = DEFAULT_VALUE_BLANK;
    }
    $mode = Constant::MODE_VIEW;
  }
  $output .= "<div class=\"buttons center\">\n";
  if (Constant::MODE_VIEW == $mode) {
    // ($debug, $accessKey, $autoComplete, $autoFocus, $checked, $class, $cols, $disabled, $id, $maxLength, $name, $onClick, $placeholder, $readOnly, $required, $rows, $size, $suffix, $type, $value, $wrap
    $buttonCreate = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_CREATE, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_CREATE . "_2", maxLength: NULL, name: Constant::TEXT_CREATE . "_2", onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_CREATE, wrap: NULL);
    $output .= $buttonCreate->getHtml();
    $buttonModify = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG),accessKey:  Constant::ACCESSKEY_MODIFY, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: true, id: Constant::TEXT_MODIFY . "_2", maxLength: NULL, name: Constant::TEXT_MODIFY . "_2", onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_MODIFY, wrap: NULL);
    $output .= $buttonModify->getHtml();
    $buttonDelete = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_DELETE, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: true, id: Constant::TEXT_DELETE . "_2", maxLength: NULL, name: Constant::TEXT_DELETE . "_2", onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_DELETE, wrap: NULL);
    $output .= $buttonDelete->getHtml();
  } else if (Constant::MODE_DELETE == $mode) {
    $buttonDelete = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_CONFIRM_DELETE, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_CONFIRM_DELETE . "_2", maxLength: NULL, name: Constant::TEXT_CONFIRM_DELETE . "_2", onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_CONFIRM_DELETE, wrap: NULL);
    $output .= $buttonDelete->getHtml();
    $buttonDelete = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_CANCEL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_CANCEL . "_2", maxLength: NULL, name: Constant::TEXT_CANCEL . "_2", onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_CANCEL, wrap: NULL, noValidate: true);
    $output .= $buttonDelete->getHtml();
  }
  $output .= "</div>\n";
  $hiddenMode = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::FIELD_NAME_MODE, maxLength: NULL, name: Constant::FIELD_NAME_MODE, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: $mode, wrap: NULL);
  $output .= $hiddenMode->getHtml();
  $hiddenSelectedRows = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: SELECTED_ROWS_FIELD_NAME, maxLength: NULL, name: SELECTED_ROWS_FIELD_NAME, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: $ids, wrap: NULL);
  $output .= $hiddenSelectedRows->getHtml();
  $params = array(NULL, true, Constant::MODE_DELETE == $mode ? false : true, "" == $ids ? NULL : $ids);
  $pdoStatementAndQuery = $databaseResult->getSeason(params: $params);
  $pdoStatement = $pdoStatementAndQuery[0];
  $query = $pdoStatementAndQuery[1];
  $colFormats = array(array(7, "currency", 0));
  $htmlTable = new HtmlTable(caption: NULL, class: NULL, colspan: NULL, columnFormat: $colFormats, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), delimiter: Constant::DELIMITER_DEFAULT, foreignKeys: NULL, header: true, hiddenAdditional: NULL, hiddenId: HIDDEN_ROW_FIELD_NAME, hideColumnIndexes: NULL, html: NULL, id: NULL, link: NULL, note: true, pdoStatement: $pdoStatement, query: $query, selectedRow: $ids, suffix: NULL, width: "100%");
  $output .= $htmlTable->getHtml();
  $output .= "<div class=\"buttons center\">\n";
  if (Constant::MODE_VIEW == $mode) {
    // ($debug, $accessKey, $autoComplete, $autoFocus, $checked, $class, $cols, $disabled, $id, $maxLength, $name, $onClick, $placeholder, $readOnly, $required, $rows, $size, $suffix, $type, $value, $wrap
    $buttonCreate = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_CREATE, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_CREATE, maxLength: NULL, name: Constant::TEXT_CREATE, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_CREATE, wrap: NULL);
    $output .= $buttonCreate->getHtml();
    $buttonModify = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG),accessKey:  Constant::ACCESSKEY_MODIFY, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: true, id: Constant::TEXT_MODIFY, maxLength: NULL, name: Constant::TEXT_MODIFY, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_MODIFY, wrap: NULL);
    $output .= $buttonModify->getHtml();
    $buttonDelete = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_DELETE, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: true, id: Constant::TEXT_DELETE, maxLength: NULL, name: Constant::TEXT_DELETE, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_DELETE, wrap: NULL);
    $output .= $buttonDelete->getHtml();
  } else if (Constant::MODE_DELETE == $mode) {
    $buttonDelete = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_CONFIRM_DELETE, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_CONFIRM_DELETE, maxLength: NULL, name: Constant::TEXT_CONFIRM_DELETE, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_CONFIRM_DELETE, wrap: NULL);
    $output .= $buttonDelete->getHtml();
    $buttonDelete = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_CANCEL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_CANCEL, maxLength: NULL, name: Constant::TEXT_CANCEL, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_CANCEL, wrap: NULL, noValidate: true);
    $output .= $buttonDelete->getHtml();
  }
  $output .= "</div>\n";
}
$smarty->assign("content", $output);
$smarty->display("manage.tpl");
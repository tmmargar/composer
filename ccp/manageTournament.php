<?php
declare(strict_types = 1);
namespace ccp;
use Poker\Ccp\classes\model\Constant;
use Poker\Ccp\classes\model\DateTime;
use Poker\Ccp\classes\model\FormControl;
use Poker\Ccp\classes\model\FormOption;
use Poker\Ccp\classes\model\FormSelect;
use Poker\Ccp\classes\model\HtmlTable;
use Poker\Ccp\classes\utility\SessionUtility;
require_once "init.php";
define("TOURNAMENT_DESCRIPTION_FIELD_LABEL", "Description");
define("TOURNAMENT_COMMENT_FIELD_LABEL", "Comment");
define("TOURNAMENT_MAP_FIELD_LABEL", "Map");
define("TOURNAMENT_LIMIT_TYPE_ID_FIELD_LABEL", "Limit type");
define("TOURNAMENT_GAME_TYPE_ID_FIELD_LABEL", "Game type");
define("TOURNAMENT_SPECIAL_TYPE_ID_FIELD_LABEL", "Special type");
define("TOURNAMENT_CHIP_COUNT_FIELD_LABEL", "Chip count");
define("TOURNAMENT_LOCATION_ID_FIELD_LABEL", "Location");
define("TOURNAMENT_DATE_TIME_FIELD_LABEL", "Date / Time");
define("TOURNAMENT_BUYIN_AMOUNT_FIELD_LABEL", "Buyin amount");
define("TOURNAMENT_MAX_PLAYERS_FIELD_LABEL", "Max players");
define("TOURNAMENT_REBUY_AMOUNT_FIELD_LABEL", "Rebuy amount");
define("TOURNAMENT_ADDON_AMOUNT_FIELD_LABEL", "Addon amount");
define("TOURNAMENT_ADDON_CHIP_COUNT_FIELD_LABEL", "Addon chip count");
define("TOURNAMENT_MAX_REBUYS_FIELD_LABEL", "Max rebuys");
define("TOURNAMENT_GROUP_ID_FIELD_LABEL", "Group");
define("TOURNAMENT_RAKE_FIELD_LABEL", "Rake");
define("TOURNAMENT_DESCRIPTION_FIELD_NAME", "tournamentDescription");
define("TOURNAMENT_COMMENT_FIELD_NAME", "tournamentComment");
define("TOURNAMENT_MAP_FIELD_NAME", "tournamentMap");
define("TOURNAMENT_ID_FIELD_NAME", "tournamentName");
define("TOURNAMENT_DESC_FIELD_NAME", "tournamentDescription");
define("TOURNAMENT_DATE_FIELD_NAME", "tournamentDate");
define("TOURNAMENT_LIMIT_TYPE_ID_FIELD_NAME", "tournamentLimitTypeId");
define("TOURNAMENT_GAME_TYPE_ID_FIELD_NAME", "tournamentGameTypeId");
define("TOURNAMENT_SPECIAL_TYPE_ID_FIELD_NAME", "tournamentSpecialTypeId");
define("TOURNAMENT_CHIP_COUNT_FIELD_NAME", "tournamentChipCount");
define("TOURNAMENT_LOCATION_ID_FIELD_NAME", "tournamentLocationId");
define("TOURNAMENT_DATE_TIME_FIELD_NAME", "tournamentStartDateTime");
define("TOURNAMENT_BUYIN_AMOUNT_FIELD_NAME", "tournamentBuyinAmount");
define("TOURNAMENT_MAX_PLAYERS_FIELD_NAME", "tournamentMaxPlayers");
define("TOURNAMENT_MAX_REBUYS_FIELD_NAME", "tournamentRebuys");
define("TOURNAMENT_REBUY_AMOUNT_FIELD_NAME", "tournamentRebuyAmount");
define("TOURNAMENT_ADDON_AMOUNT_FIELD_NAME", "tournamentAddonAmount");
define("TOURNAMENT_ADDON_CHIP_COUNT_FIELD_NAME", "tournamentAddonChipCount");
define("TOURNAMENT_GROUP_ID_FIELD_NAME", "tournamentGroupId");
define("TOURNAMENT_RAKE_FIELD_NAME", "tournamentRake");
define("TOURNAMENT_RESULTS_EXIST_FIELD_NAME", "tournamentResultsExist");
define("SELECTED_ROWS_TOURNAMENT_LOCATION_ID_FIELD_NAME", "tournamentLocationIds");
define("SELECTED_ROWS_TOURNAMENT_GROUP_ID_FIELD_NAME", "tournamentGroupIds");
define("HIDDEN_ROW_TOURNAMENT_LOCATION_ID_FIELD_NAME", "rowTournamentLocationId");
define("HIDDEN_ROW_TOURNAMENT_GROUP_ID_FIELD_NAME", "rowTournamentGroupId");
define("SELECT_COLUMN_PREFIX_FIELD_NAME", "select");
define("DEFAULT_VALUE_TOURNAMENT_ID", -1);
$smarty->assign("title", "Manage Tournament");
$smarty->assign("heading", "Manage Tournament");
$smarty->assign("style", "<link href=\"css/manageTournament.css\" rel=\"stylesheet\">");
if (Constant::MODE_CREATE == $mode || Constant::MODE_MODIFY == $mode) {
  $params = Constant::MODE_MODIFY == $mode ? array((int) $ids) : array((int) 0);
  $paramsNested = array(SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_START_DATE)->getDatabaseFormat(), SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_END_DATE)->getDatabaseFormat(), SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_CHAMPIONSHIP_QUALIFY));
  $resultList = $databaseResult->getTournamentById(params: $params, paramsNested: $paramsNested);
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
      $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . TOURNAMENT_DESCRIPTION_FIELD_NAME . "_" . $id . "\">" . TOURNAMENT_DESCRIPTION_FIELD_LABEL . ($id != "" ? " " . $id : "") . ": </label></div>\n";
      $textBoxName = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_DESCRIPTION, autoComplete: NULL, autoFocus: true, checked: NULL, class: NULL, cols: NULL, disabled: false, id: TOURNAMENT_DESCRIPTION_FIELD_NAME . "_" . $id, maxLength: 200, name: TOURNAMENT_DESCRIPTION_FIELD_NAME . "_" . $id, onClick: NULL, placeholder: NULL, readOnly: false, required: true, rows: NULL, size: 20, suffix: NULL, type: FormControl::TYPE_INPUT_TEXTBOX, value: ((count($resultList) > 0) ? $resultList[$ctr]->getDescription() : ""), wrap: NULL);
      $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $textBoxName->getHtml() . "</div>";
      $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . TOURNAMENT_COMMENT_FIELD_NAME . "_" . $id . "\">" . TOURNAMENT_COMMENT_FIELD_LABEL . ($id != "" ? " " . $id : "") . ": </label></div>\n";
      $textBoxName = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_COMMENT, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: TOURNAMENT_COMMENT_FIELD_NAME . "_" . $id, maxLength: 200, name: TOURNAMENT_COMMENT_FIELD_NAME . "_" . $id, onClick: NULL, placeholder: NULL, readOnly: false, required: false, rows: NULL, size: 20, suffix: NULL, type: FormControl::TYPE_INPUT_TEXTBOX, value: ((count($resultList) > 0) ? $resultList[$ctr]->getComment() : ""), wrap: NULL);
      $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $textBoxName->getHtml() . "</div>";
      // $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . TOURNAMENT_MAP_FIELD_NAME . "_" . $id . "\">Map link " . $id . ": </label></div>\n";
      // $output .= HtmlUtility::buildTextbox(Constant::ACCESSKEY_MAP, NULL, false, Base::build(TOURNAMENT_MAP_FIELD_NAME . "_" . $id, NULL), 255, Base::build(TOURNAMENT_MAP_FIELD_NAME . "_" . $id, NULL), false, 100, ((count($resultList) > 0) ? $resultList[$ctr]->getDirections() : ""), NULL);
      $params = array(NULL, false, array(false, NULL));
      $resultList2 = $databaseResult->getLimitType(params: $params);
      if (count($resultList2) > 0) {
        $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . TOURNAMENT_LIMIT_TYPE_ID_FIELD_NAME . "_" . $id . "\">" . TOURNAMENT_LIMIT_TYPE_ID_FIELD_LABEL . ($id != "" ? " " . $id : "") . ": </label></div>\n";
        //     $debug, $accessKey, $class, $disabled, $id, $multiple, $name, $onClick, $readOnly, $size, $suffix, $value
        $selectLimitType = new FormSelect(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_LIMIT_TYPE, class: NULL, disabled: false, id: TOURNAMENT_LIMIT_TYPE_ID_FIELD_NAME . "_" . $id, multiple: false, name: TOURNAMENT_LIMIT_TYPE_ID_FIELD_NAME . "_" . $id, onClick: NULL, readOnly: false, size: 1, suffix: NULL, value: NULL);
        $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $selectLimitType->getHtml();
        //     $debug, $class, $disabled, $id, $name, $selectedValue, $suffix, $text, $value) {
        $option = new FormOption(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), class: NULL, disabled: false, id: NULL, name: NULL, selectedValue: count($resultList) > 0 ? $resultList[$ctr]->getLimitType()->getId() : "", suffix: NULL, text: Constant::TEXT_NONE, value: DEFAULT_VALUE_BLANK);
        $output .= $option->getHtml();
        foreach ($resultList2 as $limitType) {
          $option = new FormOption(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), class: NULL, disabled: false, id: NULL, name: NULL, selectedValue: count($resultList) > 0 ? $resultList[$ctr]->getLimitType()->getId() : "", suffix: NULL, text: $limitType->getName(), value: $limitType->getId());
          $output .= $option->getHtml();
        }
        $output .= "     </select>\n";
        $output .= "    </div>\n";
      }
      $params = array(NULL, false, array(false, NULL));
      $resultList2 = $databaseResult->getGameType($params);
      if (count($resultList2) > 0) {
        $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . TOURNAMENT_GAME_TYPE_ID_FIELD_NAME . "_" . $id . "\">" . TOURNAMENT_GAME_TYPE_ID_FIELD_LABEL . ($id != "" ? " " . $id : "") . ": </label></div>\n";
        $selectLimitType = new FormSelect(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_GAME_TYPE, class: NULL, disabled: false, id: TOURNAMENT_GAME_TYPE_ID_FIELD_NAME . "_" . $id, multiple: false, name: TOURNAMENT_GAME_TYPE_ID_FIELD_NAME . "_" . $id, onClick: NULL, readOnly: false, size: 1, suffix: NULL, value: NULL);
        $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $selectLimitType->getHtml();
        $option = new FormOption(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), class: NULL, disabled: false, id: NULL, name: NULL, selectedValue: count($resultList) > 0 ? $resultList[$ctr]->getGameType()->getId() : "", suffix: NULL, text: Constant::TEXT_NONE, value: DEFAULT_VALUE_BLANK);
        $output .= $option->getHtml();
        foreach ($resultList2 as $gameType) {
          $option = new FormOption(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), class: NULL, disabled: false, id: NULL, name: NULL, selectedValue: count($resultList) > 0 ? $resultList[$ctr]->getGameType()->getId() : "", suffix: NULL, text: $gameType->getName(), value: $gameType->getId());
          $output .= $option->getHtml();
        }
        $output .= "     </select>\n";
        $output .= "    </div>\n";
      }
      $params = array(NULL, false, array(true, NULL));
      $resultList2 = $databaseResult->getSpecialType($params);
      if (count($resultList2) > 0) {
        $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . TOURNAMENT_SPECIAL_TYPE_ID_FIELD_NAME . "_" . $id . "\">" . TOURNAMENT_SPECIAL_TYPE_ID_FIELD_LABEL . ($id != "" ? " " . $id : "") . ": </label></div>\n";
        $selectSpecialType = new FormSelect(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_SPECIAL_TYPE, class: NULL, disabled: false, id: TOURNAMENT_SPECIAL_TYPE_ID_FIELD_NAME . "_" . $id, multiple: false, name: TOURNAMENT_SPECIAL_TYPE_ID_FIELD_NAME . "_" . $id, onClick: NULL, readOnly: false, size: 1, suffix: NULL, value: NULL);
        $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $selectSpecialType->getHtml();
        $option = new FormOption(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), class: NULL, disabled: false, id: NULL, name: NULL, selectedValue: count($resultList) > 0 ? $resultList[$ctr]->getGameType()->getId() : "", suffix: NULL, text: Constant::TEXT_NONE, value: DEFAULT_VALUE_BLANK);
        $output .= $option->getHtml();
        foreach ($resultList2 as $specialType) {
          $option = new FormOption(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), class: NULL, disabled: false, id: NULL, name: NULL, selectedValue: count($resultList) > 0 ? $resultList[$ctr]->getSpecialType()->getId() : "", suffix: NULL, text: $specialType->getDescription(), value: $specialType->getId());
          $output .= $option->getHtml();
        }
        $output .= "     </select>\n";
        $output .= "    </div>\n";
      }
      $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . TOURNAMENT_CHIP_COUNT_FIELD_NAME . "_" . $id . "\">" . TOURNAMENT_CHIP_COUNT_FIELD_LABEL . ($id != "" ? " " . $id : "") . ": </label></div>\n";
      $textBoxName = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_CHIP_COUNT, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: TOURNAMENT_CHIP_COUNT_FIELD_NAME . "_" . $id, maxLength: 5, name: TOURNAMENT_CHIP_COUNT_FIELD_NAME . "_" . $id, onClick: NULL, placeholder: NULL, readOnly: false, required: true, rows: NULL, size: 5, suffix: NULL, type: FormControl::TYPE_INPUT_NUMBER, value: ((count($resultList) > 0) ? (string) $resultList[$ctr]->getChipCount() : ""), wrap: NULL);
      $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $textBoxName->getHtml() . " (# > 0 except Championship)</div>\n";
      $params = array(false, true, true);
      $resultList2 = $databaseResult->getLocation(params: $params);
      if (count($resultList2) > 0) {
        $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . TOURNAMENT_LOCATION_ID_FIELD_NAME . "_" . $id . "\">" . TOURNAMENT_LOCATION_ID_FIELD_LABEL . ($id != "" ? " " . $id : "") . ": </label></div>\n";
        $selectLocation = new FormSelect(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_LOCATION_NAME, class: NULL, disabled: false, id: TOURNAMENT_LOCATION_ID_FIELD_NAME . "_" . $id, multiple: false, name: TOURNAMENT_LOCATION_ID_FIELD_NAME . "_" . $id, onClick: NULL, readOnly: false, size: 1, suffix: NULL, value: NULL);
        $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $selectLocation->getHtml();
        $option = new FormOption(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), class: NULL, disabled: false, id: NULL, name: NULL, selectedValue: count($resultList) > 0 ? $resultList[$ctr]->getLocation()->getId() : "", suffix: NULL, text: Constant::TEXT_NONE, value: DEFAULT_VALUE_BLANK);
        $output .= $option->getHtml();
        foreach ($resultList2 as $location) {
          $option = new FormOption(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), class: NULL, disabled: false, id: NULL, name: NULL, selectedValue: count($resultList) > 0 ? $resultList[$ctr]->getLocation()->getId() : "", suffix: NULL, text: $location->getName(), value: $location->getId());
          $output .= $option->getHtml();
        }
        $output .= "     </select>\n";
        $output .= "    </div>\n";
      }
      $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . TOURNAMENT_DATE_TIME_FIELD_NAME . "_" . $id . "\">" . TOURNAMENT_DATE_TIME_FIELD_LABEL . ($id != "" ? " " . $id : "") . ": </label></div>\n";
      $textBoxName = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_START_TIME, autoComplete: NULL, autoFocus: false, checked: NULL, class: array("timePicker"), cols: NULL, disabled: false, id: TOURNAMENT_DATE_TIME_FIELD_NAME . "_" . $id, maxLength: 30, name: TOURNAMENT_DATE_TIME_FIELD_NAME . "_" . $id, onClick: NULL, placeholder: NULL, readOnly: false, required: true, rows: NULL, size: 20, suffix: NULL, type: FormControl::TYPE_INPUT_DATE_TIME, value: ((count($resultList) > 0) ? ($resultList[$ctr]->getDateAndTime()->getDisplayDateTimePickerFormat()) : ""), wrap: NULL);
      $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $textBoxName->getHtml() . "</div>\n";
      $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . TOURNAMENT_BUYIN_AMOUNT_FIELD_NAME . "_" . $id . "\">" . TOURNAMENT_BUYIN_AMOUNT_FIELD_LABEL . ($id != "" ? " " . $id : "") . ": </label></div>\n";
      $textBoxName = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_BUYIN_AMOUNT, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: TOURNAMENT_BUYIN_AMOUNT_FIELD_NAME . "_" . $id, maxLength: 4, name: TOURNAMENT_BUYIN_AMOUNT_FIELD_NAME . "_" . $id, onClick: NULL, placeholder: NULL, readOnly: false, required: true, rows: NULL, size: 5, suffix: NULL, type: FormControl::TYPE_INPUT_NUMBER, value: ((count($resultList) > 0) ? (string) -$resultList[$ctr]->getBuyinAmount() : ""), wrap: NULL);
      $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $textBoxName->getHtml() . " (# > 0 except Championship)</div>\n";
      $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . TOURNAMENT_MAX_PLAYERS_FIELD_NAME . "_" . $id . "\">" . TOURNAMENT_MAX_PLAYERS_FIELD_LABEL . ($id != "" ? " " . $id : "") . ": </label></div>\n";
      $textBoxName = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_MAX_PLAYERS, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: TOURNAMENT_MAX_PLAYERS_FIELD_NAME . "_" . $id, maxLength: 2, name: TOURNAMENT_MAX_PLAYERS_FIELD_NAME . "_" . $id, onClick: NULL, placeholder: NULL, readOnly: false, required: true, rows: NULL, size: 5, suffix: NULL, type: FormControl::TYPE_INPUT_NUMBER, value: ((count($resultList) > 0) ? (string) $resultList[$ctr]->getMaxPlayers() : ""), wrap: NULL);
      $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $textBoxName->getHtml() . " (# > 0)</div>\n";
      $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . TOURNAMENT_REBUY_AMOUNT_FIELD_NAME . "_" . $id . "\">" . TOURNAMENT_REBUY_AMOUNT_FIELD_LABEL . ($id != "" ? " " . $id : "") . ": </label></div>\n";
      $textBoxName = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_REBUY_AMOUNT, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: TOURNAMENT_REBUY_AMOUNT_FIELD_NAME . "_" . $id, maxLength: 4, name: TOURNAMENT_REBUY_AMOUNT_FIELD_NAME . "_" . $id, onClick: NULL, placeholder: NULL, readOnly: false, required: true, rows: NULL, size: 5, suffix: NULL, type: FormControl::TYPE_INPUT_NUMBER, value: ((count($resultList) > 0) ? (string) -$resultList[$ctr]->getRebuyAmount() : ""), wrap: NULL);
      $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $textBoxName->getHtml() . " (# >= 0)</div>\n";
      $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . TOURNAMENT_MAX_REBUYS_FIELD_NAME . "_" . $id . "\">" . TOURNAMENT_MAX_REBUYS_FIELD_LABEL . ($id != "" ? " " . $id : "") . ": </label></div>\n";
      $textBoxName = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_MAX_REBUYS, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: TOURNAMENT_MAX_REBUYS_FIELD_NAME . "_" . $id, maxLength: 2, name: TOURNAMENT_MAX_REBUYS_FIELD_NAME . "_" . $id, onClick: NULL, placeholder: NULL, readOnly: false, required: true, rows: NULL, size: 5, suffix: NULL, type: FormControl::TYPE_INPUT_NUMBER, value: ((count($resultList) > 0) ? (string) $resultList[$ctr]->getMaxRebuys() : ""), wrap: NULL);
      $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $textBoxName->getHtml() . " (# >= 0)</div>\n";
      $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . TOURNAMENT_ADDON_AMOUNT_FIELD_NAME . "_" . $id . "\">" . TOURNAMENT_ADDON_AMOUNT_FIELD_LABEL . ($id != "" ? " " . $id : "") . ": </label></div>\n";
      $textBoxName = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_ADDON_AMOUNT, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: TOURNAMENT_ADDON_AMOUNT_FIELD_NAME . "_" . $id, maxLength: 4, name: TOURNAMENT_ADDON_AMOUNT_FIELD_NAME . "_" . $id, onClick: NULL, placeholder: NULL, readOnly: false, required: true, rows: NULL, size: 5, suffix: NULL, type: FormControl::TYPE_INPUT_NUMBER, value: (count($resultList) > 0 ? (string) -$resultList[$ctr]->getAddonAmount() : ""), wrap: NULL);
      $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $textBoxName->getHtml() . " (# >= 0)</div>\n";
      $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . TOURNAMENT_ADDON_CHIP_COUNT_FIELD_NAME . "_" . $id . "\">" . TOURNAMENT_ADDON_CHIP_COUNT_FIELD_LABEL . ($id != "" ? " " . $id : "") . ": </label></div>\n";
      $textBoxName = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_ADDON_CHIP_COUNT, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: TOURNAMENT_ADDON_CHIP_COUNT_FIELD_NAME . "_" . $id, maxLength: 5, name: TOURNAMENT_ADDON_CHIP_COUNT_FIELD_NAME . "_" . $id, onClick: NULL, placeholder: NULL, readOnly: false, required: true, rows: NULL, size: 5, suffix: NULL, type: FormControl::TYPE_INPUT_NUMBER, value: ((count($resultList) > 0) ? (string) $resultList[$ctr]->getAddonChipCount() : ""), wrap: NULL);
      $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $textBoxName->getHtml() . " (# >= 0)</div>\n";
      $resultList2 = $databaseResult->getGroupNameList();
      if (count($resultList2) > 0) {
        $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . TOURNAMENT_GROUP_ID_FIELD_NAME . "_" . $id . "\">" . TOURNAMENT_GROUP_ID_FIELD_LABEL . ($id != "" ? " " . $id : "") . ": </label></div>\n";
        $selectLocation = new FormSelect(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_GROUP, class: NULL, disabled: false, id: TOURNAMENT_GROUP_ID_FIELD_NAME . "_" . $id, multiple: false, name: TOURNAMENT_GROUP_ID_FIELD_NAME . "_" . $id, onClick:NULL, readOnly: false, size: 1, suffix: NULL, value: NULL);
        $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $selectLocation->getHtml();
        $option = new FormOption(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), class: NULL, disabled: false, id: NULL, name: NULL, selectedValue: count($resultList) > 0 ? $resultList[$ctr]->getGroupPayout()->getGroup()->getId() : "", suffix: NULL, text: Constant::TEXT_NONE, value: DEFAULT_VALUE_BLANK);
        $output .= $option->getHtml();
        foreach ($resultList2 as $values) {
          $option = new FormOption(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), class: NULL, disabled: false, id: NULL, name: NULL, selectedValue: count($resultList) > 0 ? $resultList[$ctr]->getGroupPayout()->getGroup()->getId() : "", suffix: NULL, text: $values[1], value: $values[0]);
          $output .= $option->getHtml();
        }
        $output .= "     </select>\n";
        $output .= "    </div>\n";
      }
      $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . TOURNAMENT_RAKE_FIELD_NAME . "_" . $id . "\">" . TOURNAMENT_RAKE_FIELD_LABEL . ($id != "" ? " " . $id : "") . ": </label></div>\n";
      $textBoxName = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_RAKE, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: TOURNAMENT_RAKE_FIELD_NAME . "_" . $id, maxLength: 4, name: TOURNAMENT_RAKE_FIELD_NAME . "_" . $id, onClick: NULL, placeholder: NULL, readOnly: false, required: true, rows: NULL, size: 5, suffix: NULL, type: FormControl::TYPE_INPUT_NUMBER, value: ((count($resultList) > 0) ? (string) $resultList[$ctr]->getRake() : ""), wrap: NULL);
      $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $textBoxName->getHtml() . " (# 1 to 99 except Championship)</div>\n";
      $hiddenRow = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: HIDDEN_ROW_FIELD_NAME . "_" . $id, maxLength: NULL, name: HIDDEN_ROW_FIELD_NAME . "_" . $id, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: ((count($resultList) > 0) ? $resultList[$ctr]->getId() : ""), wrap: NULL);
      $output .= $hiddenRow->getHtml();
      $hiddenRow = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: HIDDEN_ROW_TOURNAMENT_LOCATION_ID_FIELD_NAME . "_" . $id, maxLength: NULL, name: HIDDEN_ROW_TOURNAMENT_LOCATION_ID_FIELD_NAME . "_" . $id, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: ((count($resultList) > 0) ? $resultList[$ctr]->getLocation()->getId() : ""), wrap: NULL);
      $output .= $hiddenRow->getHtml();
      $hiddenRow = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: HIDDEN_ROW_TOURNAMENT_GROUP_ID_FIELD_NAME . "_" . $id, maxLength: NULL, name: HIDDEN_ROW_TOURNAMENT_GROUP_ID_FIELD_NAME . "_" . $id, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: ((count($resultList) > 0) ? $resultList[$ctr]->getGroupPayout()->getGroup()->getId() : ""), wrap: NULL);
      $output .= $hiddenRow->getHtml();
      $ctr ++;
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
    $tournamentId = (int) ((isset($_POST[HIDDEN_ROW_FIELD_NAME . "_" . $id])) ? $_POST[HIDDEN_ROW_FIELD_NAME . "_" . $id] : DEFAULT_VALUE_TOURNAMENT_ID);
    $tournamentDescription = (isset($_POST[TOURNAMENT_DESCRIPTION_FIELD_NAME . "_" . $id])) ? $_POST[TOURNAMENT_DESCRIPTION_FIELD_NAME . "_" . $id] : DEFAULT_VALUE_BLANK;
    $tournamentComment = (isset($_POST[TOURNAMENT_COMMENT_FIELD_NAME . "_" . $id])) ? $_POST[TOURNAMENT_COMMENT_FIELD_NAME . "_" . $id] : DEFAULT_VALUE_BLANK;
    $tournamentMap = (isset($_POST[TOURNAMENT_MAP_FIELD_NAME . "_" . $id])) ? $_POST[TOURNAMENT_MAP_FIELD_NAME . "_" . $id] : DEFAULT_VALUE_BLANK;
    $tournamentLimitTypeId = (int) ((isset($_POST[TOURNAMENT_LIMIT_TYPE_ID_FIELD_NAME . "_" . $id])) ? $_POST[TOURNAMENT_LIMIT_TYPE_ID_FIELD_NAME . "_" . $id] : 0);
    $tournamentGameTypeId = (int) ((isset($_POST[TOURNAMENT_GAME_TYPE_ID_FIELD_NAME . "_" . $id])) ? $_POST[TOURNAMENT_GAME_TYPE_ID_FIELD_NAME . "_" . $id] : 0);
    $tournamentSpecialTypeId = (isset($_POST[TOURNAMENT_SPECIAL_TYPE_ID_FIELD_NAME . "_" . $id])) ? $_POST[TOURNAMENT_SPECIAL_TYPE_ID_FIELD_NAME . "_" . $id] : DEFAULT_VALUE_BLANK;
    $tournamentChipCount = (int) ((isset($_POST[TOURNAMENT_CHIP_COUNT_FIELD_NAME . "_" . $id])) ? $_POST[TOURNAMENT_CHIP_COUNT_FIELD_NAME . "_" . $id] : 0);
    $tournamentLocationId = (int) ((isset($_POST[TOURNAMENT_LOCATION_ID_FIELD_NAME . "_" . $id])) ? $_POST[TOURNAMENT_LOCATION_ID_FIELD_NAME . "_" . $id] : 0);
    $tournamentDateTime = isset($_POST[TOURNAMENT_DATE_TIME_FIELD_NAME . "_" . $id]) ? $_POST[TOURNAMENT_DATE_TIME_FIELD_NAME . "_" . $id] : DEFAULT_VALUE_BLANK;
    $aryTournamentDateTime = explode("T", $tournamentDateTime);
    $tournamentDate = $aryTournamentDateTime[0];
    $tournamentStartTime = $aryTournamentDateTime[1];
    $tournamentBuyinAmount = (int) ((isset($_POST[TOURNAMENT_BUYIN_AMOUNT_FIELD_NAME . "_" . $id])) ? $_POST[TOURNAMENT_BUYIN_AMOUNT_FIELD_NAME . "_" . $id] : 0);
    $tournamentMaxPlayers = (int) ((isset($_POST[TOURNAMENT_MAX_PLAYERS_FIELD_NAME . "_" . $id])) ? $_POST[TOURNAMENT_MAX_PLAYERS_FIELD_NAME . "_" . $id] : 0);
    $tournamentMaxRebuys = (int) ((isset($_POST[TOURNAMENT_MAX_REBUYS_FIELD_NAME . "_" . $id])) ? $_POST[TOURNAMENT_MAX_REBUYS_FIELD_NAME . "_" . $id] : 0);
    $tournamentRebuyAmount = (int) ((isset($_POST[TOURNAMENT_REBUY_AMOUNT_FIELD_NAME . "_" . $id])) ? $_POST[TOURNAMENT_REBUY_AMOUNT_FIELD_NAME . "_" . $id] : 0);
    $tournamentAddonAmount = (int) ((isset($_POST[TOURNAMENT_ADDON_AMOUNT_FIELD_NAME . "_" . $id])) ? $_POST[TOURNAMENT_ADDON_AMOUNT_FIELD_NAME . "_" . $id] : 0);
    $tournamentAddonChipCount = (int) ((isset($_POST[TOURNAMENT_ADDON_CHIP_COUNT_FIELD_NAME . "_" . $id])) ? $_POST[TOURNAMENT_ADDON_CHIP_COUNT_FIELD_NAME . "_" . $id] : 0);
    $tournamentGroupId = (int) ((isset($_POST[TOURNAMENT_GROUP_ID_FIELD_NAME . "_" . $id])) ? $tournamentGroupId = $_POST[TOURNAMENT_GROUP_ID_FIELD_NAME . "_" . $id] : 0);
    $tournamentRake = (isset($_POST[TOURNAMENT_RAKE_FIELD_NAME . "_" . $id])) ? $_POST[TOURNAMENT_RAKE_FIELD_NAME . "_" . $id] : 0;
    $dateTime = new DateTime(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: NULL, time: $tournamentDate);
    $dateTimeStart = new DateTime(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: NULL, time: $tournamentDate . " " . $tournamentStartTime);
    if (Constant::MODE_SAVE_CREATE == $mode) {
      $params = array($tournamentDescription, $tournamentComment, $tournamentLimitTypeId, $tournamentGameTypeId, $tournamentChipCount, $tournamentLocationId, $dateTime->getDatabaseFormat(), $dateTimeStart->getDatabaseTimeFormat(), $tournamentBuyinAmount, $tournamentMaxPlayers, $tournamentMaxRebuys, $tournamentRebuyAmount, $tournamentAddonAmount, $tournamentAddonChipCount, $tournamentGroupId, (float) (trim($tournamentRake, "%") / 100), $tournamentMap, $tournamentSpecialTypeId);
      $rowCount = $databaseResult->insertTournament(params: $params);
      // check if first tournament for season if so add fees
      $resultList = $databaseResult->getSeasonMaxId();
      $params = array((int) $resultList[0]);
      $resultList2 = $databaseResult->getTournamentIdMax(params: $params);
      if (!isset($resultList2[0]) || $resultList2[0] == 0) {
        $params = array((int) ($resultList[0] - 1));
        $resultList3 = $databaseResult->getTournamentIdMax(params: $params);
        $params = array((int) $resultList[0], (int) ($resultList3[0] + 1));
        $rowCount = $databaseResult->insertFeePlayersForYear(params: $params);
      }
    } elseif (Constant::MODE_SAVE_MODIFY == $mode) {
      $params = array($tournamentId, $tournamentDescription, $tournamentComment, $tournamentLimitTypeId, $tournamentGameTypeId, $tournamentChipCount, $tournamentLocationId, $dateTime->getDatabaseFormat(), $dateTimeStart->getDatabaseTimeFormat(), $tournamentBuyinAmount, $tournamentMaxPlayers, $tournamentMaxRebuys, $tournamentRebuyAmount, $tournamentAddonAmount, $tournamentAddonChipCount, $tournamentGroupId, ($tournamentRake / 100), $tournamentMap, $tournamentSpecialTypeId);
      $rowCount = $databaseResult->updateTournament(params: $params);
      $params = array($tournamentMaxRebuys == 0 ? (int) 0 : NULL, $tournamentMaxRebuys == 0 ? Constant::FLAG_NO : NULL, $tournamentAddonAmount == 0 ? Constant::FLAG_NO : NULL, $tournamentAddonAmount == 0 ? Constant::FLAG_NO : NULL, $tournamentId);
      if (isset($params[0]) || isset($params[1]) || isset($params[2]) || isset($params[3])) {
        $rowCount = $databaseResult->updateResultByTournamentId(params: $params);
      }
    }
    if (!is_numeric($rowCount)) {
      $output .=
      "<script type=\"module\">\n" .
      "  import { dataTable, display, input } from \"./scripts/import.js\";\n" .
      "  display.showErrors({errors: [ \"" . $rowCount . "\" ]});\n" .
      "</script>\n";
    }
    $ids = DEFAULT_VALUE_BLANK;
    $mode = Constant::MODE_VIEW;
  }
}
if (Constant::MODE_MODIFY == $mode || Constant::MODE_DELETE == $mode) {
  $resultsExist = 0;
  $params = array((int) $ids, false);
  $resultList = $databaseResult->getResultFinishedByTournamentId(params: $params);
  if (count($resultList) > 0) {
    $resultsExist = Constant::FLAG_YES_DATABASE;
  }
  $hiddenResultsExist = new FormControl(SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), NULL, NULL, false, NULL, NULL, NULL, false, TOURNAMENT_RESULTS_EXIST_FIELD_NAME, NULL, TOURNAMENT_RESULTS_EXIST_FIELD_NAME, NULL, NULL, false, NULL, NULL, NULL, NULL, FormControl::TYPE_INPUT_HIDDEN, (string) $resultsExist, NULL);
  $output .= $hiddenResultsExist->getHtml();
}
if (Constant::MODE_VIEW == $mode || Constant::MODE_DELETE == $mode || Constant::MODE_CONFIRM == $mode) {
  if (Constant::MODE_CONFIRM == $mode) {
    if ($ids != DEFAULT_VALUE_BLANK) {
      $params = array((int) $ids);
      $resultList = $databaseResult->getFeeCountByTournamentId(params: $params);
      if ($resultList[0] > 0) {
        $output .=
          "<script type=\"module\">\n" .
          "  import { dataTable, display, input } from \"./scripts/import.js\";\n" .
          "  display.showErrors({errors: [ \"You cannot delete tournament id " . $ids . " becauses fees exist for that tournament\" ]});\n" .
          "</script>\n";
      } else {
        $params = array((int) $ids);
        $rowCount = $databaseResult->deleteResult(params: $params);
        $rowCount = $databaseResult->deleteTournament(params: $params);
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
    $mode = Constant::MODE_VIEW;
  }
  $output .= "<div class=\"buttons center\">\n";
  if (Constant::MODE_VIEW == $mode) {
    $buttonCreate = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_CREATE, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_CREATE, maxLength: NULL, name: Constant::TEXT_CREATE, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_CREATE, wrap: NULL);
    $output .= $buttonCreate->getHtml();
    $buttonModify = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_MODIFY, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: true, id: Constant::TEXT_MODIFY, maxLength: NULL, name: Constant::TEXT_MODIFY, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_MODIFY, wrap: NULL);
    $output .= $buttonModify->getHtml();
    $buttonDelete = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_DELETE, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: true, id: Constant::TEXT_DELETE, maxLength: NULL, name: Constant::TEXT_DELETE, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_DELETE, wrap: NULL, noValidate: true);
    $output .= $buttonDelete->getHtml();
  } else if (Constant::MODE_DELETE == $mode) {
    $buttonDelete = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_CONFIRM_DELETE, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_CONFIRM_DELETE, maxLength: NULL, name: Constant::TEXT_CONFIRM_DELETE, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_CONFIRM_DELETE, wrap: NULL);
    $output .= $buttonDelete->getHtml();
    $buttonDelete = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_CANCEL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_CANCEL, maxLength: NULL, name: Constant::TEXT_CANCEL, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_CANCEL, wrap: NULL, noValidate: true);
    $output .= $buttonDelete->getHtml();
  }
  $output .= "</div>\n";
  $hiddenMode = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::FIELD_NAME_MODE, maxLength: NULL, name: Constant::FIELD_NAME_MODE, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: $mode, wrap: NULL);
  $output .= $hiddenMode->getHtml();
  $hiddenSelectedRows = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: SELECTED_ROWS_FIELD_NAME, maxLength: NULL, name: SELECTED_ROWS_FIELD_NAME, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: $ids, wrap: NULL);
  $output .= $hiddenSelectedRows->getHtml();
  $params = array(NULL, true, "" == $ids ? NULL : (int) $ids);
  $paramsNested = array(SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_START_DATE)->getDatabaseFormat(), SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_END_DATE)->getDatabaseFormat(), SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_CHAMPIONSHIP_QUALIFY));
  $pdoStatementAndQuery = $databaseResult->getTournament(params: $params, paramsNested: $paramsNested, mode: NULL);
  $pdoStatement = $pdoStatementAndQuery[0];
  $query = $pdoStatementAndQuery[1];
  $colFormats = array(array(19, "time", NULL), array(20, "number", NULL), array(21, "number", 0), array(22, "currency", 0), array(23, "number", 0), array(24, "currency", 0), array(25, "currency", 0), array(26, "number", 0), array(29, "percentage", 0));
  $hideColIndexes = array(3, 5, 7, 9, 10, 11, 12, 13, 14, 15, 16, 27, 30, 31, 32, 33, 34, 35, 36, 38);
  $colSpan = array(array("Game", "Rebuy", "Addon", "Group"), array(6, 23, 25, 28), array(array(8), array(24), array(26), array(29)));
  $htmlTable = new HtmlTable(caption: NULL, class: NULL, colspan: $colSpan, columnFormat: $colFormats, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), delimiter: Constant::DELIMITER_DEFAULT, foreignKeys: NULL, header: true, hiddenAdditional: NULL, hiddenId: HIDDEN_ROW_FIELD_NAME, hideColumnIndexes: $hideColIndexes, html: NULL, id: NULL, link: NULL, note: true, pdoStatement: $pdoStatement, query: $query, selectedRow: $ids, suffix: NULL, width: "100%");
  $output .= $htmlTable->getHtml();
}
$smarty->assign("content", $output);
$smarty->display("manage.tpl");
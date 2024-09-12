<?php
declare(strict_types = 1);
namespace ccp;
use Exception;
use Poker\Ccp\Model\Constant;
use Poker\Ccp\Model\FormControl;
use Poker\Ccp\Model\FormOption;
use Poker\Ccp\Model\FormSelect;
use Poker\Ccp\Model\HtmlTable;
use Poker\Ccp\Utility\SessionUtility;
use Poker\Ccp\Entity\Locations;
require_once "init.php";
define("LOCATION_NAME_FIELD_LABEL", "Location name");
define("ADDRESS_FIELD_LABEL", "Address");
define("CITY_FIELD_LABEL", "City");
define("STATE_FIELD_LABEL", "State");
define("ZIP_FIELD_LABEL", "Zip");
define("ACTIVE_FIELD_LABEL", "Active");
define("MAP_FIELD_LABEL", "Map");
define("PLAYER_ID_FIELD_LABEL", "Player name");
define("LOCATION_NAME_FIELD_NAME", "locationName");
define("ADDRESS_FIELD_NAME", "address");
define("CITY_FIELD_NAME", "city");
define("STATE_FIELD_NAME", "states");
define("ZIP_FIELD_NAME", "zipCode");
define("ACTIVE_FIELD_NAME", "active");
define("MAP_FIELD_NAME", "map");
define("PLAYER_ID_FIELD_NAME", "playerId");
$smarty->assign("title", "Manage Location");
$smarty->assign("heading", "Manage Location");
$smarty->assign("style", "<link href=\"css/manageLocation.css\" rel=\"stylesheet\">");
$errors = NULL;
if (Constant::MODE_CREATE == $mode || Constant::MODE_MODIFY == $mode) {
    $resultList = $entityManager->getRepository(entityName: Constant::ENTITY_LOCATIONS)->getById(locationId: (int) $ids);
    $output .= " <div class=\"buttons center\">\n";
    $buttonSave = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_SAVE, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_SAVE . "_2", maxLength: NULL, name: Constant::TEXT_SAVE . "_2", onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_SAVE, wrap: NULL);
    $output .= $buttonSave->getHtml();
    $buttonReset = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_RESET, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_RESET . "_2", maxLength: NULL, name: Constant::TEXT_RESET . "_2", onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_RESET, value: Constant::TEXT_RESET, wrap: NULL, noValidate: true);
    $output .= $buttonReset->getHtml();
    $buttonCancel = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_CANCEL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_CANCEL . "_2", maxLength: NULL, name: Constant::TEXT_CANCEL . "_2", onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_CANCEL, wrap: NULL, noValidate: true);
    $output .= $buttonCancel->getHtml();
    $output .= " </div>\n";
    $output .= " <div class=\"responsive responsive--2cols responsive--collapse\">";
    if (Constant::MODE_CREATE == $mode || (Constant::MODE_MODIFY == $mode && $ids != DEFAULT_VALUE_BLANK)) {
        $ctr = 0;
        $ary = explode(separator: Constant::DELIMITER_DEFAULT, string: $ids);
        foreach ($ary as $id) {
            $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . LOCATION_NAME_FIELD_NAME . "_" . $id . "\">" . LOCATION_NAME_FIELD_LABEL . ($id != "" ? " " . $id : "") . ": </div>\n";
            $textBoxName = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_LOCATION_NAME, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: LOCATION_NAME_FIELD_NAME . "_" . $id, maxLength: 30, name: LOCATION_NAME_FIELD_NAME . "_" . $id, onClick: NULL, placeholder: NULL, readOnly: true, required: NULL, rows: NULL, size: 30, suffix: NULL, type: FormControl::TYPE_INPUT_TEXTBOX, value: ((count($resultList) > 0) ? $resultList[$ctr]->getLocationName() : ""), wrap: NULL);
            $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $textBoxName->getHtml() . " (last name - city)</div>\n";
            $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . PLAYER_ID_FIELD_NAME . "_" . $id . "\">" . PLAYER_ID_FIELD_LABEL . ($id != "" ? " " . $id : "") . ": </div>\n";
            $resultList2 = $entityManager->getRepository(entityName: Constant::ENTITY_PLAYERS)->getActives();
            if (count($resultList2) > 0) {
                $selectLocation = new FormSelect(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_PLAYER_ID, class: NULL, disabled: false, id: PLAYER_ID_FIELD_NAME . "_" . $id, multiple: false, name: PLAYER_ID_FIELD_NAME . "_" . $id, onClick: NULL, readOnly: false, size: 1, suffix: NULL, value: NULL);
                $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $selectLocation->getHtml();        //     $debug, $class, $disabled, $id, $name, $selectedValue, $suffix, $text, $value) {
                $option = new FormOption(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), class: NULL, disabled: false, id: NULL, name: NULL, selectedValue: NULL, suffix: NULL, text: Constant::TEXT_NONE, value: "");
                $output .= $option->getHtml();
                foreach ($resultList2 as $player) {
                    $option = new FormOption(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), class: NULL, disabled: false, id: NULL, name: NULL, selectedValue: count($resultList) > 0 ? $resultList[$ctr]->getPlayers()->getPlayerId() : "", suffix: NULL, text: $player->getPlayerName(), value: $player->getPlayerId());
                    $output .= $option->getHtml();
                }
                $output .= "  </select>\n";
                $output .= " </div>\n";
          }
          $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . ADDRESS_FIELD_NAME . "_" . $id . "\">" . ADDRESS_FIELD_LABEL . ($id != "" ? " " . $id : "") . ": </div>\n";
          $textBoxAddress = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_ADDRESS, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: ADDRESS_FIELD_NAME . "_" . $id, maxLength: 75, name: ADDRESS_FIELD_NAME . "_" . $id, onClick: NULL, placeholder: NULL, readOnly: false, required: true, rows: NULL, size: 75, suffix: NULL, type: FormControl::TYPE_INPUT_TEXTBOX, value: ((count($resultList) > 0) ? $resultList[$ctr]->getLocationAddress() : ""), wrap: NULL);
          $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $textBoxAddress->getHtml() . "</div>\n";
          $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . CITY_FIELD_NAME . "_" . $id . "\">" . CITY_FIELD_LABEL . ($id != "" ? " " . $id : "") . ": </div>\n";
          $textBoxCity = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_CITY, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: CITY_FIELD_NAME . "_" . $id, maxLength: 50, name: CITY_FIELD_NAME . "_" . $id, onClick: NULL, placeholder: NULL, readOnly: false, required: true, rows: NULL, size: 30, suffix: NULL, type: FormControl::TYPE_INPUT_TEXTBOX, value: ((count($resultList) > 0) ? $resultList[$ctr]->getLocationCity() : ""), wrap: NULL);
          $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $textBoxCity->getHtml() . "</div>\n";
          $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . STATE_FIELD_NAME . "_" . $id . "\">" . STATE_FIELD_LABEL . ($id != "" ? " " . $id : "") . ": </div>\n";
          $output .= " <div class=\"responsive-cell responsive-cell-value\"><input id=\"states_" . $id . "\" name=\"states_" . $id . "\" readonly size=\"1\" type=\"text\" value=\"MI\" />\n</div>\n";
          $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . ZIP_FIELD_NAME . "_" . $id . "\">" . ZIP_FIELD_LABEL . ($id != "" ? " " . $id : "") . ": </div>\n";
          $textBoxZip = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_ZIP, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: ZIP_FIELD_NAME . "_" . $id, maxLength: 5, name: ZIP_FIELD_NAME . "_" . $id, onClick: NULL, placeholder: NULL, readOnly: false, required: true, rows: NULL, size: 5, suffix: NULL, type: FormControl::TYPE_INPUT_NUMBER, value: ((count($resultList) > 0) ? (string) $resultList[$ctr]->getLocationZipCode() : ""), wrap: NULL);
          $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $textBoxZip->getHtml() . " (5 digits)\n</div>\n";
          $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . MAP_FIELD_NAME . "_" . $id . "\">" . MAP_FIELD_LABEL . ($id != "" ? " " . $id : "") . ": </div>\n";
          $textBoxMap = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_MAP, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: MAP_FIELD_NAME . "_" . $id, maxLength: 50, name: MAP_FIELD_NAME . "_" . $id, onClick: NULL, placeholder: NULL, readOnly: false, required: true, rows: NULL, size: 30, suffix: NULL, type: FormControl::TYPE_INPUT_TEXTBOX, value: ((count($resultList) > 0) ? $resultList[$ctr]->getLocationMapLink() : ""), wrap: NULL);
          $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $textBoxMap->getHtml() . " (&lt;filename&gt;.pdf)\n</div>\n";
          $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . ACTIVE_FIELD_NAME . "_" . $id . "\">" . ACTIVE_FIELD_LABEL . ($id != "" ? " " . $id : "") . ": </div>\n";
          $output .= " <div class=\"responsive-cell responsive-cell-value\">" . ((count($resultList) == 0) || Constant::FLAG_YES_DATABASE == $resultList[$ctr]->getPlayers()->getPlayerActiveFlag() ? Constant::TEXT_YES : Constant::TEXT_NO) . "</div>\n";
          $hiddenRow = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: HIDDEN_ROW_FIELD_NAME . "_" . $id, maxLength: NULL, name: HIDDEN_ROW_FIELD_NAME . "_" . $id, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: ((count($resultList) > 0) ? $resultList[$ctr]->getLocationId() : ""), wrap: NULL);
          $output .= $hiddenRow->getHtml();
          $ctr++;
        }
        $output .= " <div class=\"buttons center\">\n";
        $buttonSave = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_SAVE, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_SAVE, maxLength: NULL, name: Constant::TEXT_SAVE, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_SAVE, wrap: NULL);
        $output .= $buttonSave->getHtml();
        $buttonReset = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_RESET, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_RESET, maxLength: NULL, name: Constant::TEXT_RESET, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_RESET, value: Constant::TEXT_RESET, wrap: NULL, noValidate: true);
        $output .= $buttonReset->getHtml();
    }
    $buttonCancel = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_CANCEL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_CANCEL, maxLength: NULL, name: Constant::TEXT_CANCEL, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_CANCEL, wrap: NULL, noValidate: true);
    $output .= $buttonCancel->getHtml();
    $output .= " </div>\n";
    $hiddenMode = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::FIELD_NAME_MODE, maxLength: NULL, name: Constant::FIELD_NAME_MODE, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: $mode, wrap: NULL);
    $output .= $hiddenMode->getHtml();
    $hiddenSelectedRows = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: SELECTED_ROWS_FIELD_NAME, maxLength: NULL, name: SELECTED_ROWS_FIELD_NAME, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: $ids, wrap: NULL);
    $output .= $hiddenSelectedRows->getHtml();
    $output .= "</div>\n";
} elseif (Constant::MODE_SAVE_CREATE == $mode || Constant::MODE_SAVE_MODIFY == $mode) {
    $ctr = 0;
    $ary = explode(separator: Constant::DELIMITER_DEFAULT, string: $ids);
    foreach ($ary as $id) {
        $locationName = isset($_POST[LOCATION_NAME_FIELD_NAME . "_" . $id]) ? $_POST[LOCATION_NAME_FIELD_NAME . "_" . $id] : DEFAULT_VALUE_BLANK;
        $playerId = (int) ((isset($_POST[PLAYER_ID_FIELD_NAME . "_" . $id])) ? $_POST[PLAYER_ID_FIELD_NAME . "_" . $id] : 0);
        $address = (isset($_POST[ADDRESS_FIELD_NAME . "_" . $id])) ? $_POST[ADDRESS_FIELD_NAME . "_" . $id] : DEFAULT_VALUE_BLANK;
        $city = (isset($_POST[CITY_FIELD_NAME . "_" . $id])) ? $_POST[CITY_FIELD_NAME . "_" . $id] : DEFAULT_VALUE_BLANK;
        $state = (isset($_POST[STATE_FIELD_NAME . "_" . $id])) ? $_POST[STATE_FIELD_NAME . "_" . $id] : DEFAULT_VALUE_BLANK;
        $zipCode = (int) ((isset($_POST[ZIP_FIELD_NAME . "_" . $id])) ? $_POST[ZIP_FIELD_NAME . "_" . $id] : 0);
        $map = (isset($_POST[MAP_FIELD_NAME . "_" . $id])) ? $_POST[MAP_FIELD_NAME . "_" . $id] : DEFAULT_VALUE_BLANK;
        if (Constant::MODE_SAVE_CREATE == $mode) {
            $params = array($locationName, $playerId, $address, $city, $state, $zipCode);
            $lo = new Locations();
            $lo->setLocationAddress(locationAddress: $address);
            $lo->setLocationCity(locationCity: $city);
            $lo->setLocationMap(locationMap: NULL);
            $lo->setLocationMapLink(locationMapLink: $map);
            $lo->setLocationName(locationName: $locationName);
            $lo->setLocationState(locationState: $state);
            $lo->setLocationZipCode(locationZipCode: $zipCode);
            $player = $entityManager->find(className: Constant::ENTITY_PLAYERS, id: $playerId);
            $lo->setPlayers(players: $player);
            $entityManager->persist(entity: $lo);
            try {
                $entityManager->flush();
            } catch (Exception $e) {
                $errors = $e->getMessage();
            }
        } elseif (Constant::MODE_SAVE_MODIFY == $mode) {
            $locationName = (isset($_POST[LOCATION_NAME_FIELD_NAME . "_" . $id])) ? $_POST[LOCATION_NAME_FIELD_NAME . "_" . $id] : DEFAULT_VALUE_BLANK;
            $tempLocationId = (int) ((isset($_POST[HIDDEN_ROW_FIELD_NAME . "_" . $id])) ? $_POST[HIDDEN_ROW_FIELD_NAME . "_" . $id] : 0);
            $lo = $entityManager->find(className: Constant::ENTITY_LOCATIONS, id: $tempLocationId);
            $lo->setLocationAddress(locationAddress: $address);
            $lo->setLocationCity(locationCity: $city);
            $lo->setLocationMap(locationMap: NULL);
            $lo->setLocationMapLink(locationMapLink: $map);
            $lo->setLocationName(locationName: $locationName);
            $lo->setLocationState(locationState: $state);
            $lo->setLocationZipCode(locationZipCode: $zipCode);
            $player = $entityManager->find(className: Constant::ENTITY_PLAYERS, id: $playerId);
            $lo->setPlayers(players: $player);
            $entityManager->persist(entity: $lo);
            try {
                $entityManager->flush();
            } catch (Exception $e) {
                $errors = $e->getMessage();
            }
        }
        if (isset($errors)) {
            $output .=
            "<script type=\"module\">\n" .
            "  import { dataTable, display, input } from \"./scripts/import.js\";\n" .
            "  display.showErrors({errors: [ \"" . $errors . "\" ]});\n" .
            "</script>\n";
        }
        $ctr++;
    }
    $ids = DEFAULT_VALUE_BLANK;
    $mode = Constant::MODE_VIEW;
}
if (Constant::MODE_VIEW == $mode || Constant::MODE_DELETE == $mode || Constant::MODE_CONFIRM == $mode) {
    if (Constant::MODE_CONFIRM == $mode) {
        if (DEFAULT_VALUE_BLANK != $ids) {
            $params = array((int) $ids);
            $lo = $entityManager->find(className: Constant::ENTITY_LOCATIONS, id: (int) $ids);
            $entityManager->remove(entity: $lo);
            try {
                $entityManager->flush();
            } catch (Exception $e) {
                $errors = $e->getMessage();
            }
            if (isset($errors)) {
                $output .=
                "<script type=\"module\">\n" .
                "  import { dataTable, display, input } from \"./scripts/import.js\";\n" .
                "  display.showErrors({errors: [ \"" . $errors . "\" ]});\n" .
                "</script>\n";
            }
            $ids = DEFAULT_VALUE_BLANK;
        }
      $mode = Constant::MODE_VIEW;
    }
    $output .= "<div class=\"buttons center\">\n";
    if (Constant::MODE_VIEW == $mode) {
        $buttonCreate = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_CREATE, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_CREATE . "_2", maxLength: NULL, name: Constant::TEXT_CREATE . "_2", onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_CREATE, wrap: NULL);
        $output .= $buttonCreate->getHtml();
        $buttonModify = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG),accessKey:  Constant::ACCESSKEY_MODIFY, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: true, id: Constant::TEXT_MODIFY . "_2", maxLength: NULL, name: Constant::TEXT_MODIFY . "_2", onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_MODIFY, wrap: NULL);
        $output .= $buttonModify->getHtml();
        $buttonDelete = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_DELETE, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: true, id: Constant::TEXT_DELETE . "_2", maxLength: NULL, name: Constant::TEXT_DELETE . "_2", onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_DELETE, wrap: NULL);
        $output .= $buttonDelete->getHtml();
    } else if (Constant::MODE_DELETE == $mode) {
        $buttonDelete = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_CONFIRM_DELETE, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_CONFIRM_DELETE . "_2", maxLength: NULL, name: Constant::TEXT_CONFIRM_DELETE . "_2", onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_CONFIRM_DELETE, wrap: NULL);
        $output .= $buttonDelete->getHtml();
        $buttonDelete = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_CANCEL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_CANCEL . "_2", maxLength: NULL, name: Constant::TEXT_CANCEL . "_2", onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_CANCEL, wrap: NULL);
        $output .= $buttonDelete->getHtml();
    }
    $output .= "</div>\n";
    $hiddenMode = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::FIELD_NAME_MODE, maxLength: NULL, name: Constant::FIELD_NAME_MODE, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: $mode, wrap: NULL);
    $output .= $hiddenMode->getHtml();
    $hiddenSelectedRows = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: SELECTED_ROWS_FIELD_NAME, maxLength: NULL, name: SELECTED_ROWS_FIELD_NAME, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: $ids, wrap: NULL);
    $output .= $hiddenSelectedRows->getHtml();
    $id = ("" == $ids) ? NULL : (int) $ids;
    $result = $entityManager->getRepository(entityName: Constant::ENTITY_LOCATIONS)->getTableOutput(locationId: $id, indexed: true);
    $resultHeaders = $entityManager->getRepository(entityName: Constant::ENTITY_LOCATIONS)->getTableOutput(locationId: $id, indexed: false);
    $colFormats = array(array(0, "right", 0), array(7, "right", 0));
    $hideColIndexes = array(2);
    $link = array(array(3), array("managePlayer.php", array("playerId", "mode"), array(2, "modify"), 3));
    $htmlTable = new HtmlTable(caption: NULL, class: NULL, colspan: NULL, columnFormat: $colFormats, debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), delimiter: Constant::DELIMITER_DEFAULT, foreignKeys: NULL, header: true, hiddenAdditional: NULL, hiddenId: HIDDEN_ROW_FIELD_NAME, hideColumnIndexes: $hideColIndexes, html: NULL, id: NULL, link: $link, note: true, selectedRow: $ids, suffix: NULL, width: "90%");
    $output .= $htmlTable->getHtml(results: $result, resultHeaders: $resultHeaders);
    $output .= "<div class=\"buttons center\">\n";
    if (Constant::MODE_VIEW == $mode) {
        $buttonCreate = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_CREATE, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_CREATE, maxLength: NULL, name: Constant::TEXT_CREATE, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_CREATE, wrap: NULL);
        $output .= $buttonCreate->getHtml();
        $buttonModify = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG),accessKey:  Constant::ACCESSKEY_MODIFY, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: true, id: Constant::TEXT_MODIFY, maxLength: NULL, name: Constant::TEXT_MODIFY, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_MODIFY, wrap: NULL);
        $output .= $buttonModify->getHtml();
        $buttonDelete = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_DELETE, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: true, id: Constant::TEXT_DELETE, maxLength: NULL, name: Constant::TEXT_DELETE, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_DELETE, wrap: NULL);
        $output .= $buttonDelete->getHtml();
    } else if (Constant::MODE_DELETE == $mode) {
        $buttonDelete = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_CONFIRM_DELETE, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_CONFIRM_DELETE, maxLength: NULL, name: Constant::TEXT_CONFIRM_DELETE, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_CONFIRM_DELETE, wrap: NULL);
        $output .= $buttonDelete->getHtml();
        $buttonDelete = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_CANCEL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_CANCEL, maxLength: NULL, name: Constant::TEXT_CANCEL, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_CANCEL, wrap: NULL);
        $output .= $buttonDelete->getHtml();
    }
    $output .= "</div>\n";
}
$smarty->assign("content", $output);
$smarty->display("manage.tpl");
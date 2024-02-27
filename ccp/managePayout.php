<?php
declare(strict_types = 1);
namespace ccp;
use Exception;
use Poker\Ccp\Model\Constant;
use Poker\Ccp\Model\FormControl;
use Poker\Ccp\Model\HtmlTable;
use Poker\Ccp\Model\Payout;
use Poker\Ccp\Model\Structure;
use Poker\Ccp\Utility\SessionUtility;
use Poker\Ccp\Entity\Payouts;
use Poker\Ccp\Entity\Structures;
require_once "init.php";
define("PAYOUT_NAME_FIELD_LABEL", "Payout name");
define("BONUS_POINTS_FIELD_LABEL", "Bonus points");
define("MIN_PLAYERS_FIELD_LABEL", "Min players");
define("MAX_PLAYERS_FIELD_LABEL", "Max players");
define("PLACE_FIELD_LABEL", "Place");
define("PERCENTAGE_FIELD_LABEL", "Percentage");
define("PAYOUT_ID_FIELD_NAME", "payoutId");
define("PAYOUT_NAME_FIELD_NAME", "payoutName");
define("MIN_PLAYERS_FIELD_NAME", "minPlayers");
define("MAX_PLAYERS_FIELD_NAME", "maxPlayers");
define("PLACE_FIELD_NAME", "place");
define("PERCENTAGE_FIELD_NAME", "percentage");
define("PERCENTAGE_TOTAL_FIELD_NAME", "percentageTotal");
define("SELECTED_ROW_FIELD_NAME", "tempPayoutId");
define("DEFAULT_VALUE_PAYOUT_ID", 0);
$smarty->assign("title", "Manage Payout");
$smarty->assign("heading", "Manage Payout");
$smarty->assign("style", "<link href=\"css/managePayout.css\" rel=\"stylesheet\">");
$errors = NULL;
if (Constant::MODE_CREATE == $mode || Constant::MODE_MODIFY == $mode) {
    $ids = isset($_GET[PAYOUT_ID_FIELD_NAME]) ? $_GET[PAYOUT_ID_FIELD_NAME] : $ids;
    $resultList = $entityManager->getRepository(Constant::ENTITY_PAYOUTS)->getById(payoutId: (int) $ids);
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
    $output .= " </div>\n";
    $output .= "<div class=\"responsive responsive--2cols responsive--collapse\">";
    if (Constant::MODE_CREATE == $mode || (Constant::MODE_MODIFY == $mode && $ids != DEFAULT_VALUE_BLANK)) {
        $ctr = 0;
        $ary = explode(Constant::DELIMITER_DEFAULT, $ids);
        foreach ($ary as $id) {
            $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . PAYOUT_NAME_FIELD_NAME . "_" . $id . "\">" . PAYOUT_NAME_FIELD_LABEL . ($id != "" ? " " . $id : "") . ": </div>\n";
            $textPayoutName = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_NAME, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: PAYOUT_NAME_FIELD_NAME . "_" . $id, maxLength: 30, name: PAYOUT_NAME_FIELD_NAME . "_" . $id, onClick: NULL, placeholder: NULL, readOnly: false, required: true, rows: NULL, size: 30, suffix: NULL, type: FormControl::TYPE_INPUT_TEXTBOX, value: ((count($resultList) > 0) ? $resultList[$ctr]->getPayoutName() : ""), wrap: NULL);
            $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $textPayoutName->getHtml() . "</div>\n";
            $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . MIN_PLAYERS_FIELD_NAME . "_" . $id . "\">" . MIN_PLAYERS_FIELD_LABEL . ($id != "" ? " " . $id : "") . ": </div>\n";
            $textMinPlayers = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_MIN_PLAYERS, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: MIN_PLAYERS_FIELD_NAME . "_" . $id, maxLength: 2, name: MIN_PLAYERS_FIELD_NAME . "_" . $id, onClick: NULL, placeholder: NULL, readOnly: false, required: true, rows: NULL, size: 2, suffix: NULL, type: FormControl::TYPE_INPUT_NUMBER, value: (string) ((count($resultList) > 0) ? $resultList[$ctr]->getPayoutMinPlayers() : 0), wrap: NULL);
            $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $textMinPlayers->getHtml() . "</div>\n";
            $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . MAX_PLAYERS_FIELD_NAME . "_" . $id . "\">" . MAX_PLAYERS_FIELD_LABEL . ($id != "" ? " " . $id : "") . ": </div>\n";
            $textMaxPlayers = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_MAX_PLAYERS, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: MAX_PLAYERS_FIELD_NAME . "_" . $id, maxLength: 2, name: MAX_PLAYERS_FIELD_NAME . "_" . $id, onClick: NULL, placeholder: NULL, readOnly: false, required: true, rows: NULL, size: 2, suffix: NULL, type: FormControl::TYPE_INPUT_NUMBER, value: (string) ((count($resultList) > 0) ? $resultList[$ctr]->getPayoutMaxPlayers() : 36), wrap: NULL);
            $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $textMaxPlayers->getHtml() . "</div>\n";
            $percentageTotal = 0;
            $ctr2 = 0;
            $output .= " <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"display\" id=\"" . Constant::ID_TABLE_INPUT . "\" style=\"margin: 0;\" width=\"100%\">\n";
            $output .= "  <tbody>\n";
            if (count($resultList) == 0) {
                $structure = new Structure(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: NULL, place: 1, percentage : 1);
                $payout = new Payout(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: NULL, name: "", minPlayers: 0, maxPlayers: 0, structures: array($structure));
                $resultList[0] = $payout;
            }
            foreach ($resultList[$ctr]->getStructures() as $structures) {
                if (get_class(object: $structures) != "Poker\Ccp\Model\Structure") {
                    $structure = new Structure(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: NULL, place: 0, percentage : 0);
                    $structure->createFromEntity(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), structures: $structures);
                    $payout = new Payout(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: NULL, name: "", minPlayers: 0, maxPlayers: 0, structures: array($structure));
                    $payout->createFromEntity(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), payouts: $resultList[$ctr]);
                }
                $output .= "   <tr>\n";
                $output .= "    <td class=\"textAlignUnset\">" . PLACE_FIELD_LABEL . ($id != "" ? " " . $id : "") . ":</td>\n";
                $output .= "    <td class=\"textAlignUnset\">\n";
                $textPayoutPlace = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_PLACE, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: true, id: PLACE_FIELD_NAME . "_" . $id . "_" . $ctr2, maxLength: 2, name: PLACE_FIELD_NAME . "_" . $id . "_" . $ctr2, onClick: NULL, placeholder: NULL, readOnly: true, required: true, rows: NULL, size: 2, suffix: NULL, type: FormControl::TYPE_INPUT_NUMBER, value: (string) ((count($resultList) > 0) ? $structure->getPlace() : ""), wrap: NULL);
                $output .= $textPayoutPlace->getHtml();
                $output .= "    </td>\n";
                $output .= "    <td class=\"textAlignUnset\">" . PERCENTAGE_FIELD_LABEL . ($id != "" ? " " . $id : "") . ":</td>\n";
                $output .= "    <td class=\"textAlignUnset\">\n";
                $textPayoutPercentage = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_PERCENTAGE, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: PERCENTAGE_FIELD_NAME . "_" . $id . "_" . $ctr2, maxLength: 3, name: PERCENTAGE_FIELD_NAME . "_" . $id . "_" . $ctr2, onClick: NULL, placeholder: NULL, readOnly: false, required: true, rows: NULL, size: 2, suffix: NULL, type: FormControl::TYPE_INPUT_NUMBER, value: (string) ((count($resultList) > 0) ? $structure->getPercentage() * 100 : ""), wrap: NULL);
                $output .= $textPayoutPercentage->getHtml();
                $output .= "    </td>\n";
                $output .= "   </tr>\n";
                $percentageTotal += $structure->getPercentage() * 100;
                $ctr2++;
            }
            $output .= "   <tr id=\"rowTotal\">\n";
            $output .= "    <td class=\"textAlignUnset\"></td>\n";
            $output .= "    <td class=\"textAlignUnset\">\n";
            $textDummy = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: array("hidden"), cols: NULL, disabled: false, id: "dummy_" . $id . "_total", maxLength: 2, name: "dummy_" . $id . "_total", onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: 2, suffix: NULL, type: FormControl::TYPE_INPUT_TEXTBOX, value: NULL, wrap: NULL);
            $output .= $textDummy->getHtml();
            $output .= "    </td>\n";
            $output .= "    <td class=\"textAlignUnset\">Total " . PERCENTAGE_FIELD_LABEL . ($id != "" ? " " . $id : "") . ":</td>\n";
            $output .= "    <td class=\"textAlignUnset\">\n";
            $textPayoutPercentage = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_PERCENTAGE, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: true, id: PERCENTAGE_FIELD_NAME . "Total", maxLength: 3, name: PERCENTAGE_FIELD_NAME . "Total", onClick: NULL, placeholder: NULL, readOnly: true, required: NULL, rows: NULL, size: 2, suffix: NULL, type: FormControl::TYPE_INPUT_TEXTBOX, value: (string) $percentageTotal, wrap: NULL);
            $output .= $textPayoutPercentage->getHtml();
            $output .= "    </td>\n";
            $output .= "   </tr>\n";
            $output .= "  </tbody>\n";
            $output .= " </table>\n";
            $hiddenRow = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: HIDDEN_ROW_FIELD_NAME . "_" . $id, maxLength: NULL, name: HIDDEN_ROW_FIELD_NAME . "_" . $id, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: ((count($resultList) > 0) ? $payout->getId() : ""), wrap: NULL);
            $output .= $hiddenRow->getHtml();
            $ctr++;
        }
        $output .= " <div class=\"buttons center\">\n";
        $buttonAddRow = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_ADD_ROW, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_ADD_ROW, maxLength: NULL, name: Constant::TEXT_ADD_ROW, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_BUTTON, value: Constant::TEXT_ADD_ROW, wrap: NULL);
        $output .= $buttonAddRow->getHtml();
        $buttonRemoveRow = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_REMOVE_ROW, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_REMOVE_ROW, maxLength: NULL, name: Constant::TEXT_REMOVE_ROW, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_BUTTON, value: Constant::TEXT_REMOVE_ROW, wrap: NULL);
        $output .= $buttonRemoveRow->getHtml();
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
        if (Constant::MODE_SAVE_CREATE == $mode || Constant::MODE_SAVE_MODIFY == $mode) {
            $payoutName = (isset($_POST[PAYOUT_NAME_FIELD_NAME . "_" . $id])) ? $_POST[PAYOUT_NAME_FIELD_NAME . "_" . $id] : DEFAULT_VALUE_BLANK;
            $minPlayers = (int) (isset($_POST[MIN_PLAYERS_FIELD_NAME . "_" . $id]) ? $_POST[MIN_PLAYERS_FIELD_NAME . "_" . $id] : 0);
            $maxPlayers = (int) (isset($_POST[MAX_PLAYERS_FIELD_NAME . "_" . $id]) ? $_POST[MAX_PLAYERS_FIELD_NAME . "_" . $id] : 0);
            if (Constant::MODE_SAVE_CREATE == $mode) {
                $pa = new Payouts();
                $pa->setPayoutMaxPlayers($maxPlayers);
                $pa->setPayoutMinPlayers($minPlayers);
                $pa->setPayoutName($payoutName);
                $entityManager->persist($pa);
                try {
                    $entityManager->flush();
                } catch (Exception $e) {
                    $errors = $e->getMessage();
                }
                $resultList2 = $entityManager->getRepository(Constant::ENTITY_PAYOUTS)->getMaxId();
                $tempPayoutId = (int) $resultList2[1];
            } else {
                $tempPayoutId = (int) ((isset($_POST[HIDDEN_ROW_FIELD_NAME . "_" . $id])) ? $_POST[HIDDEN_ROW_FIELD_NAME . "_" . $id] : 0);
            }
            if (Constant::MODE_SAVE_MODIFY == $mode) {
                $pa = $entityManager->find(Constant::ENTITY_PAYOUTS, $tempPayoutId);
                $pa->setPayoutMaxPlayers($maxPlayers);
                $pa->setPayoutMinPlayers($minPlayers);
                $pa->setPayoutName($payoutName);
                $entityManager->persist($pa);
                try {
                    $entityManager->flush();
                } catch (Exception $e) {
                    $errors = $e->getMessage();
                }
                $rowCount = $entityManager->getRepository(Constant::ENTITY_STRUCTURES)->deleteForPayout($tempPayoutId);
            }
            if (isset($errors)) {
                $output .=
                    "<script type=\"module\">\n" .
                    "  import { dataTable, display, input } from \"./scripts/import.js\";\n" .
                    "  display.showErrors({errors: [ \"" . $errors . "\" ]});\n" .
                    "</script>\n";
            }
            $ctr2 = 0;
            $found = true;
            while ($found) {
                $place = (isset($_POST[PLACE_FIELD_NAME . "_" . (Constant::MODE_SAVE_MODIFY == $mode ? $tempPayoutId : "") . "_" . $ctr2])) ? $_POST[PLACE_FIELD_NAME . "_" . (Constant::MODE_SAVE_MODIFY == $mode ? $tempPayoutId : "") . "_" . $ctr2] : NULL;
                $percentage = (isset($_POST[PERCENTAGE_FIELD_NAME . "_" . (Constant::MODE_SAVE_MODIFY == $mode ? $tempPayoutId : "") . "_" . $ctr2])) ? $_POST[PERCENTAGE_FIELD_NAME . "_" . (Constant::MODE_SAVE_MODIFY == $mode ? $tempPayoutId : "") . "_" . $ctr2] : NULL;
                if (isset($place) && isset($percentage)) {
                    $params = array($tempPayoutId, (int) $place, (float) $percentage / 100);
                    $str = new Structures();
                    $pa = $entityManager->find(Constant::ENTITY_PAYOUTS, $tempPayoutId);
                    $str->setPayouts($pa);
                    $str->setStructurePercentage((string) ($percentage / 100));
                    $str->setStructurePlace((int) $place);
                    $entityManager->persist($str);
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
                    $ctr2++;
                } else {
                    $found = false;
                }
            }
        }
    }
    $ids = DEFAULT_VALUE_BLANK;
    $mode = Constant::MODE_VIEW;
}
if (Constant::MODE_VIEW == $mode || Constant::MODE_DELETE == $mode || Constant::MODE_CONFIRM == $mode) {
    if (Constant::MODE_CONFIRM == $mode) {
        if (DEFAULT_VALUE_BLANK != $ids) {
            $rowCount = $entityManager->getRepository(Constant::ENTITY_STRUCTURES)->deleteForPayout((int) $ids);
            $pa = $entityManager->find(Constant::ENTITY_PAYOUTS, (int) $ids);
            $entityManager->remove($pa);
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
        $buttonCreate = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_CREATE, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_CREATE . "_2", maxLength: NULL, name: Constant::TEXT_CREATE . "_2", onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_CREATE, wrap: NULL);
        $output .= $buttonCreate->getHtml();
        $buttonModify = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG),accessKey:  Constant::ACCESSKEY_MODIFY, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: true, id: Constant::TEXT_MODIFY . "_2", maxLength: NULL, name: Constant::TEXT_MODIFY . "_2", onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_MODIFY, wrap: NULL);
        $output .= $buttonModify->getHtml();
        $buttonDelete = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_DELETE, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: true, id: Constant::TEXT_DELETE . "_2", maxLength: NULL, name: Constant::TEXT_DELETE . "_2", onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_DELETE, wrap: NULL);
        $output .= $buttonDelete->getHtml();
    } else if (Constant::MODE_DELETE == $mode) {
        $buttonDelete = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_CONFIRM_DELETE, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_CONFIRM_DELETE . "_2", maxLength: NULL, name: Constant::TEXT_CONFIRM_DELETE . "_2", onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_CONFIRM_DELETE, wrap: NULL);
        $output .= $buttonDelete->getHtml();
        $buttonDelete = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_CANCEL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_CANCEL . "_2", maxLength: NULL, name: Constant::TEXT_CANCEL . "_2", onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_CANCEL, wrap: NULL);
        $output .= $buttonDelete->getHtml();
    }
    $output .= "</div>\n";
    $hiddenMode = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::FIELD_NAME_MODE, maxLength: NULL, name: Constant::FIELD_NAME_MODE, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: $mode, wrap: NULL);
    $output .= $hiddenMode->getHtml();
    $hiddenSelectedRows = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: SELECTED_ROWS_FIELD_NAME, maxLength: NULL, name: SELECTED_ROWS_FIELD_NAME, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: $ids, wrap: NULL);
    $output .= $hiddenSelectedRows->getHtml();
    $id = ("" == $ids) ? NULL : (int) $ids;
    $result = $entityManager->getRepository(Constant::ENTITY_PAYOUTS)->getTableOutput(payoutId: $id, indexed: true);
    $resultHeaders = $entityManager->getRepository(Constant::ENTITY_PAYOUTS)->getTableOutput(payoutId: $id, indexed: false);
    $colFormats = array(array(5, "percentage", 0));
    $htmlTable = new HtmlTable(caption: NULL, class: NULL, colspan: NULL, columnFormat: $colFormats, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), delimiter: Constant::DELIMITER_DEFAULT, foreignKeys: NULL, header: true, hiddenAdditional: NULL, hiddenId: HIDDEN_ROW_FIELD_NAME, hideColumnIndexes: NULL, html: NULL, id: NULL, link: NULL, note: true, selectedRow: $ids, suffix: NULL, width: "100%");
    $output .= $htmlTable->getHtml(results: $result, resultHeaders: $resultHeaders);
    $output .= "<div class=\"buttons center\">\n";
    if (Constant::MODE_VIEW == $mode) {
        $buttonCreate = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_CREATE, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_CREATE, maxLength: NULL, name: Constant::TEXT_CREATE, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_CREATE, wrap: NULL);
        $output .= $buttonCreate->getHtml();
        $buttonModify = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG),accessKey:  Constant::ACCESSKEY_MODIFY, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: true, id: Constant::TEXT_MODIFY, maxLength: NULL, name: Constant::TEXT_MODIFY, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_MODIFY, wrap: NULL);
        $output .= $buttonModify->getHtml();
        $buttonDelete = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_DELETE, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: true, id: Constant::TEXT_DELETE, maxLength: NULL, name: Constant::TEXT_DELETE, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_DELETE, wrap: NULL);
        $output .= $buttonDelete->getHtml();
    } else if (Constant::MODE_DELETE == $mode) {
        $buttonDelete = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_CONFIRM_DELETE, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_CONFIRM_DELETE, maxLength: NULL, name: Constant::TEXT_CONFIRM_DELETE, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_CONFIRM_DELETE, wrap: NULL);
        $output .= $buttonDelete->getHtml();
        $buttonDelete = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_CANCEL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_CANCEL, maxLength: NULL, name: Constant::TEXT_CANCEL, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_CANCEL, wrap: NULL);
        $output .= $buttonDelete->getHtml();
    }
    $output .= "</div>\n";
}
$smarty->assign("content", $output);
$smarty->display("manage.tpl");
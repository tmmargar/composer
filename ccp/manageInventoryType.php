<?php
declare(strict_types = 1);
namespace ccp;
use Exception;
use Poker\Ccp\Model\Constant;
use Poker\Ccp\Model\FormControl;
use Poker\Ccp\Model\HtmlTable;
use Poker\Ccp\Model\InventoryType;
use Poker\Ccp\Model\Structure;
use Poker\Ccp\Utility\SessionUtility;
use Poker\Ccp\Entity\InventoryTypes;
use Poker\Ccp\Entity\Structures;
require_once "init.php";
define("INVENTORY_TYPE_NAME_FIELD_LABEL", "Name");
define("INVENTORY_TYPE_MIN_AMOUNT_FIELD_LABEL", "Min amount");
define("INVENTORY_TYPE_MAX_AMOUNT_FIELD_LABEL", "Max amount");
define("INVENTORY_TYPE_COMMENT_FIELD_LABEL", "Comment");
define("INVENTORY_TYPE_ID_FIELD_NAME", "inventoryTypeId");
define("INVENTORY_TYPE_NAME_FIELD_NAME", "inventoryTypeName");
define("INVENTORY_TYPE_MIN_AMOUNT_FIELD_NAME", "minAmount");
define("INVENTORY_TYPE_MAX_AMOUNT_FIELD_NAME", "maxAmount");
define("INVENTORY_TYPE_COMMENT_FIELD_NAME", "comment");
define("SELECTED_ROW_FIELD_NAME", "tempInventoryTypeTypeId");
define("DEFAULT_VALUE_INVENTORY_TYPE_ID", 0);
$smarty->assign("title", "Manage Inventory Type");
$smarty->assign("heading", "Manage Inventory Type");
$smarty->assign("style", "<link href=\"css/manageInventoryType.css\" rel=\"stylesheet\">");
$errors = NULL;
if (Constant::MODE_CREATE == $mode || Constant::MODE_MODIFY == $mode) {
    $ids = isset($_GET[INVENTORY_TYPE_ID_FIELD_NAME]) ? $_GET[INVENTORY_TYPE_ID_FIELD_NAME] : $ids;
    $resultList = $entityManager->getRepository(entityName: Constant::ENTITY_INVENTORY_TYPES)->getById(inventoryTypeId: (int) $ids);
    $output .= " <div class=\"buttons center\">\n";
    $buttonSave = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_SAVE, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_SAVE . "_2", maxLength: NULL, name: Constant::TEXT_SAVE . "_2", onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_SAVE, wrap: NULL);
    $output .= $buttonSave->getHtml();
    $buttonReset = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_RESET, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_RESET . "_2", maxLength: NULL, name: Constant::TEXT_RESET . "_2", onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_RESET, value: Constant::TEXT_RESET, wrap: NULL, noValidate: true);
    $output .= $buttonReset->getHtml();
    $buttonCancel = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_CANCEL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_CANCEL . "_2", maxLength: NULL, name: Constant::TEXT_CANCEL . "_2", onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_CANCEL, wrap: NULL, noValidate: true);
    $output .= $buttonCancel->getHtml();
    $output .= " </div>\n";
    $output .= "<div class=\"responsive responsive--2cols responsive--collapse\">";
    if (Constant::MODE_CREATE == $mode || (Constant::MODE_MODIFY == $mode && $ids != DEFAULT_VALUE_BLANK)) {
        $ctr = 0;
        $ary = explode(separator: Constant::DELIMITER_DEFAULT, string: $ids);
        foreach ($ary as $id) {
            $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . INVENTORY_TYPE_NAME_FIELD_NAME . "_" . $id . "\">" . INVENTORY_TYPE_NAME_FIELD_LABEL . ($id != "" ? " " . $id : "") . ": </div>\n";
            $textInventoryTypeName = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_NAME, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: INVENTORY_TYPE_NAME_FIELD_NAME . "_" . $id, maxLength: 30, name: INVENTORY_TYPE_NAME_FIELD_NAME . "_" . $id, onClick: NULL, placeholder: NULL, readOnly: false, required: true, rows: NULL, size: 30, suffix: NULL, type: FormControl::TYPE_INPUT_TEXTBOX, value: ((count($resultList) > 0) ? $resultList[$ctr]->getInventoryTypeName() : ""), wrap: NULL);
            $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $textInventoryTypeName->getHtml() . "</div>\n";
            $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . INVENTORY_TYPE_MIN_AMOUNT_FIELD_NAME . "_" . $id . "\">" . INVENTORY_TYPE_MIN_AMOUNT_FIELD_LABEL . ($id != "" ? " " . $id : "") . ": </div>\n";
            $textInventoryTypeMinAmount = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_MIN_AMOUNT, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: INVENTORY_TYPE_MIN_AMOUNT_FIELD_NAME . "_" . $id, maxLength: 2, name: INVENTORY_TYPE_MIN_AMOUNT_FIELD_NAME . "_" . $id, onClick: NULL, placeholder: NULL, readOnly: false, required: true, rows: NULL, size: 2, suffix: NULL, type: FormControl::TYPE_INPUT_NUMBER, value: (string) ((count($resultList) > 0) ? $resultList[$ctr]->getInventoryTypeMinAmount() : 0), wrap: NULL);
            $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $textInventoryTypeMinAmount->getHtml() . "</div>\n";
            $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . INVENTORY_TYPE_MAX_AMOUNT_FIELD_NAME . "_" . $id . "\">" . INVENTORY_TYPE_MAX_AMOUNT_FIELD_LABEL . ($id != "" ? " " . $id : "") . ": </div>\n";
            $textInventoryTypeMinAmount = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_MAX_AMOUNT, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: INVENTORY_TYPE_MAX_AMOUNT_FIELD_NAME . "_" . $id, maxLength: 2, name: INVENTORY_TYPE_MAX_AMOUNT_FIELD_NAME . "_" . $id, onClick: NULL, placeholder: NULL, readOnly: false, required: true, rows: NULL, size: 2, suffix: NULL, type: FormControl::TYPE_INPUT_NUMBER, value: (string) ((count($resultList) > 0) ? $resultList[$ctr]->getInventoryTypeMaxAmount() : 0), wrap: NULL);
            $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $textInventoryTypeMinAmount->getHtml() . "</div>\n";
            $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . INVENTORY_TYPE_COMMENT_FIELD_NAME . "_" . $id . "\">" . INVENTORY_TYPE_COMMENT_FIELD_LABEL . ($id != "" ? " " . $id : "") . ": </label></div>\n";
            $textBoxComment = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_COMMENT, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: INVENTORY_TYPE_COMMENT_FIELD_NAME . "_" . $id, maxLength: 200, name: INVENTORY_TYPE_COMMENT_FIELD_NAME . "_" . $id, onClick: NULL, placeholder: NULL, readOnly: false, required: false, rows: NULL, size: 20, suffix: NULL, type: FormControl::TYPE_INPUT_TEXTBOX, value: ((count($resultList) > 0) ? $resultList[$ctr]->getInventoryTypeComment() : ""), wrap: NULL);
            $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $textBoxComment->getHtml() . "</div>";
            $hiddenRow = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: HIDDEN_ROW_FIELD_NAME . "_" . $id, maxLength: NULL, name: HIDDEN_ROW_FIELD_NAME . "_" . $id, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: ((count($resultList) > 0) ? $resultList[$ctr]->getInventoryTypeId() : ""), wrap: NULL);
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
    $ary = explode(separator: Constant::DELIMITER_DEFAULT, string: $ids);
    foreach ($ary as $id) {
        if (Constant::MODE_SAVE_CREATE == $mode || Constant::MODE_SAVE_MODIFY == $mode) {
            $inventoryTypeName = (isset($_POST[INVENTORY_TYPE_NAME_FIELD_NAME . "_" . $id])) ? $_POST[INVENTORY_TYPE_NAME_FIELD_NAME . "_" . $id] : DEFAULT_VALUE_BLANK;
            $inventoryTypeMinAmount = (int) (isset($_POST[INVENTORY_TYPE_MIN_AMOUNT_FIELD_NAME . "_" . $id]) ? $_POST[INVENTORY_TYPE_MIN_AMOUNT_FIELD_NAME . "_" . $id] : 0);
            $inventoryTypeMaxAmount = (int) (isset($_POST[INVENTORY_TYPE_MAX_AMOUNT_FIELD_NAME . "_" . $id]) ? $_POST[INVENTORY_TYPE_MAX_AMOUNT_FIELD_NAME . "_" . $id] : 0);
            $inventoryTypeComment = isset($_POST[INVENTORY_TYPE_COMMENT_FIELD_NAME . "_" . $id]) ? "" == $_POST[INVENTORY_TYPE_COMMENT_FIELD_NAME . "_" . $id] ? NULL : $_POST[INVENTORY_TYPE_COMMENT_FIELD_NAME . "_" . $id] : NULL;
            if (Constant::MODE_SAVE_CREATE == $mode) {
                $it = new InventoryTypes();
                $it->setInventoryTypeComment(inventoryTypeComment: $inventoryTypeComment);
                $it->setInventoryTypeMinAmount(inventoryTypeMinAmount: $inventoryTypeMinAmount);
                $it->setInventoryTypeMaxAmount(inventoryTypeMaxAmount: $inventoryTypeMaxAmount);
                $it->setInventoryTypeName(inventoryTypeName: $inventoryTypeName);
                $entityManager->persist(entity: $it);
                try {
                    $entityManager->flush();
                } catch (Exception $e) {
                    $errors = $e->getMessage();
                }
                $resultList2 = $entityManager->getRepository(entityName: Constant::ENTITY_INVENTORY_TYPES)->getMaxId();
                $tempInventoryTypeId = (int) $resultList2[1];
            } else {
                $tempInventoryTypeId = (int) ((isset($_POST[HIDDEN_ROW_FIELD_NAME . "_" . $id])) ? $_POST[HIDDEN_ROW_FIELD_NAME . "_" . $id] : 0);
            }
            if (Constant::MODE_SAVE_MODIFY == $mode) {
                $it = $entityManager->find(className: Constant::ENTITY_INVENTORY_TYPES, id: $tempInventoryTypeId);
                $it->setInventoryTypeComment(inventoryTypeComment: $inventoryTypeComment);
                $it->setInventoryTypeMinAmount(inventoryTypeMinAmount: $inventoryTypeMinAmount);
                $it->setInventoryTypeMaxAmount(inventoryTypeMaxAmount: $inventoryTypeMaxAmount);
                $it->setInventoryTypeName(inventoryTypeName: $inventoryTypeName);
                $entityManager->persist(entity: $it);
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
        }
    }
    $ids = DEFAULT_VALUE_BLANK;
    $mode = Constant::MODE_VIEW;
}
if (Constant::MODE_VIEW == $mode || Constant::MODE_DELETE == $mode || Constant::MODE_CONFIRM == $mode) {
    if (Constant::MODE_CONFIRM == $mode) {
        if (DEFAULT_VALUE_BLANK != $ids) {
            $it = $entityManager->find(className: Constant::ENTITY_INVENTORY_TYPES, id: (int) $ids);
            $entityManager->remove(entity: $it);
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
    $result = $entityManager->getRepository(entityName: Constant::ENTITY_INVENTORY_TYPES)->getTableOutput(inventoryTypeId: $id, indexed: true);
    $resultHeaders = $entityManager->getRepository(entityName: Constant::ENTITY_INVENTORY_TYPES)->getTableOutput(inventoryTypeId: $id, indexed: false);
    $colFormats = array(array(5, "percentage", 0));
    $htmlTable = new HtmlTable(caption: NULL, class: NULL, colspan: NULL, columnFormat: $colFormats, debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), delimiter: Constant::DELIMITER_DEFAULT, foreignKeys: NULL, header: true, hiddenAdditional: NULL, hiddenId: HIDDEN_ROW_FIELD_NAME, hideColumnIndexes: NULL, html: NULL, id: NULL, link: NULL, note: true, selectedRow: $ids, suffix: NULL, width: "100%");
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
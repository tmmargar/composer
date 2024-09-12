<?php
declare(strict_types = 1);
namespace ccp;
use Exception;
use Poker\Ccp\Model\Constant;
use Poker\Ccp\Model\FormControl;
use Poker\Ccp\Model\FormOption;
use Poker\Ccp\Model\FormSelect;
use Poker\Ccp\Model\HtmlTable;
use Poker\Ccp\Model\Inventory;
use Poker\Ccp\Model\Structure;
use Poker\Ccp\Utility\SessionUtility;
use Poker\Ccp\Entity\Inventories;
use Poker\Ccp\Entity\Structures;
require_once "init.php";
define("INVENTORY_TYPE_ID_FIELD_LABEL", "Inventory type");
define("INVENTORY_TYPE_MIN_AMOUNT_FIELD_LABEL", "Min amount");
define("INVENTORY_TYPE_MAX_AMOUNT_FIELD_LABEL", "Max amount");
define("CURRENT_AMOUNT_FIELD_LABEL", "Current amount");
define("WARNING_AMOUNT_FIELD_LABEL", "Warning amount");
define("ORDER_AMOUNT_FIELD_LABEL", "Order amount");
define("INVENTORY_COMMENT_FIELD_LABEL", "Comment");
define("INVENTORY_ID_FIELD_NAME", "inventoryId");
define("INVENTORY_TYPE_ID_FIELD_NAME", "inventoryTypeId");
define("INVENTORY_TYPE_MIN_AMOUNT_FIELD_NAME", "minAmount");
define("INVENTORY_TYPE_MAX_AMOUNT_FIELD_NAME", "maxAmount");
define("CURRENT_AMOUNT_FIELD_NAME", "currentAmount");
define("WARNING_AMOUNT_FIELD_NAME", "warningAmount");
define("ORDER_AMOUNT_FIELD_NAME", "orderAmount");
define("INVENTORY_COMMENT_FIELD_NAME", "comment");
define("SELECTED_ROW_FIELD_NAME", "tempInventoryId");
define("DEFAULT_VALUE_INVENTORY_ID", 0);
$smarty->assign("title", "Manage Inventory");
$smarty->assign("heading", "Manage Inventory");
$smarty->assign("style", "<link href=\"css/manageInventory.css\" rel=\"stylesheet\">");
$errors = NULL;
if (Constant::MODE_CREATE == $mode || Constant::MODE_MODIFY == $mode) {
    $ids = isset($_GET[INVENTORY_ID_FIELD_NAME]) ? $_GET[INVENTORY_ID_FIELD_NAME] : $ids;
    $inventories = $entityManager->getRepository(entityName: Constant::ENTITY_INVENTORIES)->getById(inventoryId: (int) $ids);
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
        $ary = explode(Constant::DELIMITER_DEFAULT, $ids);
        foreach ($ary as $id) {
            $inventoryTypes = $entityManager->getRepository(entityName: Constant::ENTITY_INVENTORY_TYPES)->getNotConfigured(inventoryTypeId: Constant::MODE_MODIFY == $mode ? (int) $id : NULL);
            if (NULL !== $inventoryTypes) {
                $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . INVENTORY_TYPE_ID_FIELD_NAME . "_" . $id . "\">" . INVENTORY_TYPE_ID_FIELD_LABEL . ($id != "" ? " " . $id : "") . ": </label></div>\n";
                $selectInventoryType = new FormSelect(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_LIMIT_TYPE, class: NULL, disabled: false, id: INVENTORY_TYPE_ID_FIELD_NAME . "_" . $id, multiple: false, name: INVENTORY_TYPE_ID_FIELD_NAME . "_" . $id, onClick: NULL, readOnly: false, size: 1, suffix: NULL, value: NULL);
                $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $selectInventoryType->getHtml();
                $option = new FormOption(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), class: NULL, disabled: false, id: NULL, name: NULL, selectedValue: count($inventories) > 0 ? $inventories[$ctr]->getInventoryTypes()->getInventoryTypeId() : "", suffix: NULL, text: Constant::TEXT_NONE, value: DEFAULT_VALUE_BLANK);
                $output .= $option->getHtml();
                foreach ($inventoryTypes as $inventoryType) {
                    $option = new FormOption(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), class: NULL, disabled: false, id: NULL, name: NULL, selectedValue: count($inventories) > 0 ? $inventories[$ctr]->getInventoryTypes()->getInventoryTypeId() . Constant::DELIMITER_VALUE_DEFAULT . $inventoryType->getInventoryTypeMinAmount() . Constant::DELIMITER_VALUE_DEFAULT . $inventoryType->getInventoryTypeMaxAmount() : "", suffix: NULL, text: $inventoryType->getInventoryTypeName(), value: $inventoryType->getInventoryTypeId() . Constant::DELIMITER_VALUE_DEFAULT . $inventoryType->getInventoryTypeMinAmount() . Constant::DELIMITER_VALUE_DEFAULT . $inventoryType->getInventoryTypeMaxAmount());
                    $output .= $option->getHtml();
                }
                $output .= "     </select>\n";
                $output .= "    </div>\n";
            }

            $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . INVENTORY_TYPE_MIN_AMOUNT_FIELD_NAME . "_" . $id . "\">" . INVENTORY_TYPE_MIN_AMOUNT_FIELD_LABEL . ($id != "" ? " " . $id : "") . ": </div>\n";
            $textInventoryTypeMinAmount = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_MIN_AMOUNT, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: true, id: INVENTORY_TYPE_MIN_AMOUNT_FIELD_NAME . "_" . $id, maxLength: 2, name: INVENTORY_TYPE_MIN_AMOUNT_FIELD_NAME . "_" . $id, onClick: NULL, placeholder: NULL, readOnly: true, required: false, rows: NULL, size: 2, suffix: NULL, type: FormControl::TYPE_INPUT_NUMBER, value: (string) ((count($resultList) > 0) ? $inventories[$ctr]->getInventoryTypes()->getInventoryTypeMinAmount() : 0), wrap: NULL);
            $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $textInventoryTypeMinAmount->getHtml() . "</div>\n";
            $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . INVENTORY_TYPE_MAX_AMOUNT_FIELD_NAME . "_" . $id . "\">" . INVENTORY_TYPE_MAX_AMOUNT_FIELD_LABEL . ($id != "" ? " " . $id : "") . ": </div>\n";
            $textInventoryTypeMinAmount = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_MAX_AMOUNT, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: true, id: INVENTORY_TYPE_MAX_AMOUNT_FIELD_NAME . "_" . $id, maxLength: 2, name: INVENTORY_TYPE_MAX_AMOUNT_FIELD_NAME . "_" . $id, onClick: NULL, placeholder: NULL, readOnly: true, required: false, rows: NULL, size: 2, suffix: NULL, type: FormControl::TYPE_INPUT_NUMBER, value: (string) ((count($inventories) > 0) ? $inventories[$ctr]->getInventoryTypes()->getInventoryTypeMaxAmount() : 0), wrap: NULL);
            $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $textInventoryTypeMinAmount->getHtml() . "</div>\n";

            $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . CURRENT_AMOUNT_FIELD_NAME . "_" . $id . "\">" . CURRENT_AMOUNT_FIELD_LABEL . ($id != "" ? " " . $id : "") . ": </div>\n";
            $textCurrentAmount = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_CURRENT_AMOUNT, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: CURRENT_AMOUNT_FIELD_NAME . "_" . $id, maxLength: 2, name: CURRENT_AMOUNT_FIELD_NAME . "_" . $id, onClick: NULL, placeholder: NULL, readOnly: false, required: true, rows: NULL, size: 2, suffix: NULL, type: FormControl::TYPE_INPUT_NUMBER, value: (string) (count($inventories) > 0 ? $inventories[$ctr]->getInventoryCurrentAmount() : 0), wrap: NULL);
            $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $textCurrentAmount->getHtml() . "</div>\n";
            $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . WARNING_AMOUNT_FIELD_NAME . "_" . $id . "\">" . WARNING_AMOUNT_FIELD_LABEL . ($id != "" ? " " . $id : "") . ": </div>\n";
            $textWarningAmount = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_WARNING_AMOUNT, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: WARNING_AMOUNT_FIELD_NAME . "_" . $id, maxLength: 2, name: WARNING_AMOUNT_FIELD_NAME . "_" . $id, onClick: NULL, placeholder: NULL, readOnly: false, required: true, rows: NULL, size: 2, suffix: NULL, type: FormControl::TYPE_INPUT_NUMBER, value: (string) (count($inventories) > 0 ? $inventories[$ctr]->getInventoryWarningAmount() : 0), wrap: NULL);
            $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $textWarningAmount->getHtml() . "</div>\n";
            $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . ORDER_AMOUNT_FIELD_NAME . "_" . $id . "\">" . ORDER_AMOUNT_FIELD_LABEL . ($id != "" ? " " . $id : "") . ": </div>\n";
            $textOrderAmount = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_ORDER_AMOUNT, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: ORDER_AMOUNT_FIELD_NAME . "_" . $id, maxLength: 2, name: ORDER_AMOUNT_FIELD_NAME . "_" . $id, onClick: NULL, placeholder: NULL, readOnly: false, required: true, rows: NULL, size: 2, suffix: NULL, type: FormControl::TYPE_INPUT_NUMBER, value: (string) (count($inventories) > 0 ? $inventories[$ctr]->getInventoryOrderAmount() : 0), wrap: NULL);
            $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $textOrderAmount->getHtml() . "</div>\n";
            $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . INVENTORY_COMMENT_FIELD_NAME . "_" . $id . "\">" . INVENTORY_COMMENT_FIELD_LABEL . ($id != "" ? " " . $id : "") . ": </label></div>\n";
            $textBoxComment = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_COMMENT, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: INVENTORY_COMMENT_FIELD_NAME . "_" . $id, maxLength: 200, name: INVENTORY_COMMENT_FIELD_NAME . "_" . $id, onClick: NULL, placeholder: NULL, readOnly: false, required: false, rows: NULL, size: 20, suffix: NULL, type: FormControl::TYPE_INPUT_TEXTBOX, value: ((count($inventories) > 0) ? $inventories[$ctr]->getInventoryComment() : ""), wrap: NULL);
            $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $textBoxComment->getHtml() . "</div>";
            $hiddenRow = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: HIDDEN_ROW_FIELD_NAME . "_" . $id, maxLength: NULL, name: HIDDEN_ROW_FIELD_NAME . "_" . $id, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: (count($inventories) > 0 ? $inventories[$ctr]->getInventoryId() : ""), wrap: NULL);
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
            $inventoryTypeId = (isset($_POST[INVENTORY_TYPE_ID_FIELD_NAME . "_" . $id])) ? $_POST[INVENTORY_TYPE_ID_FIELD_NAME . "_" . $id] : DEFAULT_VALUE_BLANK;
            $currentAmount = (int) (isset($_POST[CURRENT_AMOUNT_FIELD_NAME . "_" . $id]) ? $_POST[CURRENT_AMOUNT_FIELD_NAME . "_" . $id] : 0);
            $warningAmount = (int) (isset($_POST[WARNING_AMOUNT_FIELD_NAME . "_" . $id]) ? $_POST[WARNING_AMOUNT_FIELD_NAME . "_" . $id] : 0);
            $orderAmount = (int) (isset($_POST[ORDER_AMOUNT_FIELD_NAME . "_" . $id]) ? $_POST[ORDER_AMOUNT_FIELD_NAME . "_" . $id] : 0);
            $inventoryComment = isset($_POST[INVENTORY_COMMENT_FIELD_NAME . "_" . $id]) ? "" == $_POST[INVENTORY_COMMENT_FIELD_NAME . "_" . $id] ? NULL : $_POST[INVENTORY_COMMENT_FIELD_NAME . "_" . $id] : NULL;
            if (Constant::MODE_SAVE_CREATE == $mode) {
                $in = new Inventories();
                $in->setInventoryComment(inventoryComment: $inventoryComment);
                $in->setInventoryCurrentAmount(inventoryCurrentAmount: $currentAmount);
                $in->setInventoryWarningAmount(inventoryWarningAmount: $warningAmount);
                $in->setInventoryOrderAmount(inventoryOrderAmount: $orderAmount);
                $inventoryType = $entityManager->find(className: Constant::ENTITY_INVENTORY_TYPES, id: $inventoryTypeId);
                $in->setInventoryTypes(inventoryTypes: $inventoryType);
                $entityManager->persist(entity: $in);
                try {
                    $entityManager->flush();
                } catch (Exception $e) {
                    $errors = $e->getMessage();
                }
                $resultList2 = $entityManager->getRepository(entityName: Constant::ENTITY_INVENTORIES)->getMaxId();
                $tempInventoryId = (int) $resultList2[1];
            } else {
                $tempInventoryId = (int) ((isset($_POST[HIDDEN_ROW_FIELD_NAME . "_" . $id])) ? $_POST[HIDDEN_ROW_FIELD_NAME . "_" . $id] : 0);
            }
            if (Constant::MODE_SAVE_MODIFY == $mode) {
                $in = $entityManager->find(className: Constant::ENTITY_INVENTORIES, id: $tempInventoryId);
                $in->setInventoryComment(inventoryComment: $inventoryComment);
                $in->setInventoryCurrentAmount(inventoryCurrentAmount: $currentAmount);
                $in->setInventoryWarningAmount(inventoryWarningAmount: $warningAmount);
                $in->setInventoryOrderAmount(inventoryOrderAmount: $orderAmount);
                $inventoryType = $entityManager->find(className: Constant::ENTITY_INVENTORY_TYPES, id: $inventoryTypeId);
                $in->setInventoryTypes(inventoryTypes: $inventoryType);
                $entityManager->persist(entity: $in);
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
            $in = $entityManager->find(className: Constant::ENTITY_INVENTORIES, id: (int) $ids);
            $entityManager->remove(entity: $in);
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
    $result = $entityManager->getRepository(entityName: Constant::ENTITY_INVENTORIES)->getTableOutput(inventoryId: $id, indexed: true);
    $resultHeaders = $entityManager->getRepository(entityName: Constant::ENTITY_INVENTORIES)->getTableOutput(inventoryId: $id, indexed: false);
    $colFormats = NULL;
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
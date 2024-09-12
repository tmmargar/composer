<?php
declare(strict_types = 1);
namespace ccp;
use DateTime;
use Exception;
use Poker\Ccp\Model\Constant;
use Poker\Ccp\Model\FormControl;
use Poker\Ccp\Model\FormOption;
use Poker\Ccp\Model\FormSelect;
use Poker\Ccp\Model\HtmlTable;
use Poker\Ccp\Utility\SessionUtility;
use Poker\Ccp\Entity\Groups;
use Poker\Ccp\Entity\GroupPayouts;
use Poker\Ccp\Entity\Payouts;
require_once "init.php";
define("GROUP_FIELD_LABEL", "Group");
define("PAYOUT_FIELD_LABEL", "Payout");
define("GROUP_ID_FIELD_NAME", "groupId");
define("GROUP_NAME_FIELD_NAME", "groupName");
define("PAYOUT_ID_FIELD_NAME", "payoutId");
define("PAYOUT_NAME_FIELD_NAME", "payoutName");
define("SELECTED_ROW_FIELD_NAME", "tempGroupPayoutId");
define("ORIGINAL_PAYOUT_ID_FIELD_NAME", "payoutIdOriginal");
define("ORIGINAL_GROUP_ID_FIELD_NAME", "groupIdOriginal");
define("DEFAULT_VALUE_GROUP_ID", 0);
define("DEFAULT_VALUE_PAYOUT_ID", 0);
$smarty->assign("title", "Manage Group Payout");
$smarty->assign("heading", "Manage Group Payout");
$smarty->assign("style", "<link href=\"css/manageGroupPayout.css\" rel=\"stylesheet\">");
$errors = NULL;
$aryGroupPayoutIds = explode(separator: Constant::DELIMITER_VALUE_DEFAULT, string: $ids);
if (Constant::MODE_CREATE == $mode || Constant::MODE_MODIFY == $mode) {
    $params = Constant::MODE_MODIFY == $mode ? array((int) $aryGroupPayoutIds[0], (int) $aryGroupPayoutIds[1]) : array((int) 0, (int) 0);
    $resultList = $entityManager->getRepository(entityName: Constant::ENTITY_GROUP_PAYOUTS)->getById(groupId: Constant::MODE_MODIFY == $mode ? (int) $aryGroupPayoutIds[0] : 0, payoutId: Constant::MODE_MODIFY == $mode ? (int) $aryGroupPayoutIds[1] : 0);
    if (Constant::MODE_CREATE == $mode || (Constant::MODE_MODIFY == $mode && $ids != DEFAULT_VALUE_BLANK)) {
        $output .= " <div class=\"buttons center\">\n";
        $buttonSave = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_SAVE, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_SAVE . "_2", maxLength: NULL, name: Constant::TEXT_SAVE . "_2", onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_SAVE, wrap: NULL);
        $output .= $buttonSave->getHtml();
        $buttonReset = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_RESET, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_RESET . "_2", maxLength: NULL, name: Constant::TEXT_RESET . "_2", onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_RESET, value: Constant::TEXT_RESET, wrap: NULL, noValidate: true);
        $output .= $buttonReset->getHtml();
        $buttonCancel = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_CANCEL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_CANCEL . "_2", maxLength: NULL, name: Constant::TEXT_CANCEL . "_2", onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_CANCEL, wrap: NULL, noValidate: true);
        $output .= $buttonCancel->getHtml();
        $output .= " </div>\n";
        $output .= "<div class=\"responsive responsive--2cols responsive--collapse\">";
        $groupId = isset($_POST[GROUP_ID_FIELD_NAME . "_"]) ? $_POST[GROUP_ID_FIELD_NAME . "_"] : DEFAULT_VALUE_GROUP_ID;
        $payoutId = isset($_POST[PAYOUT_ID_FIELD_NAME . "_"]) ? $_POST[PAYOUT_ID_FIELD_NAME . "_"] : DEFAULT_VALUE_PAYOUT_ID;
        $ctr = 0;
        $ary = explode(separator: Constant::DELIMITER_DEFAULT, string: $ids);
        foreach ($ary as $id) {
            $arySplit = explode(separator: Constant::DELIMITER_VALUE_DEFAULT, string: $id);
            $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . GROUP_ID_FIELD_NAME . "_" . $id . "\">" . GROUP_FIELD_LABEL . ($arySplit[0] != "" ? " " . $arySplit[0] : "") . ": </div>\n";
            $resultList2 = $entityManager->getRepository(entityName: Constant::ENTITY_GROUPS)->getById(groupId: NULL);
            if (count($resultList2) > 0) {
                $output .= " <div class=\"responsive-cell responsive-cell-value\">";
                $selectGroup = new FormSelect(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_GROUP, class: NULL, disabled: false, id: GROUP_ID_FIELD_NAME . "_" . $arySplit[0], multiple: false, name: GROUP_ID_FIELD_NAME . "_" . $arySplit[0], onClick: NULL, readOnly: false, size: 1, suffix: NULL, value: NULL);
                $output .= $selectGroup->getHtml();
                $option = new FormOption(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), class: NULL, disabled: false, id: NULL, name: NULL, selectedValue: $groupId, suffix: NULL, text: Constant::TEXT_NONE, value: "");
                $output .= $option->getHtml();
                foreach ($resultList2 as $group) {
                    $option = new FormOption(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), class: NULL, disabled: false, id: NULL, name: NULL, selectedValue: count($resultList) > 0 ? $resultList[$ctr]->getGroups()->getGroupId() : "", suffix: NULL, text: $group->getGroupName(), value: $group->getGroupId());
                    $output .= $option->getHtml();
                }
                $output .= "  </select>\n";
                $output .= " </div>\n";
            }
            $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . PAYOUT_ID_FIELD_NAME . "_" . $id . "\">" . PAYOUT_FIELD_LABEL . ($arySplit[0] != "" ? " " . $arySplit[0] : "") . ": </div>\n";
            $resultList2 = $entityManager->getRepository(entityName: Constant::ENTITY_PAYOUTS)->getById(payoutId: NULL);
            if (count($resultList2) > 0) {
                $output .= " <div class=\"responsive-cell responsive-cell-value\">";
                $selectGroup = new FormSelect(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_PAYOUT, class: NULL, disabled: false, id: PAYOUT_ID_FIELD_NAME . "_" . $arySplit[0], multiple: false, name: PAYOUT_ID_FIELD_NAME . "_" . $arySplit[0], onClick: NULL, readOnly: false, size: 1, suffix: NULL, value: NULL);
                $output .= $selectGroup->getHtml();
                $option = new FormOption(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), class: NULL, disabled: false, id: NULL, name: NULL, selectedValue: $payoutId, suffix: NULL, text: Constant::TEXT_NONE, value: "");
                $output .= $option->getHtml();
                foreach ($resultList2 as $payout) {
                    //TODO: handle array payout
                    $option = new FormOption(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), class: NULL, disabled: false, id: NULL, name: NULL, selectedValue: count($resultList) > 0 ? $resultList[$ctr]->getPayouts()->getPayoutId() : "", suffix: NULL, text: $payout->getPayoutName(), value: $payout->getPayoutId());
                    $output .= $option->getHtml();
                }
                $output .= "  </select>\n";
                $output .= " </div>\n";
            }
            $hiddenOriginalGroupId = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: ORIGINAL_GROUP_ID_FIELD_NAME . "_" . $arySplit[0], maxLength: NULL, name: ORIGINAL_GROUP_ID_FIELD_NAME . "_" . $arySplit[0], onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: ((count($resultList) > 0) ? $resultList[$ctr]->getGroups()->getGroupId() : ""), wrap: NULL);
            $output .= $hiddenOriginalGroupId->getHtml();
            $hiddenOriginalPayoutId = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: ORIGINAL_PAYOUT_ID_FIELD_NAME . "_" . $arySplit[0], maxLength: NULL, name: ORIGINAL_PAYOUT_ID_FIELD_NAME . "_" . $arySplit[0], onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: ((count($resultList) > 0) ? $resultList[$ctr]->getPayouts()->getPayoutId() : ""), wrap: NULL);
            $output .= $hiddenOriginalPayoutId->getHtml();
            $hiddenRow = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: HIDDEN_ROW_FIELD_NAME . "_" . $arySplit[0], maxLength: NULL, name: HIDDEN_ROW_FIELD_NAME . "_" . $arySplit[0], onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: ((count($resultList) > 0) ? $resultList[$ctr]->getGroups()->getGroupId() . Constant::DELIMITER_VALUE_DEFAULT . $resultList[$ctr]->getPayouts()->getPayoutId() : ""), wrap: NULL);
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
    $ary = explode(Constant::DELIMITER_DEFAULT, $ids);
    foreach ($ary as $id) {
        $arySplit = explode(separator: Constant::DELIMITER_VALUE_DEFAULT, string: $id);
        $groupId = (int) ((isset($_POST[GROUP_ID_FIELD_NAME . "_" . $arySplit[0]])) ? $_POST[GROUP_ID_FIELD_NAME . "_" . $arySplit[0]] : DEFAULT_VALUE_GROUP_ID);
        $payoutId = (int) ((isset($_POST[PAYOUT_ID_FIELD_NAME . "_" . $arySplit[0]])) ? $_POST[PAYOUT_ID_FIELD_NAME . "_" . $arySplit[0]] : DEFAULT_VALUE_PAYOUT_ID);
        $originalGroupId = (int) ((isset($_POST[ORIGINAL_GROUP_ID_FIELD_NAME . "_" . $arySplit[0]])) ? $_POST[ORIGINAL_GROUP_ID_FIELD_NAME . "_" . $arySplit[0]] : DEFAULT_VALUE_GROUP_ID);
        $originalPayoutId = (int) ((isset($_POST[ORIGINAL_PAYOUT_ID_FIELD_NAME . "_" . $arySplit[0]])) ? $_POST[ORIGINAL_PAYOUT_ID_FIELD_NAME . "_" . $arySplit[0]] : DEFAULT_VALUE_PAYOUT_ID);
        if (Constant::MODE_SAVE_CREATE == $mode) {
            $grp = new GroupPayouts();
            $groupsFind = $entityManager->find(className: Constant::ENTITY_GROUPS, id: $groupId);
            $grp->setGroups(groups: $groupsFind);
            $payoutsFind = $entityManager->find(className: Constant::ENTITY_PAYOUTS, id: $payoutId);
            $grp->setPayouts(payouts: $payoutsFind);
            $entityManager->persist(entity: $grp);
            try {
                $entityManager->flush();
            } catch (Exception $e) {
                $errors = $e->getMessage();
            }
        } elseif (Constant::MODE_SAVE_MODIFY == $mode) {
            $params = array($groupId, $payoutId, $originalGroupId, $originalPayoutId);
            $groupsFind = $entityManager->find(className: Constant::ENTITY_GROUPS, id: $originalGroupId);
            $payoutsFind = $entityManager->find(className: Constant::ENTITY_PAYOUTS, id: $originalPayoutId);
            $groupPayoutsFind = $entityManager->getRepository(entityName: Constant::ENTITY_GROUP_PAYOUTS)->findOneBy(array("groups" => $groupsFind, "payouts" => $payoutsFind));
            $groupsFind = $entityManager->find(className: Constant::ENTITY_GROUPS, id: $groupId);
            $groupPayoutsFind->setGroups(groups: $groupsFind);
            $payoutsFind = $entityManager->find(className: Constant::ENTITY_PAYOUTS, id: $payoutId);
            $groupPayoutsFind->setPayouts(payouts: $payoutsFind);
            $entityManager->persist(entity: $groupPayoutsFind);
            try {
                $entityManager->flush();
            } catch (Exception $e) {
                $errors = $e->getMessage();
            }
            $aryGroupPayoutIds = array("", "");
        }
        if (isset($errors)) {
            $output .=
                "<script type=\"module\">\n" .
                "  import { dataTable, display, input } from \"./scripts/import.js\";\n" .
                "  display.showErrors({errors: [ \"" . $errors . "\" ]});\n" .
                "</script>\n";
        }
    }
    $ids = DEFAULT_VALUE_BLANK;
    $mode = Constant::MODE_VIEW;
}
if (Constant::MODE_VIEW == $mode || Constant::MODE_DELETE == $mode || Constant::MODE_CONFIRM == $mode) {
    if (Constant::MODE_CONFIRM == $mode) {
        if (DEFAULT_VALUE_BLANK != $ids) {
            $params = array((int) $aryGroupPayoutIds[0], (int) $aryGroupPayoutIds[1]);
            $groupsFind = $entityManager->find(className: Constant::ENTITY_GROUPS, id: (int) $aryGroupPayoutIds[0]);
            $payoutsFind = $entityManager->find(className: Constant::ENTITY_PAYOUTS, id: (int) $aryGroupPayoutIds[1]);
            $groupPayoutsFind = $entityManager->getRepository(entityName: Constant::ENTITY_GROUP_PAYOUTS)->findOneBy(array("groups" => $groupsFind, "payouts" => $payoutsFind));
            $entityManager->remove(entity: $groupPayoutsFind);
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
            $aryGroupPayoutIds = array(DEFAULT_VALUE_BLANK);
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
    $params = array("" == $aryGroupPayoutIds[0] ? NULL : (int) $aryGroupPayoutIds[0], "" == $aryGroupPayoutIds[0] ? NULL : (int) $aryGroupPayoutIds[1]);
    $result = $entityManager->getRepository(entityName: Constant::ENTITY_GROUP_PAYOUTS)->getTableOutput(groupId: "" == $aryGroupPayoutIds[0] ? NULL : (int) $aryGroupPayoutIds[0], payoutId: "" == $aryGroupPayoutIds[0] ? NULL : (int) $aryGroupPayoutIds[1], indexed: true);
    $resultHeaders = $entityManager->getRepository(entityName: Constant::ENTITY_GROUP_PAYOUTS)->getTableOutput(groupId: "" == $aryGroupPayoutIds[0] ? NULL : (int) $aryGroupPayoutIds[0], payoutId: "" == $aryGroupPayoutIds[0] ? NULL : (int) $aryGroupPayoutIds[1], indexed: false);
    $link = array(array(1, 3), array("manageGroup.php", array("groupId", "mode"), array(0, "modify"), 1), array("managePayout.php", array("payoutId", "mode"), array(2, "modify"), 3));
    $htmlTable = new HtmlTable(caption: NULL, class: NULL, colspan: NULL, columnFormat: NULL, debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), delimiter: Constant::DELIMITER_DEFAULT, foreignKeys: NULL, header: true, hiddenAdditional: NULL, hiddenId: HIDDEN_ROW_FIELD_NAME, hideColumnIndexes: NULL, html: NULL, id: NULL, link: $link, note: true, selectedRow: $ids, suffix: NULL, width: "100%");
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
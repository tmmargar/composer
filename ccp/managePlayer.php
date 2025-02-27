<?php
declare(strict_types = 1);
namespace ccp;
use DateTime;
use Exception;
use Poker\Ccp\Model\Constant;
use Poker\Ccp\Model\FormBase;
use Poker\Ccp\Model\FormControl;
use Poker\Ccp\Model\HtmlTable;
use Poker\Ccp\Utility\DateTimeUtility;
use Poker\Ccp\Utility\SessionUtility;
use Poker\Ccp\Entity\Players;
require_once "init.php";
define("FIRST_NAME_FIELD_LABEL", "First name");
define("LAST_NAME_FIELD_LABEL", "Last name");
define("PLAYERNAME_FIELD_LABEL", "Username");
define("PASSWORD_FIELD_LABEL", "Password");
define("EMAIL_FIELD_LABEL", "Email");
define("PHONE_FIELD_LABEL", "Phone");
define("ADMINISTRATOR_FIELD_LABEL", "Administrator");
define("ACTIVE_FIELD_LABEL", "Active");
define("REGISTRATION_DATE_FIELD_LABEL", "Registration Date");
define("APPROVAL_DATE_FIELD_LABEL", "Approval Date");
define("APPROVAL_PLAYER_FIELD_LABEL", "Approval Player");
define("REJECTION_DATE_FIELD_LABEL", "Rejection Date");
define("REJECTION_PLAYER_FIELD_LABEL", "Rejection Player");
define("PLAYER_ID_FIELD_NAME", "playerId");
define("FIRST_NAME_FIELD_NAME", "firstName");
define("LAST_NAME_FIELD_NAME", "lastName");
define("PLAYERNAME_FIELD_NAME", "username");
define("PASSWORD_FIELD_NAME", "password");
define("EMAIL_FIELD_NAME", "email");
define("PHONE_FIELD_NAME", "phone");
define("ADMINISTRATOR_FIELD_NAME", "administrator");
define("ACTIVE_FIELD_NAME", "active");
define("REGISTRATION_DATE_FIELD_NAME", "registrationDate");
define("APPROVAL_DATE_FIELD_NAME", "approvalDate");
define("APPROVAL_PLAYER_FIELD_NAME", "approvalPlayer");
define("REJECTION_DATE_FIELD_NAME", "rejectionDate");
define("REJECTION_PLAYER_FIELD_NAME", "rejectionPlayer");
define("DEFAULT_VALUE_ADMINISTRATOR", false);
define("DEFAULT_VALUE_PHONE", 0);
define("DEFAULT_VALUE_ACTIVE_CREATE", true);
define("DEFAULT_VALUE_ACTIVE", false);
define("DEFAULT_VALUE_NUM_ROWS", 20);
$smarty->assign("title", "Manage Player");
$smarty->assign("heading", "Manage Player");
$smarty->assign("style", "<link href=\"css/managePlayer.css\" rel=\"stylesheet\">");
$errors = NULL;
if (Constant::MODE_CREATE == $mode || Constant::MODE_MODIFY == $mode) {
    $ids = isset($_GET[PLAYER_ID_FIELD_NAME]) ? $_GET[PLAYER_ID_FIELD_NAME] : $ids;
    $resultList = $entityManager->getRepository(entityName: Constant::ENTITY_PLAYERS)->getById(playerId: (int) $ids);
    if (count(value: $resultList) > 0) {
        // doctrine ORM stores 1 instance of entity so need to refresh because called in navigation
        $entityManager->refresh(entity: $resultList[0]);
    }
    $output .= " <div class=\"buttons center\">\n";
    $buttonSave = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_SAVE, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_SAVE . "_2", maxLength: NULL, name: Constant::TEXT_SAVE . "_2", onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_SAVE, wrap: NULL, noValidate: false);
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
            $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . FIRST_NAME_FIELD_NAME . "_" . $id . "\">" . FIRST_NAME_FIELD_LABEL . ($id != "" ? " " . $id : "") . ": </label></div>\n";
            $textBoxName = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_FIRST_NAME, autoComplete: NULL, autoFocus: true, checked: NULL, class: NULL, cols: NULL, disabled: false, id: FIRST_NAME_FIELD_NAME . "_" . $id, maxLength: 30, name: FIRST_NAME_FIELD_NAME . "_" . $id, onClick: NULL, placeholder: NULL, readOnly: false, required: true, rows: NULL, size: 25, suffix: NULL, type: FormControl::TYPE_INPUT_TEXTBOX, value: ((count($resultList) > 0) ? $resultList[$ctr]->getPlayerFirstName() : ""), wrap: NULL, noValidate: false);
            $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $textBoxName->getHtml() . "</div>";
            $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . LAST_NAME_FIELD_NAME . "_" . $id . "\">" . LAST_NAME_FIELD_LABEL . ($id != "" ? " " . $id : "") . ": </div>\n";
            $textBoxName = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_LAST_NAME, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: LAST_NAME_FIELD_NAME . "_" . $id, maxLength: 30, name: LAST_NAME_FIELD_NAME . "_" . $id, onClick: NULL, placeholder: NULL, readOnly: false, required: true, rows: NULL, size: 25, suffix: NULL, type: FormControl::TYPE_INPUT_TEXTBOX, value: ((count($resultList) > 0) ? $resultList[$ctr]->getPlayerLastName() : ""), wrap: NULL, noValidate: false);
            $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $textBoxName->getHtml() . "</div>";
            $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . PLAYERNAME_FIELD_NAME . "_" . $id . "\">" . PLAYERNAME_FIELD_LABEL . ($id != "" ? " " . $id : "") . ": </div>\n";
            $textBoxName = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_LOCATION_NAME, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: PLAYERNAME_FIELD_NAME . "_" . $id, maxLength: 30, name: PLAYERNAME_FIELD_NAME . "_" . $id, onClick: NULL, placeholder: NULL, readOnly: false, required: true, rows: NULL, size: 25, suffix: NULL, type: FormControl::TYPE_INPUT_TEXTBOX, value: ((count($resultList) > 0) ? $resultList[$ctr]->getPlayerUsername() : ""), wrap: NULL, noValidate: false);
            $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $textBoxName->getHtml() . "</div>";
            $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . PASSWORD_FIELD_NAME . "_" . $id . "\">" . PASSWORD_FIELD_LABEL .
            ($id != "" ? " " . $id : "") . ": </div>\n";
            $textBoxPassword = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_PASSWORD, autoComplete: "new-password", autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: PASSWORD_FIELD_NAME . "_" . $id, maxLength: NULL, name: PASSWORD_FIELD_NAME . "_" . $id, onClick: NULL, placeholder: NULL, readOnly: false, required: false, rows: NULL, size: 25, suffix: NULL, type: FormControl::TYPE_INPUT_PASSWORD, value: "", wrap: NULL, noValidate: false);
            $output .= " <div class=\"responsive-cell responsive-cell-value\">" . "  <div id=\"passwordDiv\"" . (Constant::MODE_MODIFY == $mode ? " style=\"display: none;\"" : "") . ">" .
            $textBoxPassword->getHtml() . "</div>\n";
            if (Constant::MODE_MODIFY == $mode) {
                $output .= " <div id=\"passwordLinkDiv\">";
                $output .=
                    "<script type=\"module\">\n" . "  import { dataTable, display, input } from \"./scripts/import.js\";\n" .
                    "  document.querySelector(\"#password_link\").addEventListener(\"click\", (evt) => input.showHideToggle({aryId: ['passwordLinkDiv', 'passwordDiv'], idFocus: '" . PASSWORD_FIELD_NAME . "_" . $id . "'}));\n" .
                    "</script>\n";
                $output .= "  <a href=\"#\" id=\"password_link\">Click to enter new password</a>\n";
                $output .= " </div>\n";
            }
            $output .= " </div>\n";
            $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . EMAIL_FIELD_NAME . "_" . $id . "\">" . EMAIL_FIELD_LABEL . ($id != "" ? " " . $id : "") . ": </div>\n";
            $textBoxEmail = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_EMAIL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: EMAIL_FIELD_NAME . "_" . $id, maxLength: 100, name: EMAIL_FIELD_NAME . "_" . $id, onClick: NULL, placeholder: NULL, readOnly: false, required: true, rows: NULL, size: 25, suffix: NULL, type: FormControl::TYPE_INPUT_EMAIL, value: ((count($resultList) > 0) ? $resultList[$ctr]->getPlayerEmail() : ""), wrap: NULL, noValidate: false);
            $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $textBoxEmail->getHtml() . "</div>";
            $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . PHONE_FIELD_NAME . "_" . $id . "\">" . PHONE_FIELD_LABEL . ($id != "" ? " " . $id : "") . ": </div>\n";
            $textBoxPhone = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_PHONE, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: PHONE_FIELD_NAME . "_" . $id, maxLength: NULL, name: PHONE_FIELD_NAME . "_" . $id, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: 12, suffix: NULL, type: FormControl::TYPE_INPUT_TELEPHONE, value: ((count($resultList) > 0) ? $resultList[$ctr]->getPlayerPhone() == 0 ? "" : $resultList[$ctr]->getPlayerPhone() : ""), wrap: NULL, noValidate: false);
            $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $textBoxPhone->getHtml() . "</div>";
            if (SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_ADMINISTRATOR) == Constant::FLAG_YES_DATABASE) {
                $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . ADMINISTRATOR_FIELD_NAME . "_" . $id . "\">" . ADMINISTRATOR_FIELD_LABEL . ($id != "" ? " " . $id : "") . ": </div>\n";
                $checkboxAdministrator = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: ((count($resultList) > 0) && $resultList[$ctr]->getPlayerAdministratorFlag() ? true : false), class: NULL, cols: NULL, disabled: false, id: ADMINISTRATOR_FIELD_NAME . "_" . $id, maxLength: NULL, name: ADMINISTRATOR_FIELD_NAME . "_" . $id, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_CHECKBOX, value: (string) Constant::FLAG_YES_DATABASE, wrap: NULL, noValidate: false);
                $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $checkboxAdministrator->getHtml() . "</div>";
                $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . ACTIVE_FIELD_NAME . "_" . $id . "\">" . ACTIVE_FIELD_LABEL . ($id != "" ? " " . $id : "") . ": </div>\n";
                $checkboxActive = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: (((count($resultList) > 0) && $resultList[$ctr]->getPlayerActiveFlag() || Constant::MODE_CREATE == $mode) ? true : false), class: NULL, cols: NULL, disabled: false, id: ACTIVE_FIELD_NAME . "_" . $id, maxLength: NULL, name: ACTIVE_FIELD_NAME . "_" . $id, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_CHECKBOX, value: (string) Constant::FLAG_YES_DATABASE, wrap: NULL, noValidate: false);
                $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $checkboxActive->getHtml() . "</div>";
            }
            if (Constant::MODE_MODIFY == $mode) {
                $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . REGISTRATION_DATE_FIELD_NAME . "_" . $id . "\">" . REGISTRATION_DATE_FIELD_LABEL . ($id != "" ? " " . $id : "") . ": </div>\n";
                $registrationDateTime = $resultList[$ctr]->getPlayerRegistrationDate();
                $textRegistrationDate = new FormBase(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), class: NULL, disabled: true, id: REGISTRATION_DATE_FIELD_NAME . "_" . $id, name: REGISTRATION_DATE_FIELD_NAME . "_" . $id, suffix: NULL, value: DateTimeUtility::formatDisplayDateTime(value: $registrationDateTime));
                $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $textRegistrationDate->getValue() . "</div>";
                $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . APPROVAL_DATE_FIELD_NAME . "_" . $id . "\">" . APPROVAL_DATE_FIELD_LABEL . ($id != "" ? " " . $id : "") . ": </div>\n";
                $approvalDateTime = $resultList[$ctr]->getPlayerApprovalDate();
                $textApprovalDate = new FormBase(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), class: NULL, disabled: true, id: APPROVAL_DATE_FIELD_NAME . "_" . $id, name: APPROVAL_DATE_FIELD_NAME . "_" . $id, suffix: NULL, value: NULL === $approvalDateTime ? NULL : DateTimeUtility::formatDisplayDateTime(value: $approvalDateTime));
                $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $textApprovalDate->getValue() . "</div>";
                $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . APPROVAL_PLAYER_FIELD_NAME . "_" . $id . "\">" . APPROVAL_PLAYER_FIELD_LABEL . ($id != "" ? " " . $id : "") . ": </div>\n";
                $textApprovalPlayer = new FormBase(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), class: NULL, disabled: true, id: APPROVAL_PLAYER_FIELD_NAME . "_" . $id, name: APPROVAL_PLAYER_FIELD_NAME . "_" . $id, suffix: NULL, value: NULL === $resultList[$ctr]->getPlayerApproval() ? NULL : $resultList[$ctr]->getPlayerApproval()->getPlayerName());
                $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $textApprovalPlayer->getValue() . "</div>";
                $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . REJECTION_DATE_FIELD_NAME . "_" . $id . "\">" . REJECTION_DATE_FIELD_LABEL . ($id != "" ? " " . $id : "") . ": </div>\n";
                $rejectionDateTime = $resultList[$ctr]->getPlayerRejectionDate();
                $textRejectionDate = new FormBase(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), class: NULL, disabled: true, id: REJECTION_DATE_FIELD_NAME . "_" . $id, name: REJECTION_DATE_FIELD_NAME . "_" . $id, suffix: NULL, value: NULL === $rejectionDateTime ? NULL : DateTimeUtility::formatDisplayDateTime(value: $rejectionDateTime));
                $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $textRejectionDate->getValue() . "</div>";
                $output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . REJECTION_PLAYER_FIELD_NAME . "_" . $id . "\">" . REJECTION_PLAYER_FIELD_LABEL . ($id != "" ? " " . $id : "") . ": </div>\n";
                $textRejectionPlayer = new FormBase(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), class: NULL, disabled: true, id: REJECTION_PLAYER_FIELD_NAME . "_" . $id, name: REJECTION_PLAYER_FIELD_NAME . "_" . $id, suffix: NULL, value: NULL === $resultList[$ctr]->getPlayerRejection() ? NULL : $resultList[$ctr]->getPlayerRejection()->getPlayerName());
                $output .= " <div class=\"responsive-cell responsive-cell-value\">" . $textRejectionPlayer->getValue() . "</div>";
            }
            $hiddenRow = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: HIDDEN_ROW_FIELD_NAME . "_" . $id, maxLength: NULL, name: HIDDEN_ROW_FIELD_NAME . "_" . $id, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: ((count($resultList) > 0) ? $resultList[$ctr]->getPlayerId() : ""), wrap: NULL, noValidate: false);
            $output .= $hiddenRow->getHtml();
            $ctr++;
        }
        $output .= " <div class=\"buttons center\">\n";
        $buttonSave = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_SAVE, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_SAVE, maxLength: NULL, name: Constant::TEXT_SAVE, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_SAVE, wrap: NULL, noValidate: false);
        $output .= $buttonSave->getHtml();
        $buttonReset = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_RESET, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_RESET, maxLength: NULL, name: Constant::TEXT_RESET, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_RESET, value: Constant::TEXT_RESET, wrap: NULL, noValidate: true);
        $output .= $buttonReset->getHtml();
    }
    $buttonCancel = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_CANCEL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_CANCEL, maxLength: NULL, name: Constant::TEXT_CANCEL, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_CANCEL, wrap: NULL, noValidate: true);
    $output .= $buttonCancel->getHtml();
    $output .= " </div>\n";
    $hiddenMode = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::FIELD_NAME_MODE, maxLength: NULL, name: Constant::FIELD_NAME_MODE, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: $mode, wrap: NULL, noValidate: false);
    $output .= $hiddenMode->getHtml();
    $hiddenSelectedRows = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: SELECTED_ROWS_FIELD_NAME, maxLength: NULL, name: SELECTED_ROWS_FIELD_NAME, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: $ids, wrap: NULL, noValidate: false);
    $output .= $hiddenSelectedRows->getHtml();
    $output .= "</div>\n";
} elseif (Constant::MODE_SAVE_CREATE == $mode || Constant::MODE_SAVE_MODIFY == $mode) {
    $ctr = 0;
    $ary = explode(separator: Constant::DELIMITER_DEFAULT, string: $ids);
    foreach ($ary as $id) {
        $firstName = isset($_POST[FIRST_NAME_FIELD_NAME . "_" . $id]) ? $_POST[FIRST_NAME_FIELD_NAME . "_" . $id] : DEFAULT_VALUE_BLANK;
        $lastName = isset($_POST[LAST_NAME_FIELD_NAME . "_" . $id]) ? $_POST[LAST_NAME_FIELD_NAME . "_" . $id] : DEFAULT_VALUE_BLANK;
        $username = isset($_POST[PLAYERNAME_FIELD_NAME . "_" . $id]) ? $_POST[PLAYERNAME_FIELD_NAME . "_" . $id] : DEFAULT_VALUE_BLANK;
        $password = isset($_POST[PASSWORD_FIELD_NAME . "_" . $id]) ? $_POST[PASSWORD_FIELD_NAME . "_" . $id] : DEFAULT_VALUE_BLANK;
        $email = isset($_POST[EMAIL_FIELD_NAME . "_" . $id]) ? $_POST[EMAIL_FIELD_NAME . "_" . $id] : DEFAULT_VALUE_BLANK;
        $phone = (isset($_POST[PHONE_FIELD_NAME . "_" . $id])) ? preg_replace("/[^0-9]/", "", $_POST[PHONE_FIELD_NAME . "_" . $id]) : DEFAULT_VALUE_PHONE;
        $phone = (int) ($phone == "" ? DEFAULT_VALUE_PHONE : $phone);
        $administrator = (int) (isset($_POST[ADMINISTRATOR_FIELD_NAME . "_" . $id]) ? $_POST[ADMINISTRATOR_FIELD_NAME . "_" . $id] : DEFAULT_VALUE_ADMINISTRATOR);
        $active = (int) (isset($_POST[ACTIVE_FIELD_NAME . "_" . $id]) ? $_POST[ACTIVE_FIELD_NAME . "_" . $id] : (Constant::MODE_SAVE_CREATE == $mode ? DEFAULT_VALUE_ACTIVE_CREATE : DEFAULT_VALUE_ACTIVE));
        if (Constant::MODE_SAVE_CREATE == $mode) {
            $pl = new Players();
            $pl->setPlayerActiveFlag(playerActiveFlag: (bool) $active);
            $pl->setPlayerAdministratorFlag(playerAdministratorFlag: (bool) $administrator);
            $pl->setPlayerApprovalDate(playerApprovalDate: new DateTime());
            $playerApproval = $entityManager->find(className: Constant::ENTITY_PLAYERS, id: (int) SessionUtility::getValue(name: "userid"));
            $pl->setPlayerApproval(playerApproval: $playerApproval);
            $pl->setPlayerEmail(playerEmail: $email);
            $pl->setPlayerFirstName(playerFirstName: $firstName);
            $pl->setPlayerLastName(playerLastName: $lastName);
            $pl->setPlayerPassword(playerPassword: $password);
            $pl->setPlayerPhone(playerPhone: (string) $phone);
            $pl->setPlayerRegistrationDate(playerRegistrationDate: new DateTime());
            $pl->setPlayerUsername(playerUsername: $username);
            $entityManager->persist(entity: $pl);
            try {
                $entityManager->flush();
            } catch (Exception $e) {
                $errors = $e->getMessage();
            }
        } elseif (Constant::MODE_SAVE_MODIFY == $mode) {
            $tempPlayerId = (int) ((isset($_POST[HIDDEN_ROW_FIELD_NAME . "_" . $id])) ? $_POST[HIDDEN_ROW_FIELD_NAME . "_" . $id] : 0);
            $pl = $entityManager->find(className: Constant::ENTITY_PLAYERS, id: $tempPlayerId);
            $pl->setPlayerActiveFlag(playerActiveFlag: (bool) $active);
            $pl->setPlayerAdministratorFlag(playerAdministratorFlag: (bool) $administrator);
            $pl->setPlayerEmail(playerEmail: $email);
            $pl->setPlayerFirstName(playerFirstName: $firstName);
            $pl->setPlayerLastName(playerLastName: $lastName);
            if (strlen(string: $password) > 0) {
                $pl->setPlayerPassword(playerPassword: $password);
            }
            $pl->setPlayerPhone(playerPhone: (string) $phone);
            $pl->setPlayerUsername(playerUsername: $username);
            $entityManager->persist(entity: $pl);
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
            $pl = $entityManager->find(className: Constant::ENTITY_PLAYERS, id: (int) $ids);
            $entityManager->remove(entity: $pl);
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
        $buttonCreate = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_CREATE, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_CREATE, maxLength: NULL, name: Constant::TEXT_CREATE, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_CREATE, wrap: NULL, noValidate: false);
        $output .= $buttonCreate->getHtml();
        $buttonModify = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_MODIFY, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: true, id: Constant::TEXT_MODIFY, maxLength: NULL, name: Constant::TEXT_MODIFY, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_MODIFY, wrap: NULL, noValidate: false);
        $output .= $buttonModify->getHtml();
    } else if (Constant::MODE_DELETE == $mode) {
        $buttonDelete = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_CONFIRM_DELETE, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_CONFIRM_DELETE, maxLength: NULL, name: Constant::TEXT_CONFIRM_DELETE, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_CONFIRM_DELETE, wrap: NULL, noValidate: false);
        $output .= $buttonDelete->getHtml();
        $buttonCancel = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_CANCEL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_CANCEL, maxLength: NULL, name: Constant::TEXT_CANCEL, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_CANCEL, wrap: NULL, noValidate: false);
        $output .= $buttonCancel->getHtml();
    }
    $output .= "</div>\n";
    $hiddenMode = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::FIELD_NAME_MODE, maxLength: NULL, name: Constant::FIELD_NAME_MODE, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: $mode, wrap: NULL, noValidate: false);
    $output .= $hiddenMode->getHtml();
    $hiddenSelectedRows = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: SELECTED_ROWS_FIELD_NAME, maxLength: NULL, name: SELECTED_ROWS_FIELD_NAME, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: $ids, wrap: NULL, noValidate: false);
    $output .= $hiddenSelectedRows->getHtml();
    $id = ("" == $ids) ? NULL : (int) $ids;
    $result = $entityManager->getRepository(entityName: Constant::ENTITY_PLAYERS)->getTableOutput(playerId: $id, indexed: true);
    $resultHeaders = $entityManager->getRepository(entityName: Constant::ENTITY_PLAYERS)->getTableOutput(playerId: $id, indexed: false);
    $colFormats = array(array(7, "date", 0));
    $hideColIndexes = array(3, 8, 9, 11, 12);
    $htmlTable = new HtmlTable(caption: NULL, class: NULL, colspan: NULL, columnFormat: $colFormats, debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), delimiter: Constant::DELIMITER_DEFAULT, foreignKeys: NULL, header: true, hiddenAdditional: NULL, hiddenId: HIDDEN_ROW_FIELD_NAME, hideColumnIndexes: $hideColIndexes, html: NULL, id: NULL, link: NULL, note: true, selectedRow: $ids, suffix: NULL, width: "100%");
    $output .= $htmlTable->getHtml(results: $result, resultHeaders: $resultHeaders);
}
$smarty->assign("content", $output);
$smarty->display("manage.tpl");
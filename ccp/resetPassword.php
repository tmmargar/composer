<?php
declare(strict_types = 1);
namespace ccp;
use DateInterval;
use DateTime;
use Poker\Ccp\Model\Constant;
use Poker\Ccp\Model\Email;
use Poker\Ccp\Model\FormControl;
use Poker\Ccp\Utility\SessionUtility;
require_once "init.php";
define("PLAYERNAME_FIELD_NAME", "username");
define("EMAIL_FIELD_NAME", "email");
define("PASSWORD_FIELD_NAME", "password");
define("CONFIRM_PASSWORD_FIELD_NAME", "confirmPassword");
define("SEND_REQUEST_FIELD_NAME", "send");
define("RESET_PASSWORD_FIELD_NAME", "resetPassword");
$smarty->assign("title", "Chip Chair and a Prayer Reset Password");
$smarty->assign("heading", "Reset Password");
$smarty->assign("action", $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"]);
$smarty->assign("formName", "frmPasswordReset");
if (isset($_GET["nav"])) {
    require_once "navigation.php";
} else {
    $smarty->assign("navigation", "");
}
$emailAddress = isset($_POST[EMAIL_FIELD_NAME]) ? $_POST[EMAIL_FIELD_NAME] : (isset($_GET[EMAIL_FIELD_NAME]) ? $_GET[EMAIL_FIELD_NAME] : DEFAULT_VALUE_BLANK);
$username = isset($_POST[PLAYERNAME_FIELD_NAME]) ? $_POST[PLAYERNAME_FIELD_NAME] : (isset($_GET[PLAYERNAME_FIELD_NAME]) ? $_GET[PLAYERNAME_FIELD_NAME] : DEFAULT_VALUE_BLANK);
$password = isset($_POST[PASSWORD_FIELD_NAME]) ? $_POST[PASSWORD_FIELD_NAME] : DEFAULT_VALUE_BLANK;
$output .=
    "<script type=\"module\">\n" .
    "  import { dataTable, display, input } from \"./scripts/import.js\";\n" .
    "  let aryMessages = [];\n" .
    "  let aryErrors = [];\n";
$entityManager = getEntityManager();
if (Constant::MODE_RESET_PASSWORD_REQUEST == $mode) {
    $params = array($username, $emailAddress, time());
//     $selector = bin2hex(random_bytes(length: 8));
//     $token = random_bytes(length: 32);
//     $hash = hash(algo: 'sha256', data: $token);
//     $expires = new DateTime();
//     $expires->add(new DateInterval("P1D")); // 1 day
    $resultList = $entityManager->getRepository(entityName: Constant::ENTITY_PLAYERS)->updateForReset(password: NULL, selectorTemp: NULL, tokenTemp: NULL, expiresTemp: NULL, username: $username, email: $emailAddress);
    if (!is_array(value: $resultList)) {
        $output .= "  aryErrors.push(\"Unable to change password for username <span class='bold'>" . $username . "</span> and email <span class='bold'>" . $emailAddress . "</span>. Please try again.\");";
        $mode = Constant::MODE_VIEW;
    } else {
        $email = new Email(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), toName: array(Constant::NAME_STAFF), toEmail: array(Constant::EMAIL_STAFF()), fromName: array($username), fromEmail: array($emailAddress), ccName: NULL, ccEmail: NULL, bccName: NULL, bccEmail: NULL, subject: NULL, body: NULL);
        $output .= "  aryMessages.push(\"" . $email->sendPasswordResetRequestEmail(info: $params, selectorAndToken: $resultList) . "<br>You will be redirected to the login page in 5 seconds\");";
        header("refresh:5;url=login.php");
    }
    $output .= "  if (aryMessages.length > 0) {display.showMessages({messages: aryMessages});}";
    $output .= "  if (aryErrors.length > 0) {display.showErrors({errors: aryErrors});}</script>";
} else if (Constant::MODE_RESET_PASSWORD == $mode) {
    $selector = filter_input(type: INPUT_GET, var_name: "selector");
    $validator = filter_input(type: INPUT_GET, var_name: "validator");
    if (false !== ctype_xdigit(text: $selector) && false !== ctype_xdigit(text: $validator)) {
        $calc = hash(algo: 'sha256', data: hex2bin($validator));
        $params = array($username, $emailAddress);
        $resultList = $entityManager->getRepository(entityName: Constant::ENTITY_PLAYERS)->getByUsernameAndEmail(username: $username, email: $emailAddress);
        if (hash_equals(known_string: $calc, user_string: $resultList[0]->getPlayerToken())) {
            $output .= "</script>\n";
            $output .= "<div class=\"responsive responsive--2cols responsive--collapse\">";
            $textBoxPassword = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_PASSWORD, autoComplete: "new-password", autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: PASSWORD_FIELD_NAME, maxLength: NULL, name: PASSWORD_FIELD_NAME, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: 20, suffix: NULL, type: FormControl::TYPE_INPUT_PASSWORD, value: $password, wrap: NULL, noValidate: false);
            $output .= " <div class=\"responsive-cell responsive-cell--head\"><label class=\"label\" for=\"" . $textBoxPassword->getId() . "\">New:</label></div>";
            $output .= " <div class=\"responsive-cell responsive-cell--value\">" . $textBoxPassword->getHtml() . "</div>";
            $textBoxConfirmPassword = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_CONFIRM_PASSWORD, autoComplete: "new-password", autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: CONFIRM_PASSWORD_FIELD_NAME, maxLength: NULL, name: CONFIRM_PASSWORD_FIELD_NAME, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: 20, suffix: NULL, type: FormControl::TYPE_INPUT_PASSWORD, value: NULL, wrap: NULL, noValidate: false);
            $output .= " <div class=\"responsive-cell responsive-cell--head\"><label class=\"label\" for=\"" . $textBoxConfirmPassword->getId() . "\">Confirm New:</label></div>";
            $output .= " <div class=\"responsive-cell responsive-cell--value\">" . $textBoxConfirmPassword->getHtml() . "</div>";
            $buttonReset = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_RESET, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: true, id: RESET_PASSWORD_FIELD_NAME, maxLength: NULL, name: RESET_PASSWORD_FIELD_NAME, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: ucwords(implode(" ", preg_split('/(?=[A-Z])/', RESET_PASSWORD_FIELD_NAME))), wrap: NULL, noValidate: false);
            $output .= $buttonReset->getHtml();
            $output .= "</div>";
        } else {
            $output .= "  aryErrors.push(\"Username <strong><u>" . $username . "</u></strong> and email <strong><u>" . $emailAddress . "</u></strong> are not a valid combination. Please try again.\");\n</script>\n";
            $mode = Constant::MODE_VIEW;
        }
    }
} else if (Constant::MODE_RESET_PASSWORD_CONFIRM == $mode) {
    $resultList = $entityManager->getRepository(entityName: Constant::ENTITY_PLAYERS)->updateForReset(password: $password, selectorTemp: NULL, tokenTemp: NULL, expiresTemp: NULL, username: $username, email: $emailAddress);
    if (is_array(value: $resultList)) {
        $output .= "  aryErrors.push(\"Unable to change password for username <span class='bold'>" . $username . "</span> and email <span class='bold'>" . $emailAddress . "</span>. Please try again.\");";
        $mode = "";
    } else {
        $email = new Email(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), fromName: array(Constant::NAME_STAFF), fromEmail: array(Constant::EMAIL_STAFF()), toName: array($username), toEmail: array($emailAddress), ccName: NULL, ccEmail: NULL, bccName: NULL, bccEmail: NULL, subject: NULL, body: NULL);
        $output .= "  aryMessages.push(\"" . $email->sendPasswordResetSuccessfulEmail() . "<br>You will be redirected to the login page in 5 seconds\");";
        session_destroy();
        header("refresh:5;url=login.php");
    }
    $output .= "  if (aryMessages.length > 0) {display.showMessages({messages: aryMessages});}";
    $output .= "  if (aryErrors.length > 0) {display.showErrors({errors: aryErrors});}</script>\n";
}
if (Constant::MODE_VIEW == $mode) {
    $output .= "</script>\n";
    $output .= "<div class=\"responsive responsive--2cols responsive--collapse\">";
    $textBoxUsername = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_PLAYERNAME, autoComplete: "off", autoFocus: true, checked: NULL, class: NULL, cols: NULL, disabled: false, id: PLAYERNAME_FIELD_NAME, maxLength: 30, name: PLAYERNAME_FIELD_NAME, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: 20, suffix: NULL, type: FormControl::TYPE_INPUT_TEXTBOX, value: $username, wrap: NULL, noValidate: false);
    $output .= " <div class=\"responsive-cell responsive-cell--head\"><label class=\"label\" for=\"" . $textBoxUsername->getId() . "\">Username:</label></div>";
    $output .= " <div class=\"responsive-cell responsive-cell--value\">" . $textBoxUsername->getHtml() . "</div>";
    $textBoxEmail = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_EMAIL, autoComplete: "email", autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: EMAIL_FIELD_NAME, maxLength: 50, name: EMAIL_FIELD_NAME, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: 20, suffix: NULL, type: FormControl::TYPE_INPUT_TEXTBOX, value: $emailAddress, wrap: NULL, noValidate: false);
    $output .= " <div class=\"responsive-cell responsive-cell--head\"><label class=\"label\" for=\"" . $textBoxEmail->getId() . "\">Email:</label></div>";
    $output .= " <div class=\"responsive-cell responsive-cell--value\">" . $textBoxEmail->getHtml() . "</div>";
    $output .= " <div class=\"responsive-cell\">\n";
    $buttonEmail = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_SIGN_UP, autoComplete: NULL, autoFocus: false, checked: NULL, class: array("button-icon button-icon-separator icon-border-caret-right"), cols: NULL, disabled: true, id: SEND_REQUEST_FIELD_NAME, maxLength: NULL, name: SEND_REQUEST_FIELD_NAME, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: ucwords(implode(" ", preg_split('/(?=[A-Z])/', SEND_REQUEST_FIELD_NAME))), wrap: NULL, noValidate: false);
    $output .= $buttonEmail->getHtml() . "</div>\n";
    $output .= "</div>";
}
$hiddenMode = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::FIELD_NAME_MODE, maxLength: NULL, name: Constant::FIELD_NAME_MODE, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: $mode, wrap: NULL, noValidate: false);
$output .= $hiddenMode->getHtml();
$smarty->assign("content", $output);
$smarty->display("resetPassword.tpl");
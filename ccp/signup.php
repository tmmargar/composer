<?php
declare(strict_types = 1);
namespace ccp;
use DateTime;
use Exception;
use Poker\Ccp\classes\model\Constant;
use Poker\Ccp\classes\model\Email;
use Poker\Ccp\classes\model\FormControl;
use Poker\Ccp\classes\utility\SessionUtility;
use Poker\Ccp\Entity\Players;
require_once "init.php";
define("NAME_FIELD_NAME", "name");
define("EMAIL_FIELD_NAME", "email");
define("USERNAME_FIELD_NAME", "username");
define("PASSWORD_FIELD_NAME", "password");
define("CONFIRM_PASSWORD_FIELD_NAME", "confirmPassword");
define("SIGN_UP_FIELD_NAME", "signUp");
define("SIGN_UP_TEXT", "Sign Up");
$errors = NULL;
$name = isset($_POST[NAME_FIELD_NAME]) ? $_POST[NAME_FIELD_NAME] : DEFAULT_VALUE_BLANK;
$emailAddress = isset($_POST[EMAIL_FIELD_NAME]) ? $_POST[EMAIL_FIELD_NAME] : DEFAULT_VALUE_BLANK;
$username = isset($_POST[USERNAME_FIELD_NAME]) ? $_POST[USERNAME_FIELD_NAME] : DEFAULT_VALUE_BLANK;
$password = isset($_POST[PASSWORD_FIELD_NAME]) ? $_POST[PASSWORD_FIELD_NAME] : DEFAULT_VALUE_BLANK;
$confirmPassword = isset($_POST[CONFIRM_PASSWORD_FIELD_NAME]) ? $_POST[CONFIRM_PASSWORD_FIELD_NAME] : DEFAULT_VALUE_BLANK;
$classUsername = DEFAULT_VALUE_BLANK;
$classEmail = DEFAULT_VALUE_BLANK;
$autoFocusName = true;
$autoFocusUserName = false;
$autoFocusEmail = false;
if (Constant::MODE_SIGNUP == $mode) {
    $resultList = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getByUsername(username: $username);
    if (0 < count($resultList)) {
        $failMessage = "Username <span class='bold'>" . $username . "</span> already exists. Please choose another.";
        $classUsername = "errors";
        $autoFocusUserName = true;
        $autoFocusName = false;
    } else {
        $params = array($emailAddress);
        $resultList = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getByEmail(email: $emailAddress);
        if (0 < count($resultList)) {
            $failMessage = "Email <span class='bold'>" . $emailAddress . "</span> already exists. Please choose another.";
            $classEmail = "errors";
            $autoFocusEmail = true;
            $autoFocusName = false;
        } else {
            $output .=
                "<script type=\"module\">\n" .
                "  import { dataTable, display, input } from \"./scripts/import.js\";\n" .
                "  let aryMessages = [];\n";
            $nameValues = explode(" ", $name);
            $pl = new Players();
            $pl->setPlayerActiveFlag("0");
            $pl->setPlayerAdministratorFlag("0");
            $pl->setPlayerEmail($emailAddress);
            $pl->setPlayerExpires(NULL);
            $pl->setPlayerFirstName($nameValues[0]);
            $pl->setPlayerLastName($nameValues[1]);
            $pl->setPlayerPassword($password);
            $pl->setPlayerPhone("0");
            $pl->setPlayerRegistrationDate(new DateTime());
            $pl->setPlayerSelector(NULL);
            $pl->setPlayerToken(NULL);
            $pl->setPlayerUsername($username);
            $entityManager->persist($pl);
            try {
                $entityManager->flush();
            } catch (Exception $e) {
                $errors = $e->getMessage();
            }
            $email = new Email(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), toName: array(Constant::NAME_STAFF), toEmail: array(Constant::EMAIL_STAFF()), fromName: array($name), fromEmail: array($emailAddress), ccName: array(Constant::NAME_STAFF), ccEmail: array(Constant::EMAIL_STAFF()), bccName: NULL, bccEmail: NULL, subject: NULL, body: NULL);
            $output .= "  aryMessages.push(\"" . $email->sendSignUpEmail() . "\");";
            // send email to staff for approval
            $email = new Email(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), toName: array($name), toEmail: array($emailAddress), fromName: array(Constant::NAME_STAFF), fromEmail: array(Constant::EMAIL_STAFF()), ccName: array(Constant::NAME_STAFF), ccEmail: array(Constant::EMAIL_STAFF()), bccName: NULL, bccEmail: NULL, subject: NULL, body: NULL);
            $output .= "  aryMessages.push(\"" . $email->sendSignUpApprovalEmail() . "\");";
            $output .= "  if (aryMessages.length > 0) {display.showMessages({messages: aryMessages});}</script>";
            $name = DEFAULT_VALUE_BLANK;
            $emailAddress = DEFAULT_VALUE_BLANK;
            $username = DEFAULT_VALUE_BLANK;
            $password = DEFAULT_VALUE_BLANK;
            $confirmPassword = DEFAULT_VALUE_BLANK;
        }
    }
}
$smarty->assign("title", "Chip Chair and a Prayer New Player Sign Up");
$smarty->assign("heading", "New Player Sign Up");
$smarty->assign("action", $_SERVER["SCRIPT_NAME"] . "?" . $_SERVER["QUERY_STRING"]);
$smarty->assign("formName", "frmSignup");
if (isset($failMessage)) {
    $output .=
        "<script type=\"module\">\n" .
        "  import { dataTable, display, input } from \"./scripts/import.js\";\n" .
        "  display.showErrors({errors: [ \"" . $failMessage . "\" ]});" .
        "</script>";
}
$output .= "<div class=\"responsive responsive--2cols responsive--collapse\">";
$textBoxName = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_NAME, autoComplete: "off", autoFocus: $autoFocusName, checked: NULL, class: NULL, cols: NULL, disabled: false, id: NAME_FIELD_NAME, maxLength: 60, name: NAME_FIELD_NAME, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: 20, suffix: NULL, type: FormControl::TYPE_INPUT_TEXTBOX, value: $name, wrap: NULL);
$output .= " <div class=\"responsive-cell responsive-cell--head\"><label class=\"label\" for=\"" . $textBoxName->getId() . "\">Name:</label></div>";
$output .= " <div class=\"responsive-cell\">" . $textBoxName->getHtml() . "</div>";
$textBoxEmail = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_EMAIL, autoComplete: "off", autoFocus: $autoFocusEmail, checked: NULL, class: array($classEmail), cols: NULL, disabled: false, id: EMAIL_FIELD_NAME, maxLength: 50, name: EMAIL_FIELD_NAME, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: 20, suffix: NULL, type: FormControl::TYPE_INPUT_TEXTBOX, value: $emailAddress, wrap: NULL);
$output .= " <div class=\"responsive-cell responsive-cell--head\"><label class=\"label\" for=\"" . $textBoxEmail->getId() . "\">Email:</label></div>";
$output .= " <div class=\"responsive-cell\">" . $textBoxEmail->getHtml() . "</div>";
$textBoxUsername = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_USERNAME, autoComplete: "off", autoFocus: $autoFocusUserName, checked: NULL, class: array($classUsername), cols: NULL, disabled: false, id: USERNAME_FIELD_NAME, maxLength: 30, name: USERNAME_FIELD_NAME, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: 20, suffix: NULL, type: FormControl::TYPE_INPUT_TEXTBOX, value: $username, wrap: NULL);
$output .= " <div class=\"responsive-cell responsive-cell--head\"><label class=\"label\" for=\"" . $textBoxUsername->getId() . "\">Username:</label></div>";
$output .= " <div class=\"responsive-cell\">" . $textBoxUsername->getHtml() . "</div>";
$textBoxPassword = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_PASSWORD, autoComplete: "off", autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: PASSWORD_FIELD_NAME, maxLength: NULL, name: PASSWORD_FIELD_NAME, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: 20, suffix: NULL, type: FormControl::TYPE_INPUT_PASSWORD, value: $password, wrap: NULL);
$output .= " <div class=\"responsive-cell responsive-cell--head\"><label class=\"label\" for=\"" . $textBoxPassword->getId() . "\">Password:</label></div>";
$output .= " <div class=\"responsive-cell\">" . $textBoxPassword->getHtml() . "</div>";
$textBoxConfirmPassword = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_CONFIRM_PASSWORD, autoComplete: "off", autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: CONFIRM_PASSWORD_FIELD_NAME, maxLength: NULL, name: CONFIRM_PASSWORD_FIELD_NAME, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: 20, suffix: NULL, type: FormControl::TYPE_INPUT_PASSWORD, value: $confirmPassword, wrap: NULL);
$output .= " <div class=\"responsive-cell responsive-cell--head\"><label class=\"label\" for=\"" . $textBoxConfirmPassword->getId() . "\">Confirm Password:</label></div>";
$output .= " <div class=\"responsive-cell\">" . $textBoxConfirmPassword->getHtml() . "</div>";
$buttonSignup = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_SIGN_UP, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: true, id: SIGN_UP_FIELD_NAME, maxLength: NULL, name: SIGN_UP_FIELD_NAME, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: SIGN_UP_TEXT, wrap: NULL);
$output .= " <div class=\"responsive-cell\">" . $buttonSignup->getHtml() . "</div>";
$output .= "</div>";
$hiddenMode = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::FIELD_NAME_MODE, maxLength: NULL, name: Constant::FIELD_NAME_MODE, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: $mode, wrap: NULL);
$output .= $hiddenMode->getHtml();
$smarty->assign("content", $output);
$smarty->display("signup.tpl");
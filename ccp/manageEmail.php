<?php
declare(strict_types = 1);
namespace ccp;
use Poker\Ccp\classes\model\Constant;
use Poker\Ccp\classes\model\Email;
use Poker\Ccp\classes\model\FormControl;
use Poker\Ccp\classes\model\FormOption;
use Poker\Ccp\classes\model\FormSelect;
use Poker\Ccp\classes\utility\SessionUtility;
require_once "init.php";
define("TO_FIELD_NAME", "to");
define("SUBJECT_FIELD_NAME", "subject");
define("BODY_FIELD_NAME", "body");
define("EMAIL_FIELD_NAME", "email");
$smarty->assign("title", "Send Email");
$smarty->assign("heading", "Send Email");
$mode = isset($_POST[Constant::FIELD_NAME_MODE]) ? $_POST[Constant::FIELD_NAME_MODE] : Constant::MODE_VIEW;
$output = "";
if (Constant::MODE_EMAIL == $mode) {
    $to = isset($_POST[TO_FIELD_NAME]) ? $_POST[TO_FIELD_NAME] : "";
    $subject = isset($_POST[SUBJECT_FIELD_NAME]) ? $_POST[SUBJECT_FIELD_NAME] : "";
    $body = isset($_POST[BODY_FIELD_NAME]) ? $_POST[BODY_FIELD_NAME] : "";
    $output .=
        "<script type=\"module\">\n" .
        "  import { dataTable, display, input } from \"./scripts/import.js\";\n" .
        "  let aryMessages = [];\n";
    foreach ($to as $toEach) {
        $toArray = explode(":", $toEach);
        $email = new Email(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), fromName: array(Constant::NAME_STAFF), fromEmail: array(Constant::EMAIL_STAFF()), toName: array($toArray[0]), toEmail: array($toArray[1]), ccName: NULL, ccEmail: NULL, bccName: NULL, bccEmail: NULL, subject: $subject, body: $body);
        $output .= "  aryMessages.push(\"" . $email->sendEmail() . "\");\n";
    }
    $output .= "  if (aryMessages.length > 0) {display.showMessages({messages: aryMessages});}\n</script>\n";
}
$resultList = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getActives();
if (count($resultList) == 0) {
    echo "No active users";
}
$script =
    "<script src=\"https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js\"></script>\n" .
    "<script src=\"scripts/manageEmail.js\" type=\"module\"></script>\n" .
    "<script src=\"https://cdn.tiny.cloud/1/gb0quk0idsdusszgqyocrwkff5r6uupkzb3j30niuvzxqiyt/tinymce/6/tinymce.min.js\" referrerpolicy=\"origin\"></script>\n";
$smarty->assign("script", $script);
$style =
    "<link href=\"https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css\" rel=\"stylesheet\">\n" .
    "<link href=\"css/manageEmail.css\" rel=\"stylesheet\">\n";
$smarty->assign("style", $style);
$smarty->assign("mode", $mode);
$smarty->assign("action", $_SERVER["SCRIPT_NAME"]);
$smarty->assign("formName", "frmEmail");
$output .= " <div class=\"buttons center\">\n";
$buttonEmail = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_EMAIL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: EMAIL_FIELD_NAME . "_2", maxLength: NULL, name: EMAIL_FIELD_NAME . "_2", onClick: NULL, placeholder:NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: ucwords(EMAIL_FIELD_NAME), wrap: NULL);
$output .= $buttonEmail->getHtml();
$buttonReset = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_RESET, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_RESET . "_2", maxLength: NULL, name: Constant::TEXT_RESET . "_2", onClick: NULL, placeholder:NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_RESET, value: Constant::TEXT_RESET, wrap: NULL);
$output .= $buttonReset->getHtml();
$output .= " </div>\n";
$output .= "<div class=\"responsive responsive--2cols responsive--collapse\">";
$output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . TO_FIELD_NAME . "\">To:</div>\n";
$output .= " <div class=\"responsive-cell responsive-cell-value\" style=\"overflow: unset;\">";
$output .= "  <a href=\"#\" id=\"selectAll\">Select all</a>&nbsp;<a id=\"deselectAll\">De-select all</a>\n";
$selectTo = new FormSelect(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_TO, class: array("tom-select"), disabled: false, id: TO_FIELD_NAME, multiple: true, name: TO_FIELD_NAME . "[]", onClick: NULL, readOnly: false, size: 5, suffix: NULL, value: NULL);
$output .= $selectTo->getHtml();
foreach ($resultList as $player) {
    $option = new FormOption(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), class: NULL, disabled: false, id: NULL, name: NULL, selectedValue: NULL, suffix: NULL, text: $player->getPlayerName(), value: $player->getPlayerName() . ":" . $player->getPlayerEmail());
    $output .= $option->getHtml();
}
$output .= "  </select>\n";
$output .= " </div>\n";
$output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . SUBJECT_FIELD_NAME . "\">Subject:</div>\n";
$textBoxEmail = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_SUBJECT, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: SUBJECT_FIELD_NAME, maxLength: 100, name: SUBJECT_FIELD_NAME, onClick: NULL, placeholder:NULL, readOnly: false, required: true, rows: NULL, size: 41, suffix: NULL, type: FormControl::TYPE_INPUT_TEXTBOX, value: NULL, wrap: NULL);
$output .= " <div class=\"responsive-cell responsive-cell-value\">" . $textBoxEmail->getHtml() . "</div>\n";
$output .= " <div class=\"responsive-cell responsive-cell-label responsive-cell--head\"><label for=\"" . BODY_FIELD_NAME . "\">Body:</div>\n";
$textAreaBody = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_BODY, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: 43, disabled: false, id: BODY_FIELD_NAME, maxLength: NULL, name: BODY_FIELD_NAME, onClick: NULL, placeholder:NULL, readOnly: false, required: false, rows: 10, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_TEXTAREA, value: NULL, wrap: "hard");
$output .= " <div class=\"responsive-cell responsive-cell-value\">" . $textAreaBody->getHtml() . "</div>\n";
$output .= "</div>\n";
$output .= "<div class=\"buttons center\">\n";
$buttonEmail = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_EMAIL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: EMAIL_FIELD_NAME, maxLength: NULL, name: EMAIL_FIELD_NAME, onClick: NULL, placeholder:NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: ucwords(EMAIL_FIELD_NAME), wrap: NULL);
$output .= $buttonEmail->getHtml();
$buttonReset = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_RESET, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_RESET, maxLength: NULL, name: Constant::TEXT_RESET, onClick: NULL, placeholder:NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_RESET, value: Constant::TEXT_RESET, wrap: NULL);
$output .= $buttonReset->getHtml();
$output .= " </div>\n";
$hiddenMode = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::FIELD_NAME_MODE, maxLength: NULL, name: Constant::FIELD_NAME_MODE, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: $mode, wrap: NULL);
$output .= $hiddenMode->getHtml();
$smarty->assign("content", $output);
$smarty->display("manage.tpl");
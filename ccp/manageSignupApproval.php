<?php
declare(strict_types = 1);
namespace ccp;
use Poker\Ccp\classes\model\Constant;
use Poker\Ccp\classes\model\Email;
use Poker\Ccp\classes\model\FormControl;
use Poker\Ccp\classes\utility\SessionUtility;
use PDO;
require_once "init.php";
define("APPROVE_FIELD_NAME", "approve");
define("REJECT_FIELD_NAME", "reject");
define("SAVE_FIELD_NAME", "save");
define("SAVE_TEXT", "Save");
$smarty->assign("title", "Chip Chair and a Prayer Player Approval");
$smarty->assign("heading", "Player Approval");
// $style = "<style type=\"text/css\">\n" . "div.label, div.input {\n" . "  height: 17px;\n" . "  line-height: 17px;\n" . "  margin: 5px;\n" . "}\n" . "div.label {\n" . "  float: left;\n" . "  width: 100px;\n" . "}\n" . "div.input {\n" . "  float: left;\n" . "  margin: 0;\n" . "}\n" . "div.clear {\n" . "  clear: both;\n" . "  height: 0;\n" . "  line-height: 0;\n" . "}\n" . "</style>\n";
$smarty->assign("style", "<link href=\"css/manageSignupApproval.css\" rel=\"stylesheet\">");
if (Constant::MODE_SAVE_VIEW == $mode) {
  $player = array();
  $emailAddress = array();
  $approval = array();
  $rejection = array();
  foreach ($_POST as $key => $value) {
    $userId = count(explode("_", $key)) > 1 ? explode("_", $key)[1] : "";
    if (strpos($key, 'user_') !== false) {
      $player[$userId] = $value;
    } else if (strpos($key, 'email_') !== false) {
      $emailAddress[$userId] = $value;
    } else if (strpos($key, 'approvePlayer_') !== false) {
      $approval[$userId] = $player[$userId];
    } else if (strpos($key, 'rejectPlayer_') !== false) {
      $rejection[$userId] = $player[$userId];
    }
  }
  $output .=
    "<script type=\"module\">\n" .
    "  import { dataTable, display, input } from \"./scripts/import.js\";\n" .
    "  let aryMessages = [];\n";
  // update approval date or rejection date and set active flag
  $params = array();
  // id, first_name, last_name, username, password, email, phone, administrator, registration_date, approval_date, approval_userid, rejection_date, rejection_userid, active, reset_selector, reset_token, reset_expires, remember_selector, remember_token, remember_expires
  foreach ($approval as $key => $value) {
    $params[0] = (int) $key;
    $params[9] = "";
    $params[10] = SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_USERID);
    $params[13] = 1;
    $databaseResult->updatePlayer(params: $params);
    $output .= "  aryMessages.push(\"Successfully approved " . $value . "\");\n";
    $email = new Email(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), fromName: array(Constant::NAME_STAFF), fromEmail: array(Constant::EMAIL_STAFF()), toName: array($value), toEmail: array($emailAddress[$key]), ccName: NULL, ccEmail: NULL, bccName: NULL, bccEmail: NULL, subject: NULL, body: NULL);
    $output .= "  aryMessages.push(\"" . $email->sendApprovedEmail() . "\");\n";
  }
  $params = NULL;
  foreach ($rejection as $key => $value) {
    $params[0] = (int) $key;
    $params[11] = "";
    $params[12] = SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_USERID);
    $databaseResult->updatePlayer(params: $params);
    $output .= "  aryMessages.push(\"Successfully rejected " . $value . "\");\n";
    $email = new Email(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), fromName: array(Constant::NAME_STAFF), fromEmail: array(Constant::EMAIL_STAFF()), toName: array($value), toEmail: array($emailAddress[$key]), ccName: NULL, ccEmail: NULL, bccName: NULL, bccEmail: NULL, subject: NULL, body: NULL);
    $output .= "  aryMessages.push(\"" . $email->sendRejectedEmail() . "\");\n";
  }
  $output .= "  if (aryMessages.length > 0) {display.showMessages({messages: aryMessages});}\n</script>\n";
}
$pdoStatementAndQuery = $databaseResult->getPlayersForApproval();
$query = $pdoStatementAndQuery[1];
$pdoStatement = $databaseResult->getConnection()->prepare(query: $query);
$pdoStatement->execute();
if (0 < $pdoStatement->rowCount()) {
  $count = $pdoStatement->columnCount();
  $output .= "<div class=\"buttons center\">\n";
  $buttonSave = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_SAVE, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: true, id: Constant::TEXT_SAVE . "_2", maxLength: NULL, name: Constant::TEXT_SAVE . "_2", onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_SAVE, wrap: NULL);
  $output .= $buttonSave->getHtml();
  $output .= "</div>\n";
  $output .= "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"dataTbl display\" id=\"dataTblSignupApproval\">\n";
  $headerRow = true;
  while ($row = $pdoStatement->fetch(PDO::FETCH_BOTH)) {
    if ($headerRow) {
      $output .= " <thead>\n";
      $output .= "  <tr>\n";
      for ($index = 1; $index < $count; $index ++) {
        $output .= "   <th>" . ucwords($pdoStatement->getColumnMeta($index)["name"]) . "</th>\n";
      }
      $output .= "   <th class=\"center\">Approve\n<br />\n<input id=\"approvePlayerCheckAll\" name=\"approvePlayerCheckAll\" type=\"checkbox\" /></th>\n";
      $output .= "   <th class=\"center\">Reject\n<br />\n<input id=\"rejectPlayerCheckAll\" name=\"rejectPlayerCheckAll\" type=\"checkbox\" /></th>\n";
      $output .= "  </tr>\n";
      $output .= " </thead>\n";
      $output .= " <tbody>\n";
      $headerRow = false;
    }
    $output .= "  <tr>\n";
    for ($index = 1; $index < $count; $index ++) {
      $hiddenPlayer = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::FIELD_NAME_PLAYER . "_" . $row[0], maxLength: NULL, name: Constant::FIELD_NAME_PLAYER . "_" . $row[0], onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: $row[1], wrap: NULL);
      $hiddenEmail = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::FIELD_NAME_EMAIL . "_" . $row[0], maxLength: NULL, name: Constant::FIELD_NAME_EMAIL . "_" . $row[0], onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: $row[2], wrap: NULL);
      $hiddenUsername = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::FIELD_NAME_USERNAME . "_" . $row[0], maxLength: NULL, name: Constant::FIELD_NAME_USERNAME . "_" . $row[0], onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: $row[3], wrap: NULL);
      $output .= "   <td>" . $row[$index] . ($index == 1 ? $hiddenPlayer->getHtml() : ($index == 2 ? $hiddenEmail->getHtml() : ($index == 3 ? $hiddenUsername->getHtml() : ""))) . "</td>\n";
    }
    $output .= "   <td class=\"center\"><input id=\"approvePlayer_" . $row[0] . "\" name=\"approvePlayer_" . $row[0] . "\" type=\"checkbox\" value=\"1\" /></td>\n";
    $output .= "   <td class=\"center\"><input id=\"rejectPlayer_" . $row[0] . "\" name=\"rejectPlayer_" . $row[0] . "\" type=\"checkbox\" value=\"1\" /></td>\n";
    $output .= "  </tr>\n";
  }
  $output .= " </tbody>\n";
  $output .= "</table>\n";
  $output .= "<div class=\"buttons center\">\n";
  $buttonSave = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_SAVE, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: true, id: Constant::TEXT_SAVE, maxLength: NULL, name: Constant::TEXT_SAVE, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_SAVE, wrap: NULL);
  $output .= $buttonSave->getHtml();
  $output .= "</div>\n";
} else {
  $output .= "<br />\nNo users require approval";
}
$hiddenMode = new FormControl(SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), NULL, NULL, false, NULL, NULL, NULL, false, Constant::FIELD_NAME_MODE, NULL, Constant::FIELD_NAME_MODE, NULL, NULL, false, NULL, NULL, NULL, NULL, FormControl::TYPE_INPUT_HIDDEN, $mode, NULL);
$output .= $hiddenMode->getHtml();
$smarty->assign("content", $output);
$smarty->display("manage.tpl");
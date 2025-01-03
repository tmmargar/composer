<?php
declare(strict_types = 1);
namespace ccp;
use DateTime;
use Exception;
use PDO;
use Poker\Ccp\Model\Constant;
use Poker\Ccp\Model\Email;
use Poker\Ccp\Model\FormControl;
use Poker\Ccp\Utility\SessionUtility;
use Poker\Ccp\Entity\Players;
require_once "init.php";
define("APPROVE_FIELD_NAME", "approve");
define("REJECT_FIELD_NAME", "reject");
define("SAVE_FIELD_NAME", "save");
define("SAVE_TEXT", "Save");
$smarty->assign("title", "Chip Chair and a Prayer Player Approval");
$smarty->assign("heading", "Player Approval");
// $style = "<style type=\"text/css\">\n" . "div.label, div.input {\n" . "  height: 17px;\n" . "  line-height: 17px;\n" . "  margin: 5px;\n" . "}\n" . "div.label {\n" . "  float: left;\n" . "  width: 100px;\n" . "}\n" . "div.input {\n" . "  float: left;\n" . "  margin: 0;\n" . "}\n" . "div.clear {\n" . "  clear: both;\n" . "  height: 0;\n" . "  line-height: 0;\n" . "}\n" . "</style>\n";
$smarty->assign("style", "<link href=\"css/manageSignupApproval.css\" rel=\"stylesheet\">");
$errors = NULL;
if (Constant::MODE_SAVE_VIEW == $mode) {
    $player = array();
    $emailAddress = array();
    $approval = array();
    $rejection = array();
    foreach ($_POST as $key => $value) {
        $playerId = count(value: explode(separator: "_", string: $key)) > 1 ? explode(separator: "_", string: $key)[1] : "";
        if (strpos(haystack: $key, needle: "player_") !== false) {
            $player[$playerId] = $value;
        } else if (strpos(haystack: $key, needle: "email_") !== false) {
            $emailAddress[$playerId] = $value;
        } else if (strpos(haystack: $key, needle: "approvePlayer_") !== false) {
            $approval[$playerId] = $player[$playerId];
        } else if (strpos(haystack: $key, needle: "rejectPlayer_") !== false) {
            $rejection[$playerId] = $player[$playerId];
        }
    }
    $output .=
        "<script type=\"module\">\n" .
        "  import { dataTable, display, input } from \"./scripts/import.js\";\n" .
        "  let aryMessages = [];\n";
    // update approval date or rejection date and set active flag
    foreach ($approval as $key => $value) {
        $player = $entityManager->find(className: Constant::ENTITY_PLAYERS, id: (int) $key);
        $playerApproval = $entityManager->find(className: Constant::ENTITY_PLAYERS, id: (int) SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_PLAYERID));
        $player->setPlayerApproval(playerApproval: $playerApproval);
        $player->setPlayerApprovalDate(playerApprovalDate: new DateTime());
        $player->setPlayerActiveFlag(playerActiveFlag: true);
        $entityManager->persist(entity: $player);
        try {
            $entityManager->flush();
        } catch (Exception $e) {
            $errors = $e->getMessage();
        }
        $output .= "  aryMessages.push(\"Successfully approved " . $value . "\");\n";
        $email = new Email(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), fromName: array(Constant::NAME_STAFF), fromEmail: array(Constant::EMAIL_STAFF()), toName: array($value), toEmail: array($emailAddress[$key]), ccName: NULL, ccEmail: NULL, bccName: NULL, bccEmail: NULL, subject: NULL, body: NULL);
        $output .= "  aryMessages.push(\"" . $email->sendApprovedEmail() . "\");\n";
    }
    foreach ($rejection as $key => $value) {
        $player = $entityManager->find(className: Constant::ENTITY_PLAYERS, id: (int) $key);
        $playerRejection = $entityManager->find(className: Constant::ENTITY_PLAYERS, id: (int) SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_PLAYERID));
        $player->setPlayerRejection(playerRejection: $playerRejection);
        $player->setPlayerRejectionDate(playerRejectionDate: new DateTime());
        $entityManager->persist(entity: $player);
        try {
            $entityManager->flush();
        } catch (Exception $e) {
            $errors = $e->getMessage();
        }
        $output .= "  aryMessages.push(\"Successfully rejected " . $value . "\");\n";
        $email = new Email(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), fromName: array(Constant::NAME_STAFF), fromEmail: array(Constant::EMAIL_STAFF()), toName: array($value), toEmail: array($emailAddress[$key]), ccName: NULL, ccEmail: NULL, bccName: NULL, bccEmail: NULL, subject: NULL, body: NULL);
        $output .= "  aryMessages.push(\"" . $email->sendRejectedEmail() . "\");\n";
    }
    $output .= "  if (aryMessages.length > 0) {display.showMessages({messages: aryMessages});}\n</script>\n";
}
$result = $entityManager->getRepository(entityName: Constant::ENTITY_PLAYERS)->getForApproval(indexed: true);
$resultHeaders = $entityManager->getRepository(entityName: Constant::ENTITY_PLAYERS)->getForApproval(indexed: false);
if (0 < count(value: $result)) {
    $count = count(value: $result[0]);
    $output .= "<div class=\"buttons center\">\n";
    $buttonSave = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_SAVE, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: true, id: Constant::TEXT_SAVE . "_2", maxLength: NULL, name: Constant::TEXT_SAVE . "_2", onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_SAVE, wrap: NULL, noValidate: false);
    $output .= $buttonSave->getHtml();
    $output .= "</div>\n";
    $output .= "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"dataTbl display\" id=\"dataTblSignupApproval\">\n";
    $headerRow = true;
    $ctr = 0;
    while ($ctr < count(value: $result)) {
        $row = $result[$ctr];
        if ($headerRow) {
            $output .= " <thead>\n";
            $output .= "  <tr>\n";
            for ($index = 1; $index < $count; $index++) {
                $headers = array_keys(array: $resultHeaders[$ctr]);
                $output .= "   <th>" . ucwords(string: $headers[$index]) . "</th>\n";
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
            $hiddenPlayer = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::FIELD_NAME_PLAYER . "_" . $row[0], maxLength: NULL, name: Constant::FIELD_NAME_PLAYER . "_" . $row[0], onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: $row[1], wrap: NULL, noValidate: false);
            $hiddenEmail = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::FIELD_NAME_EMAIL . "_" . $row[0], maxLength: NULL, name: Constant::FIELD_NAME_EMAIL . "_" . $row[0], onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: $row[2], wrap: NULL, noValidate: false);
            $hiddenUsername = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::FIELD_NAME_PLAYERNAME . "_" . $row[0], maxLength: NULL, name: Constant::FIELD_NAME_PLAYERNAME . "_" . $row[0], onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: $row[3], wrap: NULL, noValidate: false);
            $output .= "   <td>" . $row[$index] . ($index == 1 ? $hiddenPlayer->getHtml() : ($index == 2 ? $hiddenEmail->getHtml() : ($index == 3 ? $hiddenUsername->getHtml() : ""))) . "</td>\n";
        }
        $output .= "   <td class=\"center\"><input id=\"approvePlayer_" . $row[0] . "\" name=\"approvePlayer_" . $row[0] . "\" type=\"checkbox\" value=\"1\" /></td>\n";
        $output .= "   <td class=\"center\"><input id=\"rejectPlayer_" . $row[0] . "\" name=\"rejectPlayer_" . $row[0] . "\" type=\"checkbox\" value=\"1\" /></td>\n";
        $output .= "  </tr>\n";
        $ctr++;
    }
    $output .= " </tbody>\n";
    $output .= "</table>\n";
    $output .= "<div class=\"buttons center\">\n";
    $buttonSave = new FormControl(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_SAVE, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: true, id: Constant::TEXT_SAVE, maxLength: NULL, name: Constant::TEXT_SAVE, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_SAVE, wrap: NULL, noValidate: false);
    $output .= $buttonSave->getHtml();
    $output .= "</div>\n";
} else {
    $output .= "<br />\nNo users require approval";
}
$hiddenMode = new FormControl(SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), NULL, NULL, false, NULL, NULL, NULL, false, Constant::FIELD_NAME_MODE, NULL, Constant::FIELD_NAME_MODE, NULL, NULL, false, NULL, NULL, NULL, NULL, FormControl::TYPE_INPUT_HIDDEN, $mode, NULL, noValidate: false);
$output .= $hiddenMode->getHtml();
$smarty->assign("content", $output);
$smarty->display("manage.tpl");
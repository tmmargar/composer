<?php
declare(strict_types = 1);
namespace ccp;
use DateTime;
use Poker\Ccp\Model\Constant;
use Poker\Ccp\Model\Email;
use Poker\Ccp\Model\GameType;
use Poker\Ccp\Model\Group;
use Poker\Ccp\Model\GroupPayout;
use Poker\Ccp\Model\LimitType;
use Poker\Ccp\Model\Location;
use Poker\Ccp\Model\Payout;
use Poker\Ccp\Model\Phone;
use Poker\Ccp\Model\Player;
use Poker\Ccp\Model\Result;
use Poker\Ccp\Model\SpecialType;
use Poker\Ccp\Model\Status;
use Poker\Ccp\Model\Structure;
use Poker\Ccp\Model\Tournament;
use Poker\Ccp\Utility\DateTimeUtility;
use Poker\Ccp\Utility\SessionUtility;
require_once "init.php";
if (Constant::FLAG_LOCAL()) {
    set_time_limit(seconds: 240);
}
$now = new DateTime();
$output .=
    "<script type=\"module\">\n" .
    "  import { dataTable, display, input } from \"./scripts/import.js\";\n" .
    "  let aryMessages = [];\n";
$output .= isset($mode) ? "  aryMessages.push(\"###Run at " . DateTimeUtility::formatDisplayLong(value: $now) . "###\");\n" : "\r";
$season = $entityManager->getRepository(entityName: Constant::ENTITY_SEASONS)->getActives();
$resultList = $entityManager->getRepository(Constant::ENTITY_RESULTS)->getOrderedSummary(currentDate: new DateTime(), startDate: $season->getSeasonStartDate(), endDate: $season->getSeasonEndDate(), championship: false, stats: false, email: true, indexed: true);
if (count($resultList) == 0) {
    $output .= isset($mode) ? "  aryMessages.push(\"No players needing auto email notification for championship qualification.\")\n" : "\r";
} else if ($resultList[0][3] > 5) {
    $output .= isset($mode) ? "  aryMessages.push(\"More than 5 tournaments left in the season so no notificiations are needed.\")\n" : "\r";
} else {
    foreach ($resultList as $row) {
        $players = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getById(playerId: $row[0]);
        $email = new Email(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), fromName: array(Constant::NAME_STAFF), fromEmail: array(Constant::EMAIL_STAFF()), toName: array($row[1]), toEmail: array($players[0]->getPlayerEmail()), ccName: NULL, ccEmail: NULL, bccName: NULL, bccEmail: NULL, subject: NULL, body: NULL);
        $output .= isset($mode) ? "  aryMessages.push(\"" . $email->sendReminderChampionshipQualificationEmail(left: $row[3], needed: $row[4]) . "\");\n" : "\r";
    }
}
$output .= "  if (aryMessages.length > 0) {display.showMessages({messages: aryMessages});}\n</script>\n";
if (isset($_GET[Constant::FIELD_NAME_MODE])) {
    $smarty->assign("title", "Chip Chair and a Prayer Auto Reminder Championship");
    $smarty->assign("heading", "Auto Reminder Championship");
    $smarty->assign("mode", Constant::MODE_VIEW);
    $smarty->assign("action", $_SERVER["SCRIPT_NAME"]);
    $smarty->assign("formName", "frmAutoReminderChamp");
    $smarty->assign("content", $output);
    $smarty->display("base.tpl");
} else {
    echo $output;
}
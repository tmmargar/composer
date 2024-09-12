<?php
declare(strict_types = 1);
namespace ccp;
use DateTime;
use Exception;
use Poker\Ccp\Model\Address;
use Poker\Ccp\Model\Constant;
use Poker\Ccp\Model\Email;
use Poker\Ccp\Model\Tournament;
use Poker\Ccp\Utility\DateTimeUtility;
use Poker\Ccp\Utility\SessionUtility;
use Poker\Ccp\Entity\Results;
require_once "init.php";
$now = new DateTime();
$output .=
    "<script type=\"module\">\n" .
    "  import { dataTable, display, input } from \"./scripts/import.js\";\n" .
    "  let aryMessages = [];\n";
$output .= isset($mode) ? "  aryMessages.push(\"###Run at " . DateTimeUtility::formatDisplayLong(value: $now) . "###\");\n" : "\r";
$dateTime = new DateTime();
$resultList = $entityManager->getRepository(entityName: Constant::ENTITY_TOURNAMENTS)->getRegisterHost(startDate: $dateTime, endDate: $dateTime);
if (count($resultList) == 0) {
    $output .= isset($mode) ? "  aryMessages.push(\"No tournaments needing auto host registration or host is already registered\");\n" : "\r";
} else {
    foreach ($resultList as $tournaments) {
        $player = $tournaments->getLocations()->getPlayers();
        $params = array($tournaments->getTournamentId(), $player->getPlayerId(), "N/A");
        $re = new Results();
        $re->setPlayers(players: $player);
        $re->setResultAddonFlag(resultAddonFlag: Constant::FLAG_NO);
        $re->setResultPaidAddonFlag(resultPaidAddonFlag: Constant::FLAG_NO);
        $re->setResultPaidBuyinFlag(resultPaidBuyinFlag: Constant::FLAG_NO);
        $re->setResultPaidRebuyFlag(resultPaidRebuyFlag: Constant::FLAG_NO);
        $re->setResultPlaceFinished(resultPlaceFinished: 0);
        $re->setResultRebuyCount(resultRebuyCount: 0);
        $re->setResultRegistrationFood(resultRegistrationFood: "N/A");
        $re->setResultRegistrationOrder(resultRegistrationOrder: 1);
        $statusCode = $entityManager->find(className: Constant::ENTITY_STATUS_CODES, id: Constant::CODE_STATUS_REGISTERED);
        $re->setStatusCodes(statusCodes: $statusCode);
        $tournaments = $entityManager->find(className: Constant::ENTITY_TOURNAMENTS, id: $tournaments->getTournamentId());
        $re->setTournaments(tournaments: $tournaments);
        $entityManager->persist(entity: $re);
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
        } else {
            $output .= isset($mode) ? "  aryMessages.push(\"Successfully registered " . $player->getPlayerName() . " for tournament on " . DateTimeUtility::formatDisplayDate(value: $tournaments->getTournamentDate()) . " starting at " . DateTimeUtility::formatDisplayTime(value: $tournaments->getTournamentStartTime()) . "\");\n" : "\r";
        }
        $emailTournament = new Tournament(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), id: 0, description: NULL, comment: NULL, limitType: NULL, gameType: NULL, specialType: NULL, chipCount: 0, location: NULL, date: NULL, startTime: NULL, buyinAmount: 0, maxPlayers: 0, maxRebuys: 0, rebuyAmount: 0, addonAmount: 0, addonChipCount: 0, groupPayout: NULL, rake: 0, registeredCount: 0, buyinsPaid: 0, rebuysPaid: 0, rebuysCount: 0, addonsPaid: 0, enteredCount: 0, earnings: 0);
        $emailTournament->createFromEntity(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), tournaments: $tournaments);
        $email = new Email(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), fromName: array(Constant::NAME_STAFF), fromEmail: array(Constant::EMAIL_STAFF()), toName: array($player->getPlayerName()), toEmail: array($player->getPlayerEmail()), ccName: NULL, ccEmail: NULL, bccName: NULL, bccEmail: NULL, subject: NULL, body: NULL);
        $output .= (isset($mode) ? "  aryMessages.push(\"" . $email->sendRegisteredEmail(location: $emailTournament->getLocation(), tournament: $emailTournament, feeStatus: "Paid", waitList: 0) . "\");\n" : "\r");
        $email = new Email(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), fromName: array(Constant::NAME_STAFF), fromEmail: array(Constant::EMAIL_STAFF()), toName: array(Constant::NAME_STAFF), toEmail: array(Constant::EMAIL_STAFF()), ccName: NULL, ccEmail: NULL, bccName: NULL, bccEmail: NULL, subject: NULL, body: NULL);
        $output .= isset($mode) ? "  aryMessages.push(\"" . $email->sendRegisteredEmail(location: $emailTournament->getLocation(), tournament: $emailTournament, feeStatus: "Paid", waitList: 0, autoRegister: $player->getPlayerName()) . "\");\n" : "\r";
  }
}
$output .= "  if (aryMessages.length > 0) {display.showMessages({messages: aryMessages});}\n</script>\n";
if (isset($_GET[Constant::FIELD_NAME_MODE])) {
    $smarty->assign("title", "Chip Chair and a Prayer Auto Register Host");
    $smarty->assign("heading", "Auto Register Host");
    $smarty->assign("mode", Constant::MODE_VIEW);
    $smarty->assign("action", $_SERVER["SCRIPT_NAME"]);
    $smarty->assign("formName", "frmAutoRegisterHost");
    $smarty->assign("content", $output);
    $smarty->display("base.tpl");
} else {
    echo $output;
}
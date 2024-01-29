<?php
declare(strict_types = 1);
namespace ccp;
use DateTime;
use Exception;
use Poker\Ccp\classes\model\Address;
use Poker\Ccp\classes\model\Constant;
use Poker\Ccp\classes\model\Email;
use Poker\Ccp\classes\model\Tournament;
use Poker\Ccp\classes\utility\SessionUtility;
use Poker\Ccp\Entity\Results;
require_once "init.php";
$now = new DateTime();
$output .=
    "<script type=\"module\">\n" .
    "  import { dataTable, display, input } from \"./scripts/import.js\";\n" .
    "  let aryMessages = [];\n";
$output .= isset($mode) ? "  aryMessages.push(\"###Run at " . $now->format("D, M j, Y h:i A") . "###\");\n" : "\r";
$dateTime = new DateTime();
$resultList = $entityManager->getRepository(Constant::ENTITY_TOURNAMENTS)->getRegisterHost(startDate: $dateTime, endDate: $dateTime);
if (count($resultList) == 0) {
    $output .= isset($mode) ? "  aryMessages.push(\"No tournaments needing auto host registration or host is already registered\");\n" : "\r";
} else {
    foreach ($resultList as $tournaments) {
        $player = $tournaments->getLocations()->getPlayers();
        $params = array($tournaments->getTournamentId(), $player->getPlayerId(), "N/A");
//         $rowCount = $databaseResult->insertRegistration(params: $params);
        $re = new Results();
//         $player = $entityManager->find(Constant::ENTITY_PLAYERS, (int) $player->getId());
        $re->setPlayers($player);
//         $re->setPlayerKos(null);
        $re->setResultAddonFlag(Constant::FLAG_NO);
        $re->setResultPaidAddonFlag(Constant::FLAG_NO);
        $re->setResultPaidBuyinFlag(Constant::FLAG_NO);
        $re->setResultPaidRebuyFlag(Constant::FLAG_NO);
        $re->setResultPlaceFinished(0);
        $re->setResultRebuyCount(0);
        $re->setResultRegistrationFood("N/A");
        $re->setResultRegistrationOrder(1);
        $statusCode = $entityManager->find(Constant::ENTITY_STATUS_CODES, Constant::CODE_STATUS_REGISTERED);
        $re->setStatusCodes($statusCode);
        $tournaments = $entityManager->find(Constant::ENTITY_TOURNAMENTS, $tournaments->getTournamentId());
        $re->setTournaments($tournaments);
        $entityManager->persist($re);
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
            $output .= isset($mode) ? "  aryMessages.push(\"Successfully registered " . $player->getPlayerName() . " for tournament on " . $tournaments->getTournamentDate()->format("m/d/Y") . " starting at " . $tournaments->getTournamentStartTime()->format("h:i A") . "\");\n" : "\r";
        }
        $emailTournament = new Tournament(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: 0, description: NULL, comment: NULL, limitType: NULL, gameType: NULL, specialType: NULL, chipCount: 0, location: NULL, date: NULL, startTime: NULL, buyinAmount: 0, maxPlayers: 0, maxRebuys: 0, rebuyAmount: 0, addonAmount: 0, addonChipCount: 0, groupPayout: NULL, rake: 0, registeredCount: 0, buyinsPaid: 0, rebuysPaid: 0, rebuysCount: 0, addonsPaid: 0, enteredCount: 0, earnings: 0);
        $emailTournament->createFromEntity(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), tournaments: $tournaments);
        $email = new Email(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), fromName: array(Constant::NAME_STAFF), fromEmail: array(Constant::EMAIL_STAFF()), toName: array($player->getPlayerName()), toEmail: array($player->getPlayerEmail()), ccName: NULL, ccEmail: NULL, bccName: NULL, bccEmail: NULL, subject: NULL, body: NULL);
        $output .= (isset($mode) ? "  aryMessages.push(\"" . $email->sendRegisteredEmail(location: $emailTournament->getLocation(), tournament: $emailTournament, feeStatus: "Paid", waitList: 0) . "\");\n" : "\r");
        $email = new Email(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), fromName: array(Constant::NAME_STAFF), fromEmail: array(Constant::EMAIL_STAFF()), toName: array(Constant::NAME_STAFF), toEmail: array(Constant::EMAIL_STAFF()), ccName: NULL, ccEmail: NULL, bccName: NULL, bccEmail: NULL, subject: NULL, body: NULL);
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
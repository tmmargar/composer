<?php
declare(strict_types = 1);
namespace Poker\Ccp;
use Poker\Ccp\classes\model\Address;
use Poker\Ccp\classes\model\Constant;
use Poker\Ccp\classes\model\DateTime;
use Poker\Ccp\classes\model\Email;
use Poker\Ccp\classes\model\FormControl;
use Poker\Ccp\classes\model\Phone;
use Poker\Ccp\classes\model\Tournament;
use Poker\Ccp\classes\utility\SessionUtility;
use DateInterval;
require_once "init.php";
define("TOURNAMENT_ID_PARAMETER_NAME", "tournamentId");
define("USER_ID_PARAMETER_NAME", "userId");
define("FOOD_FIELD_NAME", "food");
define("REGISTERED_FIELD_NAME", "registered");
define("REGISTERED_LABEL", "Registered");
define("UPDATE_REGISTER_TEXT", "Update");
define("WAIT_LIST_COUNT_FIELD_NAME", "waitListCount");
$smarty->assign("title", "Chip Chair and a Prayer Registration");
$output = "";
$smarty->assign("formName", "frmRegistration");
$tournamentId = (isset($_POST[TOURNAMENT_ID_PARAMETER_NAME]) ? $_POST[TOURNAMENT_ID_PARAMETER_NAME] : isset($_GET[TOURNAMENT_ID_PARAMETER_NAME])) ? $_GET[TOURNAMENT_ID_PARAMETER_NAME] : "";
$urlAction = $_SERVER["SCRIPT_NAME"] . "?tournamentId=" . $tournamentId;
$smarty->assign("action", $urlAction);
$smarty->assign("heading", "Registration");
$output .=
  "<script type=\"module\">\n" .
  "  import { dataTable, display, input } from \"./scripts/import.js\";\n" .
  "  let aryMessages = [];\n" .
  "  let aryErrors = [];\n";
if (!isset($tournamentId) || "" == $tournamentId) {
  $output .= " aryErrors.push(\"Unable to identify tournament to register for.\");\n";
} else {
  $userId = (isset($_POST[USER_ID_PARAMETER_NAME]) ? $_POST[USER_ID_PARAMETER_NAME] : isset($_GET[USER_ID_PARAMETER_NAME])) ? $_GET[USER_ID_PARAMETER_NAME] : SessionUtility::getValue(SessionUtility::OBJECT_NAME_USERID);
  $mode = isset($_POST[Constant::FIELD_NAME_MODE]) ? $_POST[Constant::FIELD_NAME_MODE] : Constant::MODE_VIEW;
  $now = new DateTime(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), id: NULL, time: "now");
  $registerText = Constant::TEXT_REGISTER;
  $registered = false;
  if (isset($_POST[REGISTERED_FIELD_NAME]) && (1 == $_POST[REGISTERED_FIELD_NAME])) {
    $registered = true;
  }
  $waitListCount = isset($_POST["waitListCount"]) ? $_POST["waitListCount"] : 0;
  if (Constant::MODE_SAVE_CREATE == $mode || Constant::MODE_SAVE_MODIFY == $mode) {
    $food = isset($_POST[FOOD_FIELD_NAME]) ? $_POST[FOOD_FIELD_NAME] : NULL;
    if (Constant::MODE_SAVE_CREATE == $mode) {
      if ($registered) {
        $params = array($food, $tournamentId, $userId);
        $databaseResult->updateRegistration(params: $params);
        $state = "updating your registration";
      } else {
        $params = array($tournamentId, $userId, $food);
        $databaseResult->insertRegistration(params: $params);
        $state = "registering";
      }
    } else {
      $params = array($tournamentId, $userId);
      $resultList = $databaseResult->getResultByTournamentIdAndPlayerId(params: $params);
      $registerOrder = $resultList[0]->getRegisterOrder();
      $databaseResult->deleteRegistration(params: $params);
      $registered = false;
      $state = "cancelling";
    }
    if (Constant::MODE_SAVE_CREATE != $mode) {
      $params = array($tournamentId, $registerOrder);
      $databaseResult->updateRegistrationCancel(params: $params);
      $waitListEmail = true;
    }
    $mode = Constant::MODE_SEND_EMAIL;
    $output .= "aryMessages.push(\"Thank you for " . $state . ". <a href='registrationList.php'>Click here</a> to register for more tournaments.\");\n";
  }
  if ($mode == Constant::MODE_VIEW || $mode == Constant::MODE_SEND_EMAIL) {
    $params = array($tournamentId);
    $paramsNested = array(SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_START_DATE)->getDatabaseFormat(), SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_END_DATE)->getDatabaseFormat(), SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_CHAMPIONSHIP_QUALIFY));
    $resultList = $databaseResult->getTournamentById(params: $params, paramsNested: $paramsNested);
    if (0 < count($resultList)) {
      $tournament = $resultList[0];
      $tournamentLocationUser = $tournament->getLocation()->getUser();
      $tournamentAddress = $tournament->getLocation()->getUser()->getAddress();
      $waitListCount = ($tournament->getRegisteredCount() > $tournament->getMaxPlayers()) ? ($tournament->getRegisteredCount() - $tournament->getMaxPlayers()) : 0;
      if ($mode == Constant::MODE_SEND_EMAIL) {
        // if set means email notification required and need location information from view query
        if (isset($state)) {
          $params = array($userId);
          $resultList = $databaseResult->getUserById(params: $params);
          if (0 < count($resultList)) {
            $user = $resultList[0];
            $paramsNested = array($userId, $tournamentId);
            $resultListNested = $databaseResult->getFeeByTournamentAndPlayer(params: $paramsNested);
            $feeStatus = $resultListNested[0]->getStatus();
            $email = new Email(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), fromName: array(Constant::NAME_STAFF), fromEmail: array(Constant::EMAIL_STAFF()), toName: array($user->getName()), toEmail: array($user->getEmail()), ccName: NULL, ccEmail: NULL, bccName: NULL, bccEmail: NULL, subject: NULL, body: NULL);
            $emailAddress = new Address(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: NULL, address: $tournamentAddress->getAddress(), city: $tournamentAddress->getCity(), state: $tournamentAddress->getState(), zip: $tournamentAddress->getZip());
//             $debug, $id, $description, $comment, $limitType, $gameType, $specialType, $chipCount, $location, $date, $startTime, $endTime, $buyinAmount, $maxPlayers, $maxRebuys, $rebuyAmount, $addonAmount, $addonChipCount, $groupPayout, $rake, $registeredCount, $buyinsPaid, $rebuysPaid, $rebuysCount, $addonsPaid, $enteredCount
            $emailTournament = new Tournament(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: $tournament->getId(), description: NULL, comment: NULL, limitType: NULL, gameType: NULL, specialType: NULL, chipCount: 0, location: NULL, date: $tournament->getDate(), startTime: $tournament->getStartTime(), endTime: NULL, buyinAmount: 0, maxPlayers: 0, maxRebuys: 0, rebuyAmount: 0, addonAmount: 0, addonChipCount: 0, groupPayout: NULL, rake: 0, registeredCount: 0, buyinsPaid: 0, rebuysPaid: 0, rebuysCount: 0, addonsPaid: 0, enteredCount: 0);
            if ("cancelling" == $state) {
              $output .= "aryMessages.push(\"" . $email->sendCancelledEmail(address: $emailAddress, tournament: $emailTournament) . "\");\n";
            } else {
              $output .= "aryMessages.push(\"" . $email->sendRegisteredEmail(address: $emailAddress, tournament: $emailTournament, feeStatus: $feeStatus, waitList: $waitListCount) . "\");\n";
            }
          }
          if (isset($waitListEmail)) {
            // send email to person who moved from wait list to registered
            $params = array($tournamentId);
            $resultList = $databaseResult->getWaitListedPlayerByTournamentId(params: $params);
            if (0 < count($resultList)) {
              $waitListName = $resultList[1];
              $waitListEmail = $resultList[2];
              $paramsNested = array($resultList[0], $tournamentId);
              $resultListNested = $databaseResult->getFeeByTournamentAndPlayer(params: $paramsNested);
              $feeStatus = $resultListNested[0]->getStatus();
              $email = new Email(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), fromName: array(Constant::NAME_STAFF), fromEmail: array(Constant::EMAIL_STAFF()), toName: array($waitListName), toEmail: array($waitListEmail), ccName: NULL, ccEmail: NULL, bccName: NULL, bccEmail: NULL, subject: NULL, body: NULL);
              $emailAddress = new Address(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: NULL, address: $tournamentAddress->getAddress(), city: $tournamentAddress->getCity(), state: $tournamentAddress->getState(), zip: $tournamentAddress->getZip(), phone: NULL);
              $emailTournament = new Tournament(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: $tournament->getId(), description: NULL, comment: NULL, limitType: NULL, gameType: NULL, specialType: NULL, chipCount: 0, location: NULL, date: $tournament->getDate(), startTime: $tournament->getStartTime(), endTime: NULL, buyinAmount: 0, maxPlayers: 0, maxRebuys: 0, rebuyAmount: 0, addonAmount: 0, addonChipCount: 0, groupPayout: NULL, rake: 0, registeredCount: 0, buyinsPaid: 0, rebuysPaid: 0, rebuysCount: 0, addonsPaid: 0, enteredCount: 0);
              $output .= "aryMessages.push(\"" . $email->sendRegisteredEmail(address: $emailAddress, tournament: $emailTournament, feeStatus: $feeStatus, waitList: -99) . "\");\n";
              // send email to CCP staff
              $email = new Email(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), fromName: array(Constant::NAME_STAFF), fromEmail: array(Constant::EMAIL_STAFF()), toName: array(Constant::NAME_STAFF), toEmail: array(Constant::EMAIL_STAFF()), ccName: NULL, ccEmail: NULL, bccName: NULL, bccEmail: NULL, subject: NULL, body: NULL);
              $emailAddress = new Address(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: NULL, address: $tournamentAddress->getAddress(), city: $tournamentAddress->getCity(), state: $tournamentAddress->getState(), zip: $tournamentAddress->getZip(), phone: NULL);
              $output .= "aryMessages.push(\"" . $email->sendRegisteredEmail(address: $emailAddress, tournament: $emailTournament, feeStatus: $feeStatus, waitList: $user->getName() . " un-registered and " . $waitListName) . "\");\n";
            }
          }
        }
        $output .= "  if (aryErrors.length > 0) {display.showErrors({errors: aryErrors});}\n";
        $output .= "  if (aryMessages.length > 0) {display.showMessages({messages: aryMessages});}\n</script>\n";
      } else {
        $output .= "  if (aryErrors.length > 0) {display.showErrors({errors: aryErrors});}\n";
        $output .= "  if (aryMessages.length > 0) {display.showMessages({messages: aryMessages});}\n</script>\n";
        $output .= "  <div class=\"column2\">" . $tournament->getDescription() . "</div>\n";
        $output .= "  <div class=\"clear\"></div>\n";
        $output .= "  <div class=\"column2\">" . $tournament->getComment() . "</div>\n";
        $output .= "  <div class=\"clear\"></div>\n";
        $output .= "  <div class=\"column2\">Hosted by " . $tournament->getLocation()->getUser()->getName() . "</div>\n";
        $output .= "  <div class=\"clear\"></div>\n";
        $output .= "  <div class=\"column2\">" . $tournament->getLimitType()->getName() . " " . $tournament->getGameType()->getName() . "</div>\n";
        $output .= "  <div class=\"clear\"></div>\n";
        $output .= "  <div class=\"column2\">$" . - $tournament->getBuyinAmount() . " for " . $tournament->getChipCount() . " chips</div>\n";
        $output .= "  <div class=\"clear\"></div>\n";
        $output .= "  <div class=\"column2\">" . $tournament->getMaxRebuys() . " rebuy(s), $" . - $tournament->getRebuyAmount() . " for " . $tournament->getChipCount() . " chips</div>\n";
        if (0 != $tournament->getAddonAmount()) {
          $output .= "  <div class=\"clear\"></div>\n";
          $output .= "  <div class=\"column2\">addon, $" . - $tournament->getAddonAmount() . " for " . $tournament->getAddonChipCount() . " chips</div>\n";
        }
        $output .= "  <div class=\"clear\"></div>\n";
        $output .= "  <br />\n";
        $output .= "  <div class=\"column\">Date / Time:</div>\n";
        $output .= "  <div>" . $tournament->getDate()->getDisplayLongFormat() . " " . $tournament->getStartTime()->getDisplayAmPmFormat() . "</div>\n";
        $output .= "  <div class=\"clear\"></div>\n";
        $output .= "  <div class=\"column\">Map:</div>\n";
        $output .= "  <div>" . $tournament->getLocation()->buildMapUrl() . "</div>\n";
        $output .= "  <div class=\"clear\"></div>\n";
        $output .= "  <div class=\"column\">Location:</div>\n";
        $phone = new Phone(SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), NULL, (string) $tournamentLocationUser->getPhone()->getValue());
        $output .= "  <div style=\"float: left;\">" . $tournamentAddress->getAddress() . "<br />" . $tournamentAddress->getCity() . ", " . $tournamentAddress->getState() . " " . $tournamentAddress->getZip() . "<br />" . $phone->getDisplayFormatted() . "</div>\n";
        $output .= "  <div class=\"clear\"></div>\n";
        $output .= "  <div class=\"column\">Available:</div>\n";
        if ($tournament->getDescription() == "Championship") {
          $maxPlayers = 36;
        } else {
          $maxPlayers = $tournament->getMaxPlayers();
        }
        $dateTimeRegistrationClose = new DateTime(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: NULL, time: $tournament->getDate()->getDatabaseFormat() . " " . $tournament->getRegistrationClose()->getDisplayAmPmFormat());
        // $registrationCloseDate = $dateTimeRegistrationClose->getDatabaseFormat();
        $tournamentDateClone = clone $tournament->getDate();
        $registrationOpenDate = new DateTime(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: NULL, time: $tournamentDateClone->getDatabaseFormat() . " 12:00:00");
        $interval = new DateInterval(Constant::INTERVAL_DATE_REGISTRATION_OPEN);
        $registrationOpenDate->getTime()->sub($interval);
        if ($tournament->getDescription() == "Championship") {
          $registeredCount = 0;
        } else {
          if ($now < $dateTimeRegistrationClose) {
            $registeredCount = ($tournament->getRegisteredCount() <= $maxPlayers) ? ($maxPlayers - $tournament->getRegisteredCount()) : 0;
          } else {
            $registeredCount = 0;
          }
        }
        $output .= "  <div>" . $registeredCount . " out of " . $maxPlayers . "</div>\n";
        $output .= "  <div class=\"clear\"></div>\n";
        $output .= "  <div class=\"column\">Wait list #:</div>\n";
        $output .= "  <div>" . $waitListCount . "</div>\n";
        $output .= "  <div class=\"clear\"></div>\n";
        $output .= "  <div class=\"column\">Deadline:</div>\n";
        // $output .= " <div>" . DateTimeUtility::getDateAndTimeDisplayLongFormat($registrationCloseDate) . "</div>\n";
        // $dateTime = new DateTime(SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), NULL, $registrationCloseDate);
        $output .= "  <div>" . $dateTimeRegistrationClose->getDisplayLongTimeFormat() . "</div>\n";
        $output .= "  <div class=\"clear\"></div>\n";
        $params = array($tournamentId, $userId);
        $resultList = $databaseResult->getFoodByTournamentIdAndPlayerId(params: $params);
//         var_dump($resultList);
        $output .= "  <br />\n";
        if (0 < count($resultList)) {
          $output .= "  <div class=\"column\">Name:</div>\n";
          $output .= "  <div>" . $resultList[0] . "</div>\n";
          $output .= "  <div class=\"clear\"></div>\n";
          $output .= "  <div class=\"column\">Email:</div>\n";
          $output .= "  <div>" . $resultList[1] . "</div>\n";
          $output .= "  <div class=\"clear\"></div>\n";
          $output .= "  <div class=\"column\">Dish:</div>\n";
          $output .= "  <div>";
          $food = $resultList[2];
          if (($now->getTime() >= $registrationOpenDate->getTime()) && ($now->getTime() <= $dateTimeRegistrationClose->getTime())) {
            $textBoxName = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_FOOD, autoComplete: NULL, autoFocus: true, checked: NULL, class: NULL, cols: NULL, disabled: false, id: FOOD_FIELD_NAME, maxLength: NULL, name: FOOD_FIELD_NAME, onClick: NULL, placeholder: NULL, readOnly: false, required: true, rows: NULL, size: 20, suffix: NULL, type: FormControl::TYPE_INPUT_TEXTBOX, value: $food, wrap: NULL);
            $output .= $textBoxName->getHtml();
          } else {
            $output .= $food;
          }
          $output .= "</div>\n";
          $output .= "  <div class=\"clear\"></div>\n";
        }
        $output2 = "  <h3 class=\"center\">" . REGISTERED_LABEL . "</h3>\n";
        $output2 .= "  <div class=\"columnHeader\">Name</div>\n";
        $output2 .= "  <div class=\"column1Header\">Season Fee</div>\n";
        $output2 .= "  <div class=\"columnHeader\">Food</div>\n";
        $output2 .= "  <div class=\"clear\" style=\"padding-bottom: 2px;\"></div>\n";
        $params = array($tournamentId);
        $resultList = $databaseResult->getResultRegisteredByTournamentId(params: $params);
        if (0 < count($resultList)) {
          $count = 0;
          $registered = false;
          foreach ($resultList as $result) {
            if ($userId == $result->getUser()->getId()) {
              $registered = true;
              $registerText = UPDATE_REGISTER_TEXT;
            }
            // wait list goes here
            if ($count == $maxPlayers) {
              $output2 .= " <h3>Wait List</h3>";
            }
            $output2 .= "  <div class=\"column\">" . $result->getUser()->getName() . "</div>\n";
            $output2 .= "  <div class=\"column1\">" . $result->getFeeStatus() . "</div>\n";
            $output2 .= "  <div>" . NULL !== $result->getFood() ? $result->getFood() : "" . "</div>\n";
            $output2 .= "  <div class=\"clear\" style=\"padding-bottom: 2px;\"></div>\n";
            $count ++;
          }
        } else {
          $output2 .= "  None\n";
        }
        // if not registered and there is a wait list
        if (!$registered && isset($count) && $count >= $maxPlayers) {
          $registerText = "Add to wait list";
        }
        // $params = array($userId, $startDate, $endDate);
        $params = array($userId, SessionUtility::getValue(SessionUtility::OBJECT_NAME_START_DATE)->getDatabaseFormat(), SessionUtility::getValue(SessionUtility::OBJECT_NAME_END_DATE)->getDatabaseFormat());
        $resultList = $databaseResult->getTournamentsPlayedByPlayerIdAndDateRange(params: $params);
        if (0 < count($resultList)) {
          $numPlayed = $resultList[0];
        }
        // check in registration range and not full
        if (($now->getTime() >= $registrationOpenDate->getTime()) && ($now->getTime() <= $dateTimeRegistrationClose->getTime())) {
          if ($tournament->getDescription() == "Championship" && SessionUtility::getValue(SessionUtility::OBJECT_NAME_CHAMPIONSHIP_QUALIFY) > $numPlayed) {
            $output .=
              "<script type=\"module\">\n" .
              "  import { dataTable, display, input } from \"./scripts/import.js\";\n" .
              "  aryMessages = [];\n" .
              "  aryErrors = [];\n";
            $output .= "  aryErrors.push(\"You are not allowed to register for the Championship because you only played " . $numPlayed . " tournaments and did not meet the " . SessionUtility::getValue(SessionUtility::OBJECT_NAME_CHAMPIONSHIP_QUALIFY) . " tournament minimum to qualify.\");\n";
            $output .= "  if (aryErrors.length > 0) {display.showErrors({errors: aryErrors});}\n";
            $output .= "  if (aryMessages.length > 0) {display.showMessages({messages: aryMessages});}\n</script>\n";
          } else {
            $output .= " <div class=\"center\">\n";
            $buttonRegister = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_REGISTER, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::TEXT_REGISTER, maxLength: NULL, name: Constant::TEXT_REGISTER, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: $registerText, wrap: NULL);
            $output .= $buttonRegister->getHtml();
            $buttonUnregister = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: Constant::ACCESSKEY_UNREGISTER, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: (!$registered ? true : false), id: Constant::TEXT_UNREGISTER, maxLength: NULL, name: Constant::TEXT_UNREGISTER, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_SUBMIT, value: Constant::TEXT_UNREGISTER, wrap: NULL, import: NULL, noValidate: true);
            $output .= $buttonUnregister->getHtml();
            $output .= " </div>\n";
          }
        }
        $output .= $output2;
        $hiddenMode = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: Constant::FIELD_NAME_MODE, maxLength: NULL, name: Constant::FIELD_NAME_MODE, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: $mode, wrap: NULL);
        $output .= $hiddenMode->getHtml();
        $hiddenRegistered = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: REGISTERED_FIELD_NAME, maxLength: NULL, name: REGISTERED_FIELD_NAME, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: (string) $registered, wrap: NULL);
        $output .= $hiddenRegistered->getHtml();
        $hiddenWaitListCount = new FormControl(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: NULL, cols: NULL, disabled: false, id: WAIT_LIST_COUNT_FIELD_NAME, maxLength: NULL, name: WAIT_LIST_COUNT_FIELD_NAME, onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: (string) $waitListCount, wrap: NULL);
        $output .= $hiddenWaitListCount->getHtml();
      }
    }
  }
}
$smarty->assign("content", $output);
$smarty->display("registration.tpl");
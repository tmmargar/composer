<?php
declare(strict_types = 1);
namespace ccp;
use DateTime;
use Poker\Ccp\Model\Constant;
require_once "init.php";
define("TOURNAMENT_ID_PARAMETER_NAME", "tournamentId");
$smarty->assign("title", "Chip Chair and a Prayer Registration");
$output = "";
$output2 = "";
$smarty->assign("formName", "frmRegistration");
$tournamentId = (isset($_POST[TOURNAMENT_ID_PARAMETER_NAME]) ? $_POST[TOURNAMENT_ID_PARAMETER_NAME] : isset($_GET[TOURNAMENT_ID_PARAMETER_NAME])) ? $_GET[TOURNAMENT_ID_PARAMETER_NAME] : "";
$urlAction = $_SERVER["SCRIPT_NAME"] . "?tournamentId=" . $tournamentId;
$smarty->assign("action", $urlAction);
$smarty->assign("heading", "Registration");
$output .= "<script type=\"module\">\n" . "  import { dataTable, display, input } from \"../scripts/import.js\";\n" . "  let aryMessages = [];\n" . "  let aryErrors = [];\n" . "</script>\n";
$mode = isset($_POST[Constant::FIELD_NAME_MODE]) ? $_POST[Constant::FIELD_NAME_MODE] : Constant::MODE_VIEW;
$tournamentDate = isset($_GET["tournamentDate"]) ? $_GET["tournamentDate"] : "";
$max = $_GET["max"] == "Y" ? true : false;
$entityManager = getEntityManager();
// date (YYYY-MM-DD) and true if max false if not
$resultList = $entityManager->getRepository(entityName: Constant::ENTITY_TOURNAMENTS)->getRegistrationList(tournamentDate: new DateTime(datetime: $tournamentDate), max: $max);
if (0 < count($resultList)) {
    $count = 0;
    $registered = false;
    $output2 .= " <table id=\"output\">\n <tbody>\n";
    foreach ($resultList as $result) {
        $output2 .= "  <tr>\n";
        $output2 .= "   <td>" . $result["player_first_name"] . " " . $result["player_last_name"] . "</td>\n";
        $output2 .= "   <td>" . $result["result_registration_food"] . "</td>\n";
        $output2 .= "   <td>" . $result["fee status"] . "</td>\n";
        $output2 .= "  </tr>\n";
        $count++;
    }
    $output2 .= "</tbody>\n</table>\n";
} else {
    $output2 .= "  None\n";
}
$output .= $output2;
$smarty->assign("content", $output);
$smarty->display("registration_svc.tpl");
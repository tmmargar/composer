<?php
declare(strict_types = 1);
namespace ccp;
use Poker\Ccp\classes\model\Constant;
use Poker\Ccp\classes\utility\SessionUtility;
$header = "";
$smarty->assign("header", $header);
$aryScriptFull = explode(separator: "/", string: $_SERVER["SCRIPT_NAME"]);
$scriptName = $aryScriptFull[count(value: $aryScriptFull) - 1];
$aryScript = explode(separator: ".", string: $scriptName);
$scriptNameNoExtension = $aryScript[0];
$smarty->assign("script", "<script src=\"../scripts/" . $scriptNameNoExtension . ".js\" type=\"module\"></script>\n");
$smarty->assign("style", "");
$smarty->assign("action", $_SERVER["SCRIPT_NAME"]);
$mode = isset($_POST[Constant::FIELD_NAME_MODE]) ? $_POST[Constant::FIELD_NAME_MODE] : (isset($_GET[Constant::FIELD_NAME_MODE]) ? $_GET[Constant::FIELD_NAME_MODE] : Constant::MODE_VIEW);
$smarty->assign("mode", $mode);
$ids = isset($_POST[SELECTED_ROWS_FIELD_NAME]) ? $_POST[SELECTED_ROWS_FIELD_NAME] : DEFAULT_VALUE_BLANK;
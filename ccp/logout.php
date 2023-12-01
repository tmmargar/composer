<?php
declare(strict_types = 1);
namespace ccp;
use Poker\Ccp\classes\utility\SessionUtility;
require_once "init.php";
echo SessionUtility::destroy();
header("Location:login.php?" . $_SERVER["QUERY_STRING"]);
<?php
declare(strict_types = 1);
namespace ccp;
use Poker\Ccp\classes\model\SmartyLocal;
require_once "vendor/autoload.php";
$smartyCcp = new SmartyLocal();
$smartyCcp->initialize(debug: false);
// variable used in individual pages
$smarty = $smartyCcp->getSmarty();
<?php
declare(strict_types = 1);
namespace ccp;
use Poker\Ccp\Model\SmartyLocalService;
$smartyCcp = new SmartyLocalService();
$smartyCcp->initialize(debug: false);
// variable used in individual pages
$smarty = $smartyCcp->getSmarty();
<?php
declare(strict_types = 1);
namespace Poker\Ccp;
use Poker\Ccp\classes\model\SmartyLocalService;
  $smartyCcp = new SmartyLocalService();
  $smartyCcp->initialize(debug: false);
  // variable used in individual pages
  $smarty = $smartyCcp->getSmarty();
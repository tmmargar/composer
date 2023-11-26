<?php
declare(strict_types = 1);
namespace Poker\Ccp;
use Poker\Ccp\classes\model\SmartyLocal;
  $smartyCcp = new SmartyLocal();
  $smartyCcp->initialize(debug: false);
  // variable used in individual pages
  $smarty = $smartyCcp->getSmarty();
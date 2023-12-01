<?php
declare(strict_types = 1);
namespace ccp;
require_once "init.php";
$smarty->assign("title", "Chip Chair and a Prayer Home");
$parentObjectId = "";
$outputHome = " <div class=\"responsive responsive--3cols responsive--collapse\">\n";
$reportId = "prizePoolForSeason";
$outputHome .=
  "  <div class=\"responsive-cell\">\n" .
  "  </div>\n" .
  "  <div class=\"responsive-cell\">\n" .
  include "top5.php";
$outputHome .=
  "  </div>\n" .
  "  <div class=\"responsive-cell\">\n" .
  "  </div>\n" .
  "  <div class=\"responsive-cell\">\n";
$reportId = "pointsTotalForSeason";
$outputHome .= include "top5.php";
$outputHome .=
  "  </div>\n" .
  "  <div class=\"responsive-cell\">\n";
$reportId = "earningsTotalForSeason";
$outputHome .= include "top5.php";
$outputHome .=
  "  </div>\n" .
  "  <div class=\"responsive-cell\">\n";
$reportId = "knockoutsTotalForSeason";
$outputHome .= include "top5.php";
$outputHome .=
  "  </div>\n" .
  "  <div class=\"responsive-cell\">\n";
$reportId = "winnersForSeason";
$outputHome .= include "top5.php";
$outputHome .=
  "  </div>\n" .
  "  <div class=\"responsive-cell\">\n" .
  include "chartGauge.php";
$outputHome .=
  " </div>\n" .
  "  <div class=\"center responsive-cell\">\n";
// $reportId = "earningsTotalForSeasonForPlayer";
// $parentObjectId = "widget6";
$limitCount = 4;
$outputHome .= include "registrationList.php";
$outputHome .= " </div>\n";
$smarty->assign("content", $outputHome);
$smarty->display("home.tpl");
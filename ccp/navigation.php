<?php
declare(strict_types = 1);
namespace Poker\Ccp;
use Poker\Ccp\classes\model\Constant;
use Poker\Ccp\classes\model\DateTime;
use Poker\Ccp\classes\model\HtmlLink;
use Poker\Ccp\classes\model\HtmlMenu;
use Poker\Ccp\classes\utility\SessionUtility;
// $accessKey, $class, $debug, $href, $id, $paramName, $paramValue, $tabIndex, $text, $title)
$htmlLinkHome = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "home.php", id: NULL, paramName: NULL, paramValue: NULL, tabIndex: - 1, text: "Home", title: NULL);
$htmlLinkEvents = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "registrationList.php", id: NULL, paramName: NULL, paramValue: NULL, tabIndex: - 1, text: "Events", title: NULL);
$htmlLinkChampionship = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "championship.php", id: NULL, paramName: NULL, paramValue: NULL, tabIndex: - 1, text: "Championship Seating", title: NULL);
// $debug, $id, $items, $text
$htmlMenuResults = new HtmlMenu(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: NULL, items: NULL, text: "Results");
$params = array("desc");
$queryResultYears = $databaseResult->getTournamentYearsPlayed(params: $params);
$counterOverall = count($queryResultYears);
$counterLoop = 1;
$counterResultSeasonGroup = 0;
$counterResultSeason = 0;
foreach ($queryResultYears as $rowYears) {
  $htmlLinkResultArray = array();
  $htmlLinkResultArrayCounter = 0;
  if ($counterLoop == 1) {
    // $htmlLinkResultArray = array();
    // $htmlLinkResultArrayCounter = 0;
    $htmlMenuResultSeasonGroup = new HtmlMenu(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: NULL, items: NULL, text: "Seasons " . $counterOverall . " to " . ($counterOverall - 4 == 0 ? 1 : $counterOverall - 4));
    $htmlMenuResultSeasonGroupArray[$counterResultSeasonGroup] = $htmlMenuResultSeasonGroup;
    $counterResultSeasonGroup ++;
    $htmlMenuResultSeasonArray = array();
  }
  $htmlMenuResultSeason = new HtmlMenu(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: NULL, items: NULL, text: $rowYears . " (Season " . ($rowYears - DateTime::YEAR_FIRST_SEASON) . ")");
  $htmlMenuResultSeasonArray[$counterResultSeason] = $htmlMenuResultSeason;
  $counterResultSeason ++;
  $htmlMenuResultSeasonGroup->setItems($htmlMenuResultSeasonArray);
  $params = array($rowYears);
  $queryResultAll = $databaseResult->getTournamentAll(params: $params);
  if (0 < count($queryResultAll)) {
    foreach ($queryResultAll as $rowAll) {
      $description = NULL !== $rowAll->getDescription() ? count(explode(" - ", $rowAll->getDescription())) > 1 ? explode(" - ", $rowAll->getDescription())[1] : explode(" - ", $rowAll->getDescription())[0] : "";
      $htmlLinkResult = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "reports.php", id: NULL, paramName: array("reportId","tournamentId"), paramValue: array("results",$rowAll->getId()), tabIndex: - 1, text: $description . " (" . $rowAll->getDate()->getDisplayFormat() . ")", title: NULL);
      $htmlLinkResultArray[$htmlLinkResultArrayCounter] = $htmlLinkResult;
      $htmlLinkResultArrayCounter ++;
    }
  }
  $htmlMenuResultSeason->setItems($htmlLinkResultArray);
  if ($counterLoop == 5 || $counterOverall == 1) {
    $counterLoop = 1;
    $counterResultSeason = 0;
  } else {
    $counterLoop ++;
  }
  $counterOverall --;
}
$htmlMenuResults->setItems($htmlMenuResultSeasonGroupArray);
$htmlMenuStats = new HtmlMenu(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: NULL, items: NULL, text: "Stats");
$htmlLinkMyStats = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "personalize.php", id: NULL, paramName: NULL, paramValue: NULL, tabIndex: - 1, text: "My Stats", title: NULL);
$params = array(false);
$resultListUsers = $databaseResult->getUsersAll(params: $params);
$counterOverall = 1;
$counterLoop = 1;
$counterUserGroup = 0;
foreach ($resultListUsers as $user) {
  if ($counterLoop == 1) {
    $htmlLinkUserArray = array();
    $htmlLinkUserArrayCounter = 0;
    $htmlMenuUserGroup = new HtmlMenu(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: NULL, items: NULL, text: "Users " . $counterOverall . " to " . ($counterOverall + 9));
    $htmlMenuUserGroupArray[$counterUserGroup] = $htmlMenuUserGroup;
    $counterUserGroup ++;
  }
  $htmlLinkUser = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "personalize.php", id: NULL, paramName: array("userId"), paramValue: array($user->getId()), tabIndex: - 1, text: $user->getName() . "'s stats", title: NULL);
  $htmlLinkUserArray[$htmlLinkUserArrayCounter] = $htmlLinkUser;
  $htmlLinkUserArrayCounter ++;
  if ($counterLoop == 10 || $counterOverall == count($resultListUsers)) {
    $htmlMenuUserGroup->setItems($htmlLinkUserArray);
    $counterLoop = 1;
  } else {
    $counterLoop ++;
  }
  $counterOverall ++;
}
// echo "<br>" . print_r($htmlLinkUsers, true);
// echo "<br>" . print_r($htmlLinkUserArray, true);
$htmlMenuOtherStats = new HtmlMenu(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: NULL, items: NULL, text: "Other Users");
$htmlMenuOtherStats->setItems($htmlMenuUserGroupArray);
$htmlStatsArray = array($htmlLinkMyStats,$htmlMenuOtherStats);
$htmlMenuStats->setItems($htmlStatsArray);
$htmlMenuReports = new HtmlMenu(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: NULL, items: NULL, text: "Reports");
$htmlMenuReportStandard = new HtmlMenu(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: NULL, items: NULL, text: "Standard");
$queryResult = $databaseResult->getResultIdMax(NULL);
$htmlLinkResults = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "reports.php", id: NULL, paramName: array("reportId","tournamentId"), paramValue: array("results",$queryResult[0]), tabIndex: - 1, text: "Results", title: NULL);
$htmlLinkTotalPoints = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "reports.php", id: NULL, paramName: array("reportId","seasonSelection"), paramValue: array("pointsTotal","show"), tabIndex: - 1, text: "Total pts", title: NULL);
$htmlLinkEarnings = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "reports.php", id: NULL, paramName: array("reportId","seasonSelection"), paramValue: array("earnings","show"), tabIndex: - 1, text: "Earnings", title: NULL);
$htmlLinkEarningsChampionship = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "reports.php", id: NULL, paramName: array("reportId","seasonSelection"), paramValue: array("earningsChampionship","show"), tabIndex: - 1, text: "Earnings (Championship)", title: NULL);
$htmlLinkKnockouts = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "reports.php", id: NULL, paramName: array("reportId","seasonSelection"), paramValue: array("knockouts","show"), tabIndex: - 1, text: "KOs", title: NULL);
$htmlLinkSummary = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "reports.php", id: NULL, paramName: array("reportId","seasonSelection"), paramValue: array("summary","show"), tabIndex: - 1, text: "Summary", title: NULL);
$htmlLinkWinners = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "reports.php", id: NULL, paramName: array("reportId","seasonSelection"), paramValue: array("winners","show"), tabIndex: - 1, text: "Winners", title: NULL);
$htmlLinkFinishes = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "reports.php", id: NULL, paramName: array("reportId","seasonSelection"), paramValue: array("finishes","show"), tabIndex: - 1, text: "Finishes", title: NULL);
$htmlLinkFees = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "reports.php", id: NULL, paramName: array("reportId"), paramValue: array("fees","show"), tabIndex: - 1, text: "Fees", title: NULL);
$htmlLinkStandardArray = array($htmlLinkResults,$htmlLinkTotalPoints,$htmlLinkEarnings,$htmlLinkEarningsChampionship,$htmlLinkKnockouts,$htmlLinkSummary,$htmlLinkWinners,$htmlLinkFinishes,$htmlLinkFees);
$htmlMenuReportStandard->setItems($htmlLinkStandardArray);
$htmlMenuReportSeason = new HtmlMenu(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: NULL, items: NULL, text: "Season");
$htmlLinkPrizePool = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "top5.php", id: NULL, paramName: array("reportId","navigation"), paramValue: array("prizePoolForSeason","Y"), tabIndex: - 1, text: "Prize pool", title: NULL);
$htmlLinkTotalPoints = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "top5.php", id: NULL, paramName: array("reportId","navigation"), paramValue: array("pointsTotalForSeason","Y"), tabIndex: - 1, text: "Total points", title: NULL);
$htmlLinkAveragePoints = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "top5.php", id: NULL, paramName: array("reportId","navigation"), paramValue: array("pointsAverageForSeason","Y"), tabIndex: - 1, text: "Average points", title: NULL);
$htmlLinkTotalEarnings = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "top5.php", id: NULL, paramName: array("reportId","navigation"), paramValue: array("earningsTotalForSeason","Y"), tabIndex: - 1, text: "Total Earnings", title: NULL);
$htmlLinkAverageEarnings = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "top5.php", id: NULL, paramName: array("reportId","navigation"), paramValue: array("earningsAverageForSeason","Y"), tabIndex: - 1, text: "Average Earnings", title: NULL);
$htmlLinkTotalKnockouts = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "top5.php", id: NULL, paramName: array("reportId","navigation"), paramValue: array("knockoutsTotalForSeason","Y"), tabIndex: - 1, text: "Total knockouts", title: NULL);
$htmlLinkAverageKnockouts = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "top5.php", id: NULL, paramName: array("reportId","navigation"), paramValue: array("knockoutsAverageForSeason","Y"), tabIndex: - 1, text: "Average knockouts", title: NULL);
$htmlLinkWinners = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "top5.php", id: NULL, paramName: array("reportId","navigation"), paramValue: array("winnersForSeason","Y"), tabIndex: - 1, text: "Winners", title: NULL);
$htmlLinkSeasonArray = array($htmlLinkPrizePool,$htmlLinkTotalPoints,$htmlLinkAveragePoints,$htmlLinkTotalEarnings,$htmlLinkAverageEarnings,$htmlLinkTotalKnockouts,$htmlLinkAverageKnockouts,$htmlLinkWinners);
$htmlMenuReportSeason->setItems($htmlLinkSeasonArray);
$htmlMenuReportChampionship = new HtmlMenu(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: NULL, items: NULL, text: "Championship");
$htmlLinkByYearByEarnings = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "reports.php", id: NULL, paramName: array("reportId","sort"), paramValue: array("championship","0 down,2 down"), tabIndex: - 1, text: "By year by earnings", title: NULL);
$htmlLinkByNameByYear = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "reports.php", id: NULL, paramName: array("reportId","sort"), paramValue: array("championship","1 up,0 down"), tabIndex: - 1, text: "By name by year", title: NULL);
$htmlLinkByNameByEarningsByYear = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "reports.php", id: NULL, paramName: array("reportId","sort"), paramValue: array("championship","1 up,2 down"), tabIndex: - 1, text: "By name by earnings by year", title: NULL);
$htmlLinkByNameByEarnings = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "reports.php", id: NULL, paramName: array("reportId","sort","group"), paramValue: array("championship","2 down,1 up","true"), tabIndex: - 1, text: "By name by earnings", title: NULL);
$htmlLinkChampionshipArray = array($htmlLinkByYearByEarnings,$htmlLinkByNameByYear,$htmlLinkByNameByEarningsByYear,$htmlLinkByNameByEarnings);
$htmlMenuReportChampionship->setItems($htmlLinkChampionshipArray);
$htmlMenuReportRanking = new HtmlMenu(SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: NULL, items: NULL, text: "Ranking");
$htmlLinkTotalPoints = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "top5.php", id: NULL, paramName: array("reportId","navigation"), paramValue: array("pointsTotalForUser","Y"), tabIndex: - 1, text: "Total pts", title: NULL);
$htmlLinkAveragePoints = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "top5.php", id: NULL, paramName: array("reportId","navigation"), paramValue: array("pointsAverageForUser","Y"), tabIndex: - 1, text: "Average points", title: NULL);
$htmlLinkTotalKnockouts = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "top5.php", id: NULL, paramName: array("reportId","navigation"), paramValue: array("knockoutsTotalForUser","Y"), tabIndex: - 1, text: "Total KOs", title: NULL);
$htmlLinkAverageKnockouts = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "top5.php", id: NULL, paramName: array("reportId","navigation"), paramValue: array("knockoutsAverageForUser","Y"), tabIndex: - 1, text: "Average KOs", title: NULL);
$htmlLinkTotalEarnings = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "top5.php", id: NULL, paramName: array("reportId","navigation"), paramValue: array("earningsTotalForUser","Y"), tabIndex: - 1, text: "Total earnings", title: NULL);
$htmlLinkAverageEarnings = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "top5.php", id: NULL, paramName: array("reportId","navigation"), paramValue: array("earningsAverageForUser","Y"), tabIndex: - 1, text: "Avg earnings", title: NULL);
$htmlLinkWins = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "top5.php", id: NULL, paramName: array("reportId","navigation"), paramValue: array("winsForUser","Y"), tabIndex: - 1, text: "Wins", title: NULL);
$htmlLinkNemesis = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "top5.php", id: NULL, paramName: array("reportId","navigation"), paramValue: array("nemesisForUser","Y"), tabIndex: - 1, text: "Nemesis", title: NULL);
$htmlLinkBully = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "top5.php", id: NULL, paramName: array("reportId","navigation"), paramValue: array("bullyForUser","Y"), tabIndex: - 1, text: "Bully", title: NULL);
$htmlLinkWon = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "top5.php", id: NULL, paramName: array("reportId","navigation"), paramValue: array("tournamentsWonForUser","Y"), tabIndex: - 1, text: "Won", title: NULL);
$htmlLinkFinishes = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "top5.php", id: NULL, paramName: array("reportId","navigation"), paramValue: array("finishesForUser","Y"), tabIndex: - 1, text: "Finishes", title: NULL);
$htmlLinkPlayedCount = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "top5.php", id: NULL, paramName: array("reportId","navigation"), paramValue: array("tournamentsPlayedForUser","Y"), tabIndex: - 1, text: "Played count", title: NULL);
$htmlLinkPlayedByType = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "top5.php", id: NULL, paramName: array("reportId","navigation"), paramValue: array("tournamentsPlayedByTypeForUser","Y"), tabIndex: - 1, text: "Played by type", title: NULL);
$htmlLinkMemberSince = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "top5.php", id: NULL, paramName: array("reportId","navigation"), paramValue: array("tournamentsPlayedFirstForUser","Y"), tabIndex: - 1, text: "Member since", title: NULL);
$htmlLinkRankingArray = array($htmlLinkTotalPoints,$htmlLinkAveragePoints,$htmlLinkTotalKnockouts,$htmlLinkAverageKnockouts,$htmlLinkTotalEarnings,$htmlLinkAverageEarnings,$htmlLinkWins,$htmlLinkNemesis,$htmlLinkBully,$htmlLinkWon,$htmlLinkFinishes,$htmlLinkPlayedCount,$htmlLinkPlayedByType,$htmlLinkMemberSince);
$htmlMenuReportRanking->setItems($htmlLinkRankingArray);
$htmlReportsArray = array($htmlMenuReportStandard,$htmlMenuReportSeason,$htmlMenuReportChampionship,$htmlMenuReportRanking);
$htmlMenuReports->setItems($htmlReportsArray);
$levels = array($htmlLinkHome,$htmlLinkEvents,$htmlLinkChampionship,$htmlMenuResults,$htmlMenuStats,$htmlMenuReports);
// print_r($htmlMenuResults); die();
if (SessionUtility::getValue(SessionUtility::OBJECT_NAME_ADMINISTRATOR) != 0) {
  $htmlMenuReportAdministration = new HtmlMenu(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: NULL, items: NULL, text: "Administration");
  $htmlMenuReportGames = new HtmlMenu(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: NULL, items: NULL, text: "Games");
  $htmlLinkNotifications = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "manageNotification.php", id: NULL, paramName: NULL, paramValue: NULL, tabIndex: - 1, text: "Notifcations", title: NULL);
  $htmlLinkSeasons = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "manageSeason.php", id: NULL, paramName: NULL, paramValue: NULL, tabIndex: - 1, text: "Seasons", title: NULL);
  $htmlLinkTournaments = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "manageTournament.php", id: NULL, paramName: NULL, paramValue: NULL, tabIndex: - 1, text: "Tournaments", title: NULL);
  $htmlLinkTournamentAbsences = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "manageTournamentAbsence.php", id: NULL, paramName: NULL, paramValue: NULL, tabIndex: - 1, text: "Tournament Absences", title: NULL);
  $htmlLinkRegistration = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "manageRegistration.php", id: NULL, paramName: NULL, paramValue: NULL, tabIndex: - 1, text: "Registration", title: NULL);
  $htmlLinkBuyins = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "manageBuyins.php", id: NULL, paramName: NULL, paramValue: NULL, tabIndex: - 1, text: "Buyins", title: NULL);
  $htmlLinkResults = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "manageResults.php", id: NULL, paramName: NULL, paramValue: NULL, tabIndex: - 1, text: "Results", title: NULL);
  $htmlLinkSpecialType = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "manageSpecialType.php", id: NULL, paramName: NULL, paramValue: NULL, tabIndex: - 1, text: "Special type", title: NULL);
  $htmlLinkGameType = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "manageGameType.php", id: NULL, paramName: NULL, paramValue: NULL, tabIndex: - 1, text: "Game type", title: NULL);
  $htmlLinkLimitType = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "manageLimitType.php", id: NULL, paramName: NULL, paramValue: NULL, tabIndex: - 1, text: "Limit type", title: NULL);
  $htmlLinkGamesArray = array($htmlLinkNotifications,$htmlLinkSeasons,$htmlLinkTournaments,$htmlLinkTournamentAbsences,$htmlLinkRegistration,$htmlLinkBuyins,$htmlLinkResults,$htmlLinkGameType,$htmlLinkLimitType,$htmlLinkSpecialType);
  $htmlMenuReportGames->setItems($htmlLinkGamesArray);
  $htmlMenuReportUsers = new HtmlMenu(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: NULL, items: NULL, text: "Users");
  $htmlLinkLocations = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "manageLocation.php", id: NULL, paramName: NULL, paramValue: NULL, tabIndex: - 1, text: "Locations", title: NULL);
  $htmlLinkUsers = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "manageUser.php", id: NULL, paramName: NULL, paramValue: NULL, tabIndex: - 1, text: "Users", title: NULL);
  $htmlLinkNewUserApproval = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "manageSignupApproval.php", id: NULL, paramName: NULL, paramValue: NULL, tabIndex: - 1, text: "New user approval", title: NULL);
  $htmlLinkUsersArray = array($htmlLinkLocations,$htmlLinkUsers,$htmlLinkNewUserApproval);
  $htmlMenuReportUsers->setItems($htmlLinkUsersArray);
  $htmlMenuReportPayouts = new HtmlMenu(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: NULL, items: NULL, text: "Payouts");
  $htmlLinkGroup = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "manageGroup.php", id: NULL, paramName: NULL, paramValue: NULL, tabIndex: - 1, text: "Groups", title: NULL);
  $htmlLinkGroupPayout = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "manageGroupPayout.php", id: NULL, paramName: NULL, paramValue: NULL, tabIndex: - 1, text: "Payout groups", title: NULL);
  $htmlLinkPayout = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "managePayout.php", id: NULL, paramName: NULL, paramValue: NULL, tabIndex: - 1, text: "Payouts", title: NULL);
  $htmlLinkPayoutArray = array($htmlLinkGroup,$htmlLinkPayout,$htmlLinkGroupPayout);
  $htmlMenuReportPayouts->setItems($htmlLinkPayoutArray);
  $htmlLinkEmail = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "manageEmail.php", id: NULL, paramName: NULL, paramValue: NULL, tabIndex: - 1, text: "Send email", title: NULL);
  $htmlMenuReportScheduledJobs = new HtmlMenu(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: NULL, items: NULL, text: "Scheduled Jobs");
  $htmlLinkAutoRegisterHost = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "autoRegisterHost.php", id: NULL, paramName: array(Constant::FIELD_NAME_MODE), paramValue: array(Constant::MODE_VIEW), tabIndex: - 1, text: "Run auto register host", title: NULL);
  $htmlLinkAutoReminder = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "autoReminder.php", id: NULL, paramName: array(Constant::FIELD_NAME_MODE), paramValue: array(Constant::MODE_VIEW), tabIndex: - 1, text: "Run auto reminder", title: NULL);
  $htmlLinkScheduledJobsArray = array($htmlLinkAutoRegisterHost,$htmlLinkAutoReminder);
  $htmlMenuReportScheduledJobs->setItems($htmlLinkScheduledJobsArray);
  $htmlLinkAdministrationArray = array($htmlMenuReportGames,$htmlMenuReportUsers,$htmlMenuReportPayouts,$htmlLinkEmail,$htmlMenuReportScheduledJobs);
  $htmlMenuReportAdministration->setItems($htmlLinkAdministrationArray);
  array_push($levels, $htmlMenuReportAdministration);
}
$htmlMenuReportMyProfile = new HtmlMenu(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: NULL, items: NULL, text: "My profile");
$htmlLinkEdit = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "manageUser.php", id: NULL, paramName: array("mode","userId"), paramValue: array("modify",SessionUtility::getValue(SessionUtility::OBJECT_NAME_USERID)), tabIndex: - 1, text: "Edit my profile", title: NULL);
$htmlLinkLogout = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "logout.php", id: NULL, paramName: NULL, paramValue: NULL, tabIndex: - 1, text: "Logout", title: NULL);
$htmlLinkResetPassword = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "resetPassword.php", id: NULL, paramName: array("nav"), paramValue: array("Y"), tabIndex: - 1, text: "Password reset", title: NULL);
$htmlLinkMyProfileArray = array($htmlLinkEdit,$htmlLinkLogout,$htmlLinkResetPassword);
$htmlMenuReportMyProfile->setItems($htmlLinkMyProfileArray);
array_push($levels, $htmlMenuReportMyProfile);
$htmlLinkRules = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "rules/ccp_rules_2023.pdf", id: NULL, paramName: NULL, paramValue: NULL, tabIndex: - 1, text: "Rules", title: NULL);
array_push($levels, $htmlLinkRules);
// echo print_r($levels, true);
$htmlMenuRoot = new HtmlMenu(debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), id: NULL, items: $levels, text: NULL);
$smarty->assign("navigation", $htmlMenuRoot->getHtmlRoot());
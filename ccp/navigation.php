<?php
declare(strict_types = 1);
namespace ccp;
use DateTime;
use Poker\Ccp\Model\Constant;
use Poker\Ccp\Model\HtmlLink;
use Poker\Ccp\Model\HtmlMenu;
use Poker\Ccp\Utility\DateTimeUtility;
use Poker\Ccp\Utility\HtmlUtility;
use Poker\Ccp\Utility\SessionUtility;
$htmlLinkHome = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), href: "home.php", id: NULL, paramName: NULL, paramValue: NULL, tabIndex: - 1, text: "Home", title: NULL);
$htmlLinkEvents = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), href: "registrationList.php", id: NULL, paramName: NULL, paramValue: NULL, tabIndex: - 1, text: "Events", title: NULL);
$htmlLinkChampionship = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), href: "championship.php", id: NULL, paramName: NULL, paramValue: NULL, tabIndex: - 1, text: "Championship Seating", title: NULL);
$htmlMenuResults = new HtmlMenu(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), id: NULL, items: NULL, text: "Results");
$params = array("desc");
$entityManager = getEntityManager();
$queryResultYears = $entityManager->getRepository(entityName: Constant::ENTITY_TOURNAMENTS)->getYears();
$counterOverall = count(value: $queryResultYears);
$counterLoop = 1;
$counterResultSeasonGroup = 0;
$counterResultSeason = 0;
foreach ($queryResultYears as $rowYears) { // array of integers
    $htmlLinkResultArray = array();
    $htmlLinkResultArrayCounter = 0;
    if ($counterLoop == 1) {
        $htmlMenuResultSeasonGroup = new HtmlMenu(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), id: NULL, items: NULL, text: "Seasons " . $counterOverall . " to " . ($counterOverall - 4 == 0 ? 1 : $counterOverall - 4));
        $htmlMenuResultSeasonGroupArray[$counterResultSeasonGroup] = $htmlMenuResultSeasonGroup;
        $counterResultSeasonGroup++;
        $htmlMenuResultSeasonArray = array();
    }
    $htmlMenuResultSeason = new HtmlMenu(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), id: NULL, items: NULL, text: $rowYears["year"] . " (Season " . ($rowYears["year"] - 2006) . ")");
    $htmlMenuResultSeasonArray[$counterResultSeason] = $htmlMenuResultSeason;
    $counterResultSeason++;
    $htmlMenuResultSeasonGroup->setItems(items: $htmlMenuResultSeasonArray);
    $queryResultAll = $entityManager->getRepository(entityName: Constant::ENTITY_TOURNAMENTS)->getForYear(tournamentDate: new DateTime($rowYears["year"] . "-01-01"));
    if (0 < count(value: $queryResultAll)) {
        foreach ($queryResultAll as $tournaments) { // array of Tournaments
            $description = NULL !== $tournaments->getTournamentDescription() ? count(explode(" - ", $tournaments->getTournamentDescription())) > 1 ? explode(" - ", $tournaments->getTournamentDescription())[1] : explode(" - ", $tournaments->getTournamentDescription())[0] : "";
            $htmlLinkResult = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), href: "reports.php", id: NULL, paramName: array("reportId", "tournamentId"), paramValue: array("results", $tournaments->getTournamentId()), tabIndex: - 1, text: $description . " (" . DateTimeUtility::formatDisplayDate(value: $tournaments->getTournamentDate()) . " " . DateTimeUtility::formatDisplayTime(value: $tournaments->getTournamentStartTime()) . ")", title: NULL);
            $htmlLinkResultArray[$htmlLinkResultArrayCounter] = $htmlLinkResult;
            $htmlLinkResultArrayCounter++;
        }
    }
    $htmlMenuResultSeason->setItems(items: $htmlLinkResultArray);
    if ($counterLoop == 5 || $counterOverall == 1) {
        $counterLoop = 1;
        $counterResultSeason = 0;
    } else {
        $counterLoop++;
    }
    $counterOverall--;
}
$htmlMenuResults->setItems(items: $htmlMenuResultSeasonGroupArray);
$htmlMenuStats = new HtmlMenu(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), id: NULL, items: NULL, text: "Stats");
$htmlLinkMyStats = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), href: "personalize.php", id: NULL, paramName: NULL, paramValue: NULL, tabIndex: - 1, text: "My Stats", title: NULL);
$resultListPlayers = $entityManager->getRepository(entityName: Constant::ENTITY_PLAYERS)->getById(playerId: NULL);
$counterOverall = 1;
$counterLoop = 1;
$counterPlayerGroup = 0;
foreach ($resultListPlayers as $player) {
    if ($counterLoop == 1) {
        $htmlLinkPlayerArray = array();
        $htmlLinkPlayerArrayCounter = 0;
        $htmlMenuPlayerGroup = new HtmlMenu(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), id: NULL, items: NULL, text: "Players " . $counterOverall . " to " . ($counterOverall + 9));
        $htmlMenuPlayerGroupArray[$counterPlayerGroup] = $htmlMenuPlayerGroup;
        $counterPlayerGroup++;
    }
    $htmlLinkPlayer = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), href: "personalize.php", id: NULL, paramName: array("playerId"), paramValue: array($player->getPlayerId()), tabIndex: - 1, text: $player->getPlayerName() . "'s stats", title: NULL);
    $htmlLinkPlayerArray[$htmlLinkPlayerArrayCounter] = $htmlLinkPlayer;
    $htmlLinkPlayerArrayCounter ++;
    if ($counterLoop == 10 || $counterOverall == count(value: $resultListPlayers)) {
        $htmlMenuPlayerGroup->setItems(items: $htmlLinkPlayerArray);
        $counterLoop = 1;
    } else {
        $counterLoop++;
    }
    $counterOverall++;
}
// echo "<br>" . print_r($htmlLinkPlayers, true);
// echo "<br>" . print_r($htmlLinkPlayerArray, true);
$htmlMenuOtherStats = new HtmlMenu(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), id: NULL, items: NULL, text: "Other Players");
$htmlMenuOtherStats->setItems(items: $htmlMenuPlayerGroupArray);
$htmlStatsArray = array($htmlLinkMyStats,$htmlMenuOtherStats);
$htmlMenuStats->setItems(items: $htmlStatsArray);
$htmlMenuReports = new HtmlMenu(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), id: NULL, items: NULL, text: "Reports");
$htmlMenuReportStandard = new HtmlMenu(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), id: NULL, items: NULL, text: "Standard");
$queryResult = $entityManager->getRepository(entityName: Constant::ENTITY_TOURNAMENTS)->getResultsMaxId(tournamentDate: new DateTime());
$htmlLinkResults = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), href: "reports.php", id: NULL, paramName: array("reportId","tournamentId"), paramValue: array("results",$queryResult[0]["tournamentId"]), tabIndex: - 1, text: "Results", title: NULL);
$htmlLinkTotalPoints = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), href: "reports.php", id: NULL, paramName: array("reportId","seasonSelection"), paramValue: array("pointsTotal","show"), tabIndex: - 1, text: "Total pts", title: NULL);
$htmlLinkEarnings = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), href: "reports.php", id: NULL, paramName: array("reportId","seasonSelection"), paramValue: array("earnings","show"), tabIndex: - 1, text: "Earnings", title: NULL);
$htmlLinkEarningsChampionship = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), href: "reports.php", id: NULL, paramName: array("reportId","seasonSelection"), paramValue: array("earningsChampionship","show"), tabIndex: - 1, text: "Earnings (Championship)", title: NULL);
$htmlLinkKnockouts = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), href: "reports.php", id: NULL, paramName: array("reportId","seasonSelection"), paramValue: array("knockouts","show"), tabIndex: - 1, text: "KOs", title: NULL);
$htmlLinkSummary = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), href: "reports.php", id: NULL, paramName: array("reportId","seasonSelection"), paramValue: array("summary","show"), tabIndex: - 1, text: "Summary", title: NULL);
$htmlLinkWinners = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), href: "reports.php", id: NULL, paramName: array("reportId","seasonSelection"), paramValue: array("winners","show"), tabIndex: - 1, text: "Winners", title: NULL);
$htmlLinkFinishes = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), href: "reports.php", id: NULL, paramName: array("reportId","seasonSelection"), paramValue: array("finishes","show"), tabIndex: - 1, text: "Finishes", title: NULL);
$htmlLinkFees = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), href: "reports.php", id: NULL, paramName: array("reportId"), paramValue: array("fees"), tabIndex: - 1, text: "Fees", title: NULL);
$htmlLinkBubbles = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), href: "reports.php", id: NULL, paramName: array("reportId","seasonSelection"), paramValue: array("bubbles","show"), tabIndex: - 1, text: "Bubbles", title: NULL);
$htmlLinkStandardArray = array($htmlLinkResults,$htmlLinkTotalPoints,$htmlLinkEarnings,$htmlLinkEarningsChampionship,$htmlLinkKnockouts,$htmlLinkSummary,$htmlLinkWinners,$htmlLinkFinishes,$htmlLinkFees, $htmlLinkBubbles);
$htmlMenuReportStandard->setItems(items: $htmlLinkStandardArray);
$htmlMenuReportSeason = new HtmlMenu(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), id: NULL, items: NULL, text: "Season");
$htmlLinkPrizePool = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), href: "top5.php", id: NULL, paramName: array("reportId","navigation"), paramValue: array("prizePoolForSeason","Y"), tabIndex: - 1, text: "Prize pool", title: NULL);
$htmlLinkTotalPoints = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), href: "top5.php", id: NULL, paramName: array("reportId","navigation"), paramValue: array("pointsTotalForSeason","Y"), tabIndex: - 1, text: "Total points", title: NULL);
$htmlLinkAveragePoints = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), href: "top5.php", id: NULL, paramName: array("reportId","navigation"), paramValue: array("pointsAverageForSeason","Y"), tabIndex: - 1, text: "Average points", title: NULL);
$htmlLinkTotalEarnings = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), href: "top5.php", id: NULL, paramName: array("reportId","navigation"), paramValue: array("earningsTotalForSeason","Y"), tabIndex: - 1, text: "Total Earnings", title: NULL);
$htmlLinkAverageEarnings = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), href: "top5.php", id: NULL, paramName: array("reportId","navigation"), paramValue: array("earningsAverageForSeason","Y"), tabIndex: - 1, text: "Average Earnings", title: NULL);
$htmlLinkTotalKnockouts = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), href: "top5.php", id: NULL, paramName: array("reportId","navigation"), paramValue: array("knockoutsTotalForSeason","Y"), tabIndex: - 1, text: "Total knockouts", title: NULL);
$htmlLinkAverageKnockouts = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), href: "top5.php", id: NULL, paramName: array("reportId","navigation"), paramValue: array("knockoutsAverageForSeason","Y"), tabIndex: - 1, text: "Average knockouts", title: NULL);
$htmlLinkWinners = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), href: "top5.php", id: NULL, paramName: array("reportId","navigation"), paramValue: array("winnersForSeason","Y"), tabIndex: - 1, text: "Winners", title: NULL);
$htmlLinkSeasonArray = array($htmlLinkPrizePool,$htmlLinkTotalPoints,$htmlLinkAveragePoints,$htmlLinkTotalEarnings,$htmlLinkAverageEarnings,$htmlLinkTotalKnockouts,$htmlLinkAverageKnockouts,$htmlLinkWinners);
$htmlMenuReportSeason->setItems(items: $htmlLinkSeasonArray);
$htmlMenuReportChampionship = new HtmlMenu(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), id: NULL, items: NULL, text: "Championship");
$htmlLinkByYearByEarnings = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), href: "reports.php", id: NULL, paramName: array("reportId","sort"), paramValue: array("championship","0 down,2 down"), tabIndex: - 1, text: "By year by earnings", title: NULL);
$htmlLinkByNameByYear = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), href: "reports.php", id: NULL, paramName: array("reportId","sort"), paramValue: array("championship","1 up,0 down"), tabIndex: - 1, text: "By name by year", title: NULL);
$htmlLinkByNameByEarningsByYear = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), href: "reports.php", id: NULL, paramName: array("reportId","sort"), paramValue: array("championship","1 up,2 down"), tabIndex: - 1, text: "By name by earnings by year", title: NULL);
$htmlLinkByNameByEarnings = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), href: "reports.php", id: NULL, paramName: array("reportId","sort","group"), paramValue: array("championship","1 down,0 up","true"), tabIndex: - 1, text: "By name by earnings", title: NULL);
$htmlLinkChampionshipArray = array($htmlLinkByYearByEarnings,$htmlLinkByNameByYear,$htmlLinkByNameByEarningsByYear,$htmlLinkByNameByEarnings);
$htmlMenuReportChampionship->setItems(items: $htmlLinkChampionshipArray);
$htmlMenuReportRanking = new HtmlMenu(SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), id: NULL, items: NULL, text: "Ranking");
$htmlLinkTotalPoints = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), href: "top5.php", id: NULL, paramName: array("reportId","navigation"), paramValue: array("pointsTotalForPlayer","Y"), tabIndex: - 1, text: "Total pts", title: NULL);
$htmlLinkAveragePoints = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), href: "top5.php", id: NULL, paramName: array("reportId","navigation"), paramValue: array("pointsAverageForPlayer","Y"), tabIndex: - 1, text: "Average points", title: NULL);
$htmlLinkTotalKnockouts = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), href: "top5.php", id: NULL, paramName: array("reportId","navigation"), paramValue: array("knockoutsTotalForPlayer","Y"), tabIndex: - 1, text: "Total KOs", title: NULL);
$htmlLinkAverageKnockouts = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), href: "top5.php", id: NULL, paramName: array("reportId","navigation"), paramValue: array("knockoutsAverageForPlayer","Y"), tabIndex: - 1, text: "Average KOs", title: NULL);
$htmlLinkTotalEarnings = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), href: "top5.php", id: NULL, paramName: array("reportId","navigation"), paramValue: array("earningsTotalForPlayer","Y"), tabIndex: - 1, text: "Total earnings", title: NULL);
$htmlLinkAverageEarnings = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), href: "top5.php", id: NULL, paramName: array("reportId","navigation"), paramValue: array("earningsAverageForPlayer","Y"), tabIndex: - 1, text: "Avg earnings", title: NULL);
$htmlLinkWins = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), href: "top5.php", id: NULL, paramName: array("reportId","navigation"), paramValue: array("winsForPlayer","Y"), tabIndex: - 1, text: "Wins", title: NULL);
$htmlLinkNemesis = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), href: "top5.php", id: NULL, paramName: array("reportId","navigation"), paramValue: array("nemesisForPlayer","Y"), tabIndex: - 1, text: "Nemesis", title: NULL);
$htmlLinkBully = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), href: "top5.php", id: NULL, paramName: array("reportId","navigation"), paramValue: array("bullyForPlayer","Y"), tabIndex: - 1, text: "Bully", title: NULL);
$htmlLinkWon = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), href: "top5.php", id: NULL, paramName: array("reportId","navigation"), paramValue: array("tournamentsWonForPlayer","Y"), tabIndex: - 1, text: "Won", title: NULL);
$htmlLinkFinishes = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), href: "top5.php", id: NULL, paramName: array("reportId","navigation"), paramValue: array("finishesForPlayer","Y"), tabIndex: - 1, text: "Finishes", title: NULL);
$htmlLinkPlayedCount = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), href: "top5.php", id: NULL, paramName: array("reportId","navigation"), paramValue: array("tournamentsPlayedForPlayer","Y"), tabIndex: - 1, text: "Played count", title: NULL);
$htmlLinkPlayedByType = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), href: "top5.php", id: NULL, paramName: array("reportId","navigation"), paramValue: array("tournamentsPlayedByTypeForPlayer","Y"), tabIndex: - 1, text: "Played by type", title: NULL);
$htmlLinkMemberSince = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), href: "top5.php", id: NULL, paramName: array("reportId","navigation"), paramValue: array("tournamentsPlayedFirstForPlayer","Y"), tabIndex: - 1, text: "Member since", title: NULL);
$htmlLinkRankingArray = array($htmlLinkTotalPoints,$htmlLinkAveragePoints,$htmlLinkTotalKnockouts,$htmlLinkAverageKnockouts,$htmlLinkTotalEarnings,$htmlLinkAverageEarnings,$htmlLinkWins,$htmlLinkNemesis,$htmlLinkBully,$htmlLinkWon,$htmlLinkFinishes,$htmlLinkPlayedCount,$htmlLinkPlayedByType,$htmlLinkMemberSince);
$htmlMenuReportRanking->setItems(items: $htmlLinkRankingArray);
$htmlReportsArray = array($htmlMenuReportStandard,$htmlMenuReportSeason,$htmlMenuReportChampionship,$htmlMenuReportRanking);
$htmlMenuReports->setItems(items: $htmlReportsArray);
$levels = array($htmlLinkHome,$htmlLinkEvents,$htmlLinkChampionship,$htmlMenuResults,$htmlMenuStats,$htmlMenuReports);
// print_r($htmlMenuResults); die();
if (SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_ADMINISTRATOR) != 0) {
    $htmlMenuReportAdministration = new HtmlMenu(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), id: NULL, items: NULL, text: "Administration");
    $htmlMenuReportGames = new HtmlMenu(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), id: NULL, items: NULL, text: "Games");
    $htmlLinkNotifications = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), href: "manageNotification.php", id: NULL, paramName: NULL, paramValue: NULL, tabIndex: - 1, text: "Notifcations", title: NULL);
    $htmlLinkSeasons = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), href: "manageSeason.php", id: NULL, paramName: NULL, paramValue: NULL, tabIndex: - 1, text: "Seasons", title: NULL);
    $htmlLinkTournaments = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), href: "manageTournament.php", id: NULL, paramName: NULL, paramValue: NULL, tabIndex: - 1, text: "Tournaments", title: NULL);
    $htmlLinkTournamentAbsences = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), href: "manageTournamentAbsence.php", id: NULL, paramName: NULL, paramValue: NULL, tabIndex: - 1, text: "Tournament Absences", title: NULL);
    $htmlLinkRegistration = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), href: "manageRegistration.php", id: NULL, paramName: NULL, paramValue: NULL, tabIndex: - 1, text: "Registration", title: NULL);
    $htmlLinkBuyins = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), href: "manageBuyins.php", id: NULL, paramName: NULL, paramValue: NULL, tabIndex: - 1, text: "Buyins", title: NULL);
    $htmlLinkResults = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), href: "manageResults.php", id: NULL, paramName: NULL, paramValue: NULL, tabIndex: - 1, text: "Results", title: NULL);
    $htmlLinkSpecialType = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), href: "manageSpecialType.php", id: NULL, paramName: NULL, paramValue: NULL, tabIndex: - 1, text: "Special type", title: NULL);
    $htmlLinkGameType = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), href: "manageGameType.php", id: NULL, paramName: NULL, paramValue: NULL, tabIndex: - 1, text: "Game type", title: NULL);
    $htmlLinkLimitType = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), href: "manageLimitType.php", id: NULL, paramName: NULL, paramValue: NULL, tabIndex: - 1, text: "Limit type", title: NULL);
    $htmlLinkGamesArray = array($htmlLinkNotifications,$htmlLinkSeasons,$htmlLinkTournaments,$htmlLinkTournamentAbsences,$htmlLinkRegistration,$htmlLinkBuyins,$htmlLinkResults,$htmlLinkGameType,$htmlLinkLimitType,$htmlLinkSpecialType);
    $htmlMenuReportGames->setItems(items: $htmlLinkGamesArray);
    $htmlMenuReportPlayers = new HtmlMenu(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), id: NULL, items: NULL, text: "Players");
    $htmlLinkLocations = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), href: "manageLocation.php", id: NULL, paramName: NULL, paramValue: NULL, tabIndex: - 1, text: "Locations", title: NULL);
    $htmlLinkPlayers = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), href: "managePlayer.php", id: NULL, paramName: NULL, paramValue: NULL, tabIndex: - 1, text: "Players", title: NULL);
    $htmlLinkNewPlayerApproval = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), href: "manageSignupApproval.php", id: NULL, paramName: NULL, paramValue: NULL, tabIndex: - 1, text: "New player approval", title: NULL);
    $htmlLinkPlayersArray = array($htmlLinkLocations,$htmlLinkPlayers,$htmlLinkNewPlayerApproval);
    $htmlMenuReportPlayers->setItems(items: $htmlLinkPlayersArray);
    $htmlMenuReportPayouts = new HtmlMenu(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), id: NULL, items: NULL, text: "Payouts");
    $htmlLinkGroup = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), href: "manageGroup.php", id: NULL, paramName: NULL, paramValue: NULL, tabIndex: - 1, text: "Groups", title: NULL);
    $htmlLinkGroupPayout = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), href: "manageGroupPayout.php", id: NULL, paramName: NULL, paramValue: NULL, tabIndex: - 1, text: "Payout groups", title: NULL);
    $htmlLinkPayout = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), href: "managePayout.php", id: NULL, paramName: NULL, paramValue: NULL, tabIndex: - 1, text: "Payouts", title: NULL);
    $htmlLinkPayoutArray = array($htmlLinkGroup,$htmlLinkPayout,$htmlLinkGroupPayout);
    $htmlMenuReportPayouts->setItems(items: $htmlLinkPayoutArray);
    $htmlMenuReportInventories = new HtmlMenu(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), id: NULL, items: NULL, text: "Inventories");
    $htmlLinkInventoryType = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), href: "manageInventoryType.php", id: NULL, paramName: NULL, paramValue: NULL, tabIndex: - 1, text: "Manage inventory type", title: NULL);
    $htmlLinkInventory = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), href: "manageInventory.php", id: NULL, paramName: NULL, paramValue: NULL, tabIndex: - 1, text: "Manage inventory", title: NULL);
    $htmlLinkInventoryGauge = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), href: "inventoryGauge.php", id: NULL, paramName: NULL, paramValue: NULL, tabIndex: - 1, text: "View inventory", title: NULL);
    $htmlLinkInventoryArray = array($htmlLinkInventoryType,$htmlLinkInventory,$htmlLinkInventoryGauge);
    $htmlMenuReportInventories->setItems(items: $htmlLinkInventoryArray);
    $htmlLinkEmail = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), href: "manageEmail.php", id: NULL, paramName: NULL, paramValue: NULL, tabIndex: - 1, text: "Send email", title: NULL);
    $htmlMenuReportScheduledJobs = new HtmlMenu(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), id: NULL, items: NULL, text: "Scheduled Jobs");
    $htmlLinkAutoRegisterHost = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), href: "autoRegisterHost.php", id: NULL, paramName: array(Constant::FIELD_NAME_MODE), paramValue: array(Constant::MODE_VIEW), tabIndex: - 1, text: "Run auto register host", title: NULL);
    $htmlLinkAutoReminder = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), href: "autoReminder.php", id: NULL, paramName: array(Constant::FIELD_NAME_MODE), paramValue: array(Constant::MODE_VIEW), tabIndex: - 1, text: "Run auto reminder", title: NULL);
    $htmlLinkAutoReminderChampionship = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), href: "autoReminderChampionship.php", id: NULL, paramName: array(Constant::FIELD_NAME_MODE), paramValue: array(Constant::MODE_VIEW), tabIndex: - 1, text: "Run auto reminder championship", title: NULL);
    $htmlLinkAutoChangeSeason = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), href: "autoChangeSeason.php", id: NULL, paramName: array(Constant::FIELD_NAME_MODE), paramValue: array(Constant::MODE_VIEW), tabIndex: - 1, text: "Run auto change season", title: NULL);
    $htmlLinkScheduledJobsArray = array($htmlLinkAutoRegisterHost,$htmlLinkAutoReminder,$htmlLinkAutoReminderChampionship, $htmlLinkAutoChangeSeason);
    $htmlMenuReportScheduledJobs->setItems(items: $htmlLinkScheduledJobsArray);
    $htmlLinkAdministrationArray = array($htmlMenuReportGames,$htmlMenuReportPlayers,$htmlMenuReportPayouts,$htmlMenuReportInventories,$htmlLinkEmail,$htmlMenuReportScheduledJobs);
    $htmlMenuReportAdministration->setItems(items: $htmlLinkAdministrationArray);
    array_push($levels, $htmlMenuReportAdministration);
}
$htmlMenuReportMyProfile = new HtmlMenu(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), id: NULL, items: NULL, text: "My profile");
$htmlLinkEdit = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), href: "managePlayer.php", id: NULL, paramName: array("mode","playerId"), paramValue: array("modify",SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_PLAYERID)), tabIndex: - 1, text: "Edit my profile", title: NULL);
$htmlLinkLogout = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), href: "logout.php", id: NULL, paramName: NULL, paramValue: NULL, tabIndex: - 1, text: "Logout", title: NULL);
$htmlLinkResetPassword = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), href: "resetPassword.php", id: NULL, paramName: array("nav"), paramValue: array("Y"), tabIndex: - 1, text: "Password reset", title: NULL);
$htmlLinkMyProfileArray = array($htmlLinkEdit,$htmlLinkLogout,$htmlLinkResetPassword);
$htmlMenuReportMyProfile->setItems(items: $htmlLinkMyProfileArray);
array_push($levels, $htmlMenuReportMyProfile);
$year = DateTimeUtility::formatYear(value: new \DateTime());
$htmlLinkRules = new HtmlLink(accessKey: NULL, class: NULL, debug: SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG), href: "rules/ccp_rules.pdf", id: NULL, paramName: NULL, paramValue: NULL, tabIndex: - 1, text: "Rules", title: NULL);
array_push($levels, $htmlLinkRules);
// echo print_r($levels, true);
$htmlMenuRoot = new HtmlMenu(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), id: NULL, items: $levels, text: NULL);
$smarty->assign("navigation", $htmlMenuRoot->getHtmlRoot());
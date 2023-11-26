<?php
declare(strict_types = 1);
namespace ccp\classes\model;
use Exception;
use PDO;
use PDOException;
class DatabaseResult extends Root {
  private const USER_NAME_SERVER = "chipch5_app";
  private const PASSWORD_SERVER = "app_chipch5";
  private const USER_NAME_LOCAL = "root";
  private const PASSWORD_LOCAL = "toor";
  private const PORT = 3306;
  private const DATABASE_NAME  = "chipch5_stats_new";
  private const HOST_NAME = "localhost";
  private Database $database;
  private PDO $connection;
  public function __construct(protected bool $debug) {
    $this->setDatabase($this->initializeDatabase());
    $this->setConnection($this->initializeConnection());
  }
  public function getConnection(): PDO {
    return $this->connection;
  }
  public function getDatabase(): Database {
    return $this->database;
  }
  public function setConnection(PDO $connection) {
    $this->connection = $connection;
  }
  public function setDatabase(Database $database) {
    $this->database = $database;
  }
  public function getBlobTest(): array {
    return $this->getData(dataName: "blobTest", params: NULL, orderBy: NULL, returnQuery: false, limitCount: NULL, rank: false);
  }
  public function getAutoRegisterHost(array $params): array|string {
    return $this->getData(dataName: "autoRegisterHost", params: $params, orderBy: NULL, returnQuery: false, limitCount: NULL, rank: false);
  }
  public function getBullyForUser(array $params): array|string {
    return $this->getData(dataName: "bullyForUser", params: $params, orderBy: NULL, returnQuery: true, limitCount: NULL, rank: false);
  }
  public function getChampionshipByYearByEarnings(array $params): array|string {
    return $this->getData(dataName: "championship", params: $params, orderBy: NULL, returnQuery: true, limitCount: NULL, rank: false);
  }
  public function getChampionshipByPlayerForYear(array $params): array|string {
    return $this->getData(dataName: "championship", params: $params, orderBy: NULL, returnQuery: true, limitCount: NULL, rank: false);
  }
  public function getChampionshipByPlayerByEarnings(array $params): array|string {
    return $this->getData(dataName: "championship", params: $params, orderBy: NULL, returnQuery: true, limitCount: NULL, rank: false);
  }
  public function getChampionshipQualifiedPlayers(array $params, bool $returnQuery): array|string {
    return $this->getData(dataName: "championshipQualifiedPlayers", params: $params, orderBy: NULL, returnQuery: $returnQuery, limitCount: NULL, rank: false);
  }
  public function getCountTournamentForDates(array $params): array|string {
    return $this->getData(dataName: "countTournamentForDates", params: $params, orderBy: NULL, returnQuery: false, limitCount: NULL, rank: false);
  }
  public function getEarningsAverageForSeason($params, $orderBy, $limitCount): array|string {
    return $this->getData(dataName: "earningsAverageForSeason", params: $params, orderBy: $orderBy, returnQuery: true, limitCount: $limitCount, rank: false);
  }
  public function getEarningsTotalForChampionship(array $params): array|string {
    return $this->getData(dataName: "earningsTotalForChampionship", params: $params, orderBy: NULL, returnQuery: true, limitCount: NULL, rank: false);
  }
  public function getEarningsTotalForSeason($params, $orderBy, $limitCount): array|string {
    return $this->getData(dataName: "earningsTotalForSeason", params: $params, orderBy: $orderBy, returnQuery: true, limitCount: $limitCount, rank: false);
  }
  public function getEarningsTotalAndAverageForSeasonForUser($params, $orderBy, $rank): array|string {
    return $this->getData(dataName: "earningsTotalAndAverageForSeasonForUser", params: $params, orderBy: $orderBy, returnQuery: true, limitCount: NULL, rank: $rank);
  }
  public function getEarningsTotalAndAverageForUser($params, $orderBy, $rank): array|string {
    return $this->getData(dataName: "earningsTotalAndAverageForUser", params: $params, orderBy: $orderBy, returnQuery: true, limitCount: NULL, rank: $rank);
  }
  public function getFeeDetail(): array|string {
    return $this->getData(dataName: "feeDetail", params: NULL, orderBy: NULL, returnQuery: true, limitCount: NULL, rank: false);
  }
  public function getFeeBySeason(): array|string {
    return $this->getData(dataName: "feeSelectBySeason", params: NULL, orderBy: NULL, returnQuery: true, limitCount: NULL, rank: false);
  }
  public function getFeeByTournamentAndPlayer(array $params): array|string {
    return $this->getData(dataName: "feeSelectByTournamentIdAndPlayerId", params: $params, orderBy: NULL, returnQuery: false, limitCount: NULL, rank: false);
  }
  public function getFinishesForUser(array $params): array|string {
    return $this->getData(dataName: "finishesSelectAllByPlayerId", params: $params, orderBy: NULL, returnQuery: true, limitCount: NULL, rank: false);
  }
  public function getFoodByTournamentIdAndPlayerId(array $params): array|string {
    return $this->getData(dataName: "foodByTournamentIdAndPlayerId", params: $params, orderBy: NULL, returnQuery: false, limitCount: NULL, rank: false);
  }
  public function getGameType(array $params): array|string {
    return $this->getData(dataName: "gameTypeSelectAll", params: $params[2], orderBy: $params[0], returnQuery: $params[1], limitCount: NULL, rank: false);
  }
  public function getGameTypeById(array $params): array|string {
    return $this->getData(dataName: "gameTypeSelectOneById", params: $params, orderBy: NULL, returnQuery: false, limitCount: NULL, rank: false);
  }
  public function getGroupsAll(array $params): array|string {
    $paramsLocal = isset($params[1]) ? array($params[1]) : NULL;
    return $this->getData(dataName: "groupSelectAll", params: $paramsLocal, orderBy: NULL, returnQuery: $params[0], limitCount: NULL, rank: false);
  }
  public function getGroupById(array $params): array|string {
    return $this->getData(dataName: "groupSelectAllById", params: $params, orderBy: NULL, returnQuery: false, limitCount: NULL, rank: false);
  }
  public function getGroupNameList(): array|string {
    return $this->getData(dataName: "groupSelectNameList", params: NULL, orderBy: NULL, returnQuery: false, limitCount: NULL, rank: false);
  }
  public function getGroupPayout(array $params): array|string {
    return $this->getData(dataName: "groupPayoutSelectAll", params: $params, orderBy: NULL, returnQuery: true, limitCount: NULL, rank: false);
  }
  public function getGroupPayoutById(array $params): array|string {
    return $this->getData(dataName: "groupPayoutSelectAllById", params: $params, orderBy: NULL, returnQuery: false, limitCount: NULL, rank: false);
  }
  public function getKnockoutsAverageForSeason($params, $orderBy, $limitCount): array|string {
    return $this->getData(dataName: "knockoutsAverageForSeason", params: $params, orderBy: $orderBy, returnQuery: true, limitCount: $limitCount, rank: false);
  }
  public function getKnockoutsTotalForSeason($params, $orderBy, $limitCount): array|string {
    return $this->getData(dataName: "knockoutsTotalForSeason", params: $params, orderBy: $orderBy, returnQuery: true, limitCount: $limitCount, rank: false);
  }
  public function getKnockoutsTotalAndAverageForSeasonForUser($params, $orderBy, $rank): array|string {
    return $this->getData(dataName: "knockoutsTotalAndAverageForSeasonForUser", params: $params, orderBy: $orderBy, returnQuery: true, limitCount: NULL, rank: $rank);
  }
  public function getKnockoutsTotalAndAverageForUser($params, $orderBy, $rank): array|string {
    return $this->getData(dataName: "knockoutsTotalAndAverageForUser", params: $params, orderBy: $orderBy, returnQuery: true, limitCount: NULL, rank: $rank);
  }
  public function getLimitType(array $params): array|string {
    return $this->getData(dataName: "limitTypeSelectAll", params: $params[2], orderBy: $params[0], returnQuery: $params[1], limitCount: NULL, rank: false);
  }
  public function getLimitTypeById(array $params): array|string {
    return $this->getData(dataName: "limitTypeSelectOneById", params: $params, orderBy: NULL, returnQuery: false, limitCount: NULL, rank: false);
  }
  public function getLocation(array $params): array|string {
    return $this->getData(dataName: "locationSelectAll", params: $params, orderBy: NULL, returnQuery: $params[0], limitCount: NULL, rank: false);
  }
  public function getLocationById(array $params): array|string {
    return $this->getData(dataName: "locationSelectById", params: $params, orderBy: NULL, returnQuery: false, limitCount: NULL, rank: false);
  }
  public function getLogin($userName): array|string {
    return $this->getData(dataName: "login", params: array($userName,"Super Users"), orderBy: NULL, returnQuery: false, limitCount: NULL, rank: false);
  }
  public function getNemesisForUser(array $params): array|string {
    return $this->getData(dataName: "nemesisForUser", params: $params, orderBy: NULL, returnQuery: true, limitCount: NULL, rank: false);
  }
  public function getNotification($params, $returnQuery): array|string {
    return $this->getData(dataName: "notificationSelectAll", params: $params, orderBy: NULL, returnQuery: $returnQuery, limitCount: NULL, rank: false);
  }
  public function getNotificationById(array $params): array|string {
    return $this->getData(dataName: "notificationSelectOneById", params: $params, orderBy: NULL, returnQuery: false, limitCount: NULL, rank: false);
  }
  public function getPayoutsAll(array $params): array|string {
    $paramsLocal = isset($params[1]) ? array($params[1]) : NULL;
    return $this->getData(dataName: "payoutSelectAll", params: $paramsLocal, orderBy: NULL, returnQuery: $params[0], limitCount: NULL, rank: false);
  }
  public function getPayoutById(array $params): array|string {
    return $this->getData(dataName: "payoutSelectAllById", params: $params, orderBy: NULL, returnQuery: false, limitCount: NULL, rank: false);
  }
  public function getPayoutMaxId(): array|string {
    return $this->getData(dataName: "payoutSelectMaxId", params: NULL, orderBy: NULL, returnQuery: false, limitCount: NULL, rank: false);
  }
  public function getPayoutNameList(): array|string {
    return $this->getData(dataName: "payoutSelectNameList", params: NULL, orderBy: NULL, returnQuery: false, limitCount: NULL, rank: false);
  }
  public function getPointsAverageForSeason($params, $orderBy, $limitCount): array|string {
    return $this->getData(dataName: "pointsAverageForSeason", params: $params, orderBy: $orderBy, returnQuery: true, limitCount: $limitCount, rank: false);
  }
  public function getPointsTotalForSeason($params, $orderBy, $limitCount): array|string {
    return $this->getData(dataName: "pointsTotalForSeason", params: $params, orderBy: $orderBy, returnQuery: true, limitCount: $limitCount, rank: false);
  }
  public function getPointsTotalAndAverageForSeasonForUser($params, $orderBy, $rank): array|string {
    return $this->getData(dataName: "pointsTotalAndAverageForSeasonForUser", params: $params, orderBy: $orderBy, returnQuery: true, limitCount: NULL, rank: $rank);
  }
  public function getPointsTotalAndAverageForUser($params, $orderBy, $rank): array|string {
    return $this->getData(dataName: "pointsTotalAndAverageForUser", params: $params, orderBy: $orderBy, returnQuery: true, limitCount: NULL, rank: $rank);
  }
  public function getPrizePoolForSeason($params, $returnQuery): array|string {
    return $this->getData(dataName: "prizePoolForSeason", params: $params, orderBy: NULL, returnQuery: $returnQuery, limitCount: NULL, rank: false);
  }
  public function getRegistrationList(array $params): array|string {
    return $this->getData(dataName: "registrationList", params: $params, orderBy: NULL, returnQuery: false, limitCount: NULL, rank: false);
  }
  public function getRegistrationWaitList(array $params): array|string {
    return $this->getData(dataName: "registrationWaitList", params: $params, orderBy: NULL, returnQuery: false, limitCount: NULL, rank: false);
  }
  public function getResultIdMax(array|NULL $params): array|string {
    return $this->getData(dataName: "resultIdMax", params: $params, orderBy: NULL, returnQuery: false, limitCount: NULL, rank: false);
  }
  public function getResult(): array|string {
    return $this->getData(dataName: "resultSelectAll", params: NULL, orderBy: NULL, returnQuery: false, limitCount: NULL, rank: false);
  }
  public function getResultDuring(array $params): array|string {
    return $this->getData(dataName: "resultSelectAllDuring", params: $params, orderBy: NULL, returnQuery: false, limitCount: NULL, rank: false);
  }
  public function getResultLastEnteredDuring(array $params): array|string {
    return $this->getData(dataName: "resultSelectLastEnteredDuring", params: $params, orderBy: NULL, returnQuery: false, limitCount: NULL, rank: false);
  }
  public function getResultByTournamentId(array $params): array|string {
    return $this->getData(dataName: "resultSelectAllByTournamentId", params: $params, orderBy: NULL, returnQuery: true, limitCount: NULL, rank: false);
  }
  public function getResultByTournamentIdAndPlayerId(array $params): array|string {
    return $this->getData(dataName: "resultSelectOneByTournamentIdAndPlayerId", params: $params, orderBy: NULL, returnQuery: false, limitCount: NULL, rank: false);
  }
  public function getResultRegisteredByTournamentId(array $params): array|string {
    return $this->getData(dataName: "resultSelectRegisteredByTournamentId", params: $params, orderBy: NULL, returnQuery: false, limitCount: NULL, rank: false);
  }
  public function getResultFinishedByTournamentId(array $params): array|string {
    return $this->getData(dataName: "resultSelectAllFinishedByTournamentId", params: $params, orderBy: NULL, returnQuery: $params[1], limitCount: NULL, rank: false);
  }
  public function getResultPaidByTournamentId($params, $returnQuery): array|string {
    return $this->getData(dataName: "resultSelectPaidByTournamentId", params: $params, orderBy: NULL, returnQuery: $returnQuery, limitCount: NULL, rank: false);
  }
  public function getResultPaidNotEnteredByTournamentId(array $params): array|string {
    return $this->getData(dataName: "resultSelectPaidNotEnteredByTournamentId", params: $params, orderBy: NULL, returnQuery: false, limitCount: NULL, rank: false);
  }
  public function getResultOrderedTotalPoints(array $params): array|string {
    return $this->getData(dataName: "resultAllOrderedPoints", params: $params, orderBy: NULL, returnQuery: true, limitCount: NULL, rank: false);
  }
  public function getResultOrderedEarnings(array $params): array|string {
    return $this->getData(dataName: "resultAllOrderedEarnings", params: $params, orderBy: NULL, returnQuery: true, limitCount: NULL, rank: false);
  }
  public function getResultOrderedKnockouts(array $params): array|string {
    return $this->getData(dataName: "resultAllOrderedKnockouts", params: $params, orderBy: NULL, returnQuery: true, limitCount: NULL, rank: false);
  }
  public function getResultOrderedSummary(array $params): array|string {
    return $this->getData(dataName: "resultAllOrderedSummary", params: $params, orderBy: NULL, returnQuery: true, limitCount: NULL, rank: false);
  }
  public function getResultOrderedSummaryStats(array $params): array|string {
    $resultListSummary = $this->getData(dataName: "resultAllOrderedSummaryStats", params: $params, orderBy: NULL, returnQuery: false, limitCount: NULL, rank: false);
    $resultListKo = $this->getData(dataName: "resultAllOrderedKnockoutsStats", params: $params, orderBy: NULL, returnQuery: false, limitCount: NULL, rank: false);
    $resultListWinners = $this->getData(dataName: "winnersSelectAllStats", params: $params, orderBy: NULL, returnQuery: false, limitCount: NULL, rank: false);
    foreach ($resultListSummary as $resultListSummaryKey => $resultListSummaryValue) {
      foreach ($resultListKo as $resultListKoKey => $resultListKoValue) {
        if ($resultListSummaryKey == $resultListKoKey) {
          $resultListSummary[$resultListSummaryKey] = array_merge($resultListSummaryValue, $resultListKoValue);
          break;
        }
      }
      foreach ($resultListWinners as $resultListWinnersKey => $resultListWinnersValue) {
        if ($resultListSummaryKey == $resultListWinnersKey) {
          $resultListSummary[$resultListSummaryKey] = array_merge($resultListSummary[$resultListSummaryKey], $resultListWinnersValue);
          break;
        }
      }
    }
    return $resultListSummary;
  }
  public function getResultPaidUserCount(): array|string {
    return $this->getData(dataName: "resultPaidUserCount", params: NULL, orderBy: NULL, returnQuery: false, limitCount: NULL, rank: false);
  }
  public function getSeason(array $params): array|string {
    return $this->getData(dataName: "seasonSelectAll", params: $params, orderBy: $params[0], returnQuery: $params[1], limitCount: NULL, rank: false);
  }
  public function getSeasonByActive(array $params): array|string {
    return $this->getData(dataName: "seasonSelectOneByActive", params: $params, orderBy: NULL, returnQuery: false, limitCount: NULL, rank: false);
  }
  public function getSeasonById(array $params): array|string {
    return $this->getData(dataName: "seasonSelectOneById", params: $params, orderBy: NULL, returnQuery: false, limitCount: NULL, rank: false);
  }
  public function getSeasonByIdAndDesc(array $params): array|string {
    return $this->getData(dataName: "seasonSelectOneByIdAndDesc", params: $params, orderBy: NULL, returnQuery: false, limitCount: NULL, rank: false);
  }
  public function getSeasonByTournamentId(array $params): array|string {
    return $this->getData(dataName: "seasonSelectOneByTournamentId", params: $params, orderBy: NULL, returnQuery: false, limitCount: NULL, rank: false);
  }
  public function getSeasonActiveCount(): array|string {
    return $this->getData(dataName: "seasonActiveCount", params: NULL, orderBy: NULL, returnQuery: false, limitCount: NULL, rank: false);
  }
  public function getSeasonChampionships(): array|string {
    return $this->getData(dataName: "seasonSelectAllChampionship", params: NULL, orderBy: NULL, returnQuery: false, limitCount: NULL, rank: false);
  }
  public function getSeasonDateCheckCount(array $params): array|string {
    return $this->getData(dataName: "seasonDateCheckCount", params: $params, orderBy: NULL, returnQuery: false, limitCount: NULL, rank: false);
  }
  public function getSeasonMaxId(): array|string {
    return $this->getData(dataName: "seasonSelectMaxId", params: NULL, orderBy: NULL, returnQuery: false, limitCount: NULL, rank: false);
  }
  public function getSeasonStats(array $params): array|string {
    return $this->getData(dataName: "seasonStats", params: $params, orderBy: NULL, returnQuery: false, limitCount: NULL, rank: false);
  }
  public function getSpecialType(array $params): array|string {
    return $this->getData(dataName: "specialTypeSelectAll", params: $params[2], orderBy: $params[0], returnQuery: $params[1], limitCount: NULL, rank: false);
  }
  public function getSpecialTypeById(array $params): array|string {
    return $this->getData(dataName: "specialTypeSelectOneById", params: $params, orderBy: NULL, returnQuery: false, limitCount: NULL, rank: false);
  }
  public function getStatus(): array|string {
    return $this->getData(dataName: "statusSelectAll", params: NULL, orderBy: NULL, returnQuery: false, limitCount: NULL, rank: false);
  }
  public function getStatusPaid($params, $returnQuery): array|string {
    return $this->getData(dataName: "statusSelectPaid", params: $params, orderBy: NULL, returnQuery: $returnQuery, limitCount: NULL, rank: false);
  }
  public function getStructure(): array|string {
    return $this->getData(dataName: "structureSelectAll", params: NULL, orderBy: NULL, returnQuery: false, limitCount: NULL, rank: false);
  }
  public function getStructurePayout(array $params): array|string {
    return $this->getData(dataName: "structurePayout", params: $params, orderBy: NULL, returnQuery: false, limitCount: NULL, rank: false);
  }
  public function getTournament(array $params, array $paramsNested): array|string {
    $paramsLocal = isset($params[2]) ? array($params[2]) : NULL;
    return $this->getData(dataName: "tournamentSelectAll", params: $paramsLocal, paramsNested: $paramsNested, orderBy: $params[0], returnQuery: $params[1], limitCount: NULL, rank: false);
  }
  public function getTournamentAll(array $params): array|string {
    return $this->getData(dataName: "tournamentAll", params: $params, orderBy: NULL, returnQuery: false, limitCount: NULL, rank: false);
  }
  public function getTournamentIdMax(array $params): array|string {
    return $this->getData(dataName: "tournamentIdMax", params: $params, orderBy: NULL, returnQuery: false, limitCount: NULL, rank: false);
  }
  public function getTournamentOrdered(array $params, array $paramsNested): array|string {
    return $this->getData(dataName: "tournamentSelectAllOrdered", params: NULL, paramsNested: $paramsNested, orderBy: NULL, returnQuery: $params[0], limitCount: NULL, rank: false);
  }
  public function getTournamentsForEmailNotifications(array $params): array|string {
    return $this->getData(dataName: "tournamentsSelectForEmailNotifications", params: $params, orderBy: NULL, returnQuery: false, limitCount: NULL, rank: false);
  }
  public function getTournamentByDateAndStartTime(array $params, array $paramsNested, $limitCount): array|string {
    return $this->getData(dataName: "tournamentSelectAllByDateAndStartTime", params: $params, paramsNested: $paramsNested, orderBy: NULL, returnQuery: false, limitCount: $limitCount, rank: false);
  }
  public function getTournamentById(array $params, array $paramsNested): array|string {
    return $this->getData(dataName: "tournamentSelectOneById", params: $params, paramsNested: $paramsNested, orderBy: NULL, returnQuery: false, limitCount: NULL, rank: false);
  }
  public function getTournamentDuring(): array|string {
    return $this->getData(dataName: "tournamentSelectAllDuring", params: NULL, orderBy: NULL, returnQuery: false, limitCount: NULL, rank: false);
  }
  public function getTournamentYearsPlayed(array $params): array|string {
    return $this->getData(dataName: "tournamentSelectAllYearsPlayed", params: $params, orderBy: NULL, returnQuery: false, limitCount: NULL, rank: false);
  }
  public function getTournamentForRegistration(array $params, array $paramsNested): array|string {
    return $this->getData(dataName: "tournamentSelectAllForRegistration", params: $params, paramsNested: $paramsNested, orderBy: NULL, returnQuery: false, limitCount: NULL, rank: false);
  }
  public function getTournamentForBuyins(array $params, array $paramsNested): array|string {
    return $this->getData(dataName: "tournamentSelectAllForBuyins", params: $params, paramsNested: $paramsNested, orderBy: NULL, returnQuery: false, limitCount: NULL, rank: false);
  }
  public function getTournamentForRegistrationStatus(array $params): array|string {
    return $this->getData(dataName: "tournamentSelectAllRegistrationStatus", params: $params, orderBy: NULL, returnQuery: true, limitCount: NULL, rank: false);
  }
  public function getTournamentForChampionship(array $params, array $paramsNested): array|string {
    return $this->getData(dataName: "tournamentSelectAllForChampionship", params: $params, paramsNested: $paramsNested, orderBy: NULL, returnQuery: false, limitCount: NULL, rank: false);
  }
  public function getTournamentsPlayedByPlayerIdAndDateRange(array $params): array|string {
    return $this->getData(dataName: "tournamentsPlayedByPlayerIdAndDateRange", params: $params, orderBy: NULL, returnQuery: false, limitCount: NULL, rank: false);
  }
  public function getTournamentsWonByPlayerId(array $params, array $paramsNested): array|string {
    return $this->getData(dataName: "tournamentsWonByPlayerId", params: $params, paramsNested: $paramsNested, orderBy: NULL, returnQuery: false, limitCount: NULL, rank: false);
  }
  public function getTournamentsPlayed(array $params): array|string {
    return $this->getData(dataName: "tournamentsPlayed", params: $params, orderBy: NULL, returnQuery: true, limitCount: NULL, rank: true);
  }
  public function getTournamentsPlayedByTypeByPlayerId(array $params): array|string {
    return $this->getData(dataName: "tournamentsPlayedByType", params: $params, orderBy: NULL, returnQuery: true, limitCount: NULL, rank: false);
  }
  public function getTournamentsPlayedFirstByPlayerId(array $params): array|string {
    return $this->getData(dataName: "tournamentsPlayedFirst", params: $params, orderBy: NULL, returnQuery: false, limitCount: NULL, rank: false);
  }
  public function getTournamentIdList(array $params): array|string {
    return $this->getData(dataName: "tournamentIdList", params: $params, orderBy: NULL, returnQuery: false, limitCount: NULL, rank: false);
  }
  public function getUserAbsencesByTournamentId(array $params): array|string {
    return $this->getData(dataName: "userAbsencesByTournamentId", params: $params, orderBy: NULL, returnQuery: false, limitCount: NULL, rank: false);
  }
  public function getUsersActive(array $params): array|string {
    return $this->getData(dataName: "userActive", params: NULL, orderBy: NULL, returnQuery: false, limitCount: NULL, rank: false);
  }
  public function getUsersAll(array $params): array|string {
    return $this->getData(dataName: "userSelectAll", params: $params, orderBy: NULL, returnQuery: $params[0], limitCount: NULL, rank: false);
  }
  public function getUserById(array $params): array|string {
    return $this->getData(dataName: "userSelectOneById", params: $params, orderBy: NULL, returnQuery: false, limitCount: NULL, rank: false);
  }
  public function getUserByName(array $params): array|string {
    return $this->getData(dataName: "userSelectOneByName", params: $params, orderBy: NULL, returnQuery: false, limitCount: NULL, rank: false);
  }
  public function getUserByUsername(array $params): array|string {
    return $this->getData(dataName: "userSelectOneByUsername", params: $params, orderBy: NULL, returnQuery: false, limitCount: NULL, rank: false);
  }
  public function getUserByEmail(array $params): array|string {
    return $this->getData(dataName: "userSelectOneByEmail", params: $params, orderBy: NULL, returnQuery: false, limitCount: NULL, rank: false);
  }
  public function getUsersForEmailNotifications(array $params): array|string {
    return $this->getData(dataName: "usersSelectForEmailNotifications", params: $params, orderBy: NULL, returnQuery: false, limitCount: NULL, rank: false);
  }
  public function getUsersForApproval(): array|string {
    return $this->getData(dataName: "usersSelectForApproval", params: NULL, orderBy: NULL, returnQuery: true, limitCount: NULL, rank: false);
  }
  public function getUserPaidByTournamentId(array $params): array|string {
    return $this->getData(dataName: "userPaidByTournamentId", params: $params, orderBy: NULL, returnQuery: false, limitCount: NULL, rank: false);
  }
  public function getWaitListedPlayerByTournamentId(array $params): array|string {
    return $this->getData(dataName: "waitListedPlayerByTournamentId", params: $params, orderBy: NULL, returnQuery: false, limitCount: NULL, rank: false);
  }
  public function getWinnersForSeason($params, $returnQuery, $limitCount): array|string {
    return $this->getData(dataName: "winnersForSeason", params: $params, orderBy: NULL, returnQuery: $returnQuery, limitCount: $limitCount, rank: false);
  }
  public function getWinsForUser($params, $orderBy, $rank): array|string {
    return $this->getData(dataName: "winsForUser", params: $params, orderBy: $orderBy, returnQuery: true, limitCount: NULL, rank: $rank);
  }
  public function getWinsTotalAndAverageForSeasonForUser($params, $orderBy, $rank): array|string {
    return $this->getData(dataName: "winsTotalAndAverageForSeasonForUser", params: $params, orderBy: $orderBy, returnQuery: true, limitCount: NULL, rank: $rank);
  }
  public function getWinners(array $params): array|string {
    return $this->getData(dataName: "winnersSelectAll", params: $params, orderBy: NULL, returnQuery: true, limitCount: NULL, rank: false);
  }
  public function getUserPasswordReset(array $params): array|string {
    return $this->getData(dataName: "userPasswordReset", params: $params, orderBy: NULL, returnQuery: false, limitCount: NULL, rank: false);
  }
  public function deleteFeeBySeason(array $params): int|array {
    return $this->deleteData(dataName: "feeBySeasonDelete", params: $params);
  }
  public function deleteFeeBySeasonAndPlayer(array $params): int|array {
    return $this->deleteData(dataName: "feeBySeasonAndPlayerDelete", params: $params);
  }
  public function deleteLocation(array $params): int|array {
    return $this->deleteData(dataName: "locationDelete", params: $params);
  }
  public function deleteGameType(array $params): int|array {
    return $this->deleteData(dataName: "gameTypeDelete", params: $params);
  }
  public function deleteGroup(array $params): int|array {
    return $this->deleteData(dataName: "groupDelete", params: $params);
  }
  public function deleteGroupPayout(array $params): int|array {
    return $this->deleteData(dataName: "groupPayoutDelete", params: $params);
  }
  public function deleteLimitType(array $params): int|array {
    return $this->deleteData(dataName: "limitTypeDelete", params: $params);
  }
  public function deleteNotification(array $params): int|array {
    return $this->deleteData(dataName: "notificationDelete", params: $params);
  }
  public function deletePayout(array $params): int|array {
    return $this->deleteData(dataName: "payoutDelete", params: $params);
  }
  public function deleteRegistration(array $params): int|array {
    return $this->deleteData(dataName: "registrationDelete", params: $params);
  }
  public function deleteResult(array $params): int|array {
    return $this->deleteData(dataName: "resultDelete", params: $params);
  }
  public function deleteSeason(array $params): int|array {
    return $this->deleteData(dataName: "seasonDelete", params: $params);
  }
  public function deleteSpecialType(array $params): int|array {
    return $this->deleteData(dataName: "specialTypeDelete", params: $params);
  }
  public function deleteStructure(array $params): int|array {
    return $this->deleteData(dataName: "structureDelete", params: $params);
  }
  public function deleteTournament(array $params): int|array {
    return $this->deleteData(dataName: "tournamentDelete", params: $params);
  }
  public function deleteTournamentAbsence(array $params): int|array {
    return $this->deleteData(dataName: "tournamentAbsenceDelete", params: $params);
  }
  public function insertBlob(array $params) {
    return $this->insertData(dataName: "blobInsert", params: $params);
  }
  public function insertFee(array $params): int|array {
    return $this->insertData(dataName: "feeInsert", params: $params);
  }
  public function insertFeeUsersForYear(array $params): int|array {
    return $this->insertData(dataName: "feeUsersForYearInsert", params: $params);
  }
  public function insertGameType(array $params): int|array {
    return $this->insertData(dataName: "gameTypeInsert", params: $params);
  }
  public function insertGroup(array $params): int|array {
    return $this->insertData(dataName: "groupInsert", params: $params);
  }
  public function insertGroupPayout(array $params): int|array {
    return $this->insertData(dataName: "groupPayoutInsert", params: $params);
  }
  public function insertLimitType(array $params): int|array {
    return $this->insertData(dataName: "limitTypeInsert", params: $params);
  }
  public function insertLocation(array $params): int|array {
    return $this->insertData(dataName: "locationInsert", params: $params);
  }
  public function insertNotification(array $params): int|array {
    return $this->insertData(dataName: "notificationInsert", params: $params);
  }
  public function insertPayout(array $params): int|array {
    return $this->insertData(dataName: "payoutInsert", params: $params);
  }
  public function insertRegistration(array $params): int|array {
    return $this->insertData(dataName: "registrationInsert", params: $params);
  }
  public function insertSeason(array $params): int|array {
    return $this->insertData(dataName: "seasonInsert", params: $params);
  }
  public function insertSpecialType(array $params): int|array {
    return $this->insertData(dataName: "specialTypeInsert", params: $params);
  }
  public function insertStructure(array $params): int|array {
    return $this->insertData(dataName: "structureInsert", params: $params);
  }
  public function insertTournament(array $params): int|array {
    return $this->insertData(dataName: "tournamentInsert", params: $params);
  }
  public function insertTournamentAbsence(array $params): int|array {
    return $this->insertData(dataName: "tournamentAbsenceInsert", params: $params);
  }
  public function insertUser(array $params): int|array {
    return $this->insertData(dataName: "userInsert", params: $params);
  }
  public function updateBuyins(array $params): int|array {
    return $this->updateData(dataName: "buyinsUpdate", params: $params);
  }
  public function updateFees(array $params): int|array {
    return $this->updateData(dataName: "feesUpdate", params: $params);
  }
  public function updateGameType(array $params): int|array {
    return $this->updateData(dataName: "gameTypeUpdate", params: $params);
  }
  public function updateGroup(array $params): int|array {
    return $this->updateData(dataName: "groupUpdate", params: $params);
  }
  public function updateGroupPayout(array $params): int|array {
    return $this->updateData(dataName: "groupPayoutUpdate", params: $params);
  }
  public function updateLimitType(array $params): int|array {
    return $this->updateData(dataName: "limitTypeUpdate", params: $params);
  }
  public function updateLocation(array $params): int|array {
    return $this->updateData(dataName: "locationUpdate", params: $params);
  }
  public function updateNotification(array $params): int|array {
    return $this->updateData(dataName: "notificationUpdate", params: $params);
  }
  public function updatePayout(array $params): int|array {
    return $this->updateData(dataName: "payoutUpdate", params: $params);
  }
  public function updateRegistration(array $params): int|array {
    return $this->updateData(dataName: "registrationUpdate", params: $params);
  }
  public function updateRegistrationCancel(array $params): int|array {
    return $this->updateData(dataName: "registrationCancelUpdate", params: $params);
  }
  public function updateResult(array $params): int|array {
    return $this->updateData(dataName: "resultUpdate", params: $params);
  }
  public function updateResultDuring(array $params): int|array {
    return $this->updateData(dataName: "resultUpdateDuring", params: $params);
  }
  public function updateResultByTournamentIdAndPlace(array $params): int|array {
    return $this->updateData(dataName: "resultUpdateByTournamentIdAndPlace", params: $params);
  }
  public function updateResultByTournamentId(array $params): int|array {
    return $this->updateData(dataName: "resultUpdateByTournamentId", params: $params);
  }
  public function updateSeason(array $params): int|array {
    return $this->updateData(dataName: "seasonUpdate", params: $params);
  }
  public function updateTournament(array $params): int|array {
    return $this->updateData(dataName: "tournamentUpdate", params: $params);
  }
  public function updateSpecialType(array $params): int|array {
    return $this->updateData(dataName: "specialTypeUpdate", params: $params);
  }
  public function updateUser(array $params): int|array {
    return $this->updateData(dataName: "userUpdate", params: $params);
  }
  public function updateUserReset(array $params): int|array {
    return $this->updateData(dataName: "userUpdateReset", params: $params);
  }
  public function updateUserChangePassword(array $params): int|array {
    return $this->updateData(dataName: "userUpdateChangePassword", params: $params);
  }
  public function updateUserRememberMe(array $params): int|array {
    return $this->updateData(dataName: "userUpdateRememberMe", params: $params);
  }
  public function updateUserRememberMeClear(array $params): int|array {
    return $this->updateData(dataName: "userUpdateRememberMeClear", params: $params);
  }
  private function initializeDatabase(): Database {
    if ($_SERVER["SERVER_NAME"] == Constant::URL || $_SERVER["SERVER_NAME"] == Constant::URL_WWW) {
      $username = self::USER_NAME_SERVER;
      $password = self::PASSWORD_SERVER;
    } else {
      $username = self::USER_NAME_LOCAL;
      $password = self::PASSWORD_LOCAL;
    }
    $port = self::PORT;
    $databaseName = self::DATABASE_NAME;
    $database = new Database(debug: $this->isDebug(), hostName: self::HOST_NAME, userid: $username, password: $password, databaseName: $databaseName, port: $port);
    return $database;
  }
  private function initializeConnection(): PDO {
    try {
      $connection = new PDO(dsn: $this->getDatabase()->getDsn(), username: $this->getDatabase()->getUserid(), password: $this->getDatabase()->getPassword(),
        options: array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false));
    } catch (PDOException $e) {
      echo 'Connection failed: ' . $e->getMessage() . "\r\n" . $this->getDatabase();
    }
    return $connection;
  }
  // $dataName is name of the query
  // $params is array of input parameters
  // $paramsNested is array of input parameters
  // $orderBy is array of fields to order by
  // $returnQuery is boolean (true returns query instead of results, false returns results)
  // $limitCount is number to limit the results by
  // $rank is boolean (true means ranking, false means no ranking)
  private function getData(string $dataName, array|NULL $params, array|NULL $paramsNested = NULL, array|string|NULL $orderBy = NULL, bool $returnQuery = false, int|NULL $limitCount = NULL, bool $rank = false): array|string {
    $resultList = array();
    $pdoStatement = NULL;
    switch ($dataName) {
      case "blobTest":
        $query = "SELECT name, contentType, blobcontents FROM blobtest";
        break;
      case "autoRegisterHost":
        $query =
        "SELECT t.tournamentId, t.tournamentDate, t.startTime, l.playerId, l.address, l.city, l.state, l.zipCode, CONCAT(u.first_name, ' ', u.last_name) AS name, u.email " .
        "FROM poker_tournament t " .
        "INNER JOIN poker_location l ON t.locationId = l.locationId AND tournamentDate BETWEEN :startDate AND DATE_ADD(:endDate, INTERVAL 14 DAY) " .
        "INNER JOIN poker_user u ON l.playerId = u.id AND " . $this->buildUserActive(alias: "u") .
        " LEFT JOIN poker_result r ON t.tournamentId = r.tournamentId AND u.id = r.playerId " .
        "WHERE r.playerId IS NULL";
        if (isset($limitCount)) {
          $query .= " LIMIT :limitCount";
        }
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':startDate', $params[0], PDO::PARAM_STR);
        $pdoStatement->bindParam(':endDate', $params[1], PDO::PARAM_STR);
        if (isset($limitCount)) {
          $pdoStatement->bindParam(':limitCount', $limitCount, PDO::PARAM_INT);
        }
        break;
      case "bullyForUser":
        $query =
        "SELECT CONCAT(u.first_name, ' ', u.last_name) AS name, u.active, COUNT(r.playerId) AS kOs " .
        "FROM poker_result r " .
        "INNER JOIN poker_user u ON r.playerId = u.id " .
        "WHERE r.knockedOutBy = :knockedOutBy " .
        "GROUP BY r.playerId " .
        "ORDER BY kOs DESC, " . $this->buildOrderByName(prefix: NULL);
        if (isset($limitCount)) {
          $query .= " LIMIT :limitCount";
        }
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':knockedOutBy', $params[0], PDO::PARAM_INT);
        if (isset($limitCount)) {
          $pdoStatement->bindParam(':limitCount', $limitCount, PDO::PARAM_INT);
        }
        break;
      case "championship":
        $query = "SELECT ";
        if (! isset($params[3]) || !$params[3]) {
          $query .= " yr, id, ";
        }
        $query .= "name, ";
        if (isset($params[3]) && $params[3]) {
          $query .= "SUM(earnings) AS ";
        }
        $queryAndBindParams = $this->buildChampionship($params);
        $query .=
        "earnings, SUM(earnings) / trnys AS avg, trnys " .
        "FROM (" . $queryAndBindParams[0] . ") a " .
        "WHERE earnings > 0 " .
        "GROUP BY " . $params[2];
        if (isset($limitCount)) {
          $query .= " LIMIT :limitCount";
        }
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        if (isset($queryAndBindParams[1])) {
          foreach ($queryAndBindParams[1] as $key => $value) {
            $pdoStatement->bindParam($key, $value, PDO::PARAM_STR);
          }
        }
        if (isset($limitCount)) {
          $pdoStatement->bindParam(':limitCount', $limitCount, PDO::PARAM_INT);
        }
        break;
      case "championshipQualifiedPlayers":
        $query =
        "SELECT u.id, CONCAT(u.first_name, ' ', u.last_name) AS name, " .
        "       SUM((np.numPlayers - r.place + 1) * IFNULL(st.typeMultiplier, 1) + IF(r.place BETWEEN 1 AND se.seasonFinalTablePlayers, se.seasonFinalTableBonusPoints, 0)) AS points, " .
        "       SUM(IF(r.place BETWEEN 1 AND se.seasonFinalTablePlayers, se.seasonFinalTableBonusPoints, 0)) AS 'bonus points', " .
        "       nt.numTourneys AS tourneys, " .
        "       SUM((np.numPlayers - r.place + 1) * IFNULL(st.typeMultiplier, 1) + IF(r.place BETWEEN 1 AND se.seasonFinalTablePlayers, se.seasonFinalTableBonusPoints, 0)) / nt.numTourneys AS 'average points', " .
        "       ta.playerId AS playerIdAbsence, IF(ta.playerId IS NULL, 'Attending', 'Not attending') AS 'absence status' " .
        "FROM poker_user u INNER JOIN poker_result r ON u.id = r.playerid AND " . $this->buildUserActive(alias: "u") .
        " INNER JOIN poker_tournament t on r.tournamentid = t.tournamentid AND t.tournamentDate BETWEEN :startDate1 AND :endDate1 " .
        "LEFT JOIN poker_special_type st ON t.specialTypeId = st.typeId " .
        "INNER JOIN (SELECT r1.playerid, COUNT(*) AS numTourneys " .
        "            FROM poker_result r1 " .
        "            INNER JOIN poker_tournament t1 ON r1.tournamentid = t1.tournamentid " .
        "            WHERE r1.place > 0 " .
        "            AND t1.tournamentDate BETWEEN :startDate2 AND :endDate2 " .
        "            GROUP BY r1.playerid) nt ON r.playerid = nt.playerid " .
        "INNER JOIN (SELECT tournamentid, COUNT(*) AS numPlayers " .
        "            FROM poker_result " .
        "            WHERE place > 0 " .
        "            GROUP BY tournamentid) np ON r.tournamentid = np.tournamentid " .
        "INNER JOIN poker_season se ON t.tournamentDate BETWEEN se.seasonStartDate AND se.seasonEndDate " .
        "LEFT JOIN (SELECT t.tournamentId, t.tournamentDate FROM poker_tournament t INNER JOIN poker_special_type st ON t.specialTypeId = st.typeId WHERE st.typeDescription = '" . Constant::DESCRIPTION_CHAMPIONSHIP . "') tc ON tc.tournamentDate BETWEEN se.seasonStartDate AND se.seasonEndDate " .
        "LEFT JOIN poker_tournament_absence ta ON tc.tournamentId = ta.tournamentId AND u.id = ta.playerId " .
        "LEFT JOIN poker_result ra ON ta.tournamentId = ra.tournamentId AND ta.playerId = ra.playerId " .
        "WHERE nt.numTourneys >= :numTourneys " .
        "GROUP BY r.playerid " .
        "ORDER BY points DESC";
        if (isset($limitCount)) {
          $query .= " LIMIT :limitCount";
        }
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':startDate1', $params[0], PDO::PARAM_STR);
        $pdoStatement->bindParam(':endDate1', $params[1], PDO::PARAM_STR);
        $pdoStatement->bindParam(':startDate2', $params[0], PDO::PARAM_STR);
        $pdoStatement->bindParam(':endDate2', $params[1], PDO::PARAM_STR);
        $pdoStatement->bindParam(':numTourneys', $params[2], PDO::PARAM_INT);
        if (isset($limitCount)) {
          $pdoStatement->bindParam(':limitCount', $limitCount, PDO::PARAM_INT);
        }
        break;
      case "countTournamentForDates": // 0 is start date, 1 is end date, 2 is user id, 3 is true for result table and false for not
        $query =
        "SELECT COUNT(*) AS cnt " .
        "FROM poker_tournament t";
        if (isset($params[3]) && $params[3]) {
          $query .= " INNER JOIN poker_result r ON t.tournamentId = r.tournamentId";
        }
        if (isset($params[2])) {
          $query .=
          " WHERE r.playerId = :playerId" .
          " AND r.statusCode = '" . Constant::CODE_STATUS_FINISHED . "' AND ";
        } else {
          $query .= " WHERE ";
        }
        $query .= "t.tournamentDate BETWEEN :startDate AND :endDate";
        if (isset($limitCount)) {
          $query .= " LIMIT :limitCount";
        }
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        if (isset($params[2])) {
          $pdoStatement->bindParam(':playerId', $params[2], PDO::PARAM_INT);
          $pdoStatement->bindParam(':startDate', $params[0], PDO::PARAM_STR);
          $pdoStatement->bindParam(':endDate', $params[1], PDO::PARAM_STR);
        } else {
          $pdoStatement->bindParam(':startDate', $params[0], PDO::PARAM_STR);
          $pdoStatement->bindParam(':endDate', $params[1], PDO::PARAM_STR);
        }
        if (isset($limitCount)) {
          $pdoStatement->bindParam(':limitCount', $limitCount, PDO::PARAM_INT);
        }
        break;
      case "earningsAverageForSeason":
      case "earningsTotalForChampionship":
      case "earningsTotalForSeason":
      case "earningsTotalAndAverageForSeasonForUser":
      case "earningsTotalAndAverageForUser":
        if ("earningsTotalAndAverageForUser" == $dataName) {
          $userId = $params[0];
        } else if ("earningsTotalForSeason" != $dataName && "earningsAverageForSeason" != $dataName && "earningsTotalForChampionship" != $dataName) {
          $userId = $params[2];
        }
        $query = "";
        if ("earningsTotalForChampionship" != $dataName) {
          $query .=
          "SELECT u.id, CONCAT(u.first_name, ' ', u.last_name) AS name, IFNULL(earns, 0) AS earns, IFNULL(earns / trnys, 0) AS avg, IFNULL(trnys, 0) AS trnys, u.active " .
          "FROM poker_user u " .
          "LEFT JOIN (SELECT id, SUM(totalEarnings) AS earns, numTourneys AS trnys " .
          "           FROM (SELECT id, first_name, last_name, SUM(earnings) AS totalEarnings, MAX(earnings) AS maxEarnings, numTourneys " .
          "                 FROM (SELECT p.id, p.first_name, p.last_name, " .
          "                              ((np.numPlayers * (t.buyinAmount - (t.buyinAmount * t.rake))) + " .
          "                               (IFNULL(nr.numRebuys, 0) * (t.rebuyAmount - (t.rebuyAmount * t.rake))) + " .
          "                               (IFNULL(na.numAddons, 0) * (t.addonAmount - (t.addonAmount * t.rake)))) * IFNULL(s.percentage, 0) AS Earnings, " .
          "                              nt.NumTourneys " .
          "            FROM poker_user p " .
          "            INNER JOIN poker_result r ON p.id = r.playerId " .
          "            INNER JOIN poker_tournament t ON r.tournamentId = t.tournamentId ";
          if ("earningsTotalAndAverageForUser" != $dataName) {
            $query .= "            AND t.tournamentDate BETWEEN :startDate1 AND :endDate1 ";
          }
          $query .=
          "            INNER JOIN (SELECT r1.playerId, COUNT(*) AS NumTourneys " .
          "                        FROM poker_result r1 " .
          "                        INNER JOIN poker_tournament t1 ON r1.tournamentId = t1.tournamentId AND r1.place > 0 ";
          if ("earningsTotalAndAverageForUser" != $dataName) {
            $query .= "                        AND t1.tournamentDate BETWEEN :startDate2 AND :endDate2 ";
          }
          $query .=
          "                        GROUP BY r1.playerId) nt ON r.playerId = nt.playerId " .
          "            INNER JOIN (SELECT r2.tournamentId, COUNT(*) AS numPlayers " .
          "                        FROM poker_result r2 " .
          "                        WHERE r2.place > 0 " .
          "                        GROUP BY r2.tournamentId) np ON r.tournamentId = np.tournamentId " .
          "            LEFT JOIN (SELECT r3.tournamentId, SUM(r3.rebuyCount) AS numRebuys " .
          "                       FROM poker_result r3 " .
          "                       WHERE r3.place > 0 " .
          "                       AND r3.rebuyCount > 0 " .
          "                       GROUP BY r3.tournamentId) nr ON r.tournamentId = nr.tournamentId " .
          "            LEFT JOIN (SELECT tournamentId, COUNT(addonPaid) AS numAddons " .
          "                       FROM poker_result " .
          "                       WHERE addonPaid = '" . Constant::FLAG_YES . "' " .
          "                       GROUP BY tournamentId) na ON r.tournamentId = na.tournamentId " .
          "            LEFT JOIN (SELECT a.tournamentId, s1.payoutId, s1.place, s1.percentage " .
          "                       FROM (SELECT np.tournamentId, p.payoutId " .
          "                             FROM (SELECT r.tournamentId, COUNT(*) AS numPlayers " .
          "                                   FROM poker_result r " .
          "                                   WHERE r.statusCode IN ('" . Constant::CODE_STATUS_REGISTERED . "', '" . Constant::CODE_STATUS_FINISHED . "') " .
          "                                   GROUP BY r.tournamentId) np " .
          "                             INNER JOIN poker_tournament t on np.tournamentId = t.tournamentId " .
          "                             INNER JOIN poker_group_payout gp ON t.GroupId = gp.GroupId " .
          "                             INNER JOIN poker_payout p ON gp.PayoutId = p.PayoutId AND np.numPlayers BETWEEN p.minPlayers AND p.maxPlayers) a " .
          "                       INNER JOIN poker_structure s1 ON a.payoutId = s1.payoutId) s ON r.tournamentId = s.tournamentId AND r.place = s.place WHERE r.place > 0) y " .
          "            GROUP BY id " .
          "            UNION ";
        } else {
          $query .= "             SELECT id, CONCAT(first_name, ' ', last_name) AS name, IFNULL(totalEarnings, 0) AS earns";
          if ("earningsTotalForChampionship" == $dataName) {
            $query .= ", IFNULL(totalEarnings / trnys, 0) AS avg, IFNULL(trnys, 0) AS trnys ";
          }
          $query .= "             FROM (";
        }
        $query .= "            SELECT xx.id, xx.last_name, xx.first_name, SUM(xx.earnings) AS totalEarnings, MAX(xx.earnings) AS maxEarnings, 0";
        if ("earningsTotalForChampionship" == $dataName) {
          $query .= ", numTourneys AS trnys ";
        }
        $query .= "            FROM (SELECT Yr, p.Id, p.first_name, p.last_name, ";
        if ("earningsTotalForChampionship" == $dataName) {
          $query .= " numTourneys, ";
        }
        $query .=
        "                        qq.total * IFNULL(s.percentage, 0) AS Earnings " .
        "                 FROM poker_user p " .
        "                 INNER JOIN poker_result r ON p.id = r.playerId " .
        "                 INNER JOIN poker_tournament t ON r.tournamentId = t.tournamentId";
        if ("earningsTotalAndAverageForUser" != $dataName && "earningsTotalForChampionship" != $dataName) {
          $query .= "        AND t.tournamentDate BETWEEN :startDate3 AND :endDate3 ";
        }
        if ("earningsTotalForChampionship" == $dataName && isset($params[0])) {
          $query .= "                               AND YEAR(t.tournamentDate) IN (:tournamentDate1) ";
        }
        $query .=
        "                  INNER JOIN (SELECT Yr, SUM(total) - IF(Yr = 2008, 150, " . // adjust to match Dave W stats
        "                                                       IF(Yr = 2007, -291, " . // adjust to match Dave W stats
        "                                                        IF(Yr = 2006, -824, 0))) " . // adjust to match Dave W stats
        "                                         AS total " .
        "                         FROM (SELECT YEAR(t2.tournamentDate) AS Yr, t2.tournamentId AS Id, IFNULL(b.Play, 0) AS Play, " .
        "                                      ((t2.BuyinAmount * t2.rake) * Play) + " .
        "                                      ((t2.rebuyAmount * t2.rake) * IFNULL(nr.numRebuys, 0)) + " .
        "                                      ((t2.addonAmount * t2.rake) * IFNULL(na.numAddons, 0)) AS Total " .
        "                               FROM poker_tournament t2 LEFT JOIN (SELECT tournamentId, COUNT(*) AS Play " .
        "                                                                   FROM poker_result " .
        "                                                                   WHERE buyinPaid = '" . Constant::FLAG_YES . "' " . "                                                                   AND place > 0 " .
        "                                                                   GROUP BY tournamentId) b ON t2.tournamentId = b.tournamentId";
        if ("earningsTotalAndAverageForUser" != $dataName && "earningsTotalForChampionship" != $dataName) {
          $query .= "                               AND t2.tournamentDate BETWEEN :startDate4 AND :endDate4 ";
        }
        if ("earningsTotalForChampionship" == $dataName && isset($params[0])) {
          $query .= "                               AND YEAR(t2.tournamentDate) IN (:tournamentDate2) ";
        }
        $query .=
        "                              LEFT JOIN (SELECT r.tournamentId, SUM(r.rebuyCount) AS numRebuys " .
        "                                         FROM poker_result r " .
        "                                         WHERE r.rebuyPaid = '" . Constant::FLAG_YES . "' " .
        "                                         AND r.rebuyCount > 0 " .
        "                                         GROUP BY r.tournamentId) nr ON t2.tournamentId = nr.tournamentId " .
        "                              LEFT JOIN (SELECT r.tournamentId, COUNT(*) AS numAddons " .
        "                                         FROM poker_result r " .
        "                                         WHERE r.AddonPaid = '" . Constant::FLAG_YES . "' " .
        "                                         GROUP BY r.tournamentId) na ON t2.tournamentId = na.tournamentId) zz " .
        "                        GROUP BY zz.yr) qq";
        $query .= " ON qq.yr = YEAR(t.tournamentDate) ";
        $query .= "                  LEFT JOIN poker_special_type st ON t.specialTypeId = st.typeId";
        if ("earningsTotalForChampionship" == $dataName) {
          $query .=
          "                  INNER JOIN (SELECT r1.playerId, COUNT(*) AS NumTourneys FROM poker_result r1 INNER JOIN poker_tournament t1 ON r1.tournamentId = t1.tournamentId AND r1.place > 0 INNER JOIN poker_special_type st1 ON t1.specialTypeId = st1.typeId AND st1.typeDescription = '" . Constant::DESCRIPTION_CHAMPIONSHIP . "' GROUP BY r1.playerId) nt ON r.playerId = nt.playerId ";
        }
        $query .=
        "                  LEFT JOIN (SELECT a.tournamentId, s1.payoutId, s1.place, s1.percentage " .
        "                             FROM (SELECT np.tournamentId, p.payoutId " .
        "                                   FROM (SELECT r.tournamentId, COUNT(*) AS numPlayers " .
        "                                         FROM poker_result r " .
        "                                         WHERE r.statusCode IN ('" . Constant::CODE_STATUS_REGISTERED . "','" . Constant::CODE_STATUS_FINISHED . "') " .
        "                                         GROUP BY r.tournamentId) np " .
        "                                   INNER JOIN poker_tournament t on np.tournamentId = t.tournamentId";
        if ("earningsTotalAndAverageForUser" != $dataName && "earningsTotalForChampionship" != $dataName) {
          $query .= "                                    AND t.tournamentDate BETWEEN :startDate5 AND :endDate5 ";
        }
        if ("earningsTotalForChampionship" == $dataName && isset($params[0])) {
          $query .= "                               AND YEAR(t.tournamentDate) IN (:tournamentDate3) ";
        }
        $query .=
        "                                   INNER JOIN poker_group_payout gp ON t.GroupId = gp.GroupId " .
        "                                   INNER JOIN poker_payout p ON gp.PayoutId = p.PayoutId AND np.numPlayers BETWEEN p.minPlayers AND p.maxPlayers) a " .
        "                             INNER JOIN poker_structure s1 ON a.payoutId = s1.payoutId) s ON r.tournamentId = s.tournamentId AND r.place = s.place " .
        "                  WHERE r.place > 0 " .
        "                  AND st.typeDescription = '" . Constant::DESCRIPTION_CHAMPIONSHIP . "'" .
        "                  GROUP BY id, yr) xx ";
        if ("earningsTotalForChampionship" == $dataName) {
          $query .=
          "        GROUP BY xx.id, xx.last_name, xx.first_name " .
          "        ORDER BY totalearnings desc, xx.last_name, xx.first_name) a";
        } else {
          $query .=
          "      GROUP BY xx.id, xx.last_name, xx.first_name) cc " .
          "GROUP BY id) z ON u.id = z.id ";
          if ("earningsTotalForSeason" != $dataName && "earningsAverageForSeason" != $dataName && "earningsTotalForChampionship" != $dataName) {
            $whereClause = "WHERE u.id = " . $userId;
            $query .= " WHERE u.id = " . $userId;
          } else {
            $query .= " WHERE " . $this->buildUserActive(alias: "u");
          }
          if ("earningsTotalAndAverageForUser" != $dataName && "earningsTotalAndAverageForSeasonForUser" != $dataName) {
            $query .= " ORDER BY ";
            if (1 == $orderBy[0]) {
              $query .= "earns";
            } else {
              $query .= "avg";
            }
            $query .= " DESC, " . $this->buildOrderByName(prefix: NULL);
          }
          if ("earningsTotalForChampionship" == $dataName) {
            $query .= ")";
          }
          if ($rank) {
            if (1 == $orderBy[0]) {
              $orderByFieldName = "earns DESC, " . $this->buildOrderByName(prefix: NULL);
              $selectFieldName = "earns";
            } else {
              $orderByFieldName = "avg DESC, " . $this->buildOrderByName(prefix: NULL);
              $selectFieldName = "avg";
            }
            $selectFieldNames = "id, name, earns, avg, trnys";
            $query = $this->modifyQueryAddRank(query: $query, whereClause: $whereClause, selectFieldName: $selectFieldName, selectFieldNames: $selectFieldNames, orderByFieldName: $orderByFieldName);
          }
        }
        if (isset($limitCount)) {
          $query .= " LIMIT :limitCount";
        }
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        if ("earningsTotalAndAverageForUser" != $dataName && "earningsTotalForChampionship" != $dataName) {
          $pdoStatement->bindParam(':startDate1', $params[0], PDO::PARAM_STR);
          $pdoStatement->bindParam(':endDate1', $params[1], PDO::PARAM_STR);
          $pdoStatement->bindParam(':startDate2', $params[0], PDO::PARAM_STR);
          $pdoStatement->bindParam(':endDate2', $params[1], PDO::PARAM_STR);
          $pdoStatement->bindParam(':startDate3', $params[0], PDO::PARAM_STR);
          $pdoStatement->bindParam(':endDate3', $params[1], PDO::PARAM_STR);
          $pdoStatement->bindParam(':startDate4', $params[0], PDO::PARAM_STR);
          $pdoStatement->bindParam(':endDate4', $params[1], PDO::PARAM_STR);
          $pdoStatement->bindParam(':startDate5', $params[0], PDO::PARAM_STR);
          $pdoStatement->bindParam(':endDate5', $params[1], PDO::PARAM_STR);
        } else if ("earningsTotalForChampionship" == $dataName && isset($params[0])) {
          $pdoStatement->bindParam(':tournamentDate1', $params[0], PDO::PARAM_STR);
          $pdoStatement->bindParam(':tournamentDate2', $params[0], PDO::PARAM_STR);
          $pdoStatement->bindParam(':tournamentDate3', $params[0], PDO::PARAM_STR);
        }
        if (isset($limitCount)) {
          $pdoStatement->bindParam(':limitCount', $limitCount, PDO::PARAM_INT);
        }
        break;
      case "feeDetail":
        $query =
        "SELECT s.seasonId, s.seasonDescription AS Description, CONCAT(u.first_name, ' ', u.last_name) AS Name, f.tournamentId, s.seasonFee AS Fee, IFNULL(f.amount, 0) AS Paid, IF(f.amount IS NULL, s.seasonFee, s.seasonFee - f.amount) AS Balance " .
        "FROM poker_user u CROSS JOIN poker_season s ON " . $this->buildUserActive(alias: "u") .
        " LEFT JOIN poker_fee f ON s.seasonId = f.seasonId AND u.id = f.playerId AND f.amount > 0 " .
        "ORDER BY s.seasonDescription, f.playerId";
        break;
      case "feeSelectBySeason":
        $query =
        "SELECT s.seasonId, s.seasonDescription AS Description, s.seasonStartDate AS 'Start Date', s.seasonEndDate AS 'End Date', SUM(f.amount) AS Amount " .
        "FROM poker_fee f INNER JOIN poker_season s ON f.seasonId = s.seasonId " .
        "GROUP BY s.seasonId";
        break;
      case "feeSelectByTournamentIdAndPlayerId":
        $query =
        "SELECT se.seasonId, u.id, f.amount, " .
        "IF(se.seasonFee IS NULL OR f.amount IS NULL, '', IF(se.seasonFee - f.amount = 0, 'Paid', CONCAT('Owes $', (se.seasonFee - f.amount)))) AS status " .
        "FROM poker_user u INNER JOIN poker_tournament t ON u.id = :playerId AND t.tournamentId = :tournamentId " .
        "INNER JOIN poker_season se ON t.tournamentDate BETWEEN se.seasonStartDate AND se.seasonEndDate " .
        "INNER JOIN (SELECT seasonId, playerId, SUM(amount) AS amount FROM poker_fee GROUP BY seasonId, playerId) f ON se.seasonId = f.seasonId AND u.id = f.playerId";
        if (isset($limitCount)) {
          $query .= " LIMIT :limitCount";
        }
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':playerId', $params[0], PDO::PARAM_INT);
        $pdoStatement->bindParam(':tournamentId', $params[1], PDO::PARAM_INT);
        if (isset($limitCount)) {
          $pdoStatement->bindParam(':limitCount', $limitCount, PDO::PARAM_INT);
        }
        break;
      case "finishesSelectAllByPlayerId":
        $query =
        "SELECT a.place, IFNULL(b.finishes, 0) AS finishes, IFNULL(b.pct, 0) AS pct " .
        "FROM (SELECT DISTINCT place " .
        "      FROM poker_result " .
        "      WHERE place > 0 " .
        "      ORDER BY place) a " .
        "LEFT JOIN (SELECT r1.place, COUNT(*) AS finishes, COUNT(*) / (SELECT COUNT(*) " .
        "                                                              FROM poker_result r2 " .
        "                                                              INNER JOIN poker_tournament t2 ON r2.tournamentId = t2.tournamentId";
        if (isset($params[1]) && isset($params[2])) {
          $query .= "                                                    AND t2.tournamentDate BETWEEN :startDate1 AND :endDate1 ";
        }
        $query .=
        "                                                              WHERE r2.playerId = r1.playerId) AS pct " .
        "           FROM poker_result r1 " .
        "           INNER JOIN poker_tournament t1 ON r1.tournamentId = t1.tournamentId";
        if (isset($params[1]) && isset($params[2])) {
          $query .= " AND t1.tournamentDate BETWEEN :startDate2 AND :endDate2";
        }
        $query .=
        "           AND r1.playerId = :playerId" .
        "           GROUP BY r1.place) b ON a.place = b.place";
        if (isset($limitCount)) {
          $query .= " LIMIT :limitCount";
        }
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        if (isset($params[1]) && isset($params[2])) {
          $pdoStatement->bindParam(':playerId', $params[0], PDO::PARAM_INT);
          $pdoStatement->bindParam(':startDate1', $params[1], PDO::PARAM_STR);
          $pdoStatement->bindParam(':endDate1', $params[2], PDO::PARAM_STR);
          $pdoStatement->bindParam(':startDate2', $params[1], PDO::PARAM_STR);
          $pdoStatement->bindParam(':endDate2', $params[2], PDO::PARAM_STR);
        } else {
          $pdoStatement->bindParam(':playerId', $params[0], PDO::PARAM_INT);
        }
        if (isset($limitCount)) {
          $pdoStatement->bindParam(':limitCount', $limitCount, PDO::PARAM_INT);
        }
        break;
      case "foodByTournamentIdAndPlayerId":
        $query =
        "SELECT CONCAT(u.first_name, ' ', u.last_name) AS name, u.email, r.food, u.active " .
        "FROM poker_user u LEFT JOIN poker_result r ON u.id = r.playerId AND r.tournamentId = :tournamentId " .
        "WHERE u.id = :playerId";
        if (isset($limitCount)) {
          $query .= " LIMIT :limitCount";
        }
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':tournamentId', $params[0], PDO::PARAM_INT);
        $pdoStatement->bindParam(':playerId', $params[1], PDO::PARAM_INT);
        if (isset($limitCount)) {
          $pdoStatement->bindParam(':limitCount', $limitCount, PDO::PARAM_INT);
        }
        break;
      case "gameTypeSelectAll":
      case "gameTypeSelectOneById":
        $query =
        "SELECT gameTypeId AS id, gameTypeName AS name " .
        "FROM poker_game_type";
        if ("gameTypeSelectOneById" == $dataName) {
          $query .= " WHERE gameTypeId = :typeId";
          //         } else if ("gameTypeSelectAll" == $dataName) {
          //           if ($params[1]) {
          //             $query .= " WHERE gameTypeId IN (:typeId)";
          //           }
        }
        if (isset($limitCount)) {
          $query .= " LIMIT :limitCount";
        }
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        if ("gameTypeSelectOneById" == $dataName) {
          $pdoStatement->bindParam(':typeId', $params[0], PDO::PARAM_INT);
          //         } else if ("gameTypeSelectAll" == $dataName) {
          //           if ($params[1]) {
          //             $pdoStatement->bindParam(':typeId', $params[1], PDO::PARAM_INT);
          //           }
        }
        if (isset($limitCount)) {
          $pdoStatement->bindParam(':limitCount', $limitCount, PDO::PARAM_INT);
        }
        break;
      case "groupSelectAll":
      case "groupSelectAllById":
        $query =
        "SELECT groupId AS id, groupName AS name " .
        "FROM poker_group";
        if ("groupSelectAllById" == $dataName) {
          $query .= " WHERE groupId = :groupId";
        } else if ("groupSelectAll" == $dataName) {
          if (isset($params[0])) {
            $query .= " WHERE groupId IN (:groupId)";
          }
        }
        if (isset($limitCount)) {
          $query .= " LIMIT :limitCount";
        }
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        if ("groupSelectAllById" == $dataName) {
          $pdoStatement->bindParam(':groupId', $params[0], PDO::PARAM_INT);
        } else if ("groupSelectAll" == $dataName) {
          if (isset($params[0])) {
            $pdoStatement->bindParam(':groupId', $params[0], PDO::PARAM_INT);
          }
        }
        if (isset($limitCount)) {
          $pdoStatement->bindParam(':limitCount', $limitCount, PDO::PARAM_INT);
        }
        break;
      case "groupPayoutSelectAll":
      case "groupPayoutSelectAllById":
        $query =
        "SELECT gp.groupId AS id, g.groupName AS 'group name', gp.payoutId AS 'payout id', p.payoutName AS 'payout name' " .
        "FROM poker_group_payout gp INNER JOIN poker_group g ON gp.groupId = g.groupId " .
        "INNER JOIN poker_payout p ON gp.payoutId = p.payoutId";
        if ("groupPayoutSelectAllById" == $dataName) {
          $query .= " WHERE gp.groupId = :groupId AND gp.payoutId = :payoutId";
        } else if ("groupPayoutSelectAll" == $dataName) {
          if (isset($params[0]) && isset($params[1])) {
            $query .=
            " WHERE gp.groupId IN (:groupId)" .
            " AND gp.payoutId IN (:payoutId)";
          }
        }
        if (isset($limitCount)) {
          $query .= " LIMIT :limitCount";
        }
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        if ("groupPayoutSelectAllById" == $dataName) {
          $pdoStatement->bindParam(':groupId', $params[0], PDO::PARAM_INT);
          $pdoStatement->bindParam(':payoutId', $params[1], PDO::PARAM_INT);
        } else if ("groupPayoutSelectAll" == $dataName) {
          if (isset($params[0]) && isset($params[1])) {
            $pdoStatement->bindParam(':groupId', $params[0], PDO::PARAM_INT);
            $pdoStatement->bindParam(':payoutId', $params[1], PDO::PARAM_INT);
          }
        }
        if (isset($limitCount)) {
          $pdoStatement->bindParam(':limitCount', $limitCount, PDO::PARAM_INT);
        }
        break;
      case "groupSelectNameList":
        $query =
        "SELECT groupId, groupName " .
        "FROM poker_group";
        break;
      case "knockoutsAverageForSeason":
      case "knockoutsTotalForSeason":
      case "knockoutsTotalAndAverageForSeasonForUser":
      case "knockoutsTotalAndAverageForUser":
        if ("knockoutsTotalAndAverageForUser" == $dataName) {
          $userId = $params[0];
        } else if ("knockoutsTotalForSeason" != $dataName && "knockoutsAverageForSeason" != $dataName) {
          $userId = $params[2];
        }
        $query =
        "SELECT u.id, CONCAT(u.first_name, ' ', u.last_name) AS name, IFNULL(kO, 0) AS kO, IFNULL(avg, 0) AS avg, IFNULL(trnys, 0) AS trnys, u.active " .
        "FROM poker_user u LEFT JOIN (SELECT id, k.knockouts AS kO, ROUND(k.knockouts / nt.numTourneys, 2) AS avg, nt.numTourneys AS trnys " .
        "                             FROM (SELECT u.id, u.first_name, u.last_name, COUNT(*) AS knockouts " .
        "                                   FROM poker_tournament t " .
        "                                   INNER JOIN poker_result r ON t.tournamentId = r.tournamentId ";
        if ("knockoutsTotalAndAverageForUser" != $dataName) {
          $query .= "                                   AND t.tournamentDate BETWEEN :startDate1 AND :endDate1 ";
        }
        $query .=
        "                                   AND r.place > 0 " .
        "                                   INNER JOIN poker_user u ON r.knockedOutBy = u.id " .
        "                                   GROUP BY r.knockedOutBy) k " .
        "INNER JOIN (SELECT r.playerId, COUNT(*) AS numTourneys " .
        "            FROM poker_tournament t INNER JOIN poker_result r ON t.tournamentId = r.tournamentId AND r.place > 0 ";
        if ("knockoutsTotalAndAverageForUser" != $dataName) {
          $query .= "   AND t.tournamentDate BETWEEN :startDate2 AND :endDate2 ";
        }
        $query .= "    GROUP BY r.playerId) nt ON k.Id = nt.PlayerId) a ON u.id = a.id";
        if ("knockoutsTotalForSeason" != $dataName && "knockoutsAverageForSeason" != $dataName) {
          $whereClause = "WHERE u.id = " . $userId;
          $query .= " WHERE u.id = " . $userId;
        } else {
          $query .= " WHERE " . $this->buildUserActive(alias: "u");
        }
        if ("knockoutsTotalAndAverageForSeasonForUser" != $dataName && "knockoutsTotalAndAverageForUser" != $dataName) {
          $query .= " ORDER BY ";
          if (1 == $orderBy[0]) {
            $query .= "kO";
          } else {
            $query .= "avg";
          }
          $query .= " DESC, " . $this->buildOrderByName(prefix: NULL);
        }
        if ($rank) {
          if (1 == $orderBy[0]) {
            $orderByFieldName = "kO DESC, " . $this->buildOrderByName(prefix: NULL);
            $selectFieldName = "kO";
          } else {
            $orderByFieldName = "avg DESC, " . $this->buildOrderByName(prefix: NULL);
            $selectFieldName = "avg";
          }
          $selectFieldNames = "id, name, kO, avg, trnys";
          $query = $this->modifyQueryAddRank(query: $query, whereClause: $whereClause, selectFieldName: $selectFieldName, selectFieldNames: $selectFieldNames, orderByFieldName: $orderByFieldName);
        }
        if (isset($limitCount)) {
          $query .= " LIMIT :limitCount";
        }
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        if ("knockoutsTotalAndAverageForUser" != $dataName) {
          $pdoStatement->bindParam(':startDate1', $params[0], PDO::PARAM_STR);
          $pdoStatement->bindParam(':endDate1', $params[1], PDO::PARAM_STR);
          $pdoStatement->bindParam(':startDate2', $params[0], PDO::PARAM_STR);
          $pdoStatement->bindParam(':endDate2', $params[1], PDO::PARAM_STR);
        }
        if (isset($limitCount)) {
          $pdoStatement->bindParam(':limitCount', $limitCount, PDO::PARAM_INT);
        }
        break;
      case "limitTypeSelectAll":
      case "limitTypeSelectOneById":
        $query =
        "SELECT limitTypeId AS id, limitTypeName AS name " .
        "FROM poker_limit_type";
        if ("limitTypeSelectOneById" == $dataName) {
          $query .= " WHERE limitTypeId = :typeId";
          //         } else if ("limitTypeSelectAll" == $dataName) {
          //           if ($params[1]) {
          //             $query .= " WHERE limitTypeId IN (:typeId)";
          //           }
        }
        if (isset($limitCount)) {
          $query .= " LIMIT :limitCount";
        }
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        if ("limitTypeSelectOneById" == $dataName) {
          $pdoStatement->bindParam(':typeId', $params[0], PDO::PARAM_INT);
          //         } else if ("limitTypeSelectAll" == $dataName) {
          //           if ($params[1]) {
          //             $pdoStatement->bindParam(':typeId', $params[1], PDO::PARAM_INT);
          //           }
        }
        if (isset($limitCount)) {
          $pdoStatement->bindParam(':limitCount', $limitCount, PDO::PARAM_INT);
        }
        break;
      case "locationSelectAll":
        $query =
        "SELECT l.locationId AS id, l.locationName AS name, u.id as playerId, CONCAT(u.first_name, ' ', u.last_name) AS host, l.address, l.city, UPPER(l.state) AS state, l.zipCode AS zip, u.active, (SELECT COUNT(*) FROM poker_tournament t WHERE t.LocationId = l.locationId) AS trnys " .
        "FROM poker_location l INNER JOIN poker_user u ON l.playerId = u.id ";
        if ($params[1]) {
          $query .= " AND " . $this->buildUserActive(alias: "u");
        }
        if (isset($params[3])) {
          $query .= " WHERE locationId IN (:locationId)";
        }
        if ($params[2]) {
          $query .= " ORDER BY l.locationName";
        }
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        if (isset($params[3])) {
          $pdoStatement->bindParam(':locationId', $params[3], PDO::PARAM_INT);
        }
        break;
      case "locationSelectById":
        $query =
        "SELECT l.locationId AS id, l.locationName AS name, l.playerId, l.address, l.city, UPPER(l.state) AS state, l.zipCode AS zip, u.active " .
        "FROM poker_location l INNER JOIN poker_user u ON l.playerId = u.id " .
        "WHERE l.locationId IN (:locationId)";
        if (isset($limitCount)) {
          $query .= " LIMIT :limitCount";
        }
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':locationId', $params[0], PDO::PARAM_INT);
        if (isset($limitCount)) {
          $pdoStatement->bindParam(':limitCount', $limitCount, PDO::PARAM_INT);
        }
        break;
      case "login":
        $query =
        "SELECT password " .
        "FROM poker_user " .
        "WHERE username = :userName " .
        "AND " . $this->buildUserActive(alias: NULL);
        if (isset($limitCount)) {
          $query .= " LIMIT :limitCount";
        }
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':userName', $params[0], PDO::PARAM_STR);
        if (isset($limitCount)) {
          $pdoStatement->bindParam(':limitCount', $limitCount, PDO::PARAM_INT);
        }
        break;
      case "nemesisForUser":
        $query =
        "SELECT CONCAT(u.first_name, ' ', u.last_name) AS name, u.active, COUNT(r.knockedOutBy) AS kOs " .
        "FROM poker_result r INNER JOIN poker_user u ON r.knockedOutBy = u.id " .
        "WHERE r.playerId = :playerId " .
        "GROUP BY r.knockedOutBy " .
        "ORDER BY kOs DESC, " . $this->buildOrderByName(prefix: NULL);
        if (isset($limitCount)) {
          $query .= " LIMIT :limitCount";
        }
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':playerId', $params[0], PDO::PARAM_INT);
        if (isset($limitCount)) {
          $pdoStatement->bindParam(':limitCount', $limitCount, PDO::PARAM_INT);
        }
        break;
      case "notificationSelectAll":
      case "notificationSelectOneById":
        $query =
        "SELECT id, description, startDate AS 'start date', endDate AS 'end date' " .
        "FROM poker_notification ";
        if ("notificationSelectOneById" == $dataName) {
          $query .= " WHERE id = :playerId";
        } else if ("notificationSelectAll" == $dataName) {
          if (isset($params[0])) {
            $query .= " WHERE :tournamentDate BETWEEN startDate AND endDate ";
          }
          if (isset($params[1])) {
            $query .= isset($params[0]) ? " AND" : " WHERE" . " id IN (:notificationId)";
          }
          if (isset($orderBy)) {
            $query .= $orderBy;
          }
        }
        if (isset($limitCount)) {
          $query .= " LIMIT :limitCount";
        }
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        if ("notificationSelectOneById" == $dataName) {
          $pdoStatement->bindParam(':playerId', $params[0], PDO::PARAM_INT);
        } else if ("notificationSelectAll" == $dataName && isset($params[0])) {
          $pdoStatement->bindParam(':tournamentDate', $params[0], PDO::PARAM_STR);
        } else if ("notificationSelectAll" == $dataName) {
          if (isset($params[1])) {
            $pdoStatement->bindParam(':notificationId', $params[1], PDO::PARAM_INT);
          }
        }
        if (isset($limitCount)) {
          $pdoStatement->bindParam(':limitCount', $limitCount, PDO::PARAM_INT);
        }
        break;
      case "payoutSelectAll":
      case "payoutSelectAllById":
        $query =
        "SELECT p.payoutId AS id, p.payoutName AS name, p.minPlayers AS 'min players', p.maxPlayers AS 'max players', s.place, s.percentage " .
        "FROM poker_payout p INNER JOIN poker_structure s ON p.payoutId = s.payoutId";
        if ("payoutSelectAllById" == $dataName) {
          $query .= " WHERE p.payoutId = :payoutId";
        } else if ("payoutSelectAll" == $dataName) {
          if (isset($params[0])) {
            $query .= " WHERE p.payoutId IN (:payoutId)";
          }
        }
        if (isset($limitCount)) {
          $query .= " LIMIT :limitCount";
        }
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        if ("payoutSelectAllById" == $dataName) {
          $pdoStatement->bindParam(':payoutId', $params[0], PDO::PARAM_INT);
        } else if ("payoutSelectAll" == $dataName) {
          if (isset($params[0])) {
            $pdoStatement->bindParam(':payoutId', $params[0], PDO::PARAM_INT);
          }
        }
        if (isset($limitCount)) {
          $pdoStatement->bindParam(':limitCount', $limitCount, PDO::PARAM_INT);
        }
        break;
      case "payoutSelectMaxId":
        $query =
        "SELECT MAX(payoutId) AS id " .
        "FROM poker_payout";
        break;
      case "payoutSelectNameList":
        $query =
        "SELECT payoutId, payoutName " .
        "FROM poker_payout";
        break;
      case "pointsAverageForSeason":
      case "pointsTotalForSeason":
      case "pointsTotalAndAverageForSeasonForUser":
      case "pointsTotalAndAverageForUser":
        if ("pointsTotalAndAverageForUser" == $dataName) {
          $userId = $params[0];
        } else if ("pointsTotalForSeason" != $dataName && "pointsAverageForSeason" != $dataName) {
          $userId = $params[2];
        }
        $query =
        "SELECT u.id, CONCAT(u.first_name, ' ', u.last_name) AS name, IFNULL(a.points, 0) AS pts, IFNULL(ROUND(a.points / a.trnys, 2), 0) AS avg, IFNULL(a.trnys, 0) AS trnys, u.active " .
        "FROM poker_user u LEFT JOIN (SELECT u.id, SUM((np.numPlayers - r.place + 1) * IFNULL(st.typeMultiplier, 1) + IF(r.place BETWEEN 1 AND se.seasonFinalTablePlayers, se.seasonFinalTableBonusPoints, 0)) AS points, nt.trnys " .
        "                            FROM poker_user u INNER JOIN poker_result r ON u.id = r.playerId " .
        "                            INNER JOIN poker_tournament t ON r.tournamentId = t.tournamentId ";
        if ("pointsTotalAndAverageForUser" != $dataName) {
          $query .= "                             AND t.tournamentDate BETWEEN :startDate1 AND :endDate1 ";
        }
        $query .=
        "                            INNER JOIN poker_season se ON t.tournamentDate BETWEEN se.seasonStartDate AND se.seasonEndDate " .
        "                            LEFT JOIN poker_special_type st ON t.specialTypeId = st.typeId" .
        "                            INNER JOIN (SELECT r1.playerId, COUNT(*) AS trnys " .
        "                                        FROM poker_result r1 " .
        "                                        INNER JOIN poker_tournament t1 ON r1.TournamentId = t1.TournamentId AND r1.place > 0 ";
        if ("pointsTotalAndAverageForUser" != $dataName) {
          $query .= "                                         AND t1.tournamentDate BETWEEN :startDate2 AND :endDate2 ";
        }
        $query .=
        "                                        GROUP BY r1.playerId) nt ON r.playerId = nt.playerId " .
        "                            INNER JOIN (SELECT tournamentId, COUNT(*) AS numPlayers " .
        "                                        FROM poker_result " .
        "                                        WHERE place > 0 " .
        "                                        GROUP BY tournamentId) np ON r.tournamentId = np.tournamentId " .
        "                            GROUP BY r.playerId) a ON u.id = a.id";
        if ("pointsTotalForSeason" != $dataName && "pointsAverageForSeason" != $dataName) {
          $whereClause = "WHERE u.id = " . $userId;
          $query .= " WHERE u.id = " . $userId;
        } else {
          $query .= " WHERE " . $this->buildUserActive(alias: "u");
        }
        if ("pointsTotalAndAverageForUser" != $dataName && "pointsTotalAndAverageForSeasonForUser" != $dataName) {
          $query .= " ORDER BY ";
          if (1 == $orderBy[0]) {
            $query .= "pts";
          } else {
            $query .= "avg";
          }
          $query .= " DESC, " . $this->buildOrderByName(prefix: NULL);
        }
        if ($rank) {
          if (1 == $orderBy[0]) {
            $orderByFieldName = "pts DESC, " . $this->buildOrderByName(prefix: NULL);
            $selectFieldName = "pts";
          } else {
            $orderByFieldName = "avg DESC, " . $this->buildOrderByName(prefix: NULL);
            $selectFieldName = "avg";
          }
          $selectFieldNames = "id, name, pts, avg, trnys";
          $query = $this->modifyQueryAddRank(query: $query, whereClause: $whereClause, selectFieldName: $selectFieldName, selectFieldNames: $selectFieldNames, orderByFieldName: $orderByFieldName);
        }
        if (isset($limitCount)) {
          $query .= " LIMIT :limitCount";
        }
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        if ("pointsTotalAndAverageForUser" != $dataName) {
          $pdoStatement->bindParam(':startDate1', $params[0], PDO::PARAM_STR);
          $pdoStatement->bindParam(':endDate1', $params[1], PDO::PARAM_STR);
          $pdoStatement->bindParam(':startDate2', $params[0], PDO::PARAM_STR);
          $pdoStatement->bindParam(':endDate2', $params[1], PDO::PARAM_STR);
        }
        if (isset($limitCount)) {
          $pdoStatement->bindParam(':limitCount', $limitCount, PDO::PARAM_INT);
        }
        break;
      case "prizePoolForSeason":
        $query =
        "SELECT SUM(total) AS total " .
        "FROM (SELECT t.tournamentId AS Id, np.play, " .
        "             ((t.buyinAmount * t.rake) * np.play) + " .
        "             ((t.rebuyAmount * t.rake) * IFNULL(nr.numRebuys, 0)) + " .
        "             ((t.addonAmount * t.rake) * IFNULL(na.numAddons, 0)) AS total " .
        "      FROM poker_tournament t " .
        "      LEFT JOIN (SELECT TournamentId, COUNT(*) AS play " .
        "                 FROM poker_result " .
        "                 WHERE buyinPaid = '" . Constant::FLAG_YES . "' " .
        "                 AND place > 0 " .
        "                 GROUP BY tournamentId) np ON t.tournamentId = np.tournamentId " .
        "      LEFT JOIN (SELECT tournamentId, SUM(rebuyCount) AS numRebuys " .
        "                 FROM poker_result " .
        "                 WHERE rebuyPaid = '" . Constant::FLAG_YES . "' " .
        "                 AND rebuyCount > 0 " .
        "                 GROUP BY tournamentId) nr ON t.tournamentId = nr.tournamentId " .
        "      LEFT JOIN (SELECT tournamentId, COUNT(*) AS numAddons " .
        "                 FROM poker_result " .
        "                 WHERE addonPaid = '" . Constant::FLAG_YES . "' " .
        "                 GROUP BY tournamentId) na ON t.tournamentId = na.tournamentId " .
        "      WHERE t.tournamentDate BETWEEN :startDate AND :endDate) zz";
        if (isset($limitCount)) {
          $query .= " LIMIT :limitCount";
        }
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':startDate', $params[0], PDO::PARAM_STR);
        $pdoStatement->bindParam(':endDate', $params[1], PDO::PARAM_STR);
        if (isset($limitCount)) {
          $pdoStatement->bindParam(':limitCount', $limitCount, PDO::PARAM_INT);
        }
        break;
      case "registrationList":
        $query =
        "SELECT u.first_name, u.last_name, r.food, IF(s.seasonFee - f.amount = 0, 'Paid', CONCAT('Owes ',s.seasonFee - f.amount)) AS 'fee status' " .
        "FROM (SELECT t1.tournamentId, t1.tournamentDate " .
        "      FROM poker_tournament t1 INNER JOIN (SELECT tournamentdate, MIN(startTime) startTimeMin, MAX(startTime) startTimeMax " .
        "                                           FROM poker_tournament " .
        "                                           WHERE tournamentdate = :tournamentDate) t2 " .
        "      ON t1.tournamentDate = t2.tournamentDate " .
        "      AND t1.startTime = t2.startTime" . ($params[1] ? "Max" : "Min") . ") t INNER JOIN poker_result r ON t.tournamentId = r.tournamentId " .
        "INNER JOIN poker_user u ON r.playerId = u.id " .
        "INNER JOIN poker_season s ON t.tournamentDate BETWEEN s.seasonStartDate AND s.seasonEndDate " .
        "INNER JOIN (SELECT seasonId, playerId, SUM(amount) AS amount FROM poker_fee GROUP BY seasonId, playerId) f ON s.seasonId = f.seasonId AND u.id = f.playerId " .
        "ORDER BY r.registerOrder";
        if (isset($limitCount)) {
          $query .= " LIMIT :limitCount";
        }
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':tournamentDate', $params[0], PDO::PARAM_STR);
        if (isset($limitCount)) {
          $pdoStatement->bindParam(':limitCount', $limitCount, PDO::PARAM_INT);
        }
        break;
      case "registrationWaitList":
        $query =
        "SELECT u.id, CONCAT(u.first_name, ' ', u.last_name) AS name, u.email, t.maxPlayers, u.active " .
        "FROM poker_user u " .
        "INNER JOIN poker_result r ON u.id = r.playerId " .
        "INNER JOIN poker_tournament t ON r.tournamentId = t.tournamentId AND r.tournamentId = :tournamentId AND r.registerOrder > :registerOrder AND r.registerOrder > t.maxPlayers " .
        "ORDER BY r.registerOrder";
        if (isset($limitCount)) {
          $query .= " LIMIT :limitCount";
        }
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':tournamentId', $params[0], PDO::PARAM_INT);
        $pdoStatement->bindParam(':registerOrder', $params[1], PDO::PARAM_INT);
        if (isset($limitCount)) {
          $pdoStatement->bindParam(':limitCount', $limitCount, PDO::PARAM_INT);
        }
        break;
      case "resultIdMax":
        $query =
        "SELECT MAX(r.tournamentId) AS tournamentId " .
        "FROM poker_tournament t INNER JOIN poker_result r ON t.TournamentId = r.TournamentId " .
        "WHERE tournamentDate <= CURRENT_DATE";
        break;
      case "resultSelectAll":
      case "resultSelectOneByTournamentIdAndPlayerId":
      case "resultSelectRegisteredByTournamentId":
      case "resultSelectAllFinishedByTournamentId":
      case "resultSelectPaidByTournamentId":
      case "resultSelectPaidNotEnteredByTournamentId":
        $query = "SELECT r.tournamentId, r.playerId, CONCAT(u.first_name, ' ' , u.last_name) AS name, u.email, r.statusCode, s.statusName AS status, r.registerOrder, r.buyinPaid, r.rebuyPaid, r.rebuyCount AS rebuy, r.addonPaid AS addon, r.addonFlag, r.place, r.knockedOutBy, CONCAT(u2.first_name, ' ' , u2.last_name) AS 'knocked out by', r.food, u.active, u2.active as knockedOutActive, " .
          "IF(se.seasonFee IS NULL OR f.amount IS NULL, '', IF(se.seasonFee - f.amount = 0, 'Paid', CONCAT('Owes $', (se.seasonFee - f.amount)))) AS feeStatus " .
          "FROM poker_result r INNER JOIN poker_user u ON r.playerId = u.id " .
          "LEFT JOIN poker_user u2 ON r.knockedOutBy = u2.id " .
          "INNER JOIN poker_status_code s ON r.statusCode = s.statusCode " .
          "INNER JOIN poker_tournament t ON r.tournamentId = t.tournamentId " .
          "INNER JOIN poker_season se ON t.tournamentDate BETWEEN se.seasonStartDate AND se.seasonEndDate " .
          "INNER JOIN (SELECT seasonId, playerId, SUM(amount) AS amount FROM poker_fee GROUP BY seasonId, playerId) f ON se.seasonId = f.seasonId AND u.id = f.playerId";
        if ("resultSelectOneByTournamentIdAndPlayerId" == $dataName) {
          $query .=
          " WHERE r.tournamentId = :tournamentId" .
          " AND r.playerId = :playerId";
        } else if ("resultSelectRegisteredByTournamentId" == $dataName) {
          $query .=
          " WHERE r.tournamentId = :tournamentId" .
          " AND r.statusCode IN ('" . Constant::CODE_STATUS_REGISTERED . "', '" . Constant::CODE_STATUS_PAID . "')" .
          " AND r.place = 0" .
          " ORDER BY r.registerOrder";
        } else if ("resultSelectAllFinishedByTournamentId" == $dataName) {
          $query .=
          " WHERE r.tournamentId = :tournamentId" .
          " AND r.place <> 0" .
          " ORDER BY r.place DESC";
        } else if ("resultSelectPaidByTournamentId" == $dataName || "resultSelectPaidNotEnteredByTournamentId" == $dataName) {
          $query .=
          " WHERE r.tournamentId = :tournamentId" .
          " AND r.buyinPaid = '" . Constant::FLAG_YES . "'";
          if ("resultSelectPaidNotEnteredByTournamentId" == $dataName) {
            $query .= " AND place = 0";
          }
          if (isset($params[2])) {
            $query .= $params[2];
          }
          if ("resultSelectPaidByTournamentId" == $dataName || "resultSelectPaidNotEnteredByTournamentId" == $dataName) {
            $query .= " ORDER BY " . $this->buildOrderByName(prefix: "u");
          }
        }
        if (isset($limitCount)) {
          $query .= " LIMIT :limitCount";
        }
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        if ("resultSelectOneByTournamentIdAndPlayerId" == $dataName) {
          $pdoStatement->bindParam(':tournamentId', $params[0], PDO::PARAM_INT);
          $pdoStatement->bindParam(':playerId', $params[1], PDO::PARAM_INT);
        } else if ("resultSelectRegisteredByTournamentId" == $dataName || "resultSelectAllFinishedByTournamentId" == $dataName || "resultSelectPaidByTournamentId" == $dataName || "resultSelectPaidNotEnteredByTournamentId" == $dataName) {
          $pdoStatement->bindParam(':tournamentId', $params[0], PDO::PARAM_INT);
        }
        if (isset($limitCount)) {
          $pdoStatement->bindParam(':limitCount', $limitCount, PDO::PARAM_INT);
        }
        break;
      case "resultSelectAllDuring":
        $query =
        "SELECT MIN(place) AS place " .
        "FROM poker_result " .
        "WHERE tournamentId = :tournamentId " .
        "AND place > 0";
        if (isset($limitCount)) {
          $query .= " LIMIT :limitCount";
        }
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':tournamentId', $params[0], PDO::PARAM_INT);
        if (isset($limitCount)) {
          $pdoStatement->bindParam(':limitCount', $limitCount, PDO::PARAM_INT);
        }
        break;
      case "resultSelectLastEnteredDuring":
        $query =
        "SELECT r.playerId, CONCAT(u.first_name, ' ', u.last_name) AS name, u.active " .
        "FROM poker_result r INNER JOIN poker_user u ON r.playerId = u.id " .
        "WHERE r.tournamentId = :tournamentId " .
        "AND r.place = :place";
        if (isset($limitCount)) {
          $query .= " LIMIT :limitCount";
        }
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':tournamentId', $params[0], PDO::PARAM_INT);
        $pdoStatement->bindParam(':place', $params[1], PDO::PARAM_INT);
        if (isset($limitCount)) {
          $pdoStatement->bindParam(':limitCount', $limitCount, PDO::PARAM_INT);
        }
        break;
      case "resultSelectAllByTournamentId":
        // prize pool used for championship resuls
        if (isset($params[0])) {
          $temp = $params[0];
        } else {
          $temp =
          "((np.NumPlayers * (t.BuyinAmount-(t.BuyinAmount * t.Rake))) + " .
          " (IFNULL(nr.NumRebuys, 0) * (t.RebuyAmount - (t.RebuyAmount * t.Rake))) + " .
          " (IFNULL(na.NumAddons, 0) * (t.AddonAmount - (t.AddonAmount * t.Rake))))";
        }
        $query =
        "SELECT r.place, CONCAT(p.first_name, ' ', p.last_name) AS name, r.rebuyCount * t.rebuyAmount AS rebuy, " .
        "       IF(r.AddonPaid = '" . Constant::FLAG_YES . "', t.AddonAmount, 0) AS addon, " .
        "       " . $temp . " * IFNULL(s.Percentage, 0) AS earnings, " .
        "       (np.numPlayers - r.place + 1) * IFNULL(st.typeMultiplier, 1) + IF(r.place BETWEEN 1 AND se.seasonFinalTablePlayers, se.seasonFinalTableBonusPoints, 0) AS 'pts', " .
        "       ko.knockedOutBy AS 'knocked out by', p.active, ko.active AS knockOutActive " .
        "FROM poker_result r INNER JOIN poker_user p ON r.playerId = p.id " .
        "INNER JOIN poker_tournament t ON r.tournamentId = t.tournamentId " .
        "INNER JOIN poker_season se ON t.tournamentDate BETWEEN se.seasonStartDate AND se.seasonEndDate " .
        "LEFT JOIN poker_special_type st ON t.specialTypeId = st.typeId " .
        "INNER JOIN poker_location l ON t.locationId = l.locationId " .
        "INNER JOIN poker_limit_type lt ON t.limitTypeId = lt.limitTypeId " .
        "INNER JOIN poker_game_type gt ON t.gameTypeId = gt.gameTypeId " .
        "LEFT JOIN " .
        "    (SELECT s1.payoutId, s1.place, s1.percentage " .
        "     FROM (SELECT p.payoutId " .
        "           FROM (SELECT r.tournamentId, COUNT(*) AS numPlayers " .
        "                 FROM poker_result r " .
        "                 WHERE r.tournamentId = :tournamentId1 " .
        " AND r.place > 0 AND r.statusCode IN ('" . Constant::CODE_STATUS_REGISTERED . "','" . Constant::CODE_STATUS_FINISHED . "')) np " .
        "           INNER JOIN poker_tournament t ON np.tournamentId = t.tournamentId " .
        "           INNER JOIN poker_group_payout gp ON t.GroupId = gp.GroupId " .
        "           INNER JOIN poker_payout p ON gp.PayoutId = p.PayoutId AND np.numPlayers BETWEEN p.minPlayers AND p.maxPlayers) a " .
        "     INNER JOIN poker_structure s1 ON a.payoutId = s1.payoutId) s ON r.place = s.place " .
        "INNER JOIN (SELECT r1.tournamentId, COUNT(*) AS numPlayers " .
        "            FROM poker_result r1 " .
        "            WHERE r1.place > 0 " .
        "            GROUP BY r1.tournamentId) np ON r.tournamentId = np.tournamentId " .
        "LEFT JOIN (SELECT r2.tournamentId, SUM(r2.rebuyCount) AS numRebuys " .
        "           FROM poker_result r2 " .
        "           WHERE r2.rebuyCount > 0 GROUP BY r2.tournamentId) nr ON r.tournamentId = nr.tournamentId " .
        "LEFT JOIN (SELECT r3.tournamentId, r3.playerId, CONCAT(p1.first_name, ' ', p1.last_name) AS knockedOutBy, p1.active " .
        "           FROM poker_result r3 INNER JOIN poker_user p1 ON r3.knockedOutBy = p1.id) ko ON r.tournamentId = ko.tournamentId AND r.playerId = ko.playerId " .
        "LEFT JOIN (SELECT tournamentId, COUNT(addonPaid) AS numAddons " .
        "           FROM poker_result WHERE addonPaid = '" . Constant::FLAG_YES . "' GROUP BY tournamentId) na ON r.tournamentId = na.tournamentId " .
        "WHERE r.tournamentId = :tournamentId2 " .
        "AND r.place > 0 " .
        "ORDER BY t.tournamentDate DESC, t.startTime DESC, r.place";
        if (isset($limitCount)) {
          $query .= " LIMIT :limitCount";
        }
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':tournamentId1', $params[1], PDO::PARAM_INT);
        $pdoStatement->bindParam(':tournamentId2', $params[1], PDO::PARAM_INT);
        if (isset($limitCount)) {
          $pdoStatement->bindParam(':limitCount', $limitCount, PDO::PARAM_INT);
        }
        break;
      case "resultAllOrderedPoints":
        $query =
        "SELECT CONCAT(u.first_name, ' ', u.last_name) AS name, " .
        "       SUM((np.numPlayers - r.place + 1) * IFNULL(st.typeMultiplier, 1) + IF(r.place BETWEEN 1 AND se.seasonFinalTablePlayers, se.seasonFinalTableBonusPoints, 0)) AS pts, " .
        "       SUM((np.numPlayers - r.place + 1) * IFNULL(st.typeMultiplier, 1) + IF(r.place BETWEEN 1 AND se.seasonFinalTablePlayers, se.seasonFinalTableBonusPoints, 0)) / nt.numTourneys AS avg, " .
        "       nt.numTourneys AS tourneys, u.active " .
        "FROM poker_user u " . "INNER JOIN poker_result r ON u.id = r.playerId " .
        "INNER JOIN poker_tournament t on r.tournamentId = t.tournamentId ";
        if (isset($params[0]) && isset($params[1])) {
          $query .= "      AND t.tournamentDate BETWEEN :startDate1 AND :endDate1 ";
        }
        $query .=
        "INNER JOIN poker_season se ON t.tournamentDate BETWEEN se.seasonStartDate AND se.seasonEndDate " .
        "LEFT JOIN poker_special_type st ON t.specialTypeId = st.typeId" .
        " INNER JOIN (SELECT r1.playerId, COUNT(*) AS numTourneys FROM poker_result r1 INNER JOIN poker_tournament t1 ON r1.tournamentId = t1.tournamentId AND r1.place > 0";
        if (isset($params[0]) && isset($params[1])) {
          $query .= "      AND t1.tournamentDate BETWEEN :startDate2 AND :endDate2 ";
        }
        $query .=
        " LEFT JOIN poker_special_type st ON t1.specialTypeId = st.typeId AND (st.typeDescription IS NULL OR st.typeDescription <> '" . Constant::DESCRIPTION_CHAMPIONSHIP . "')" .
        " GROUP BY r1.playerId) nt ON r.playerId = nt.playerId " .
        "INNER JOIN (SELECT tournamentId, COUNT(*) AS numPlayers " .
        "            FROM poker_result " .
        "            WHERE place > 0 " .
        "            GROUP BY tournamentId) np ON r.tournamentId = np.tournamentId " .
        "GROUP BY r.playerId " .
        "ORDER BY pts DESC, " . $this->buildOrderByName(prefix: NULL);
        if (isset($limitCount)) {
          $query .= " LIMIT :limitCount";
        }
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        if (isset($params[0]) && isset($params[1])) {
          $pdoStatement->bindParam(':startDate1', $params[0], PDO::PARAM_STR);
          $pdoStatement->bindParam(':endDate1', $params[1], PDO::PARAM_STR);
          $pdoStatement->bindParam(':startDate2', $params[0], PDO::PARAM_STR);
          $pdoStatement->bindParam(':endDate2', $params[1], PDO::PARAM_STR);
        }
        if (isset($limitCount)) {
          $pdoStatement->bindParam(':limitCount', $limitCount, PDO::PARAM_INT);
        }
        break;
      case "resultAllOrderedEarnings":
        $query =
        "SELECT name, SUM(totalEarnings) AS earnings, SUM(totalEarnings) / numTourneys AS avg, MAX(maxEarnings) AS max, numTourneys AS tourneys, active " .
        "FROM (SELECT id, name, SUM(earnings) AS totalEarnings, MAX(earnings) AS maxEarnings, numTourneys, active " .
        "      FROM (SELECT p.id, CONCAT(p.first_name, ' ', p.last_name) AS name, " .
        "                   ((np.numPlayers * (t.buyinAmount - (t.buyinAmount * t.rake))) + " .
        "                    (IFNULL(nr.numRebuys, 0) * (t.rebuyAmount - (t.rebuyAmount * t.rake))) + " .
        "                    (IFNULL(na.numAddons, 0) * (t.addonAmount - (t.addonAmount * t.rake)))) * IFNULL(s.percentage, 0) AS earnings, nt.numTourneys, p.active " .
        "            FROM poker_result r INNER JOIN poker_user p ON r.playerId = p.id " .
        "            INNER JOIN poker_tournament t ON r.tournamentId = t.tournamentId ";
        if (isset($params[0]) && isset($params[1])) {
          $query .= "            AND t.tournamentDate BETWEEN :startDate1 AND :endDate1 ";
        }
        $query .=
        "            INNER JOIN (SELECT r1.playerId, COUNT(*) AS numTourneys " .
        "                        FROM poker_result r1 INNER JOIN poker_tournament t1 ON r1.tournamentId = t1.tournamentId AND r1.place > 0 ";
        if (isset($params[0]) && isset($params[1])) {
          $query .= "                       AND t1.tournamentDate BETWEEN :startDate2 AND :endDate2 ";
        }
        $query .=
        "                        GROUP BY r1.playerId) nt ON r.playerId = nt.playerId " .
        "            INNER JOIN (SELECT r2.tournamentId, COUNT(*) AS numPlayers " .
        "                        FROM poker_result r2 " .
        "                        WHERE r2.place > 0 " .
        "                        GROUP BY r2.tournamentId) np ON r.tournamentId = np.tournamentId " .
        "            LEFT JOIN (SELECT r3.tournamentId, SUM(r3.rebuyCount) AS numRebuys " .
        "                       FROM poker_result r3 " .
        "                       WHERE r3.place > 0 " .
        "                       AND r3.rebuyCount > 0 " .
        "                       GROUP BY r3.tournamentId) nr ON r.tournamentId = nr.tournamentId " .
        "            LEFT JOIN (SELECT tournamentId, COUNT(addonPaid) AS numaddons " .
        "                       FROM poker_result " .
        "                       WHERE addonPaid = '" . Constant::FLAG_YES . "' " .
        "                       GROUP BY tournamentId) na ON r.tournamentId = na.tournamentId " .
        "            LEFT JOIN (SELECT a.tournamentId, s1.payoutId, s1.place, s1.percentage " .
        "                       FROM (SELECT np.tournamentId, p.payoutId " .
        "                             FROM (SELECT r.tournamentId, COUNT(*) AS numPlayers " .
        "                                   FROM poker_result r " .
        "                                   WHERE r.place > 0 " .
        "                                   AND r.statusCode IN ('" . Constant::CODE_STATUS_REGISTERED . "','" . Constant::CODE_STATUS_FINISHED . "') " .
        "                                   GROUP BY r.tournamentId) np " .
        "                             INNER JOIN poker_tournament t on np.tournamentId = t.tournamentId " .
        "                             INNER JOIN poker_group_payout gp ON t.GroupId = gp.GroupId " .
        "                             INNER JOIN poker_payout p ON gp.PayoutId = p.PayoutId AND np.numPlayers BETWEEN p.minPlayers AND p.maxPlayers) a " .
        "                       INNER JOIN poker_structure s1 ON a.payoutId = s1.payoutId) s ON r.tournamentId = s.tournamentId AND r.place = s.place " .
        "                       WHERE r.place > 0) y " .
        "            GROUP BY id " .
        "            UNION " .
        "            SELECT xx.id, xx.name, SUM(xx.earnings) AS totalEarnings, MAX(xx.earnings) AS maxEarnings, 0, xx.active " .
        "            FROM (SELECT YEAR(t.tournamentDate) AS yr, p.id, CONCAT(p.first_name, ' ', p.last_name) AS name, p.active, " .
        "                         (SELECT SUM(total) - IF(Yr = 2008, 150, IF(Yr = 2007, -291, IF(Yr = 2006, -824, 0))) AS 'total pool' " .
        "                          FROM (SELECT YEAR(t2.tournamentDate) AS Yr, t2.tournamentId AS id, IF(b.play IS NULL, 0, CONCAT(b.play, '+', IFNULL(nr.numRebuys, 0), 'r', '+', IFNULL(na.numAddons, 0), 'a')) AS play, " .
        "                                       ((t2.buyinAmount * t2.rake) * play) + ((t2.rebuyAmount * t2.rake) * IFNULL(nr.numRebuys, 0)) + ((t2.addonAmount * t2.rake) * IFNULL(na.numaddons, 0)) AS total " .
        "                                FROM poker_tournament t2 " .
        "                                LEFT JOIN (SELECT tournamentId, COUNT(*) AS play " .
        "                                           FROM poker_result " .
        "                                           WHERE buyinPaid = '" . Constant::FLAG_YES . "' " .
        "                                           AND place > 0 " .
        "                                           GROUP BY tournamentId) b ON t2.tournamentId = b.tournamentId";
        if (isset($params[0]) && isset($params[1])) {
          $query .= "                                AND t2.tournamentDate BETWEEN :startDate3 AND :endDate3 ";
        }
        $query .=
        "                               LEFT JOIN (SELECT r.tournamentId, SUM(r.rebuyCount) AS numRebuys " .
        "                                          FROM poker_result r " .
        "                                          WHERE r.rebuyPaid = '" . Constant::FLAG_YES . "' " .
        "                                          AND r.rebuyCount > 0 " .
        "                                          GROUP BY r.tournamentId) nr ON t2.tournamentId = nr.tournamentId " .
        "                               LEFT JOIN (SELECT r.tournamentId, COUNT(*) AS numaddons " .
        "                                          FROM poker_result r " .
        "                                          WHERE r.addonPaid = '" . Constant::FLAG_YES . "' " .
        "                                          GROUP BY r.tournamentId) na ON t2.tournamentId = na.tournamentId) zz " .
        "                         WHERE zz.yr = YEAR(t.tournamentDate) " .
        "                         GROUP BY zz.yr) * IF(s.percentage IS NULl, 0, s.percentage) AS earnings " .
        "                 FROM poker_result r " .
        "                 INNER JOIN poker_user p ON r.playerId = p.id " .
        "                 INNER JOIN poker_tournament t ON r.tournamentId = t.tournamentId";
        if (isset($params[0]) && isset($params[1])) {
          $query .= "                  AND t.tournamentDate BETWEEN :startDate4 AND :endDate4 ";
        }
        $query .=
        "                 LEFT JOIN poker_special_type st ON t.specialTypeId = st.typeId" .
        "                 LEFT JOIN (SELECT a.tournamentId, s1.payoutId, s1.place, s1.percentage " .
        "                            FROM (SELECT np.tournamentId, p.payoutId " .
        "                                  FROM (SELECT r.tournamentId, COUNT(*) AS numPlayers " .
        "                                        FROM poker_result r " .
        "                                        WHERE r.place > 0 " .
        "                                        AND r.statusCode IN ('" . Constant::CODE_STATUS_REGISTERED . "','" . Constant::CODE_STATUS_FINISHED . "') " .
        "                                        GROUP BY r.tournamentId) np " .
        "                                  INNER JOIN poker_tournament t on np.tournamentId = t.tournamentId";
        if (isset($params[0]) && isset($params[1])) {
          $query .= "                                   AND t.tournamentDate BETWEEN :startDate5 AND :endDate5 ";
        }
        $query .=
        "                                  INNER JOIN poker_group_payout gp ON t.GroupId = gp.GroupId " .
        "                                  INNER JOIN poker_payout p ON gp.PayoutId = p.PayoutId AND np.numPlayers BETWEEN p.minPlayers AND p.maxPlayers) a " .
        "                            INNER JOIN poker_structure s1 ON a.payoutId = s1.payoutId) s ON r.tournamentId = s.tournamentId AND r.place = s.place " .
        "                 WHERE r.place > 0 " .
        "                 AND st.typeDescription = '" . Constant::DESCRIPTION_CHAMPIONSHIP . "'" .
        "                 GROUP BY id, yr) xx " .
        "            GROUP BY xx.id, xx.name) cc " .
        "GROUP BY id, name " .
        "ORDER BY earnings DESC";
        if (isset($limitCount)) {
          $query .= " LIMIT :limitCount";
        }
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        if (isset($params[0]) && isset($params[1])) {
          $pdoStatement->bindParam(':startDate1', $params[0], PDO::PARAM_STR);
          $pdoStatement->bindParam(':endDate1', $params[1], PDO::PARAM_STR);
          $pdoStatement->bindParam(':startDate2', $params[0], PDO::PARAM_STR);
          $pdoStatement->bindParam(':endDate2', $params[1], PDO::PARAM_STR);
          $pdoStatement->bindParam(':startDate3', $params[0], PDO::PARAM_STR);
          $pdoStatement->bindParam(':endDate3', $params[1], PDO::PARAM_STR);
          $pdoStatement->bindParam(':startDate4', $params[0], PDO::PARAM_STR);
          $pdoStatement->bindParam(':endDate4', $params[1], PDO::PARAM_STR);
          $pdoStatement->bindParam(':startDate5', $params[0], PDO::PARAM_STR);
          $pdoStatement->bindParam(':endDate5', $params[1], PDO::PARAM_STR);
        }
        if (isset($limitCount)) {
          $pdoStatement->bindParam(':limitCount', $limitCount, PDO::PARAM_INT);
        }
        break;
      case "resultAllOrderedKnockouts":
      case "resultAllOrderedKnockoutsStats":
        $query =
        "SELECT k.id, name, k.knockouts AS kOs, k.knockouts / nt.numTourneys AS avgKo, b.bestKnockout AS bestKo, nt.numTourneys AS tourneys, k.active " .
        "FROM (SELECT u3.id, CONCAT(u3.first_name, ' ', u3.last_name) AS name, u.active, COUNT(*) AS knockouts " .
        "      FROM poker_tournament t " .
        "      INNER JOIN poker_limit_type lt ON t.limitTypeId = lt.limitTypeId " .
        "      INNER JOIN poker_game_type gt ON t.gameTypeId = gt.gameTypeId " .
        "      INNER JOIN poker_location l ON t.locationId = l.locationId " .
        "      INNER JOIN poker_user u ON l.playerId = u.id " .
        "      INNER JOIN poker_result r ON t.tournamentId = r.tournamentId " .
        "      INNER JOIN poker_user u2 ON r.playerId = u2.id " .
        "      INNER JOIN poker_status_code sc ON r.statusCode = sc.statusCode " .
        "      INNER JOIN poker_user u3 ON r.knockedOutBy = u3.id " .
        "      WHERE r.place > 0 ";
        if (isset($params[0]) && isset($params[1])) {
          $query .= "      AND t.tournamentDate BETWEEN :startDate1 AND :endDate1 ";
        }
        $query .=
        "      GROUP BY r.knockedOutBy) k " .
        "INNER JOIN (SELECT r.playerId, COUNT(*) AS numTourneys " .
        "            FROM poker_tournament t " .
        "            INNER JOIN poker_limit_type lt ON t.limitTypeId = lt.limitTypeId " .
        "            INNER JOIN poker_game_type gt ON t.gameTypeId = gt.gameTypeId " .
        "            INNER JOIN poker_location l ON t.locationId = l.locationId " .
        "            INNER JOIN poker_user u ON l.playerId = u.id " .
        "            INNER JOIN poker_result r ON t.tournamentId = r.tournamentId " .
        "            INNER JOIN poker_user u2 ON r.playerId = u2.id " .
        "            INNER JOIN poker_status_code sc ON r.statusCode = sc.statusCode " .
        "            LEFT JOIN poker_user u3 ON r.knockedOutBy = u3.id " .
        "            WHERE r.place > 0 ";
        if (isset($params[0]) && isset($params[1])) {
          $query .= "            AND t.tournamentDate BETWEEN :startDate2 AND :endDate2 ";
        }
        $query .=
        "            GROUP BY r.playerId) nt ON k.id = nt.playerId " .
        "LEFT JOIN (SELECT id, MAX(knockouts) AS BestKnockout " .
        "           FROM (SELECT u3.id, t.tournamentId, r.knockedOutBy, COUNT(*) AS knockouts " .
        "                 FROM poker_tournament t INNER JOIN poker_limit_type lt ON t.LimitTypeId = lt.limitTypeId " .
        "                 INNER JOIN poker_game_type gt ON t.gameTypeId = gt.gameTypeId " .
        "                 INNER JOIN poker_location l ON t.locationId = l.locationId " .
        "                 INNER JOIN poker_user u ON l.playerId = u.id " .
        "                 INNER JOIN poker_result r ON t.tournamentId = r.tournamentId " .
        "                 INNER JOIN poker_user u2 ON r.playerId = u2.id " .
        "                 INNER JOIN poker_status_code sc ON r.statusCode = sc.statusCode " .
        "                 INNER JOIN poker_user u3 ON r.knockedOutBy = u3.id " .
        "                 WHERE r.place > 0 " .
        "                 AND r.knockedOutBy IS NOT NULL ";
        if (isset($params[0]) && isset($params[1])) {
          $query .= "                 AND t.tournamentDate BETWEEN :startDate3 AND :endDate3 ";
        }
        $query .=
        "                 GROUP BY t.tournamentId, r.knockedOutBy) z " .
        "           GROUP BY z.Id) b ON nt.playerId = b.id " .
        "WHERE b.id IN (SELECT DISTINCT playerId " .
        "               FROM poker_result " .
        "               WHERE statusCode = '" . Constant::CODE_STATUS_FINISHED . "') " .
        "ORDER BY k.knockouts DESC, nt.NumTourneys";
        if (isset($limitCount)) {
          $query .= " LIMIT :limitCount";
        }
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        if (isset($params[0]) && isset($params[1])) {
          $pdoStatement->bindParam(':startDate1', $params[0], PDO::PARAM_STR);
          $pdoStatement->bindParam(':endDate1', $params[1], PDO::PARAM_STR);
          $pdoStatement->bindParam(':startDate2', $params[0], PDO::PARAM_STR);
          $pdoStatement->bindParam(':endDate2', $params[1], PDO::PARAM_STR);
          $pdoStatement->bindParam(':startDate3', $params[0], PDO::PARAM_STR);
          $pdoStatement->bindParam(':endDate3', $params[1], PDO::PARAM_STR);
        }
        if (isset($limitCount)) {
          $pdoStatement->bindParam(':limitCount', $limitCount, PDO::PARAM_INT);
        }
        break;
      case "resultAllOrderedSummary":
      case "resultAllOrderedSummaryStats":
        $query = "SELECT ";
        if ("resultAllOrderedSummaryStats" == $dataName) {
          $query .= "id, ";
        }
        $query .=
        "name, d.tourneys AS '#', IFNULL(d.Points, 0) AS pts, d.Points / d.numTourneys AS AvgPoints, d.FTs AS 'count', d.pctFTs AS '%', d.avgPlace AS 'avg', d.high AS 'best', d.low AS 'worst', " .
        "-(IF(d.numTourneys IS NULL, 0, d.numTourneys * d.buyinAmount)) AS buyins, -(IFNULL(d.rebuys, 0)) AS rebuys, " .
        "-(IFNULL(d.addons, 0)) AS addons, -(IF(d.numTourneys IS NULL, 0, d.numTourneys * d.buyinAmount)) + -(IFNULL(d.rebuys, 0)) + -(IFNULL(d.addons, 0)) AS 'total', " .
        "d.earnings,  " .
        "d.earnings - IF(d.numTourneys IS NULL, 0, d.numTourneys * d.buyinAmount) - IFNULL(d.rebuys, 0) - IFNULL(d.addons, 0) AS 'net(+/-)', " .
        "d.earnings / d.numTourneys AS '$ / trny', " .
        "(d.earnings - IF(d.numTourneys IS NULL, 0, d.numTourneys * d.buyinAmount) - IFNULL(d.rebuys, 0) - IFNULL(d.addons, 0)) / d.numTourneys AS 'Net / Trny', " .
        "active " .
        "FROM (SELECT a.Id, a.name, a.active, a.Tourneys, a.FTs, a.PctFTs, a.AvgPlace, a.Low, a.High, IFNULL(b.Earnings, 0) AS Earnings, a.NumTourneys, " .
        "             e.PlayerId, e.Place, e.NumPlayers, e.Points, e.Rebuys, e.Addons, e.NumRebuys, e.BuyinAmount" .
        "      FROM (SELECT p.Id, CONCAT(p.first_name, ' ', p.last_name) AS name, IFNULL(nt.NumTourneys, 0) AS Tourneys, IFNULL(nft.NumFinalTables, 0) AS FTs, " .
        "                   IF(nt.NumTourneys IS NULL, 0, IFNULL(nft.NumFinalTables, 0) / nt.NumTourneys) AS PctFTs, " .
        "                   IF(nt.NumTourneys IS NULL, 0, IFNULL(nt.TotalPlaces, 0) / nt.NumTourneys) AS AvgPlace, " .
        "                   IFNULL(nt.MaxPlace, 0) AS Low, IFNULL(nt.MinPlace, 0) AS High, nt.NumTourneys, p.active " .
        "            FROM poker_user p " .
        "            LEFT JOIN (SELECT r1.PlayerId, COUNT(*) AS NumTourneys, SUM(r1.Place) AS TotalPlaces, MAX(r1.Place) AS MaxPlace, MIN(r1.Place) AS MinPlace " .
        "                       FROM poker_result r1 " .
        "                       INNER JOIN poker_tournament t1 ON r1.TournamentId = t1.TournamentId";
        if (isset($params[0]) && isset($params[1])) {
          $query .= "                       AND t1.tournamentDate BETWEEN :startDate1 AND :endDate1 ";
        }
        $query .=
        "                       WHERE r1.Place > 0 " .
        "                       GROUP BY r1.PlayerId) nt ON p.Id = nt.PlayerId " .
        "            LEFT JOIN (SELECT r2.PlayerId, COUNT(*) AS NumFinalTables " .
        "                       FROM poker_result r2 " .
        "                       INNER JOIN poker_tournament t2 ON r2.TournamentId = t2.TournamentId";
        if (isset($params[0]) && isset($params[1])) {
          $query .= "                       AND t2.tournamentDate BETWEEN :startDate2 AND :endDate2 ";
        }
        $query .=
        "                       INNER JOIN poker_season se ON t2.tournamentDate BETWEEN se.seasonStartDate AND se.seasonEndDate " .
        "                       WHERE r2.Place BETWEEN 1 AND se.seasonFinalTablePlayers " .
        "                       GROUP BY r2.PlayerId) nft ON p.Id = nft.PlayerId) a " .
        "            LEFT JOIN (SELECT id, name, SUM(TotalEarnings) AS Earnings, MAX(MaxEarnings) AS MaxEarnings, numTourneys " .
        "                       FROM (SELECT Id, name, SUM(Earnings) AS TotalEarnings, MAX(Earnings) AS MaxEarnings, NumTourneys " .
        "                             FROM (SELECT p.Id, CONCAT(p.first_name, ' ', p.last_name) AS name, " .
        "                                         ((np.NumPlayers * (t.BuyinAmount - (t.BuyinAmount * t.rake))) + " .
        "                                          (IFNULL(nr.NumRebuys, 0) * (t.RebuyAmount - (t.RebuyAmount * t.rake))) + " .
        "                                          (IFNULL(na.NumAddons, 0) * (t.AddonAmount - (t.AddonAmount * t.rake)))) * IFNULL(s.Percentage, 0) AS Earnings, nt.NumTourneys " .
        "                                   FROM poker_result r INNER JOIN poker_user p ON r.PlayerId = p.Id " .
        "                                   INNER JOIN poker_tournament t ON r.TournamentId = t.TournamentId";
        if (isset($params[0]) && isset($params[1])) {
          $query .= "                                   AND t.tournamentDate BETWEEN :startDate3 AND :endDate3 ";
        }
        $query .=
        "                                   INNER JOIN (SELECT r1.PlayerId, COUNT(*) AS NumTourneys " .
        "                                               FROM poker_result r1 " .
        "                                               INNER JOIN poker_tournament t1 ON r1.TournamentId = t1.TournamentId AND r1.Place > 0";
        if (isset($params[0]) && isset($params[1])) {
          $query .= "                                               AND t1.tournamentDate BETWEEN :startDate4 AND :endDate4 ";
        }
        $queryAndBindParams = $this->buildChampionship($params);
        $query .=
        "                                               GROUP BY r1.PlayerId) nt ON r.PlayerId = nt.PlayerId " .
        "                                   INNER JOIN (SELECT r2.TournamentId, COUNT(*) AS NumPlayers " .
        "                                               FROM poker_result r2 " .
        "                                               WHERE r2.Place > 0 " .
        "                                               GROUP BY r2.TournamentId) np ON r.TournamentId = np.TournamentId " .
        "                                   LEFT JOIN (SELECT r3.TournamentId, SUM(r3.rebuyCount) AS NumRebuys " .
        "                                              FROM poker_result r3 " .
        "                                              WHERE r3.Place > 0 " .
        "                                              AND r3.RebuyCount > 0 GROUP BY r3.TournamentId) nr ON r.TournamentId = nr.TournamentId " .
        "                                   LEFT JOIN (SELECT TournamentId, COUNT(AddonPaid) AS NumAddons " .
        "                                              FROM poker_result " .
        "                                              WHERE AddonPaid = '" . Constant::FLAG_YES . "' " .
        "                                              GROUP BY TournamentId) na ON r.TournamentId = na.TournamentId " .
        "                                   LEFT JOIN (SELECT a.tournamentId, s1.payoutId, s1.place, s1.percentage " .
        "                                              FROM (SELECT np.tournamentId, p.payoutId " .
        "                                                    FROM (SELECT r.tournamentId, COUNT(*) AS numPlayers " .
        "                                                          FROM poker_result r " .
        "                                                          WHERE r.place > 0 " .
        "                                                          AND r.statusCode IN ('" . Constant::CODE_STATUS_REGISTERED . "','" . Constant::CODE_STATUS_FINISHED . "') " .
        "                                                          GROUP BY r.tournamentId) np INNER JOIN poker_tournament t on np.tournamentId = t.tournamentId " .
        "                                                    INNER JOIN poker_group_payout gp ON t.GroupId = gp.GroupId " .
        "                                                    INNER JOIN poker_payout p ON gp.PayoutId = p.PayoutId AND np.numPlayers BETWEEN p.minPlayers AND p.maxPlayers) a " .
        "                                              INNER JOIN poker_structure s1 ON a.payoutId = s1.payoutId) s ON r.tournamentId = s.tournamentId AND r.place = s.place " .
        "                                   WHERE r.Place > 0) y " .
        "                             GROUP BY id " .
        "                             UNION " .
        "                             SELECT xx.id, xx.name, SUM(xx.earnings) AS TotalEarnings, MAX(xx.earnings) AS MaxEarnings, 0 " .
        "                             FROM (" . $queryAndBindParams[0] .
        "                                   GROUP BY id, yr) xx " .
        "                             GROUP BY xx.id, xx.name) cc " .
        "                       GROUP BY id, name) b ON a.Id = b.Id " .
        "LEFT JOIN (SELECT c.PlayerId, c.Place, c.NumPlayers, SUM((c.numPlayers - c.place + 1) * IFNULL(c.typeMultiplier, 1)) + IF(c.place BETWEEN 1 AND c.seasonFinalTablePlayers, c.seasonFinalTableBonusPoints, 0) AS Points, " .
        "                                                     SUM(IFNULL(c.NumRebuys, 0) * c.RebuyAmount) AS Rebuys, " .
        "                                                     SUM(IFNULL(c.NumAddons, 0) * c.AddonAmount) AS Addons, " .
        "                                                     c.NumRebuys, c.BuyinAmount " .
        "           FROM (SELECT a.TournamentId, a.tournamentDesc, a.PlayerId, a.Place, a.NumPlayers, a.NumRebuys, a.BuyinAmount, a.RebuyAmount, a.AddonAmount, a.NumAddons, a.typeDescription, a.typeMultiplier, a.seasonFinalTablePlayers, a.seasonFinalTableBonusPoints " .
        "                 FROM (SELECT r.TournamentId, t.tournamentDesc, r.PlayerId, r.Place, np.NumPlayers, nr.NumRebuys, t.BuyinAmount, t.RebuyAmount, t.AddonAmount, na.NumAddons, st.typeDescription, st.typeMultiplier, se.seasonFinalTablePlayers, se.seasonFinalTableBonusPoints " .
        "                       FROM poker_result r INNER JOIN poker_tournament t ON r.TournamentId = t.TournamentId";
        if (isset($params[0]) && isset($params[1])) {
          $query .= "                       AND t.tournamentDate BETWEEN :startDate5 AND :endDate5 ";
        }
        $query .=
        "                       AND r.place > 0 " .
        "                       INNER JOIN poker_season se ON t.tournamentDate BETWEEN se.seasonStartDate AND se.seasonEndDate " .
        "                       LEFT JOIN poker_special_type st ON t.specialTypeId = st.typeId" .
        "                       INNER JOIN (SELECT r3.TournamentId, COUNT(*) AS NumPlayers " .
        "                                   FROM poker_result r3 INNER JOIN poker_tournament t3 ON r3.TournamentId = t3.TournamentId";
        if (isset($params[0]) && isset($params[1])) {
          $query .= "                                   AND t3.tournamentDate BETWEEN :startDate6 AND :endDate6 ";
        }
        $query .=
        "                                   WHERE r3.Place > 0 " .
        "                                   GROUP BY r3.TournamentId) np ON r.TournamentId = np.TournamentId " .
        "                       LEFT JOIN (SELECT r4.TournamentId, r4.PlayerId, SUM(r4.rebuyCount) AS NumRebuys " .
        "                                  FROM poker_result r4 " .
        "                                  INNER JOIN poker_tournament t4 ON r4.TournamentId = t4.TournamentId";
        if (isset($params[0]) && isset($params[1])) {
          $query .= "                                  AND t4.tournamentDate BETWEEN :startDate7 AND :endDate7 ";
        }
        $query .=
        "                                  WHERE r4.Place > 0 " .
        "                                  AND r4.RebuyCount > 0 " .
        "                                  GROUP BY r4.TournamentId, r4.PlayerId) nr ON r.TournamentId = nr.TournamentId AND r.PlayerId = nr.PlayerId " .
        "                       LEFT JOIN (SELECT TournamentId, PlayerId, COUNT(AddonPaid) AS NumAddons " .
        "                                  FROM poker_result r7 WHERE AddonPaid = '" . Constant::FLAG_YES . "' GROUP BY TournamentId, PlayerId) na ON r.TournamentId = na.TournamentId AND r.PlayerId = na.PlayerId) a " .
        "                 ) c " .
        "           GROUP BY c.PlayerId) e ON b.Id = e.PlayerId " .
        "WHERE b.Id IN (SELECT DISTINCT playerId FROM poker_result WHERE statusCode = '" . Constant::CODE_STATUS_FINISHED . "')) d ";
        if ("resultAllOrderedSummary" == $dataName) {
          $query .= "ORDER BY ROUND(d.earnings, 0) DESC";
        }
        if (isset($limitCount)) {
          $query .= " LIMIT :limitCount";
        }
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        if (isset($params[0]) && isset($params[1])) {
          $pdoStatement->bindParam(':startDate1', $params[0], PDO::PARAM_STR);
          $pdoStatement->bindParam(':endDate1', $params[1], PDO::PARAM_STR);
          $pdoStatement->bindParam(':startDate2', $params[0], PDO::PARAM_STR);
          $pdoStatement->bindParam(':endDate2', $params[1], PDO::PARAM_STR);
          $pdoStatement->bindParam(':startDate3', $params[0], PDO::PARAM_STR);
          $pdoStatement->bindParam(':endDate3', $params[1], PDO::PARAM_STR);
          $pdoStatement->bindParam(':startDate4', $params[0], PDO::PARAM_STR);
          $pdoStatement->bindParam(':endDate4', $params[1], PDO::PARAM_STR);
          $pdoStatement->bindParam(':startDate5', $params[0], PDO::PARAM_STR);
          $pdoStatement->bindParam(':endDate5', $params[1], PDO::PARAM_STR);
          $pdoStatement->bindParam(':startDate6', $params[0], PDO::PARAM_STR);
          $pdoStatement->bindParam(':endDate6', $params[1], PDO::PARAM_STR);
          $pdoStatement->bindParam(':startDate7', $params[0], PDO::PARAM_STR);
          $pdoStatement->bindParam(':endDate7', $params[1], PDO::PARAM_STR);
        }
        if (isset($queryAndBindParams[1])) {
          foreach ($queryAndBindParams[1] as $key => $value) {
            $pdoStatement->bindParam($key, $value, PDO::PARAM_STR);
          }
        }
        if (isset($limitCount)) {
          $pdoStatement->bindParam(':limitCount', $limitCount, PDO::PARAM_INT);
        }
        break;
      case "resultPaidUserCount":
        $query =
        "SELECT COUNT(*) AS cnt " .
        "FROM poker_user u INNER JOIN poker_result r ON r.playerId = u.id " .
        "INNER JOIN poker_tournament t ON r.tournamentId = t.tournamentId " .
        "AND t.tournamentDate = (SELECT MAX(t9.tournamentDate) " .
        "                        FROM poker_tournament t9 " .
        "                        INNER JOIN poker_result r9 ON t9.tournamentId = r9.tournamentId AND r9.statusCode = '" . Constant::CODE_STATUS_PAID . "') " .
        "AND t.startTime = (SELECT MIN(t10.startTime) " .
        "                  FROM poker_tournament t10 " .
        "                  INNER JOIN poker_result r10 ON t10.tournamentId = r10.tournamentId AND r10.statusCode = '" . Constant::CODE_STATUS_PAID . "' " .
        "                                              AND t10.tournamentDate = (SELECT MAX(t11.tournamentDate) " .
        "                                                                        FROM poker_tournament t11 " .
        "                                                                        INNER JOIN poker_result r11 ON t11.tournamentId = r11.tournamentId AND r11.statusCode = '" . Constant::CODE_STATUS_PAID . "'))";
        break;
      case "seasonActiveCount":
        $query =
        "SELECT COUNT(*) AS cnt " .
        "FROM poker_season " .
        "WHERE seasonActive = 1";
        break;
      case "seasonDateCheckCount":
        $query =
        "SELECT COUNT(*) AS cnt " .
        "FROM poker_season " .
        "WHERE (:tournamentDate1 BETWEEN seasonStartDate AND seasonEndDate OR :tournamentDate2 BETWEEN seasonStartDate AND seasonEndDate)";
        if (isset($params[2])) {
          $query .= " AND seasonId <> :seasonId";
        }
        if (isset($limitCount)) {
          $query .= " LIMIT :limitCount";
        }
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        if (isset($params[2])) {
          $pdoStatement->bindParam(':tournamentDate1', $params[0], PDO::PARAM_STR);
          $pdoStatement->bindParam(':tournamentDate2', $params[1], PDO::PARAM_STR);
          $pdoStatement->bindParam(':seasonId', $params[2], PDO::PARAM_INT);
        } else {
          $pdoStatement->bindParam(':tournamentDate1', $params[0], PDO::PARAM_STR);
          $pdoStatement->bindParam(':tournamentDate2', $params[1], PDO::PARAM_STR);
        }
        if (isset($limitCount)) {
          $pdoStatement->bindParam(':limitCount', $limitCount, PDO::PARAM_INT);
        }
        break;
      case "seasonStats":
        $query =
        "SELECT SUBSTRING_INDEX(NAME, ' ', 1) AS first_name, SUBSTRING_INDEX(NAME, ' ', -1) AS last_name, d.tourneys AS '#', IFNULL(d.Points, 0) AS pts, d.Points / d.numTourneys AS AvgPoints, d.FTs AS 'count', d.pctFTs AS '%', d.avgPlace AS 'avg', d.high AS 'best', d.low AS 'worst', -(IF(d.numTourneys IS NULL, 0, d.numTourneys * d.buyinAmount)) AS buyins, -IFNULL(d.rebuys, 0) AS rebuys, -IFNULL(d.addons, 0) AS addons, -(IF(d.numTourneys IS NULL, 0, d.numTourneys * d.buyinAmount)) + -IFNULL(d.rebuys, 0) + -IFNULL(d.addons, 0) AS 'total', d.earnings, d.earnings - IF(d.numTourneys IS NULL, 0, d.numTourneys * d.buyinAmount) - IFNULL(d.rebuys, 0) - IFNULL(d.addons, 0) AS 'net(+/-)', d.kos AS 'KOs', d.kos / d.numTourneys AS 'Avg KO', d.koMax AS 'Most KO', IFNULL(d.wins, 0) AS wins, IFNULL(d.wins, 0) / d.numTourneys AS AvgWins " .
        "FROM (SELECT a.Id, a.name, a.active, a.Tourneys, a.FTs, a.PctFTs, a.AvgPlace, a.Low, a.High, IFNULL(b.earnings, 0) AS earnings, a.NumTourneys, " .
        "             e.PlayerId, e.Place, e.NumPlayers, e.Points, e.Rebuys, e.Addons, e.NumRebuys, e.BuyinAmount, km.kos, km.koMax, w.wins " .
        "      FROM (SELECT p.Id, CONCAT(p.first_name, ' ', p.last_name) AS name, IFNULL(nt.NumTourneys, 0) AS Tourneys, IFNULL(nft.NumFinalTables, 0) AS FTs, IF(nt.NumTourneys IS NULL, 0, IFNULL(nft.NumFinalTables, 0) / nt.NumTourneys) AS PctFTs, IF(nt.NumTourneys IS NULL, 0, IFNULL(nt.TotalPlaces, 0) / nt.NumTourneys) AS AvgPlace, IFNULL(nt.MaxPlace, 0) AS Low, IFNULL(nt.MinPlace, 0) AS High, IFNULL(nt.NumTourneys, 0) AS NumTourneys, p.active " .
        "            FROM poker_user p LEFT JOIN (SELECT r1.PlayerId, COUNT(*) AS NumTourneys, SUM(r1.Place) AS TotalPlaces, MAX(r1.Place) AS MaxPlace, MIN(r1.Place) AS MinPlace " .
        "                                         FROM poker_result r1 INNER JOIN poker_tournament t1 ON r1.TournamentId = t1.TournamentId " .
        "                                         AND t1.tournamentDate BETWEEN :startDate1 AND :endDate1 " .
        "                                         WHERE r1.Place > 0 GROUP BY r1.PlayerId) nt ON p.Id = nt.PlayerId " .
        "            LEFT JOIN (SELECT r2.PlayerId, COUNT(*) AS NumFinalTables " .
        "                       FROM poker_result r2 INNER JOIN poker_tournament t2 ON r2.TournamentId = t2.TournamentId " .
        "                       AND t2.tournamentDate BETWEEN :startDate2 AND :endDate2 " .
        "                       INNER JOIN poker_season se ON t2.tournamentDate BETWEEN se.seasonStartDate AND se.seasonEndDate " .
        "                       WHERE r2.Place BETWEEN 1 AND se.seasonFinalTablePlayers GROUP BY r2.PlayerId) nft ON p.Id = nft.PlayerId) a " .
        "      LEFT JOIN (SELECT id, name, SUM(TotalEarnings) AS Earnings, MAX(MaxEarnings) AS MaxEarnings, numTourneys " .
        "                 FROM (SELECT Id, name, SUM(Earnings) AS TotalEarnings, MAX(Earnings) AS MaxEarnings, NumTourneys " .
        "                       FROM (SELECT p.Id, CONCAT(p.first_name, ' ', p.last_name) AS name, ((np.NumPlayers * (t.BuyinAmount - (t.BuyinAmount * t.rake))) + (IFNULL(nr.NumRebuys, 0) * (t.RebuyAmount - (t.RebuyAmount * t.rake))) + (IFNULL(na.NumAddons, 0) * (t.AddonAmount - (t.AddonAmount * t.Rake)))) * IFNULL(s.Percentage, 0) AS Earnings, nt.NumTourneys " .
        "                             FROM poker_result r INNER JOIN poker_user p ON r.PlayerId = p.Id " .
        "                             INNER JOIN poker_tournament t ON r.TournamentId = t.TournamentId " .
        "                             AND t.tournamentDate BETWEEN :startDate3 AND :endDate3 " .
        "                       INNER JOIN (SELECT r1.PlayerId, COUNT(*) AS NumTourneys " .
        "                                   FROM poker_result r1 INNER JOIN poker_tournament t1 ON r1.TournamentId = t1.TournamentId AND r1.Place > 0 " .
        "                                   AND t1.tournamentDate BETWEEN :startDate4 AND :endDate4 " .
        "                                   GROUP BY r1.PlayerId) nt ON r.PlayerId = nt.PlayerId " .
        "                       INNER JOIN (SELECT r2.TournamentId, COUNT(*) AS NumPlayers FROM poker_result r2 WHERE r2.Place > 0 GROUP BY r2.TournamentId) np ON r.TournamentId = np.TournamentId " .
        "                       LEFT JOIN (SELECT r3.TournamentId, SUM(r3.rebuyCount) AS NumRebuys FROM poker_result r3 WHERE r3.Place > 0 AND r3.RebuyCount > 0 GROUP BY r3.TournamentId) nr ON r.TournamentId = nr.TournamentId " .
        "                       LEFT JOIN (SELECT TournamentId, COUNT(AddonPaid) AS NumAddons FROM poker_result WHERE AddonPaid = '" . Constant::FLAG_YES . "' GROUP BY TournamentId) na ON r.TournamentId = na.TournamentId " .
        "                       LEFT JOIN (SELECT a.tournamentId, s1.payoutId, s1.place, s1.percentage " .
        "                                  FROM (SELECT np.tournamentId, p.payoutId " .
        "                                        FROM (SELECT r.tournamentId, COUNT(*) AS numPlayers FROM poker_result r WHERE r.place > 0 AND r.statusCode IN ('" . Constant::CODE_STATUS_REGISTERED . "', '" . Constant::CODE_STATUS_FINISHED . "') GROUP BY r.tournamentId) np INNER JOIN poker_tournament t on np.tournamentId = t.tournamentId " .
        "                                        INNER JOIN poker_group_payout gp ON t.GroupId = gp.GroupId " .
        "                                        INNER JOIN poker_payout p ON gp.PayoutId = p.PayoutId AND np.numPlayers BETWEEN p.minPlayers AND p.maxPlayers) a " .
        "                                        INNER JOIN poker_structure s1 ON a.payoutId = s1.payoutId) s ON r.tournamentId = s.tournamentId AND r.place = s.place " .
        "                                        WHERE r.Place > 0) y " .
        "                       GROUP BY id " .
        "                       UNION ALL " .
        "                       SELECT xx.id, xx.name, SUM(xx.earnings) AS TotalEarnings, MAX(xx.earnings) AS MaxEarnings, 0 " .
        "                       FROM (SELECT se.seasonStartDate, YEAR(t.tournamentDate) AS Yr, p.Id, p.first_name, p.last_name, CONCAT(p.first_name, ' ', p.last_name) AS name, qq.total * IFNULL(s.Percentage, 0) AS Earnings, numTourneys AS trnys " .
        "                             FROM poker_result r INNER JOIN poker_user p ON r.PlayerId = p.Id " .
        "                             INNER JOIN poker_tournament t ON r.TournamentId = t.TournamentId AND t.tournamentDate BETWEEN :startDate5 AND :endDate5 " .
        "                             INNER JOIN poker_season se ON t.tournamentDate BETWEEN se.seasonStartDate AND se.seasonEndDate " .
        "                             INNER JOIN (SELECT seasonStartDate, seasonEndDate, SUM(total) - IF(YEAR(seasonEndDate) = 2008, 150, IF(YEAR(seasonEndDate) = 2007, -291, IF(YEAR(seasonEndDate) = 2006, -824, 0))) AS total " .
        "                                         FROM (SELECT se2.seasonStartDate, se2.seasonEndDate, t2.TournamentId AS Id, IF(b.Play IS NULL, 0, CONCAT(b.Play, '+', IFNULL(nr.NumRebuys, 0), 'r', '+', IFNULL(na.NumAddons, 0), 'a')) AS Play, ((t2.BuyinAmount * t2.Rake) * Play) + ((t2.RebuyAmount * t2.Rake) * IFNULL(nr.NumRebuys, 0)) + ((t2.AddonAmount * t2.Rake) * IFNULL(na.NumAddons, 0) ) AS Total " .
        "                                               FROM poker_tournament t2 INNER JOIN poker_season se2 ON t2.tournamentDate BETWEEN se2.seasonStartDate AND se2.seasonEndDate " .
        "                                               LEFT JOIN (SELECT TournamentId, COUNT(*) AS Play FROM poker_result WHERE buyinPaid = '" . Constant::FLAG_YES . "' AND Place > 0 GROUP BY TournamentId) b ON t2.TournamentId = b.TournamentId " .
        "                                               LEFT JOIN (SELECT r.TournamentId, SUM(r.rebuyCount) AS NumRebuys FROM poker_result r WHERE r.rebuyPaid = '" . Constant::FLAG_YES . "' AND r.RebuyCount > 0 GROUP BY r.TournamentId) nr ON t2.TournamentId = nr.TournamentId " .
        "                                               LEFT JOIN (SELECT r.TournamentId, COUNT(*) AS NumAddons FROM poker_result r WHERE r.AddonPaid = '" . Constant::FLAG_YES . "' GROUP BY r.TournamentId) na ON t2.TournamentId = na.TournamentId) zz " .
        "                                         GROUP BY seasonStartDate, seasonEndDate) qq ON qq.seasonStartDate = se.seasonStartDate AND qq.seasonEndDate = se.seasonEndDate " .
        "                             LEFT JOIN poker_special_type st ON t.specialTypeId = st.typeId " .
        "                             INNER JOIN (SELECT r1.playerId, COUNT(*) AS NumTourneys FROM poker_result r1 INNER JOIN poker_tournament t1 ON r1.tournamentId = t1.tournamentId AND r1.place > 0 INNER JOIN poker_special_type st1 ON t1.specialTypeId = st1.typeId AND st1.typeDescription = '" . Constant::DESCRIPTION_CHAMPIONSHIP . "' GROUP BY r1.playerId) nt ON r.playerId = nt.playerId " .
        "                             LEFT JOIN (SELECT a.tournamentId, s1.payoutId, s1.place, s1.percentage " .
        "                                        FROM (SELECT np.tournamentId, p.payoutId " .
        "                                              FROM (SELECT r.tournamentId, COUNT(*) AS numPlayers FROM poker_result r WHERE r.place > 0 AND r.statusCode IN ('" . Constant::CODE_STATUS_REGISTERED . "','" . Constant::CODE_STATUS_FINISHED . "') GROUP BY r.tournamentId) np " .
        "                                              INNER JOIN poker_tournament t on np.tournamentId = t.tournamentId " .
        "                                              AND t.tournamentDate BETWEEN :startDate6 AND :endDate6 " .
        "                                              INNER JOIN poker_group_payout gp ON t.GroupId = gp.GroupId " .
        "                                              INNER JOIN poker_payout p ON gp.PayoutId = p.PayoutId AND np.numPlayers BETWEEN p.minPlayers AND p.maxPlayers) a " .
        "                                              INNER JOIN poker_structure s1 ON a.payoutId = s1.payoutId) s ON r.tournamentId = s.tournamentId AND r.place = s.place " .
        "                             WHERE r.Place > 0 " .
        "                             AND st.typeDescription = '" . Constant::DESCRIPTION_CHAMPIONSHIP . "' " .
        "                             GROUP BY id, yr) xx " .
        "                       GROUP BY xx.id, xx.name) cc " .
        "                 GROUP BY id, name) b ON a.Id = b.Id " .
        "      LEFT JOIN (SELECT c.PlayerId, c.Place, c.NumPlayers, IF(c.Place IS NULL, 0, SUM((c.numPlayers - c.place + 1) * IFNULL(c.typeMultiplier, 1))) + IF(c.place BETWEEN 1 AND c.seasonFinalTablePlayers, c.seasonFinalTableBonusPoints, 0) AS Points, SUM(IFNULL(c.NumRebuys, 0) * c.RebuyAmount) AS Rebuys, SUM(IFNULL(c.NumAddons, 0) * c.AddonAmount) AS Addons, IFNULL(c.NumRebuys, 0) AS NumRebuys, c.BuyinAmount " .
        "                 FROM (SELECT a.TournamentId, a.tournamentDesc, a.PlayerId, a.Place, a.NumPlayers, a.NumRebuys, a.BuyinAmount, a.RebuyAmount, a.AddonAmount, a.NumAddons, a.typeDescription, a.typeMultiplier, a.seasonFinalTablePlayers, a.seasonFinalTableBonusPoints " .
        "                       FROM (SELECT r.TournamentId, t.tournamentDesc, r.PlayerId, r.Place, np.NumPlayers, nr.NumRebuys, t.BuyinAmount, t.RebuyAmount, t.AddonAmount, na.NumAddons, st.typeDescription, st.typeMultiplier, se.seasonFinalTablePlayers, se.seasonFinalTableBonusPoints " .
        "                             FROM poker_result r INNER JOIN poker_tournament t ON r.TournamentId = t.TournamentId AND t.tournamentDate BETWEEN :startDate7 AND :endDate7 " .
        "                             INNER JOIN poker_season se ON t.tournamentDate BETWEEN se.seasonStartDate AND se.seasonEndDate " .
        "                             AND r.place > 0 " .
        "                             LEFT JOIN poker_special_type st ON t.specialTypeId = st.typeId " .
        "                             INNER JOIN (SELECT r3.TournamentId, COUNT(*) AS NumPlayers FROM poker_result r3 INNER JOIN poker_tournament t3 ON r3.TournamentId = t3.TournamentId " .
        "                                         AND t3.tournamentDate BETWEEN :startDate8 AND :endDate8 " .
        "                                         WHERE r3.Place > 0 GROUP BY r3.TournamentId) np ON r.TournamentId = np.TournamentId " .
        "                             LEFT JOIN (SELECT r4.TournamentId, r4.PlayerId, SUM(r4.rebuyCount) AS NumRebuys FROM poker_result r4 INNER JOIN poker_tournament t4 ON r4.TournamentId = t4.TournamentId " .
        "                                        AND t4.tournamentDate BETWEEN :startDate9 AND :endDate9 " .
        "                                        WHERE r4.Place > 0 AND r4.RebuyCount > 0 GROUP BY r4.TournamentId, r4.PlayerId) nr ON r.TournamentId = nr.TournamentId AND r.PlayerId = nr.PlayerId " .
        "                             LEFT JOIN (SELECT TournamentId, PlayerId, COUNT(AddonPaid) AS NumAddons FROM poker_result r7 WHERE AddonPaid = '" . Constant::FLAG_YES . "' GROUP BY TournamentId, PlayerId) na ON r.TournamentId = na.TournamentId AND r.PlayerId = na.PlayerId) a " .
        "                      ) c " .
        "                 GROUP BY c.PlayerId) e ON b.Id = e.PlayerId " .
        "      LEFT JOIN (SELECT id, SUM(knockouts) AS kos, MAX(knockouts) AS koMax " .
        "                 FROM (SELECT u.id, COUNT(*) AS knockouts " .
        "                       FROM poker_tournament t INNER JOIN poker_result r ON t.tournamentId = r.tournamentId " .
        "                       AND t.tournamentDate BETWEEN :startDate10 AND :endDate10 " .
        "                       AND r.place > 0 " .
        "                       INNER JOIN poker_user u ON r.knockedOutBy = u.id " .
        "                       GROUP BY t.tournamentid, r.knockedOutBy) k " .
        "                 GROUP BY id) km ON b.id = km.id " .
        "      LEFT JOIN (SELECT r.playerId, COUNT(*) AS wins " .
        "                 FROM poker_tournament t INNER JOIN poker_result r ON t.tournamentId = r.tournamentId AND r.place = 1 " .
        "                 AND t.tournamentDate BETWEEN :startDate11 AND :endDate11 " .
        "                 GROUP BY r.playerId) w ON b.id = w.playerId " .
        "      WHERE b.Id IN (SELECT DISTINCT playerId FROM poker_result WHERE statusCode = '" . Constant::CODE_STATUS_FINISHED . "') " .
        "      ) d";
        if (isset($limitCount)) {
          $query .= " LIMIT :limitCount";
        }
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':startDate1', $params[0], PDO::PARAM_STR);
        $pdoStatement->bindParam(':endDate1', $params[1], PDO::PARAM_STR);
        $pdoStatement->bindParam(':startDate2', $params[0], PDO::PARAM_STR);
        $pdoStatement->bindParam(':endDate2', $params[1], PDO::PARAM_STR);
        $pdoStatement->bindParam(':startDate3', $params[0], PDO::PARAM_STR);
        $pdoStatement->bindParam(':endDate3', $params[1], PDO::PARAM_STR);
        $pdoStatement->bindParam(':startDate4', $params[0], PDO::PARAM_STR);
        $pdoStatement->bindParam(':endDate4', $params[1], PDO::PARAM_STR);
        $pdoStatement->bindParam(':startDate5', $params[0], PDO::PARAM_STR);
        $pdoStatement->bindParam(':endDate5', $params[1], PDO::PARAM_STR);
        $pdoStatement->bindParam(':startDate6', $params[0], PDO::PARAM_STR);
        $pdoStatement->bindParam(':endDate6', $params[1], PDO::PARAM_STR);
        $pdoStatement->bindParam(':startDate7', $params[0], PDO::PARAM_STR);
        $pdoStatement->bindParam(':endDate7', $params[1], PDO::PARAM_STR);
        $pdoStatement->bindParam(':startDate8', $params[0], PDO::PARAM_STR);
        $pdoStatement->bindParam(':endDate8', $params[1], PDO::PARAM_STR);
        $pdoStatement->bindParam(':startDate9', $params[0], PDO::PARAM_STR);
        $pdoStatement->bindParam(':endDate9', $params[1], PDO::PARAM_STR);
        $pdoStatement->bindParam(':startDate10', $params[0], PDO::PARAM_STR);
        $pdoStatement->bindParam(':endDate10', $params[1], PDO::PARAM_STR);
        $pdoStatement->bindParam(':startDate11', $params[0], PDO::PARAM_STR);
        $pdoStatement->bindParam(':endDate11', $params[1], PDO::PARAM_STR);
        if (isset($limitCount)) {
          $pdoStatement->bindParam(':limitCount', $limitCount, PDO::PARAM_INT);
        }
        break;
      case "seasonSelectAll":
      case "seasonSelectOneById":
      case "seasonSelectOneByIdAndDesc":
      case "seasonSelectOneByActive":
        $query =
        "SELECT seasonId AS id, seasonDescription AS description, seasonStartDate AS 'start date', seasonEndDate AS 'end date', seasonChampionshipQualify AS '# to qualify', seasonFinalTablePlayers AS 'final table players', seasonFinalTableBonusPoints AS 'final table bonus points', seasonFee AS fee, seasonActive AS 'active' " .
        "FROM poker_season s ";
        if ("seasonSelectOneById" == $dataName) {
          $query .= " WHERE seasonid = :seasonId";
        } else if ("seasonSelectOneByIdAndDesc" == $dataName) {
          $query .=
          " INNER JOIN poker_tournament t" .
          " LEFT JOIN poker_special_type st ON t.specialTypeId = st.typeId" .
          " WHERE t.tournamentDate BETWEEN s.seasonStartDate AND s.seasonEndDate AND t.tournamentId = :tournamentId AND st.typeDescription = :typeDescription";
        } else if ("seasonSelectOneByActive" == $dataName) {
          $query .= " WHERE seasonActive = :seasonActive";
        } else if ("seasonSelectAll" == $dataName && isset($params[3])) {
          $query .= " WHERE seasonId IN (:seasonId)";
        }
        if (isset($params[2]) && $params[2]) {
          $query .= " ORDER BY seasonStartDate DESC";
        }
        if (isset($limitCount)) {
          $query .= " LIMIT :limitCount";
        }
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        if ("seasonSelectOneById" == $dataName) {
          $pdoStatement->bindParam(':seasonId', $params[0], PDO::PARAM_INT);
        } else if ("seasonSelectOneByIdAndDesc" == $dataName) {
          $pdoStatement->bindParam(':tournamentId', $params[0], PDO::PARAM_INT);
          $pdoStatement->bindParam(':typeDescription', $params[1], PDO::PARAM_STR);
        } else if ("seasonSelectOneByActive" == $dataName) {
          $pdoStatement->bindParam(':seasonActive', $params[0], PDO::PARAM_INT);
        } else if ("seasonSelectAll" == $dataName && isset($params[3])) {
          $pdoStatement->bindParam(':seasonId', $params[3], PDO::PARAM_INT);
        }
        if (isset($limitCount)) {
          $pdoStatement->bindParam(':limitCount', $limitCount, PDO::PARAM_INT);
        }
        break;
      case "seasonSelectOneByTournamentId":
        $query =
        "SELECT s.seasonId " .
        "FROM poker_season s INNER JOIN poker_tournament t ON t.tournamentDate BETWEEN s.seasonStartDate AND s.seasonEndDate AND t.tournamentId = :tournamentId";
        if (isset($limitCount)) {
          $query .= " LIMIT :limitCount";
        }
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':tournamentId', $params[0], PDO::PARAM_INT);
        if (isset($limitCount)) {
          $pdoStatement->bindParam(':limitCount', $limitCount, PDO::PARAM_INT);
        }
        break;
      case "seasonSelectAllChampionship":
        $query =
        "SELECT seasonId, seasonDescription, seasonStartDate, seasonEndDate, seasonChampionshipQualify, seasonFinalTablePlayers, seasonFinalTableBonusPoints, seasonFee, seasonActive " .
        "FROM poker_season " .
        "ORDER BY seasonEndDate DESC, seasonStartDate";
        break;
      case "seasonSelectMaxId":
        $query =
        "SELECT MAX(seasonId) AS id " .
        "FROM poker_season";
        break;
      case "specialTypeSelectAll":
      case "specialTypeSelectOneById":
        $query =
        "SELECT typeId AS id, typeDescription AS description, typeMultiplier AS multiplier " .
        "FROM poker_special_type";
        if ("specialTypeSelectOneById" == $dataName) {
          $query .= " WHERE typeId = :typeId";
        } else if ("specialTypeSelectAll" == $dataName) {
          if ($params[1]) {
            $query .= " WHERE typeId IN (:typeId)";
          }
        }
        if (isset($params[0]) && $params[0]) {
          $query .= " ORDER BY typeDescription";
        }
        if (isset($limitCount)) {
          $query .= " LIMIT :limitCount";
        }
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        if ("specialTypeSelectOneById" == $dataName) {
          $pdoStatement->bindParam(':typeId', $params[0], PDO::PARAM_INT);
        } else if ("specialTypeSelectAll" == $dataName) {
          if ($params[1]) {
            $pdoStatement->bindParam(':typeId', $params[1], PDO::PARAM_INT);
          }
        }
        if (isset($limitCount)) {
          $pdoStatement->bindParam(':limitCount', $limitCount, PDO::PARAM_INT);
        }
        break;
      case "statusSelectPaid":
        $query =
        "SELECT p.id, CONCAT(p.first_name, ' ', p.last_name) AS name, " .
        "       IF(s.seasonFee - f.amount = 0, 'Paid', 'Not paid') AS 'fee status logic', " .
        "       IF(s.seasonFee IS NULL OR f.amount IS NULL, '', IF(s.seasonFee - f.amount = 0, 'Paid', CONCAT('Owes ', (s.seasonFee - f.amount)))) AS 'fee status', " .
        "       IFNULL(f.amount, 0) AS amount, " .
        "       IF(f.amount = s.seasonFee, f.amount, IFNULL(f2.amount, 0)) AS amount2, " .
        "       IF(r.buyinPaid = '" . Constant::FLAG_YES . "', 'Paid', 'Not paid') AS 'buyin status', " .
        "       IF(r.rebuyPaid = '" . Constant::FLAG_YES . "', 'Paid', 'Not paid') AS 'rebuy status', " .
        "       r.rebuyCount, IF(r.addonPaid = '" . Constant::FLAG_YES . "', 'Paid', 'Not paid') AS 'addon status' " .
        "FROM poker_user p INNER JOIN poker_result r ON p.id = r.playerId AND " . $this->buildUserActive(alias: "p") .
        " INNER JOIN poker_tournament t ON r.tournamentId = t.tournamentId AND t.tournamentId = :tournamentId AND r.statusCode IN ('" . Constant::CODE_STATUS_REGISTERED . "', '" . Constant::CODE_STATUS_PAID . "') AND r.registerOrder <= t.maxPlayers" .
        " INNER JOIN poker_season s ON t.tournamentDate BETWEEN s.seasonStartDate AND s.seasonEndDate" .
        " LEFT JOIN (SELECT seasonId, playerId, SUM(amount) AS amount FROM poker_fee GROUP BY seasonId, playerId) f ON s.seasonId = f.seasonId AND p.id = f.playerId" .
        " LEFT JOIN (SELECT seasonId, playerId, tournamentId, amount FROM poker_fee) f2 ON s.seasonId = f2.seasonId AND p.id = f2.playerId AND t.tournamentId = f2.tournamentId";
        if (isset($limitCount)) {
          $query .= " LIMIT :limitCount";
        }
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':tournamentId', $params[0], PDO::PARAM_INT);
        if (isset($limitCount)) {
          $pdoStatement->bindParam(':limitCount', $limitCount, PDO::PARAM_INT);
        }
        break;
      case "statusSelectAll":
        $query =
        "SELECT statusCode, statusName " .
        "FROM poker_status_code";
        break;
      case "structureSelectAll":
      case "structurePayout":
        $query = "SELECT payoutId, place, percentage";
        if ("structurePayout" == $dataName) {
          $query .= " * " . $params[0] . " AS pay, percentage";
        }
        $query .= " FROM poker_structure";
        if ("structurePayout" == $dataName) {
          $query .= " WHERE payoutId = :payoutId";
        }
        if (isset($limitCount)) {
          $query .= " LIMIT :limitCount";
        }
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        if ("structurePayout" == $dataName) {
          $pdoStatement->bindParam(':payoutId', $params[0], PDO::PARAM_INT);
        }
        if (isset($limitCount)) {
          $pdoStatement->bindParam(':limitCount', $limitCount, PDO::PARAM_INT);
        }
        break;
      case "tournamentIdMax":
        $query =
        "SELECT MAX(t.tournamentId) AS tournamentId " .
        "FROM poker_tournament t INNER JOIN poker_season s ON t.tournamentDate BETWEEN s.seasonStartDate AND s.seasonEndDate " .
        "WHERE s.seasonId = :seasonId";
        if (isset($limitCount)) {
          $query .= " LIMIT :limitCount";
        }
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':seasonId', $params[0], PDO::PARAM_INT);
        if (isset($limitCount)) {
          $pdoStatement->bindParam(':limitCount', $limitCount, PDO::PARAM_INT);
        }
        break;
      case "tournamentAll":
        $query =
        "SELECT tournamentId, tournamentDesc, tournamentDate " .
        "FROM poker_tournament " .
        "WHERE YEAR(tournamentDate) = :tournamentDate " .
        "ORDER BY tournamentDate DESC, startTime DESC";
        if (isset($limitCount)) {
          $query .= " LIMIT :limitCount";
        }
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':tournamentDate', $params[0], PDO::PARAM_STR);
        if (isset($limitCount)) {
          $pdoStatement->bindParam(':limitCount', $limitCount, PDO::PARAM_INT);
        }
        break;
      case "tournamentSelectAll":
      case "tournamentSelectAllByDateAndStartTime":
      case "tournamentSelectOneById":
      case "tournamentSelectAllForRegistration":
      case "tournamentSelectAllForBuyins":
      case "tournamentSelectAllForChampionship":
      case "tournamentSelectAllOrdered":
      case "tournamentsSelectForEmailNotifications":
        $query =
        "SELECT t.tournamentId AS id, t.tournamentDesc AS description, t.comment, t.locationId, l.locationName AS location, t.limitTypeId, lt.limitTypeName AS 'limit', t.gameTypeId, gt.gameTypeName AS 'type', " .
        "       l.playerId, CONCAT(u.first_name, ' ', u.last_name) AS playerName, l.address, l.city, l.state, l.zipCode, u.phone, l.map AS mapHide, l.mapLink AS map, t.tournamentDate AS date, t.startTime AS 'start', t.endTime AS 'end', t.maxPlayers AS 'max players', t.chipCount AS 'chips', -t.buyinAmount AS 'buyin', t.maxRebuys AS 'max', -t.rebuyAmount AS 'amt', -t.addonAmount AS 'amt ', " .
        "       t.addonChipCount AS 'chips ', t.groupId, g.groupName AS name, t.rake, IFNULL(nr.registeredCount, 0) AS registeredCount, " .
        "       IFNULL(bp.buyinsPaid, 0) AS buyinsPaid, " .
        "       IFNULL(rp.rebuysPaid, 0) AS rebuysPaid, " .
        "       IFNULL(rc.rebuysCount, 0) AS rebuysCount, " .
        "       IFNULL(ap.addonsPaid, 0) AS addonsPaid, " .
        "       IFNULL(ec.enteredCount, 0) AS enteredCount, " .
        "       t.specialTypeId, st.typeDescription AS std, st.typeMultiplier " .
        "FROM poker_tournament t INNER JOIN poker_game_type gt ON t.gameTypeId = gt.gameTypeId " .
        "INNER JOIN poker_limit_type lt ON t.limitTypeId = lt.limitTypeId ";
        if ("tournamentSelectAllForChampionship" == $dataName) {
          $query .= "INNER JOIN poker_special_type st ON t.specialTypeId = st.typeId AND st.typeDescription = '" . Constant::DESCRIPTION_CHAMPIONSHIP . "' ";
        } else {
          $query .= "LEFT JOIN poker_special_type st ON t.specialTypeId = st.typeId ";
        }
        $query .=
        "INNER JOIN poker_location l ON t.locationId = l.locationId " .
        "INNER JOIN poker_user u ON l.playerId = u.id " .
        "INNER JOIN poker_group g on t.groupId = g.groupId " .
        "LEFT JOIN (SELECT tournamentId, COUNT(*) AS buyinsPaid FROM poker_result WHERE buyinPaid = '" . Constant::FLAG_YES . "' AND place = 0 GROUP BY tournamentId) bp ON t.tournamentId = bp.tournamentId " .
        "LEFT JOIN (SELECT tournamentId, COUNT(*) AS rebuysPaid FROM poker_result WHERE rebuyPaid = '" . Constant::FLAG_YES . "' AND place = 0 GROUP BY tournamentId) rp ON t.tournamentId = rp.tournamentId " .
        "LEFT JOIN (SELECT tournamentId, SUM(rebuyCount) AS rebuysCount FROM poker_result WHERE rebuyPaid = '" . Constant::FLAG_YES . "' AND place = 0 GROUP BY tournamentId) rc ON t.tournamentId = rc.tournamentId " .
        "LEFT JOIN (SELECT tournamentId, COUNT(*) AS addonsPaid FROM poker_result WHERE addonPaid = '" . Constant::FLAG_YES . "' AND place = 0 GROUP BY tournamentId) ap ON t.tournamentId = ap.tournamentId " .
        "LEFT JOIN (SELECT tournamentId, COUNT(*) AS enteredCount FROM poker_result WHERE statusCode = '" . Constant::CODE_STATUS_FINISHED . "' AND place <> 0 GROUP BY tournamentId) ec ON t.tournamentId = ec.tournamentId " .
        "LEFT JOIN (SELECT tournamentId, COUNT(*) AS registeredCount FROM poker_result WHERE statusCode = '" . Constant::CODE_STATUS_REGISTERED . "' GROUP BY tournamentId) nr ON t.tournamentId = nr.tournamentId";
        if ("tournamentSelectAllByDateAndStartTime" == $dataName) {
          $query .=
          " WHERE t.tournamentDate >= :tournamentDate1 OR (t.tournamentDate = :tournamentDate2 AND t.startTime >= :startTime)" .
          " ORDER BY t.tournamentDate, t.startTime";
        } else if ("tournamentSelectOneById" == $dataName) {
          $query .= " WHERE t.tournamentId = :tournamentId";
        } else if ("tournamentSelectAllForRegistration" == $dataName || "tournamentSelectAllForBuyins" == $dataName || "tournamentSelectAllForChampionship" == $dataName) {
          $query .=
          " WHERE (" . $params[0] . " >= t.tournamentDate OR CURRENT_DATE <= " . $params[1] . ")" .
          " AND enteredCount IS NULL" .
          " ORDER BY t.tournamentDate, t.startTime";
        } else if ("tournamentSelectAllOrdered" == $dataName) {
          $query .= " ORDER BY t.tournamentDate DESC, t.startTime DESC";
        } else if ("tournamentSelectAll" == $dataName) {
          if (isset($params[0])) {
            $query .= " WHERE t.tournamentId IN (:tournamentId)";
          }
          if (isset($orderBy)) {
            $query .= $orderBy;
          }
        } else if ("tournamentsSelectForEmailNotifications" == $dataName) {
          $query .=
          " WHERE DATE(tournamentDate) = DATE(DATE_ADD(now(), INTERVAL :interval DAY))" .
          " ORDER BY tournamentDate, startTime";
        }
        if (isset($limitCount)) {
          $query .= " LIMIT :limitCount";
        }
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        if ("tournamentSelectAllByDateAndStartTime" == $dataName) {
          $pdoStatement->bindParam(':tournamentDate1', $params[0], PDO::PARAM_STR);
          $pdoStatement->bindParam(':tournamentDate2', $params[0], PDO::PARAM_STR);
          $pdoStatement->bindParam(':startTime', $params[1], PDO::PARAM_STR);
        } else if ("tournamentSelectOneById" == $dataName) {
          $pdoStatement->bindParam(':tournamentId', $params[0], PDO::PARAM_INT);
        } else if ("tournamentsSelectForEmailNotifications" == $dataName) {
          $pdoStatement->bindParam(':interval', $params[0], PDO::PARAM_INT);
        } else if ("tournamentSelectAll" == $dataName) {
          if (isset($params[0])) {
            $pdoStatement->bindParam(':tournamentId', $params[0], PDO::PARAM_INT);
          }
        }
        if (isset($limitCount)) {
          $pdoStatement->bindParam(':limitCount', $limitCount, PDO::PARAM_INT);
        }
        break;
      case "tournamentSelectAllRegistrationStatus":
        $query =
        "SELECT pt.id, CONCAT(pt.first_name, ' ', pt.last_name) AS name, " .
        "IF(pt.seasonFee - IFNULL(pt.amount, 0) = 0, 'Paid', CONCAT('Owes $', seasonFee - IFNULL(pt.amount, 0))) AS 'season fee', " .
        "IF(r.registerOrder IS NULL, 'Not registered', 'Registered') AS status, " .
        "IF(r.registerOrder IS NULL, 'N/A', IF(r.registerOrder > pt.maxPlayers, CONCAT(r.registerOrder, ' (Wait list #', r.registerOrder - pt.maxPlayers, ')'), r.registerOrder)) AS 'order' " .
        "FROM (SELECT p.id, p.first_name, p.last_name, t.tournamentId, t.maxPlayers, s.seasonFee, f.amount " .
        "      FROM poker_user p CROSS JOIN poker_tournament t ON " . $this->buildUserActive(alias: "p") . " AND t.tournamentId = :tournamentId" .
        "      LEFT JOIN poker_season s ON t.tournamentDate BETWEEN s.seasonStartDate AND s.seasonEndDate " .
        "      LEFT JOIN (SELECT seasonId, playerId, SUM(amount) AS amount FROM poker_fee GROUP BY seasonId, playerId) f ON s.seasonId = f.seasonId AND p.id = f.playerId) pt " .
        "LEFT JOIN poker_result r ON pt.id = r.playerId AND r.tournamentId = pt.tournamentId AND r.statusCode IN ('" . Constant::CODE_STATUS_REGISTERED . "', '" . Constant::CODE_STATUS_PAID . "');";
        if (isset($limitCount)) {
          $query .= " LIMIT :limitCount";
        }
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':tournamentId', $params[0], PDO::PARAM_INT);
        if (isset($limitCount)) {
          $pdoStatement->bindParam(':limitCount', $limitCount, PDO::PARAM_INT);
        }
        break;
      case "tournamentSelectAllDuring":
        $query =
        "SELECT t.tournamentId, t.tournamentDate, t.startTime, l.locationName, IFNULL(bp.buyinsPaid, 0) AS buyinsPaid " .
        "FROM poker_tournament t " .
        "INNER JOIN poker_location l ON t.locationId = l.locationId " .
        "LEFT JOIN (SELECT tournamentId, COUNT(*) AS buyinsPaid " .
        "           FROM poker_result " .
        "           WHERE buyinPaid = '" . Constant::FLAG_YES . "' " .
        "           GROUP BY tournamentId) bp ON t.tournamentId = bp.tournamentId " .
        "WHERE tournamentDate = CURRENT_DATE " .
        "AND bp.buyinsPaid > 0 " .
        "AND t.startTime = (SELECT MAX(t.startTime) " .
        "                   FROM poker_tournament t " .
        "                   LEFT JOIN (SELECT tournamentId, COUNT(*) AS buyinsPaid " .
        "                              FROM poker_result " .
        "                              WHERE buyinPaid = '" . Constant::FLAG_YES . "' " .
        "                              GROUP BY tournamentId) bp ON t.tournamentId = bp.tournamentId AND t.tournamentDate = CURRENT_DATE " .
        "                   WHERE bp.buyinsPaid > 0)";
        break;
      case "tournamentSelectAllYearsPlayed":
        $query =
        "SELECT DISTINCT YEAR(tournamentDate) AS year " .
        "FROM poker_tournament " .
        "ORDER BY YEAR(tournamentDate) desc";
        if (isset($orderBy)) {
          $query .= " " . $orderBy;
        }
        break;
      case "tournamentsPlayedByPlayerIdAndDateRange":
        $query =
        "SELECT COUNT(*) AS numPlayed " .
        "FROM poker_tournament t INNER JOIN poker_result r ON t.tournamentId = r.tournamentId " .
        "AND playerId = :playerId " .
        "AND place > 0 " .
        "AND tournamentDate BETWEEN :startDate AND :endDate";
        if (isset($limitCount)) {
          $query .= " LIMIT :limitCount";
        }
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':playerId', $params[0], PDO::PARAM_INT);
        $pdoStatement->bindParam(':startDate', $params[1], PDO::PARAM_STR);
        $pdoStatement->bindParam(':endDate', $params[2], PDO::PARAM_STR);
        if (isset($limitCount)) {
          $pdoStatement->bindParam(':limitCount', $limitCount, PDO::PARAM_INT);
        }
        break;
      case "tournamentsWonByPlayerId":
        $query =
        "SELECT t.tournamentId AS id, t.tournamentDesc AS description, t.comment as comment, t.locationId, l.locationName AS location, t.limitTypeId, lt.limitTypeName AS 'limit', t.gameTypeId, gt.gameTypeName AS 'type', t.chipCount AS 'chips', " .
        "l.playerId, CONCAT(u.first_name, ' ', u.last_name) AS name, l.address, l.city, l.state, l.zipCode, u.phone, t.tournamentDate AS date, t.startTime AS 'start', t.endTime AS 'end', t.buyinAmount AS 'buyin', t.maxPlayers AS 'max players', t.maxRebuys AS 'max', t.rebuyAmount AS 'amt', t.addonAmount AS 'amt ', " .
        "t.addonChipCount AS 'chips ', t.rake, l.map AS mapHide, l.mapLink AS map, IFNULL(ec.enteredCount, 0) AS enteredCount, u.active, t.specialTypeId, st.typeDescription AS std, " .
        "(((t.buyinAmount * ec.enteredCount) + (IFNULL(nr.numRebuys, 0) * t.rebuyAmount) + (IFNULL(na.numAddons, 0) * t.addonAmount)) * .8)* s.percentage AS earnings, st.typeMultiplier " .
        "FROM poker_user u INNER JOIN poker_result r ON u.id = r.playerId AND u.id = :playerId" .
        " INNER JOIN poker_tournament t ON t.tournamentId = r.tournamentId AND r.place = 1 " .
        "LEFT JOIN poker_special_type st ON t.specialTypeId = st.typeId " .
        "INNER JOIN poker_game_type gt ON t.gameTypeId = gt.gameTypeId " .
        "INNER JOIN poker_limit_type lt ON t.limitTypeId = lt.limitTypeId " .
        "INNER JOIN poker_location l ON t.locationId = l.locationId " .
        "LEFT JOIN (SELECT tournamentId, COUNT(*) AS enteredCount " .
        "           FROM poker_result " .
        "           WHERE statusCode = '" . Constant::CODE_STATUS_FINISHED . "' " .
        "           AND place <> 0 " .
        "           GROUP BY tournamentId) ec ON t.tournamentId = ec.tournamentId " .
        "LEFT JOIN (SELECT a.tournamentId, s1.payoutId, s1.place, s1.percentage " .
        "          FROM (SELECT np.tournamentId, p.payoutId " .
        "                FROM (SELECT r.tournamentId, COUNT(*) AS numPlayers " .
        "                      FROM poker_result r " .
        "                      WHERE r.statusCode IN ('" . Constant::CODE_STATUS_REGISTERED . "','" . Constant::CODE_STATUS_FINISHED . "') " .
        "                      GROUP BY r.tournamentId) np " .
        "                INNER JOIN poker_tournament t on np.tournamentId = t.tournamentId " .
        "                INNER JOIN poker_group_payout gp ON t.GroupId = gp.GroupId " .
        "                INNER JOIN poker_payout p ON gp.PayoutId = p.PayoutId AND np.numPlayers BETWEEN p.minPlayers AND p.maxPlayers) a " .
        "          INNER JOIN poker_structure s1 ON a.payoutId = s1.payoutId) s ON r.tournamentId = s.tournamentId AND r.place = s.place " .
        "LEFT JOIN (SELECT r2.tournamentId, SUM(r2.rebuyCount) AS numRebuys " .
        "           FROM poker_result r2 " .
        "           WHERE r2.rebuyCount > 0 GROUP BY r2.tournamentId) nr ON r.tournamentId = nr.tournamentId " .
        "LEFT JOIN (SELECT tournamentId, COUNT(addonPaid) AS numAddons " .
        "           FROM poker_result WHERE addonPaid = '" . Constant::FLAG_YES . "' GROUP BY tournamentId) na ON r.tournamentId = na.tournamentId " .
        "ORDER BY date, 'start time'";
        if (isset($limitCount)) {
          $query .= " LIMIT :limitCount";
        }
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':playerId', $params[0], PDO::PARAM_INT);
        if (isset($limitCount)) {
          $pdoStatement->bindParam(':limitCount', $limitCount, PDO::PARAM_INT);
        }
        break;
      case "tournamentsPlayed":
        $query =
        "SELECT u.id, CONCAT(u.first_name, ' ', u.last_name) AS name, u.active, COUNT(*) AS tourneys " .
        "FROM poker_user u " .
        "LEFT JOIN poker_result r ON u.id = r.playerId AND r.place > 0 " .
        "WHERE " . $this->buildUserActive(alias: "u") .
        " GROUP BY u.id <REPLACE>";
        if ($rank) {
          $whereClause = "<REPLACE>";
          $orderByFieldName = "tourneys DESC, " . $this->buildOrderByName(prefix: NULL);
          $selectFieldNames = "id, name, tourneys";
          $query = $this->modifyQueryAddRank(query: $query, whereClause: $whereClause, selectFieldName: "tourneys", selectFieldNames: $selectFieldNames, orderByFieldName: $orderByFieldName);
        }
        break;
      case "tournamentsPlayedByType":
        $query =
        "SELECT lt.limitTypeId, lt.limitTypeName AS 'limit type', gt.gameTypeId, gt.gameTypeName AS 'game type', t.maxRebuys AS rebuys, IF(t.addonAmount > 0, '" . Constant::FLAG_YES . "', '" . Constant::FLAG_NO . "') AS addon, COUNT(*) AS count " .
        "FROM poker_tournament t " .
        "INNER JOIN poker_limit_type lt ON lt.limitTypeId = t.limitTypeId " .
        "INNER JOIN poker_game_type gt ON gt.gameTypeId = t.gameTypeId " .
        "INNER JOIN poker_result r ON t.tournamentId = r.tournamentId AND r.statusCode = '" . Constant::CODE_STATUS_FINISHED . "' AND r.playerId = :playerId " .
        "GROUP BY lt.limitTypeId, lt.limitTypeName, gt.gameTypeId, gt.gameTypeName, t.maxRebuys, addon";
        if (isset($limitCount)) {
          $query .= " LIMIT :limitCount";
        }
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':playerId', $params[0], PDO::PARAM_INT);
        if (isset($limitCount)) {
          $pdoStatement->bindParam(':limitCount', $limitCount, PDO::PARAM_INT);
        }
        break;
      case "tournamentsPlayedFirst":
        $query =
        "SELECT MIN(t.tournamentDate) AS date " .
        "FROM poker_tournament t " .
        "INNER JOIN poker_result r ON t.tournamentId = r.tournamentId AND r.playerId = :playerId";
        if (isset($limitCount)) {
          $query .= " LIMIT :limitCount";
        }
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':playerId', $params[0], PDO::PARAM_INT);
        if (isset($limitCount)) {
          $pdoStatement->bindParam(':limitCount', $limitCount, PDO::PARAM_INT);
        }
        break;
      case "tournamentIdList":
        $query =
        "SELECT tournamentId " .
        "FROM poker_tournament " .
        "ORDER BY tournamentDate, startTime";
        break;
      case "userAbsencesByTournamentId":
        $query =
        "SELECT pta.playerId, CONCAT(u.first_name, ' ', u.last_name) AS name " .
        "FROM poker_tournament_absence pta INNER JOIN poker_tournament pt ON pta.tournamentId = pt.tournamentId AND YEAR(tournamentDate) = :tournamentDate " .
        "LEFT JOIN poker_special_type st ON pt.specialTypeId = st.typeId AND st.typeDescription = '" . Constant::DESCRIPTION_CHAMPIONSHIP . "'" .
        " INNER JOIN poker_user u ON pta.playerId = u.id";
        if (isset($limitCount)) {
          $query .= " LIMIT :limitCount";
        }
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':tournamentDate', $params[0], PDO::PARAM_STR);
        if (isset($limitCount)) {
          $pdoStatement->bindParam(':limitCount', $limitCount, PDO::PARAM_INT);
        }
        break;
      case "userPaidByTournamentId":
        $query =
        "SELECT p.id, CONCAT(p.first_name, ' ', p.last_name) AS name, p.username, p.password, p.email, p.administrator, p.registration_date, p.approval_date, p.approval_userid, p.rejection_date, p.rejection_userid, p.active, p.selector, p.token, p.expires " .
        "FROM poker_user p " .
        "INNER JOIN poker_result r ON p.id = r.playerId AND r.tournamentId = :tournamentId " .
        "AND r.buyinPaid = '" . Constant::FLAG_YES . "' " .
        "ORDER BY " . $this->buildOrderByName(prefix: NULL);
        if (isset($limitCount)) {
          $query .= " LIMIT :limitCount";
        }
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':tournamentId', $params[0], PDO::PARAM_INT);
        if (isset($limitCount)) {
          $pdoStatement->bindParam(':limitCount', $limitCount, PDO::PARAM_INT);
        }
        break;
      case "userActive":
        $query =
        "SELECT u.id, CONCAT(u.first_name, ' ', u.last_name) AS name, u.email " .
        "FROM poker_user u " .
        "WHERE " . $this->buildUserActive(alias: "u") .
        " ORDER BY " . $this->buildOrderByName(prefix: "u");
        break;
      case "userSelectAll":
      case "userSelectOneById":
      case "userSelectOneByName":
      case "userSelectOneByUsername":
      case "userSelectOneByEmail":
        $query =
        "SELECT u.id, CONCAT(u.first_name, ' ', u.last_name) AS name, u.username, u.password, u.email, u.phone, u.administrator AS admin, u.registration_date AS 'Reg Date', u.approval_date AS 'App Date', u.approval_userid AS 'App User', CONCAT(ua.first_name, ' ', ua.last_name) AS 'App Name', u.rejection_date AS 'Reject Date', u.rejection_userid AS 'Reject User', CONCAT(ur.first_name, ' ', ur.last_name) AS 'Reject Name', u.active " .
        "FROM poker_user u " .
        "LEFT JOIN poker_user ua ON u.approval_userid = ua.id " .
        "LEFT JOIN poker_user ur ON u.rejection_userid = ur.id";
        if ("userSelectAll" != $dataName && "userSelectOneById" != $dataName) {
          $query .= " WHERE u.active <> 0";
        }
        if ("userSelectOneById" == $dataName) {
          $query .= " WHERE u.id = :playerId";
        } else if ("userSelectOneByName" == $dataName) {
          $query .= " AND CONCAT(u.first_name, ' ', u.last_name) = :name";
        } else if ("userSelectOneByUsername" == $dataName) {
          $query .= " AND u.username = :userName";
        } else if ("userSelectOneByEmail" == $dataName) {
          $query .= " AND u.email = :email";
        }
        $query .= " ORDER BY " . $this->buildOrderByName(prefix: "u");
        if (isset($limitCount)) {
          $query .= " LIMIT :limitCount";
        }
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        if ("userSelectOneById" == $dataName) {
          $pdoStatement->bindParam(':playerId', $params[0], PDO::PARAM_INT);
        } else if ("userSelectOneByName" == $dataName) {
          $pdoStatement->bindParam(':name', $params[0], PDO::PARAM_STR);
        } else if ("userSelectOneByUsername" == $dataName) {
          $pdoStatement->bindParam(':userName', $params[0], PDO::PARAM_STR);
        } else if ("userSelectOneByEmail" == $dataName) {
          $pdoStatement->bindParam(':email', $params[0], PDO::PARAM_STR);
        }
        if (isset($limitCount)) {
          $pdoStatement->bindParam(':limitCount', $limitCount, PDO::PARAM_INT);
        }
        break;
      case "usersSelectForEmailNotifications":
        $query =
        "SELECT id, CONCAT(first_name, ' ', last_name) AS name, email " .
        "FROM poker_user u " .
        "WHERE " . $this->buildUserActive(alias: "u") .
        " AND approval_date IS NOT NULL " .
        "AND rejection_date IS NULL " .
        "AND email <>  '' " .
        "ORDER BY id";
        break;
      case "usersSelectForApproval":
        $query =
        "SELECT u.id, CONCAT(u.first_name, ' ', u.last_name) AS name, u.email, u.username, u.rejection_date AS 'Rejection Date', CONCAT(u2.first_name, ' ', u2.last_name) AS 'Rejection Name' " .
        "FROM poker_user u " .
        "LEFT JOIN poker_user u2 ON u.rejection_userid = u2.id " .
        "WHERE u.approval_date IS NULL AND u.rejection_date IS NULL";
        break;
      case "waitListedPlayerByTournamentId":
        $query =
        "SELECT u.id, CONCAT(u.first_name, ' ', u.last_name) AS name, u.email, t.maxPlayers " .
        "FROM poker_user u " .
        "INNER JOIN poker_result r ON u.id = r.playerId AND r.tournamentId = :tournamentId AND " . $this->buildUserActive(alias: "u") .
        " INNER JOIN poker_tournament t ON r.tournamentId = t.tournamentId AND r.registerOrder = t.maxPlayers";
        if (isset($limitCount)) {
          $query .= " LIMIT :limitCount";
        }
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':tournamentId', $params[0], PDO::PARAM_INT);
        if (isset($limitCount)) {
          $pdoStatement->bindParam(':limitCount', $limitCount, PDO::PARAM_INT);
        }
        break;
      case "winnersForSeason":
      case "winsForUser":
      case "winsTotalAndAverageForSeasonForUser":
        $query =
        "SELECT u.id, CONCAT(u.first_name, ' ', u.last_name) AS name, IFNULL(wins, 0) AS wins, IFNULL(wins / trnys, 0) AS avg, IFNULL(trnys, 0) AS trnys, u.active " .
        "FROM poker_user u " .
        "LEFT JOIN (SELECT r.playerId, COUNT(*) AS wins, numTourneys AS trnys " .
        "           FROM poker_tournament t " .
        "           INNER JOIN poker_result r ON t.tournamentId = r.tournamentId AND r.place = 1 ";
        if ("winsForUser" != $dataName && isset($params[0]) && isset($params[1])) {
          $query .= "            AND t.tournamentDate BETWEEN :startDate1 AND :endDate1 ";
        }
        $query .=
        "            INNER JOIN (SELECT r.playerId, COUNT(*) AS numTourneys " .
        "                        FROM poker_result r " .
        "                        INNER JOIN poker_tournament t ON r.tournamentId = t.tournamentId AND r.place > 0 ";
        if ("winsForUser" != $dataName && isset($params[0]) && isset($params[1])) {
          $query .= "                        AND t.tournamentDate BETWEEN :startDate2 AND :endDate2 ";
        }
        $query .=
        "                        GROUP BY r.playerId) nt ON r.playerId = nt.playerId " .
        "            GROUP BY r.playerId) a ON u.id = a.playerId AND " . $this->buildUserActive(alias: "u");
        if ("winsTotalAndAverageForSeasonForUser" == $dataName || "winsForUser" == $dataName) {
          $whereClause = "WHERE u.id = :playerId";
          $query .= " WHERE u.id = :playerId";
        } else if ("winnersForSeason" == $dataName) {
          $query .= " WHERE wins > 0";
        }
        if ("winsForUser" != $dataName && "winsTotalAndAverageForSeasonForUser" != $dataName) {
          $query .= " ORDER BY wins DESC, " . $this->buildOrderByName(prefix: NULL);
        }
        if ($rank) {
          if (1 == $orderBy[0]) {
            $orderByFieldName = "wins DESC, " . $this->buildOrderByName(prefix: NULL);
            $selectFieldName = "wins";
          } else {
            $orderByFieldName = "avg DESC, " . $this->buildOrderByName(prefix: NULL);
            $selectFieldName = "avg";
          }
          $selectFieldNames = "id, name, wins, avg, trnys";
          $query = $this->modifyQueryAddRank(query: $query, whereClause: $whereClause, selectFieldName: $selectFieldName, selectFieldNames: $selectFieldNames, orderByFieldName: $orderByFieldName);
        }
        if (isset($limitCount)) {
          $query .= " LIMIT :limitCount";
        }
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        if ("winsForUser" != $dataName && isset($params[0]) && isset($params[1])) {
          $pdoStatement->bindParam(':startDate1', $params[0], PDO::PARAM_STR);
          $pdoStatement->bindParam(':endDate1', $params[1], PDO::PARAM_STR);
          $pdoStatement->bindParam(':startDate2', $params[0], PDO::PARAM_STR);
          $pdoStatement->bindParam(':endDate2', $params[1], PDO::PARAM_STR);
        } else if ("winsTotalAndAverageForSeasonForUser" == $dataName) {
          $pdoStatement->bindParam(':playerId', $params[2], PDO::PARAM_INT);
        }
        if (isset($limitCount)) {
          $pdoStatement->bindParam(':limitCount', $limitCount, PDO::PARAM_INT);
        }
        break;
      case "winnersSelectAll":
      case "winnersSelectAllStats":
        $query =
        "SELECT CONCAT(u.first_name, ' ', u.last_name) AS name, u.active, COUNT(*) AS wins " .
        "FROM poker_result r " .
        "INNER JOIN poker_tournament t ON r.tournamentId = t.tournamentId AND r.place = 1 ";
        if (isset($params[0]) && isset($params[1])) {
          $query .= "AND t.tournamentDate BETWEEN :startDate AND :endDate ";
        }
        $query .=
        "INNER JOIN poker_user u ON r.playerId = u.id " .
        "GROUP BY u.name " .
        "ORDER BY ";
        if ("winnersSelectAll" == $dataName) {
          $query .= "wins DESC, ";
        }
        $query .= $this->buildOrderByName(prefix: NULL);
        if (isset($limitCount)) {
          $query .= " LIMIT :limitCount";
        }
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        if (isset($params[0]) && isset($params[1])) {
          $pdoStatement->bindParam(':startDate', $params[0], PDO::PARAM_STR);
          $pdoStatement->bindParam(':endDate', $params[1], PDO::PARAM_STR);
        }
        if (isset($limitCount)) {
          $pdoStatement->bindParam(':limitCount', $limitCount, PDO::PARAM_INT);
        }
        break;
      case "userPasswordReset":
        $query =
        "SELECT token " .
        "FROM poker_user " .
        "WHERE username = :userName " .
        "AND email = :email";
        if (isset($limitCount)) {
          $query .= " LIMIT :limitCount";
        }
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':userName', $params[0], PDO::PARAM_STR);
        $pdoStatement->bindParam(':email', $params[1], PDO::PARAM_STR);
        if (isset($limitCount)) {
          $pdoStatement->bindParam(':limitCount', $limitCount, PDO::PARAM_INT);
        }
        break;
    }
    if ($returnQuery) {
      if ($this->isDebug()) {
        echo "<br>" . $query;
      }
      return array($pdoStatement, $query);
    } else {
      if (!isset($pdoStatement)) {
        $pdoStatement = $this->getConnection()->prepare(query: $query);
      }
      $pdoStatement->execute();
      if ($this->isDebug()) {
        echo "<br>" . $pdoStatement->debugDumpParams();
      }
      $queryResult = $pdoStatement->fetchAll(PDO::FETCH_BOTH);
      if ($queryResult) {
        $numRecords = count($queryResult);
        $hasRecords = 0 < $numRecords;
        if ($hasRecords) {
          $ctr = 0;
          foreach($queryResult as $row) {
            $resultListForPerson = array();
            switch ($dataName) {
              case "blobTest":
                array_push($resultList, $row["name"]);
                array_push($resultList, $row["contentType"]);
                array_push($resultList, $row["blobcontents"]);
                break;
              case "autoRegisterHost":
                $address = new Address(debug: $this->isDebug(), id: NULL, address: $row["address"], city: $row["city"], state: $row["state"], zip: (int) $row["zipCode"]);
                $user = new User(debug: $this->isDebug(), id: (int) $row["playerId"], name: $row["name"], username: NULL, password: NULL, email: $row["email"], phone: NULL, administrator: 0, registrationDate: NULL, approvalDate: NULL, approvalUserid: NULL, approvalName: NULL, rejectionDate: NULL, rejectionUserid: NULL, rejectionName: NULL, active: 0, address: $address, resetSelector: NULL, resetToken: NULL, resetExpires: NULL, rememberSelector: NULL, rememberToken: NULL, rememberExpires: NULL);
                $location = new Location(debug: $this->isDebug(), id: NULL, name: "", user: $user, count: 0, active: 0, map: NULL, mapName: NULL, tournamentCount: 0);
                $dateTime = new DateTime(debug: $this->isDebug(), id: NULL, time: $row["tournamentDate"]);
                $dateStartTime = new DateTime(debug: $this->isDebug(), id: NULL, time: $row["startTime"]);
                $tournament = new Tournament(debug: $this->isDebug(), id: (int) $row["tournamentId"], description: NULL, comment: NULL, limitType: NULL, gameType: NULL, specialType: NULL, chipCount: 0, location: $location, date: $dateTime, startTime: $dateStartTime, endTime: NULL, buyinAmount: 0, maxPlayers: 0, maxRebuys: 0, rebuyAmount: 0, addonAmount: 0, addonChipCount: 0, groupPayout: NULL, rake: 0, registeredCount: 0, buyinsPaid: 0, rebuysPaid: 0, rebuysCount: 0, addonsPaid: 0, enteredCount: 0);
                array_push($resultList, $tournament);
                break;
              case "bullyForUser":
                array_push($resultList, $row["name"]);
                array_push($resultList, $row["active"]);
                array_push($resultList, $row["numKOs"]);
                break;
              case "championshipByYearByEarnings":
                $object = array();
                array_push($object, (int) $row["yr"]);
                array_push($object, (int) $row["id"]);
                array_push($object, $row["name"]);
                array_push($object, (int) $row["earnings"]);
                array_push($resultList, $object);
                break;
              case "championshipQualifiedPlayers":
                $object = array();
                array_push($object, (int) $row["id"]);
                array_push($object, $row["name"]);
                array_push($object, (int) $row["points"]);
                array_push($object, (int) $row["bonus points"]);
                array_push($object, (int) $row["tourneys"]);
                array_push($object, (int) $row["average points"]);
                array_push($object, (int) $row["playerIdAbsence"]);
                array_push($object, $row["absence status"]);
                array_push($resultList, $object);
                break;
              case "countTournamentForDates":
                $object = array();
                array_push($resultList, (int) $row["cnt"]);
                break;
              case "earningsAverageForSeason":
              case "earningsTotalForChampionship":
              case "earningsTotalForSeason":
              case "earningsTotalAndAverageForSeasonForUser":
              case "earningsTotalAndAverageForUser":
                if ("earningsTotalAndAverageForSeasonForUser" != $dataName && "earningsTotalAndAverageForUser" != $dataName) {
                  array_push($resultList, $row["name"]);
                }
                array_push($resultList, $row["earns"]);
                if ("earningsTotalForChampionship" != $dataName) {
                  array_push($resultList, $row["avg"]);
                  array_push($resultList, $row["trnys"]);
                }
                array_push($resultList, $row["active"]);
                break;
              case "feeDetail":
                array_push($resultList, (int) $row["seasonId"]);
                array_push($resultList, $row["Description"]);
                array_push($resultList, $row["Name"]);
                array_push($resultList, (int) $row["tournamentId"]);
                array_push($resultList, (int) $row["Fee"]);
                array_push($resultList, (int) $row["Paid"]);
                array_push($resultList, (int) $row["Balance"]);
                break;
              case "feeSelectBySeason":
                array_push($resultList, (int) $row["seasonId"]);
                array_push($resultList, $row["Description"]);
                array_push($resultList, $row["Start Date"]);
                array_push($resultList, $row["End Date"]);
                array_push($resultList, (int) $row["Amount"]);
                break;
              case "feeSelectByTournamentIdAndPlayerId":
                $fee = new Fee(debug: $this->isDebug(), seasonId: (int) $row["seasonId"], playerId: (int) $row["id"], amount: (int) $row["amount"], status: $row["status"]);
                array_push($resultList, $fee);
                break;
              case "finishesSelectAllByPlayerId":
                array_push($resultList, (int) $row["place"]);
                array_push($resultList, (int) $row["finishes"]);
                array_push($resultList, (float) $row["avg"]);
                break;
              case "foodByTournamentIdAndPlayerId":
                array_push($resultList, $row["name"]);
                array_push($resultList, $row["email"]);
                array_push($resultList, $row["food"]);
                array_push($resultList, (int) $row["active"]);
                break;
              case "gameTypeSelectAll":
              case "gameTypeSelectOneById":
                $gameType = new GameType(debug: $this->isDebug(), id: $row["id"], name: $row["name"]);
                array_push($resultList, $gameType);
                break;
              case "groupSelectAll":
              case "groupSelectAllById":
                $group = new Group(debug: $this->isDebug(), id: (int) $row["id"], name: $row["name"]);
                array_push($resultList, $group);
                break;
              case "groupPayoutSelectAll":
              case "groupPayoutSelectAllById":
                $group = new Group(debug: $this->isDebug(), id: (int) $row["id"], name: $row["group name"]);
                $aryPayouts = $this->getPayouts((int) $params[0], $dataName == "groupPayoutSelectAllById" ? (int) $params[1] : NULL, $dataName == "groupPayoutSelectAllById" ? true : false);
                $groupPayout = new GroupPayout(debug: $this->isDebug(), id: NULL, group: $group, payouts: $aryPayouts);
                array_push($resultList, $groupPayout);
                break;
              case "groupSelectNameList":
                $values = array($row["groupId"],$row["groupName"]);
                array_push($resultList, $values);
                break;
              case "knockoutsAverageForSeason":
              case "knockoutsTotalForSeason":
              case "knockoutsTotalAndAverageForSeasonForUser":
              case "knockoutsTotalAndAverageForUser":
                array_push($resultList, $row["name"]);
                array_push($resultList, $row["kO"]);
                array_push($resultList, $row["avg"]);
                array_push($resultList, $row["trnys"]);
                array_push($resultList, $row["active"]);
                break;
              case "limitTypeSelectAll":
              case "limitTypeSelectOneById":
                $limitType = new LimitType(debug: $this->isDebug(), id: $row["id"], name: $row["name"]);
                array_push($resultList, $limitType);
                break;
              case "locationSelectAll":
              case "locationSelectById":
                // case "locationSelectAllCount":
                if ("locationSelectById" != $dataName) {
                  $name = $row["host"];
                } else {
                  $name = "";
                }
                $address = new Address(debug: $this->isDebug(), id: NULL, address: $row["address"], city: $row["city"], state: $row["state"], zip: (int) $row["zip"]);
                $user = new User(debug: $this->isDebug(), id: $row["playerId"], name: $name, username: NULL, password: NULL, email: NULL, phone: NULL, administrator: 0, registrationDate: NULL, approvalDate: NULL, approvalUserid: NULL, approvalName: NULL, rejectionDate: NULL, rejectionUserid: NULL, rejectionName: NULL, active: (int) $row["active"], address: $address, resetSelector: NULL, resetToken: NULL, resetExpires: NULL, rememberSelector: NULL, rememberToken: NULL, rememberExpires: NULL);
                $location = new Location(debug: $this->isDebug(), id: $row["id"], name: $row["name"], user: $user, count: 0, active: (int) $row["active"], map: NULL, mapName: NULL, tournamentCount: "locationSelectAll" == $dataName ? (int) $row["trnys"] : 0);
                array_push($resultList, $location);
                break;
              case "login":
                array_push($resultList, $row["password"]);
                break;
              case "nemesisForUser":
                array_push($resultList, $row["name"]);
                array_push($resultList, $row["active"]);
                array_push($resultList, $row["numKOs"]);
                break;
              case "notificationSelectAll":
              case "notificationSelectOneById":
                $startDateTime = new DateTime(debug: $this->isDebug(), id: NULL, time: $row["start date"]);
                $endDateTime = new DateTime(debug: $this->isDebug(), id: NULL, time: $row["end date"]);
                $notification = new Notification(debug: $this->isDebug(), id: (int) $row["id"], description: $row["description"], startDate: $startDateTime, endDate: $endDateTime);
                array_push($resultList, $notification);
                break;
              case "payoutSelectMaxId":
                array_push($resultList, (int) $row["id"]);
                break;
              case "payoutSelectNameList":
                $values = array($row["payoutId"],$row["payoutName"]);
                array_push($resultList, $values);
                break;
              case "pointsAverageForSeason":
              case "pointsTotalForSeason":
              case "pointsTotalAndAverageForSeasonForUser":
              case "pointsTotalAndAverageForUser":
                array_push($resultList, $row["name"]);
                array_push($resultList, $row["pts"]);
                array_push($resultList, $row["avg"]);
                array_push($resultList, $row["trnys"]);
                array_push($resultList, $row["active"]);
                break;
              case "prizePoolForSeason":
                array_push($resultList, $row["total"]);
                break;
              case "registrationList":
                $values = array($row["first_name"],$row["last_name"],$row["food"],$row["fee status"]);
                ;
                array_push($resultList, $values);
                break;
              case "registrationWaitList":
                $values = array($row["id"],$row["name"],$row["email"],$row["maxPlayers"],$row["active"]);
                array_push($resultList, $values);
                break;
              case "resultIdMax":
                array_push($resultList, (int) $row["tournamentId"]);
                break;
              case "resultSelectAll":
              case "resultSelectOneByTournamentIdAndPlayerId":
              case "resultSelectRegisteredByTournamentId":
              case "resultSelectAllFinishedByTournamentId":
              case "resultSelectPaidByTournamentId":
              case "resultSelectPaidNotEnteredByTournamentId":
                $status = new Status(debug: $this->isDebug(), id: NULL, code: $row["statusCode"], name: $row["status"]);
                $tournament = new Tournament(debug: $this->isDebug(), id: (int) $row["tournamentId"], description: NULL, comment: NULL, limitType: NULL, gameType: NULL, specialType: NULL, chipCount: 0, location: NULL, date: NULL, startTime: NULL, endTime: NULL, buyinAmount: 0, maxPlayers: 0, maxRebuys: 0, rebuyAmount: 0, addonAmount: 0, addonChipCount: 0, groupPayout: NULL, rake: 0, registeredCount: 0, buyinsPaid: 0, rebuysPaid: 0, rebuysCount: 0, addonsPaid: 0, enteredCount: 0);
                $user = new User(debug: $this->isDebug(), id: (int) $row["playerId"], name: $row["name"], username: NULL, password: NULL, email: $row["email"], phone: NULL, administrator: 0, registrationDate: NULL, approvalDate: NULL, approvalUserid: NULL, approvalName: NULL, rejectionDate: NULL, rejectionUserid: NULL, rejectionName: NULL, active: (int) $row["active"], address: NULL, resetSelector: NULL, resetToken: NULL, resetExpires: NULL, rememberSelector: NULL, rememberToken: NULL, rememberExpires: NULL);
                if (isset($row["knocked out by"])) {
                  $nameKnockedOutBy = $row["knocked out by"];
                } else {
                  $nameKnockedOutBy = "";
                }
                $knockedOutBy = new User(debug: $this->isDebug(), id: (int) $row["knockedOutBy"], name: $nameKnockedOutBy, username: NULL, password: NULL, email: NULL, phone: NULL, administrator: 0, registrationDate: NULL, approvalDate: NULL, approvalUserid: NULL, approvalName: NULL, rejectionDate: NULL, rejectionUserid: NULL, rejectionName: NULL, active: isset($row["knockedOutActive"]) ? (int) $row["knockedOutActive"] : 0, address: NULL, resetSelector: NULL, resetToken: NULL, resetExpires: NULL, rememberSelector: NULL, rememberToken: NULL, rememberExpires: NULL);
                $buyinPaid = new BooleanString($row["buyinPaid"]);
                $rebuyPaid = new BooleanString($row["rebuyPaid"]);
                $addonPaid = new BooleanString($row["addon"]);
                $addonFlag = new BooleanString($row["addonFlag"]);
                $result = new Result(debug: $this->isDebug(), id: NULL, tournament: $tournament, user: $user, status: $status, registerOrder: (int) $row["registerOrder"], buyinPaid: $buyinPaid->getBoolean(), rebuyPaid: $rebuyPaid->getBoolean(), addonPaid: $addonPaid->getBoolean(), rebuyCount: (int) $row["rebuy"], addonFlag: $addonFlag->getBoolean(), place: (int) $row["place"], knockedOutBy: $knockedOutBy, food: $row["food"], feeStatus: $row["feeStatus"]);
                array_push($resultList, $result);
                break;
              case "resultSelectAllDuring":
                array_push($resultList, (int) $row["place"]);
                break;
              case "resultSelectLastEnteredDuring":
                array_push($resultList, (int) $row["playerId"]);
                array_push($resultList, $row["name"]);
                break;
              case "resultSelectAllByTournamentId":
                array_push($resultList, (int) $row["place"]);
                array_push($resultList, $row["name"]);
                array_push($resultList, $row["rebuy"]);
                array_push($resultList, $row["addon"]);
                array_push($resultList, (float) $row["earnings"]);
                array_push($resultList, (int) $row["total pts"]);
                array_push($resultList, $row["knocked out by"]);
                array_push($resultList, $row["active"]);
                array_push($resultList, $row["knockOutActive"]);
                break;
              case "resultAllOrderedPoints":
                array_push($resultList, $row["name"]);
                array_push($resultList, (int) $row["pts"]);
                array_push($resultList, (int) $row["avg"]);
                array_push($resultList, (int) $row["tourneys"]);
                array_push($resultList, $row["active"]);
                break;
              case "resultAllOrderedEarnings":
                array_push($resultList, $row["name"]);
                array_push($resultList, (int) $row["earnings"]);
                array_push($resultList, (int) $row["max"]);
                array_push($resultList, (int) $row["avg"]);
                array_push($resultList, (int) $row["tourneys"]);
                array_push($resultList, $row["active"]);
                break;
              case "resultAllOrderedKnockouts":
                array_push($resultList, $row["name"]);
                array_push($resultList, (int) $row["knockouts"]);
                array_push($resultList, (int) $row["avg"]);
                array_push($resultList, (int) $row["best"]);
                array_push($resultList, (int) $row["tourneys"]);
                array_push($resultList, $row["active"]);
                break;
              case "resultAllOrderedKnockoutsStats":
                $resultListForPerson = array();
                $resultListForPerson["name"] = $row["name"];
                $resultListForPerson["kOs"] = $row["kOs"];
                $resultListForPerson["avgKo"] = $row["avgKo"];
                $resultListForPerson["bestKo"] = $row["bestKo"];
                $resultListForPerson["tourneys"] = $row["tourneys"];
                $resultListForPerson["active"] = $row["active"];
                $resultList[$row["name"]] = $resultListForPerson;
                break;
              case "resultAllOrderedSummary":
                array_push($resultList, $row["name"]);
                array_push($resultList, (int) $row["#"]);
                array_push($resultList, (int) $row["pts"]);
                array_push($resultList, (int) $row["count"]);
                array_push($resultList, (int) $row["%"]);
                array_push($resultList, (int) $row["avg"]);
                array_push($resultList, (int) $row["best"]);
                array_push($resultList, (int) $row["worst"]);
                array_push($resultList, (int) $row["buyins"]);
                array_push($resultList, (int) $row["rebuys"]);
                array_push($resultList, (int) $row["addons"]);
                array_push($resultList, (int) $row["earnings"]);
                array_push($resultList, (int) $row["net(+/-)"]);
                array_push($resultList, (int) $row["net %"]);
                array_push($resultList, $row["active"]);
                break;
              case "resultAllOrderedSummaryStats":
                $resultListForPerson = array();
                $resultListForPerson["name"] = $row["name"];
                $resultListForPerson["tourneys"] = $row["tourneys"];
                $resultListForPerson["points"] = $row["points"];
                $resultListForPerson["AvgPoints"] = $row["AvgPoints"];
                $resultListForPerson["count"] = $row["count"];
                $resultListForPerson["%"] = $row["%"];
                $resultListForPerson["avg"] = $row["avg"];
                $resultListForPerson["best"] = $row["best"];
                $resultListForPerson["worst"] = $row["worst"];
                $resultListForPerson["buyins"] = $row["buyins"];
                $resultListForPerson["rebuys"] = $row["rebuys"];
                $resultListForPerson["addons"] = $row["addons"];
                $resultListForPerson["earnings"] = $row["earnings"];
                $resultListForPerson["net(+/-)"] = $row["net(+/-)"];
                $resultListForPerson["active"] = $row["active"];
                $resultList[(string) $row["name"]] = $resultListForPerson;
                break;
              case "resultPaidUserCount":
                array_push($resultList, (int) $row["cnt"]);
                break;
              case "seasonActiveCount":
              case "seasonDateCheckCount":
                array_push($resultList, (int) $row["cnt"]);
                break;
              case "seasonStats":
                if ($ctr == 0) {
                  $heading = array();
                  $colCtr = 0;
                  while ($colCtr < $pdoStatement->ColumnCount()) {
                    array_push($heading, $pdoStatement->getColumnMeta($colCtr)["name"]);
                    $colCtr++;
                  }
                  array_push($resultList, $heading);
                }
                $values = array($row["first_name"],$row["last_name"],(int) $row["#"],(int) $row["pts"],(float) $row["AvgPoints"],(int) $row["count"],(float) $row["%"],(float) $row["avg"],
                  (int) $row["best"],(int) $row["worst"],(int) $row["buyins"],(int) $row["rebuys"],(int) $row["addons"],(int) $row["total"],(int) $row["earnings"],(int) $row["net(+/-)"],
                  (int) $row["KOs"],(float) $row["Avg KO"],(int) $row["Most KO"],(int) $row["wins"],(float) $row["AvgWins"]);
                array_push($resultList, $values);
                break;
              case "seasonSelectAll":
              case "seasonSelectOneById":
              case "seasonSelectOneByIdAndDesc":
              case "seasonSelectOneByActive":
                $startDateTime = new DateTime(debug: $this->isDebug(), id: NULL, time: $row["start date"]);
                $endDateTime = new DateTime(debug: $this->isDebug(), id: NULL, time: $row["end date"]);
                $season = new Season(debug: $this->isDebug(), id: $row["id"], description: $row["description"], startDate: $startDateTime, endDate: $endDateTime, championshipQualify: (int) $row["# to qualify"], finalTablePlayers: (int) $row["final table players"], finalTableBonusPoints: (int) $row["final table bonus points"], fee: (int) $row["fee"], active: (int) $row["active"]);
                array_push($resultList, $season);
                break;
              case "seasonSelectAllChampionship":
                $startDateTime = new DateTime(debug: $this->isDebug(), id: NULL, time: $row["seasonStartDate"]);
                $endDateTime = new DateTime(debug: $this->isDebug(), id: NULL, time: $row["seasonEndDate"]);
                $season = new Season(debug: $this->isDebug(), id: $row["id"], description: $row["seasonDescription"], startDate: $startDateTime, endDate: $endDateTime, championshipQualify: (int) $row["# to qualify"], finalTablePlayers: (int) $row["finalTablePlayers"], finalTableBonusPoints: (int) $row["finalTableBonusPoints"], active: $row["seasonActive"]);
                array_push($resultList, $season);
                break;
              case "seasonSelectOneByTournamentId":
                array_push($resultList, (int) $row["seasonId"]);
                break;
              case "seasonSelectMaxId":
                array_push($resultList, (int) $row["id"]);
                break;
              case "specialTypeSelectAll":
              case "specialTypeSelectOneById":
                $specialType = new SpecialType(debug: $this->isDebug(), id: $row["id"], description: $row["description"], multiplier: $row["multiplier"]);
                array_push($resultList, $specialType);
                break;
              case "statusSelectPaid":
                $values = array($row["id"],$row["name"],$row["fee status logic"],$row["fee status"],$row["amount"],$row["amount2"],$row["buyin status"],$row["rebuy status"],$row["rebuyCount"],$row["addon status"]);
                array_push($resultList, $values);
                break;
              case "statusSelectAll":
                $status = new Status(debug: $this->isDebug(), id: NULL, code: (int) $row["statusCode"], name: NULL);
                array_push($resultList, $status);
                break;
              case "structureSelectAll":
              case "structurePayout":
                if ("structureSelectAll" == $dataName) {
                  $structure = new Structure(debug: $this->isDebug(), id: NULL, place: (int) $row["place"], percentage: (float) $row["percentage"]);
                  array_push($resultList, $structure);
                } else if ("structurePayout" == $dataName) {
                  $values = array($row["place"],$row["percentage"],(float) $row["pay"]);
                  array_push($resultList, $values);
                }
                break;
              case "tournamentIdMax":
                array_push($resultList, (int) $row["tournamentId"]);
                break;
              case "tournamentAll":
                $dateTime = new DateTime(debug: $this->isDebug(), id: NULL, time: $row["tournamentDate"]);
                $tournament = new Tournament(debug: $this->isDebug(), id: $row["tournamentId"], description: $row["tournamentDesc"], comment: NULL, limitType: NULL, gameType: NULL, specialType: NULL, chipCount: 0, location: NULL, date: $dateTime, startTime: NULL, endTime: NULL, buyinAmount: 0, maxPlayers: 0, maxRebuys: 0, rebuyAmount: 0, addonAmount: 0, addonChipCount: 0, groupPayout: NULL, rake: 0, registeredCount: 0, buyinsPaid: 0, rebuysPaid: 0, rebuysCount: 0, addonsPaid: 0, enteredCount: 0);
                array_push($resultList, $tournament);
                break;
              case "tournamentSelectAll":
              case "tournamentSelectAllByDateAndStartTime":
              case "tournamentSelectOneById":
              case "tournamentSelectAllForRegistration":
              case "tournamentSelectAllForBuyins":
              case "tournamentSelectAllForChampionship":
              case "tournamentSelectAllOrdered":
              case "tournamentsWonByPlayerId":
                $limitType = new LimitType(debug: $this->isDebug(), id: $row["limitTypeId"], name: $row["limit"]);
                $gameType = new GameType(debug: $this->isDebug(), id: $row["gameTypeId"], name: $row["type"]);
                $specialType = new SpecialType(debug: $this->isDebug(), id: $row["specialTypeId"], description: $row["std"], multiplier: $row["typeMultiplier"]);
                $address = new Address(debug: $this->isDebug(), id: NULL, address: $row["address"], city: $row["city"], state: $row["state"], zip: (int) $row["zipCode"]);
                if ("tournamentsWonByPlayerId" != $dataName) {
                  $name = $row["playerName"];
                } else {
                  $name = "";
                }
                $phone = new Phone(debug: $this->isDebug(), id: NULL, value: $row["phone"]);
                $user = new User(debug: $this->isDebug(), id: $row["playerId"], name: $name, username: NULL, password: NULL, email: NULL, phone: $phone, administrator: 0, registrationDate: NULL, approvalDate: NULL, approvalUserid: NULL, approvalName: NULL, rejectionDate: NULL, rejectionUserid: NULL, rejectionName: NULL, active: 0, address: $address, resetSelector: NULL, resetToken: NULL, resetExpires: NULL, rememberSelector: NULL, rememberToken: NULL, rememberExpires: NULL);
                $location = new Location(debug: $this->isDebug(), id: $row["locationId"], name: $row["location"], user: $user, count: 0, active: 0, map: $row["mapHide"], mapName: $row["map"], tournamentCount: 0);
                $dateTime = new DateTime(debug: $this->isDebug(), id: NULL, time: $row["date"]);
                $dateTimeStart = new DateTime(debug: $this->isDebug(), id: NULL, time: $row["start"]);
                $dateTimeEnd = new DateTime(debug: $this->isDebug(), id: NULL, time: $row["end"]);
                if (! isset($row["std"]) || (isset($row["std"]) && strpos($row["std"], Constant::DESCRIPTION_CHAMPIONSHIP) === false)) {
                  $maxPlayers = (int) $row["max players"];
                } else {
                  $databaseResult = new DatabaseResult(debug: $this->isDebug());
                  $maxPlayers = (int) count($databaseResult->getChampionshipQualifiedPlayers(params: $paramsNested, returnQuery: false));
                }
                if ("tournamentsWonByPlayerId" != $dataName) {
                  $group = new Group($this->isDebug(), $row["groupId"], $row["name"]);
                  $groupPayout = new GroupPayout(debug: $this->isDebug(), id: NULL, group: $group, payouts: $this->getPayouts(groupId: (int) $row["groupId"], payoutId: NULL, structureFlag: true));
                } else {
                  $groupPayout = NULL;
                }
                // $tournament->setDirections($row["map"]);
                if ("tournamentsWonByPlayerId" != $dataName) {
                  $registeredCount = (int) $row["registeredCount"];
                  $buyinsPaid = (int) $row["buyinsPaid"];
                  $rebuysPaid = (int) $row["rebuysPaid"];
                  $rebuysCount = (int) $row["rebuysCount"];
                  $addonsPaid = (int) $row["addonsPaid"];
                  $earnings = 0;
                } else {
                  $registeredCount = 0;
                  $buyinsPaid = 0;
                  $rebuysPaid = 0;
                  $rebuysCount = 0;
                  $addonsPaid = 0;
                  $earnings = (int) $row["earnings"];
                }
                $tournament = new Tournament(debug: $this->isDebug(), id: $row["id"], description: $row["description"], comment: $row["comment"], limitType: $limitType, gameType: $gameType, specialType: $specialType, chipCount: (int) $row["chips"], location: $location, date: $dateTime, startTime: $dateTimeStart, endTime: $dateTimeEnd, buyinAmount: (int) $row["buyin"], maxPlayers: $maxPlayers, maxRebuys: (int) $row["max"], rebuyAmount: (int) $row["amt"], addonAmount: (int) $row["amt "], addonChipCount: (int) $row["chips "], groupPayout: $groupPayout, rake: (float) ($row["rake"] * 100), registeredCount: $registeredCount, buyinsPaid: $buyinsPaid, rebuysPaid: $rebuysPaid, rebuysCount: $rebuysCount, addonsPaid: $addonsPaid, enteredCount: (int) $row["enteredCount"], earnings: $earnings);
                array_push($resultList, $tournament);
                break;
              case "tournamentSelectAllRegistrationStatus":
                array_push($resultList, (int) $row["id"]);
                array_push($resultList, $row["name"]);
                array_push($resultList, $row["status"]);
                array_push($resultList, $row["season fee"]);
                array_push($resultList, (int) $row["order"]);
                array_push($resultList, $row["active"]);
                break;
              case "tournamentSelectAllDuring":
                array_push($resultList, (int) $row["tournamentId"]);
                $dateTime = new DateTime(debug: $this->isDebug(), id: NULL, time: $row["tournamentDate"]);
                array_push($resultList, $dateTime);
                $dateTime = new DateTime(debug: $this->isDebug(), id: NULL, time: $row["startTime"]);
                array_push($resultList, $dateTime);
                array_push($resultList, $row["locationName"]);
                array_push($resultList, (int) $row["buyinsPaid"]);
                break;
              case "tournamentSelectAllYearsPlayed":
                array_push($resultList, (int) $row["year"]);
                break;
              case "tournamentsSelectForEmailNotifications":
                break;
              case "tournamentsPlayedByPlayerIdAndDateRange":
                array_push($resultList, (int) $row["numPlayed"]);
                break;
              case "tournamentsPlayedByType":
                $object = array();
                $limitType = new LimitType(id: (int) $row["limitTypeId"], name: $row["limit type"]);
                array_push($object, $limitType);
                array_push($resultList, $object);
                $gameType = new GameType(id: (int) $row["gameTypeId"], name: $row["game type"]);
                array_push($object, $gameType);
                array_push($resultList, $object);
                array_push($object, (int) $row["count"]);
                array_push($object, (int) $row["rebuys"]);
                break;
              case "tournamentsPlayedFirst":
                $dateTime = new DateTime(debug: $this->isDebug(), id: NULL, time: $row["date"]);
                array_push($resultList, $dateTime);
                break;
              case "tournamentIdList":
                $object = array();
                array_push($resultList, (int) $row["tournamentId"]);
                break;
              case "userAbsencesByTournamentId":
                $values = array($row["playerId"],$row["name"]);
                array_push($resultList, $values);
                break;
              case "userActive":
                $user = new User(debug: $this->isDebug(), id: (int) $row["id"], name: $row["name"], username: NULL, password: NULL, email: $row["email"], phone: NULL, administrator: 0, registrationDate: NULL, approvalDate: NULL, approvalUserid: NULL, approvalName: NULL, rejectionDate: NULL, rejectionUserid: NULL, rejectionName: NULL, active: 1, address: NULL, resetSelector: NULL, resetToken: NULL, resetExpires: NULL, rememberSelector: NULL, rememberToken: NULL, rememberExpires: NULL);
                array_push($resultList, $user);
                break;
              case "userSelectAll":
              case "userSelectOneById":
              case "userSelectOneByName":
              case "userSelectOneByUsername":
              case "userSelectOneByEmail":
              case "userPaidByTournamentId":
                $phone = new Phone(debug: $this->isDebug(), id: NULL, value: $row[5]);
                $user = new User(debug: $this->isDebug(), id: (int) $row["id"], name: $row["name"], username: $row[2], password: $row[3], email: $row[4], phone: $phone, administrator: (int) $row[6], registrationDate: $row[7], approvalDate: $row[8], approvalUserid: (int) $row[9], approvalName: $row[10], rejectionDate: $row[11], rejectionUserid: (int) $row[12], rejectionName: $row[13], active: (int) $row[14], address: NULL, resetSelector: NULL, resetToken: NULL, resetExpires: NULL, rememberSelector: NULL, rememberToken: NULL, rememberExpires: NULL);
                array_push($resultList, $user);
                break;
              case "usersSelectForEmailNotifications":
                $user = new User(debug: $this->isDebug(), id: $row["id"], name: $row["name"], username: NULL, password: NULL, email: $row["email"], phone: NULL, administrator: 0, registrationDate: NULL, approvalDate: NULL, approvalUserid: 0, approvalName: NULL, rejectionDate: NULL, rejectionUserid: 0, rejectionName: NULL, active: 0, address: NULL, resetSelector: NULL, resetToken: NULL, resetExpires: NULL, rememberSelector: NULL, rememberToken: NULL, rememberExpires: NULL);
                array_push($resultList, $user);
                break;
              case "waitListedPlayerByTournamentId":
                array_push($resultList, $row["id"]);
                array_push($resultList, $row["name"]);
                array_push($resultList, $row["email"]);
                array_push($resultList, (int) $row["maxPlayers"]);
                break;
              case "winnersForSeason":
              case "winsForUser":
              case "winsTotalAndAverageForSeasonForUser":
                $object = array();
                array_push($object, (int) $row["id"]);
                array_push($object, $row["name"]);
                array_push($object, (int) $row["wins"]);
                array_push($object, (int) $row["avg"]);
                array_push($object, (int) $row["trnys"]);
                array_push($object, $row["active"]);
                array_push($resultList, $object);
                break;
              case "winnersSelectAll":
                array_push($resultList, $row["name"]);
                array_push($resultList, $row["active"]);
                array_push($resultList, (int) $row["wins"]);
                break;
              case "winnersSelectAllStats":
                $resultListForPerson = array();
                $resultListForPerson["name"] = $row["name"];
                $resultListForPerson["active"] = $row["active"];
                $resultListForPerson["wins"] = $row["wins"];
                $resultList[(string) $row["name"]] = $resultListForPerson;
              case "userPasswordReset":
                array_push($resultList, $row["token"]);
                break;
            }
            $ctr++;
          }
          if ($dataName == "payoutSelectAll" || $dataName == "payoutSelectAllById") {
            $resultList = $this->getPayouts(groupId: NULL, payoutId: $dataName == "payoutSelectAllById" ? (int) $params[0] : NULL, structureFlag: $dataName == "payoutSelectAllById" ? true : false);
          }
        }
      }
      $pdoStatement->closeCursor();
    }
    return $resultList;
  }
  // $groupId is group id
  // $payoutId is payout id
  // $structureFlag is boolean true for structures
  private function getPayouts(int|NULL $groupId, int|NULL $payoutId, bool $structureFlag): array {
    $payouts = array();
    $queryNested =
    "SELECT p.payoutId AS id, p.payoutName AS name, p.minPlayers AS 'min players', p.maxPlayers AS 'max players' " .
    "FROM poker_payout p ";
    if (isset($groupId)) {
      $queryNested .=
      " INNER JOIN poker_group_payout gp ON gp.payoutId = p.payoutId" .
      " WHERE gp.groupId = :groupId";
    }
    if (isset($payoutId)) {
      if (isset($groupId)) {
        $queryNested .= " AND ";
      } else {
        $queryNested .= " WHERE ";
      }
      $queryNested .= "p.payoutId = :payoutId";
    }
    $pdoStatementNested = $this->getConnection()->prepare(query: $queryNested);
    if (isset($payoutId) && isset($groupId)) {
      $pdoStatementNested->bindParam(':groupId', $groupId, PDO::PARAM_INT);
      $pdoStatementNested->bindParam(':payoutId', $payoutId, PDO::PARAM_INT);
    } else if (isset($payoutId)) {
      $pdoStatementNested->bindParam(':payoutId', $payoutId, PDO::PARAM_INT);
    } else if (isset($groupId)) {
      $pdoStatementNested->bindParam(':groupId', $groupId, PDO::PARAM_INT);
    }
    $pdoStatementNested->execute();
    if ($this->isDebug()) {
      echo "<br>" . $pdoStatementNested->debugDumpParams();
    }
    $queryResultNested = $pdoStatementNested->fetchAll(PDO::FETCH_BOTH);
    if ($queryResultNested) {
      $numRecords = count($queryResultNested);
      $hasRecords = 0 < $numRecords;
      if ($hasRecords) {
        $structures = NULL;
        $ctr2 = 0;
        foreach($queryResultNested as $rowNested) {
          if ($structureFlag) {
            $queryNested2 =
            "SELECT s.place, s.percentage " .
            "FROM poker_structure s " .
            "WHERE payoutId = :payoutId";
            $pdoStatementNested2 = $this->getConnection()->prepare(query: $queryNested2);
            $pdoStatementNested2->bindParam(':payoutId', $rowNested["id"], PDO::PARAM_INT);
            $pdoStatementNested2->execute();
            if ($this->isDebug()) {
              echo "<br>" . $pdoStatementNested2->debugDumpParams();
            }
            $queryResultNested2 = $pdoStatementNested2->fetchAll(PDO::FETCH_BOTH);
            if ($queryResultNested2) {
              $numRecords2 = count($queryResultNested2);
              $hasRecords2 = 0 < $numRecords2;
              if ($hasRecords2) {
                $ctr3 = 0;
                $structures = array();
                foreach($queryResultNested2 as $rowNested2) {
                  $structure = new Structure(debug: $this->isDebug(), id: NULL, place: (int) $rowNested2["place"], percentage: (float) $rowNested2["percentage"]);
                  $structures[$ctr3] = $structure;
                  $ctr3++;
                }
              }
            }
            $pdoStatementNested2->closeCursor();
          }
          $payout = new Payout(debug: $this->isDebug(), id: (int) $rowNested["id"], name: $rowNested["name"], minPlayers: (int) $rowNested["min players"], maxPlayers: (int) $rowNested["max players"], structures: $structures);
          $payouts[$ctr2] = $payout;
          $ctr2++;
        }
      }
    }
    $pdoStatementNested->closeCursor();
    return $payouts;
  }
  // $dataName is name of query
  // $params is array of input parameters
  private function deleteData(string $dataName, array $params = NULL): int|array {
    $numRecords = 0;
    try {
      switch ($dataName) {
        case "feeBySeasonDelete":
          $query =
          "DELETE FROM poker_fee " .
          "WHERE seasonId IN (:seasonId)";
          $pdoStatement = $this->getConnection()->prepare(query: $query);
          $pdoStatement->bindParam(':seasonId', $params[0], PDO::PARAM_INT);
          break;
        case "feeBySeasonAndPlayerDelete":
          $query =
          "DELETE FROM poker_fee " .
          "WHERE seasonId IN (:seasonId) " .
          "AND playerId = :playerId " .
          "AND amount = 0";
          $pdoStatement = $this->getConnection()->prepare(query: $query);
          $pdoStatement->bindParam(':seasonId', $params[0], PDO::PARAM_INT);
          $pdoStatement->bindParam(':playerId', $params[1], PDO::PARAM_INT);
          break;
        case "gameTypeDelete":
          $query =
          "DELETE FROM poker_game_type " .
          "WHERE gameTypeId IN (:gameTypeId)";
          $pdoStatement = $this->getConnection()->prepare(query: $query);
          $pdoStatement->bindParam(':gameTypeId', $params[0], PDO::PARAM_INT);
          break;
        case "groupDelete":
          $query =
          "DELETE FROM poker_group " .
          "WHERE groupId IN (:groupId)";
          $pdoStatement = $this->getConnection()->prepare(query: $query);
          $pdoStatement->bindParam(':groupId', $params[0], PDO::PARAM_INT);
          break;
        case "groupPayoutDelete":
          $query =
          "DELETE FROM poker_group_payout " .
          "WHERE groupId IN (:groupId) " .
          "AND payoutId IN (:payoutId)";
          $pdoStatement = $this->getConnection()->prepare(query: $query);
          $pdoStatement->bindParam(':groupId', $params[0], PDO::PARAM_INT);
          $pdoStatement->bindParam(':payoutId', $params[1], PDO::PARAM_INT);
          break;
        case "limitTypeDelete":
          $query =
          "DELETE FROM poker_limit_type " .
          "WHERE limitTypeId IN (:limitTypeId)";
          $pdoStatement = $this->getConnection()->prepare(query: $query);
          $pdoStatement->bindParam(':limitTypeId', $params[0], PDO::PARAM_INT);
          break;
        case "locationDelete":
          $query =
          "DELETE FROM poker_location " .
          "WHERE locationId IN (:locationId)";
          $pdoStatement = $this->getConnection()->prepare(query: $query);
          $pdoStatement->bindParam(':locationId', $params[0], PDO::PARAM_INT);
          break;
        case "notificationDelete":
          $query =
          "DELETE FROM poker_notification " .
          "WHERE id IN (:notificationId)";
          $pdoStatement = $this->getConnection()->prepare(query: $query);
          $pdoStatement->bindParam(':notificationId', $params[0], PDO::PARAM_INT);
          break;
        case "payoutDelete":
          $query =
          "DELETE FROM poker_payout " .
          "WHERE payoutId IN (:payoutId)";
          $pdoStatement = $this->getConnection()->prepare(query: $query);
          $pdoStatement->bindParam(':payoutId', $params[0], PDO::PARAM_INT);
          break;
        case "registrationDelete":
          $query =
          "DELETE FROM poker_result " .
          "WHERE tournamentId = :tournamentId AND playerId = :playerId";
          $pdoStatement = $this->getConnection()->prepare(query: $query);
          $pdoStatement->bindParam(':tournamentId', $params[0], PDO::PARAM_INT);
          $pdoStatement->bindParam(':playerId', $params[1], PDO::PARAM_INT);
          break;
        case "resultDelete":
          $query =
          "DELETE FROM poker_result " .
          "WHERE tournamentId IN (:tournamentId)";
          $pdoStatement = $this->getConnection()->prepare(query: $query);
          $pdoStatement->bindParam(':tournamentId', $params[0], PDO::PARAM_INT);
          break;
        case "seasonDelete":
          $query =
          "DELETE FROM poker_season " .
          "WHERE seasonId IN (:seasonId)";
          $pdoStatement = $this->getConnection()->prepare(query: $query);
          $pdoStatement->bindParam(':seasonId', $params[0], PDO::PARAM_INT);
          break;
        case "specialTypeDelete":
          $query =
          "DELETE FROM poker_special_type " .
          "WHERE typeId IN (:specialTypeId)";
          $pdoStatement = $this->getConnection()->prepare(query: $query);
          $pdoStatement->bindParam(':specialTypeId', $params[0], PDO::PARAM_INT);
          break;
        case "structureDelete":
          $query =
          "DELETE FROM poker_structure " .
          "WHERE payoutId IN (:payoutId)";
          $pdoStatement = $this->getConnection()->prepare(query: $query);
          $pdoStatement->bindParam(':payoutId', $params[0], PDO::PARAM_INT);
          break;
        case "tournamentDelete":
          $query =
          "DELETE FROM poker_tournament " .
          "WHERE tournamentId IN (:tournamentId)";
          $pdoStatement = $this->getConnection()->prepare(query: $query);
          $pdoStatement->bindParam(':tournamentId', $params[0], PDO::PARAM_INT);
          break;
        case "tournamentAbsenceDelete":
          $query =
          "DELETE FROM poker_tournament_absence " .
          "WHERE tournamentId IN (:tournamentId) " .
          "AND playerId = :playerId";
          $pdoStatement = $this->getConnection()->prepare(query: $query);
          $pdoStatement->bindParam(':tournamentId', $params[0], PDO::PARAM_INT);
          $pdoStatement->bindParam(':playerId', $params[1], PDO::PARAM_INT);
          break;
      }
      $pdoStatement->execute();
      if ($this->isDebug()) {
        echo "<br>" . $pdoStatement->debugDumpParams();
      }
      if ($pdoStatement->errorCode() == "00000") {
        $numRecords = $pdoStatement->rowCount();
      } else if ($pdoStatement) {
        $numRecords = $pdoStatement->errorInfo();
      }
    } catch (Exception $e) {
      throw new Exception($e);
    }
    return $numRecords;
  }
  // $dataName is name of query
  // $params is array of input parameters
  private function insertData(string $dataName, array $params = NULL): int|array {
    $numRecords = 0;
    // try {
    switch ($dataName) {
      case "blobInsert":
        $query = "INSERT INTO blobtest(name, contentType, blobcontents) VALUES(:name, :contentType, :blobcontents)";
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':name', $params[0], PDO::PARAM_STR);
        $pdoStatement->bindParam(':contentTypexbo', $params[1], PDO::PARAM_STR);
        $pdoStatement->bindParam(':blobcontents', $params[2], PDO::PARAM_LOB);
        break;
      case "feeInsert":
        $query =
        "INSERT INTO poker_fee(seasonId, playerId, tournamentId, amount) " .
        "VALUES(:seasonId, :playerId, :tournamentId, :amount)";
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':seasonId', $params[0], PDO::PARAM_INT);
        $pdoStatement->bindParam(':playerId', $params[1], PDO::PARAM_INT);
        $pdoStatement->bindParam(':tournamentId', $params[2], PDO::PARAM_INT);
        $pdoStatement->bindParam(':amount', $params[3], PDO::PARAM_INT);
        break;
      case "feeUsersForYearInsert":
        $query =
        "INSERT INTO poker_fee(seasonId, playerId, tournamentId, amount) " .
        "SELECT :seasonId, p.id, :tournamentId, 0 FROM poker_user p";
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':seasonId', $params[0], PDO::PARAM_INT);
        $pdoStatement->bindParam(':tournamentId', $params[1], PDO::PARAM_INT);
        break;
      case "gameTypeInsert":
        $query =
        "INSERT INTO poker_game_type(gameTypeId, gameTypeName) " .
        "SELECT IFNULL(MAX(gameTypeId), 0) + 1, :gameTypeName FROM poker_game_type";
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':gameTypeName', $params[0], PDO::PARAM_STR);
        break;
      case "groupInsert":
        $query =
        "INSERT INTO poker_group(groupId, groupName) " .
        "SELECT IFNULL(MAX(groupId), 0) + 1, :groupName FROM poker_group";
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':groupName', $params[0], PDO::PARAM_STR);
        break;
      case "groupPayoutInsert":
        $query =
        "INSERT INTO poker_group_payout(groupId, payoutId) " .
        "VALUES(:groupId, :payoutId)";
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':groupId', $params[0], PDO::PARAM_INT);
        $pdoStatement->bindParam(':payoutId', $params[1], PDO::PARAM_INT);
        break;
      case "limitTypeInsert":
        $query =
        "INSERT INTO poker_limit_type(limitTypeId, limitTypeName) " .
        "SELECT IFNULL(MAX(limitTypeId), 0) + 1, :limitTypeName FROM poker_limit_type";
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':limitTypeName', $params[0], PDO::PARAM_STR);
        break;
      case "locationInsert":
        $query =
        "INSERT INTO poker_location(locationId, locationName, playerId, address, city, state, zipCode) " .
        "SELECT IFNULL(MAX(locationId), 0) + 1, :locationName, :playerId, :address, :city, UPPER(:state), :zipCode FROM poker_location";
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':locationName', $params[0], PDO::PARAM_STR);
        $pdoStatement->bindParam(':playerId', $params[1], PDO::PARAM_INT);
        $pdoStatement->bindParam(':address', $params[2], PDO::PARAM_STR);
        $pdoStatement->bindParam(':city', $params[3], PDO::PARAM_STR);
        $pdoStatement->bindParam(':state', $params[4], PDO::PARAM_STR);
        $pdoStatement->bindParam(':zipCode', $params[5], PDO::PARAM_INT);
        break;
      case "notificationInsert":
        $query =
        "INSERT INTO poker_notification(id, description, startDate, endDate) " .
        "SELECT IFNULL(MAX(id), 0) + 1, :description, :startDate, :endDate FROM poker_notification";
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':description', $params[0], PDO::PARAM_STR);
        $pdoStatement->bindParam(':startDate', $params[1], PDO::PARAM_STR);
        $pdoStatement->bindParam(':endDate', $params[2], PDO::PARAM_STR);
        break;
      case "payoutInsert":
        $query =
        "INSERT INTO poker_payout(payoutId, payoutName, minPlayers, maxPlayers) " .
        "SELECT IFNULL(MAX(payoutId), 0) + 1, :payoutName, :minPlayers, :maxPlayers FROM poker_payout";
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':payoutName', $params[0], PDO::PARAM_STR);
        $pdoStatement->bindParam(':minPlayers', $params[1], PDO::PARAM_INT);
        $pdoStatement->bindParam(':maxPlayers', $params[2], PDO::PARAM_INT);
        break;
      case "registrationInsert":
        $query =
        "INSERT INTO poker_result(tournamentId, playerId, rebuyCount, statusCode, registerOrder, addonFlag, place, knockedOutBy, food) " .
        "SELECT :tournamentId1, :playerId, 0, :statusCode, IF(MAX(registerOrder) IS NULL, 1, MAX(registerOrder) + 1), :addonFlag, 0, NULL, :food FROM poker_result WHERE tournamentId = :tournamentId2";
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':tournamentId1', $params[0], PDO::PARAM_INT);
        $pdoStatement->bindParam(':playerId', $params[1], PDO::PARAM_INT);
        $pdoStatement->bindValue(':statusCode', Constant::CODE_STATUS_REGISTERED, PDO::PARAM_STR);
        $pdoStatement->bindValue(':addonFlag', Constant::FLAG_NO, PDO::PARAM_STR);
        $pdoStatement->bindParam(':food', $params[2], PDO::PARAM_STR);
        $pdoStatement->bindParam(':tournamentId2', $params[0], PDO::PARAM_INT);
        break;
      case "seasonInsert":
        $query =
        "INSERT INTO poker_season(seasonId, seasonDescription, seasonStartDate, seasonEndDate, seasonChampionshipQualify, seasonFinalTablePlayers, seasonFinalTableBonusPoints, seasonFee, seasonActive) " .
        "SELECT IFNULL(MAX(seasonId), 0) + 1, :seasonDescription, :seasonStartDate, :seasonEndDate, :seasonChampionshipQualify, :seasonFinalTablePlayers, :seasonFinalTableBonusPoints, :seasonFee, :seasonActive FROM poker_season";
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':seasonDescription', $params[0], PDO::PARAM_STR);
        $pdoStatement->bindParam(':seasonStartDate', $params[1], PDO::PARAM_STR);
        $pdoStatement->bindParam(':seasonEndDate', $params[2], PDO::PARAM_STR);
        $pdoStatement->bindParam(':seasonChampionshipQualify', $params[3], PDO::PARAM_INT);
        $pdoStatement->bindParam(':seasonFinalTablePlayers', $params[4], PDO::PARAM_INT);
        $pdoStatement->bindParam(':seasonFinalTableBonusPoints', $params[5], PDO::PARAM_INT);
        $pdoStatement->bindParam(':seasonFee', $params[6], PDO::PARAM_STR);
        $pdoStatement->bindParam(':seasonActive', $params[7], PDO::PARAM_INT);
        break;
      case "specialTypeInsert":
        $query =
        "INSERT INTO poker_special_type(typeId, typeDescription, typeMultiplier) " .
        "SELECT IFNULL(MAX(typeId), 0) + 1, :typeDescription, :typeMultiplier FROM poker_special_type";
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':typeDescription', $params[0], PDO::PARAM_STR);
        $pdoStatement->bindParam(':typeMultiplier', $params[1], PDO::PARAM_INT);
        break;
      case "structureInsert":
        $query =
        "INSERT INTO poker_structure(payoutId, place, percentage) " .
        "VALUES(:payoutId, :place, :percentage)";
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':payoutId', $params[0], PDO::PARAM_INT);
        $pdoStatement->bindParam(':place', $params[1], PDO::PARAM_INT);
        $pdoStatement->bindParam(':percentage', $params[2], PDO::PARAM_STR); // for non integer use string
        break;
      case "tournamentInsert":
        $query =
        "INSERT INTO poker_tournament(tournamentId, tournamentDesc, comment, limitTypeId, gameTypeId, chipCount, locationId, tournamentDate, startTime, endTime, buyinAmount, maxPlayers, maxRebuys, rebuyAmount, addonAmount, addonChipCount, groupId, rake, map, specialTypeId) " .
        "SELECT IFNULL(MAX(tournamentId), 0) + 1, :tournamentDesc, :comment, :limitTypeId, :gameTypeId, :chipCount, :locationId, :tournamentDate, :startTime, :endTime, :buyinAmount, :maxPlayers, :maxRebuys, :rebuyAmount, :addonAmount, :addonChipCount, :groupId, :rake, :map, NULLIF(:specialTypeId, '') FROM poker_tournament";
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':tournamentDesc', $params[0], PDO::PARAM_STR);
        $pdoStatement->bindParam(':comment', $params[1], PDO::PARAM_STR);
        $pdoStatement->bindParam(':limitTypeId', $params[2], PDO::PARAM_INT);
        $pdoStatement->bindParam(':gameTypeId', $params[3], PDO::PARAM_INT);
        $pdoStatement->bindParam(':chipCount', $params[4], PDO::PARAM_INT);
        $pdoStatement->bindParam(':locationId', $params[5], PDO::PARAM_INT);
        $pdoStatement->bindParam(':tournamentDate', $params[6], PDO::PARAM_STR);
        $pdoStatement->bindParam(':startTime', $params[7], PDO::PARAM_STR);
        $pdoStatement->bindParam(':endTime', $params[8], PDO::PARAM_STR);
        $pdoStatement->bindParam(':buyinAmount', $params[9], PDO::PARAM_INT);
        $pdoStatement->bindParam(':maxPlayers', $params[10], PDO::PARAM_INT);
        $pdoStatement->bindParam(':maxRebuys', $params[11], PDO::PARAM_INT);
        $pdoStatement->bindParam(':rebuyAmount', $params[12], PDO::PARAM_INT);
        $pdoStatement->bindParam(':addonAmount', $params[13], PDO::PARAM_INT);
        $pdoStatement->bindParam(':addonChipCount', $params[14], PDO::PARAM_INT);
        $pdoStatement->bindParam(':groupId', $params[15], PDO::PARAM_INT);
        $pdoStatement->bindParam(':rake', $params[16], PDO::PARAM_STR); // for non integer use string
        $pdoStatement->bindParam(':map', $params[17], PDO::PARAM_STR);
        $pdoStatement->bindParam(':specialTypeId', $params[18], PDO::PARAM_STR); // for NULL number use string
        break;
      case "tournamentAbsenceInsert":
        $query =
        "INSERT INTO poker_tournament_absence(tournamentId, playerId) " .
        "VALUES(:tournamentId, :playerId)";
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':tournamentId', $params[0], PDO::PARAM_INT);
        $pdoStatement->bindParam(':playerId', $params[1], PDO::PARAM_INT);
        break;
      case "userInsert":
        $query =
        "INSERT INTO poker_user(id, first_name, last_name, username, password, email, phone, administrator, registration_date, approval_date, approval_userid, rejection_date, rejection_userid, active, selector, token, expires) " .
        "SELECT MAX(id) + 1, :firstName, :lastName, :username, '" . password_hash($params[4], PASSWORD_DEFAULT) . "', :email, :phone, :administrator, IFNULL(:registrationDate, CURRENT_TIMESTAMP), :approvalDate, :approvalUserid, :rejectionDate, :rejectionUserId, :active, :selector, :token, :expires FROM poker_user";
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':firstName', $params[1], PDO::PARAM_STR);
        $pdoStatement->bindParam(':lastName', $params[2], PDO::PARAM_STR);
        $pdoStatement->bindParam(':username', $params[3], PDO::PARAM_STR);
        $pdoStatement->bindParam(':email', $params[5], PDO::PARAM_STR);
        $pdoStatement->bindParam(':phone', $params[6], PDO::PARAM_INT);
        $pdoStatement->bindParam(':administrator', $params[7], PDO::PARAM_INT);
        $pdoStatement->bindParam(':registrationDate', $params[8], PDO::PARAM_STR);
        $pdoStatement->bindParam(':approvalDate', $params[9], PDO::PARAM_STR);
        $pdoStatement->bindParam(':approvalUserid', $params[10], PDO::PARAM_STR);
        $pdoStatement->bindParam(':rejectionDate', $params[11], PDO::PARAM_STR);
        $pdoStatement->bindParam(':rejectionUserId', $params[12], PDO::PARAM_STR);
        $pdoStatement->bindParam(':active', $params[13], PDO::PARAM_INT);
        $pdoStatement->bindParam(':selector', $params[14], PDO::PARAM_STR);
        $pdoStatement->bindParam(':token', $params[15], PDO::PARAM_STR);
        $pdoStatement->bindParam(':expires', $params[16], PDO::PARAM_STR);
        break;
    }
    $pdoStatement->execute();
    if ($this->isDebug()) {
      echo "<br>" . $pdoStatement->debugDumpParams();
    }
    if ($pdoStatement->errorCode() == "00000") {
      $numRecords = $pdoStatement->rowCount();
    } else if ($pdoStatement) {
      $numRecords = $pdoStatement->errorInfo();
    }
    return $numRecords;
  }
  // $dataName is query name
  // $params is array of input parameters
  private function updateData(string $dataName, array $params = NULL): int|array {
    $numRecords = 0;
    try {
      switch ($dataName) {
        case "buyinsUpdate":
          $query =
          "UPDATE poker_result " .
          "SET statusCode = :statusCode, " .
          "    buyinPaid = :buyinPaid, " .
          "    rebuyPaid = :rebuyPaid, " .
          "    addonPaid = :addonPaid, " .
          "    rebuyCount = :rebuyCount " .
          "WHERE tournamentId = :tournamentId " .
          "AND playerId = :playerId";
          $pdoStatement = $this->getConnection()->prepare(query: $query);
          $pdoStatement->bindParam(':statusCode', $params[0], PDO::PARAM_STR);
          $pdoStatement->bindParam(':buyinPaid', $params[1], PDO::PARAM_STR);
          $pdoStatement->bindParam(':rebuyPaid', $params[2], PDO::PARAM_STR);
          $pdoStatement->bindParam(':addonPaid', $params[3], PDO::PARAM_STR);
          $pdoStatement->bindParam(':rebuyCount', $params[4], PDO::PARAM_INT);
          $pdoStatement->bindParam(':tournamentId', $params[5], PDO::PARAM_INT);
          $pdoStatement->bindParam(':playerId', $params[6], PDO::PARAM_INT);
          break;
        case "feesUpdate":
          $query =
          "UPDATE poker_fee " .
          "SET amount = :amount " .
          "WHERE seasonId = :seasonId " .
          "AND playerId = :playerId " .
          "AND tournamentId = :tournamentId";
          $pdoStatement = $this->getConnection()->prepare(query: $query);
          $pdoStatement->bindParam(':amount', $params[0], PDO::PARAM_INT);
          $pdoStatement->bindParam(':seasonId', $params[1], PDO::PARAM_INT);
          $pdoStatement->bindParam(':playerId', $params[2], PDO::PARAM_INT);
          $pdoStatement->bindParam(':tournamentId', $params[3], PDO::PARAM_INT);
          break;
        case "gameTypeUpdate":
          $query =
          "UPDATE poker_game_type " .
          "SET gameTypeName = :gameTypeName " .
          "WHERE gameTypeId = :gameTypeId";
          $pdoStatement = $this->getConnection()->prepare(query: $query);
          $pdoStatement->bindValue(':gameTypeName', trim($params[1]), PDO::PARAM_STR);
          $pdoStatement->bindParam(':gameTypeId', $params[0], PDO::PARAM_INT);
          break;
        case "groupUpdate":
          $query =
          "UPDATE poker_group " .
          "SET groupName = :groupName " .
          "WHERE groupId = :groupId";
          $pdoStatement = $this->getConnection()->prepare(query: $query);
          $pdoStatement->bindParam(':groupName', $params[0], PDO::PARAM_STR);
          $pdoStatement->bindParam(':groupId', $params[1], PDO::PARAM_INT);
          break;
        case "groupPayoutUpdate":
          $query =
          "UPDATE poker_group_payout " .
          "SET groupId = :groupId1, " .
          "    payoutId = :payoutId1 " .
          "WHERE groupId = :groupId2 " .
          "AND payoutId = :payoutId2";
          $pdoStatement = $this->getConnection()->prepare(query: $query);
          $pdoStatement->bindParam(':groupId1', $params[0], PDO::PARAM_INT);
          $pdoStatement->bindParam(':payoutId1', $params[1], PDO::PARAM_INT);
          $pdoStatement->bindParam(':groupId2', $params[2], PDO::PARAM_INT);
          $pdoStatement->bindParam(':payoutId2', $params[3], PDO::PARAM_INT);
          break;
        case "limitTypeUpdate":
          $query =
          "UPDATE poker_limit_type " .
          "SET limitTypeName = :limitTypeName " .
          "WHERE limitTypeId = :limitTypeId";
          $pdoStatement = $this->getConnection()->prepare(query: $query);
          $pdoStatement->bindValue(':limitTypeName', trim($params[1]), PDO::PARAM_STR);
          $pdoStatement->bindParam(':limitTypeId', $params[0], PDO::PARAM_INT);
          break;
        case "locationUpdate":
          $query =
          "UPDATE poker_location " .
          "SET locationName = :locationName, " .
          "    playerId = :playerId, " .
          "    address = :address, " .
          "    city = :city, " .
          "    state = UPPER(:state), " .
          "    zipCode = :zipCode " .
          "WHERE locationId = :locationId";
          $pdoStatement = $this->getConnection()->prepare(query: $query);
          $pdoStatement->bindParam(':locationName', $params[0], PDO::PARAM_STR);
          $pdoStatement->bindParam(':playerId', $params[1], PDO::PARAM_INT);
          $pdoStatement->bindParam(':address', $params[2], PDO::PARAM_STR);
          $pdoStatement->bindParam(':city', $params[3], PDO::PARAM_STR);
          $pdoStatement->bindParam(':state', $params[4], PDO::PARAM_STR);
          $pdoStatement->bindParam(':zipCode', $params[5], PDO::PARAM_INT);
          $pdoStatement->bindParam(':locationId', $params[6], PDO::PARAM_INT);
          break;
        case "notificationUpdate":
          $query =
          "UPDATE poker_notification " .
          "SET description = :description, " .
          "    startDate = :startDate, " .
          "    endDate = :endDate " .
          "WHERE id = :notificationId";
          $pdoStatement = $this->getConnection()->prepare(query: $query);
          $pdoStatement->bindParam(':description', $params[1], PDO::PARAM_STR);
          $pdoStatement->bindParam(':startDate', $params[2], PDO::PARAM_STR);
          $pdoStatement->bindParam(':endDate', $params[3], PDO::PARAM_STR);
          $pdoStatement->bindParam(':notificationId', $params[0], PDO::PARAM_INT);
          break;
        case "payoutUpdate":
          $query =
          "UPDATE poker_payout " .
          "SET payoutName = :payoutName, " .
          "    minPlayers = :minPlayers, " .
          "    maxPlayers = :maxPlayers " .
          "WHERE payoutId = :payoutId";
          $pdoStatement = $this->getConnection()->prepare(query: $query);
          $pdoStatement->bindParam(':payoutName', $params[0], PDO::PARAM_STR);
          $pdoStatement->bindParam(':minPlayers', $params[1], PDO::PARAM_INT);
          $pdoStatement->bindParam(':maxPlayers', $params[2], PDO::PARAM_INT);
          $pdoStatement->bindParam(':payoutId', $params[3], PDO::PARAM_INT);
          break;
        case "registrationUpdate":
          $query =
          "UPDATE poker_result " .
          "SET food = :food " .
          "WHERE tournamentId = :tournamentId " .
          "AND playerId = :playerId";
          $pdoStatement = $this->getConnection()->prepare(query: $query);
          $pdoStatement->bindParam(':food', $params[0], PDO::PARAM_STR);
          $pdoStatement->bindParam(':tournamentId', $params[1], PDO::PARAM_INT);
          $pdoStatement->bindParam(':playerId', $params[2], PDO::PARAM_INT);
          break;
        case "registrationCancelUpdate":
          $query =
          "UPDATE poker_result " .
          "SET registerOrder = registerOrder - 1 " .
          "WHERE tournamentId = :tournamentId " .
          "AND registerOrder > :registerOrder";
          $pdoStatement = $this->getConnection()->prepare(query: $query);
          $pdoStatement->bindParam(':tournamentId', $params[0], PDO::PARAM_INT);
          $pdoStatement->bindParam(':registerOrder', $params[1], PDO::PARAM_INT);
          break;
        case "resultUpdate":
          $query =
          "UPDATE poker_result " .
          "SET " . (isset($params[0]) ? "rebuyCount = :rebuyCount, " : "") .
          (isset($params[1]) ? "rebuyPaid = :rebuyPaid, " : "") .
          (isset($params[2]) ? " addonPaid = :addonPaid, " : "") .
          "statusCode = :statusCode, place = :place, knockedOutBy = :knockedOutBy WHERE tournamentId = :tournamentId";
          if (isset($params[7])) {
            $query .= " AND playerId IN (:playerId)";
          }
          $pdoStatement = $this->getConnection()->prepare(query: $query);
          $pdoStatement->bindParam(':statusCode', $params[3], PDO::PARAM_STR);
          $pdoStatement->bindParam(':place', $params[4], PDO::PARAM_INT);
          $pdoStatement->bindParam(':knockedOutBy', $params[5], PDO::PARAM_INT);
          $pdoStatement->bindParam(':tournamentId', $params[6], PDO::PARAM_INT);
          if (isset($params[0])) {
            $pdoStatement->bindParam(':rebuyCount', $params[0], PDO::PARAM_INT);
          }
          if (isset($params[1])) {
            $pdoStatement->bindParam(':rebuyPaid', $params[1], PDO::PARAM_STR);
          }
          if (isset($params[2])) {
            $pdoStatement->bindParam(':addonPaid', $params[2], PDO::PARAM_STR);
          }
          if (isset($params[7])) {
            $pdoStatement->bindParam(':playerId', $params[7], PDO::PARAM_INT);
          }
          break;
        case "resultUpdateDuring":
          $query =
          "UPDATE poker_result " .
          "SET statusCode = :statusCode, place = :place, knockedOutBy = :knockedOutBy " .
          "WHERE tournamentId = :tournamentId " .
          "AND playerId = :playerId";
          $pdoStatement = $this->getConnection()->prepare(query: $query);
          $pdoStatement->bindParam(':statusCode', $params[0], PDO::PARAM_STR);
          $pdoStatement->bindParam(':place', $params[1], PDO::PARAM_INT);
          $pdoStatement->bindParam(':knockedOutBy', $params[2], PDO::PARAM_INT);
          $pdoStatement->bindParam(':tournamentId', $params[3], PDO::PARAM_INT);
          $pdoStatement->bindParam(':playerId', $params[4], PDO::PARAM_INT);
          break;
        case "resultUpdateByTournamentIdAndPlace":
          $query =
          "UPDATE poker_result " .
          "SET " . (isset($params[0]) ? "rebuyCount = :rebuyCount, " : "") .
          (isset($params[1]) ? "rebuyPaid = :rebuyPaid, " : "") .
          (isset($params[2]) ? " addonPaid = :addonPaid, " : "") .
          "statusCode = :statusCode, place = :place, knockedoutBy = :knockedOutBy " .
          "WHERE tournamentId = :tournamentId";
          $pdoStatement = $this->getConnection()->prepare(query: $query);
          $pdoStatement->bindParam(':statusCode', $params[3], PDO::PARAM_STR);
          $pdoStatement->bindParam(':place', $params[4], PDO::PARAM_INT);
          $pdoStatement->bindParam(':knockedOutBy', $params[5], PDO::PARAM_INT);
          $pdoStatement->bindParam(':tournamentId', $params[6], PDO::PARAM_INT);
          if (isset($params[0])) {
            $pdoStatement->bindParam(':rebuyCount', $params[0], PDO::PARAM_INT);
          }
          if (isset($params[1])) {
            $pdoStatement->bindParam(':rebuyPaid', $params[1], PDO::PARAM_STR);
          }
          if (isset($params[2])) {
            $pdoStatement->bindParam(':addonPaid', $params[2], PDO::PARAM_STR);
          }
          break;
        case "resultUpdateByTournamentId":
          $query =
          "UPDATE poker_result " .
          "SET " . (isset($params[0]) ? "rebuyCount = :rebuyCount" : "") .
          (isset($params[1]) ? (isset($params[0]) ? ", " : "") . "rebuyPaid = :rebuyPaid" : "") .
          (isset($params[2]) ? (isset($params[1]) ? ", " : "") . "addonPaid = :addonPaid" : "") .
          (isset($params[3]) ? (isset($params[2]) ? ", " : "") . "addonFlag = :rebuyPaid" : "") .
          " WHERE tournamentId = :tournamentId";
          $pdoStatement = $this->getConnection()->prepare(query: $query);
          $pdoStatement->bindParam(':tournamentId', $params[4], PDO::PARAM_INT);
          if (isset($params[0])) {
            $pdoStatement->bindParam(':rebuyCount', $params[0], PDO::PARAM_INT);
          }
          if (isset($params[1])) {
            $pdoStatement->bindParam(':rebuyPaid', $params[1], PDO::PARAM_STR);
          }
          if (isset($params[2])) {
            $pdoStatement->bindParam(':addonPaid', $params[2], PDO::PARAM_STR);
          }
          if (isset($params[3])) {
            $pdoStatement->bindParam(':addonFlag', $params[3], PDO::PARAM_STR);
          }
          break;
        case "seasonUpdate":
          $query =
          "UPDATE poker_season " .
          "SET seasonDescription = :seasonDescription, " .
          "    seasonStartDate = :seasonStartDate, " .
          "    seasonEndDate = :seasonEndDate, " .
          "    seasonChampionshipQualify = :seasonChampionshipQualify, " .
          "    seasonFinalTablePlayers = :seasonFinalTablePlayers, " .
          "    seasonFinalTableBonusPoints = :seasonFinalTableBonusPoints, " .
          "    seasonFee = :seasonFee, " .
          "    seasonActive = :seasonActive " .
          "WHERE seasonId = :seasonId";
          $pdoStatement = $this->getConnection()->prepare(query: $query);
          $pdoStatement->bindValue(':seasonDescription', trim($params[1]), PDO::PARAM_STR);
          $pdoStatement->bindParam(':seasonStartDate', $params[2], PDO::PARAM_STR);
          $pdoStatement->bindParam(':seasonEndDate', $params[3], PDO::PARAM_STR);
          $pdoStatement->bindParam(':seasonChampionshipQualify', $params[4], PDO::PARAM_INT);
          $pdoStatement->bindParam(':seasonFinalTablePlayers', $params[5], PDO::PARAM_INT);
          $pdoStatement->bindParam(':seasonFinalTableBonusPoints', $params[6], PDO::PARAM_INT);
          $pdoStatement->bindParam(':seasonFee', $params[7], PDO::PARAM_INT);
          $pdoStatement->bindParam(':seasonActive', $params[8], PDO::PARAM_INT);
          $pdoStatement->bindParam(':seasonId', $params[0], PDO::PARAM_INT);
          break;
        case "specialTypeUpdate":
          $query =
          "UPDATE poker_special_type " .
          "SET typeDescription = :typeDescription, " .
          "    typeMultiplier = :typeMultiplier " .
          "WHERE typeId = :specialTypeId";
          $pdoStatement = $this->getConnection()->prepare(query: $query);
          $pdoStatement->bindValue(':typeDescription', trim($params[1]), PDO::PARAM_STR);
          $pdoStatement->bindParam(':typeMultiplier', $params[2], PDO::PARAM_INT);
          $pdoStatement->bindParam(':specialTypeId', $params[0], PDO::PARAM_INT);
          break;
        case "tournamentUpdate":
          $query =
          "UPDATE poker_tournament " .
          "SET tournamentDesc = :tournamentDesc, comment = :comment, limitTypeId = :limitTypeId, gameTypeId = :gameTypeId, chipCount = :chipCount, locationId = :locationId, " .
          "tournamentDate = :tournamentDate, startTime = :startTime, endTime = :endTime, buyinAmount = :buyinAmount, maxPlayers = :maxPlayers, maxRebuys = :maxRebuys, " .
          "rebuyAmount = :rebuyAmount, addonAmount = :addonAmount, addonChipCount = :addonChipCount, groupId = :groupId, rake = :rake, map = :map, specialTypeId = NULLIF(:specialTypeId, '') " .
          "WHERE tournamentId = :tournamentId";
          $pdoStatement = $this->getConnection()->prepare(query: $query);
          $pdoStatement->bindValue(':tournamentDesc', trim($params[1]), PDO::PARAM_STR);
          $pdoStatement->bindValue(':comment', trim($params[2]), PDO::PARAM_STR);
          $pdoStatement->bindParam(':limitTypeId', $params[3], PDO::PARAM_INT);
          $pdoStatement->bindParam(':gameTypeId', $params[4], PDO::PARAM_INT);
          $pdoStatement->bindParam(':chipCount', $params[5], PDO::PARAM_INT);
          $pdoStatement->bindParam(':locationId', $params[6], PDO::PARAM_INT);
          $pdoStatement->bindParam(':tournamentDate', $params[7], PDO::PARAM_STR);
          $pdoStatement->bindParam(':startTime', $params[8], PDO::PARAM_STR);
          $pdoStatement->bindParam(':endTime', $params[9], PDO::PARAM_STR);
          $pdoStatement->bindParam(':buyinAmount', $params[10], PDO::PARAM_INT);
          $pdoStatement->bindParam(':maxPlayers', $params[11], PDO::PARAM_INT);
          $pdoStatement->bindParam(':maxRebuys', $params[12], PDO::PARAM_INT);
          $pdoStatement->bindParam(':rebuyAmount', $params[13], PDO::PARAM_INT);
          $pdoStatement->bindParam(':addonAmount', $params[14], PDO::PARAM_INT);
          $pdoStatement->bindParam(':addonChipCount', $params[15], PDO::PARAM_INT);
          $pdoStatement->bindParam(':groupId', $params[16], PDO::PARAM_INT);
          $pdoStatement->bindParam(':rake', $params[17], PDO::PARAM_STR); // for non integer use string
          $pdoStatement->bindValue(':map', trim($params[18]), PDO::PARAM_STR);
          $pdoStatement->bindParam(':specialTypeId', $params[19], PDO::PARAM_STR); // for NULL number use string
          $pdoStatement->bindParam(':tournamentId', $params[0], PDO::PARAM_INT);
          break;
        case "userUpdate":
          $validValues = array(0,1);
          $query = "UPDATE poker_user SET";
          if (isset($params[0])) {
            $query .= " id = :playerId";
          }
          if (isset($params[1])) {
            if (isset($params[0])) {
              $query .= ", ";
            }
            $query .= " first_name = :firstName";
          }
          if (isset($params[2])) {
            if (isset($params[0]) || isset($params[1])) {
              $query .= ", ";
            }
            $query .= " last_name = :lastName";
          }
          if (isset($params[3])) {
            if (isset($params[0]) || isset($params[1]) || isset($params[2])) {
              $query .= ", ";
            }
            $query .= " username = :userName";
          }
          if (isset($params[4])) {
            if (isset($params[0]) || isset($params[1]) || isset($params[2]) || isset($params[3])) {
              $query .= ", ";
            }
            $query .= " password = '" . password_hash($params[4], PASSWORD_DEFAULT) . "'";
          }
          if (isset($params[5])) {
            if (isset($params[0]) || isset($params[1]) || isset($params[2]) || isset($params[3]) || isset($params[4])) {
              $query .= ", ";
            }
            $query .= " email = :email";
          }
          if (isset($params[6])) {
            if (isset($params[0]) || isset($params[1]) || isset($params[2]) || isset($params[3]) || isset($params[4]) || isset($params[5])) {
              $query .= ", ";
            }
            $query .= " phone = :phone";
          }
          if (isset($params[7]) && in_array($params[7], $validValues)) {
            if (isset($params[0]) || isset($params[1]) || isset($params[2]) || isset($params[3]) || isset($params[4]) || isset($params[5]) || isset($params[6])) {
              $query .= ", ";
            }
            $query .= " administrator = :administrator";
          }
          if (isset($params[8])) {
            if (isset($params[0]) || isset($params[1]) || isset($params[2]) || isset($params[3]) || isset($params[4]) || isset($params[5]) || isset($params[6]) || isset($params[7])) {
              $query .= ", ";
            }
            $query .= " registration_date = :registrationDate";
          }
          if (isset($params[9])) {
            if (isset($params[0]) || isset($params[1]) || isset($params[2]) || isset($params[3]) || isset($params[4]) || isset($params[5]) || isset($params[6]) || isset($params[7]) ||
              isset($params[8])) {
                $query .= ", ";
              }
              $query .= " approval_date = IF(:approvalDate1 = '', CURRENT_TIMESTAMP, :approvalDate2)";
          }
          if (isset($params[10])) {
            if (isset($params[0]) || isset($params[1]) || isset($params[2]) || isset($params[3]) || isset($params[4]) || isset($params[5]) || isset($params[6]) || isset($params[7]) ||
              isset($params[8]) || isset($params[9])) {
                $query .= ", ";
              }
              $query .= " approval_userid = :approvalUserId";
          }
          if (isset($params[11])) {
            if (isset($params[0]) || isset($params[1]) || isset($params[2]) || isset($params[3]) || isset($params[4]) || isset($params[5]) || isset($params[6]) || isset($params[7]) ||
              isset($params[8]) || isset($params[9]) || isset($params[10])) {
                $query .= ", ";
              }
              $query .= " rejection_date = IF(:rejectionDate1 = '', CURRENT_TIMESTAMP, :rejectionDate2)";
          }
          if (isset($params[12])) {
            if (isset($params[0]) || isset($params[1]) || isset($params[2]) || isset($params[3]) || isset($params[4]) || isset($params[5]) || isset($params[6]) || isset($params[7]) ||
              isset($params[8]) || isset($params[9]) || isset($params[10]) || isset($params[11])) {
                $query .= ", ";
              }
              $query .= " rejection_userid = :rejectionUserId";
          }
          if (isset($params[13]) && in_array($params[13], $validValues)) {
            if (isset($params[0]) || isset($params[1]) || isset($params[2]) || isset($params[3]) || isset($params[4]) || isset($params[5]) || isset($params[6]) || isset($params[7]) ||
              isset($params[8]) || isset($params[9]) || isset($params[10]) || isset($params[11]) || isset($params[12])) {
                $query .= ", ";
              }
              $query .= " active = :active";
          }
          if (isset($params[14])) {
            $query .= " selector = :selector";
          }
          if (isset($params[15])) {
            $query .= " token = :token";
          }
          if (isset($params[16])) {
            $query .= " expires = :expires";
          }
          $query .= " WHERE id = :playerId";
          $pdoStatement = $this->getConnection()->prepare(query: $query);
          if (isset($params[0])) {
            $pdoStatement->bindParam(':playerId', $params[0], PDO::PARAM_INT);
          }
          if (isset($params[1])) {
            $pdoStatement->bindParam(':firstName', $params[1], PDO::PARAM_STR);
          }
          if (isset($params[2])) {
            $pdoStatement->bindParam(':lastName', $params[2], PDO::PARAM_STR);
          }
          if (isset($params[3])) {
            $pdoStatement->bindParam(':userName', $params[3], PDO::PARAM_STR);
          }
          if (isset($params[5])) {
            $pdoStatement->bindParam(':email', $params[5], PDO::PARAM_STR);
          }
          if (isset($params[6])) {
            $pdoStatement->bindParam(':phone', $params[6], PDO::PARAM_INT);
          }
          if (isset($params[7])) {
            $pdoStatement->bindParam(':administrator', $params[7], PDO::PARAM_INT);
          }
          if (isset($params[8])) {
            $pdoStatement->bindParam(':registrationDate', $params[8], PDO::PARAM_STR);
          }
          if (isset($params[9])) {
            $pdoStatement->bindParam(':approvalDate1', $params[9], PDO::PARAM_STR);
            $pdoStatement->bindParam(':approvalDate2', $params[9], PDO::PARAM_STR);
          }
          if (isset($params[10])) {
            $pdoStatement->bindParam(':approvalUserId', $params[10], PDO::PARAM_INT);
          }
          if (isset($params[11])) {
            $pdoStatement->bindParam(':rejectionDate1', $params[11], PDO::PARAM_STR);
            $pdoStatement->bindParam(':rejectionDate2', $params[11], PDO::PARAM_STR);
          }
          if (isset($params[12])) {
            $pdoStatement->bindParam(':rejectionUserId', $params[12], PDO::PARAM_INT);
          }
          if (isset($params[13])) {
            $pdoStatement->bindParam(':active', $params[13], PDO::PARAM_INT);
          }
          if (isset($params[14])) {
            $pdoStatement->bindParam(':selector', $params[14], PDO::PARAM_STR);
          }
          if (isset($params[15])) {
            $pdoStatement->bindParam(':token', $params[15], PDO::PARAM_STR);
          }
          if (isset($params[16])) {
            $pdoStatement->bindParam(':expires', $params[16], PDO::PARAM_STR);
          }
          break;
        case "userUpdateReset":
          $selector = bin2hex(random_bytes(length: 8));
          $token = random_bytes(length: 32);
          $expires = new \DateTime("NOW");
          $expires->add(new \DateInterval("P1D")); // 1 day
          $hash = hash('sha256', $token);
          $query =
          "UPDATE poker_user " .
          "SET selector = :selector, token = :token, expires = :expires " .
          "WHERE username = :userName " .
          "AND email = :email";
          $pdoStatement = $this->getConnection()->prepare(query: $query);
          $pdoStatement->bindParam(':selector', $selector, PDO::PARAM_STR);
          $pdoStatement->bindParam(':userName', $params[0], PDO::PARAM_STR);
          $pdoStatement->bindParam(':email', $params[1], PDO::PARAM_STR);
          $pdoStatement->bindParam(':token', $hash, PDO::PARAM_STR);
          $pdoStatement->bindValue(':expires', $expires->format('U'), PDO::PARAM_STR);
          break;
        case "userUpdateChangePassword":
          $hash = password_hash($params[2], PASSWORD_DEFAULT);
          $query =
          "UPDATE poker_user " .
          "SET password = :password, selector = NULL, token = NULL, expires = NULL " .
          "WHERE username = :userName " .
          "AND email = :email";
          $pdoStatement = $this->getConnection()->prepare(query: $query);
          $pdoStatement->bindParam(':password', $hash, PDO::PARAM_STR);
          $pdoStatement->bindParam(':userName', $params[0], PDO::PARAM_STR);
          $pdoStatement->bindParam(':email', $params[1], PDO::PARAM_STR);
          break;
      }
      $pdoStatement->execute();
      if ($this->isDebug()) {
        echo "<br>" . $pdoStatement->debugDumpParams();
      }
      if ($pdoStatement->errorCode() == "00000") {
        if ($dataName == "userUpdateReset" || $dataName == "userUpdateRememberMe") {
          if (1 == $pdoStatement->rowCount()) {
            $numRecords = array($selector,bin2hex($token),$expires->format('U'));
          } else {
            $numRecords = "More than 1 record found!";
          }
        } else {
          $numRecords = $pdoStatement->rowCount();
        }
      } else if ($pdoStatement) {
        $numRecords = $pdoStatement->errorInfo();
      }
    } catch (Exception $e) {
      throw new Exception($e->getMessage());
    }
    return $numRecords;
  }
  // $prefix is table alias
  private function buildOrderByName(string|NULL $prefix): string {
    $alias = isset($prefix) ? $prefix . "." : "";
    return $alias . "last_name, " . $alias . "first_name";
  }
  // returns array of query and array of parameters to bind
  private function buildChampionship(array $params): array {
    $bindParams = NULL;
    $query =
    "SELECT se.seasonStartDate, YEAR(t.tournamentDate) AS Yr, p.Id, p.first_name, p.last_name, CONCAT(p.first_name, ' ', p.last_name) AS name, " .
    "       qq.total * IFNULL(s.Percentage, 0) AS Earnings, numTourneys AS trnys " .
    "FROM poker_result r " .
    "INNER JOIN poker_user p ON r.PlayerId = p.Id " .
    "INNER JOIN poker_tournament t ON r.TournamentId = t.TournamentId ";
    if (isset($params[0]) && isset($params[1])) {
      $query .= "            AND t.tournamentDate BETWEEN :tournamentStartDate91 AND :tournamentEndDate91 ";
      $bindParams[':tournamentStartDate91'] = $params[0];
      $bindParams[':tournamentEndDate91'] = $params[1];
    }
    $query .=
    "INNER JOIN poker_season se ON t.tournamentDate BETWEEN se.seasonStartDate AND se.seasonEndDate " .
    "INNER JOIN (SELECT seasonStartDate, seasonEndDate, SUM(total) - IF(YEAR(seasonEndDate) = 2008, 150, IF(YEAR(seasonEndDate) = 2007, -291, IF(YEAR(seasonEndDate) = 2006, -824, 0))) AS total " .
    "            FROM (SELECT se2.seasonStartDate, se2.seasonEndDate, t2.TournamentId AS Id, IF(b.Play IS NULL, 0, CONCAT(b.Play, '+', IFNULL(nr.NumRebuys, 0) , 'r', '+', IFNULL(na.NumAddons, 0) , 'a')) AS Play, ((t2.BuyinAmount * t2.Rake) * Play) + ((t2.RebuyAmount * t2.Rake) * IFNULL(nr.NumRebuys, 0) ) + ((t2.AddonAmount * t2.Rake) * IFNULL(na.NumAddons, 0) ) AS Total " .
    "                  FROM poker_tournament t2 " .
    "                  INNER JOIN poker_season se2 ON t2.tournamentDate BETWEEN se2.seasonStartDate AND se2.seasonEndDate " .
    "                  LEFT JOIN (SELECT TournamentId, COUNT(*) AS Play FROM poker_result WHERE buyinPaid = '" . Constant::FLAG_YES .
    "' AND Place > 0 GROUP BY TournamentId) b ON t2.TournamentId = b.TournamentId " .
    "                  LEFT JOIN (SELECT r.TournamentId, SUM(r.rebuyCount) AS NumRebuys FROM poker_result r WHERE r.rebuyPaid = '" . Constant::FLAG_YES .
    "' AND r.RebuyCount > 0 GROUP BY r.TournamentId) nr ON t2.TournamentId = nr.TournamentId " .
    "                  LEFT JOIN (SELECT r.TournamentId, COUNT(*) AS NumAddons FROM poker_result r WHERE r.AddonPaid = '" . Constant::FLAG_YES .
    "' GROUP BY r.TournamentId) na ON t2.TournamentId = na.TournamentId) zz " .
    "            GROUP BY seasonStartDate, seasonEndDate) qq ON qq.seasonStartDate = se.seasonStartDate AND qq.seasonEndDate = se.seasonEndDate " .
    "LEFT JOIN poker_special_type st ON t.specialTypeId = st.typeId " .
    "INNER JOIN (SELECT r1.playerId, COUNT(*) AS NumTourneys FROM poker_result r1 INNER JOIN poker_tournament t1 ON r1.tournamentId = t1.tournamentId AND r1.place > 0 INNER JOIN poker_special_type st1 ON t1.specialTypeId = st1.typeId AND st1.typeDescription = '" . Constant::DESCRIPTION_CHAMPIONSHIP . "' GROUP BY r1.playerId) nt ON r.playerId = nt.playerId " .
    "LEFT JOIN (SELECT a.tournamentId, s1.payoutId, s1.place, s1.percentage " .
    "          FROM (SELECT np.tournamentId, p.payoutId " .
    "                FROM (SELECT r.tournamentId, COUNT(*) AS numPlayers FROM poker_result r WHERE r.place > 0 AND r.statusCode IN ('" . Constant::CODE_STATUS_REGISTERED . "','" . Constant::CODE_STATUS_FINISHED . "') GROUP BY r.tournamentId) np " .
    "                INNER JOIN poker_tournament t on np.tournamentId = t.tournamentId ";
    if (isset($params[0]) && isset($params[1])) {
      $query .= "            AND t.tournamentDate BETWEEN :tournamentStartDate92 AND :tournamentEndDate92 ";
      $bindParams[':tournamentStartDate92'] = $params[0];
      $bindParams[':tournamentEndDate92'] = $params[1];
    }
    $query .=
    "                INNER JOIN poker_group_payout gp ON t.GroupId = gp.GroupId " .
    "                INNER JOIN poker_payout p ON gp.PayoutId = p.PayoutId AND np.numPlayers BETWEEN p.minPlayers AND p.maxPlayers) a " .
    "          INNER JOIN poker_structure s1 ON a.payoutId = s1.payoutId) s ON r.tournamentId = s.tournamentId AND r.place = s.place " .
    "WHERE r.Place > 0 " . "AND st.typeDescription = '" . Constant::DESCRIPTION_CHAMPIONSHIP . "' ";
    return array($query, $bindParams);
  }
  // $alias is table alias
  private function buildUserActive(string|NULL $alias = NULL): string {
    return (isset($alias) ? $alias . "." : "") . "active = " . Constant::FLAG_YES_DATABASE;
  }
  // $query is query to modify
  // $whereClause is where clause to replace in query
  // $selectFieldName is field name used for ranking
  // $selectFieldNames is list of field names to search in
  // $orderByFieldName is order by field name to use when replacing
  private function modifyQueryAddRank(string $query, string $whereClause, string $selectFieldName, string $selectFieldNames, string $orderByFieldName): string {
    if ($this->isDebug()) {
      echo "<br>orig -> " . $query;
    }
    $queryTemp = substr_replace($query, "SELECT ROW_NUMBER() OVER (ORDER BY " . $selectFieldName . " DESC, name) AS row, RANK() OVER (ORDER BY " . $selectFieldName . " DESC) AS rank, " . $selectFieldNames . " FROM (SELECT ", 0, 6);
    $queryTemp = str_replace(search: $whereClause, replace: "ORDER BY " . $selectFieldName . " DESC, last_name, first_name) z ORDER BY row, name", subject: $queryTemp);
    return $queryTemp;
  }
}
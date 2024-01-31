<?php
declare(strict_types = 1);
namespace Poker\Ccp\classes\model;
use PDO;
use PDOException;
use Poker\Ccp\classes\model\Constant;
class DatabaseResult extends Root {
  private const USER_NAME_SERVER = "chipch5_app";
  private const PASSWORD_SERVER = "app_chipch5";
  private const USER_NAME_LOCAL = "root";
  private const PASSWORD_LOCAL = "toor";
  private const PORT = 3306;
  private const DATABASE_NAME  = "chipch5_stats_orm";
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
  public function getBullyForPlayer(array $params): array|string {
    return $this->getData(dataName: "bullyForPlayer", params: $params, orderBy: NULL, returnQuery: true, limitCount: NULL, rank: false);
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
  public function getEarningsTotalAndAverageForSeasonForPlayer($params, $orderBy, $rank): array|string {
    return $this->getData(dataName: "earningsTotalAndAverageForSeasonForPlayer", params: $params, orderBy: $orderBy, returnQuery: true, limitCount: NULL, rank: $rank);
  }
  public function getEarningsTotalAndAverageForPlayer($params, $orderBy, $rank): array|string {
    return $this->getData(dataName: "earningsTotalAndAverageForPlayer", params: $params, orderBy: $orderBy, returnQuery: true, limitCount: NULL, rank: $rank);
  }
  public function getFeeCountByTournamentId(array $params): array|string {
    return $this->getData(dataName: "feeCountByTournamentId", params: $params, orderBy: NULL, returnQuery: false, limitCount: NULL, rank: false);
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
  public function getFinishesForPlayer(array $params): array|string {
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
  public function getKnockoutsTotalAndAverageForSeasonForPlayer($params, $orderBy, $rank): array|string {
    return $this->getData(dataName: "knockoutsTotalAndAverageForSeasonForPlayer", params: $params, orderBy: $orderBy, returnQuery: true, limitCount: NULL, rank: $rank);
  }
  public function getKnockoutsTotalAndAverageForPlayer($params, $orderBy, $rank): array|string {
    return $this->getData(dataName: "knockoutsTotalAndAverageForPlayer", params: $params, orderBy: $orderBy, returnQuery: true, limitCount: NULL, rank: $rank);
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
  public function getNemesisForPlayer(array $params): array|string {
    return $this->getData(dataName: "nemesisForPlayer", params: $params, orderBy: NULL, returnQuery: true, limitCount: NULL, rank: false);
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
  public function getPointsTotalAndAverageForSeasonForPlayer($params, $orderBy, $rank): array|string {
    return $this->getData(dataName: "pointsTotalAndAverageForSeasonForPlayer", params: $params, orderBy: $orderBy, returnQuery: true, limitCount: NULL, rank: $rank);
  }
  public function getPointsTotalAndAverageForPlayer($params, $orderBy, $rank): array|string {
    return $this->getData(dataName: "pointsTotalAndAverageForPlayer", params: $params, orderBy: $orderBy, returnQuery: true, limitCount: NULL, rank: $rank);
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
  public function getResultIdMax(?array $params): array|string {
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
  public function getResultPaidPlayerCount(): array|string {
    return $this->getData(dataName: "resultPaidPlayerCount", params: NULL, orderBy: NULL, returnQuery: false, limitCount: NULL, rank: false);
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
  public function getTournament(array $params, array $paramsNested, ?string $mode): array|string {
    $paramsLocal = isset($params[2]) ? array($params[2]) : NULL;
    return $this->getData(dataName: "tournamentSelectAll", params: $paramsLocal, paramsNested: $paramsNested, orderBy: $params[0], returnQuery: $params[1], limitCount: NULL, rank: false, mode: $mode);
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
  public function getPlayerAbsencesByTournamentId(array $params): array|string {
    return $this->getData(dataName: "playerAbsencesByTournamentId", params: $params, orderBy: NULL, returnQuery: false, limitCount: NULL, rank: false);
  }
  public function getPlayersActive(array $params): array|string {
    return $this->getData(dataName: "playerActive", params: NULL, orderBy: NULL, returnQuery: false, limitCount: NULL, rank: false);
  }
  public function getPlayersAll(array $params): array|string {
    return $this->getData(dataName: "playerSelectAll", params: $params, orderBy: NULL, returnQuery: $params[0], limitCount: NULL, rank: false);
  }
  public function getPlayerById(array $params): array|string {
    return $this->getData(dataName: "playerSelectOneById", params: $params, orderBy: NULL, returnQuery: false, limitCount: NULL, rank: false);
  }
  public function getPlayerByName(array $params): array|string {
    return $this->getData(dataName: "playerSelectOneByName", params: $params, orderBy: NULL, returnQuery: false, limitCount: NULL, rank: false);
  }
  public function getPlayerByUsername(array $params): array|string {
    return $this->getData(dataName: "playerSelectOneByUsername", params: $params, orderBy: NULL, returnQuery: false, limitCount: NULL, rank: false);
  }
  public function getPlayerByEmail(array $params): array|string {
    return $this->getData(dataName: "playerSelectOneByEmail", params: $params, orderBy: NULL, returnQuery: false, limitCount: NULL, rank: false);
  }
  public function getPlayersForEmailNotifications(array $params): array|string {
    return $this->getData(dataName: "playersSelectForEmailNotifications", params: $params, orderBy: NULL, returnQuery: false, limitCount: NULL, rank: false);
  }
  public function getPlayersForApproval(): array|string {
    return $this->getData(dataName: "playersSelectForApproval", params: NULL, orderBy: NULL, returnQuery: true, limitCount: NULL, rank: false);
  }
  public function getPlayerPaidByTournamentId(array $params): array|string {
    return $this->getData(dataName: "playerPaidByTournamentId", params: $params, orderBy: NULL, returnQuery: false, limitCount: NULL, rank: false);
  }
  public function getWaitListedPlayerByTournamentId(array $params): array|string {
    return $this->getData(dataName: "waitListedPlayerByTournamentId", params: $params, orderBy: NULL, returnQuery: false, limitCount: NULL, rank: false);
  }
  public function getWinnersForSeason($params, $returnQuery, $limitCount): array|string {
    return $this->getData(dataName: "winnersForSeason", params: $params, orderBy: NULL, returnQuery: $returnQuery, limitCount: $limitCount, rank: false);
  }
  public function getWinsForPlayer($params, $orderBy, $rank): array|string {
    return $this->getData(dataName: "winsForPlayer", params: $params, orderBy: $orderBy, returnQuery: true, limitCount: NULL, rank: $rank);
  }
  public function getWinsTotalAndAverageForSeasonForPlayer($params, $orderBy, $rank): array|string {
    return $this->getData(dataName: "winsTotalAndAverageForSeasonForPlayer", params: $params, orderBy: $orderBy, returnQuery: true, limitCount: NULL, rank: $rank);
  }
  public function getWinners(array $params): array|string {
    return $this->getData(dataName: "winnersSelectAll", params: $params, orderBy: NULL, returnQuery: true, limitCount: NULL, rank: false);
  }
  public function getPlayerPasswordReset(array $params): array|string {
    return $this->getData(dataName: "playerPasswordReset", params: $params, orderBy: NULL, returnQuery: false, limitCount: NULL, rank: false);
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
  public function insertFeePlayersForYear(array $params): int|array {
    return $this->insertData(dataName: "feePlayersForYearInsert", params: $params);
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
  public function insertPlayer(array $params): int|array {
    return $this->insertData(dataName: "playerInsert", params: $params);
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
  public function updatePlayer(array $params): int|array {
    return $this->updateData(dataName: "playerUpdate", params: $params);
  }
  public function updatePlayerReset(array $params): int|array {
    return $this->updateData(dataName: "playerUpdateReset", params: $params);
  }
  public function updatePlayerChangePassword(array $params): int|array {
    return $this->updateData(dataName: "playerUpdateChangePassword", params: $params);
  }
  public function updatePlayerRememberMe(array $params): int|array {
    return $this->updateData(dataName: "playerUpdateRememberMe", params: $params);
  }
  public function updatePlayerRememberMeClear(array $params): int|array {
    return $this->updateData(dataName: "playerUpdateRememberMeClear", params: $params);
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
  private function getData(string $dataName, ?array $params, ?array $paramsNested = NULL, array|string $orderBy = NULL, bool $returnQuery = false, ?int $limitCount = NULL, bool $rank = false, ?string $mode = NULL): array|string {
    $resultList = array();
    $pdoStatement = NULL;
    switch ($dataName) {
      case "blobTest":
        $query = "SELECT name, content_type, blob_contents FROM blob_test";
        break;
      case "autoRegisterHost":
        $query =
          "SELECT t.tournament_id, t.tournament_date, t.tournament_start_time, l.player_id, l.location_address, l.location_city, l.location_state, l.location_zip_code, CONCAT(p.player_first_name, ' ', p.player_last_name) AS name, p.player_email " .
          "FROM poker_tournaments t " .
          "INNER JOIN poker_locations l ON t.location_id = l.location_id AND t.tournament_date BETWEEN :startDate AND DATE_ADD(:endDate, INTERVAL 14 DAY) " .
          "INNER JOIN poker_players p ON l.player_id = p.player_id AND " . $this->buildPlayerActive(alias: "p") .
          " LEFT JOIN poker_results r ON t.tournament_id = r.tournament_id AND p.player_id = r.player_id " .
          "WHERE r.player_id IS NULL";
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
      case "bullyForPlayer":
        $query =
          "SELECT CONCAT(p.player_first_name, ' ', p.player_last_name) AS name, p.player_active_flag, COUNT(r.player_id) AS kOs " .
          "FROM poker_results r " .
          "INNER JOIN poker_players p ON r.player_id = p.player_id " .
          "WHERE r.player_id_ko = :knockedOutBy " .
          "GROUP BY r.player_id " .
          "ORDER BY kOs DESC, " . $this->buildOrderByName(prefix: "p");
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
          $query .= " yr, player_id, ";
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
          "SELECT p.player_id, CONCAT(p.player_first_name, ' ', p.player_last_name) AS name, " .
          "       SUM((np.numPlayers - r.result_place_finished + 1) * IFNULL(st.special_type_multiplier, 1) + IF(r.result_place_finished BETWEEN 1 AND se.season_final_table_players, se.season_final_table_bonus_points, 0)) AS points, " .
          "       SUM(IF(r.result_place_finished BETWEEN 1 AND se.season_final_table_players, se.season_final_table_bonus_points, 0)) AS 'bonus points', " .
          "       nt.numTourneys AS tourneys, " .
          "       SUM((np.numPlayers - r.result_place_finished + 1) * IFNULL(st.special_type_multiplier, 1) + IF(r.result_place_finished BETWEEN 1 AND se.season_final_table_players, se.season_final_table_bonus_points, 0)) / nt.numTourneys AS 'average points', " .
          "       ta.player_id AS playerIdAbsence, IF(ta.player_id IS NULL, 'Attending', 'Not attending') AS 'absence status' " .
          "FROM poker_players p INNER JOIN poker_results r ON p.player_id = r.player_id AND " . $this->buildPlayerActive(alias: "p") .
          " INNER JOIN poker_tournaments t on r.tournament_id = t.tournament_id AND t.tournament_date BETWEEN :startDate1 AND :endDate1 " .
          "LEFT JOIN poker_special_types st ON t.special_type_Id = st.special_type_Id " .
          "INNER JOIN (SELECT r1.player_id, COUNT(*) AS numTourneys " .
          "            FROM poker_results r1 " .
          "            INNER JOIN poker_tournaments t1 ON r1.tournament_id = t1.tournament_id " .
          "            WHERE r1.result_place_finished > 0 " .
          "            AND t1.tournament_date BETWEEN :startDate2 AND :endDate2 " .
          "            GROUP BY r1.player_id) nt ON r.player_id = nt.player_id " .
          "INNER JOIN (SELECT tournament_id, COUNT(*) AS numPlayers " .
          "            FROM poker_results " .
          "            WHERE result_place_finished > 0 " .
          "            GROUP BY tournament_id) np ON r.tournament_id = np.tournament_id " .
          "INNER JOIN poker_seasons se ON t.tournament_date BETWEEN se.season_start_date AND se.season_end_date " .
          "LEFT JOIN (SELECT t.tournament_id, t.tournament_date FROM poker_tournaments t INNER JOIN poker_special_types st ON t.special_type_id = st.special_type_Id WHERE st.special_type_description = '" . Constant::DESCRIPTION_CHAMPIONSHIP . "') tc ON tc.tournament_date BETWEEN se.season_start_date AND se.season_end_date " .
          "LEFT JOIN poker_tournament_absences ta ON tc.tournament_id = ta.tournament_id AND p.player_id = ta.player_id " .
          "LEFT JOIN poker_results ra ON ta.tournament_id = ra.tournament_id AND ta.player_id = ra.player_id " .
          "WHERE nt.numTourneys >= :numTourneys " .
          "GROUP BY r.player_id " .
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
      case "countTournamentForDates": // 0 is start date, 1 is end date, 2 is player id, 3 is true for result table and false for not
        $query =
          "SELECT COUNT(*) AS cnt " .
          "FROM poker_tournaments t";
        if (isset($params[3]) && $params[3]) {
          $query .= " INNER JOIN poker_results r ON t.tournament_id = r.tournament_id";
        }
        if (isset($params[2])) {
          $query .=
            " WHERE r.player_id = :playerId" .
            " AND r.status_code = '" . Constant::CODE_STATUS_FINISHED . "' AND ";
        } else {
          $query .= " WHERE ";
        }
        $query .= "t.tournament_date BETWEEN :startDate AND :endDate";
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
      case "earningsTotalAndAverageForSeasonForPlayer":
      case "earningsTotalAndAverageForPlayer":
        if ("earningsTotalAndAverageForPlayer" == $dataName) {
          $userId = $params[0];
        } else if ("earningsTotalForSeason" != $dataName && "earningsAverageForSeason" != $dataName && "earningsTotalForChampionship" != $dataName) {
          $userId = $params[2];
        }
        $query = "";
        if ("earningsTotalForChampionship" != $dataName) {
          $query .=
            "SELECT p.player_id, CONCAT(p.player_first_name, ' ', p.player_last_name) AS name, IFNULL(earns, 0) AS earns, IFNULL(earns / trnys, 0) AS avg, IFNULL(trnys, 0) AS trnys, p.player_active_flag " .
            "FROM poker_players p " .
            "LEFT JOIN (SELECT player_id, SUM(totalEarnings) AS earns, numTourneys AS trnys " .
            "           FROM (SELECT player_id, player_first_name, player_last_name, SUM(earnings) AS totalEarnings, MAX(earnings) AS maxEarnings, numTourneys " .
            "                 FROM (SELECT p.player_id, p.player_first_name, p.player_last_name, " .
            "                              ((np.numPlayers * (t.tournament_buyin_amount - (t.tournament_buyin_amount * t.tournament_rake))) + " .
            "                               (IFNULL(nr.numRebuys, 0) * (t.tournament_rebuy_amount - (t.tournament_rebuy_amount * t.tournament_rake))) + " .
            "                               (IFNULL(na.numAddons, 0) * (t.tournament_addon_Amount - (t.tournament_addon_amount * t.tournament_rake)))) * IFNULL(s.structure_percentage, 0) AS Earnings, " .
            "                              nt.NumTourneys " .
            "            FROM poker_players p " .
            "            INNER JOIN poker_results r ON p.player_id = r.player_id " .
            "            INNER JOIN poker_tournaments t ON r.tournament_id = t.tournament_id ";
          if ("earningsTotalAndAverageForPlayer" != $dataName) {
            $query .= "            AND t.tournament_date BETWEEN :startDate1 AND :endDate1 ";
          }
          $query .=
            "            INNER JOIN (SELECT r1.player_id, COUNT(*) AS NumTourneys " .
            "                        FROM poker_results r1 " .
            "                        INNER JOIN poker_tournaments t1 ON r1.tournament_id = t1.tournament_id AND r1.result_place_finished > 0 ";
          if ("earningsTotalAndAverageForPlayer" != $dataName) {
            $query .= "                        AND t1.tournament_date BETWEEN :startDate2 AND :endDate2 ";
          }
          $query .=
            "                        GROUP BY r1.player_id) nt ON r.player_id = nt.player_id " .
            "            INNER JOIN (SELECT r2.tournament_id, COUNT(*) AS numPlayers " .
            "                        FROM poker_results r2 " .
            "                        WHERE r2.result_place_finished > 0 " .
            "                        GROUP BY r2.tournament_id) np ON r.tournament_id = np.tournament_id " .
            "            LEFT JOIN (SELECT r3.tournament_id, SUM(r3.result_rebuy_count) AS numRebuys " .
            "                       FROM poker_results r3 " .
            "                       WHERE r3.result_place_finished > 0 " .
            "                       AND r3.result_rebuy_count > 0 " .
            "                       GROUP BY r3.tournament_id) nr ON r.tournament_id = nr.tournament_id " .
            "            LEFT JOIN (SELECT tournament_id, COUNT(result_paid_addon_flag) AS numAddons " .
            "                       FROM poker_results " .
            "                       WHERE result_paid_addon_flag = '" . Constant::FLAG_YES . "' " .
            "                       GROUP BY tournament_id) na ON r.tournament_id = na.tournament_id " .
            "            LEFT JOIN (SELECT a.tournament_id, s1.payout_id, s1.structure_place, s1.structure_percentage " .
            "                       FROM (SELECT np.tournament_id, p.payout_id " .
            "                             FROM (SELECT r.tournament_id, COUNT(*) AS numPlayers " .
            "                                   FROM poker_results r " .
            "                                   WHERE r.status_code IN ('" . Constant::CODE_STATUS_REGISTERED . "', '" . Constant::CODE_STATUS_FINISHED . "') " .
            "                                   GROUP BY r.tournament_id) np " .
            "                             INNER JOIN poker_tournaments t on np.tournament_id = t.tournament_id " .
            "                             INNER JOIN poker_group_payouts gp ON t.group_id = gp.group_id " .
            "                             INNER JOIN poker_payouts p ON gp.payout_id = p.payout_id AND np.numPlayers BETWEEN p.payout_min_players AND p.payout_max_players) a " .
            "                       INNER JOIN poker_structures s1 ON a.payout_id = s1.payout_id) s ON r.tournament_id = s.tournament_id AND r.result_place_finished = s.structure_place WHERE r.result_place_finished > 0) y " .
            "            GROUP BY player_id ";
            // start remove for season
          if ("earningsTotalForSeason" != $dataName) {
            $query .= "            UNION ";
          }
        } else {
          $query .= "             SELECT player_id, CONCAT(player_first_name, ' ', player_last_name) AS name, IFNULL(totalEarnings, 0) AS earns";
          if ("earningsTotalForChampionship" == $dataName) {
            $query .= ", IFNULL(totalEarnings / trnys, 0) AS avg, IFNULL(trnys, 0) AS trnys ";
          }
          $query .= "             FROM (";
        }
        if ("earningsTotalForSeason" != $dataName) {
          $query .= "            SELECT xx.player_id, xx.player_last_name, xx.player_first_name, SUM(xx.earnings) AS totalEarnings, MAX(xx.earnings) AS maxEarnings, 0";
          if ("earningsTotalForChampionship" == $dataName) {
            $query .= ", numTourneys AS trnys ";
          }
          $query .= "            FROM (SELECT Yr, p.player_id, p.player_first_name, p.player_last_name, ";
          if ("earningsTotalForChampionship" == $dataName) {
            $query .= " numTourneys, ";
          }
          $query .=
            "                        qq.total * IFNULL(s.structure_percentage, 0) AS Earnings " .
            "                 FROM poker_players p " .
            "                 INNER JOIN poker_results r ON p.player_id = r.player_id " .
            "                 INNER JOIN poker_tournaments t ON r.tournament_id = t.tournament_id";
          if ("earningsTotalAndAverageForPlayer" != $dataName && "earningsTotalForChampionship" != $dataName) {
            $query .= "        AND t.tournament_date BETWEEN :startDate3 AND :endDate3 ";
          }
          if ("earningsTotalForChampionship" == $dataName && isset($params[0])) {
            $query .= "                               AND YEAR(t.tournament_date) IN (:tournamentDate1) ";
          }
          $query .=
            "                  INNER JOIN (SELECT Yr, SUM(total) - IF(Yr = 2008, 150, " . // adjust to match Dave W stats
            "                                                       IF(Yr = 2007, -291, " . // adjust to match Dave W stats
            "                                                        IF(Yr = 2006, -824, 0))) " . // adjust to match Dave W stats
            "                                         AS total " .
            "                         FROM (SELECT YEAR(t2.tournament_date) AS Yr, t2.tournament_id AS Id, IFNULL(b.Play, 0) AS Play, " .
            "                                      ((t2.tournament_buyin_amount * t2.tournament_rake) * Play) + " .
            "                                      ((t2.tournament_rebuy_amount * t2.tournament_rake) * IFNULL(nr.numRebuys, 0)) + " .
            "                                      ((t2.tournament_addon_amount * t2.tournament_rake) * IFNULL(na.numAddons, 0)) AS Total " .
            "                               FROM poker_tournaments t2 LEFT JOIN (SELECT tournament_id, COUNT(*) AS Play " .
            "                                                                   FROM poker_results " .
            "                                                                   WHERE result_paid_buyin_flag = '" . Constant::FLAG_YES . "' " .
            "                                                                   AND result_place_finished > 0 " .
            "                                                                   GROUP BY tournament_id) b ON t2.tournament_id = b.tournament_id";
          if ("earningsTotalAndAverageForPlayer" != $dataName && "earningsTotalForChampionship" != $dataName) {
            $query .= "                               AND t2.tournament_date BETWEEN :startDate4 AND :endDate4 ";
          }
          if ("earningsTotalForChampionship" == $dataName && isset($params[0])) {
            $query .= "                               AND YEAR(t2.tournament_date) IN (:tournamentDate2) ";
          }
          $query .=
            "                              LEFT JOIN (SELECT r.tournament_id, SUM(r.result_rebuy_count) AS numRebuys " .
            "                                         FROM poker_results r " .
            "                                         WHERE r.result_paid_rebuy_flag = '" . Constant::FLAG_YES . "' " .
            "                                         AND r.result_rebuy_count > 0 " .
            "                                         GROUP BY r.tournament_id) nr ON t2.tournament_id = nr.tournament_id " .
            "                              LEFT JOIN (SELECT r.tournament_id, COUNT(*) AS numAddons " .
            "                                         FROM poker_results r " .
            "                                         WHERE r.result_paid_addon_flag = '" . Constant::FLAG_YES . "' " .
            "                                         GROUP BY r.tournament_id) na ON t2.tournament_id = na.tournament_id) zz " .
            "                        GROUP BY zz.yr) qq";
          $query .= " ON qq.yr = YEAR(t.tournament_date) ";
          $query .= "                  LEFT JOIN poker_special_types st ON t.special_type_id = st.special_type_id";
          if ("earningsTotalForChampionship" == $dataName) {
            $query .=
              "                  INNER JOIN (SELECT r1.player_id, COUNT(*) AS NumTourneys FROM poker_results r1 INNER JOIN poker_tournaments t1 ON r1.tournament_id = t1.tournament_id AND r1.result_place_finished > 0 INNER JOIN poker_special_types st1 ON t1.special_type_id = st1.special_type_id AND st1.special_type_description = '" . Constant::DESCRIPTION_CHAMPIONSHIP . "' GROUP BY r1.player_id) nt ON r.player_id = nt.player_id ";
          }
          $query .=
            "                  LEFT JOIN (SELECT a.tournament_id, s1.payout_id, s1.structure_place, s1.structure_percentage " .
            "                             FROM (SELECT np.tournament_id, p.payout_id " .
            "                                   FROM (SELECT r.tournament_id, COUNT(*) AS numPlayers " .
            "                                         FROM poker_results r " .
            "                                         WHERE r.status_code IN ('" . Constant::CODE_STATUS_REGISTERED . "','" . Constant::CODE_STATUS_FINISHED . "') " .
            "                                         GROUP BY r.tournament_id) np " .
          "                                   INNER JOIN poker_tournaments t on np.tournament_id = t.tournament_id";
          if ("earningsTotalAndAverageForPlayer" != $dataName && "earningsTotalForChampionship" != $dataName) {
            $query .= "                                    AND t.tournament_date BETWEEN :startDate5 AND :endDate5 ";
          }
          if ("earningsTotalForChampionship" == $dataName && isset($params[0])) {
            $query .= "                               AND YEAR(t.tournament_date) IN (:tournamentDate3) ";
          }
          $query .=
            "                                   INNER JOIN poker_group_payouts gp ON t.group_id = gp.group_id " .
            "                                   INNER JOIN poker_payouts p ON gp.payout_id = p.payout_id AND np.numPlayers BETWEEN p.payout_min_players AND p.payout_max_players) a " .
            "                             INNER JOIN poker_structures s1 ON a.payout_id = s1.payout_id) s ON r.tournament_id = s.tournament_id AND r.result_place_finished = s.structure_place " .
            "                  WHERE r.result_place_finished > 0 " .
            "                  AND st.special_type_description = '" . Constant::DESCRIPTION_CHAMPIONSHIP . "'" .
            "                  GROUP BY player_id, yr) xx ";
        }
        if ("earningsTotalForChampionship" == $dataName) {
          $query .=
            "        GROUP BY xx.player_id, xx.player_last_name, xx.player_first_name " .
            "        ORDER BY totalearnings desc, xx.player_last_name, xx.player_first_name) a";
        } else {
          if ("earningsTotalForSeason" == $dataName) {
            $query .= ") cc ";
          } else {
            $query .= "      GROUP BY xx.player_id, xx.player_last_name, xx.player_first_name) cc ";
          }
          $query .= "GROUP BY player_id) z ON p.player_id = z.player_id ";
          if ("earningsTotalForSeason" != $dataName && "earningsAverageForSeason" != $dataName && "earningsTotalForChampionship" != $dataName) {
            $whereClause = "WHERE p.player_id = " . $userId;
            $query .= " WHERE p.player_id = " . $userId;
          } else {
            $query .= " WHERE " . $this->buildPlayerActive(alias: "p");
          }
          if ("earningsTotalAndAverageForPlayer" != $dataName && "earningsTotalAndAverageForSeasonForPlayer" != $dataName) {
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
            $selectFieldNames = "player_id, name, earns, avg, trnys";
            $query = $this->modifyQueryAddRank(query: $query, whereClause: $whereClause, selectFieldName: $selectFieldName, selectFieldNames: $selectFieldNames, orderByFieldName: $orderByFieldName);
          }
        }
        if (isset($limitCount)) {
          $query .= " LIMIT :limitCount";
        }
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        if ("earningsTotalAndAverageForPlayer" != $dataName && "earningsTotalForChampionship" != $dataName) {
          $pdoStatement->bindParam(':startDate1', $params[0], PDO::PARAM_STR);
          $pdoStatement->bindParam(':endDate1', $params[1], PDO::PARAM_STR);
          $pdoStatement->bindParam(':startDate2', $params[0], PDO::PARAM_STR);
          $pdoStatement->bindParam(':endDate2', $params[1], PDO::PARAM_STR);
          if ("earningsTotalForSeason" != $dataName) {
            $pdoStatement->bindParam(':startDate3', $params[0], PDO::PARAM_STR);
            $pdoStatement->bindParam(':endDate3', $params[1], PDO::PARAM_STR);
            $pdoStatement->bindParam(':startDate4', $params[0], PDO::PARAM_STR);
            $pdoStatement->bindParam(':endDate4', $params[1], PDO::PARAM_STR);
            $pdoStatement->bindParam(':startDate5', $params[0], PDO::PARAM_STR);
            $pdoStatement->bindParam(':endDate5', $params[1], PDO::PARAM_STR);
          }
        } else if ("earningsTotalForChampionship" == $dataName && isset($params[0])) {
          $pdoStatement->bindParam(':tournamentDate1', $params[0], PDO::PARAM_STR);
          $pdoStatement->bindParam(':tournamentDate2', $params[0], PDO::PARAM_STR);
          $pdoStatement->bindParam(':tournamentDate3', $params[0], PDO::PARAM_STR);
        }
        if (isset($limitCount)) {
          $pdoStatement->bindParam(':limitCount', $limitCount, PDO::PARAM_INT);
        }
        break;
      case "feeCountByTournamentId":
        $query =
          "SELECT COUNT(*) AS cnt " .
          "FROM poker_fees " .
          "WHERE tournament_id = :tournamentId";
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':tournamentId', $params[0], PDO::PARAM_INT);
        break;
      case "feeDetail":
        $query =
          "SELECT s.season_id, s.season_description AS Description, CONCAT(p.player_first_name, ' ', p.player_last_name) AS Name, f.tournament_id, s.season_fee AS Fee, IFNULL(f.fee_amount, 0) AS Paid, IF(f.fee_amount IS NULL, s.season_fee, s.season_fee - f.fee_amount) AS Balance " .
          "FROM poker_players p CROSS JOIN poker_seasons s ON " . $this->buildPlayerActive(alias: "p") .
          " LEFT JOIN poker_fees f ON s.season_id = f.season_id AND p.player_id = f.player_id AND f.fee_amount > 0 " .
          "ORDER BY s.season_description, f.player_id";
        break;
      case "feeSelectBySeason":
        $query =
          "SELECT s.season_id, s.season_description AS Description, s.season_start_date AS 'Start Date', s.season_end_date AS 'End Date', SUM(f.fee_amount) AS Amount " .
          "FROM poker_fees f INNER JOIN poker_seasons s ON f.season_id = s.season_id " .
          "GROUP BY s.season_id";
        break;
      case "feeSelectByTournamentIdAndPlayerId":
        $query =
          "SELECT se.season_id, p.player_id, f.fee_amount, " .
          "IFNULL(f.fee_amount, 0) AS fee_amount, IF(se.season_fee IS NULL, '', IF(se.season_fee - IFNULL(f.fee_amount, 0) = 0, 'Paid', CONCAT('Owes $', (se.season_fee - IFNULL(f.fee_amount, 0))))) AS status " .
          "FROM poker_players p INNER JOIN poker_tournaments t ON p.player_id = :playerId AND t.tournament_id = :tournamentId " .
          "INNER JOIN poker_seasons se ON t.tournament_date BETWEEN se.season_start_date AND se.season_end_date " .
          "LEFT JOIN (SELECT season_id, player_id, SUM(fee_amount) AS fee_amount FROM poker_fees GROUP BY season_id, player_id) f ON se.season_id = f.season_id AND p.player_id = f.player_id";
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
          "SELECT a.result_place_finished AS place, IFNULL(b.finishes, 0) AS finishes, IFNULL(b.pct, 0) AS pct " .
          "FROM (SELECT DISTINCT result_place_finished " .
          "      FROM poker_results " .
          "      WHERE result_place_finished > 0 " .
          "      ORDER BY result_place_finished) a " .
          "LEFT JOIN (SELECT r1.result_place_finished, COUNT(*) AS finishes, COUNT(*) / (SELECT COUNT(*) " .
          "                                                              FROM poker_results r2 " .
          "                                                              INNER JOIN poker_tournaments t2 ON r2.tournament_id = t2.tournament_id";
        if (isset($params[1]) && isset($params[2])) {
          $query .= "                                                    AND t2.tournament_date BETWEEN :startDate1 AND :endDate1 ";
        }
        $query .=
          "                                                              WHERE r2.player_id = r1.player_id) AS pct " .
          "           FROM poker_results r1 " .
          "           INNER JOIN poker_tournaments t1 ON r1.tournament_id = t1.tournament_id";
        if (isset($params[1]) && isset($params[2])) {
          $query .= " AND t1.tournament_date BETWEEN :startDate2 AND :endDate2";
        }
        $query .=
          "           AND r1.player_id = :playerId" .
          "           GROUP BY r1.result_place_finished) b ON a.result_place_finished = b.result_place_finished";
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
          "SELECT CONCAT(p.player_first_name, ' ', p.player_last_name) AS name, p.player_email, r.result_registration_food, p.player_active_flag " .
          "FROM poker_players p LEFT JOIN poker_results r ON p.player_id = r.player_id AND r.tournament_id = :tournamentId " .
          "WHERE p.player_id = :playerId";
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
          "SELECT game_type_id AS id, game_type_name AS name " .
          "FROM poker_game_types";
        if ("gameTypeSelectOneById" == $dataName) {
          $query .= " WHERE game_type_id = :typeId";
        } else if ("gameTypeSelectAll" == $dataName) {
          if ($params[1]) {
            $query .= " WHERE game_type_id IN (:typeId)";
          }
        }
        if (isset($limitCount)) {
          $query .= " LIMIT :limitCount";
        }
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        if ("gameTypeSelectOneById" == $dataName) {
          $pdoStatement->bindParam(':typeId', $params[0], PDO::PARAM_INT);
        } else if ("gameTypeSelectAll" == $dataName) {
          if ($params[1]) {
            $pdoStatement->bindParam(':typeId', $params[1], PDO::PARAM_INT);
          }
        }
        break;
      case "groupSelectAll":
      case "groupSelectAllById":
        $query =
          "SELECT group_id AS id, group_name AS name " .
          "FROM poker_groups";
        if ("groupSelectAllById" == $dataName) {
          $query .= " WHERE group_id = :groupId";
        } else if ("groupSelectAll" == $dataName) {
          if (isset($params[0])) {
            $query .= " WHERE group_id IN (:groupId)";
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
          "SELECT gp.group_id AS id, g.group_name AS 'group name', gp.payout_id AS 'payout id', p.payout_name AS 'payout name' " .
          "FROM poker_group_payouts gp INNER JOIN poker_groups g ON gp.group_id = g.group_id " .
          "INNER JOIN poker_payouts p ON gp.payout_id = p.payout_id";
        if ("groupPayoutSelectAllById" == $dataName) {
          $query .= " WHERE gp.group_id = :groupId AND gp.payout_id = :payoutId";
        } else if ("groupPayoutSelectAll" == $dataName) {
          if (isset($params[0]) && isset($params[1])) {
            $query .=
            " WHERE gp.group_id IN (:groupId)" .
            " AND gp.payout_id IN (:payoutId)";
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
          "SELECT group_id, group_name " .
          "FROM poker_groups";
        break;
      case "knockoutsAverageForSeason":
      case "knockoutsTotalForSeason":
      case "knockoutsTotalAndAverageForSeasonForPlayer":
      case "knockoutsTotalAndAverageForPlayer":
        if ("knockoutsTotalAndAverageForPlayer" == $dataName) {
          $userId = $params[0];
        } else if ("knockoutsTotalForSeason" != $dataName && "knockoutsAverageForSeason" != $dataName) {
          $userId = $params[2];
        }
        $query =
          "SELECT p.player_id, CONCAT(p.player_first_name, ' ', p.player_last_name) AS name, IFNULL(kO, 0) AS kO, IFNULL(avg, 0) AS avg, IFNULL(trnys, 0) AS trnys, p.player_active_flag " .
          "FROM poker_players p LEFT JOIN (SELECT k.player_id, k.knockouts AS kO, ROUND(k.knockouts / nt.numTourneys, 2) AS avg, nt.numTourneys AS trnys " .
          "                             FROM (SELECT p.player_id, p.player_first_name, p.player_last_name, COUNT(*) AS knockouts " .
          "                                   FROM poker_tournaments t " .
          "                                   INNER JOIN poker_results r ON t.tournament_id = r.tournament_id ";
        if ("knockoutsTotalAndAverageForPlayer" != $dataName) {
          $query .= "                                   AND t.tournament_date BETWEEN :startDate1 AND :endDate1 ";
        }
        $query .=
          "                                   AND r.result_place_finished > 0 " .
          "                                   INNER JOIN poker_players p ON r.player_id_ko = p.player_id " .
          "                                   GROUP BY r.player_id_ko) k " .
          "INNER JOIN (SELECT r.player_id, COUNT(*) AS numTourneys " .
          "            FROM poker_tournaments t INNER JOIN poker_results r ON t.tournament_id = r.tournament_id AND r.result_place_finished > 0 ";
        if ("knockoutsTotalAndAverageForPlayer" != $dataName) {
          $query .= "   AND t.tournament_date BETWEEN :startDate2 AND :endDate2 ";
        }
        $query .= "    GROUP BY r.player_id) nt ON k.player_id = nt.player_id) a ON p.player_id = a.player_id";
        if ("knockoutsTotalForSeason" != $dataName && "knockoutsAverageForSeason" != $dataName) {
          $whereClause = "WHERE p.player_id = " . $userId;
          $query .= " WHERE p.player_id = " . $userId;
        } else {
          $query .= " WHERE " . $this->buildPlayerActive(alias: "p");
        }
        if ("knockoutsTotalAndAverageForSeasonForPlayer" != $dataName && "knockoutsTotalAndAverageForPlayer" != $dataName) {
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
          $selectFieldNames = "player_id, name, kO, avg, trnys";
          $query = $this->modifyQueryAddRank(query: $query, whereClause: $whereClause, selectFieldName: $selectFieldName, selectFieldNames: $selectFieldNames, orderByFieldName: $orderByFieldName);
        }
        if (isset($limitCount)) {
          $query .= " LIMIT :limitCount";
        }
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        if ("knockoutsTotalAndAverageForPlayer" != $dataName) {
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
          "SELECT limit_type_id AS id, limit_type_name AS name " .
          "FROM poker_limit_types";
        if ("limitTypeSelectOneById" == $dataName) {
          $query .= " WHERE limit_type_id = :typeId";
        } else if ("limitTypeSelectAll" == $dataName) {
          if ($params[1]) {
            $query .= " WHERE limit_type_id IN (:typeId)";
          }
        }
        if (isset($limitCount)) {
          $query .= " LIMIT :limitCount";
        }
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        if ("limitTypeSelectOneById" == $dataName) {
          $pdoStatement->bindParam(':typeId', $params[0], PDO::PARAM_INT);
        } else if ("limitTypeSelectAll" == $dataName) {
          if ($params[1]) {
            $pdoStatement->bindParam(':typeId', $params[1], PDO::PARAM_INT);
          }
        }
        if (isset($limitCount)) {
          $pdoStatement->bindParam(':limitCount', $limitCount, PDO::PARAM_INT);
        }
        break;
      case "locationSelectAll":
        $query =
          "SELECT l.location_id AS id, l.location_name AS name, l.player_id AS playerId, CONCAT(p.player_first_name, ' ', p.player_last_name) AS host, l.location_address, l.location_city, UPPER(l.location_state) AS state, l.location_zip_code AS zip, p.player_active_flag, (SELECT COUNT(*) FROM poker_tournaments t WHERE t.location_id = l.location_id) AS trnys " .
          "FROM poker_locations l INNER JOIN poker_players p ON l.player_id = p.player_id ";
        if ($params[1]) {
          $query .= " AND " . $this->buildPlayerActive(alias: "p");
        }
        if (isset($params[3])) {
          $query .= " WHERE location_id IN (:locationId)";
        }
        if ($params[2]) {
          $query .= " ORDER BY l.location_name";
        }
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        if (isset($params[3])) {
          $pdoStatement->bindParam(':locationId', $params[3], PDO::PARAM_INT);
        }
        break;
      case "locationSelectById":
        $query =
          "SELECT l.location_id AS id, l.location_name AS name, l.player_id AS playerId, l.location_address, l.location_city, UPPER(l.location_state) AS state, l.location_zip_code AS zip, p.player_active_flag " .
          "FROM poker_locations l INNER JOIN poker_players p ON l.player_id = p.player_id " .
          "WHERE l.location_id IN (:locationId)";
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
          "SELECT player_password " .
          "FROM poker_players " .
          "WHERE player_username = :userName " .
          "AND " . $this->buildPlayerActive(alias: NULL);
        if (isset($limitCount)) {
          $query .= " LIMIT :limitCount";
        }
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':userName', $params[0], PDO::PARAM_STR);
        if (isset($limitCount)) {
          $pdoStatement->bindParam(':limitCount', $limitCount, PDO::PARAM_INT);
        }
        break;
      case "nemesisForPlayer":
        $query =
          "SELECT CONCAT(p.player_first_name, ' ', p.player_last_name) AS name, p.player_active_flag, COUNT(r.player_id_ko) AS kOs " .
          "FROM poker_results r INNER JOIN poker_players p ON r.player_id_ko = p.player_id " .
          "WHERE r.player_id = :playerId " .
          "GROUP BY r.player_id_ko " .
          "ORDER BY kOs DESC, " . $this->buildOrderByName(prefix: "p");
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
          "SELECT notification_id AS Id, notification_description AS Description, notification_start_date AS 'start date', notification_end_date AS 'end date' " .
          "FROM poker_notifications ";
        if ("notificationSelectOneById" == $dataName) {
          $query .= " WHERE notification_id = :playerId";
        } else if ("notificationSelectAll" == $dataName) {
          if (isset($params[0])) {
            $query .= " WHERE :tournamentDate BETWEEN notification_start_date AND notification_end_date ";
          }
          if (isset($params[1])) {
            $query .= isset($params[0]) ? " AND" : " WHERE" . " notification_id IN (:notificationId)";
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
          "SELECT p.payout_id AS id, p.payout_name AS name, p.payout_min_players AS 'min players', p.payout_max_players AS 'max players', s.structure_place AS place, s.structure_percentage AS percentage " .
          "FROM poker_payouts p INNER JOIN poker_structures s ON p.payout_id = s.payout_id";
        if ("payoutSelectAllById" == $dataName) {
          $query .= " WHERE p.payout_id = :payoutId";
        } else if ("payoutSelectAll" == $dataName) {
          if (isset($params[0])) {
            $query .= " WHERE p.payout_id IN (:payoutId)";
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
          "SELECT MAX(payout_id) AS id " .
          "FROM poker_payouts";
        break;
      case "payoutSelectNameList":
        $query =
          "SELECT payout_id, payout_name " .
          "FROM poker_payouts";
        break;
      case "pointsAverageForSeason":
      case "pointsTotalForSeason":
      case "pointsTotalAndAverageForSeasonForPlayer":
      case "pointsTotalAndAverageForPlayer":
        if ("pointsTotalAndAverageForPlayer" == $dataName) {
          $userId = $params[0];
        } else if ("pointsTotalForSeason" != $dataName && "pointsAverageForSeason" != $dataName) {
          $userId = $params[2];
        }
        $query =
          "SELECT p.player_id, CONCAT(p.player_first_name, ' ', p.player_last_name) AS name, IFNULL(a.points, 0) AS pts, IFNULL(ROUND(a.points / a.trnys, 2), 0) AS avg, IFNULL(a.trnys, 0) AS trnys, p.player_active_flag " .
          "FROM poker_players p LEFT JOIN (SELECT p.player_id, SUM((np.numPlayers - r.result_place_finished + 1) * IFNULL(st.special_type_multiplier, 1) + IF(r.result_place_finished BETWEEN 1 AND se.season_final_table_players, se.season_final_table_bonus_points, 0)) AS points, nt.trnys " .
          "                            FROM poker_players p INNER JOIN poker_results r ON p.player_id = r.player_id " .
          "                            INNER JOIN poker_tournaments t ON r.tournament_id = t.tournament_id ";
        if ("pointsTotalAndAverageForPlayer" != $dataName) {
          $query .= "                             AND t.tournament_date BETWEEN :startDate1 AND :endDate1 ";
        }
        $query .=
          "                            INNER JOIN poker_seasons se ON t.tournament_date BETWEEN se.season_start_date AND se.season_end_date " .
          "                            LEFT JOIN poker_special_types st ON t.special_type_id = st.special_type_id" .
          "                            INNER JOIN (SELECT r1.player_id, COUNT(*) AS trnys " .
          "                                        FROM poker_results r1 " .
          "                                        INNER JOIN poker_tournaments t1 ON r1.tournament_id = t1.tournament_id AND r1.result_place_finished > 0 ";
        if ("pointsTotalAndAverageForPlayer" != $dataName) {
          $query .= "                                         AND t1.tournament_date BETWEEN :startDate2 AND :endDate2 ";
        }
        $query .=
          "                                        GROUP BY r1.player_id) nt ON r.player_id = nt.player_id " .
          "                            INNER JOIN (SELECT tournament_id, COUNT(*) AS numPlayers " .
          "                                        FROM poker_results " .
          "                                        WHERE result_place_finished > 0 " .
          "                                        GROUP BY tournament_id) np ON r.tournament_id = np.tournament_id ";
        if ("pointsTotalForSeason" == $dataName) {
          $query .= " WHERE st.special_type_description IS NULL OR st.special_type_description <> '" . Constant::DESCRIPTION_CHAMPIONSHIP ."'";
        }
        $query .= "GROUP BY r.player_id) a ON p.player_id = a.player_id";
        if ("pointsTotalForSeason" != $dataName && "pointsAverageForSeason" != $dataName) {
          $whereClause = "WHERE p.player_id = " . $userId;
          $query .= " WHERE p.player_id = " . $userId;
        } else {
          $query .= " WHERE " . $this->buildPlayerActive(alias: "p");
        }
        if ("pointsTotalAndAverageForPlayer" != $dataName && "pointsTotalAndAverageForSeasonForPlayer" != $dataName) {
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
          $selectFieldNames = "player_id, name, pts, avg, trnys";
          $query = $this->modifyQueryAddRank(query: $query, whereClause: $whereClause, selectFieldName: $selectFieldName, selectFieldNames: $selectFieldNames, orderByFieldName: $orderByFieldName);
        }
        if (isset($limitCount)) {
          $query .= " LIMIT :limitCount";
        }
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        if ("pointsTotalAndAverageForPlayer" != $dataName) {
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
          "FROM (SELECT t.tournament_id AS Id, np.play, " .
          "             ((t.tournament_buyin_amount * t.tournament_rake) * np.play) + " .
          "             ((t.tournament_rebuy_amount * t.tournament_rake) * IFNULL(nr.numRebuys, 0)) + " .
          "             ((t.tournament_addon_amount * t.tournament_rake) * IFNULL(na.numAddons, 0)) AS total " .
          "      FROM poker_tournaments t " .
          "      LEFT JOIN (SELECT tournament_id, COUNT(*) AS play " .
          "                 FROM poker_results " .
          "                 WHERE result_paid_buyin_flag = '" . Constant::FLAG_YES . "' " .
          "                 AND result_place_finished > 0 " .
          "                 GROUP BY tournament_id) np ON t.tournament_id = np.tournament_id " .
          "      LEFT JOIN (SELECT tournament_id, SUM(result_rebuy_count) AS numRebuys " .
          "                 FROM poker_results " .
          "                 WHERE result_paid_rebuy_flag = '" . Constant::FLAG_YES . "' " .
          "                 AND result_rebuy_count > 0 " .
          "                 GROUP BY tournament_id) nr ON t.tournament_id = nr.tournament_id " .
          "      LEFT JOIN (SELECT tournament_id, COUNT(*) AS numAddons " .
          "                 FROM poker_results " .
          "                 WHERE result_paid_addon_flag = '" . Constant::FLAG_YES . "' " .
          "                 GROUP BY tournament_id) na ON t.tournament_id = na.tournament_id " .
          "      WHERE t.tournament_date BETWEEN :startDate AND :endDate) zz";
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
          "SELECT p.player_first_name, p.player_last_name, r.result_registration_food, IF(s.season_fee - f.fee_amount = 0, 'Paid', CONCAT('Owes ', s.season_fee - f.fee_amount)) AS 'fee status' " .
          "FROM (SELECT t1.tournament_id, t1.tournament_date " .
          "      FROM poker_tournaments t1 INNER JOIN (SELECT tournament_date, MIN(tournament_start_time) startTimeMin, MAX(tournament_start_time) startTimeMax " .
          "                                           FROM poker_tournaments " .
          "                                           WHERE tournament_date = :tournamentDate) t2 " .
          "      ON t1.tournament_date = t2.tournament_date " .
          "      AND t1.tournament_start_time = startTime" . ($params[1] ? "Max" : "Min") . ") t INNER JOIN poker_results r ON t.tournament_id = r.tournament_id " .
          "INNER JOIN poker_players p ON r.player_id = p.player_id " .
          "INNER JOIN poker_seasons s ON t.tournament_date BETWEEN s.season_start_date AND s.season_end_date " .
          "INNER JOIN (SELECT season_id, player_id, SUM(fee_amount) AS fee_amount FROM poker_fees GROUP BY season_id, player_id) f ON s.season_id = f.season_id AND p.player_id = f.player_id " .
          "ORDER BY r.result_registration_order";
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
          "SELECT p.player_id, CONCAT(p.player_first_name, ' ', p.player_last_name) AS name, p.player_email, t.tournament_max_players, p.player_active_flag " .
          "FROM poker_players p " .
          "INNER JOIN poker_results r ON p.player_id = r.player_id " .
          "INNER JOIN poker_tournaments t ON r.tournament_id = t.tournament_id AND r.tournament_id = :tournamentId AND r.result_registration_order > :registerOrder AND r.result_registration_order > t.tournament_max_players " .
          "ORDER BY r.result_registration_order";
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
          "SELECT MAX(r.tournament_id) AS tournamentId " .
          "FROM poker_tournaments t INNER JOIN poker_results r ON t.tournament_id = r.tournament_id " .
          "WHERE tournament_date <= CURRENT_DATE";
        break;
      case "resultSelectAll":
      case "resultSelectOneByTournamentIdAndPlayerId":
      case "resultSelectRegisteredByTournamentId":
      case "resultSelectAllFinishedByTournamentId":
      case "resultSelectPaidByTournamentId":
      case "resultSelectPaidNotEnteredByTournamentId":
        $query = "SELECT r.tournament_id, r.player_id, CONCAT(p.player_first_name, ' ' , p.player_last_name) AS name, p.player_email, r.status_code, s.status_code_name AS status, r.result_registration_order, r.result_paid_buyin_flag, r.result_paid_rebuy_flag, r.result_rebuy_count AS rebuy, r.result_paid_addon_flag AS addon, r.result_addon_flag, r.result_place_finished AS place, r.player_id_ko, CONCAT(p2.player_first_name, ' ' , p2.player_last_name) AS 'knocked out by', r.result_registration_food, p.player_active_flag, p2.player_active_flag AS knockedOutActive, " .
          "IF(se.season_fee IS NULL OR f.fee_amount IS NULL, '', IF(se.season_fee - f.fee_amount = 0, 'Paid', CONCAT('Owes $', (se.season_fee - f.fee_amount)))) AS feeStatus " .
          "FROM poker_results r INNER JOIN poker_players p ON r.player_id = p.player_id " .
          "LEFT JOIN poker_players p2 ON r.player_id_ko = p2.player_id " .
          "INNER JOIN poker_status_codes s ON r.status_code = s.status_code " .
          "INNER JOIN poker_tournaments t ON r.tournament_id = t.tournament_id " .
          "INNER JOIN poker_seasons se ON t.tournament_date BETWEEN se.season_start_date AND se.season_end_date " .
          "INNER JOIN (SELECT season_id, player_id, SUM(fee_amount) AS fee_amount FROM poker_fees GROUP BY season_id, player_id) f ON se.season_id = f.season_id AND p.player_id = f.player_id";
        if ("resultSelectOneByTournamentIdAndPlayerId" == $dataName) {
          $query .=
            " WHERE r.tournament_id = :tournamentId" .
            " AND r.player_id = :playerId";
        } else if ("resultSelectRegisteredByTournamentId" == $dataName) {
          $query .=
            " WHERE r.tournament_id = :tournamentId" .
            " AND r.status_code IN ('" . Constant::CODE_STATUS_REGISTERED . "', '" . Constant::CODE_STATUS_PAID . "')" .
            " AND r.result_place_finished = 0" .
            " ORDER BY r.result_registration_order";
        } else if ("resultSelectAllFinishedByTournamentId" == $dataName) {
          $query .=
            " WHERE r.tournament_id = :tournamentId" .
            " AND r.result_place_finished <> 0" .
            " ORDER BY r.result_place_finished DESC";
        } else if ("resultSelectPaidByTournamentId" == $dataName || "resultSelectPaidNotEnteredByTournamentId" == $dataName) {
          $query .=
            " WHERE r.tournament_id = :tournamentId" .
            " AND r.result_paid_buyin_flag = '" . Constant::FLAG_YES . "'";
          if ("resultSelectPaidNotEnteredByTournamentId" == $dataName) {
            $query .= " AND result_place_finished = 0";
          }
          if (isset($params[2])) {
            $query .= $params[2];
          }
          if ("resultSelectPaidByTournamentId" == $dataName || "resultSelectPaidNotEnteredByTournamentId" == $dataName) {
            $query .= " ORDER BY " . $this->buildOrderByName(prefix: "p");
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
          "SELECT MIN(result_place_finished) AS place " .
          "FROM poker_results " .
          "WHERE tournament_id = :tournamentId " .
          "AND result_place_finished > 0";
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
          "SELECT r.player_id, CONCAT(p.player_first_name, ' ', p.player_last_name) AS name, p.player_active_flag " .
          "FROM poker_results r INNER JOIN poker_players p ON r.player_id = p.player_id " .
          "WHERE r.tournament_id = :tournamentId " .
          "AND r.result_place_finished = :place";
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
            "((np.NumPlayers * (t.tournament_buyin_amount - (t.tournament_buyin_Amount * t.tournament_rake))) + " .
            " (IFNULL(nr.NumRebuys, 0) * (t.tournament_rebuy_amount - (t.tournament_rebuy_amount * t.tournament_rake))) + " .
            " (IFNULL(na.NumAddons, 0) * (t.tournament_addon_amount - (t.tournament_addon_amount * t.tournament_rake))))";
        }
        $query =
          "SELECT r.result_place_finished AS place, CONCAT(p.player_first_name, ' ', p.player_last_name) AS name, r.result_rebuy_count * t.tournament_rebuy_amount AS rebuy, " .
          "       IF(r.result_paid_addon_flag = '" . Constant::FLAG_YES . "', t.tournament_addon_amount, 0) AS addon, " .
          "       " . $temp . " * IFNULL(s.structure_percentage, 0) AS earnings, " .
          "       (np.numPlayers - r.result_place_finished + 1) * IFNULL(st.special_type_multiplier, 1) + IF(r.result_place_finished BETWEEN 1 AND se.season_final_table_players, se.season_final_table_bonus_points, 0) AS 'pts', " .
          "       ko.knockedOutBy AS 'knocked out by', p.player_active_flag, ko.player_active_flag AS knockOutActive " .
          "FROM poker_results r INNER JOIN poker_players p ON r.player_id = p.player_id " .
          "INNER JOIN poker_tournaments t ON r.tournament_id = t.tournament_id " .
          "INNER JOIN poker_seasons se ON t.tournament_date BETWEEN se.season_start_date AND se.season_end_date " .
          "LEFT JOIN poker_special_types st ON t.special_type_id = st.special_type_id " .
          "INNER JOIN poker_locations l ON t.location_id = l.location_id " .
          "INNER JOIN poker_limit_types lt ON t.limit_type_id = lt.limit_type_id " .
          "INNER JOIN poker_game_types gt ON t.game_type_id = gt.game_type_id " .
          "LEFT JOIN " .
          "    (SELECT s1.payout_id, s1.structure_place, s1.structure_percentage " .
          "     FROM (SELECT p.payout_id " .
          "           FROM (SELECT r.tournament_id, COUNT(*) AS numPlayers " .
          "                 FROM poker_results r " .
          "                 WHERE r.tournament_id = :tournamentId1 " .
          " AND r.result_place_finished > 0 AND r.status_code IN ('" . Constant::CODE_STATUS_REGISTERED . "','" . Constant::CODE_STATUS_FINISHED . "')) np " .
          "           INNER JOIN poker_tournaments t ON np.tournament_id = t.tournament_id " .
          "           INNER JOIN poker_group_payouts gp ON t.group_id = gp.group_id " .
          "           INNER JOIN poker_payouts p ON gp.payout_id = p.payout_id AND np.numPlayers BETWEEN p.payout_min_players AND p.payout_max_players) a " .
          "     INNER JOIN poker_structures s1 ON a.payout_id = s1.payout_id) s ON r.result_place_finished = s.structure_place " .
          "INNER JOIN (SELECT r1.tournament_id, COUNT(*) AS numPlayers " .
          "            FROM poker_results r1 " .
          "            WHERE r1.result_place_finished > 0 " .
          "            GROUP BY r1.tournament_id) np ON r.tournament_id = np.tournament_id " .
          "LEFT JOIN (SELECT r2.tournament_id, SUM(r2.result_rebuy_count) AS numRebuys " .
          "           FROM poker_results r2 " .
          "           WHERE r2.result_rebuy_count > 0 GROUP BY r2.tournament_id) nr ON r.tournament_id = nr.tournament_id " .
          "LEFT JOIN (SELECT r3.tournament_id, r3.player_id, CONCAT(p1.player_first_name, ' ', p1.player_last_name) AS knockedOutBy, p1.player_active_flag " .
          "           FROM poker_results r3 INNER JOIN poker_players p1 ON r3.player_id_ko = p1.player_id) ko ON r.tournament_id = ko.tournament_id AND r.player_id = ko.player_id " .
          "LEFT JOIN (SELECT tournament_id, COUNT(result_paid_addon_flag) AS numAddons " .
          "           FROM poker_results WHERE result_paid_addon_flag = '" . Constant::FLAG_YES . "' GROUP BY tournament_id) na ON r.tournament_id = na.tournament_id " .
          "WHERE r.tournament_id = :tournamentId2 " .
          "AND r.result_place_finished > 0 " .
          "ORDER BY t.tournament_date DESC, t.tournament_start_time DESC, r.result_place_finished";
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
          "SELECT CONCAT(p.player_first_name, ' ', p.player_last_name) AS name, " .
          "       SUM((np.numPlayers - r.result_place_finished + 1) * IFNULL(st.special_type_multiplier, 1) + IF(r.result_place_finished BETWEEN 1 AND se.season_final_table_players, se.season_final_table_bonus_points, 0)) AS pts, " .
          "       SUM((np.numPlayers - r.result_place_finished + 1) * IFNULL(st.special_type_multiplier, 1) + IF(r.result_place_finished BETWEEN 1 AND se.season_final_table_players, se.season_final_table_bonus_points, 0)) / nt.numTourneys AS avg, " .
          "       nt.numTourneys AS tourneys, p.player_active_flag " .
          "FROM poker_players p " . "INNER JOIN poker_results r ON p.player_id = r.player_id " .
          "INNER JOIN poker_tournaments t on r.tournament_id = t.tournament_id ";
        if (isset($params[0]) && isset($params[1])) {
          $query .= "      AND t.tournament_date BETWEEN :startDate1 AND :endDate1 ";
        }
        $query .=
          "INNER JOIN poker_seasons se ON t.tournament_date BETWEEN se.season_start_date AND se.season_end_date " .
          "LEFT JOIN poker_special_types st ON t.special_type_id = st.special_type_id" .
          " INNER JOIN (SELECT r1.player_id, COUNT(*) AS numTourneys FROM poker_results r1 INNER JOIN poker_tournaments t1 ON r1.tournament_id = t1.tournament_id AND r1.result_place_finished > 0";
        if (isset($params[0]) && isset($params[1])) {
          $query .= "      AND t1.tournament_date BETWEEN :startDate2 AND :endDate2 ";
        }
        $query .=
          " LEFT JOIN poker_special_types st ON t1.special_type_id = st.special_type_id AND (st.special_type_description IS NULL OR st.special_type_description <> '" . Constant::DESCRIPTION_CHAMPIONSHIP . "')" .
          " GROUP BY r1.player_id) nt ON r.player_id = nt.player_id " .
          "INNER JOIN (SELECT tournament_id, COUNT(*) AS numPlayers " .
          "            FROM poker_results " .
          "            WHERE result_place_finished > 0 " .
          "            GROUP BY tournament_id) np ON r.tournament_id = np.tournament_id " .
          "GROUP BY r.player_id " .
          "ORDER BY pts DESC, " . $this->buildOrderByName(prefix: "p");
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
          "SELECT name, SUM(totalEarnings) AS earnings, SUM(totalEarnings) / numTourneys AS avg, MAX(maxEarnings) AS max, numTourneys AS tourneys, player_active_flag " .
          "FROM (SELECT player_id, name, SUM(earnings) AS totalEarnings, MAX(earnings) AS maxEarnings, numTourneys, player_active_flag " .
          "      FROM (SELECT p.player_id, CONCAT(p.player_first_name, ' ', p.player_last_name) AS name, " .
          "                   ((np.numPlayers * (t.tournament_buyin_amount - (t.tournament_buyin_amount * t.tournament_rake))) + " .
          "                    (IFNULL(nr.numRebuys, 0) * (t.tournament_rebuy_amount - (t.tournament_rebuy_amount * t.tournament_rake))) + " .
          "                    (IFNULL(na.numAddons, 0) * (t.tournament_addon_amount - (t.tournament_addon_amount * t.tournament_rake)))) * IFNULL(s.structure_percentage, 0) AS earnings, nt.numTourneys, p.player_active_flag " .
          "            FROM poker_results r INNER JOIN poker_players p ON r.player_id = p.player_id " .
          "            INNER JOIN poker_tournaments t ON r.tournament_id = t.tournament_id ";
        if (isset($params[0]) && isset($params[1])) {
          $query .= "            AND t.tournament_date BETWEEN :startDate1 AND :endDate1 ";
        }
        $query .=
          "            INNER JOIN (SELECT r1.player_id, COUNT(*) AS numTourneys " .
          "                        FROM poker_results r1 INNER JOIN poker_tournaments t1 ON r1.tournament_id = t1.tournament_id AND r1.result_place_finished > 0 ";
        if (isset($params[0]) && isset($params[1])) {
          $query .= "                       AND t1.tournament_date BETWEEN :startDate2 AND :endDate2 ";
        }
        $query .=
          "                        GROUP BY r1.player_id) nt ON r.player_id = nt.player_id " .
          "            INNER JOIN (SELECT r2.tournament_id, COUNT(*) AS numPlayers " .
          "                        FROM poker_results r2 " .
          "                        WHERE r2.result_place_finished > 0 " .
          "                        GROUP BY r2.tournament_id) np ON r.tournament_id = np.tournament_id " .
          "            LEFT JOIN (SELECT r3.tournament_id, SUM(r3.result_rebuy_count) AS numRebuys " .
          "                       FROM poker_results r3 " .
          "                       WHERE r3.result_place_finished > 0 " .
          "                       AND r3.result_rebuy_count > 0 " .
          "                       GROUP BY r3.tournament_id) nr ON r.tournament_id = nr.tournament_id " .
          "            LEFT JOIN (SELECT tournament_id, COUNT(result_paid_addon_flag) AS numaddons " .
          "                       FROM poker_results " .
          "                       WHERE result_paid_addon_flag = '" . Constant::FLAG_YES . "' " .
          "                       GROUP BY tournament_id) na ON r.tournament_id = na.tournament_id " .
          "            LEFT JOIN (SELECT a.tournament_id, s1.payout_id, s1.structure_place, s1.structure_percentage " .
          "                       FROM (SELECT np.tournament_id, p.payout_id " .
          "                             FROM (SELECT r.tournament_id, COUNT(*) AS numPlayers " .
          "                                   FROM poker_results r " .
          "                                   WHERE r.result_place_finished > 0 " .
          "                                   AND r.status_code IN ('" . Constant::CODE_STATUS_REGISTERED . "','" . Constant::CODE_STATUS_FINISHED . "') " .
          "                                   GROUP BY r.tournament_id) np " .
          "                             INNER JOIN poker_tournaments t on np.tournament_id = t.tournament_id " .
          "                             INNER JOIN poker_group_payouts gp ON t.group_id = gp.group_id " .
          "                             INNER JOIN poker_payouts p ON gp.payout_id = p.payout_id AND np.numPlayers BETWEEN p.payout_min_players AND p.payout_max_players) a " .
          "                       INNER JOIN poker_structures s1 ON a.payout_id = s1.payout_id) s ON r.tournament_id = s.tournament_id AND r.result_place_finished = s.structure_place " .
          "                       WHERE r.result_place_finished > 0) y " .
          "            GROUP BY player_id " .
          "            UNION " .
          "            SELECT xx.player_id, xx.name, SUM(xx.earnings) AS totalEarnings, MAX(xx.earnings) AS maxEarnings, 0, xx.player_active_flag " .
          "            FROM (SELECT YEAR(t.tournament_date) AS yr, p.player_id, CONCAT(p.player_first_name, ' ', p.player_last_name) AS name, p.player_active_flag, " .
          "                         (SELECT SUM(total) - IF(Yr = 2008, 150, IF(Yr = 2007, -291, IF(Yr = 2006, -824, 0))) AS 'total pool' " .
          "                          FROM (SELECT YEAR(t2.tournament_date) AS Yr, t2.tournament_id AS id, IF(b.play IS NULL, 0, CONCAT(b.play, '+', IFNULL(nr.numRebuys, 0), 'r', '+', IFNULL(na.numAddons, 0), 'a')) AS play, " .
          "                                       ((t2.tournament_buyin_amount * t2.tournament_rake) * play) + ((t2.tournament_rebuy_amount * t2.tournament_rake) * IFNULL(nr.numRebuys, 0)) + ((t2.tournament_addon_amount * t2.tournament_rake) * IFNULL(na.numaddons, 0)) AS total " .
          "                                FROM poker_tournaments t2 " .
          "                                LEFT JOIN (SELECT tournament_id, COUNT(*) AS play " .
          "                                           FROM poker_results " .
          "                                           WHERE result_paid_buyin_flag = '" . Constant::FLAG_YES . "' " .
          "                                           AND result_place_finished > 0 " .
          "                                           GROUP BY tournament_id) b ON t2.tournament_id = b.tournament_id";
        if (isset($params[0]) && isset($params[1])) {
          $query .= "                                AND t2.tournament_date BETWEEN :startDate3 AND :endDate3 ";
        }
        $query .=
          "                               LEFT JOIN (SELECT r.tournament_id, SUM(r.result_rebuy_count) AS numRebuys " .
          "                                          FROM poker_results r " .
          "                                          WHERE r.result_paid_rebuy_flag = '" . Constant::FLAG_YES . "' " .
          "                                          AND r.result_rebuy_count > 0 " .
          "                                          GROUP BY r.tournament_id) nr ON t2.tournament_id = nr.tournament_id " .
          "                               LEFT JOIN (SELECT r.tournament_id, COUNT(*) AS numaddons " .
          "                                          FROM poker_results r " .
          "                                          WHERE r.result_paid_addon_flag = '" . Constant::FLAG_YES . "' " .
          "                                          GROUP BY r.tournament_id) na ON t2.tournament_id = na.tournament_id) zz " .
          "                         WHERE zz.yr = YEAR(t.tournament_date) " .
          "                         GROUP BY zz.yr) * IF(s.structure_percentage IS NULl, 0, s.structure_percentage) AS earnings " .
          "                 FROM poker_results r " .
          "                 INNER JOIN poker_players p ON r.player_id = p.player_id " .
          "                 INNER JOIN poker_tournaments t ON r.tournament_id = t.tournament_id";
        if (isset($params[0]) && isset($params[1])) {
          $query .= "                  AND t.tournament_date BETWEEN :startDate4 AND :endDate4 ";
        }
        $query .=
          "                 LEFT JOIN poker_special_types st ON t.special_type_id = st.special_type_id" .
          "                 LEFT JOIN (SELECT a.tournament_id, s1.payout_id, s1.structure_place, s1.structure_percentage " .
          "                            FROM (SELECT np.tournament_id, p.payout_id " .
          "                                  FROM (SELECT r.tournament_id, COUNT(*) AS numPlayers " .
          "                                        FROM poker_results r " .
          "                                        WHERE r.result_place_finished > 0 " .
          "                                        AND r.status_code IN ('" . Constant::CODE_STATUS_REGISTERED . "','" . Constant::CODE_STATUS_FINISHED . "') " .
          "                                        GROUP BY r.tournament_id) np " .
          "                                  INNER JOIN poker_tournaments t on np.tournament_id = t.tournament_id";
        if (isset($params[0]) && isset($params[1])) {
          $query .= "                                   AND t.tournament_date BETWEEN :startDate5 AND :endDate5 ";
        }
        $query .=
          "                                  INNER JOIN poker_group_payouts gp ON t.group_id = gp.group_id " .
          "                                  INNER JOIN poker_payouts p ON gp.payout_id = p.payout_id AND np.numPlayers BETWEEN p.payout_min_players AND p.payout_max_players) a " .
          "                            INNER JOIN poker_structures s1 ON a.payout_id = s1.payout_id) s ON r.tournament_id = s.tournament_id AND r.result_place_finished = s.structure_place " .
          "                 WHERE r.result_place_finished > 0 " .
          "                 AND st.special_type_description = '" . Constant::DESCRIPTION_CHAMPIONSHIP . "'" .
          "                 GROUP BY player_id, yr) xx " .
          "            GROUP BY xx.player_id, xx.name) cc " .
          "GROUP BY player_id, name " .
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
          "SELECT k.player_id, name, k.knockouts AS kOs, k.knockouts / nt.numTourneys AS avgKo, b.bestKnockout AS bestKo, nt.numTourneys AS tourneys, k.player_active_flag " .
          "FROM (SELECT p3.player_id, CONCAT(p3.player_first_name, ' ', p3.player_last_name) AS name, p.player_active_flag, COUNT(*) AS knockouts " .
          "      FROM poker_tournaments t " .
          "      INNER JOIN poker_limit_types lt ON t.limit_type_id = lt.limit_type_id " .
          "      INNER JOIN poker_game_types gt ON t.game_type_id = gt.game_type_id " .
          "      INNER JOIN poker_locations l ON t.location_id = l.location_id " .
          "      INNER JOIN poker_players p ON l.player_id = p.player_id " .
          "      INNER JOIN poker_results r ON t.tournament_id = r.tournament_id " .
          "      INNER JOIN poker_players p2 ON r.player_id = p2.player_id " .
          "      INNER JOIN poker_status_codes sc ON r.status_code = sc.status_code " .
          "      INNER JOIN poker_players p3 ON r.player_id_ko = p3.player_id " .
          "      WHERE r.result_place_finished > 0 ";
        if (isset($params[0]) && isset($params[1])) {
          $query .= "      AND t.tournament_date BETWEEN :startDate1 AND :endDate1 ";
        }
        $query .=
          "      GROUP BY r.player_id_ko) k " .
          "INNER JOIN (SELECT r.player_id, COUNT(*) AS numTourneys " .
          "            FROM poker_tournaments t " .
          "            INNER JOIN poker_limit_types lt ON t.limit_type_id = lt.limit_type_id " .
          "            INNER JOIN poker_game_types gt ON t.game_type_id = gt.game_type_id " .
          "            INNER JOIN poker_locations l ON t.location_id = l.location_id " .
          "            INNER JOIN poker_players p ON l.player_id = p.player_id " .
          "            INNER JOIN poker_results r ON t.tournament_id = r.tournament_id " .
          "            INNER JOIN poker_players p2 ON r.player_id = p2.player_id " .
          "            INNER JOIN poker_status_codes sc ON r.status_code = sc.status_code " .
          "            LEFT JOIN poker_players p3 ON r.player_id_ko = p3.player_id " .
          "            WHERE r.result_place_finished > 0 ";
        if (isset($params[0]) && isset($params[1])) {
          $query .= "            AND t.tournament_date BETWEEN :startDate2 AND :endDate2 ";
        }
        $query .=
          "            GROUP BY r.player_id) nt ON k.player_id = nt.player_id " .
          "LEFT JOIN (SELECT player_id, MAX(knockouts) AS BestKnockout " .
          "           FROM (SELECT p3.player_id, t.tournament_id, r.player_id_ko, COUNT(*) AS knockouts " .
          "                 FROM poker_tournaments t INNER JOIN poker_limit_types lt ON t.limit_type_id = lt.limit_type_id " .
          "                 INNER JOIN poker_game_types gt ON t.game_type_id = gt.game_type_id " .
          "                 INNER JOIN poker_locations l ON t.location_id = l.location_id " .
          "                 INNER JOIN poker_players p ON l.player_id = p.player_id " .
          "                 INNER JOIN poker_results r ON t.tournament_id = r.tournament_id " .
          "                 INNER JOIN poker_players p2 ON r.player_id = p2.player_id " .
          "                 INNER JOIN poker_status_codes sc ON r.status_code = sc.status_code " .
          "                 INNER JOIN poker_players p3 ON r.player_id_ko = p3.player_id " .
        "                 WHERE r.result_place_finished > 0 " .
        "                 AND r.player_id_ko IS NOT NULL ";
        if (isset($params[0]) && isset($params[1])) {
          $query .= "                 AND t.tournament_date BETWEEN :startDate3 AND :endDate3 ";
        }
        $query .=
          "                 GROUP BY t.tournament_id, r.player_id_ko) z " .
          "           GROUP BY z.player_id) b ON nt.player_id = b.player_id " .
          "WHERE b.player_id IN (SELECT DISTINCT player_id " .
          "               FROM poker_results " .
          "               WHERE status_code = '" . Constant::CODE_STATUS_FINISHED . "') " .
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
          $query .= "player_id, ";
        }
        $query .=
          "name, d.tourneys AS '#', IFNULL(d.Points, 0) AS pts, d.Points / d.numTourneys AS AvgPoints, d.FTs AS 'count', d.pctFTs AS '%', d.avgPlace AS 'avg', d.high AS 'best', d.low AS 'worst', " .
          "-(IF(d.numTourneys IS NULL, 0, d.numTourneys * d.tournament_buyin_amount)) AS buyins, -(IFNULL(d.rebuys, 0)) AS rebuys, " .
          "-(IFNULL(d.addons, 0)) AS addons, -(IF(d.numTourneys IS NULL, 0, d.numTourneys * d.tournament_buyin_amount)) + -(IFNULL(d.rebuys, 0)) + -(IFNULL(d.addons, 0)) AS 'total', " .
          "d.earnings,  " .
          "d.earnings - IF(d.numTourneys IS NULL, 0, d.numTourneys * d.tournament_buyin_amount) - IFNULL(d.rebuys, 0) - IFNULL(d.addons, 0) AS 'net(+/-)', " .
          "d.earnings / d.numTourneys AS '$ / trny', " .
          "(d.earnings - IF(d.numTourneys IS NULL, 0, d.numTourneys * d.tournament_buyin_amount) - IFNULL(d.rebuys, 0) - IFNULL(d.addons, 0)) / d.numTourneys AS 'Net / Trny', " .
          "player_active_flag " .
          "FROM (SELECT a.player_id, a.name, a.player_active_flag, a.Tourneys, a.FTs, a.PctFTs, a.AvgPlace, a.Low, a.High, IFNULL(b.Earnings, 0) AS Earnings, a.NumTourneys, " .
          "             e.result_place_finished, e.NumPlayers, e.Points, e.Rebuys, e.Addons, e.NumRebuys, e.tournament_buyin_amount" .
          "      FROM (SELECT p.player_id, CONCAT(p.player_first_name, ' ', p.player_last_name) AS name, IFNULL(nt.NumTourneys, 0) AS Tourneys, IFNULL(nft.NumFinalTables, 0) AS FTs, " .
          "                   IF(nt.NumTourneys IS NULL, 0, IFNULL(nft.NumFinalTables, 0) / nt.NumTourneys) AS PctFTs, " .
          "                   IF(nt.NumTourneys IS NULL, 0, IFNULL(nt.TotalPlaces, 0) / nt.NumTourneys) AS AvgPlace, " .
          "                   IFNULL(nt.MaxPlace, 0) AS Low, IFNULL(nt.MinPlace, 0) AS High, nt.NumTourneys, p.player_active_flag " .
          "            FROM poker_players p " .
          "            LEFT JOIN (SELECT r1.player_id, COUNT(*) AS NumTourneys, SUM(r1.result_place_finished) AS TotalPlaces, MAX(r1.result_place_finished) AS MaxPlace, MIN(r1.result_place_finished) AS MinPlace " .
          "                       FROM poker_results r1 " .
        "                       INNER JOIN poker_tournaments t1 ON r1.tournament_id = t1.tournament_id";
        if (isset($params[0]) && isset($params[1])) {
          $query .= "                       AND t1.tournament_date BETWEEN :startDate1 AND :endDate1 ";
        }
        $query .=
          "                       WHERE r1.result_place_finished > 0 " .
          "                       GROUP BY r1.player_id) nt ON p.player_id = nt.player_id " .
          "            LEFT JOIN (SELECT r2.player_id, COUNT(*) AS NumFinalTables " .
          "                       FROM poker_results r2 " .
          "                       INNER JOIN poker_tournaments t2 ON r2.tournament_id = t2.tournament_id";
        if (isset($params[0]) && isset($params[1])) {
          $query .= "                       AND t2.tournament_date BETWEEN :startDate2 AND :endDate2 ";
        }
        $query .=
          "                       INNER JOIN poker_seasons se ON t2.tournament_date BETWEEN se.season_start_date AND se.season_end_date " .
          "                       WHERE r2.result_place_finished BETWEEN 1 AND se.season_final_table_players " .
          "                       GROUP BY r2.player_id) nft ON p.player_id = nft.player_id) a " .
          "            LEFT JOIN (SELECT player_id, name, SUM(TotalEarnings) AS Earnings, MAX(MaxEarnings) AS MaxEarnings, numTourneys " .
          "                       FROM (SELECT player_id, name, SUM(Earnings) AS TotalEarnings, MAX(Earnings) AS MaxEarnings, NumTourneys " .
          "                             FROM (SELECT p.player_id, CONCAT(p.player_first_name, ' ', p.player_last_name) AS name, " .
          "                                         ((np.NumPlayers * (t.tournament_buyin_amount - (t.tournament_buyin_amount * t.tournament_rake))) + " .
          "                                          (IFNULL(nr.NumRebuys, 0) * (t.tournament_rebuy_amount - (t.tournament_rebuy_amount * t.tournament_rake))) + " .
          "                                          (IFNULL(na.NumAddons, 0) * (t.tournament_addon_amount - (t.tournament_addon_amount * t.tournament_rake)))) * IFNULL(s.structure_percentage, 0) AS Earnings, nt.NumTourneys " .
          "                                   FROM poker_results r INNER JOIN poker_players p ON r.player_id = p.player_id " .
          "                                   INNER JOIN poker_tournaments t ON r.tournament_id = t.tournament_id";
        if (isset($params[0]) && isset($params[1])) {
          $query .= "                                   AND t.tournament_date BETWEEN :startDate3 AND :endDate3 ";
        }
        $query .=
          "                                   INNER JOIN (SELECT r1.player_id, COUNT(*) AS NumTourneys " .
          "                                               FROM poker_results r1 " .
          "                                               INNER JOIN poker_tournaments t1 ON r1.tournament_id = t1.tournament_id AND r1.result_place_finished > 0";
        if (isset($params[0]) && isset($params[1])) {
          $query .= "                                               AND t1.tournament_date BETWEEN :startDate4 AND :endDate4 ";
        }
        $queryAndBindParams = $this->buildChampionship($params);
        $query .=
          "                                               GROUP BY r1.player_id) nt ON r.player_id = nt.player_id " .
          "                                   INNER JOIN (SELECT r2.tournament_id, COUNT(*) AS NumPlayers " .
          "                                               FROM poker_results r2 " .
          "                                               WHERE r2.result_place_finished > 0 " .
          "                                               GROUP BY r2.tournament_id) np ON r.tournament_id = np.tournament_id " .
          "                                   LEFT JOIN (SELECT r3.tournament_id, SUM(r3.result_rebuy_count) AS NumRebuys " .
          "                                              FROM poker_results r3 " .
          "                                              WHERE r3.result_place_finished > 0 " .
          "                                              AND r3.result_rebuy_count > 0 GROUP BY r3.tournament_id) nr ON r.tournament_id = nr.tournament_id " .
          "                                   LEFT JOIN (SELECT tournament_id, COUNT(result_paid_addon_flag) AS NumAddons " .
          "                                              FROM poker_results " .
          "                                              WHERE result_paid_addon_flag = '" . Constant::FLAG_YES . "' " .
          "                                              GROUP BY tournament_id) na ON r.tournament_id = na.tournament_id " .
          "                                   LEFT JOIN (SELECT a.tournament_id, s1.payout_id, s1.structure_place, s1.structure_percentage " .
          "                                              FROM (SELECT np.tournament_id, p.payout_id " .
          "                                                    FROM (SELECT r.tournament_id, COUNT(*) AS numPlayers " .
          "                                                          FROM poker_results r " .
          "                                                          WHERE r.result_place_finished > 0 " .
          "                                                          AND r.status_code IN ('" . Constant::CODE_STATUS_REGISTERED . "','" . Constant::CODE_STATUS_FINISHED . "') " .
          "                                                          GROUP BY r.tournament_id) np INNER JOIN poker_tournaments t on np.tournament_id = t.tournament_id " .
          "                                                    INNER JOIN poker_group_payouts gp ON t.group_id = gp.group_id " .
          "                                                    INNER JOIN poker_payouts p ON gp.payout_id = p.payout_id AND np.numPlayers BETWEEN p.payout_min_players AND p.payout_max_players) a " .
          "                                              INNER JOIN poker_structures s1 ON a.payout_id = s1.payout_id) s ON r.tournament_id = s.tournament_id AND r.result_place_finished = s.structure_place " .
          "                                   WHERE r.result_place_finished > 0) y " .
          "                             GROUP BY player_id " .
          "                             UNION " .
          "                             SELECT xx.player_id, xx.name, SUM(xx.earnings) AS TotalEarnings, MAX(xx.earnings) AS MaxEarnings, 0 " .
          "                             FROM (" . $queryAndBindParams[0] .
          "                                   GROUP BY player_id, yr) xx " .
          "                             GROUP BY xx.player_id, xx.name) cc " .
          "                       GROUP BY player_id, name) b ON a.player_id = b.player_id " .
          "LEFT JOIN (SELECT c.player_id, c.result_place_finished, c.NumPlayers, SUM((c.numPlayers - c.result_place_finished + 1) * IFNULL(c.special_type_multiplier, 1)) + IF(c.result_place_finished BETWEEN 1 AND c.season_final_table_players, c.season_final_table_bonus_points, 0) AS Points, " .
          "                                                     SUM(IFNULL(c.NumRebuys, 0) * c.tournament_rebuy_amount) AS Rebuys, " .
          "                                                     SUM(IFNULL(c.NumAddons, 0) * c.tournament_addon_amount) AS Addons, " .
          "                                                     c.NumRebuys, c.tournament_buyin_amount " .
          "           FROM (SELECT a.tournament_id, a.tournament_description, a.player_id, a.result_place_finished, a.NumPlayers, a.NumRebuys, a.tournament_buyin_amount, a.tournament_rebuy_amount, a.tournament_addon_amount, a.NumAddons, a.special_type_description, a.special_type_multiplier, a.season_final_table_players, a.season_final_table_bonus_points " .
          "                 FROM (SELECT r.tournament_id, t.tournament_description, r.player_id, r.result_place_finished, np.NumPlayers, nr.NumRebuys, t.tournament_buyin_amount, t.tournament_rebuy_amount, t.tournament_addon_amount, na.NumAddons, st.special_type_description, st.special_type_multiplier, se.season_final_table_players, se.season_Final_table_bonus_points " .
          "                       FROM poker_results r INNER JOIN poker_tournaments t ON r.tournament_id = t.tournament_id";
        if (isset($params[0]) && isset($params[1])) {
          $query .= "                       AND t.tournament_date BETWEEN :startDate5 AND :endDate5 ";
        }
        $query .=
          "                       AND r.result_place_finished > 0 " .
          "                       INNER JOIN poker_seasons se ON t.tournament_date BETWEEN se.season_start_date AND se.season_end_date " .
          "                       LEFT JOIN poker_special_types st ON t.special_type_id = st.special_type_id" .
          "                       INNER JOIN (SELECT r3.tournament_id, COUNT(*) AS NumPlayers " .
          "                                   FROM poker_results r3 INNER JOIN poker_tournaments t3 ON r3.tournament_id = t3.tournament_id";
        if (isset($params[0]) && isset($params[1])) {
          $query .= "                                   AND t3.tournament_date BETWEEN :startDate6 AND :endDate6 ";
        }
        $query .=
          "                                   WHERE r3.result_place_finished > 0 " .
          "                                   GROUP BY r3.tournament_id) np ON r.tournament_id = np.tournament_id " .
          "                       LEFT JOIN (SELECT r4.tournament_id, r4.player_id, SUM(r4.result_rebuy_count) AS NumRebuys " .
          "                                  FROM poker_results r4 " .
          "                                  INNER JOIN poker_tournaments t4 ON r4.tournament_id = t4.tournament_id";
        if (isset($params[0]) && isset($params[1])) {
          $query .= "                                  AND t4.tournament_date BETWEEN :startDate7 AND :endDate7 ";
        }
        $query .=
          "                                  WHERE r4.result_place_finished > 0 " .
          "                                  AND r4.result_rebuy_count > 0 " .
          "                                  GROUP BY r4.tournament_id, r4.player_id) nr ON r.tournament_id = nr.tournament_id AND r.player_id = nr.player_id " .
          "                       LEFT JOIN (SELECT tournament_id, player_id, COUNT(result_paid_addon_flag) AS NumAddons " .
          "                                  FROM poker_results r7 WHERE result_paid_addon_flag = '" . Constant::FLAG_YES . "' GROUP BY tournament_id, player_id) na ON r.tournament_id = na.tournament_id AND r.player_id = na.player_id) a " .
          "                 ) c " .
          "           GROUP BY c.player_id) e ON b.player_id = e.player_id " .
          "WHERE b.player_id IN (SELECT DISTINCT player_id FROM poker_results WHERE status_code = '" . Constant::CODE_STATUS_FINISHED . "')) d ";
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
      case "resultPaidPlayerCount":
        $query =
          "SELECT COUNT(*) AS cnt " .
          "FROM poker_players p INNER JOIN poker_results r ON r.player_id = p.player_id " .
          "INNER JOIN poker_tournaments t ON r.tournament_id = t.tournament_id " .
          "AND t.tournament_date = (SELECT MAX(t9.tournament_date) " .
          "                        FROM poker_tournaments t9 " .
          "                        INNER JOIN poker_results r9 ON t9.tournament_id = r9.tournament_id AND r9.status_code = '" . Constant::CODE_STATUS_PAID . "') " .
          "AND t.tournament_start_time = (SELECT MIN(t10.tournament_start_time) " .
          "                  FROM poker_tournaments t10 " .
          "                  INNER JOIN poker_results r10 ON t10.tournament_id = r10.tournament_id AND r10.status_code = '" . Constant::CODE_STATUS_PAID . "' " .
          "                                              AND t10.tournament_date = (SELECT MAX(t11.tournament_date) " .
          "                                                                        FROM poker_tournaments t11 " .
          "                                                                        INNER JOIN poker_results r11 ON t11.tournament_id = r11.tournament_id AND r11.status_code = '" . Constant::CODE_STATUS_PAID . "'))";
        break;
      case "seasonActiveCount":
        $query =
          "SELECT COUNT(*) AS cnt " .
          "FROM poker_seasons " .
          "WHERE season_active_flag = 1";
        break;
      case "seasonDateCheckCount":
        $query =
          "SELECT COUNT(*) AS cnt " .
          "FROM poker_seasons " .
          "WHERE (:tournamentDate1 BETWEEN season_start_date AND season_end_date OR :tournamentDate2 BETWEEN season_start_date AND season_end_date)";
        if (isset($params[2])) {
          $query .= " AND season_id <> :seasonId";
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
          "FROM (SELECT a.player_id, a.name, a.active, a.Tourneys, a.FTs, a.PctFTs, a.AvgPlace, a.Low, a.High, IFNULL(b.earnings, 0) AS earnings, a.NumTourneys, " .
          "             e.player_id, e.result_place_finished, e.NumPlayers, e.Points, e.Rebuys, e.Addons, e.NumRebuys, e.BuyinAmount, km.kos, km.koMax, w.wins " .
          "      FROM (SELECT p.player_id, CONCAT(p.player_first_name, ' ', p.player_last_name) AS name, IFNULL(nt.NumTourneys, 0) AS Tourneys, IFNULL(nft.NumFinalTables, 0) AS FTs, IF(nt.NumTourneys IS NULL, 0, IFNULL(nft.NumFinalTables, 0) / nt.NumTourneys) AS PctFTs, IF(nt.NumTourneys IS NULL, 0, IFNULL(nt.TotalPlaces, 0) / nt.NumTourneys) AS AvgPlace, IFNULL(nt.MaxPlace, 0) AS Low, IFNULL(nt.MinPlace, 0) AS High, IFNULL(nt.NumTourneys, 0) AS NumTourneys, p.active " .
          "            FROM poker_players p LEFT JOIN (SELECT r1.player_id, COUNT(*) AS NumTourneys, SUM(r1.result_place_finished) AS TotalPlaces, MAX(r1.result_place_finished) AS MaxPlace, MIN(r1.result_place_finished) AS MinPlace " .
          "                                         FROM poker_results r1 INNER JOIN poker_tournaments t1 ON r1.tournament_id = t1.tournament_id " .
          "                                         AND t1.tournament_date BETWEEN :startDate1 AND :endDate1 " .
          "                                         WHERE r1.result_place_finished > 0 GROUP BY r1.player_id) nt ON p.player_id = nt.player_id " .
          "            LEFT JOIN (SELECT r2.player_id, COUNT(*) AS NumFinalTables " .
          "                       FROM poker_results r2 INNER JOIN poker_tournaments t2 ON r2.tournament_id = t2.tournament_id " .
          "                       AND t2.tournament_date BETWEEN :startDate2 AND :endDate2 " .
          "                       INNER JOIN poker_seasons se ON t2.tournament_date BETWEEN se.season_start_date AND se.season_end_date " .
          "                       WHERE r2.result_place_finished BETWEEN 1 AND se.seasonFinalTablePlayers GROUP BY r2.player_id) nft ON p.player_id = nft.player_id) a " .
          "      LEFT JOIN (SELECT player_id, name, SUM(TotalEarnings) AS Earnings, MAX(MaxEarnings) AS MaxEarnings, numTourneys " .
          "                 FROM (SELECT player_id, name, SUM(Earnings) AS TotalEarnings, MAX(Earnings) AS MaxEarnings, NumTourneys " .
          "                       FROM (SELECT p.player_id, CONCAT(p.player_first_name, ' ', p.player_last_name) AS name, ((np.NumPlayers * (t.tournament_buyin_amount - (t.tournament_buyin_amount * t.tournament_rake))) + (IFNULL(nr.NumRebuys, 0) * (t.tournament_RebuyAmount - (t.tournament_rebuy_amount * t.tournament_rake))) + (IFNULL(na.NumAddons, 0) * (t.tournament_addon_amount - (t.tournament_addon_amount * t.tournament_Rake)))) * IFNULL(s.structure_percentage, 0) AS Earnings, nt.NumTourneys " .
          "                             FROM poker_results r INNER JOIN poker_players p ON r.player_id = p.player_id " .
          "                             INNER JOIN poker_tournaments t ON r.tournament_id = t.tournament_id " .
          "                             AND t.tournament_date BETWEEN :startDate3 AND :endDate3 " .
          "                       INNER JOIN (SELECT r1.player_id, COUNT(*) AS NumTourneys " .
          "                                   FROM poker_results r1 INNER JOIN poker_tournaments t1 ON r1.tournament_id = t1.tournament_id AND r1.result_place_finished > 0 " .
          "                                   AND t1.tournament_date BETWEEN :startDate4 AND :endDate4 " .
          "                                   GROUP BY r1.player_id) nt ON r.player_id = nt.player_id " .
          "                       INNER JOIN (SELECT r2.tournament_id, COUNT(*) AS NumPlayers FROM poker_results r2 WHERE r2.result_place_finished > 0 GROUP BY r2.tournament_id) np ON r.tournament_id = np.tournament_id " .
          "                       LEFT JOIN (SELECT r3.tournament_id, SUM(r3.result_rebuy_count) AS NumRebuys FROM poker_results r3 WHERE r3.result_place_finished > 0 AND r3.result_rebuy_Count > 0 GROUP BY r3.tournament_id) nr ON r.tournament_id = nr.tournament_id " .
          "                       LEFT JOIN (SELECT tournament_id, COUNT(AddonPaid) AS NumAddons FROM poker_results WHERE AddonPaid = '" . Constant::FLAG_YES . "' GROUP BY tournament_id) na ON r.tournament_id = na.tournament_id " .
          "                       LEFT JOIN (SELECT a.tournament_id, s1.payout_id, s1.structure_place, s1.structure_percentage " .
          "                                  FROM (SELECT np.tournament_id, p.payout_id " .
          "                                        FROM (SELECT r.tournament_id, COUNT(*) AS numPlayers FROM poker_results r WHERE r.result_place_finished > 0 AND r.status_code IN ('" . Constant::CODE_STATUS_REGISTERED . "', '" . Constant::CODE_STATUS_FINISHED . "') GROUP BY r.tournament_id) np INNER JOIN poker_tournaments t on np.tournament_id = t.tournament_id " .
          "                                        INNER JOIN poker_group_payouts gp ON t.group_id = gp.group_id " .
          "                                        INNER JOIN poker_payouts p ON gp.payout_id = p.payout_id AND np.numPlayers BETWEEN p.payout_min_players AND p.payout_max_players) a " .
          "                                        INNER JOIN poker_structures s1 ON a.payout_id = s1.payout_id) s ON r.tournament_id = s.tournament_id AND r.result_place_finished = s.structure_place " .
          "                                        WHERE r.result_place_finished > 0) y " .
          "                       GROUP BY player_id " .
          "                       UNION ALL " .
          "                       SELECT xx.player_id, xx.name, SUM(xx.earnings) AS TotalEarnings, MAX(xx.earnings) AS MaxEarnings, 0 " .
          "                       FROM (SELECT se.season_start_date, YEAR(t.tournament_date) AS Yr, p.player_id, p.player_first_name, p.player_last_name, CONCAT(p.player_first_name, ' ', p.player_last_name) AS name, qq.total * IFNULL(s.structure_percentage, 0) AS Earnings, numTourneys AS trnys " .
          "                             FROM poker_results r INNER JOIN poker_players p ON r.player_id = p.player_id " .
          "                             INNER JOIN poker_tournaments t ON r.tournament_id = t.tournament_id AND t.tournament_date BETWEEN :startDate5 AND :endDate5 " .
          "                             INNER JOIN poker_seasons se ON t.tournament_Date BETWEEN se.season_start_date AND se.season_end_date " .
          "                             INNER JOIN (SELECT season_start_date, season_end_date, SUM(total) - IF(YEAR(season_end_date) = 2008, 150, IF(YEAR(season_end_date) = 2007, -291, IF(YEAR(season_end_date) = 2006, -824, 0))) AS total " .
          "                                         FROM (SELECT se2.seasonStartDate, se2.seasonEndDate, t2.TournamentId AS Id, IF(b.Play IS NULL, 0, CONCAT(b.Play, '+', IFNULL(nr.NumRebuys, 0), 'r', '+', IFNULL(na.NumAddons, 0), 'a')) AS Play, ((t2.tournament_buyin_amount * t2.tournament_rake) * Play) + ((t2.tournament_rebuy_amount * t2.tournament_rake) * IFNULL(nr.NumRebuys, 0)) + ((t2.tournament_addon_amount * t2.tournament_rake) * IFNULL(na.NumAddons, 0) ) AS Total " .
          "                                               FROM poker_tournaments t2 INNER JOIN poker_seasons se2 ON t2.tournament_date BETWEEN se2.season_start_date AND se2.season_end_date " .
          "                                               LEFT JOIN (SELECT tournament_id, COUNT(*) AS Play FROM poker_results WHERE result_paid_buyin_flag = '" . Constant::FLAG_YES . "' AND result_place_finished > 0 GROUP BY tournament_id) b ON t2.tournament_id = b.tournament_id " .
          "                                               LEFT JOIN (SELECT r.tournament_id, SUM(r.result_rebuy_count) AS NumRebuys FROM poker_results r WHERE r.result_paid_buyin_flag = '" . Constant::FLAG_YES . "' AND r.result_rebuy_count > 0 GROUP BY r.tournament_id) nr ON t2.tournament_id = nr.tournament_id " .
          "                                               LEFT JOIN (SELECT r.tournament_id, COUNT(*) AS NumAddons FROM poker_results r WHERE r.result_paid_addon_flag = '" . Constant::FLAG_YES . "' GROUP BY r.tournament_id) na ON t2.tournament_id = na.tournament_id) zz " .
          "                                         GROUP BY seasonStartDate, season_end_date) qq ON qq.season_start_date = se.season_start_date AND qq.season_end_date = se.season_end_date " .
          "                             LEFT JOIN poker_special_types st ON t.special_type_id = st.special_type_id " .
          "                             INNER JOIN (SELECT r1.player_id, COUNT(*) AS NumTourneys FROM poker_results r1 INNER JOIN poker_tournaments t1 ON r1.tournament_id = t1.tournament_id AND r1.result_place_finished > 0 INNER JOIN poker_special_types st1 ON t1.special_type_id = st1.special_type_id AND st1.special_type_description = '" . Constant::DESCRIPTION_CHAMPIONSHIP . "' GROUP BY r1.player_id) nt ON r.player_id = nt.player_id " .
          "                             LEFT JOIN (SELECT a.tournament_id, s1.payout_id, s1.structure_place, s1.structure_percentage " .
          "                                        FROM (SELECT np.tournament_id, p.payout_id " .
          "                                              FROM (SELECT r.tournament_id, COUNT(*) AS numPlayers FROM poker_results r WHERE r.result_place_finished > 0 AND r.status_code IN ('" . Constant::CODE_STATUS_REGISTERED . "','" . Constant::CODE_STATUS_FINISHED . "') GROUP BY r.tournament_id) np " .
          "                                              INNER JOIN poker_tournaments t on np.tournament_id = t.tournament_id " .
          "                                              AND t.tournament_date BETWEEN :startDate6 AND :endDate6 " .
          "                                              INNER JOIN poker_group_payout gp ON t.group_id = gp.group_id " .
          "                                              INNER JOIN poker_payout p ON gp.payout_id = p.payout_id AND np.numPlayers BETWEEN p.payout_min_players AND p.payout_max_players) a " .
          "                                              INNER JOIN poker_structure s1 ON a.payout_id = s1.payout_id) s ON r.tournament_id = s.tournament_id AND r.result_place_finished = s.structure_place " .
          "                             WHERE r.result_place_finished > 0 " .
          "                             AND st.special_type_description = '" . Constant::DESCRIPTION_CHAMPIONSHIP . "' " .
          "                             GROUP BY player_id, yr) xx " .
          "                       GROUP BY xx.player_id, xx.name) cc " .
          "                 GROUP BY player_id, name) b ON a.player_id = b.player_id " .
          "      LEFT JOIN (SELECT c.player_id, c.result_place_finished, c.NumPlayers, IF(c.result_place_finished IS NULL, 0, SUM((c.numPlayers - c.result_place_finished + 1) * IFNULL(c.special_type_multiplier, 1))) + IF(c.result_place_finished BETWEEN 1 AND c.season_final_table_players, c.season_final_table_bonus_points, 0) AS Points, SUM(IFNULL(c.NumRebuys, 0) * c.RebuyAmount) AS Rebuys, SUM(IFNULL(c.NumAddons, 0) * c.AddonAmount) AS Addons, IFNULL(c.NumRebuys, 0) AS NumRebuys, c.tournament_buyin_amount " .
          "                 FROM (SELECT a.tournament_id, a.tournament_description, a.player_id, a.result_place_finished, a.NumPlayers, a.NumRebuys, a.tournament_buyin_amount, a.tournament_rebuy_amount, a.tournament_addon_amount, a.NumAddons, a.special_type_description, a.special_type_multiplier, a.season_final_table_players, a.season_final_table_bonus_points " .
          "                       FROM (SELECT r.tournament_id, t.tournament_description, r.player_id, r.result_place_finished, np.NumPlayers, nr.NumRebuys, t.tournament_buyin_amount, t.tournament_rebuy_amount, t.tournament_addon_amount, na.NumAddons, st.special_type_description, st.special_type_multiplier, se.season_final_table_players, se.season_final_table_bonus_points " .
          "                             FROM poker_results r INNER JOIN poker_tournaments t ON r.tournament_id = t.tournament_id AND t.tournament_date BETWEEN :startDate7 AND :endDate7 " .
          "                             INNER JOIN poker_seasons se ON t.tournament_date BETWEEN se.season_start_date AND se.season_end_date " .
          "                             AND r.result_place_finished > 0 " .
          "                             LEFT JOIN poker_special_types st ON t.special_type_id = st.special_type_id " .
          "                             INNER JOIN (SELECT r3.tournament_id, COUNT(*) AS NumPlayers FROM poker_results r3 INNER JOIN poker_tournaments t3 ON r3.tournament_id = t3.tournament_id " .
          "                                         AND t3.tournament_date BETWEEN :startDate8 AND :endDate8 " .
          "                                         WHERE r3.result_place_finished > 0 GROUP BY r3.tournament_id) np ON r.tournament_id = np.tournament_id " .
          "                             LEFT JOIN (SELECT r4.tournament_id, r4.PlayerId, SUM(r4.result_rebuy_count) AS NumRebuys FROM poker_results r4 INNER JOIN poker_tournaments t4 ON r4.tournament_id = t4.tournament_id " .
          "                                        AND t4.tournament_date BETWEEN :startDate9 AND :endDate9 " .
          "                                        WHERE r4.result_place_finished > 0 AND r4.RebuyCount > 0 GROUP BY r4.tournament_id, r4.PlayerId) nr ON r.tournament_id = nr.tournament_id AND player_id = nr.player_id " .
          "                             LEFT JOIN (SELECT tournament_id, player_id, COUNT(result_paid_addon_flag) AS NumAddons FROM poker_results r7 WHERE result_paid_addon_flag = '" . Constant::FLAG_YES . "' GROUP BY tournament_id, player_id) na ON r.tournament_id = na.tournament_id AND r.player_id = na.player_id) a " .
          "                      ) c " .
          "                 GROUP BY c.player_id) e ON b.player_id = e.player_id " .
          "      LEFT JOIN (SELECT player_id, SUM(knockouts) AS kos, MAX(knockouts) AS koMax " .
          "                 FROM (SELECT p.player_id, COUNT(*) AS knockouts " .
          "                       FROM poker_tournaments t INNER JOIN poker_results r ON t.tournament_id = r.tournament_id " .
          "                       AND t.tournament_date BETWEEN :startDate10 AND :endDate10 " .
          "                       AND r.result_place_finished > 0 " .
          "                       INNER JOIN poker_players p ON r.player_id_ko = p.player_id " .
          "                       GROUP BY t.tournament_id, r.player_id_ko) k " .
          "                 GROUP BY player_id) km ON b.player_id = km.player_id " .
          "      LEFT JOIN (SELECT r.player_id, COUNT(*) AS wins " .
          "                 FROM poker_tournaments t INNER JOIN poker_results r ON t.tournament_id = r.tournament_id AND r.result_place_finished = 1 " .
          "                 AND t.tournament_date BETWEEN :startDate11 AND :endDate11 " .
          "                 GROUP BY r.player_id) w ON b.player_id = w.player_id " .
          "      WHERE b.Id IN (SELECT DISTINCT player_id FROM poker_results WHERE status_code = '" . Constant::CODE_STATUS_FINISHED . "') " .
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
          "SELECT season_id AS id, season_description AS description, season_start_date AS 'start date', season_end_date AS 'end date', season_championship_qualification_count AS '# to qualify', season_final_table_players AS 'final table players', season_final_table_bonus_points AS 'final table bonus points', season_Fee AS fee, season_active_flag AS 'active' " .
          "FROM poker_seasons s ";
        if ("seasonSelectOneById" == $dataName) {
          $query .= " WHERE season_id = :seasonId";
        } else if ("seasonSelectOneByIdAndDesc" == $dataName) {
          $query .=
            " INNER JOIN poker_tournaments t" .
            " LEFT JOIN poker_special_types st ON t.special_type_id = st.special_type_id" .
            " WHERE t.tournament_date BETWEEN s.season_start_date AND s.season_end_date AND t.tournament_id = :tournamentId AND st.special_type_description = :typeDescription";
        } else if ("seasonSelectOneByActive" == $dataName) {
          $query .= " WHERE season_active_flag = :seasonActive";
        } else if ("seasonSelectAll" == $dataName && isset($params[3])) {
          $query .= " WHERE season_id IN (:seasonId)";
        }
        if (isset($params[2]) && $params[2]) {
          $query .= " ORDER BY season_start_date DESC";
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
          "SELECT s.season_id " .
          "FROM poker_seasons s INNER JOIN poker_tournaments t ON t.tournament_date BETWEEN s.season_start_date AND s.season_end_date AND t.tournament_id = :tournamentId";
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
          "SELECT season_id, season_description AS description, season_start_date, season_end_date, season_championship_qualification_count, season_final_table_players, season_final_table_bonus_points, season_Fee, season_active_flag " .
          "FROM poker_seasons " .
          "ORDER BY season_end_date DESC, season_start_date";
        break;
      case "seasonSelectMaxId":
        $query =
          "SELECT MAX(season_id) AS id " .
          "FROM poker_seasons";
        break;
      case "specialTypeSelectAll":
      case "specialTypeSelectOneById":
        $query =
          "SELECT special_type_id AS id, special_type_description AS description, special_type_multiplier AS multiplier " .
          "FROM poker_special_types";
        if ("specialTypeSelectOneById" == $dataName) {
          $query .= " WHERE special_type_id = :typeId";
        } else if ("specialTypeSelectAll" == $dataName) {
          if ($params[1]) {
            $query .= " WHERE special_type_id IN (:typeId)";
          }
        }
        if ("specialTypeSelectAll" == $dataName && isset($params[0]) && $params[0]) {
          $query .= " ORDER BY special_type_description";
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
          "SELECT p.player_id, CONCAT(p.player_first_name, ' ', p.player_last_name) AS name, " .
          "       IF(s.season_fee - IFNULL(f.fee_amount, 0) = 0, 'Paid', 'Not paid') AS 'fee status logic', " .
          "       IF(s.season_fee IS NULL, '', IF(s.season_fee - IFNULL(f.fee_amount, 0) = 0, 'Paid', CONCAT('Owes ', (s.season_fee - IFNULL(f.fee_amount, 0))))) AS 'fee status', " .
          "       IFNULL(f.fee_amount, 0) AS amount, " .
          "       IF(f.fee_amount = s.season_fee, f.fee_amount, IFNULL(f2.fee_amount, 0)) AS amount2, " .
          "       IF(r.result_paid_buyin_flag = '" . Constant::FLAG_YES . "', 'Paid', 'Not paid') AS 'buyin status', " .
          "       IF(r.result_paid_rebuy_flag = '" . Constant::FLAG_YES . "', 'Paid', 'Not paid') AS 'rebuy status', " .
          "       r.result_rebuy_count, IF(r.result_paid_addon_flag = '" . Constant::FLAG_YES . "', 'Paid', 'Not paid') AS 'addon status' " .
          "FROM poker_players p INNER JOIN poker_results r ON p.player_id = r.player_id AND " . $this->buildPlayerActive(alias: "p") .
          " INNER JOIN poker_tournaments t ON r.tournament_id = t.tournament_id AND t.tournament_id = :tournamentId AND r.status_code IN ('" . Constant::CODE_STATUS_REGISTERED . "', '" . Constant::CODE_STATUS_PAID . "') AND r.result_registration_order <= t.tournament_max_players" .
          " INNER JOIN poker_seasons s ON t.tournament_date BETWEEN s.season_start_date AND s.season_end_date" .
          " LEFT JOIN (SELECT season_id, player_id, SUM(fee_amount) AS fee_amount FROM poker_fees GROUP BY season_id, player_id) f ON s.season_id = f.season_id AND p.player_id = f.player_id" .
          " LEFT JOIN (SELECT season_id, player_id, tournament_id, fee_amount FROM poker_fees) f2 ON s.season_id = f2.season_id AND p.player_id = f2.player_id AND t.tournament_id = f2.tournament_id";
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
          "SELECT status_code, status_code_name " .
          "FROM poker_status_codes";
        break;
      case "structureSelectAll":
      case "structurePayout":
        $query = "SELECT payout_id, structure_place, structure_percentage";
        if ("structurePayout" == $dataName) {
          $query .= " * " . $params[0] . " AS pay, structure_percentage";
        }
        $query .= " FROM poker_structures";
        if ("structurePayout" == $dataName) {
          $query .= " WHERE payout_id = :payoutId";
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
          "SELECT MAX(t.tournament_id) AS tournamentId " .
          "FROM poker_tournaments t INNER JOIN poker_seasons s ON t.tournament_date BETWEEN s.season_start_date AND s.season_end_date " .
          "WHERE s.season_id = :seasonId";
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
          "SELECT tournament_id, tournament_description, tournament_date " .
          "FROM poker_tournaments " .
          "WHERE YEAR(tournament_date) = :tournamentDate " .
          "ORDER BY tournament_date DESC, tournament_start_time DESC";
        if (isset($limitCount)) {
          $query .= " LIMIT :limitCount";
        }
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':tournamentDate', $params[0], PDO::PARAM_INT);
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
          "SELECT t.tournament_id AS id, t.tournament_description AS description, t.tournament_comment AS comment, t.location_id, l.location_name AS location, t.limit_type_id, lt.limit_type_name AS 'limit', t.game_type_id, gt.game_type_name AS 'type', " .
          "       l.player_id, CONCAT(p.player_first_name, ' ', p.player_last_name) AS playerName, l.location_address, l.location_city, l.location_state, l.location_zip_code, p.player_phone, l.location_map AS mapHide, l.location_map_link AS map, t.tournament_date AS date, t.tournament_start_time AS 'start', t.tournament_max_players AS 'max players', t.tournament_chip_count AS 'chips', -t.tournament_buyin_amount AS 'buyin', t.tournament_max_rebuys AS 'max', -t.tournament_rebuy_amount AS 'amt', -t.tournament_addon_amount AS 'amt ', " .
          "       t.tournament_addon_chip_count AS 'chips ', t.group_id, g.group_name AS name, t.tournament_rake AS rake, IFNULL(nr.registeredCount, 0) AS registeredCount, " .
          "       IFNULL(bp.buyinsPaid, 0) AS buyinsPaid, " .
          "       IFNULL(rp.rebuysPaid, 0) AS rebuysPaid, " .
          "       IFNULL(rc.rebuysCount, 0) AS rebuysCount, " .
          "       IFNULL(ap.addonsPaid, 0) AS addonsPaid, " .
          "       IFNULL(ec.enteredCount, 0) AS enteredCount, " .
          "       t.special_type_id, st.special_type_description AS std, st.special_type_multiplier " .
          "FROM poker_tournaments t INNER JOIN poker_game_types gt ON t.game_type_id = gt.game_type_id " .
          "INNER JOIN poker_limit_types lt ON t.limit_type_id = lt.limit_type_id ";
        if ("tournamentSelectAllForChampionship" == $dataName) {
          $query .= "INNER JOIN poker_special_types st ON t.special_type_id = st.special_type_id AND st.special_type_description = '" . Constant::DESCRIPTION_CHAMPIONSHIP . "' ";
        } else {
          $query .= "LEFT JOIN poker_special_types st ON t.special_type_id = st.special_type_id ";
        }
        $query .=
          "INNER JOIN poker_locations l ON t.location_id = l.location_id " .
          "INNER JOIN poker_players p ON l.player_id = p.player_id " .
          "INNER JOIN poker_groups g on t.group_id = g.group_id " .
          "LEFT JOIN (SELECT tournament_id, COUNT(*) AS buyinsPaid FROM poker_results WHERE result_paid_buyin_flag = '" . Constant::FLAG_YES . "' AND result_place_finished = 0 GROUP BY tournament_id) bp ON t.tournament_id = bp.tournament_id " .
          "LEFT JOIN (SELECT tournament_id, COUNT(*) AS rebuysPaid FROM poker_results WHERE result_paid_rebuy_flag = '" . Constant::FLAG_YES . "' AND result_place_finished = 0 GROUP BY tournament_id) rp ON t.tournament_id = rp.tournament_id " .
          "LEFT JOIN (SELECT tournament_id, SUM(result_rebuy_count) AS rebuysCount FROM poker_results WHERE result_paid_rebuy_flag = '" . Constant::FLAG_YES . "' AND result_place_finished = 0 GROUP BY tournament_id) rc ON t.tournament_id = rc.tournament_id " .
          "LEFT JOIN (SELECT tournament_id, COUNT(*) AS addonsPaid FROM poker_results WHERE result_paid_addon_flag = '" . Constant::FLAG_YES . "' AND result_place_finished = 0 GROUP BY tournament_id) ap ON t.tournament_id = ap.tournament_id " .
          "LEFT JOIN (SELECT tournament_id, COUNT(*) AS enteredCount FROM poker_results WHERE status_code = '" . Constant::CODE_STATUS_FINISHED . "' AND result_place_finished <> 0 GROUP BY tournament_id) ec ON t.tournament_id = ec.tournament_id " .
          "LEFT JOIN (SELECT tournament_id, COUNT(*) AS registeredCount FROM poker_results WHERE status_code = '" . Constant::CODE_STATUS_REGISTERED . "' GROUP BY tournament_id) nr ON t.tournament_id = nr.tournament_id";
        if ("tournamentSelectAllByDateAndStartTime" == $dataName) {
          $query .=
            " WHERE t.tournament_date >= :tournamentDate1 OR (t.tournament_date = :tournamentDate2 AND t.tournament_start_time >= :startTime)" .
            " ORDER BY t.tournament_date, t.tournament_start_time";
        } else if ("tournamentSelectOneById" == $dataName) {
          $query .= " WHERE t.tournament_id = :tournamentId";
        } else if ("tournamentSelectAllForRegistration" == $dataName || "tournamentSelectAllForBuyins" == $dataName || "tournamentSelectAllForChampionship" == $dataName) {
          $query .=
            " WHERE (CURRENT_DATE >= t.tournament_date OR t.tournament_date <= DATE_ADD(CURRENT_DATE, INTERVAL 28 DAY))" .
            " AND enteredCount IS NULL" .
            " ORDER BY t.tournament_date, t.tournament_start_time";
        } else if ("tournamentSelectAllOrdered" == $dataName) {
          $query .= " ORDER BY t.tournament_date DESC, t.tournament_start_time DESC";
        } else if ("tournamentSelectAll" == $dataName) {
          if (isset($params[0])) {
            $query .= " WHERE t.tournament_id IN (:tournamentId)";
          } else if (isset($mode)) {
            if ($mode == Constant::MODE_CREATE) {
              $orderBy .= " WHERE enteredCount IS NULL AND buyinsPaid > 0 AND t.tournament_id NOT IN (SELECT DISTINCT tournament_id FROM poker_results WHERE result_place_finished <> 0)";
            } else if ($mode == Constant::MODE_MODIFY) {
              $orderBy .= " WHERE enteredCount > 0";
            }
            $orderBy .= " ORDER BY t.tournament_date DESC, t.tournament_start_time DESC";
          }
          if (isset($orderBy)) {
            $query .= $orderBy;
          }
        } else if ("tournamentsSelectForEmailNotifications" == $dataName) {
          $query .=
            " WHERE DATE(tournament_date) = DATE(DATE_ADD(now(), INTERVAL :interval DAY))" .
            " ORDER BY tournament_date, tournament_start_time";
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
          "SELECT pt.player_id, CONCAT(pt.player_first_name, ' ', pt.player_last_name) AS name, " .
          "IF(pt.season_fee - IFNULL(pt.fee_amount, 0) = 0, 'Paid', CONCAT('Owes $', season_fee - IFNULL(pt.fee_amount, 0))) AS 'season fee', " .
          "IF(r.result_registration_order IS NULL, 'Not registered', 'Registered') AS status, " .
          "IF(r.result_registration_order IS NULL, 'N/A', IF(r.result_registration_order > pt.tournament_max_players, CONCAT(r.result_registration_order, ' (Wait list #', r.result_registration_order - pt.tournament_max_players, ')'), r.result_registration_order)) AS 'order' " .
          "FROM (SELECT p.player_id, p.player_first_name, p.player_last_name, t.tournament_id, t.tournament_max_players, s.season_fee, f.fee_amount " .
          "      FROM poker_players p CROSS JOIN poker_tournaments t ON " . $this->buildPlayerActive(alias: "p") . " AND t.tournament_id = :tournamentId" .
          "      LEFT JOIN poker_seasons s ON t.tournament_date BETWEEN s.season_start_date AND s.season_end_date " .
          "      LEFT JOIN (SELECT season_id, player_id, SUM(fee_amount) AS fee_amount FROM poker_fees GROUP BY season_id, player_id) f ON s.season_id = f.season_id AND p.player_id = f.player_id) pt " .
          "LEFT JOIN poker_results r ON pt.player_id = r.player_id AND r.tournament_id = pt.tournament_id AND r.status_code IN ('" . Constant::CODE_STATUS_REGISTERED . "', '" . Constant::CODE_STATUS_PAID . "');";
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
          "SELECT t.tournament_id, t.tournament_date, t.tournament_start_time, l.location_name, IFNULL(bp.buyinsPaid, 0) AS buyinsPaid " .
          "FROM poker_tournaments t " .
          "INNER JOIN poker_locations l ON t.location_id = l.location_id " .
          "LEFT JOIN (SELECT tournament_id, COUNT(*) AS buyinsPaid " .
          "           FROM poker_results " .
          "           WHERE result_paid_buyin_flag = '" . Constant::FLAG_YES . "' " .
          "           GROUP BY tournament_id) bp ON t.tournament_id = bp.tournament_id " .
          "WHERE tournament_date = CURRENT_DATE " .
          "AND bp.result_paid_buyin_flag > 0 " .
          "AND t.tournament_start_time = (SELECT MAX(t.tournament_start_time) " .
          "                               FROM poker_tournaments t " .
          "                               LEFT JOIN (SELECT tournament_id, COUNT(*) AS buyinsPaid " .
          "                                          FROM poker_results " .
          "                                          WHERE result_paid_buyin_flag = '" . Constant::FLAG_YES . "' " .
          "                                          GROUP BY tournament_id) bp ON t.tournament_id = bp.tournament_id AND t.tournament_date = CURRENT_DATE " .
          "                               WHERE bp.result_paid_buyin_flag > 0)";
        break;
      case "tournamentSelectAllYearsPlayed":
        $query =
          "SELECT DISTINCT YEAR(tournament_date) AS year " .
          "FROM poker_tournaments " .
          "ORDER BY YEAR(tournament_date) desc";
        if (isset($orderBy)) {
          $query .= " " . $orderBy;
        }
        break;
      case "tournamentsPlayedByPlayerIdAndDateRange":
        $query =
          "SELECT COUNT(*) AS numPlayed " .
          "FROM poker_tournaments t INNER JOIN poker_results r ON t.tournament_id = r.tournament_id " .
          "AND player_id = :playerId " .
          "AND result_place_finished > 0 " .
          "AND tournament_date BETWEEN :startDate AND :endDate";
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
          "SELECT t.tournament_id AS id, t.tournament_description AS description, t.tournament_comment AS comment, t.location_id, l.location_name AS location, t.limit_type_id, lt.limit_Type_name AS 'limit', t.game_type_id, gt.game_type_name AS 'type', t.tournament_chip_count AS 'chips', " .
          "l.player_id, CONCAT(p.player_first_name, ' ', p.player_last_name) AS name, l.location_address, l.location_city, l.location_state, l.location_zip_code, p.player_phone, t.tournament_date AS date, t.tournament_start_time AS 'start', t.tournament_buyin_amount AS 'buyin', t.tournament_max_players AS 'max players', t.tournament_max_rebuys AS 'max', t.tournament_rebuy_amount AS 'amt', t.tournament_addon_amount AS 'amt ', " .
          "t.tournament_addon_chip_count AS 'chips ', t.tournament_rake AS rake, l.location_map AS mapHide, l.location_map_link AS map, IFNULL(ec.enteredCount, 0) AS enteredCount, p.player_active_Flag, t.special_type_id, st.special_type_description AS std, " .
          "(((t.tournament_buyin_amount * ec.enteredCount) + (IFNULL(nr.numRebuys, 0) * t.tournament_rebuy_amount) + (IFNULL(na.numAddons, 0) * t.tournament_addon_amount)) * .8)* s.structure_percentage AS earnings, st.special_type_multiplier " .
          "FROM poker_players p INNER JOIN poker_results r ON p.player_id = r.player_id AND p.player_id = :playerId" .
          " INNER JOIN poker_tournaments t ON t.tournament_id = r.tournament_id AND r.result_place_finished = 1 " .
          "LEFT JOIN poker_special_types st ON t.special_type_id = st.special_type_id " .
          "INNER JOIN poker_game_types gt ON t.game_type_id = gt.game_type_id " .
          "INNER JOIN poker_limit_types lt ON t.limit_type_id = lt.limit_type_id " .
          "INNER JOIN poker_locations l ON t.location_id = l.location_id " .
          "LEFT JOIN (SELECT tournament_id, COUNT(*) AS enteredCount " .
          "           FROM poker_results " .
          "           WHERE status_code = '" . Constant::CODE_STATUS_FINISHED . "' " .
          "           AND result_place_finished <> 0 " .
          "           GROUP BY tournament_id) ec ON t.tournament_id = ec.tournament_id " .
          "LEFT JOIN (SELECT a.tournament_id, s1.payout_id, s1.structure_place, s1.structure_percentage " .
          "          FROM (SELECT np.tournament_id, p.payout_id " .
          "                FROM (SELECT r.tournament_id, COUNT(*) AS numPlayers " .
          "                      FROM poker_results r " .
          "                      WHERE r.status_code IN ('" . Constant::CODE_STATUS_REGISTERED . "','" . Constant::CODE_STATUS_FINISHED . "') " .
          "                      GROUP BY r.tournament_id) np " .
          "                INNER JOIN poker_tournaments t on np.tournament_id = t.tournament_id " .
          "                INNER JOIN poker_group_payouts gp ON t.group_id = gp.group_id " .
          "                INNER JOIN poker_payouts p ON gp.payout_id = p.payout_id AND np.numPlayers BETWEEN p.payout_min_players AND p.payout_max_players) a " .
          "          INNER JOIN poker_structures s1 ON a.payout_id = s1.payout_id) s ON r.tournament_id = s.tournament_id AND r.result_place_finished = s.structure_place " .
          "LEFT JOIN (SELECT r2.tournament_id, SUM(r2.result_rebuy_count) AS numRebuys " .
          "           FROM poker_results r2 " .
          "           WHERE r2.result_rebuy_count > 0 GROUP BY r2.tournament_id) nr ON r.tournament_id = nr.tournament_id " .
          "LEFT JOIN (SELECT tournament_id, COUNT(result_paid_addon_flag) AS numAddons " .
          "           FROM poker_results WHERE result_paid_addon_flag = '" . Constant::FLAG_YES . "' GROUP BY tournament_id) na ON r.tournament_id = na.tournament_id " .
          "ORDER BY tournament_date, tournament_start_time";
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
          "SELECT p.player_id, CONCAT(p.player_first_name, ' ', p.player_last_name) AS name, p.player_active_flag, COUNT(*) AS tourneys " .
          "FROM poker_players p " .
          "LEFT JOIN poker_results r ON p.player_id = r.player_id AND r.result_place_finished > 0 " .
          "WHERE " . $this->buildPlayerActive(alias: "p") .
          " GROUP BY p.player_id <REPLACE>";
        if ($rank) {
          $whereClause = "<REPLACE>";
          $orderByFieldName = "tourneys DESC, " . $this->buildOrderByName(prefix: NULL);
          $selectFieldNames = "player_id, name, tourneys";
          $query = $this->modifyQueryAddRank(query: $query, whereClause: $whereClause, selectFieldName: "tourneys", selectFieldNames: $selectFieldNames, orderByFieldName: $orderByFieldName);
        }
        break;
      case "tournamentsPlayedByType":
        $query =
          "SELECT lt.limit_type_id, lt.limit_type_name AS 'limit type', gt.game_type_id, gt.game_type_name AS 'game type', t.tournament_max_rebuys AS rebuys, IF(t.tournament_addon_amount > 0, '" . Constant::FLAG_YES . "', '" . Constant::FLAG_NO . "') AS addon, COUNT(*) AS count " .
          "FROM poker_tournaments t " .
          "INNER JOIN poker_limit_types lt ON lt.limit_type_id = t.limit_type_id " .
          "INNER JOIN poker_game_types gt ON gt.game_type_id = t.game_type_id " .
          "INNER JOIN poker_results r ON t.tournament_id = r.tournament_id AND r.status_code = '" . Constant::CODE_STATUS_FINISHED . "' AND r.player_id = :playerId " .
          "GROUP BY lt.limit_type_id, lt.limit_type_name, gt.game_type_id, gt.game_type_name, t.tournament_max_rebuys, addon";
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
          "SELECT MIN(t.tournament_date) AS date " .
          "FROM poker_tournaments t " .
          "INNER JOIN poker_results r ON t.tournament_id = r.tournament_id AND r.player_id = :playerId";
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
          "SELECT tournament_id " .
          "FROM poker_tournaments " .
          "ORDER BY tournament_date, tournament_start_time";
        break;
      case "playerAbsencesByTournamentId":
        $query =
          "SELECT pta.player_id, CONCAT(p.player_first_name, ' ', p.player_last_name) AS name " .
          "FROM poker_tournament_absences pta INNER JOIN poker_tournaments pt ON pta.tournament_id = pt.tournament_id AND YEAR(tournament_date) = :tournamentDate " .
          "LEFT JOIN poker_special_types st ON pt.special_type_id = st.special_type_id AND st.special_type_description = '" . Constant::DESCRIPTION_CHAMPIONSHIP . "'" .
          " INNER JOIN poker_players p ON pta.player_id = p.player_id";
        if (isset($limitCount)) {
          $query .= " LIMIT :limitCount";
        }
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':tournamentDate', $params[0], PDO::PARAM_STR);
        if (isset($limitCount)) {
          $pdoStatement->bindParam(':limitCount', $limitCount, PDO::PARAM_INT);
        }
        break;
      case "playerPaidByTournamentId":
        $query =
          "SELECT p.player_id, CONCAT(p.player_first_name, ' ', p.player_last_name) AS name, p.player_username, p.player_password, p.player_email, p.player_administrator_flag, p.player_registration_date, p.player_approval_date, p.player_approval_player_id, p.player_rejection_date, p.player_rejection_player_id, p.player_active_flag, p.player_selector, p.player_token, p.player_expires " .
          "FROM poker_players p " .
          "INNER JOIN poker_results r ON p.player_id = r.player_id AND r.tournament_id = :tournamentId " .
          "AND r.result_paid_buyin_flag = '" . Constant::FLAG_YES . "' " .
          "ORDER BY " . $this->buildOrderByName(prefix: "p");
        if (isset($limitCount)) {
          $query .= " LIMIT :limitCount";
        }
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':tournamentId', $params[0], PDO::PARAM_INT);
        if (isset($limitCount)) {
          $pdoStatement->bindParam(':limitCount', $limitCount, PDO::PARAM_INT);
        }
        break;
      case "playerActive":
        $query =
          "SELECT p.player_id, CONCAT(p.player_first_name, ' ', p.player_last_name) AS name, p.player_email " .
          "FROM poker_players p " .
          "WHERE " . $this->buildPlayerActive(alias: "p") .
          " ORDER BY " . $this->buildOrderByName(prefix: "p");
        break;
      case "playerSelectAll":
      case "playerSelectOneById":
      case "playerSelectOneByName":
      case "playerSelectOneByUsername":
      case "playerSelectOneByEmail":
        $query =
          "SELECT p.player_id AS id, CONCAT(p.player_first_name, ' ', p.player_last_name) AS name, p.player_username AS username, p.player_password, p.player_email AS email, p.player_phone AS phone, p.player_administrator_flag AS admin, p.player_registration_date AS 'Reg Date', p.player_approval_date AS 'App Date', p.player_approval_player_id AS 'App User', CONCAT(ua.player_first_name, ' ', ua.player_last_name) AS 'App Name', p.player_rejection_date AS 'Reject Date', p.player_rejection_player_id AS 'Reject User', CONCAT(ur.player_first_name, ' ', ur.player_last_name) AS 'Reject Name', p.player_active_flag AS active " .
          "FROM poker_players p " .
          "LEFT JOIN poker_players ua ON p.player_approval_player_id = ua.player_id " .
          "LEFT JOIN poker_players ur ON p.player_rejection_player_id = ur.player_id";
        if ("playerSelectAll" != $dataName && "playerSelectOneById" != $dataName) {
          $query .= " WHERE p.player_active_flag <> 0";
        }
        if ("playerSelectOneById" == $dataName) {
          $query .= " WHERE p.player_id = :playerId";
        } else if ("playerSelectOneByName" == $dataName) {
          $query .= " AND CONCAT(p.player_first_name, ' ', p.player_last_name) = :name";
        } else if ("playerSelectOneByUsername" == $dataName) {
          $query .= " AND p.player_username = :userName";
        } else if ("playerSelectOneByEmail" == $dataName) {
          $query .= " AND p.player_email = :email";
        }
        $query .= " ORDER BY " . $this->buildOrderByName(prefix: "p");
        if (isset($limitCount)) {
          $query .= " LIMIT :limitCount";
        }
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        if ("playerSelectOneById" == $dataName) {
          $pdoStatement->bindParam(':playerId', $params[0], PDO::PARAM_INT);
        } else if ("playerSelectOneByName" == $dataName) {
          $pdoStatement->bindParam(':name', $params[0], PDO::PARAM_STR);
        } else if ("playerSelectOneByUsername" == $dataName) {
          $pdoStatement->bindParam(':userName', $params[0], PDO::PARAM_STR);
        } else if ("playerSelectOneByEmail" == $dataName) {
          $pdoStatement->bindParam(':email', $params[0], PDO::PARAM_STR);
        }
        if (isset($limitCount)) {
          $pdoStatement->bindParam(':limitCount', $limitCount, PDO::PARAM_INT);
        }
        break;
      case "playersSelectForEmailNotifications":
        $query =
          "SELECT player_id, CONCAT(player_first_name, ' ', player_last_name) AS name, player_email " .
          "FROM poker_players p " .
          "WHERE " . $this->buildPlayerActive(alias: "p") .
          " AND player_approval_date IS NOT NULL " .
          "AND player_rejection_date IS NULL " .
          "AND player_email <>  '' " .
          "ORDER BY player_id";
        break;
      case "playersSelectForApproval":
        $query =
          "SELECT p.player_id, CONCAT(p.player_first_name, ' ', p.player_last_name) AS name, p.player_email, p.player_username, p.player_rejection_date AS 'Rejection Date', CONCAT(p2.player_first_name, ' ', p2.player_last_name) AS 'Rejection Name' " .
          "FROM poker_players p " .
          "LEFT JOIN poker_players p2 ON p.player_rejection_player_id = p2.player_id " .
          "WHERE p.player_approval_date IS NULL AND p.player_rejection_date IS NULL";
        break;
      case "waitListedPlayerByTournamentId":
        $query =
          "SELECT p.player_id, CONCAT(p.player_first_name, ' ', p.player_last_name) AS name, p.player_email, t.tournament_max_players " .
          "FROM poker_players p " .
          "INNER JOIN poker_results r ON p.player_id = r.player_id AND r.tournament_id = :tournamentId AND " . $this->buildPlayerActive(alias: "p") .
          " INNER JOIN poker_tournaments t ON r.tournament_id = t.tournament_id AND r.result_registration_order = t.tournament_max_players";
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
      case "winsForPlayer":
      case "winsTotalAndAverageForSeasonForPlayer":
        $query =
          "SELECT p.player_id, CONCAT(p.player_first_name, ' ', p.player_last_name) AS name, IFNULL(wins, 0) AS wins, IFNULL(wins / trnys, 0) AS avg, IFNULL(trnys, 0) AS trnys, p.player_active_flag " .
          "FROM poker_players p " .
          "LEFT JOIN (SELECT r.player_id, COUNT(*) AS wins, numTourneys AS trnys " .
          "           FROM poker_tournaments t " .
          "           INNER JOIN poker_results r ON t.tournament_id = r.tournament_id AND r.result_place_finished = 1 ";
        if ("winsForPlayer" != $dataName && isset($params[0]) && isset($params[1])) {
          $query .= "            AND t.tournament_date BETWEEN :startDate1 AND :endDate1 ";
        }
        $query .=
          "            INNER JOIN (SELECT r.player_id, COUNT(*) AS numTourneys " .
          "                        FROM poker_results r " .
          "                        INNER JOIN poker_tournaments t ON r.tournament_id = t.tournament_id AND r.result_place_finished > 0 ";
        if ("winsForPlayer" != $dataName && isset($params[0]) && isset($params[1])) {
          $query .= "                        AND t.tournament_date BETWEEN :startDate2 AND :endDate2 ";
        }
        $query .=
          "                        GROUP BY r.player_id) nt ON r.player_id = nt.player_id " .
          "            GROUP BY r.player_id) a ON p.player_id = a.player_id AND " . $this->buildPlayerActive(alias: "p");
        if ("winsTotalAndAverageForSeasonForPlayer" == $dataName || "winsForPlayer" == $dataName) {
          $whereClause = "WHERE p.player_id = :playerId";
          $query .= " WHERE p.player_id = :playerId";
        } else if ("winnersForSeason" == $dataName) {
          $query .= " WHERE wins > 0";
        }
        if ("winsForPlayer" != $dataName && "winsTotalAndAverageForSeasonForPlayer" != $dataName) {
          $query .= " ORDER BY wins DESC, " . $this->buildOrderByName(prefix: "p");
        }
        if ($rank) {
          if (1 == $orderBy[0]) {
            $orderByFieldName = "wins DESC, " . $this->buildOrderByName(prefix: "p");
            $selectFieldName = "wins";
          } else {
            $orderByFieldName = "avg DESC, " . $this->buildOrderByName(prefix: "p");
            $selectFieldName = "avg";
          }
          $selectFieldNames = "player_id, name, wins, avg, trnys";
          $query = $this->modifyQueryAddRank(query: $query, whereClause: $whereClause, selectFieldName: $selectFieldName, selectFieldNames: $selectFieldNames, orderByFieldName: $orderByFieldName);
        }
        if (isset($limitCount)) {
          $query .= " LIMIT :limitCount";
        }
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        if ("winsForPlayer" != $dataName && isset($params[0]) && isset($params[1])) {
          $pdoStatement->bindParam(':startDate1', $params[0], PDO::PARAM_STR);
          $pdoStatement->bindParam(':endDate1', $params[1], PDO::PARAM_STR);
          $pdoStatement->bindParam(':startDate2', $params[0], PDO::PARAM_STR);
          $pdoStatement->bindParam(':endDate2', $params[1], PDO::PARAM_STR);
        } else if ("winsTotalAndAverageForSeasonForPlayer" == $dataName) {
          $pdoStatement->bindParam(':playerId', $params[2], PDO::PARAM_INT);
        }
        if (isset($limitCount)) {
          $pdoStatement->bindParam(':limitCount', $limitCount, PDO::PARAM_INT);
        }
        break;
      case "winnersSelectAll":
      case "winnersSelectAllStats":
        $query =
          "SELECT CONCAT(p.player_first_name, ' ', p.player_last_name) AS name, p.player_active_flag, COUNT(*) AS wins " .
          "FROM poker_results r " .
          "INNER JOIN poker_tournaments t ON r.tournament_id = t.tournament_id AND r.result_place_finished = 1 ";
        if (isset($params[0]) && isset($params[1])) {
          $query .= "AND t.tournament_date BETWEEN :startDate AND :endDate ";
        }
        $query .=
          "INNER JOIN poker_players p ON r.player_id = p.player_id " .
          "GROUP BY name " .
          "ORDER BY ";
        if ("winnersSelectAll" == $dataName) {
          $query .= "wins DESC, ";
        }
        $query .= $this->buildOrderByName(prefix: "p");
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
      case "playerPasswordReset":
        $query =
          "SELECT player_token " .
          "FROM poker_players " .
          "WHERE player_username = :userName " .
          "AND player_email = :email";
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
                $address = new Address(debug: $this->isDebug(), id: NULL, address: $row["location_address"], city: $row["location_city"], state: $row["location_state"], zip: (int) $row["location_zip_code"]);
                $player = new Player(debug: $this->isDebug(), id: (int) $row["player_id"], name: $row["name"], username: NULL, password: NULL, email: $row["player_email"], phone: NULL, administrator: 0, registrationDate: NULL, approvalDate: NULL, approvalUserid: NULL, approvalName: NULL, rejectionDate: NULL, rejectionUserid: NULL, rejectionName: NULL, active: 0, address: $address, resetSelector: NULL, resetToken: NULL, resetExpires: NULL, rememberSelector: NULL, rememberToken: NULL, rememberExpires: NULL);
                $location = new Location(debug: $this->isDebug(), id: NULL, name: "", player: $player, count: 0, active: 0, map: NULL, mapName: NULL, tournamentCount: 0);
                $dateTime = new DateTime(debug: $this->isDebug(), id: NULL, time: $row["tournament_date"]);
                $dateStartTime = new DateTime(debug: $this->isDebug(), id: NULL, time: $row["tournament_start_time"]);
                $tournament = new Tournament(debug: $this->isDebug(), id: (int) $row["tournament_id"], description: NULL, comment: NULL, limitType: NULL, gameType: NULL, specialType: NULL, chipCount: 0, location: $location, date: $dateTime, startTime: $dateStartTime, buyinAmount: 0, maxPlayers: 0, maxRebuys: 0, rebuyAmount: 0, addonAmount: 0, addonChipCount: 0, groupPayout: NULL, rake: 0, registeredCount: 0, buyinsPaid: 0, rebuysPaid: 0, rebuysCount: 0, addonsPaid: 0, enteredCount: 0);
                array_push($resultList, $tournament);
                break;
              case "bullyForPlayer":
                array_push($resultList, $row["name"]);
                array_push($resultList, $row["player_active_flag"]);
                array_push($resultList, $row["kOs"]);
                break;
              case "championshipQualifiedPlayers":
                $object = array();
                array_push($object, (int) $row["player_id"]);
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
              case "earningsTotalAndAverageForSeasonForPlayer":
              case "earningsTotalAndAverageForPlayer":
                if ("earningsTotalAndAverageForSeasonForPlayer" != $dataName && "earningsTotalAndAverageForPlayer" != $dataName) {
                  array_push($resultList, $row["name"]);
                }
                array_push($resultList, $row["earns"]);
                if ("earningsTotalForChampionship" != $dataName) {
                  array_push($resultList, $row["avg"]);
                  array_push($resultList, $row["trnys"]);
                }
                array_push($resultList, $row["active"]);
                break;
              case "feeCountByTournamentId":
                array_push($resultList, (int) $row["cnt"]);
                break;
              case "feeDetail":
                array_push($resultList, (int) $row["season_id"]);
                array_push($resultList, $row["Description"]);
                array_push($resultList, $row["Name"]);
                array_push($resultList, (int) $row["tournament_id"]);
                array_push($resultList, (int) $row["Fee"]);
                array_push($resultList, (int) $row["Paid"]);
                array_push($resultList, (int) $row["Balance"]);
                break;
              case "feeSelectBySeason":
                array_push($resultList, (int) $row["season_id"]);
                array_push($resultList, $row["Description"]);
                array_push($resultList, $row["Start Date"]);
                array_push($resultList, $row["End Date"]);
                array_push($resultList, (int) $row["Amount"]);
                break;
              case "feeSelectByTournamentIdAndPlayerId":
                $fee = new Fee(debug: $this->isDebug(), seasonId: (int) $row["season_id"], playerId: (int) $row["player_id"], amount: (int) $row["fee_amount"], status: $row["status"]);
                array_push($resultList, $fee);
                break;
              case "finishesSelectAllByPlayerId":
                array_push($resultList, (int) $row["place"]);
                array_push($resultList, (int) $row["finishes"]);
                array_push($resultList, (float) $row["avg"]);
                break;
              case "foodByTournamentIdAndPlayerId":
                array_push($resultList, $row["name"]);
                array_push($resultList, $row["player_email"]);
                array_push($resultList, $row["result_registration_food"]);
                array_push($resultList, (int) $row["player_active_flag"]);
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
                $values = array($row["group_id"],$row["group_name"]);
                array_push($resultList, $values);
                break;
              case "knockoutsAverageForSeason":
              case "knockoutsTotalForSeason":
              case "knockoutsTotalAndAverageForSeasonForPlayer":
              case "knockoutsTotalAndAverageForPlayer":
                //array_push($resultList, $row["player_id"]);
                array_push($resultList, $row["name"]);
                array_push($resultList, $row["kO"]);
                array_push($resultList, $row["avg"]);
                array_push($resultList, $row["trnys"]);
                array_push($resultList, $row["player_active_flag"]);
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
                $address = new Address(debug: $this->isDebug(), id: NULL, address: $row["location_address"], city: $row["location_city"], state: $row["state"], zip: (int) $row["zip"]);
                $player = new Player(debug: $this->isDebug(), id: $row["playerId"], name: $name, username: NULL, password: NULL, email: NULL, phone: NULL, administrator: 0, registrationDate: NULL, approvalDate: NULL, approvalUserid: NULL, approvalName: NULL, rejectionDate: NULL, rejectionUserid: NULL, rejectionName: NULL, active: (int) $row["player_active_flag"], address: $address, resetSelector: NULL, resetToken: NULL, resetExpires: NULL, rememberSelector: NULL, rememberToken: NULL, rememberExpires: NULL);
                $location = new Location(debug: $this->isDebug(), id: $row["id"], name: $row["name"], player: $player, count: 0, active: (int) $row["player_active_flag"], map: NULL, mapName: NULL, tournamentCount: "locationSelectAll" == $dataName ? (int) $row["trnys"] : 0);
                array_push($resultList, $location);
                break;
              case "login":
                array_push($resultList, $row["player_password"]);
                break;
              case "nemesisForPlayer":
                array_push($resultList, $row["name"]);
                array_push($resultList, $row["player_active_flag"]);
                array_push($resultList, $row["kOs"]);
                break;
              case "notificationSelectAll":
              case "notificationSelectOneById":
                $startDateTime = new DateTime(debug: $this->isDebug(), id: NULL, time: $row["start date"]);
                $endDateTime = new DateTime(debug: $this->isDebug(), id: NULL, time: $row["end date"]);
                $notification = new Notification(debug: $this->isDebug(), id: (int) $row["Id"], description: $row["Description"], startDate: $startDateTime, endDate: $endDateTime);
                array_push($resultList, $notification);
                break;
              case "payoutSelectMaxId":
                array_push($resultList, (int) $row["id"]);
                break;
              case "payoutSelectNameList":
                $values = array($row["payout_id"],$row["payout_name"]);
                array_push($resultList, $values);
                break;
              case "pointsAverageForSeason":
              case "pointsTotalForSeason":
              case "pointsTotalAndAverageForSeasonForPlayer":
              case "pointsTotalAndAverageForPlayer":
                array_push($resultList, $row["name"]);
                array_push($resultList, $row["pts"]);
                array_push($resultList, $row["avg"]);
                array_push($resultList, $row["trnys"]);
                array_push($resultList, $row["player_active_flag"]);
                break;
              case "prizePoolForSeason":
                array_push($resultList, $row["total"]);
                break;
              case "registrationList":
                $values = array($row["player_first_name"], $row["player_last_name"], $row["result_registration_food"], $row["fee status"]);
                array_push($resultList, $values);
                break;
              case "registrationWaitList":
                $values = array($row["player_id"], $row["name"], $row["player_email"], $row["tournament_max_players"], $row["player_active_flag"]);
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
                $status = new Status(debug: $this->isDebug(), id: NULL, code: $row["status_code"], name: $row["status"]);
                $tournament = new Tournament(debug: $this->isDebug(), id: (int) $row["tournament_id"], description: NULL, comment: NULL, limitType: NULL, gameType: NULL, specialType: NULL, chipCount: 0, location: NULL, date: NULL, startTime: NULL, buyinAmount: 0, maxPlayers: 0, maxRebuys: 0, rebuyAmount: 0, addonAmount: 0, addonChipCount: 0, groupPayout: NULL, rake: 0, registeredCount: 0, buyinsPaid: 0, rebuysPaid: 0, rebuysCount: 0, addonsPaid: 0, enteredCount: 0);
                $player = new Player(debug: $this->isDebug(), id: (int) $row["player_id"], name: $row["name"], username: NULL, password: NULL, email: $row["player_email"], phone: NULL, administrator: 0, registrationDate: NULL, approvalDate: NULL, approvalUserid: NULL, approvalName: NULL, rejectionDate: NULL, rejectionUserid: NULL, rejectionName: NULL, active: (int) $row["player_active_flag"], address: NULL, resetSelector: NULL, resetToken: NULL, resetExpires: NULL, rememberSelector: NULL, rememberToken: NULL, rememberExpires: NULL);
                if (isset($row["knocked out by"])) {
                  $nameKnockedOutBy = $row["knocked out by"];
                } else {
                  $nameKnockedOutBy = "";
                }
                $knockedOutBy = new Player(debug: $this->isDebug(), id: (int) $row["player_id_ko"], name: $nameKnockedOutBy, username: NULL, password: NULL, email: NULL, phone: NULL, administrator: 0, registrationDate: NULL, approvalDate: NULL, approvalUserid: NULL, approvalName: NULL, rejectionDate: NULL, rejectionUserid: NULL, rejectionName: NULL, active: isset($row["knockedOutActive"]) ? (int) $row["knockedOutActive"] : 0, address: NULL, resetSelector: NULL, resetToken: NULL, resetExpires: NULL, rememberSelector: NULL, rememberToken: NULL, rememberExpires: NULL);
                $buyinPaid = new BooleanString($row["result_paid_buyin_flag"]);
                $rebuyPaid = new BooleanString($row["result_paid_rebuy_flag"]);
                $addonPaid = new BooleanString($row["addon"]);
                $addonFlag = new BooleanString($row["result_addon_flag"]);
                $result = new Result(debug: $this->isDebug(), id: NULL, tournament: $tournament, player: $player, status: $status, registerOrder: (int) $row["result_registration_order"], buyinPaid: $buyinPaid->getBoolean(), rebuyPaid: $rebuyPaid->getBoolean(), addonPaid: $addonPaid->getBoolean(), rebuyCount: (int) $row["rebuy"], addonFlag: $addonFlag->getBoolean(), place: (int) $row["place"], knockedOutBy: $knockedOutBy, food: $row["result_registration_food"], feeStatus: $row["feeStatus"]);
                array_push($resultList, $result);
                break;
              case "resultSelectAllDuring":
                array_push($resultList, (int) $row["place"]);
                break;
              case "resultSelectLastEnteredDuring":
                array_push($resultList, (int) $row["player_id"]);
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
                array_push($resultList, $row["player_active_flag"]);
                array_push($resultList, $row["knockOutActive"]);
                break;
              case "resultAllOrderedPoints":
                array_push($resultList, $row["name"]);
                array_push($resultList, (int) $row["pts"]);
                array_push($resultList, (int) $row["avg"]);
                array_push($resultList, (int) $row["tourneys"]);
                array_push($resultList, $row["player_active_flag"]);
                break;
              case "resultAllOrderedEarnings":
                array_push($resultList, $row["name"]);
                array_push($resultList, (int) $row["earnings"]);
                array_push($resultList, (int) $row["max"]);
                array_push($resultList, (int) $row["avg"]);
                array_push($resultList, (int) $row["tourneys"]);
                array_push($resultList, $row["player_active_flag"]);
                break;
              case "resultAllOrderedKnockouts":
                array_push($resultList, $row["name"]);
                array_push($resultList, (int) $row["knockouts"]);
                array_push($resultList, (int) $row["avg"]);
                array_push($resultList, (int) $row["best"]);
                array_push($resultList, (int) $row["tourneys"]);
                array_push($resultList, $row["player_active_flag"]);
                break;
              case "resultAllOrderedKnockoutsStats":
                $resultListForPerson = array();
                $resultListForPerson["name"] = $row["name"];
                $resultListForPerson["kOs"] = $row["kOs"];
                $resultListForPerson["avgKo"] = $row["avgKo"];
                $resultListForPerson["bestKo"] = $row["bestKo"];
                $resultListForPerson["tourneys"] = $row["tourneys"];
                $resultListForPerson["active"] = $row["player_active_flag"];
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
                array_push($resultList, $row["player_active_flag"]);
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
                $resultListForPerson["active"] = $row["player_active_flag"];
                $resultList[(string) $row["name"]] = $resultListForPerson;
                break;
              case "resultPaidPlayerCount":
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
                $startDateTime = new DateTime(debug: $this->isDebug(), id: NULL, time: $row["season_start_date"]);
                $endDateTime = new DateTime(debug: $this->isDebug(), id: NULL, time: $row["season_end_date"]);
                $season = new Season(debug: $this->isDebug(), id: $row["id"], description: $row["season_description"], startDate: $startDateTime, endDate: $endDateTime, championshipQualify: (int) $row["# to qualify"], finalTablePlayers: (int) $row["season_final_table_players"], finalTableBonusPoints: (int) $row["season_final_table_bonus_points"], active: $row["season_active_flag"]);
                array_push($resultList, $season);
                break;
              case "seasonSelectOneByTournamentId":
                array_push($resultList, (int) $row["season_id"]);
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
                $values = array($row["player_id"],$row["name"],$row["fee status logic"],$row["fee status"],$row["amount"],$row["amount2"],$row["buyin status"],$row["rebuy status"],$row["result_rebuy_count"],$row["addon status"]);
                array_push($resultList, $values);
                break;
              case "statusSelectAll":
                $status = new Status(debug: $this->isDebug(), id: NULL, code: (int) $row["status_code"], name: NULL);
                array_push($resultList, $status);
                break;
              case "structureSelectAll":
              case "structurePayout":
                if ("structureSelectAll" == $dataName) {
                  $structure = new Structure(debug: $this->isDebug(), id: NULL, place: (int) $row["structure_place"], percentage: (float) $row["structure_percentage"]);
                  array_push($resultList, $structure);
                } else if ("structurePayout" == $dataName) {
                  $values = array($row["structure_place"], $row["structure_percentage"], (float) $row["pay"]);
                  array_push($resultList, $values);
                }
                break;
              case "tournamentIdMax":
                array_push($resultList, (int) $row["tournamentId"]);
                break;
              case "tournamentAll":
                $dateTime = new DateTime(debug: $this->isDebug(), id: NULL, time: $row["tournament_date"]);
                $tournament = new Tournament(debug: $this->isDebug(), id: $row["tournament_id"], description: $row["tournament_description"], comment: NULL, limitType: NULL, gameType: NULL, specialType: NULL, chipCount: 0, location: NULL, date: $dateTime, startTime: NULL, buyinAmount: 0, maxPlayers: 0, maxRebuys: 0, rebuyAmount: 0, addonAmount: 0, addonChipCount: 0, groupPayout: NULL, rake: 0, registeredCount: 0, buyinsPaid: 0, rebuysPaid: 0, rebuysCount: 0, addonsPaid: 0, enteredCount: 0);
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
              case "tournamentsSelectForEmailNotifications":
                $limitType = new LimitType(debug: $this->isDebug(), id: $row["limit_type_id"], name: $row["limit"]);
                $gameType = new GameType(debug: $this->isDebug(), id: $row["game_type_id"], name: $row["type"]);
                $specialType = new SpecialType(debug: $this->isDebug(), id: $row["special_type_id"], description: $row["std"], multiplier: $row["special_type_multiplier"]);
                $address = new Address(debug: $this->isDebug(), id: NULL, address: $row["location_address"], city: $row["location_city"], state: $row["location_state"], zip: (int) $row["location_zip_code"]);
                if ("tournamentsWonByPlayerId" != $dataName) {
                  $name = $row["playerName"];
                } else {
                  $name = "";
                }
                $phone = new Phone(debug: $this->isDebug(), id: NULL, value: $row["player_phone"]);
                $player = new Player(debug: $this->isDebug(), id: $row["player_id"], name: $name, username: NULL, password: NULL, email: NULL, phone: $phone, administrator: 0, registrationDate: NULL, approvalDate: NULL, approvalUserid: NULL, approvalName: NULL, rejectionDate: NULL, rejectionUserid: NULL, rejectionName: NULL, active: 0, address: $address, resetSelector: NULL, resetToken: NULL, resetExpires: NULL, rememberSelector: NULL, rememberToken: NULL, rememberExpires: NULL);
                $location = new Location(debug: $this->isDebug(), id: $row["location_id"], name: $row["location"], player: $player, count: 0, active: 0, map: $row["mapHide"], mapName: $row["map"], tournamentCount: 0);
                $dateTime = new DateTime(debug: $this->isDebug(), id: NULL, time: $row["date"]);
                $dateTimeStart = new DateTime(debug: $this->isDebug(), id: NULL, time: $row["start"]);
                if (! isset($row["std"]) || (isset($row["std"]) && strpos($row["std"], Constant::DESCRIPTION_CHAMPIONSHIP) === false)) {
                  $maxPlayers = (int) $row["max players"];
                } else {
                  $databaseResult = new DatabaseResult(debug: $this->isDebug());
                  $maxPlayers = (int) count($databaseResult->getChampionshipQualifiedPlayers(params: $paramsNested, returnQuery: false));
                }
                if ("tournamentsWonByPlayerId" != $dataName) {
                  $group = new Group($this->isDebug(), $row["group_id"], $row["name"]);
                  $groupPayout = new GroupPayout(debug: $this->isDebug(), id: NULL, group: $group, payouts: $this->getPayouts(groupId: (int) $row["group_id"], payoutId: NULL, structureFlag: true));
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
                $tournament = new Tournament(debug: $this->isDebug(), id: $row["id"], description: $row["description"], comment: $row["comment"], limitType: $limitType, gameType: $gameType, specialType: $specialType, chipCount: (int) $row["chips"], location: $location, date: $dateTime, startTime: $dateTimeStart, buyinAmount: (int) $row["buyin"], maxPlayers: $maxPlayers, maxRebuys: (int) $row["max"], rebuyAmount: (int) $row["amt"], addonAmount: (int) $row["amt "], addonChipCount: (int) $row["chips "], groupPayout: $groupPayout, rake: (float) ($row["rake"] * 100), registeredCount: $registeredCount, buyinsPaid: $buyinsPaid, rebuysPaid: $rebuysPaid, rebuysCount: $rebuysCount, addonsPaid: $addonsPaid, enteredCount: (int) $row["enteredCount"], earnings: $earnings);
                array_push($resultList, $tournament);
                break;
              case "tournamentSelectAllRegistrationStatus":
                array_push($resultList, (int) $row["tournament_id"]);
                array_push($resultList, $row["name"]);
                array_push($resultList, $row["status"]);
                array_push($resultList, $row["season fee"]);
                array_push($resultList, (int) $row["order"]);
                break;
              case "tournamentSelectAllDuring":
                array_push($resultList, (int) $row["tournament_id"]);
                $dateTime = new DateTime(debug: $this->isDebug(), id: NULL, time: $row["tournament_iddate"]);
                array_push($resultList, $dateTime);
                $dateTime = new DateTime(debug: $this->isDebug(), id: NULL, time: $row["tournament_start_time"]);
                array_push($resultList, $dateTime);
                array_push($resultList, $row["location_name"]);
                array_push($resultList, (int) $row["buyinsPaid"]);
                break;
              case "tournamentSelectAllYearsPlayed":
                array_push($resultList, (int) $row["year"]);
                break;
              case "tournamentsPlayedByPlayerIdAndDateRange":
                array_push($resultList, (int) $row["numPlayed"]);
                break;
              case "tournamentsPlayedByType":
                $object = array();
                $limitType = new LimitType(id: (int) $row["limit_type_id"], name: $row["limit type"]);
                array_push($object, $limitType);
                array_push($resultList, $object);
                $gameType = new GameType(id: (int) $row["game_type_id"], name: $row["game type"]);
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
                array_push($resultList, (int) $row["tournament_id"]);
                break;
              case "playerAbsencesByTournamentId":
                $values = array($row["player_id"],$row["name"]);
                array_push($resultList, $values);
                break;
              case "playerActive":
                $player = new Player(debug: $this->isDebug(), id: (int) $row["player_id"], name: $row["name"], username: NULL, password: NULL, email: $row["player_email"], phone: NULL, administrator: 0, registrationDate: NULL, approvalDate: NULL, approvalUserid: NULL, approvalName: NULL, rejectionDate: NULL, rejectionUserid: NULL, rejectionName: NULL, active: 1, address: NULL, resetSelector: NULL, resetToken: NULL, resetExpires: NULL, rememberSelector: NULL, rememberToken: NULL, rememberExpires: NULL);
                array_push($resultList, $player);
                break;
              case "playerPasswordReset":
                array_push($resultList, $row["player_token"]);
                break;
              case "playerSelectAll":
              case "playerSelectOneById":
              case "playerSelectOneByName":
              case "playerSelectOneByUsername":
              case "playerSelectOneByEmail":
              case "playerPaidByTournamentId":
                $phone = new Phone(debug: $this->isDebug(), id: NULL, value: $row[5]);
                $player = new Player(debug: $this->isDebug(), id: (int) $row["id"], name: $row["name"], username: $row[2], password: $row[3], email: $row[4], phone: $phone, administrator: (int) $row[6], registrationDate: $row[7], approvalDate: $row[8], approvalUserid: (int) $row[9], approvalName: $row[10], rejectionDate: $row[11], rejectionUserid: (int) $row[12], rejectionName: $row[13], active: (int) $row[14], address: NULL, resetSelector: NULL, resetToken: NULL, resetExpires: NULL, rememberSelector: NULL, rememberToken: NULL, rememberExpires: NULL);
                array_push($resultList, $player);
                break;
              case "playersSelectForEmailNotifications":
                $player = new Player(debug: $this->isDebug(), id: $row["player_id"], name: $row["name"], username: NULL, password: NULL, email: $row["player_email"], phone: NULL, administrator: 0, registrationDate: NULL, approvalDate: NULL, approvalUserid: 0, approvalName: NULL, rejectionDate: NULL, rejectionUserid: 0, rejectionName: NULL, active: 0, address: NULL, resetSelector: NULL, resetToken: NULL, resetExpires: NULL, rememberSelector: NULL, rememberToken: NULL, rememberExpires: NULL);
                array_push($resultList, $player);
                break;
              case "waitListedPlayerByTournamentId":
                array_push($resultList, $row["player_"]);
                array_push($resultList, $row["name"]);
                array_push($resultList, $row["player_email"]);
                array_push($resultList, (int) $row["tournament_max_players"]);
                break;
              case "winnersForSeason":
              case "winsForPlayer":
              case "winsTotalAndAverageForSeasonForPlayer":
                $object = array();
                array_push($object, (int) $row["player_id"]);
                array_push($object, $row["name"]);
                array_push($object, (int) $row["wins"]);
                array_push($object, (int) $row["avg"]);
                array_push($object, (int) $row["trnys"]);
                array_push($object, $row["player_active_flag"]);
                array_push($resultList, $object);
                break;
              case "winnersSelectAll":
                array_push($resultList, $row["name"]);
                array_push($resultList, $row["player_active_flag"]);
                array_push($resultList, (int) $row["wins"]);
                break;
              case "winnersSelectAllStats":
                $resultListForPerson = array();
                $resultListForPerson["name"] = $row["name"];
                $resultListForPerson["active"] = $row["player_active_flag"];
                $resultListForPerson["wins"] = $row["wins"];
                $resultList[(string) $row["name"]] = $resultListForPerson;
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
  private function getPayouts(?int $groupId, ?int $payoutId, bool $structureFlag): array {
    $payouts = array();
    $queryNested =
      "SELECT p.payout_id AS id, p.payout_name AS name, p.payout_min_players AS 'min players', p.payout_max_players AS 'max players' " .
      "FROM poker_payouts p ";
    if (isset($groupId)) {
      $queryNested .=
        " INNER JOIN poker_group_payouts gp ON gp.payout_id = p.payout_id" .
        " WHERE gp.group_id = :groupId";
    }
    if (isset($payoutId)) {
      if (isset($groupId)) {
        $queryNested .= " AND ";
      } else {
        $queryNested .= " WHERE ";
      }
      $queryNested .= "p.payout_id = :payoutId";
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
              "SELECT s.structure_place, s.structure_percentage " .
              "FROM poker_structures s " .
              "WHERE payout_id = :payoutId";
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
                  $structure = new Structure(debug: $this->isDebug(), id: NULL, place: (int) $rowNested2["structure_place"], percentage: (float) $rowNested2["structure_percentage"]);
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
    switch ($dataName) {
      case "feeBySeasonDelete":
        $query =
          "DELETE FROM poker_fees " .
          "WHERE season_id IN (:seasonId)";
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':seasonId', $params[0], PDO::PARAM_INT);
        break;
      case "feeBySeasonAndPlayerDelete":
        $query =
          "DELETE FROM poker_fees " .
          "WHERE season_id IN (:seasonId) " .
          "AND player_id = :playerId " .
          "AND fee_amount = 0";
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':seasonId', $params[0], PDO::PARAM_INT);
        $pdoStatement->bindParam(':playerId', $params[1], PDO::PARAM_INT);
        break;
      case "gameTypeDelete":
        $query =
          "DELETE FROM poker_game_types " .
          "WHERE game_type_id IN (:gameTypeId)";
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':gameTypeId', $params[0], PDO::PARAM_INT);
        break;
      case "groupDelete":
        $query =
          "DELETE FROM poker_groups " .
          "WHERE group_id IN (:groupId)";
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':groupId', $params[0], PDO::PARAM_INT);
        break;
      case "groupPayoutDelete":
        $query =
          "DELETE FROM poker_group_payouts " .
          "WHERE group_id IN (:groupId) " .
          "AND payout_id IN (:payoutId)";
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':groupId', $params[0], PDO::PARAM_INT);
        $pdoStatement->bindParam(':payoutId', $params[1], PDO::PARAM_INT);
        break;
      case "limitTypeDelete":
        $query =
          "DELETE FROM poker_limit_types " .
          "WHERE limit_type_id IN (:limitTypeId)";
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':limitTypeId', $params[0], PDO::PARAM_INT);
        break;
      case "locationDelete":
        $query =
          "DELETE FROM poker_locations " .
          "WHERE location_id IN (:locationId)";
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':locationId', $params[0], PDO::PARAM_INT);
        break;
      case "notificationDelete":
        $query =
          "DELETE FROM poker_notifications " .
          "WHERE notification_id IN (:notificationId)";
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':notificationId', $params[0], PDO::PARAM_INT);
        break;
      case "payoutDelete":
        $query =
          "DELETE FROM poker_payouts " .
          "WHERE payout_id IN (:payoutId)";
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':payoutId', $params[0], PDO::PARAM_INT);
        break;
      case "registrationDelete":
        $query =
          "DELETE FROM poker_results " .
          "WHERE tournament_id = :tournamentId AND player_id = :playerId";
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':tournamentId', $params[0], PDO::PARAM_INT);
        $pdoStatement->bindParam(':playerId', $params[1], PDO::PARAM_INT);
        break;
      case "resultDelete":
        $query =
          "DELETE FROM poker_results " .
          "WHERE tournament_id IN (:tournamentId)";
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':tournamentId', $params[0], PDO::PARAM_INT);
        break;
      case "seasonDelete":
        $query =
          "DELETE FROM poker_seasons " .
          "WHERE season_id IN (:seasonId)";
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':seasonId', $params[0], PDO::PARAM_INT);
        break;
      case "specialTypeDelete":
        $query =
        "DELETE FROM poker_special_types " .
        "WHERE special_type_id IN (:specialTypeId)";
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':specialTypeId', $params[0], PDO::PARAM_INT);
        break;
      case "structureDelete":
        $query =
          "DELETE FROM poker_structures " .
          "WHERE payout_id IN (:payoutId)";
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':payoutId', $params[0], PDO::PARAM_INT);
        break;
      case "tournamentDelete":
        $query =
          "DELETE FROM poker_tournaments " .
          "WHERE tournament_id IN (:tournamentId)";
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':tournamentId', $params[0], PDO::PARAM_INT);
        break;
      case "tournamentAbsenceDelete":
        $query =
        "DELETE FROM poker_tournament_absences " .
        "WHERE tournament_id IN (:tournamentId) " .
        "AND player_id = :playerId";
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
    return $numRecords;
  }
  // $dataName is name of query
  // $params is array of input parameters
  private function insertData(string $dataName, array $params = NULL): int|array {
    $numRecords = 0;
    // try {
    switch ($dataName) {
      case "blobInsert":
        $query = "INSERT INTO blob_test(name, content_type, blob_contents) VALUES(:name, :contentType, :blobContents)";
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':name', $params[0], PDO::PARAM_STR);
        $pdoStatement->bindParam(':contentType', $params[1], PDO::PARAM_STR);
        $pdoStatement->bindParam(':blobContents', $params[2], PDO::PARAM_LOB);
        break;
      case "feeInsert":
        $query =
          "INSERT INTO poker_fees(season_id, player_id, tournament_id, fee_amount) " .
          "VALUES(:seasonId, :playerId, :tournamentId, :amount)";
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':seasonId', $params[0], PDO::PARAM_INT);
        $pdoStatement->bindParam(':playerId', $params[1], PDO::PARAM_INT);
        $pdoStatement->bindParam(':tournamentId', $params[2], PDO::PARAM_INT);
        $pdoStatement->bindParam(':amount', $params[3], PDO::PARAM_INT);
        break;
      case "feePlayersForYearInsert":
        $query =
          "INSERT INTO poker_fees(season_id, player_id, tournament_id, fee_amount) " .
          "SELECT :seasonId, p.player_id, :tournamentId, 0 FROM poker_players p WHERE p.player_active_flag = " . Constant::FLAG_YES_DATABASE;
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':seasonId', $params[0], PDO::PARAM_INT);
        $pdoStatement->bindParam(':tournamentId', $params[1], PDO::PARAM_INT);
        print_r($params);
        echo $query;
        break;
      case "gameTypeInsert":
        $query =
          "INSERT INTO poker_game_types(game_type_id, game_type_name) " .
          "SELECT IFNULL(MAX(game_type_id), 0) + 1, :gameTypeName FROM poker_game_types";
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':gameTypeName', $params[0], PDO::PARAM_STR);
        break;
      case "groupInsert":
        $query =
          "INSERT INTO poker_groups(group_id, group_name) " .
          "SELECT IFNULL(MAX(group_id), 0) + 1, :groupName FROM poker_groups";
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':groupName', $params[0], PDO::PARAM_STR);
        break;
      case "groupPayoutInsert":
        $query =
          "INSERT INTO poker_group_payouts(group_id, payout_id) " .
          "VALUES(:groupId, :payoutId)";
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':groupId', $params[0], PDO::PARAM_INT);
        $pdoStatement->bindParam(':payoutId', $params[1], PDO::PARAM_INT);
        break;
      case "limitTypeInsert":
        $query =
        "INSERT INTO poker_limit_types(limit_type_id, limit_type_name) " .
        "SELECT IFNULL(MAX(limit_type_id), 0) + 1, :limitTypeName FROM poker_limit_types";
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':limitTypeName', $params[0], PDO::PARAM_STR);
        break;
      case "locationInsert":
        $query =
          "INSERT INTO poker_locations(location_id, location_name, player_id, location_address, location_city, location_state, location_zip_code) " .
          "SELECT IFNULL(MAX(location_id), 0) + 1, :locationName, :playerId, :address, :city, UPPER(:state), :zipCode FROM poker_locations";
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
          "INSERT INTO poker_notifications(notification_id, notification_description, notification_start_date, notification_end_date) " .
          "SELECT IFNULL(MAX(notification_id), 0) + 1, :description, :startDate, :endDate FROM poker_notifications";
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':description', $params[0], PDO::PARAM_STR);
        $pdoStatement->bindParam(':startDate', $params[1], PDO::PARAM_STR);
        $pdoStatement->bindParam(':endDate', $params[2], PDO::PARAM_STR);
        break;
      case "payoutInsert":
        $query =
          "INSERT INTO poker_payouts(payout_id, payout_name, payout_min_players, payout_max_players) " .
          "SELECT IFNULL(MAX(payout_id), 0) + 1, :payoutName, :minPlayers, :maxPlayers FROM poker_payouts";
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':payoutName', $params[0], PDO::PARAM_STR);
        $pdoStatement->bindParam(':minPlayers', $params[1], PDO::PARAM_INT);
        $pdoStatement->bindParam(':maxPlayers', $params[2], PDO::PARAM_INT);
        break;
      case "playerInsert":
        $query =
        "INSERT INTO poker_players(player_id, player_first_name, player_last_name, player_username, player_password, player_email, player_phone, player_administrator_flag, player_registration_date, player_approval_date, player_approval_player_id, player_rejection_date, player_rejection_player_id, player_active_flag, player_selector, player_token, player_expires) " .
        "SELECT MAX(player_id) + 1, :firstName, :lastName, :username, '" . password_hash($params[4], PASSWORD_DEFAULT) . "', :email, :phone, :administrator, IFNULL(:registrationDate, CURRENT_TIMESTAMP), :approvalDate, :approvalUserid, :rejectionDate, :rejectionUserId, :active, :selector, :token, :expires FROM poker_players";
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':firstName', $params[1], PDO::PARAM_STR);
        $pdoStatement->bindParam(':lastName', $params[2], PDO::PARAM_STR);
        $pdoStatement->bindParam(':username', $params[3], PDO::PARAM_STR);
        $pdoStatement->bindParam(':email', $params[5], PDO::PARAM_STR);
        $pdoStatement->bindParam(':phone', $params[6], PDO::PARAM_INT);
        $pdoStatement->bindParam(':administrator', $params[7], PDO::PARAM_INT);
        $pdoStatement->bindParam(':registrationDate', $params[8], PDO::PARAM_STR);
        $pdoStatement->bindParam(':approvalDate', $params[9], PDO::PARAM_STR);
        $pdoStatement->bindParam(':approvalUserid', $params[10], PDO::PARAM_INT);
        $pdoStatement->bindParam(':rejectionDate', $params[11], PDO::PARAM_STR);
        $pdoStatement->bindParam(':rejectionUserId', $params[12], PDO::PARAM_INT);
        $pdoStatement->bindParam(':active', $params[13], PDO::PARAM_INT);
        $pdoStatement->bindParam(':selector', $params[14], PDO::PARAM_STR);
        $pdoStatement->bindParam(':token', $params[15], PDO::PARAM_STR);
        $pdoStatement->bindParam(':expires', $params[16], PDO::PARAM_STR);
        break;
      case "registrationInsert":
        $query =
          "INSERT INTO poker_results(tournament_id, player_id, status_code, result_registration_order, result_paid_buyin_flag, result_paid_rebuy_flag, result_paid_addon_flag, result_rebuy_count, result_addon_flag, result_place_finished, player_id_ko, result_registration_food) " .
          "SELECT :tournamentId1, :playerId, :statusCode, IF(MAX(result_registration_order) IS NULL, 1, MAX(result_registration_order) + 1), :buyinPaidFlag, :rebuyPaidFlag, :addonPaidFlag, :rebuyCount, :addonFlag, :placeFinished, :playerIdKo, :food FROM poker_results WHERE tournament_id = :tournamentId2";
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':tournamentId1', $params[0], PDO::PARAM_INT);
        $pdoStatement->bindParam(':playerId', $params[1], PDO::PARAM_INT);
        $pdoStatement->bindValue(':statusCode', Constant::CODE_STATUS_REGISTERED, PDO::PARAM_STR);
        $pdoStatement->bindValue(':buyinPaidFlag', Constant::FLAG_NO, PDO::PARAM_STR);
        $pdoStatement->bindValue(':rebuyPaidFlag', Constant::FLAG_NO, PDO::PARAM_STR);
        $pdoStatement->bindValue(':addonPaidFlag', Constant::FLAG_NO, PDO::PARAM_STR);
        $pdoStatement->bindValue(':rebuyCount', 0, PDO::PARAM_INT);
        $pdoStatement->bindValue(':addonFlag', Constant::FLAG_NO, PDO::PARAM_STR);
        $pdoStatement->bindValue(':placeFinished', 0, PDO::PARAM_INT);
        $pdoStatement->bindValue(':playerIdKo', NULL, PDO::PARAM_STR);
        $pdoStatement->bindParam(':food', $params[2], PDO::PARAM_STR);
        $pdoStatement->bindParam(':tournamentId2', $params[0], PDO::PARAM_INT);
        break;
      case "seasonInsert":
        $query =
          "INSERT INTO poker_seasons(season_id, season_description, season_start_date, season_end_date, season_championship_qualification_count, season_final_table_players, season_final_table_bonus_points, season_fee, season_active_flag) " .
          "SELECT IFNULL(MAX(season_id), 0) + 1, :seasonDescription, :seasonStartDate, :seasonEndDate, :seasonChampionshipQualify, :seasonFinalTablePlayers, :seasonFinalTableBonusPoints, :seasonFee, :seasonActive FROM poker_seasons";
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':seasonDescription', $params[0], PDO::PARAM_STR);
        $pdoStatement->bindParam(':seasonStartDate', $params[1], PDO::PARAM_STR);
        $pdoStatement->bindParam(':seasonEndDate', $params[2], PDO::PARAM_STR);
        $pdoStatement->bindParam(':seasonChampionshipQualify', $params[3], PDO::PARAM_INT);
        $pdoStatement->bindParam(':seasonFinalTablePlayers', $params[4], PDO::PARAM_INT);
        $pdoStatement->bindParam(':seasonFinalTableBonusPoints', $params[5], PDO::PARAM_INT);
        $pdoStatement->bindParam(':seasonFee', $params[6], PDO::PARAM_INT);
        $pdoStatement->bindParam(':seasonActive', $params[7], PDO::PARAM_INT);
        break;
      case "specialTypeInsert":
        $query =
          "INSERT INTO poker_special_types(special_type_id, special_type_description, special_type_multiplier) " .
          "SELECT IFNULL(MAX(special_type_id), 0) + 1, :typeDescription, :typeMultiplier FROM poker_special_types";
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':typeDescription', $params[0], PDO::PARAM_STR);
        $pdoStatement->bindParam(':typeMultiplier', $params[1], PDO::PARAM_INT);
        break;
      case "structureInsert":
        $query =
          "INSERT INTO poker_structures(payout_id, structure_place, structure_percentage) " .
          "VALUES(:payoutId, :place, :percentage)";
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':payoutId', $params[0], PDO::PARAM_INT);
        $pdoStatement->bindParam(':place', $params[1], PDO::PARAM_INT);
        $pdoStatement->bindParam(':percentage', $params[2], PDO::PARAM_STR); // for non integer use string
        break;
      case "tournamentInsert":
        $query =
          "INSERT INTO poker_tournaments(tournament_id, tournament_description, tournament_comment, limit_type_id, game_type_id, tournament_chip_count, location_id, tournament_date, tournament_start_time, tournament_buyin_amount, tournament_max_players, tournament_max_rebuys, tournament_rebuy_amount, tournament_addon_amount, tournament_addon_chip_count, group_id, tournament_rake, tournament_map, special_type_id) " .
          "SELECT IFNULL(MAX(tournament_id), 0) + 1, :tournamentDesc, :comment, :limitTypeId, :gameTypeId, :chipCount, :locationId, :tournamentDate, :startTime, :buyinAmount, :maxPlayers, :maxRebuys, :rebuyAmount, :addonAmount, :addonChipCount, :groupId, :rake, :map, NULLIF(:specialTypeId, '') FROM poker_tournaments";
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':tournamentDesc', $params[0], PDO::PARAM_STR);
        $pdoStatement->bindParam(':comment', $params[1], PDO::PARAM_STR);
        $pdoStatement->bindParam(':limitTypeId', $params[2], PDO::PARAM_INT);
        $pdoStatement->bindParam(':gameTypeId', $params[3], PDO::PARAM_INT);
        $pdoStatement->bindParam(':chipCount', $params[4], PDO::PARAM_INT);
        $pdoStatement->bindParam(':locationId', $params[5], PDO::PARAM_INT);
        $pdoStatement->bindParam(':tournamentDate', $params[6], PDO::PARAM_STR);
        $pdoStatement->bindParam(':startTime', $params[7], PDO::PARAM_STR);
        $pdoStatement->bindParam(':buyinAmount', $params[8], PDO::PARAM_INT);
        $pdoStatement->bindParam(':maxPlayers', $params[9], PDO::PARAM_INT);
        $pdoStatement->bindParam(':maxRebuys', $params[10], PDO::PARAM_INT);
        $pdoStatement->bindParam(':rebuyAmount', $params[11], PDO::PARAM_INT);
        $pdoStatement->bindParam(':addonAmount', $params[12], PDO::PARAM_INT);
        $pdoStatement->bindParam(':addonChipCount', $params[13], PDO::PARAM_INT);
        $pdoStatement->bindParam(':groupId', $params[14], PDO::PARAM_INT);
        $pdoStatement->bindParam(':rake', $params[15], PDO::PARAM_STR); // for non integer use string
        $pdoStatement->bindParam(':map', $params[16], PDO::PARAM_STR);
        $pdoStatement->bindParam(':specialTypeId', $params[17], PDO::PARAM_STR); // for NULL number use string
        break;
      case "tournamentAbsenceInsert":
        $query =
        "INSERT INTO poker_tournament_absences(tournament_id, player_id) " .
        "VALUES(:tournamentId, :playerId)";
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
    return $numRecords;
  }
  // $dataName is query name
  // $params is array of input parameters
  private function updateData(string $dataName, array $params = NULL): int|array {
    $numRecords = 0;
    switch ($dataName) {
      case "buyinsUpdate":
        $query =
          "UPDATE poker_results " .
          "SET status_code = :statusCode, " .
          "    result_paid_buyin_flag = :buyinPaid, " .
          "    result_paid_rebuy_flag = :rebuyPaid, " .
          "    result_paid_addon_flag = :addonPaid, " .
          "    result_rebuy_count = :result_rebuy_count " .
          "WHERE tournament_id = :tournamentId " .
          "AND player_id = :playerId";
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':statusCode', $params[0], PDO::PARAM_STR);
        $pdoStatement->bindParam(':buyinPaid', $params[1], PDO::PARAM_STR);
        $pdoStatement->bindParam(':rebuyPaid', $params[2], PDO::PARAM_STR);
        $pdoStatement->bindParam(':addonPaid', $params[3], PDO::PARAM_STR);
        $pdoStatement->bindParam(':result_rebuy_count', $params[4], PDO::PARAM_INT);
        $pdoStatement->bindParam(':tournamentId', $params[5], PDO::PARAM_INT);
        $pdoStatement->bindParam(':playerId', $params[6], PDO::PARAM_INT);
        break;
      case "feesUpdate":
        $query =
          "UPDATE poker_fees " .
          "SET fee_amount = :amount " .
          "WHERE season_id = :seasonId " .
          "AND player_id = :playerId " .
          "AND tournament_id = :tournamentId";
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':amount', $params[0], PDO::PARAM_INT);
        $pdoStatement->bindParam(':seasonId', $params[1], PDO::PARAM_INT);
        $pdoStatement->bindParam(':playerId', $params[2], PDO::PARAM_INT);
        $pdoStatement->bindParam(':tournamentId', $params[3], PDO::PARAM_INT);
        break;
      case "gameTypeUpdate":
        $query =
          "UPDATE poker_game_types " .
          "SET game_type_name = :gameTypeName " .
          "WHERE game_type_id = :gameTypeId";
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindValue(':gameTypeName', trim($params[1]), PDO::PARAM_STR);
        $pdoStatement->bindParam(':gameTypeId', $params[0], PDO::PARAM_INT);
        break;
      case "groupUpdate":
        $query =
          "UPDATE poker_groups " .
          "SET group_name = :groupName " .
          "WHERE group_id = :groupId";
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':groupName', $params[0], PDO::PARAM_STR);
        $pdoStatement->bindParam(':groupId', $params[1], PDO::PARAM_INT);
        break;
      case "groupPayoutUpdate":
        $query =
          "UPDATE poker_group_payouts " .
          "SET group_id = :groupId1, " .
          "    payout_id = :payoutId1 " .
          "WHERE group_id = :groupId2 " .
          "AND payout_id = :payoutId2";
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':groupId1', $params[0], PDO::PARAM_INT);
        $pdoStatement->bindParam(':payoutId1', $params[1], PDO::PARAM_INT);
        $pdoStatement->bindParam(':groupId2', $params[2], PDO::PARAM_INT);
        $pdoStatement->bindParam(':payoutId2', $params[3], PDO::PARAM_INT);
        break;
      case "limitTypeUpdate":
        $query =
          "UPDATE poker_limit_types " .
          "SET limit_type_name = :limitTypeName " .
          "WHERE limit_type_id = :limitTypeId";
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindValue(':limitTypeName', trim($params[1]), PDO::PARAM_STR);
        $pdoStatement->bindParam(':limitTypeId', $params[0], PDO::PARAM_INT);
        break;
      case "locationUpdate":
        $query =
          "UPDATE poker_locations " .
          "SET location_name = :locationName, " .
          "    player_id = :playerId, " .
          "    location_address = :address, " .
          "    location_city = :city, " .
          "    location_state = UPPER(:state), " .
          "    location_zip_code = :zipCode " .
          "WHERE location_id = :locationId";
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
          "UPDATE poker_notifications " .
          "SET notification_description = :description, " .
          "    notification_start_date = :startDate, " .
          "    notification_end_date = :endDate " .
          "WHERE notification_id = :notificationId";
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':description', $params[1], PDO::PARAM_STR);
        $pdoStatement->bindParam(':startDate', $params[2], PDO::PARAM_STR);
        $pdoStatement->bindParam(':endDate', $params[3], PDO::PARAM_STR);
        $pdoStatement->bindParam(':notificationId', $params[0], PDO::PARAM_INT);
        break;
      case "payoutUpdate":
        $query =
          "UPDATE poker_payouts " .
          "SET payout_name = :payoutName, " .
          "    payout_min_players = :minPlayers, " .
          "    payout_max_players = :maxPlayers " .
          "WHERE payout_id = :payoutId";
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':payoutName', $params[0], PDO::PARAM_STR);
        $pdoStatement->bindParam(':minPlayers', $params[1], PDO::PARAM_INT);
        $pdoStatement->bindParam(':maxPlayers', $params[2], PDO::PARAM_INT);
        $pdoStatement->bindParam(':payoutId', $params[3], PDO::PARAM_INT);
        break;
      case "playerUpdate":
        $validValues = array(0,1);
        $query = "UPDATE poker_players SET";
        if (isset($params[0])) {
          $query .= " player_id = :playerId";
        }
        if (isset($params[1])) {
          if (isset($params[0])) {
            $query .= ", ";
          }
          $query .= " player_first_name = :firstName";
        }
        if (isset($params[2])) {
          if (isset($params[0]) || isset($params[1])) {
            $query .= ", ";
          }
          $query .= " player_last_name = :lastName";
        }
        if (isset($params[3])) {
          if (isset($params[0]) || isset($params[1]) || isset($params[2])) {
            $query .= ", ";
          }
          $query .= " player_username = :username";
        }
        if (isset($params[4])) {
          if (isset($params[0]) || isset($params[1]) || isset($params[2]) || isset($params[3])) {
            $query .= ", ";
          }
          $query .= " player_password = '" . password_hash($params[4], PASSWORD_DEFAULT) . "'";
        }
        if (isset($params[5])) {
          if (isset($params[0]) || isset($params[1]) || isset($params[2]) || isset($params[3]) || isset($params[4])) {
            $query .= ", ";
          }
          $query .= " player_email = :email";
        }
        if (isset($params[6])) {
          if (isset($params[0]) || isset($params[1]) || isset($params[2]) || isset($params[3]) || isset($params[4]) || isset($params[5])) {
            $query .= ", ";
          }
          $query .= " player_phone = :phone";
        }
        if (isset($params[7]) && in_array($params[7], $validValues)) {
          if (isset($params[0]) || isset($params[1]) || isset($params[2]) || isset($params[3]) || isset($params[4]) || isset($params[5]) || isset($params[6])) {
            $query .= ", ";
          }
          $query .= " player_administrator_flag = :administrator";
        }
        if (isset($params[8])) {
          if (isset($params[0]) || isset($params[1]) || isset($params[2]) || isset($params[3]) || isset($params[4]) || isset($params[5]) || isset($params[6]) || isset($params[7])) {
            $query .= ", ";
          }
          $query .= " player_registration_date = :registrationDate";
        }
        if (isset($params[9])) {
          if (isset($params[0]) || isset($params[1]) || isset($params[2]) || isset($params[3]) || isset($params[4]) || isset($params[5]) || isset($params[6]) || isset($params[7]) ||
            isset($params[8])) {
              $query .= ", ";
            }
            $query .= " player_approval_date = IF(:approvalDate1 = '', CURRENT_TIMESTAMP, :approvalDate2)";
        }
        if (isset($params[10])) {
          if (isset($params[0]) || isset($params[1]) || isset($params[2]) || isset($params[3]) || isset($params[4]) || isset($params[5]) || isset($params[6]) || isset($params[7]) ||
            isset($params[8]) || isset($params[9])) {
              $query .= ", ";
            }
            $query .= " player_approval_player_id = :approvalUserId";
        }
        if (isset($params[11])) {
          if (isset($params[0]) || isset($params[1]) || isset($params[2]) || isset($params[3]) || isset($params[4]) || isset($params[5]) || isset($params[6]) || isset($params[7]) ||
            isset($params[8]) || isset($params[9]) || isset($params[10])) {
              $query .= ", ";
            }
            $query .= " player_rejection_date = IF(:rejectionDate1 = '', CURRENT_TIMESTAMP, :rejectionDate2)";
        }
        if (isset($params[12])) {
          if (isset($params[0]) || isset($params[1]) || isset($params[2]) || isset($params[3]) || isset($params[4]) || isset($params[5]) || isset($params[6]) || isset($params[7]) ||
            isset($params[8]) || isset($params[9]) || isset($params[10]) || isset($params[11])) {
              $query .= ", ";
            }
            $query .= " player_rejection_player_id = :rejectionUserId";
        }
        if (isset($params[13]) && in_array($params[13], $validValues)) {
          if (isset($params[0]) || isset($params[1]) || isset($params[2]) || isset($params[3]) || isset($params[4]) || isset($params[5]) || isset($params[6]) || isset($params[7]) ||
            isset($params[8]) || isset($params[9]) || isset($params[10]) || isset($params[11]) || isset($params[12])) {
              $query .= ", ";
            }
            $query .= " player_active_flag = :active";
        }
        if (isset($params[14])) {
          $query .= " player_selector = :selector";
        }
        if (isset($params[15])) {
          $query .= " player_token = :token";
        }
        if (isset($params[16])) {
          $query .= " player_expires = :expires";
        }
        $query .= " WHERE player_id = :playerId2";
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        if (isset($params[0])) {
          $pdoStatement->bindParam(':playerId', $params[0], PDO::PARAM_INT);
          $pdoStatement->bindParam(':playerId2', $params[0], PDO::PARAM_INT);
        }
        if (isset($params[1])) {
          $pdoStatement->bindParam(':firstName', $params[1], PDO::PARAM_STR);
        }
        if (isset($params[2])) {
          $pdoStatement->bindParam(':lastName', $params[2], PDO::PARAM_STR);
        }
        if (isset($params[3])) {
          $pdoStatement->bindParam(':username', $params[3], PDO::PARAM_STR);
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
      case "playerUpdateReset":
        $selector = bin2hex(random_bytes(length: 8));
        $token = random_bytes(length: 32);
        $expires = new \DateTime("NOW");
        $expires->add(new \DateInterval("P1D")); // 1 day
        $hash = hash('sha256', $token);
        $query =
          "UPDATE poker_players " .
          "SET player_selector = :selector, token = :token, expires = :expires " .
          "WHERE player_username = :userName " .
          "AND player_email = :email";
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':selector', $selector, PDO::PARAM_STR);
        $pdoStatement->bindParam(':userName', $params[0], PDO::PARAM_STR);
        $pdoStatement->bindParam(':email', $params[1], PDO::PARAM_STR);
        $pdoStatement->bindParam(':token', $hash, PDO::PARAM_STR);
        $pdoStatement->bindValue(':expires', $expires->format('U'), PDO::PARAM_STR);
        break;
      case "playerUpdateChangePassword":
        $hash = password_hash($params[2], PASSWORD_DEFAULT);
        $query =
          "UPDATE poker_players " .
          "SET player_password = :password, player_selector = NULL, player_token = NULL, player_expires = NULL " .
          "WHERE player_username = :userName " .
          "AND player_email = :email";
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':password', $hash, PDO::PARAM_STR);
        $pdoStatement->bindParam(':userName', $params[0], PDO::PARAM_STR);
        $pdoStatement->bindParam(':email', $params[1], PDO::PARAM_STR);
        break;
      case "registrationUpdate":
        $query =
          "UPDATE poker_results " .
          "SET result_registration_food = :food " .
          "WHERE tournament_id = :tournamentId " .
          "AND player_id = :playerId";
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':food', $params[0], PDO::PARAM_STR);
        $pdoStatement->bindParam(':tournamentId', $params[1], PDO::PARAM_INT);
        $pdoStatement->bindParam(':playerId', $params[2], PDO::PARAM_INT);
        break;
      case "registrationCancelUpdate":
        $query =
          "UPDATE poker_results " .
          "SET result_registration_order = result_registration_order - 1 " .
          "WHERE tournament_id = :tournamentId " .
          "AND result_registration_order > :registerOrder";
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':tournamentId', $params[0], PDO::PARAM_INT);
        $pdoStatement->bindParam(':registerOrder', $params[1], PDO::PARAM_INT);
        break;
      case "resultUpdate":
        $query =
          "UPDATE poker_results " .
          "SET " . (isset($params[0]) ? "result_rebuy_count = :result_rebuy_count, " : "") .
          (isset($params[1]) ? "result_paid_rebuy_flag = :rebuyPaid, " : "") .
          (isset($params[2]) ? " result_paid_addon_flag = :addonPaid, " : "") .
          "status_code = :statusCode, result_place_finished = :place, player_id_ko = :knockedOutBy WHERE tournament_id = :tournamentId";
        if (isset($params[7])) {
          $query .= " AND player_id IN (:playerId)";
        }
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':statusCode', $params[3], PDO::PARAM_STR);
        $pdoStatement->bindParam(':place', $params[4], PDO::PARAM_INT);
        $pdoStatement->bindParam(':knockedOutBy', $params[5], PDO::PARAM_INT);
        $pdoStatement->bindParam(':tournamentId', $params[6], PDO::PARAM_INT);
        if (isset($params[0])) {
          $pdoStatement->bindParam(':result_rebuy_count', $params[0], PDO::PARAM_INT);
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
          "UPDATE poker_results " .
          "SET status_code = :statusCode, result_place_finished = :place, player_id_ko = :knockedOutBy " .
          "WHERE tournament_id = :tournamentId " .
          "AND player_id = :playerId";
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':statusCode', $params[0], PDO::PARAM_STR);
        $pdoStatement->bindParam(':place', $params[1], PDO::PARAM_INT);
        $pdoStatement->bindParam(':knockedOutBy', $params[2], PDO::PARAM_INT);
        $pdoStatement->bindParam(':tournamentId', $params[3], PDO::PARAM_INT);
        $pdoStatement->bindParam(':playerId', $params[4], PDO::PARAM_INT);
        break;
      case "resultUpdateByTournamentIdAndPlace":
        $query =
          "UPDATE poker_results " .
          "SET result_paid_buyin_flag = " . Constant::FLAG_YES . (isset($params[0]) ? ", result_rebuy_count = :rebuyCount, " : "") .
          (isset($params[1]) ? "result_paid_rebuy_flag = :rebuyPaid, " : "") .
          (isset($params[2]) ? " result_paid_addon_flag = :addonPaid, " : "") .
          "status_code = :statusCode, result_place_finished = :place, player_id_ko = :knockedOutBy " .
          "WHERE tournament_id = :tournamentId";
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
          "UPDATE poker_results " .
          "SET " . (isset($params[0]) ? "result_rebuy_count = :result_rebuy_count" : "") .
          (isset($params[1]) ? (isset($params[0]) ? ", " : "") . "result_paid_rebuy_flag = :rebuyPaid" : "") .
          (isset($params[2]) ? (isset($params[1]) ? ", " : "") . "result_paid_addon_flag = :addonPaid" : "") .
          (isset($params[3]) ? (isset($params[2]) ? ", " : "") . "result_addon_flag = :rebuyPaid" : "") .
          " WHERE tournament_id = :tournamentId";
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindParam(':tournamentId', $params[4], PDO::PARAM_INT);
        if (isset($params[0])) {
          $pdoStatement->bindParam(':result_rebuy_count', $params[0], PDO::PARAM_INT);
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
          "UPDATE poker_seasons " .
          "SET season_description = :seasonDescription, " .
          "    season_start_date = :seasonStartDate, " .
          "    season_end_date = :seasonEndDate, " .
          "    season_championship_qualification_count = :seasonChampionshipQualify, " .
          "    season_final_table_players = :seasonFinalTablePlayers, " .
          "    season_final_table_bonus_points = :seasonFinalTableBonusPoints, " .
          "    season_fee = :seasonFee, " .
          "    season_active_flag = :seasonActive " .
          "WHERE season_id = :seasonId";
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
          "UPDATE poker_special_types " .
          "SET special_type_description = :typeDescription, " .
          "    special_type_multiplier = :typeMultiplier " .
          "WHERE special_type_id = :specialTypeId";
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindValue(':typeDescription', trim($params[1]), PDO::PARAM_STR);
        $pdoStatement->bindParam(':typeMultiplier', $params[2], PDO::PARAM_INT);
        $pdoStatement->bindParam(':specialTypeId', $params[0], PDO::PARAM_INT);
        break;
      case "tournamentUpdate":
        $query =
          "UPDATE poker_tournaments " .
          "SET tournament_description = :tournamentDesc, tournament_comment = :comment, limit_type_id = :limitTypeId, game_type_id = :gameTypeId, tournament_chip_count = :chipCount, location_id = :locationId, " .
          "tournament_date = :tournamentDate, tournament_start_time = :startTime, tournament_buyin_amount = :buyinAmount, tournament_max_players = :maxPlayers, tournament_max_rebuys = :maxRebuys, " .
          "tournament_rebuy_amount = :rebuyAmount, tournament_addon_amount = :addonAmount, tournament_addon_chip_count = :addonChipCount, group_id = :groupId, tournament_rake = :rake, tournament_map = :map, special_type_id = NULLIF(:specialTypeId, '') " .
          "WHERE tournament_id = :tournamentId";
        $pdoStatement = $this->getConnection()->prepare(query: $query);
        $pdoStatement->bindValue(':tournamentDesc', trim($params[1]), PDO::PARAM_STR);
        $pdoStatement->bindValue(':comment', trim($params[2]), PDO::PARAM_STR);
        $pdoStatement->bindParam(':limitTypeId', $params[3], PDO::PARAM_INT);
        $pdoStatement->bindParam(':gameTypeId', $params[4], PDO::PARAM_INT);
        $pdoStatement->bindParam(':chipCount', $params[5], PDO::PARAM_INT);
        $pdoStatement->bindParam(':locationId', $params[6], PDO::PARAM_INT);
        $pdoStatement->bindParam(':tournamentDate', $params[7], PDO::PARAM_STR);
        $pdoStatement->bindParam(':startTime', $params[8], PDO::PARAM_STR);
        $pdoStatement->bindParam(':buyinAmount', $params[9], PDO::PARAM_INT);
        $pdoStatement->bindParam(':maxPlayers', $params[10], PDO::PARAM_INT);
        $pdoStatement->bindParam(':maxRebuys', $params[11], PDO::PARAM_INT);
        $pdoStatement->bindParam(':rebuyAmount', $params[12], PDO::PARAM_INT);
        $pdoStatement->bindParam(':addonAmount', $params[13], PDO::PARAM_INT);
        $pdoStatement->bindParam(':addonChipCount', $params[14], PDO::PARAM_INT);
        $pdoStatement->bindParam(':groupId', $params[15], PDO::PARAM_INT);
        $pdoStatement->bindParam(':rake', $params[16], PDO::PARAM_STR); // for non integer use string
        $pdoStatement->bindValue(':map', trim($params[17]), PDO::PARAM_STR);
        $pdoStatement->bindParam(':specialTypeId', $params[18], PDO::PARAM_STR); // for NULL number use string
        $pdoStatement->bindParam(':tournamentId', $params[0], PDO::PARAM_INT);
        break;
    }
    $pdoStatement->execute();
    if ($this->isDebug()) {
      echo "<br>" . $pdoStatement->debugDumpParams();
    }
    if ($pdoStatement->errorCode() == "00000") {
      if ($dataName == "playerUpdateReset" || $dataName == "playerUpdateRememberMe") {
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
    return $numRecords;
  }
  // $prefix is table alias
  private function buildOrderByName(?string $prefix): string {
    $alias = isset($prefix) ? $prefix . "." : "";
    return $alias . "player_last_name, " . $alias . "player_first_name";
  }
  // returns array of query and array of parameters to bind
  private function buildChampionship(array $params): array {
    $bindParams = NULL;
    $query =
      "SELECT se.season_start_date, YEAR(t.tournament_date) AS Yr, p.player_id, p.player_first_name, p.player_last_name, CONCAT(p.player_first_name, ' ', p.player_last_name) AS name, " .
      "       qq.total * IFNULL(s.structure_percentage, 0) AS Earnings, numTourneys AS trnys " .
      "FROM poker_results r " .
      "INNER JOIN poker_players p ON r.player_id = p.player_id " .
      "INNER JOIN poker_tournaments t ON r.tournament_id = t.tournament_id ";
    if (isset($params[0]) && isset($params[1])) {
      $query .= "            AND t.tournament_date BETWEEN :tournamentStartDate91 AND :tournamentEndDate91 ";
      $bindParams[':tournamentStartDate91'] = $params[0];
      $bindParams[':tournamentEndDate91'] = $params[1];
    }
    $query .=
      "INNER JOIN poker_seasons se ON t.tournament_date BETWEEN se.season_start_date AND se.season_end_date " .
      "INNER JOIN (SELECT season_start_date, season_end_date, SUM(total) - IF(YEAR(season_end_date) = 2008, 150, IF(YEAR(season_end_date) = 2007, -291, IF(YEAR(season_end_date) = 2006, -824, 0))) AS total " .
      "            FROM (SELECT se2.season_start_date, se2.season_end_date, t2.tournament_id AS Id, IF(b.Play IS NULL, 0, CONCAT(b.Play, '+', IFNULL(nr.NumRebuys, 0) , 'r', '+', IFNULL(na.NumAddons, 0) , 'a')) AS Play, ((t2.tournament_buyin_amount * t2.tournament_Rake) * Play) + ((t2.tournament_rebuy_amount * t2.tournament_rake) * IFNULL(nr.NumRebuys, 0) ) + ((t2.tournament_addon_amount * t2.tournament_rake) * IFNULL(na.NumAddons, 0) ) AS Total " .
      "                  FROM poker_tournaments t2 " .
      "                  INNER JOIN poker_seasons se2 ON t2.tournament_date BETWEEN se2.season_start_date AND se2.season_end_date " .
      "                  LEFT JOIN (SELECT tournament_id, COUNT(*) AS Play FROM poker_results WHERE result_paid_buyin_flag = '" . Constant::FLAG_YES .
      "' AND result_place_finished > 0 GROUP BY tournament_id) b ON t2.tournament_id = b.tournament_id " .
      "                  LEFT JOIN (SELECT r.tournament_id, SUM(r.result_rebuy_count) AS NumRebuys FROM poker_results r WHERE r.result_paid_rebuy_flag = '" . Constant::FLAG_YES .
      "' AND r.result_rebuy_count > 0 GROUP BY r.tournament_id) nr ON t2.tournament_id = nr.tournament_id " .
      "                  LEFT JOIN (SELECT r.tournament_id, COUNT(*) AS NumAddons FROM poker_results r WHERE r.result_paid_addon_flag = '" . Constant::FLAG_YES .
      "' GROUP BY r.tournament_id) na ON t2.tournament_id = na.tournament_id) zz " .
      "            GROUP BY season_start_date, season_end_date) qq ON qq.season_start_date = se.season_start_date AND qq.season_end_date = se.season_end_date " .
      "LEFT JOIN poker_special_types st ON t.special_type_id = st.special_type_id " .
      "INNER JOIN (SELECT r1.player_id, COUNT(*) AS NumTourneys FROM poker_results r1 INNER JOIN poker_tournaments t1 ON r1.tournament_id = t1.tournament_id AND r1.result_place_finished > 0 INNER JOIN poker_special_types st1 ON t1.special_type_id = st1.special_type_id AND st1.special_type_description = '" . Constant::DESCRIPTION_CHAMPIONSHIP . "' GROUP BY r1.player_id) nt ON r.player_id = nt.player_id " .
      "LEFT JOIN (SELECT a.tournament_id, s1.payout_id, s1.structure_place, s1.structure_percentage " .
      "          FROM (SELECT np.tournament_id, p.payout_id " .
      "                FROM (SELECT r.tournament_id, COUNT(*) AS numPlayers FROM poker_results r WHERE r.result_place_finished > 0 AND r.status_code IN ('" . Constant::CODE_STATUS_REGISTERED . "','" . Constant::CODE_STATUS_FINISHED . "') GROUP BY r.tournament_id) np " .
      "                INNER JOIN poker_tournaments t on np.tournament_id = t.tournament_id ";
    if (isset($params[0]) && isset($params[1])) {
      $query .= "            AND t.tournament_date BETWEEN :tournamentStartDate92 AND :tournamentEndDate92 ";
      $bindParams[':tournamentStartDate92'] = $params[0];
      $bindParams[':tournamentEndDate92'] = $params[1];
    }
    $query .=
      "                INNER JOIN poker_group_payouts gp ON t.group_id = gp.group_id " .
      "                INNER JOIN poker_payouts p ON gp.payout_id = p.payout_id AND np.numPlayers BETWEEN p.payout_min_players AND p.payout_max_players) a " .
      "          INNER JOIN poker_structures s1 ON a.payout_id = s1.payout_id) s ON r.tournament_id = s.tournament_id AND r.result_place_finished = s.structure_place " .
      "WHERE r.result_place_finished > 0 " . "AND st.special_type_description = '" . Constant::DESCRIPTION_CHAMPIONSHIP . "' ";
    return array($query, $bindParams);
  }
  // $alias is table alias
  private function buildPlayerActive(?string $alias = NULL): string {
    return (isset($alias) ? $alias . "." : "") . "player_active_flag = " . Constant::FLAG_YES_DATABASE;
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
    $queryTemp = str_replace(search: $whereClause, replace: "ORDER BY " . $selectFieldName . " DESC, player_last_name, player_first_name) z ORDER BY row, name", subject: $queryTemp);
    return $queryTemp;
  }
}
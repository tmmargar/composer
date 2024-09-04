<?php
require_once "bootstrap.php";
use Doctrine\Common\Collections\ArrayCollection;
use Poker\Ccp\Entity\Fees;
use Poker\Ccp\Entity\GameTypes;
use Poker\Ccp\Entity\Groups;
use Poker\Ccp\Entity\GroupPayouts;
use Poker\Ccp\Entity\LimitTypes;
use Poker\Ccp\Entity\Locations;
use Poker\Ccp\Entity\Notifications;
use Poker\Ccp\Entity\Payouts;
use Poker\Ccp\Entity\Players;
use Poker\Ccp\Entity\Results;
use Poker\Ccp\Entity\Seasons;
use Poker\Ccp\Entity\SpecialTypes;
use Poker\Ccp\Entity\Structures;
use Poker\Ccp\Entity\Tournaments;
use Poker\Ccp\Model\Constant;
use Poker\Ccp\Utility\DateTimeUtility;
date_default_timezone_set(timezoneId: "America/New_York");
$entityManager = getEntityManager();
// $fe = new Fees();
// $fe->setFeeAmount(3);
// $player = $entityManager->find(Constant::ENTITY_PLAYERS, 35);
// // echo "<br>player has fees count = " . count($player->getFees());
// // echo "<br>Fees player name=" . $player->getPlayerName();
// $fe->setPlayers($player);
// $season = $entityManager->find(Constant::ENTITY_SEASONS, 18);
// // echo "<br>season has fees count = " . count($season->getFees());
// // echo "<br>Fees season start date=" . DateTimeUtility::formatDisplayDateTime(value: $season->getSeasonStartDate());
// $fe->setSeasons($season);
// $tournament = $entityManager->find(Constant::ENTITY_TOURNAMENTS, 500);
// // echo "<br>tournament has fees count = " . count($tournament->getFees());
// // echo "<br>Fees tournament date=" . DateTimeUtility::formatDisplayDateTime(value: $tournament->getTournamentDate());
// $fe->setTournaments($tournament);
// $entityManager->persist($fe);
// $entityManager->flush();
// echo "<br>Created Fees with player name=" . $fe->getPlayers()->getPlayerName() . " and amount=" . $fe->getFeeAmount();
// $fe->setFeeAmount(5);
// $entityManager->persist($fe);
// $entityManager->flush();
// echo "<br>Updated Fees with player name=" . $fe->getPlayers()->getPlayerName() . " and amount=" . $fe->getFeeAmount();
// $idDeleted = $fe->getTournaments()->getTournamentId();
// $entityManager->remove($fe);
// $entityManager->flush();
// echo "<br>Deleted Fees with tournament ID=" . $idDeleted;
// $seasonFind = $entityManager->find(Constant::ENTITY_SEASONS, 15);
// $playerFind = $entityManager->find(Constant::ENTITY_PLAYERS, 35);
// $tournamentFind = $entityManager->find(Constant::ENTITY_TOURNAMENTS, 414);
// $feeFind = $entityManager->getRepository(Constant::ENTITY_FEES)->findOneBy(array("seasons" => $seasonFind, "players" => $playerFind, "tournaments" => $tournamentFind));
// echo "<br>find fee for season=" . $seasonFind->getSeasonId() . ", player=" . $playerFind->getPlayerId() . ", tournament=" . $tournamentFind->getTournamentId() . " amount=" . $feeFind->getFeeAmount();

// $gt = new GameTypes();
// $gt->setGameTypeName("orm game type");
// $entityManager->persist($gt);
// $entityManager->flush();
// echo "<br>Created GameTypes with ID=" . $gt->getGameTypeId() . " and name=" . $gt->getGameTypeName();
// $gt->setGameTypeName("orm updated gt");
// $entityManager->persist($gt);
// $entityManager->flush();
// echo "<br>Updated GameTypes with ID=" . $gt->getGameTypeId() . " and name=" . $gt->getGameTypeName();
// $idDeleted = $gt->getGameTypeId();
// $entityManager->remove($gt);
// $entityManager->flush();
// echo "<br>Deleted GameTypes with ID=" . $idDeleted;
// $gameTypesFind = $entityManager->find(Constant::ENTITY_GAME_TYPES, 3);
// echo "<br>find gametype for id=" . $gameTypesFind->getGameTypeId() . ", name=" . $gameTypesFind->getGameTypeName();

// $gr = new Groups();
// $gr->setGroupName("orm group name");
// $entityManager->persist($gr);
// $entityManager->flush();
// echo "<br>Created Groups with ID=" . $gr->getGroupId() . " and name=" . $gr->getGroupName();
// $gr->setGroupName("orm updated gn");
// $entityManager->persist($gr);
// $entityManager->flush();
// echo "<br>Updated Groups with ID=" . $gr->getGroupId() . " and name=" . $gr->getGroupName();
// $idDeleted = $gr->getGroupId();
// $entityManager->remove($gr);
// $entityManager->flush();
// echo "<br>Deleted Groups with ID=" . $idDeleted;
// $groupsFind = $entityManager->find(Constant::ENTITY_GROUPS, 3);
// echo "<br>find group for id=" . $groupsFind->getGroupId() . ", name=" . $groupsFind->getGroupName() . ", payouts=" . count($groupsFind->getGroupPayouts()) . ", tournaments=" . count($groupsFind->getTournaments());

// $grp = new GroupPayouts();
// $groupsFind = $entityManager->find(Constant::ENTITY_GROUPS, 3);
// $grp->setGroups($groupsFind);
// $payoutsFind = $entityManager->find(Constant::ENTITY_PAYOUTS, 3);
// $grp->setPayouts($payoutsFind);
// $entityManager->persist($grp);
// $entityManager->flush();
// echo "<br>Created GroupPayouts with groups=" . $grp->getGroups()->getGroupId() . " and payouts=" . $grp->getPayouts()->getPayoutId();
// $payoutsFind = $entityManager->find(Constant::ENTITY_PAYOUTS, 4);
// $grp->setPayouts($payoutsFind);
// $entityManager->persist($grp);
// $entityManager->flush();
// echo "<br>Updated GroupPayouts with groups=" . $grp->getGroups()->getGroupId() . " and payouts=" . $grp->getPayouts()->getPayoutId();
// $idGroupDeleted = $grp->getGroups()->getGroupId();
// $idPayoutDeleted = $grp->getPayouts()->getPayoutId();
// // not sure why this has old value before update so do find to do proper remove
// // $entityManager->remove($grp);
// $groupsFind = $entityManager->find(Constant::ENTITY_GROUPS, $grp->getGroups()->getGroupId());
// $payoutsFind = $entityManager->find(Constant::ENTITY_PAYOUTS, $grp->getPayouts()->getPayoutId());
// $groupPayoutsFind = $entityManager->getRepository(Constant::ENTITY_GROUP_PAYOUTS)->findOneBy(array("groups" => $groupsFind, "payouts" => $payoutsFind));
// $entityManager->remove($groupPayoutsFind);
// $entityManager->flush();
// echo "<br>Deleted GroupPayouts with groups=" . $idGroupDeleted . " and payouts=" . $idPayoutDeleted;
// $groupsFind = $entityManager->find(Constant::ENTITY_GROUPS, 2);
// $payoutsFind = $entityManager->find(Constant::ENTITY_PAYOUTS, 4);
// $groupPayoutsFind = $entityManager->getRepository(Constant::ENTITY_GROUP_PAYOUTS)->findOneBy(array("groups" => $groupsFind, "payouts" => $payoutsFind));
// echo "<br>find GroupPayouts with groups=" . $groupPayoutsFind->getGroups()->getGroupName() . " and payouts=" . $groupPayoutsFind->getPayouts()->getPayoutName();

// $lt = new LimitTypes();
// $lt->setLimitTypeName("orm limit type");
// $entityManager->persist($lt);
// $entityManager->flush();
// echo "<br>Created LimitTypes with ID=" . $lt->getLimitTypeId() . " and name=" . $lt->getLimitTypeName();
// $lt->setLimitTypeName("orm updated lt");
// $entityManager->persist($lt);
// $entityManager->flush();
// echo "<br>Updated LimitTypes with ID=" . $lt->getLimitTypeId() . " and name=" . $lt->getLimitTypeName();
// $idDeleted = $lt->getLimitTypeId();
// $entityManager->remove($lt);
// $entityManager->flush();
// echo "<br>Deleted LimitTypes with ID=" . $idDeleted;
// $limitTypesFind = $entityManager->find(Constant::ENTITY_LIMIT_TYPES, 2);
// echo "<br>find limittype for id=" . $limitTypesFind->getLimitTypeId() . ", name=" . $limitTypesFind->getLimitTypeName() . ", tournaments=" . count($limitTypesFind->getTournaments());

// $pl = new Players();
// $pl->setPlayerActiveFlag(1);
// $pl->setPlayerAdministratorFlag(0);
// $pl->setPlayerApprovalDate(null);
// $pl->setPlayerApproval(null);
// $pl->setPlayerEmail("abc@dummy.com");
// $pl->setPlayerExpires(null);
// $pl->setPlayerFirstName("first");
// $pl->setPlayerIdPrevious(0);
// $pl->setPlayerLastName("last");
// $pl->setPlayerPassword("password");
// $pl->setPlayerPhone(1234567898);
// $pl->setPlayerRegistrationDate(new DateTime());
// $pl->setPlayerRejectionDate(null);
// $pl->setPlayerRejection(null);
// $pl->setPlayerSelector(null);
// $pl->setPlayerToken(null);
// $pl->setPlayerUsername("dummy");
// $lo = new Locations();
// $lo->setLocationAddress("666 doctrine lane");
// $lo->setLocationCity("Symfony");
// $lo->setLocationMap(null);
// $lo->setLocationMapLink("maplink.dummy");
// $lo->setLocationName("ORM location name");
// $lo->setLocationState("HI");
// $lo->setLocationZipCode(12321);
// $lo->setPlayers($pl);
// $entityManager->persist($lo);
// $entityManager->flush();
// echo "<br>Created Locations with ID=" . $pl->getPlayerId() . " and active=" . $pl->getPlayerActiveFlag();
// $pl->setPlayerActiveFlag(0);
// $entityManager->persist($pl);
// $entityManager->flush();
// echo "<br>Updated Locations with ID=" . $pl->getPlayerId() . " and active=" . $pl->getPlayerActiveFlag();
// $idDeleted = $pl->getPlayerId();
// $entityManager->remove($lo);
// $entityManager->flush();
// echo "<br>Deleted Locations with ID=" . $idDeleted;
// $locationsFind = $entityManager->find(Constant::ENTITY_LOCATIONS, 2);
// echo "<br>find location for id=" . $locationsFind->getLocationId() . ", name=" . $locationsFind->getLocationName() . ", tournaments=" . count($locationsFind->getTournaments());

// $nt = new Notifications();
// $nt->setNotificationDescription("orm notif desc");
// $nt->setNotificationStartDate(new DateTime());
// $nt->setNotificationEndDate(new DateTime());
// $entityManager->persist($nt);
// $entityManager->flush();
// echo "<br>Created Notifications with ID=" . $nt->getNotificationId() . " and desc=" . $nt->getNotificationDescription() . " and start date=" . DateTimeUtility::formatDisplayDateTimeMilliseconds(value: $nt->getNotificationStartDate()) . " and end date=" . DateTimeUtility::formatDisplayDateTimeMilliseconds(value: $nt->getNotificationEndDate());
// $nt->setNotificationDescription("orm updated notif desc");
// $nt->setNotificationStartDate(new DateTime());
// $nt->setNotificationEndDate(new DateTime());
// $entityManager->persist($nt);
// $entityManager->flush();
// echo "<br>Updated Notifications with ID=" . $nt->getNotificationId() . " and desc=" . $nt->getNotificationDescription() . " and start date=" . DateTimeUtility::formatDisplayDateTimeMilliseconds(value: $nt->getNotificationStartDate()) . " and end date=" . DateTimeUtility::formatDisplayDateTimeMilliseconds(value: $nt->getNotificationEndDate());
// $idDeleted = $nt->getNotificationId();
// $entityManager->remove($nt);
// $entityManager->flush();
// echo "<br>Deleted Notifications with ID=" . $idDeleted;
// $notificationsFind = $entityManager->find(Constant::ENTITY_NOTIFICATIONS, 2);
// echo "<br>find notification for id=" . $notificationsFind->getNotificationId() . ", desc=" . $notificationsFind->getNotificationDescription();

// $pa = new Payouts();
// $pa->setPayoutMaxPlayers(20);
// $pa->setPayoutMinPlayers(5);
// $pa->setPayoutName("orm test payout");
// $entityManager->persist($pa);
// $entityManager->flush();
// echo "<br>Created Payouts with ID=" . $pa->getPayoutId() . " and name=" . $pa->getPayoutName();
// $pa->setPayoutName("orm updated name");
// $entityManager->persist($pa);
// $entityManager->flush();
// echo "<br>Updated Payouts with ID=" . $pa->getPayoutId() . " and name=" . $pa->getPayoutName();
// $idDeleted = $pa->getPayoutId();
// $entityManager->remove($pa);
// $entityManager->flush();
// echo "<br>Deleted Payouts with ID=" . $idDeleted;
// $payoutsFind = $entityManager->find(Constant::ENTITY_PAYOUTS, 1);
// echo "<br>find payout for id=" . $payoutsFind->getPayoutId() . ", name=" . $payoutsFind->getPayoutName();

// $pl = new Players();
// $pl->setPlayerActiveFlag(1);
// $pl->setPlayerAdministratorFlag(0);
// $pl->setPlayerApprovalDate(null);
// $pl->setPlayerApproval(null);
// $pl->setPlayerEmail("abc@dummy.com");
// $pl->setPlayerExpires(null);
// $pl->setPlayerFirstName("first");
// $pl->setPlayerIdPrevious(0);
// $pl->setPlayerLastName("last");
// $pl->setPlayerPassword("password");
// $pl->setPlayerPhone(1234567898);
// $pl->setPlayerRegistrationDate(new DateTime());
// $pl->setPlayerRejectionDate(null);
// $pl->setPlayerRejection(null);
// $pl->setPlayerSelector(null);
// $pl->setPlayerToken(null);
// $pl->setPlayerUsername("dummy");
// $entityManager->persist($pl);
// $entityManager->flush();
// echo "<br>Created Players with ID=" . $pl->getPlayerId() . " and active=" . $pl->getPlayerActiveFlag();
// $pl->setPlayerActiveFlag(0);
// $entityManager->persist($pl);
// $entityManager->flush();
// echo "<br>Updated Players with ID=" . $pl->getPlayerId() . " and active=" . $pl->getPlayerActiveFlag();
// $idDeleted = $pl->getPlayerId();
// $entityManager->remove($pl);
// $entityManager->flush();
// echo "<br>Deleted Players with ID=" . $idDeleted;

// $re = new Results();
// $player = $entityManager->find(Constant::ENTITY_PLAYERS, 4);
// $re->setPlayers($player);
// $re->setPlayerKos(null);
// $re->setResultAddonFlag("Y");
// $re->setResultPaidAddonFlag("Y");
// $re->setResultPaidBuyinFlag("Y");
// $re->setResultPaidRebuyFlag("N");
// $re->setResultPlaceFinished(0);
// $re->setResultRebuyCount(0);
// $re->setResultRegistrationFood("dip");
// $re->setResultRegistrationOrder(1);
// $statusCode = $entityManager->find(Constant::ENTITY_STATUS_CODES, "R");
// $re->setStatusCodes($statusCode);
// $tournament = $entityManager->find(Constant::ENTITY_TOURNAMENTS, 499);
// $re->setTournaments($tournament);
// $entityManager->persist($re);
// $entityManager->flush();
// echo "<br>Created Results with player ID=" . $re->getPlayers()->getPlayerId() . " and tournament ID=" . $re->getTournaments()->getTournamentId() . " and place=" . $re->getResultPlaceFinished();
// $re->setResultPlaceFinished(3);
// $entityManager->persist($re);
// $entityManager->flush();
// echo "<br>Updated Results with player ID=" . $re->getPlayers()->getPlayerId() . " and tournament ID=" . $re->getTournaments()->getTournamentId() . " and place=" . $re->getResultPlaceFinished();
// $idDeleted = $re->getPlayers()->getPlayerId();
// $entityManager->remove($re);
// $entityManager->flush();
// echo "<br>Deleted Results with player ID=" . $idDeleted;

// $st = new Seasons();
// $st->setSeasonDescription("orm notif desc");
// $st->setSeasonStartDate(new DateTime());
// $st->setSeasonEndDate(new DateTime());
// $st->setSeasonChampionshipQualificationCount(4);
// $st->setSeasonFinalTableBonusPoints(6);
// $st->setSeasonFinalTablePlayers(5);
// $st->setSeasonFee(11);
// $st->setSeasonActiveFlag(1);
// $entityManager->persist($st);
// $entityManager->flush();
// echo "<br>Created Seasons with ID=" . $st->getSeasonId() . " and desc=" . $st->getSeasonDescription() . " and start date=" . DateTimeUtility::formatDisplayDateTimeMilliseconds(value: $st->getSeasonStartDate()) . " and end date=" . DateTimeUtility::formatDisplayDateTimeMilliseconds(value: $st->getSeasonEndDate());
// $st->setSeasonDescription("orm updated notif desc");
// $st->setSeasonStartDate(new DateTime());
// $st->setSeasonEndDate(new DateTime());
// $entityManager->persist($st);
// $entityManager->flush();
// echo "<br>Updated Seasons with ID=" . $st->getSeasonId() . " and desc=" . $st->getSeasonDescription() . " and start date=" . DateTimeUtility::formatDisplayDateTimeMilliseconds(value: $st->getSeasonStartDate()) . " and end date=" . DateTimeUtility::formatDisplayDateTimeMilliseconds(value: $st->getSeasonEndDate());
// $idDeleted = $st->getSeasonId();
// $entityManager->remove($st);
// $entityManager->flush();
// echo "<br>Deleted Seasons with ID=" . $idDeleted;

// $st = new SpecialTypes();
// $st->setSpecialTypeDescription("orm special type");
// $st->setSpecialTypeMultiplier(4);
// $entityManager->persist($st);
// $entityManager->flush();
// echo "<br>Created SpecialTypes with ID=" . $st->getSpecialTypeId() . " and name=" . $st->getSpecialTypeDescription() . " and multiplier=" . $st->getSpecialTypeMultiplier();
// $st->setSpecialTypeDescription("orm updated st");
// $st->setSpecialTypeMultiplier(3);
// $entityManager->persist($st);
// $entityManager->flush();
// echo "<br>Updated SpecialTypes with ID=" . $st->getSpecialTypeId() . " and name=" . $st->getSpecialTypeDescription() . " and multiplier=" . $st->getSpecialTypeMultiplier();
// $idDeleted = $st->getSpecialTypeId();
// $entityManager->remove($st);
// $entityManager->flush();
// echo "<br>Deleted SpecialTypes with ID=" . $idDeleted;

// $pa = new Payouts();
// $pa->setPayoutMaxPlayers(20);
// $pa->setPayoutMinPlayers(5);
// $pa->setPayoutName("orm test payout");
// $str = new Structures();
// $str->setPayouts($pa);
// $str->setStructurePercentage(.66);
// $str->setStructurePlace(1);
// $entityManager->persist($str);
// $entityManager->flush();
// echo "<br>Created Structures with ID=" . $str->getPayouts()->getPayoutId() . " and percentage=" . $str->getStructurePercentage();
// $str->setStructurePercentage(.45);
// $str->setStructurePlace(2);
// $entityManager->persist($str);
// $entityManager->flush();
// echo "<br>Updated Structures with ID=" . $str->getPayouts()->getPayoutId() . " and percentage=" . $str->getStructurePercentage();
// $idDeleted = $str->getPayouts()->getPayoutId();
// $entityManager->remove($str);
// $entityManager->flush();
// echo "<br>Deleted Structures with ID=" . $idDeleted;

// $to = new Tournaments();
// $gameType = $entityManager->find(Constant::ENTITY_GAME_TYPES, "1");
// $to->setGameTypes($gameType);
// $group = $entityManager->find(Constant::ENTITY_GROUPS, "2");
// $to->setGroups($group);
// $limitType = $entityManager->find(Constant::ENTITY_LIMIT_TYPES, "3");
// $to->setLimitTypes($limitType);
// $location = $entityManager->find(Constant::ENTITY_LOCATIONS, "4");
// $to->setLocations($location);
// $to->setTournamentAddonAmount(1);
// $to->setTournamentAddonChipCount(2);
// $to->setTournamentBuyinAmount(3);
// $to->setTournamentChipCount(4);
// $to->setTournamentComment(null);
// $to->setTournamentDate(new DateTime());
// $to->setTournamentDescription("orm test desc");
// $to->setTournamentMaxPlayers(5);
// $to->setTournamentMaxRebuys(6);
// $to->setTournamentRake(.7);
// $to->setTournamentRebuyAmount(8);
// $to->setTournamentStartTime(new DateTime());
// $entityManager->persist($to);
// $entityManager->flush();
// echo "<br>Created Tournaments with ID=" . $to->getTournamentId() . " and desc=" . $to->getTournamentDescription();
// $to->setTournamentDescription("orm updated desc");
// $entityManager->persist($to);
// $entityManager->flush();
// echo "<br>Updated Tournaments with ID=" . $to->getTournamentId() . " and desc=" . $to->getTournamentDescription();
// $idDeleted = $to->getTournamentId();
// $entityManager->remove($to);
// $entityManager->flush();
// echo "<br>Deleted Tournaments with ID=" . $idDeleted;

// $result = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getActives();
// echo "<br>active players count=" . count($result);
// echo "<br>id=" . $result[0]->getPlayerId();
// $result = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getByName(name: "Jim Lee");
// echo "<br>active players by name count=" . count($result);
// echo "<br>id=" . $result[0]->getPlayerId();
// $result = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getByUsername(username: "timwilsoncpa");
// echo "<br>active players by username count=" . count($result);
// echo "<br>id=" . $result[0]->getPlayerId() . "/name=" . $result[0]->getPlayerName();
// $result = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getByEmail(email: "Davespokerroom@yahoo.com");
// echo "<br>active players by email count=" . count($result);
// echo "<br>id=" . $result[0]->getPlayerId() . "/name=" . $result[0]->getPlayerName();

// $dtStart = new DateTime();
// $dtStart->setDate(2023, 1, 1);
// $dtEnd = new DateTime();
// $dtEnd->setDate(2023, 12, 31);
// $result = $entityManager->getRepository(Constant::ENTITY_TOURNAMENTS)->getRegisterHost(startDate: $dtStart, endDate: $dtEnd);
// echo "<br>register host count=" . count($result);
// $result = $entityManager->getRepository(Constant::ENTITY_RESULTS)->getBullies(knockedOutBy: 35, limitCount: 5, indexed: false);
// // echo "<br>player bullies count=" . count($result);
// foreach ($result as $row) {
//     echo "<br>bully player name=" . $row["name"] . ", count=" . $row["kos"];
// }
// $result = $entityManager->getRepository(Constant::ENTITY_TOURNAMENTS)->getCountForDates(startDate: $dtStart, endDate: $dtEnd);
// echo "<br>count for dates count=" . count($result);
// $result = $entityManager->getRepository(Constant::ENTITY_TOURNAMENTS)->getCountForUserAndDates(playerId: 35, startDate: $dtStart, endDate: $dtEnd);
// echo "<br>count for dates and user count=" . count($result);
// $result = $entityManager->getRepository(Constant::ENTITY_FEES)->getForTournament(tournamentId: 469, greaterThanZero: true);
// echo "<br>fees tournament count=" . count($result);
// $result = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getFeeDetail(indexed: false);
// echo "<br>fees count=" . count($result);
// $counter = 0;
// foreach ($result as $row) {
//     echo "<br>name/desc/amt/balance=" . $row["name"] . "/" . $row["description"] . "/" . $row["fee"] . "/" . $row["balance"];
//     $counter++;
//     if ($counter == 5) { break; }
// }
// $result = $entityManager->getRepository(Constant::ENTITY_FEES)->getBySeason(indexed: false);
// echo "<br>fees by season count=" . count($result);
// foreach ($result as $row) {
//     echo "<br>season id=" . $row["season_id"] . "/total amount=" . $row["amount"];
// }
// $result = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getFeeForPlayerAndTournament(playerId: 35, tournamentId: 442);
// echo "<br>fees for player/tournament count=" . count($result);
// foreach ($result as $row) {
//     echo "<br>season=" . $row["season_id"] . "/amount=" . $row["fee_amount"] . "/status=" . $row["status"];
// }
// $result = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getFinishesForDates(playerId: 35, startDate: NULL, endDate: NULL, indexed: false);
// echo "<br>finishes for player count=" . count($result);
// foreach ($result as $row) {
//     echo "<br>place=" . $row["place"] . "/finishes=" . $row["finishes"] . "/percent=" . $row["pct"] * 100 . "%";
// }
// $result = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getResults(tournamentId: 442, playerId: 35);
// echo "<br>results for player count=" . count($result);
// foreach ($result as $player) {
//     foreach ($player->getResults() as $results) {
//         echo "<br>tournament=" . $results->getTournaments()->getTournamentId() . "/place=" . $results->getResultPlaceFinished() . "/ko by=" . $results->getPlayerKos()?->getPlayerName() . "/status=" . $results->getStatusCodes()->getStatusCodeName();
//     }
// }
// $result = $entityManager->getRepository(Constant::ENTITY_GAME_TYPES)->getById(gameTypeId: NULL);
// echo "<br>game types count=" . count($result);
// foreach ($result as $gameType) {
//     echo "<br>game type name=" . $gameType->getGameTypeName() . "/tournament count=" . count($gameType->getTournaments());
// }
// $result = $entityManager->getRepository(Constant::ENTITY_GROUPS)->getById(groupId: NULL);
// echo "<br>groups count=" . count($result);
// foreach ($result as $group) {
//     echo "<br>group name=" . $group->getGroupName() . "/group payout count=" . count($group->getGroupPayouts()) . "/tournament count=" . count($group->getTournaments());
// }
// $result = $entityManager->getRepository(Constant::ENTITY_GROUP_PAYOUTS)->getById(groupId: 1, payoutId: 1);
// echo "<br>group payouts count=" . count($result);
// foreach ($result as $groupPayout) {
//     echo "<br>group name=" . $groupPayout->getGroups()->getGroupName() . "/tournament count=" . count($groupPayout->getGroups()->getTournaments());
//     echo "<br>payout name=" . $groupPayout->getPayouts()->getPayoutName() . "/min players count=" . $groupPayout->getPayouts()->getPayoutMinPlayers() . "/max players count=" . $groupPayout->getPayouts()->getPayoutMaxPlayers();
// }
// $result = $entityManager->getRepository(Constant::ENTITY_LIMIT_TYPES)->getById(limitTypeId: NULL);
// echo "<br>limit types count=" . count($result);
// foreach ($result as $limitType) {
//     echo "<br>limit type name=" . $limitType->getLimitTypeName() . "/tournament count=" . count($limitType->getTournaments());
// }
// $result = $entityManager->getRepository(Constant::ENTITY_LOCATIONS)->getById(locationId: NULL);
// echo "<br>locations count=" . count($result);
// foreach ($result as $location) {
//     echo "<br>location name=" . $location->getLocationName() . "/player name=" . $location->getPlayers()->getPlayerName() . "/tournament count=" . count($location->getTournaments());
// }
// $result = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getByUsername(username: "tmmargar");
// echo "<br>id=" . $result[0]->getPlayerId() . "/name=" . $result[0]->getPlayerName() . "/approval=" . ($result[0]->getPlayerApproval() === NULL ? "N/A" : $result[0]->getPlayerApproval()->getPlayerName());
// $result = $entityManager->getRepository(Constant::ENTITY_RESULTS)->getNemesises(playerId: 35, limitCount: 5, indexed: false);
// // echo "<br>player nemesises count=" . count($result);
// foreach ($result as $row) {
//     echo "<br>nemesis player name=" . $row["name"] . ", count=" . $row["kos"];
// }
// $result = $entityManager->getRepository(Constant::ENTITY_NOTIFICATIONS)->getByDate(date: new DateTime());
// echo "<br>notifications count=" . count($result);
// foreach ($result as $notification) {
//     echo "<br>notification desc=" . $notification->getNotificationDescription() . "/start date=" . DateTimeUtility::formatDisplayDateTime($notification->getNotificationStartDate()) . "/end date=" . DateTimeUtility::formatDisplayDateTime(value: $notification->getNotificationEndDate());
// }
// $result = $entityManager->getRepository(Constant::ENTITY_NOTIFICATIONS)->getById(notificationId: NULL);
// echo "<br>notifications count=" . count($result);
// foreach ($result as $notification) {
//     echo "<br>notification desc=" . $notification->getNotificationDescription() . "/start date=" . DateTimeUtility::formatDisplayDateTime($notification->getNotificationStartDate()) . "/end date=" . DateTimeUtility::formatDisplayDateTime(value: $notification->getNotificationEndDate());
// }
// $result = $entityManager->getRepository(Constant::ENTITY_PAYOUTS)->getById(payoutId: NULL);
// echo "<br>payouts count=" . count($result);
// foreach ($result as $payout) {
//     echo "<br>payout name=" . $payout->getPayoutName() . "/group payout count=" . count($payout->getGroupPayouts()) . "/structures count=" . count($payout->getStructures());
// }
// $result = $entityManager->getRepository(Constant::ENTITY_TOURNAMENTS)->getRegistrationWaitList(tournamentId: 475, registerOrder: 5);
// // echo "<br>registration wait list count=" . count($result);
// foreach ($result as $tournament) {
//     foreach ($tournament->getResults() as $result) {
//         echo "<br>player name=" . $result->getPlayers()->getPlayerName() . "/register order=" . $result->getResultRegistrationOrder();
//     }
// }
// $result = $entityManager->getRepository(Constant::ENTITY_TOURNAMENTS)->getResultsMaxId(new DateTime("2023-10-07"));
// echo "<br>tournament max id=" . $result[0]["tournamentId"];
// $result = $entityManager->getRepository(Constant::ENTITY_SEASONS)->getActives();
// echo "<br>active season desc=" . $result->getSeasonDescription();
// $result = $entityManager->getRepository(Constant::ENTITY_SEASONS)->getById(seasonId: NULL);
// echo "<br>seasons count=" . count($result);
// foreach ($result as $season) {
//     echo "<br>season desc=" . $season->getSeasonDescription() . "/start date=" . DateTimeUtility::formatDisplayDateTime(value: $season->getSeasonStartDate()) . "/end date=" . DateTimeUtility::formatDisplayDateTime(value: $season->getSeasonEndDate());
// }
// $result = $entityManager->getRepository(Constant::ENTITY_SEASONS)->getByTournamentIdAndSpecialTypeDescription(tournamentId: 493, specialTypeDescription: Constant::DESCRIPTION_CHAMPIONSHIP);
// echo "<br>seasons count=" . count($result);
// // $counter = 0;
// foreach ($result as $row) {
// // "s.season_id, s.season_description, s.season_start_date, s.season_end_date, s.season_championship_qualification_count, s.season_final_table_players, s.season_final_table_bonus_points, s.season_fee, s.season_active_flag " .
//     echo "<br>st/ed/champ/ftp/ftbp/fee/active=" . $row["season_start_date"] . "/" . $row["season_end_date"] . "/" . $row["season_championship_qualification_count"] . "/" . $row["season_final_table_players"] . "/" . $row["season_final_table_bonus_points"] . "/" . $row["season_fee"] . "/" . $row["season_active_flag"];
// //     $counter++;
// //     if ($counter == 5) { break; }
// }
// $result = $entityManager->getRepository(Constant::ENTITY_SEASONS)->getMaxId();
// echo "<br>season max id=" . $result[1];
// $result = $entityManager->getRepository(Constant::ENTITY_SPECIAL_TYPES)->getById(specialTypeId: NULL);
// echo "<br>special types count=" . count($result);
// foreach ($result as $specialType) {
//     echo "<br>special type desc=" . $specialType->getSpecialTypeDescription() . "/tournament count=" . count($specialType->getTournaments());
// }
// $result = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getStatuses(tournamentId: 492, indexed: false);
// echo "<br>player statuses count=" . count($result);
// $counter = 0;
// foreach ($result as $row) {
// // p.player_id, name, fee_status_logic, fee_status, fee_amount, amount2, buyin_status, rebuy_status, result_rebuy_count, addon_status
//     echo "<br>name/logic/fee status/fee/amt2/buyin/rebuy/rbc/addon=" . $row["name"] . "/" . $row["fee_status_logic"] . "/" . $row["fee status"] . "/" . $row["fee_amount"] . "/" . $row["amount2"] . "/" . $row["buyin_status"] . "/" . $row["rebuy_status"] . "/" . $row["result_rebuy_count"] . "/" . $row["addon_status"];
//     $counter++;
//     if ($counter == 5) { break; }
// }
// $result = $entityManager->getRepository(Constant::ENTITY_STATUS_CODES)->getById(statusCode: NULL);
// echo "<br>status codes count=" . count($result);
// foreach ($result as $statusCode) {
//     echo "<br>status codes name=" . $statusCode->getStatusCodeName();
// }
// $result = $entityManager->getRepository(Constant::ENTITY_STRUCTURES)->getById(payoutId: NULL);
// echo "<br>structures count=" . count($result);
// foreach ($result as $structure) {
//     echo "<br>structures place=" . $structure->getStructurePlace() . "/percentage=" . $structure->getStructurePercentage() * 100 . "%/payout name=" . $structure->GetPayouts()->getPayoutName();
// }
// $result = $entityManager->getRepository(Constant::ENTITY_TOURNAMENTS)->getResultsMaxId(new DateTime());
// echo "<br>tournament max id=" . $result[0]["tournamentId"];
// $result = $entityManager->getRepository(Constant::ENTITY_TOURNAMENTS)->getForYear(tournamentDate: new DateTime());
// echo "<br>tournaments for year count=" . count($result);
// foreach ($result as $tournament) {
//     echo "<br>tournaments for year desc=" . $tournament->getTournamentDescription() . "/date=" . DateTimeUtility::formatDisplayDateTime(value: $tournament->getTournamentDate()) . "/st=" . DateTimeUtility::formatDisplayDateTime(value: $tournament->getTournamentStartTime());
// }

// getAllMultiple(bool $championship, ?DateTime $tournamentDate, ?DateTime $startTime, ?int $tournamentId, bool $notEntered, bool $ordered, ?string $mode, ?int $interval, int $limitCount) {
// $result = $entityManager->getRepository(Constant::ENTITY_TOURNAMENTS)->getAllMultiple(championship: true, tournamentDate: NULL, startTime: NULL, tournamentId: NULL, notEntered: true, ordered: false, mode: NULL, interval: NULL, limitCount: NULL);
// echo "<br>tournaments championship count=" . count($result);
// $result = $entityManager->getRepository(Constant::ENTITY_TOURNAMENTS)->getAllMultiple(championship: false, tournamentDate: new DateTime(), startTime: new DateTime(), tournamentId: NULL, notEntered: false, ordered: false, mode: NULL, interval: NULL, limitCount: NULL);
// echo "<br>tournaments for date and time count=" . count($result);
// $result = $entityManager->getRepository(Constant::ENTITY_TOURNAMENTS)->getAllMultiple(championship: false, tournamentDate: NULL, startTime: NULL, tournamentId: 493, notEntered: false, ordered: false, mode: NULL, interval: NULL, limitCount: NULL);
// echo "<br>tournaments for id count=" . count($result);
// $result = $entityManager->getRepository(Constant::ENTITY_TOURNAMENTS)->getAllMultiple(championship: false, tournamentDate: NULL, startTime: NULL, tournamentId: NULL, notEntered: true, ordered: false, mode: NULL, interval: NULL, limitCount: NULL);
// echo "<br>tournaments for registration/buyins count=" . count($result);
// $result = $entityManager->getRepository(Constant::ENTITY_TOURNAMENTS)->getAllMultiple(championship: false, tournamentDate: NULL, startTime: NULL, tournamentId: NULL, notEntered: false, ordered: true, mode: NULL, interval: NULL, limitCount: NULL);
// echo "<br>tournaments ordered count=" . count($result);
// $result = $entityManager->getRepository(Constant::ENTITY_TOURNAMENTS)->getAllMultiple(championship: false, tournamentDate: NULL, startTime: NULL, tournamentId: NULL, notEntered: false, ordered: false, mode: Constant::MODE_CREATE, interval: NULL, limitCount: NULL);
// echo "<br>tournaments create count=" . count($result);
// $result = $entityManager->getRepository(Constant::ENTITY_TOURNAMENTS)->getAllMultiple(championship: false, tournamentDate: NULL, startTime: NULL, tournamentId: NULL, notEntered: false, ordered: false, mode: Constant::MODE_MODIFY, interval: NULL, limitCount: NULL);
// echo "<br>tournaments modify count=" . count($result);
// $result = $entityManager->getRepository(Constant::ENTITY_TOURNAMENTS)->getAllMultiple(championship: false, tournamentDate: NULL, startTime: NULL, tournamentId: NULL, notEntered: false, ordered: false, mode: NULL, interval: 14, limitCount: NULL);
// echo "<br>tournaments modify count=" . count($result);
// $result = $entityManager->getRepository(Constant::ENTITY_TOURNAMENTS)->getRegistrationStatus(tournamentId: 494, indexed: false);
// echo "<br>tournaments registration status count=" . count($result);
// foreach ($result as $row) {
//     echo "<br>name=" . $row["name"] . "/status=" . $row["status"];
// }
// $result = $entityManager->getRepository(Constant::ENTITY_TOURNAMENTS)->getYears();
// echo "<br>tournaments registration status count=" . count($result);
// foreach ($result as $row) {
//     echo "<br>year=" . $row["year"];
// }
// $result = $entityManager->getRepository(Constant::ENTITY_TOURNAMENTS)->getForIdAndDates(playerId: 35, startDate: new DateTime("6/1/2023"), endDate: new DateTime("11/30/2023"));
// // echo "<br>tournaments for id and dates count=" . count($result);
// echo "<br>numPlayed=" . $result[0]["numPlayed"];
// $result = $entityManager->getRepository(Constant::ENTITY_TOURNAMENTS)->getWon(playerId: 35);
// echo "<br>tournaments won count=" . count($result);
// foreach ($result as $row) {
//     echo "<br>desc=" . $row["description"] . "/entered=" . $row["enteredCount"];
// }
// $result = $entityManager->getRepository(Constant::ENTITY_TOURNAMENTS)->getPlayed(indexed: false);
// echo "<br>tournaments played count=" . count($result);
// foreach ($result as $row) {
//     echo "<br>name=" . $row["name"] . "/played=" . $row["tourneys"];
// }
// $result = $entityManager->getRepository(Constant::ENTITY_TOURNAMENTS)->getPlayedByType(playerId: 35, indexed: false);
// echo "<br>tournaments played by type count=" . count($result);
// foreach ($result as $row) {
//     echo "<br>" . $row["numPlayed"] . " " . $row[0]->getLimitTypes()->getLimitTypeName() . " " . $row[0]->getGameTypes()->getGameTypeName() . ($row[0]->getTournamentMaxRebuys() > 0 ? "+r" : "") . ($row[0]->getTournamentAddonAmount() > 0 ? "+a" : "");
// }
// $result = $entityManager->getRepository(Constant::ENTITY_TOURNAMENTS)->getResultsMinDate(playerId: 35);
// // echo "<br>tournaments for id and dates count=" . count($result);
// $dt = new DateTime($result[0]["tournamentDate"]);
// echo "<br>min date=" . DateTimeUtility::formatDisplayDateTime(value: $dt);
// $result = $entityManager->getRepository(Constant::ENTITY_TOURNAMENTS)->getOrderedDateTime();
// echo "<br>tournaments ordered count=" . count($result);
// $counter = 0;
// foreach ($result as $tournament) {
//     echo "<br>" . $tournament->getTournamentDescription();
//     $counter++;
//     if ($counter == 5) {break;}
// }
// $result = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getTournamentAbsences(new DateTime("2020-01-01"));
// echo "<br>absences count=" . count($result);
// foreach ($result as $player) {
//     echo "<br>name=" . $player->getPlayerName();
// }
// $result = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getActivesForNotification();
// echo "<br>notifications count=" . count($result);
// // echo "<br>id=" . $result[0]->getPlayerId();
// $result = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getForApproval(indexed: false);
// echo "<br>approvals count=" . count($result);
// // echo "<br>id=" . $result[0]->getPlayerId();
// $result = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getWaitListed(tournamentId: 490);
// echo "<br>tournaments wait listed count=" . count($result);
// winnersForSeason
// $result = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getWins(startDate: new DateTime("2023-01-01"), endDate: new DateTime("2023-12-31"), playerId: NULL, winsForPlayer: false, winsForSeason: true, rank: false, orderBy: array(), indexed: false);
// echo "<br>players winners season count=" . count($result);
// // winsForPlayer
// $result = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getWins(startDate: new DateTime("2023-01-01"), endDate: new DateTime("2023-12-31"), playerId: 35, winsForPlayer: true, winsForSeason: false, rank: true, orderBy: array(1), indexed: false);
// echo "<br>players wins count=" . count($result);
// $counter = 0;
// foreach ($result as $row) {
//     echo "<br>name=" . $row["name"] . "/wins=" . $row["wins"];
//     $counter++;
//     if ($counter == 5) {break;}
// }
// // winsTotalAndAverageForSeasonForPlayer
// $result = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getWins(startDate: new DateTime("2023-01-01"), endDate: new DateTime("2023-12-31"), playerId: 35, winsForPlayer: false, winsForSeason: false, rank: true, orderBy: array(2), indexed: false);
// echo "<br>players wins total/avg season/player count=" . count($result);
// $counter = 0;
// foreach ($result as $row) {
//     echo "<br>name=" . $row["name"] . "/avg=" . $row["avg"];
//     $counter++;
//     if ($counter == 5) {break;}
// }
//knockoutsAverageForSeason
// $result = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getKo(playerId: NULL, startDate: new DateTime("2023-01-01"), endDate: new DateTime("2023-12-31"), orderBy: array(2), rank: false, limitCount: NULL, indexed: false);
// echo "<br>ko avg season count=" . count($result);
// $counter = 0;
// foreach ($result as $row) {
//     echo "<br>name=" . $row["name"] . "/avg=" . $row["avg"];
//     $counter++;
//     if ($counter == 5) {break;}
// }
// //knockoutsTotalForSeason
// $result = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getKo(playerId: NULL, startDate: new DateTime("2023-01-01"), endDate: new DateTime("2023-12-31"), orderBy: array(1), rank: false, limitCount: NULL, indexed: false);
// echo "<br>ko total season count=" . count($result);
// $counter = 0;
// foreach ($result as $row) {
//     echo "<br>name=" . $row["name"] . "/ko=" . $row["ko"];
//     $counter++;
//     if ($counter == 5) {break;}
// }
// //knockoutsTotalAndAverageForSeasonForPlayer
// $result = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getKo(playerId: 35, startDate: new DateTime("2023-01-01"), endDate: new DateTime("2023-12-31"), orderBy: array(1), rank: true, limitCount: NULL, indexed: false);
// echo "<br>ko total/avg season/user count=" . count($result);
// $counter = 0;
// foreach ($result as $row) {
//     echo "<br>name=" . $row["name"] . "/ko=" . $row["ko"];
//     $counter++;
//     if ($counter == 5) {break;}
// }
// //knockoutsTotalAndAverageForSeasonForPlayer
// $result = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getKo(playerId: 35, startDate: new DateTime("2023-01-01"), endDate: new DateTime("2023-12-31"), orderBy: array(2), rank: true, limitCount: NULL, indexed: false);
// echo "<br>ko total/avg season/user count=" . count($result);
// $counter = 0;
// foreach ($result as $row) {
//     echo "<br>name=" . $row["name"] . "/avg=" . $row["avg"];
//     $counter++;
//     if ($counter == 5) {break;}
// }
// //knockoutsTotalAndAverageForPlayer
// $result = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getKo(playerId: 35, startDate: NULL, endDate: NULL, orderBy: array(1), rank: true, limitCount: NULL, indexed: false);
// echo "<br>ko total/avg user count=" . count($result);
// $counter = 0;
// foreach ($result as $row) {
//     echo "<br>name=" . $row["name"] . "/ko=" . $row["ko"];
//     $counter++;
//     if ($counter == 5) {break;}
// }
// //knockoutsTotalAndAverageForPlayer
// $result = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getKo(playerId: 35, startDate: NULL, endDate: NULL, orderBy: array(2), rank: true, limitCount: NULL, indexed: false);
// echo "<br>ko total/avg user count=" . count($result);
// $counter = 0;
// foreach ($result as $row) {
//     echo "<br>name=" . $row["name"] . "/avg=" . $row["avg"];
//     $counter++;
//     if ($counter == 5) {break;}
// }
// // pointsAverageForSeason
// $result = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getPoints(playerId: NULL, startDate: new DateTime("2023-01-01"), endDate: new DateTime("2023-12-31"), orderBy: array(2), rank: false, limitCount: NULL, indexed: false);
// echo "<br>pts avg season count=" . count($result);
// $counter = 0;
// foreach ($result as $row) {
//     echo "<br>name=" . $row["name"] . "/avg=" . $row["avg"];
//     $counter++;
//     if ($counter == 5) {break;}
// }
// //pointsTotalForSeason
// $result = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getPoints(playerId: NULL, startDate: new DateTime("2023-01-01"), endDate: new DateTime("2023-12-31"), orderBy: array(1), rank: false, limitCount: NULL, indexed: false);
// echo "<br>pts total season count=" . count($result);
// $counter = 0;
// foreach ($result as $row) {
//     echo "<br>name=" . $row["name"] . "/pts=" . $row["pts"];
//     $counter++;
//     if ($counter == 5) {break;}
// }
// //pointsTotalAndAverageForSeasonForPlayer
// $result = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getPoints(playerId: 35, startDate: new DateTime("2023-01-01"), endDate: new DateTime("2023-12-31"), orderBy: array(1), rank: true, limitCount: NULL, indexed: false);
// echo "<br>pts total season/user count=" . count($result);
// $counter = 0;
// foreach ($result as $row) {
//     echo "<br>name=" . $row["name"] . "/pts=" . $row["pts"];
//     $counter++;
//     if ($counter == 5) {break;}
// }
// //pointsTotalAndAverageForSeasonForPlayer
// $result = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getPoints(playerId: 35, startDate: new DateTime("2023-01-01"), endDate: new DateTime("2023-12-31"), orderBy: array(2), rank: true, limitCount: NULL, indexed: false);
// echo "<br>pts avg season/user count=" . count($result);
// $counter = 0;
// foreach ($result as $row) {
//     echo "<br>name=" . $row["name"] . "/avg=" . $row["avg"];
//     $counter++;
//     if ($counter == 5) {break;}
// }
// $result = $entityManager->getRepository(Constant::ENTITY_TOURNAMENTS)->getPrizePool(startDate: new DateTime("2023-01-01"), endDate: new DateTime("2023-12-31"));
// echo "<br>prize pool count=" . count($result);
// echo "<br>total=" . $result[0]["total"];
// $result = $entityManager->getRepository(Constant::ENTITY_TOURNAMENTS)->getRegistrationList(tournamentDate: new DateTime("2023-10-07"), max: true);
// echo "<br>registration list max count=" . count($result);
// foreach ($result as $row) {
//     echo "<br>name=" . $row["player_first_name"] . " " . $row["player_last_name"] . "/food=" . $row["result_registration_food"];
// }
// $result = $entityManager->getRepository(Constant::ENTITY_TOURNAMENTS)->getRegistrationList(tournamentDate: new DateTime("2023-10-07"), max: false);
// echo "<br>registration list max count=" . count($result);
// foreach ($result as $row) {
//     echo "<br>name=" . $row["player_first_name"] . " " . $row["player_last_name"] . "/food=" . $row["result_registration_food"];
// }
// // resultSelectOneByTournamentIdAndPlayerId
// $result = $entityManager->getRepository(Constant::ENTITY_RESULTS)->getPaidOrRegisteredOrFinished(tournamentId: 490, playerId: 35, paid: false, registered: false, finished: false, indexed: false);
// echo "<br>results paid count=" . count($result);
// foreach ($result as $row) {
//     echo "<br>name=" . $row["name"] . " " . $row["status"] . " " . $row["place"];
// }
// //resultSelectRegisteredByTournamentId
// $result = $entityManager->getRepository(Constant::ENTITY_RESULTS)->getPaidOrRegisteredOrFinished(tournamentId: 494, playerId: NULL, paid: false, registered: true, finished: false, indexed: false);
// echo "<br>results registered count=" . count($result);
// foreach ($result as $row) {
//     echo "<br>name=" . $row["name"] . " " . $row["status"];
// }
// //resultSelectAllFinishedByTournamentId
// $result = $entityManager->getRepository(Constant::ENTITY_RESULTS)->getPaidOrRegisteredOrFinished(tournamentId: 493, playerId: NULL, paid: false, registered: false, finished: true, indexed: false);
// echo "<br>results finished count=" . count($result);
// foreach ($result as $row) {
//     echo "<br>name=" . $row["name"] . " " . $row["status"] . " " . $row["place"];
// }
// //resultSelectPaidByTournamentId
// $result = $entityManager->getRepository(Constant::ENTITY_RESULTS)->getPaidOrRegisteredOrFinished(tournamentId: 493, playerId: NULL, paid: true, registered: false, finished: false, indexed: false);
// echo "<br>results paid count=" . count($result);
// foreach ($result as $row) {
//     echo "<br>name=" . $row["name"] . " " . $row["status"];
// }
// $result = $entityManager->getRepository(Constant::ENTITY_RESULTS)->getForTournament(prizePool: 4000, tournamentId: 493, indexed: false);
// echo "<br>results for tournament count=" . count($result);
// foreach ($result as $row) {
//     echo "<br>name=" . $row["name"] . "/place=" . $row["place"] . "/earnings=" . $row["earnings"];
// }
// $result = $entityManager->getRepository(Constant::ENTITY_RESULTS)->getForTournament(prizePool: NULL, tournamentId: 492, indexed: false);
// echo "<br>results for tournament count=" . count($result);
// foreach ($result as $row) {
//     echo "<br>name=" . $row["name"] . "/place=" . $row["place"] . "/earnings=" . $row["earnings"];
// }
// $result = $entityManager->getRepository(Constant::ENTITY_RESULTS)->getOrderedByPoints(startDate: new DateTime("2023-01-01"), endDate: new DateTime("2023-12-31"), indexed: false);
// echo "<br>results ordered by points count=" . count($result);
// $counter = 0;
// foreach ($result as $row) {
//     echo "<br>name=" . $row["name"] . "/pts=" . $row["pts"];
//     $counter++;
//     if ($counter == 5) {break;}
// }
// $result = $entityManager->getRepository(Constant::ENTITY_RESULTS)->getOrderedByEarnings(startDate: new DateTime("2023-01-01"), endDate: new DateTime("2023-12-31"), indexed: false);
// echo "<br>results ordered by earnings count=" . count($result);
// $counter = 0;
// foreach ($result as $row) {
//     echo "<br>name=" . $row["name"] . "/earnings=" . $row["earnings"];
//     $counter++;
//     if ($counter == 5) {break;}
// }$result = $entityManager->getRepository(Constant::ENTITY_RESULTS)->getOrderedByKos(startDate: new DateTime("2023-01-01"), endDate: new DateTime("2023-12-31"), indexed: false);
// echo "<br>results ordered by kos count=" . count($result);
// $counter = 0;
// foreach ($result as $row) {
//     echo "<br>name=" . $row["name"] . "/kos=" . $row["kos"];
//     $counter++;
//     if ($counter == 5) {break;}
// }
// $result = $entityManager->getRepository(Constant::ENTITY_RESULTS)->getOrderedSummary(currentDate: new DateTime(), startDate: new DateTime("2023-01-01"), endDate: new DateTime("2023-12-31"), championship: true, stats: true, indexed: false);
// echo "<br>results ordered summary stats count=" . count($result);
// $counter = 0;
// foreach ($result as $row) {
//     echo "<br>name=" . $row["name"] . "/earnings=" . $row["earnings"] . "/net=" . $row["net(+/-)"];
//     $counter++;
//     if ($counter == 5) {break;}
// }
// $result = $entityManager->getRepository(Constant::ENTITY_RESULTS)->getOrderedSummary(currentDate: new DateTime(), startDate: new DateTime("2023-01-01"), endDate: new DateTime("2023-12-31"), championship: false, stats: true, indexed: false);
// echo "<br>results ordered summary stats count=" . count($result);
// $counter = 0;
// foreach ($result as $row) {
//     echo "<br>name=" . $row["name"] . "/earnings=" . $row["earnings"] . "/net=" . $row["net(+/-)"];
//     $counter++;
//     if ($counter == 5) {break;}
// }
// $result = $entityManager->getRepository(Constant::ENTITY_RESULTS)->getOrderedSummary(currentDate: new DateTime(), startDate: new DateTime("2023-01-01"), endDate: new DateTime("2023-12-31"), championship: false, stats: false, indexed: false);
// echo "<br>results ordered summary count=" . count($result);
// $counter = 0;
// foreach ($result as $row) {
//     echo "<br>name=" . $row["name"] . "/earnings=" . $row["earnings"] . "/net=" . $row["net(+/-)"];
//     $counter++;
//     if ($counter == 5) {break;}
// }
// $result = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getChampionshipQualified(startDate: new DateTime("2023-01-01"), endDate: new DateTime("2023-12-31"), numTourneys: 8, indexed: false);
// echo "<br>championship qualified players count =" . count($result);
// // "earningsAverageForSeason":
// $result = $entityManager->getRepository(Constant::ENTITY_RESULTS)->getEarnings(playerId: NULL, startDate: new DateTime("2023-01-01"), endDate: new DateTime("2023-12-31"), year: NULL, championship: false, season: true, totalAndAverage: false, rank: false, orderBy: array(2), limitCount: NULL, indexed: false);
// echo "<br>results earnings avg season count=" . count($result);
// $counter = 0;
// foreach ($result as $row) {
//     echo "<br>name=" . $row["name"] . "/earnings=" . $row["earns"] . "/avg=" . $row["avg"];
//     $counter++;
//     if ($counter == 5) {break;}
// }
// // "earningsTotalForChampionship":
// $result = $entityManager->getRepository(Constant::ENTITY_RESULTS)->getEarnings(playerId: NULL, startDate: NULL, endDate: NULL, year: 2023, championship: true, season: false, totalAndAverage: false, rank: false, orderBy: NULL, limitCount: NULL, indexed: false);
// echo "<br>results earnings total championship count=" . count($result);
// $counter = 0;
// foreach ($result as $row) {
//     echo "<br>name=" . $row["name"] . "/earnings=" . $row["earns"];
//     $counter++;
//     if ($counter == 5) {break;}
// }
// // "earningsTotalForSeason":
// $result = $entityManager->getRepository(Constant::ENTITY_RESULTS)->getEarnings(playerId: NULL, startDate: new DateTime("2023-01-01"), endDate: new DateTime("2023-12-31"), year: NULL, championship: false, season: true, totalAndAverage: false, rank: false, orderBy: array(1), limitCount: NULL, indexed: false);
// echo "<br>results earnings total season count=" . count($result);
// $counter = 0;
// foreach ($result as $row) {
//     echo "<br>name=" . $row["name"] . "/earnings=" . $row["earns"];
//     $counter++;
//     if ($counter == 5) {break;}
// }
// // "earningsTotalAndAverageForSeasonForPlayer":
// $result = $entityManager->getRepository(Constant::ENTITY_RESULTS)->getEarnings(playerId: 35, startDate: new DateTime("2023-01-01"), endDate: new DateTime("2023-12-31"), year: NULL, championship: false, season: true, totalAndAverage: true, rank: true, orderBy: array(1), limitCount: NULL, indexed: false);
// echo "<br>results earnings total/avg player season count=" . count($result);
// $counter = 0;
// foreach ($result as $row) {
//     echo "<br>name=" . $row["name"] . "/earnings=" . $row["earns"];
//     $counter++;
//     if ($counter == 5) {break;}
// }
// // "earningsTotalAndAverageForPlayer":
// $result = $entityManager->getRepository(Constant::ENTITY_RESULTS)->getEarnings(playerId: 35, startDate: new DateTime("2023-01-01"), endDate: new DateTime("2023-12-31"), year: NULL, championship: false, season: false, totalAndAverage: true, rank: true, orderBy: array(1), limitCount: NULL, indexed: false);
// echo "<br>results earnings total/avg player count=" . count($result);
// $counter = 0;
// foreach ($result as $row) {
//     echo "<br>name=" . $row["name"] . "/earnings=" . $row["earns"];
//     $counter++;
//     if ($counter == 5) {break;}
// }
// $result = $entityManager->getRepository(Constant::ENTITY_PLAYERS)->getChampionshipEarnings(groupBy: "yr, player_id", group: false, indexed: false);
// echo "<br>players championship earnings count =" . count($result);
// $counter = 0;
// foreach ($result as $row) {
//     echo "<br>year=" . $row["yr"] . "/name=" . $row["name"] . "/earnings=" . $row["earnings"];
//     $counter++;
//     if ($counter == 5) {break;}
// }
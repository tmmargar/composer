<?php
declare(strict_types = 1);
namespace Poker\Ccp\classes\model;

use Poker\Ccp\Entity\Tournaments;

class Tournament extends Base {
    private Tournaments $tournaments;
    public function createFromEntity(bool $debug, Tournaments $tournaments): Tournament {
        $this->tournaments = $tournaments;
        $limitType = new LimitType(debug: false, id: 0, name: "");
        $limitType->createFromEntity(debug: $debug, limitTypes: $tournaments->getLimitTypes());
        $gameType = new GameType(debug: false, id: 0, name: "");
        $gameType->createFromEntity(debug: $debug, gameTypes: $tournaments->getGameTypes());
        $specialType = new SpecialType(debug: false, id: 0, description: NULL, multiplier: 0);
        if (null !== $tournaments->getSpecialTypes()) {
            $specialType->createFromEntity(debug: $debug, specialTypes: $tournaments->getSpecialTypes());
        }
        $location = new Location(debug: false, id: NULL, name: "", address: "", city: "", state: "", zipCode: 00000, player: NULL, count: 0, active: "0", map: NULL, mapName: NULL, tournamentCount: 0);
        $location->createFromEntity(debug: $debug, locations: $tournaments->getLocations());
        $groupPayout = new GroupPayout(debug: false, id: NULL, group: NULL, payouts: array());
        $groupPayout->createFromEntity(debug: $debug, groupPayouts: $tournaments->getGroups()->getGroupPayouts()[0]);
        return $this->create(debug: $debug, id: $tournaments->getTournamentId(), description: $tournaments->getTournamentDescription(), comment: $tournaments->getTournamentComment(), limitType: $limitType,  gameType: $gameType,  specialType: $specialType, chipCount: $tournaments->getTournamentChipCount(), location: $location, date: new DateTime(debug: false, id: NULL, time: $tournaments->getTournamentDate()->format("Y-m-d")), startTime: new DateTime(debug: false, id: NULL, time: $tournaments->getTournamentStartTime()->format("H:i")), buyinAmount: $tournaments->getTournamentBuyinAmount(), maxPlayers: $tournaments->getTournamentMaxPlayers(), maxRebuys: $tournaments->getTournamentMaxRebuys(), rebuyAmount: $tournaments->getTournamentRebuyAmount(), addonAmount: $tournaments->getTournamentAddonAmount(), addonChipCount: $tournaments->getTournamentAddonChipCount(), groupPayout: $groupPayout, rake: (float) $tournaments->getTournamentRake(), registeredCount: 0, buyinsPaid: 0, rebuysPaid: 0, rebuysCount: 0, addonsPaid: 0, enteredCount: 0);
    }
    public function __construct(protected bool $debug, protected string|int $id, protected ?string $description, protected ?string $comment, protected ?LimitType $limitType, protected ?GameType $gameType, protected ?SpecialType $specialType, protected int $chipCount, protected ?Location $location, protected ?DateTime $date, protected ?DateTime $startTime, protected int $buyinAmount, protected int $maxPlayers, protected int $maxRebuys, protected int $rebuyAmount, protected int $addonAmount, protected int $addonChipCount, protected ?GroupPayout $groupPayout, protected float $rake, protected int $registeredCount, protected int $buyinsPaid, protected int $rebuysPaid, protected int $rebuysCount, protected int $addonsPaid, protected int $enteredCount, protected int $earnings = 0) {
        parent::__construct(debug: $debug, id: $id);
    }
    private function create(bool $debug, string|int $id, ?string $description, ?string $comment, ?LimitType $limitType, ?GameType $gameType, ?SpecialType $specialType, int $chipCount, ?Location $location, ?DateTime $date, ?DateTime $startTime, int $buyinAmount, int $maxPlayers, int $maxRebuys, int $rebuyAmount, int $addonAmount, int $addonChipCount, ?GroupPayout $groupPayout, float $rake, int $registeredCount, int $buyinsPaid, int $rebuysPaid, int $rebuysCount, int $addonsPaid, int $enteredCount, int $earnings = 0): Tournament {
        parent::__construct(debug: $debug, id: $id);
        $this->description = $description;
        $this->comment = $comment;
        $this->limitType = $limitType;
        $this->gameType = $gameType;
        $this->specialType = $specialType;
        $this->chipCount = $chipCount;
        $this->location = $location;
        $this->date = $date;
        $this->startTime = $startTime;
        $this->buyinAmount = $buyinAmount;
        $this->maxPlayers = $maxPlayers;
        $this->maxRebuys = $maxRebuys;
        $this->rebuyAmount = $rebuyAmount;
        $this->addonChipCount = $addonChipCount;
        $this->groupPayout = $groupPayout;
        $this->rake = $rake;
        $this->registeredCount = $registeredCount;
        $this->buyinsPaid = $buyinsPaid;
        $this->rebuysPaid = $rebuysPaid;
        $this->rebuysCount = $rebuysCount;
        $this->addonsPaid = $addonsPaid;
        $this->enteredCount = $enteredCount;
        $this->earnings = $earnings;
        return $this;
    }
    public function getDescription(): ?string {
        return $this->description;
    }
    public function getComment(): ?string {
        return $this->comment;
    }
    public function getLimitType(): ?LimitType {
        return $this->limitType;
    }
    public function getGameType(): ?GameType {
        return $this->gameType;
    }
    public function getSpecialType(): ?SpecialType {
        return $this->specialType;
    }
    public function getChipCount(): int {
        return $this->chipCount;
    }
    public function getLocation(): ?Location {
        return $this->location;
    }
    public function getDate(): ?DateTime {
        return $this->date;
    }
    public function getStartTime(): ?DateTime {
        return $this->startTime;
    }
    public function getBuyinAmount(): int {
        return $this->buyinAmount;
    }
    public function getMaxPlayers(): int {
        return $this->maxPlayers;
    }
    public function getMaxRebuys(): int {
        return $this->maxRebuys;
    }
    public function getRebuyAmount(): int {
        return $this->rebuyAmount;
    }
    public function getAddonAmount(): int {
        return $this->addonAmount;
    }
    public function getAddonChipCount(): int {
        return $this->addonChipCount;
    }
    public function getGroupPayout(): ?GroupPayout {
        return $this->groupPayout;
    }
    public function getRake(): float {
        return $this->rake;
    }
    public function getRakeForCalculation(): float {
        return $this->rake / 100;
    }
    public function getRegistrationClose(): DateTime {
        $close = "";
        if (isset($this->startTime)) {
          $close = clone $this->startTime;
          $interval = new \DateInterval(duration: "PT2H"); // 2 hours
          $close->getTime()->sub($interval);
        }
        return $close;
    }
    public function getRegistrationOpen(): DateTime {
        return new DateTime(debug: $this->isDebug(), id: NULL, time: "12:00");
    }
    public function getRegisteredCount(): int {
        return $this->registeredCount;
    }
    public function getBuyinsPaid(): int {
        return $this->buyinsPaid;
    }
    public function getRebuysPaid(): int {
        return $this->rebuysPaid;
    }
    public function getRebuysCount(): int {
        return $this->rebuysCount;
    }
    public function getAddonsPaid(): int {
        return $this->addonsPaid;
    }
    public function getEnteredCount(): int {
        return $this->enteredCount;
    }
    public function getEarnings(): int {
        return $this->earnings;
    }
    public function getTournaments(): Tournaments {
        return $this->tournaments;
    }
    public function setDescription(string $description): Tournament {
        $this->description = $description;
        return $this;
    }
    public function setComment(string $comment): Tournament {
        $this->comment = $comment;
        return $this;
    }
    public function setLimitType(LimitType $limitType): Tournament {
        $this->limitType = $limitType;
        return $this;
    }
    public function setGameType(GameType $gameType): Tournament {
        $this->gameType = $gameType;
        return $this;
    }
    public function setSpecialType(SpecialType $specialType): Tournament {
        $this->specialType = $specialType;
        return $this;
    }
    public function setChipCount(int $chipCount): Tournament {
        $this->chipCount = $chipCount;
        return $this;
    }
    public function setLocation(Location $location): Tournament {
        $this->location = $location;
        return $this;
    }
    public function setDate(DateTime $date): Tournament {
        $this->date = $date;
        return $this;
    }
    public function setStartTime(DateTime $startTime): Tournament {
        $this->startTime = $startTime;
        return $this;
    }
    public function setBuyinAmount(int $buyinAmount): Tournament {
        $this->buyinAmount = $buyinAmount;
        return $this;
    }
    public function setMaxPlayers(int $maxPlayers): Tournament {
        $this->maxPlayers = $maxPlayers;
        return $this;
    }
    public function setMaxRebuys(int $maxRebuys): Tournament {
        $this->maxRebuys = $maxRebuys;
        return $this;
    }
    public function setRebuyAmount(int $rebuyAmount): Tournament {
        $this->rebuyAmount = $rebuyAmount;
        return $this;
    }
    public function setAddonAmount(int $addonAmount): Tournament {
        $this->addonAmount = $addonAmount;
        return $this;
    }
    public function setAddonChipCount(int $addonChipCount): Tournament {
        $this->addonChipCount = $addonChipCount;
        return $this;
    }
    public function setGroupPayout(GroupPayout $groupPayout): Tournament {
        $this->groupPayout = $groupPayout;
        return $this;
    }
    public function setRake(float $rake): Tournament {
        $this->rake = $rake;
        return $this;
    }
    public function setRegisteredCount(int $registeredCount): Tournament {
        $this->registeredCount = $registeredCount;
        return $this;
    }
    public function setEnteredCount(int $enteredCount): Tournament {
        $this->enteredCount = $enteredCount;
        return $this;
    }
    public function setBuyinsPaid(int $buyinsPaid): Tournament {
        $this->buyinsPaid = $buyinsPaid;
        return $this;
    }
    public function setRebuysPaid(int $rebuysPaid): Tournament {
        $this->rebuysPaid = $rebuysPaid;
        return $this;
    }
    public function setRebuysCount(int $rebuysCount): Tournament {
        $this->rebuysCount = $rebuysCount;
        return $this;
    }
    public function setAddonsPaid(int $addonsPaid): Tournament {
        $this->addonsPaid = $addonsPaid;
        return $this;
    }
    public function setEarnings(int $earnings): Tournament {
        $this->earnings = $earnings;
        return $this;
    }
    public function setTournaments(Tournaments $tournaments): Tournament {
        $this->tournaments = $tournaments;
        return $this;
    }
    public function getDateAndTime() {
        return new DateTime(debug: $this->isDebug(), id: NULL, time: $this->getDate()->getDatabaseFormat() . " " . $this->getStartTime()->getDatabaseTimeFormat());
    }
    public function getDisplayDetails() {
        $optionText = $this->getDate()->getDisplayFormat();
        $optionText .= "@" . $this->getStartTime()->getDisplayAmPmFormat();
        $optionText .= " (" . $this->getLocation()->getName() . ")";
        $optionText .= " " . $this->getLimitType()->getName();
        $optionText .= " " . $this->getGameType()->getName();
        $optionText .= " " . $this->getMaxRebuys() . "r" . (0 != $this->getAddonAmount() ? "+a" : "");
        $waitListCnt = $this->getRegisteredCount() - $this->getMaxPlayers();
        $optionText .= " (" . ($waitListCnt > 0 ? $this->getMaxPlayers() : $this->getRegisteredCount()) . ($waitListCnt > 0 ? "+" . $waitListCnt . "wl" : "") . "np/" . $this->getBuyinsPaid() . "p";
        $optionText .= "+" . $this->getRebuysPaid() . "rp";
        $optionText .= "+" . $this->getAddonsPaid() . "ap";
        $optionText .= "/" . $this->getEnteredCount() . "ent)";
        return $optionText;
    }
    public function __toString(): string {
        $output = parent::__toString();
        $output .= ", description = '";
        $output .= $this->description;
        $output .= "', comment = '";
        $output .= $this->comment;
        $output .= "', limitType = [";
        $output .= $this->limitType;
        $output .= "], gameType = [";
        $output .= $this->gameType;
        $output .= "], specialType = [";
        $output .= $this->specialType;
        $output .= "], chipCount = ";
        $output .= $this->chipCount;
        $output .= ", location = [";
        $output .= $this->location;
        $output .= "], date = ";
        $output .= $this->date->getDisplayFormat();
        $output .= ", startTime = ";
        $output .= $this->startTime->getDisplayAmPmFormat();
        $output .= ", buyinAmount = ";
        $output .= $this->buyinAmount;
        $output .= ", maxPlayers = ";
        $output .= $this->maxPlayers;
        $output .= ", maxRebuys = ";
        $output .= $this->maxRebuys;
        $output .= ", rebuyAmount = ";
        $output .= $this->rebuyAmount;
        $output .= ", addonAmount = ";
        $output .= $this->addonAmount;
        $output .= ", addonChipCount = ";
        $output .= $this->addonChipCount;
        $output .= ", groupPayout = [";
        $output .= $this->groupPayout;
        $output .= "], rake = ";
        $output .= $this->rake;
        $output .= ", registrationClose = ";
        $output .= $this->getRegistrationClose()->getDisplayAmPmFormat();
        $output .= "', registeredCount = ";
        $output .= $this->registeredCount;
        $output .= ", enteredCount = ";
        $output .= $this->enteredCount;
        $output .= ", buyinsPaid = ";
        $output .= $this->buyinsPaid;
        $output .= ", rebuysPaid = ";
        $output .= $this->rebuysPaid;
        $output .= ", rebuysCount = ";
        $output .= $this->rebuysCount;
        $output .= ", addonsPaid = ";
        $output .= $this->addonsPaid;
        $output .= ", earnings = ";
        $output .= $this->earnings;
        return $output;
    }
}
<?php
declare(strict_types = 1);
namespace Poker\Ccp\classes\model;
use Poker\Ccp\Entity\Results;
class Result extends Base {
    private Results $results;
    public function createFromEntity(bool $debug, Results $results): Result {
        $this->results = $results;
        $tournament = new Tournament(debug: $debug, id: 0, description: NULL, comment: NULL, limitType: NULL, gameType: NULL, specialType: NULL, chipCount: 0, location: NULL, date: NULL, startTime: NULL, buyinAmount: 0, maxPlayers: 0, maxRebuys: 0, rebuyAmount: 0, addonAmount: 0, addonChipCount: 0, groupPayout: NULL, rake: 0, registeredCount: 0, buyinsPaid: 0, rebuysPaid: 0, rebuysCount: 0, addonsPaid: 0, enteredCount: 0, earnings: 0);
        $tournament->createFromEntity(debug: $debug, tournaments: $results->getTournaments());
        $player = new Player();
        $player->createFromEntity(debug: $debug, players: $results->getPlayers());
        $status = new Status();
        $status->createFromEntity(debug: $debug, statusCodes: $results->getStatusCodes());
        $knockedOutBy = new Player();
        $knockedOutBy->createFromEntity(debug: $debug, players: $results->getPlayerKos());
        return $this->create(debug: $debug, id: NULL, tournament: $tournament, player: $player, status: $status, registerOrder: $results->getResultRegistrationOrder(), buyinPaid: $results->getResultPaidBuyinFlag(), rebuyPaid: $results->getResultPaidRebuyFlag(), addonPaid: $results->getResultPaidAddonFlag(), rebuyCount: $results->getResultRebuyCount(), addonFlag: $results->getResultAddonFlag(), place: $results->getResultPlaceFinished(), knockedOutBy: $knockedOutBy, food: $results->getResultRegistrationFood(), feeStatus: NULL);
    }
    public function __construct(protected bool $debug, protected string|int|NULL $id, protected Tournament $tournament, protected Player $player, protected Status $status, protected int $registerOrder, protected bool $buyinPaid, protected bool $rebuyPaid, protected bool $addonPaid, protected int $rebuyCount, protected bool $addonFlag, protected int $place, protected Player $knockedOutBy, protected ?string $food, protected ?string $feeStatus) {
        parent::__construct(debug: $debug, id: $id);
    }
    private function create(bool $debug, string|int|NULL $id, Tournament $tournament, Player $player, Status $status, int $registerOrder, bool $buyinPaid, bool $rebuyPaid, bool $addonPaid, int $rebuyCount, bool $addonFlag, int $place, Player $knockedOutBy, ?string $food, ?string $feeStatus): Result {
        parent::__construct(debug: $debug, id: $id);
        $this->tournament = $tournament;
        $this->player = $player;
        $this->status = $status;
        $this->registerOrder = $registerOrder;
        $this->buyinPaid = $buyinPaid;
        $this->addonPaid = $addonPaid;
        $this->rebuyCount = $rebuyCount;
        $this->addonFlag = $addonFlag;
        $this->place = $place;
        $this->knockedOutBy = $knockedOutBy;
        $this->food = $food;
        $this->feeStatus = $feeStatus;
        return $this;
    }
    public function getTournament(): Tournament {
        return $this->tournament;
    }
    public function getPlayer(): Player {
        return $this->player;
    }
    public function getStatus(): Status {
        return $this->status;
    }
    public function getRegisterOrder(): int {
        return $this->registerOrder;
    }
    public function isBuyinPaid(): bool {
        return $this->buyinPaid;
    }
    public function isRebuyPaid(): bool {
        return $this->rebuyPaid;
    }
    public function isAddonPaid(): bool {
        return $this->addonPaid;
    }
    public function getRebuyCount(): int {
        return $this->rebuyCount;
    }
    public function isAddonFlag(): bool {
        return $this->addonFlag;
    }
    public function getPlace(): int {
        return $this->place;
    }
    public function getKnockedOutBy(): Player {
        return $this->knockedOutBy;
    }
    public function getFood(): ?string {
        return $this->food;
    }
    public function getFeeStatus(): string {
        return $this->feeStatus;
    }
    public function getResults(): Results {
        return $this->results;
    }
    public function setTournament(Tournament $tournament): Result {
        $this->tournament = $tournament;
        return $this;
    }
    public function setPlayer(Player $player): Result {
        $this->player = $player;
        return $this;
    }
    public function setStatus(Status $status): Result {
        $this->status = $status;
        return $this;
    }
    public function setRegisterOrder(int $registerOrder): Result {
        $this->registerOrder = $registerOrder;
        return $this;
    }
    public function setBuyinPaid(bool $buyinPaid): Result {
        $this->buyinPaid = $buyinPaid;
        return $this;
    }
    public function setRebuyPaid(bool $rebuyPaid): Result {
        $this->rebuyPaid = $rebuyPaid;
        return $this;
    }
    public function setAddonPaid(bool $addonPaid): Result {
        $this->addonPaid = $addonPaid;
        return $this;
    }
    public function setRebuyCount(int $rebuyCount): Result {
        $this->rebuyCount = $rebuyCount;
        return $this;
    }
    public function setAddonFlag(bool $addonFlag): Result {
        $this->addonFlag = $addonFlag;
        return $this;
    }
    public function setPlace(int $place): Result {
        $this->place = $place;
        return $this;
    }
    public function setKnockedOutBy(Player $knockedOutBy): Result {
        $this->knockedOutBy = $knockedOutBy;
        return $this;
    }
    public function setFood(?string $food): Result {
        $this->food = $food;
        return $this;
    }
    public function setFeeStatus(string $feeStatus): Result {
        $this->feeStatus = $feeStatus;
        return $this;
    }
    public function setResults(Results $results): Result {
        $this->results = $results;
        return $this;
    }
    public function __toString(): string {
        $output = parent::__toString();
        $output .= "tournament = [";
        $output .= $this->tournament;
        $output .= "], player = [";
        $output .= $this->player;
        $output .= "], status = [";
        $output .= $this->status;
        $output .= "], registerOrder = ";
        $output .= $this->registerOrder;
        $output .= ", buyinPaid = ";
        $output .= var_export(value: $this->buyinPaid, return: true);
        $output .= ", rebuyPaid = ";
        $output .= var_export(value: $this->rebuyPaid, return: true);
        $output .= ", addonPaid = ";
        $output .= var_export(value: $this->addonPaid, return: true);
        $output .= ", rebuyCount = ";
        $output .= $this->rebuyCount;
        $output .= ", addonFlag = ";
        $output .= var_export(value: $this->addonFlag, return: true);
        $output .= ", place = ";
        $output .= $this->place;
        $output .= ", knockedOutBy = [";
        $output .= $this->knockedOutBy;
        $output .= "], food = '";
        $output .= $this->food;
        $output .= "', feeStatus = '";
        $output .= $this->feeStatus;
        $output .= "'";
        return $output;
    }
}
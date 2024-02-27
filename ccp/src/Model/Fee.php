<?php
declare(strict_types = 1);
namespace Poker\Ccp\Model;
use Poker\Ccp\Entity\Fees;
class Fee extends Base {
    private Fees $fees;
    public function createFromEntity(bool $debug, Fees $fees): Fee {
        $this->fees = $fees;
        return $this->create(debug: $debug, seasonId: $fees->getSeasons()->getSeasonId(), playerId: $fees->getPlayers()->getPlayerId(), amount: $fees->getFeeAmount(), status: NULL);
    }
    public function __construct(protected bool $debug, protected int $seasonId, protected int $playerId, protected int $amount, protected string $status) {
        parent::__construct(debug: $debug, id: NULL);
    }
    private function create(bool $debug, int $seasonId, int $playerId, int $amount, string $status): Fee {
        parent::__construct(debug: $debug, id: NULL);
        $this->seasonId = $seasonId;
        $this->playerId = $playerId;
        $this->amount = $amount;
        $this->status = $status;
        return $this;
    }
    public function getAmount(): int {
        return $this->amount;
    }
    public function getPlayerId(): int {
        return $this->playerId;
    }
    public function getSeasonId(): int {
        return $this->seasonId;
    }
    public function getStatus(): string {
        return $this->status;
    }
    public function getFees(): Fees {
        return $this->fees;
    }
    public function setAmount(int $amount): Fee {
        $this->amount = $amount;
        return $this;
    }
    public function setPlayerId(string $playerId): Fee {
        $this->playerId = $playerId;
        return $this;
    }
    public function setSeasonId(string $seasonId): Fee {
        $this->seasonId = $seasonId;
        return $this;
    }
    public function setStatus(string $status): Fee {
        $this->status = $status;
        return $this;
    }
    public function setFees(Fees $fees): Fee {
        $this->fees = $fees;
        return $this;
    }
    public function __toString(): string {
        $output = parent::__toString();
        $output .= ", amount = ";
        $output .= $this->amount;
        $output .= ", playerId = ";
        $output .= $this->playerId;
        $output .= ", seasonId = ";
        $output .= $this->seasonId;
        $output .= " status = '";
        $output .= $this->status;
        $output .= "'";
        return $output;
    }
}
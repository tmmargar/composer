<?php
declare(strict_types = 1);
namespace Poker\Ccp\classes\model;
class Fee extends Base {
  public function __construct(protected bool $debug, protected int $seasonId, protected int $playerId, protected int $amount, protected string $status) {
    parent::__construct(debug: $debug, id: NULL);
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
  public function setAmount(int $amount) {
    $this->amount = $amount;
  }
  public function setPlayerId(string $playerId) {
    $this->playerId = $playerId;
  }
  public function setSeasonId(string $seasonId) {
    $this->seasonId = $seasonId;
  }
  public function setStatus(string $status) {
    $this->status = $status;
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
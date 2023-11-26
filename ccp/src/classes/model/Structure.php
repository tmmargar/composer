<?php
declare(strict_types = 1);
namespace Poker\Ccp\classes\model;
class Structure extends Base {
  public function __construct(protected bool $debug, protected string|int|NULL $id, protected int $place, protected float $percentage) {
    parent::__construct(debug: $debug, id: $id);
  }
  public function getPlace(): int {
    return $this->place;
  }
  public function getPercentage(): float {
    return $this->percentage;
  }
  public function setPlace(int $place) {
    $this->place = $place;
  }
  public function setPercentage(float $percentage) {
    $this->percentage = $percentage;
  }
  public function __toString(): string {
    $output = parent::__toString();
    $output .= ", place = ";
    $output .= $this->place;
    $output .= ", percentage = ";
    $output .= $this->percentage;
    return $output;
  }
}
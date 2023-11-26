<?php
declare(strict_types = 1);
namespace Poker\Ccp\classes\model;
class SpecialType extends Base {
  public function __construct(protected bool $debug, protected string|int|NULL $id, protected string|NULL $description, protected int|NULL $multiplier) {
    parent::__construct(debug: $debug, id: $id);
  }
  public function getDescription(): string|NULL {
    return $this->description;
  }
  public function getMultiplier(): int|NULL {
    return $this->multiplier;
  }
  public function setDescription(string $description) {
    $this->description = $description;
  }
  public function __toString(): string {
    $output = parent::__toString();
    $output .= ", description = '";
    $output .= $this->description;
    $output .= "', multiplier = ";
    $output .= $this->multiplier;
    $output .= "";
    return $output;
  }
}
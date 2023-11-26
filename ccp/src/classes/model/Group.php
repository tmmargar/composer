<?php
declare(strict_types = 1);
namespace Poker\Ccp\classes\model;
class Group extends Base {
  public function __construct(protected bool $debug, protected string|int|NULL $id, protected string $name) {
    parent::__construct(debug: $debug, id: $id);
  }
  public function getName(): string {
    return $this->name;
  }
  public function setName(string $name) {
    $this->name = $name;
  }
  public function __toString(): string {
    $output = parent::__toString();
    $output .= ", name = '";
    $output .= $this->getName();
    $output .= "'";
    return $output;
  }
}
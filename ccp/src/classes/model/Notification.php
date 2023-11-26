<?php
declare(strict_types = 1);
namespace Poker\Ccp\classes\model;
class Notification extends Base {
  public function __construct(protected bool $debug, protected string|int|NULL $id, protected string $description, protected DateTime $startDate, protected DateTime $endDate) {
    parent::__construct(debug: $debug, id: $id);
  }
  public function getDescription(): string {
    return $this->description;
  }
  public function getEndDate(): DateTime {
    return $this->endDate;
  }
  public function getStartDate(): DateTime {
    return $this->startDate;
  }
  public function setDescription(string $description) {
    $this->description = $description;
  }
  public function setEndDate(DateTime $endDate) {
    $this->endDate = $endDate;
  }
  public function setStartDate(DateTime $startDate) {
    $this->startDate = $startDate;
  }
  public function __toString(): string {
    $output = parent::__toString();
    $output .= "', description = '";
    $output .= $this->description;
    $output .= "', startDate = '";
    $output .= $this->startDate->getDisplayFormat();
    $output .= "', endDate = '";
    $output .= $this->endDate->getDisplayFormat();
    $output .= "'";
    return $output;
  }
}
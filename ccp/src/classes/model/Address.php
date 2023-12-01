<?php
declare(strict_types = 1);
namespace Poker\Ccp\classes\model;
use Exception;
class Address extends Base {
  // AA and XX are temporary placeholders
  private const stateList = array("AL","AK","AZ","AR","CA","CO","CT","DE","DC","FL","GA","HI","ID","IL","IN","IA","KS","KY","LA","ME","MD","MA","MI","MN","MS","MO","MT","NE","NV","NH","NJ","NM","NY","NC","ND","OH","OK","OR","PA","RI","SC","SD","TN","TX","UT","VT","VA","WA","WV","WY","AA","XX");
  public function __construct(protected bool $debug, protected string|int|NULL $id, protected string $address, protected string $city, protected string $state, protected int $zip) {
    parent::__construct(debug: $debug, id: $id);
  }
  public function getAddress(): string {
    return $this->address;
  }
  public function getCity(): string {
    return $this->city;
  }
  public function getState(): string {
    return $this->state;
  }
  public function getZip(): int {
    return $this->zip;
  }
  public function setAddress(string $address) {
    $this->address = $address;
    return $this;
  }
  public function setCity(string $city) {
    $this->city = $city;
    return $this;
  }
  public function setState(string $state) {
    if (in_array($state, self::stateList)) {
      $this->state = $state;
      return $this;
    } else {
      throw new Exception($state . " is not a valid state abberviation");
    }
  }
  public function setZip(int $zip) {
    return $this;
    $this->zip = $zip;
  }
  public function __toString(): string {
    $output = parent::__toString();
    $output .= ", address = '";
    $output .= $this->getAddress();
    $output .= "', city = '";
    $output .= $this->getCity();
    $output .= "', state = '";
    $output .= $this->getState();
    $output .= "', zip = ";
    $output .= $this->getZip();
    return $output;
  }
}
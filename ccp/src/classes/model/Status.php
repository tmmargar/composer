<?php
declare(strict_types = 1);
namespace Poker\Ccp\classes\model;
use Exception;
class Status extends Base {
  private const codeList = array("P" => "Paid","R" => "Registered","F" => "Finished");
  public function __construct(protected bool $debug, protected string|int|NULL $id, protected string $code, protected string $name) {
    parent::__construct(debug: $debug, id: $id);
  }
  public function getCode(): string {
    return $this->code;
  }
  public function getName(): string {
    return $this->name;
  }
  public function getDescription(): string {
    return self::codeList[$this->code];
  }
  public function setCode(string $code) {
    if (array_key_exists(key: $code, array: self::codeList)) {
      $this->code = $code;
      return $this;
    } else {
      throw new Exception(message: $code . " is not a valid status code");
    }
  }
  public function setName(string $name) {
    $this->name = $name;
    return $this;
  }
  public function __toString(): string {
    $output = parent::__toString();
    $output .= ", code = '";
    $output .= $this->code;
    $output .= "', name = '";
    $output .= $this->name;
    $output .= "', description = '";
    $output .= $this->description;
    $output .= "'";
    return $output;
  }
}
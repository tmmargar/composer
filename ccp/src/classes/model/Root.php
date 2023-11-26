<?php
declare(strict_types = 1);
namespace Poker\Ccp\classes\model;
abstract class Root {
  public function __construct(protected bool $debug = false) {}
  public function isDebug(): bool {
    return $this->debug;
  }
  public function setDebug(bool $debug) {
    $this->debug = $debug;
  }
  public function __toString(): string {
    $output = "debug = ";
    if (isset($this->debug)) {
      $output .= var_export(value: $this->debug, return: true);
    }
    return $output;
  }
}
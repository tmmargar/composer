<?php
declare(strict_types = 1);
namespace Poker\Ccp\Model;
abstract class Root {
    public function __construct(protected bool $debug = false) {}
    public function isDebug(): bool {
        return $this->debug;
    }
    public function setDebug(bool $debug): Root {
        $this->debug = $debug;
        return $this;
    }
    public function __toString(): string {
        $output = "debug = ";
        if (isset($this->debug)) {
            $output .= var_export(value: $this->debug, return: true);
        }
        return $output;
    }
}
<?php
declare(strict_types = 1);
namespace Poker\Ccp\Model;
class Phone extends Base {
    public function __construct(protected bool $debug, protected string|int|NULL $id, protected string $value) {
        parent::__construct(debug: $debug, id: $id);
    }
    public function getValue(): string {
        return $this->value == "0" ? "0000000000" : $this->value;
    }
    public function setValue(string $value) {
        $this->value = $value;
        return $this;
    }
    public function getDisplayFormatted(): string {
        // note: making sure we have something
        if (! isset($this->value[3])) {return '';}
            // note: strip out everything but numbers
            $valueNumbersOnly = preg_replace(pattern: "/[^0-9]/", replacement: "", subject: $this->value);
            $length = strlen(string: $valueNumbersOnly);
            switch ($length) {
            case 7:
                return preg_replace("/([0-9]{3})([0-9]{4})/", "$1-$2", $valueNumbersOnly);
                break;
            case 10:
                return preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "($1) $2-$3", $valueNumbersOnly);
                break;
            case 11:
                return preg_replace("/([0-9]{1})([0-9]{3})([0-9]{3})([0-9]{4})/", "$1($2) $3-$4", $valueNumbersOnly);
                break;
            default:
                return $valueNumbersOnly;
                break;
            }
    }
    public function __toString(): string {
        $output = parent::__toString();
        $output .= ", value = '";
        $output .= $this->value;
        $output .= "'";
        return $output;
    }
}
<?php
declare(strict_types = 1);
namespace Poker\Ccp\Model;
abstract class Base extends Root {
    public function __construct(protected bool $debug, private string|int|NULL $id) {
        $this->id = Base::build(value: $id, suffix: NULL);
    }
    public function getId(): string {
        return $this->id;
    }
    public function setId(int $id): Base {
        $this->id = $this->build(value: $id, suffix: NULL);
        return $this;
    }
    public function __toString(): string {
        $output = parent::__toString();
        $output .= ", id = ";
        $output .= $this->id;
        return $output;
    }
    public static function build(string|int|NULL $value, ?string $suffix): string {
        $idTemp = "";
        if (isset($value)) {
            $temp = explode(separator: " ", string: (string) $value);
            if (count(value: $temp) > 1) {
                $counter = 0;
                foreach ($temp as $val) {
                  if (0 == $counter) {
                    $val = strtolower(string: $val);
                  } else {
                    $val = ucfirst(string: $val);
                  }
                  $idTemp .= $val;
                  $counter ++;
                }
            } else {
                $idTemp = lcfirst(string: (string) $value);
            }
            if (isset($suffix)) {
                $idTemp .= $suffix;
            }
        }
        return $idTemp;
    }
}
<?php
declare(strict_types = 1);
namespace Poker\Ccp\classes\model;
class FormBase extends Base {
  public function __construct(protected bool $debug, protected array|NULL $class, protected bool $disabled, protected string|int|NULL $id, protected string|NULL $name, protected string|NULL $suffix,
    protected array|string|int|NULL $value) {
    parent::__construct(debug: $debug, id: $id);
    $this->class = NULL == $class ? array() : $class;
    $this->name = Base::build(value: $name, suffix: NULL);
    $this->value = (isset($value) && $value != "") || $value == 0 ? $value : NULL;
  }
  public function getClass(): array|NULL {
    return $this->class;
  }
  public function getClassAsString(): string {
    return implode(" ", $this->class);
  }
  public function getName(): string|NULL {
    return $this->name;
  }
  public function getSuffix(): string|NULL {
    return $this->suffix;
  }
  public function getValue(): array|string|int|NULL {
    return $this->value;
  }
  public function isDisabled(): bool {
    return $this->disabled;
  }
  public function setClass(array|NULL $class) {
    $this->class = $class;
  }
  public function setDisabled(bool $disabled) {
    $this->disabled = $disabled;
  }
  public function setName(string|NULL $name) {
    $this->name = $name;
  }
  public function setSuffix(string|NULL $suffix) {
    $this->suffix = $suffix;
  }
  public function setValue(array|string|NULL $value) {
    $this->value = $value;
  }
  public function toString(): string {
    $output = parent::__toString();
    $output .= ", class = ";
    $output .= print_r(value: $this->class, return: true);
    $output .= ", disabled = ";
    $output .= $this->disabled;
    $output .= ", name = '";
    $output .= $this->name;
    $output .= ", suffx = '";
    $output .= $this->suffix;
    $output .= "', value = '";
    $output .= $this->value;
    $output .= "'";
    return $output;
  }
}
<?php
declare(strict_types = 1);
namespace Poker\Ccp\classes\model;
class FormOption extends FormBase {
  public function __construct(protected bool $debug, protected array|NULL $class, protected bool $disabled, protected int|string|NULL $id, protected string|NULL $name,
    protected string|int|NULL $selectedValue, protected string|NULL $suffix, protected string $text, protected array|string|int|NULL $value) {
    parent::__construct(debug: $debug, class: $class, disabled: $disabled, id: $id, name: $name, suffix: $suffix, value: $value);
  }
  public function getHtml(): string {
    return "<option" .
    // (isset($this->isDisabled()) && $this->isDisabled() ? " disabled" : "") .
    (NULL !== $this->isDisabled() && $this->isDisabled() ? " disabled" : "") . " value=\"" . $this->getValue() . "\"" .
      (isset($this->selectedValue) && ($this->selectedValue == $this->getValue()) ? " selected" : "") . ">" . htmlentities(string: $this->text, flags: ENT_NOQUOTES, encoding: "UTF-8") . "</option>\n";
  }
  public function getSelectedValue(): string|NULL {
    return $this->selectedValue;
  }
  public function getText(): string {
    return $this->text;
  }
  public function setSelctedValue(string $selectedValue) {
    $this->selectedValue = $selectedValue;
  }
  public function setText(string $text) {
    $this->text = $text;
  }
  public function toString(): string {
    $output = parent::__toString();
    $output .= "selectedValue = '";
    $output .= $this->selectedValue;
    $output .= "', text = '";
    $output .= $this->text;
    $output .= "'";
    return $output;
  }
}
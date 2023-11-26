<?php
declare(strict_types = 1);
namespace Poker\Ccp\classes\model;
class FormControl extends FormBase {
  // DO NOT USE FormInput eclipse shows errors even though code runs
  public const TYPE_INPUT_BUTTON = "button";
  public const TYPE_INPUT_CHECKBOX = "checkbox";
  public const TYPE_INPUT_DATE_TIME = "datetime-local";
  public const TYPE_INPUT_EMAIL = "email";
  public const TYPE_INPUT_HIDDEN = "hidden";
  public const TYPE_INPUT_NUMBER = "number";
  public const TYPE_INPUT_PASSWORD = "password";
  public const TYPE_INPUT_RESET = "reset";
  public const TYPE_INPUT_SUBMIT = "submit";
  public const TYPE_INPUT_TELEPHONE = "tel";
  public const TYPE_INPUT_TEXTAREA = "textarea";
  public const TYPE_INPUT_TEXTBOX = "text";
  public function __construct(protected bool $debug, protected string|NULL $accessKey, protected string|NULL $autoComplete, protected bool $autoFocus, protected bool|NULL $checked,
    protected array|NULL $class, protected int|NULL $cols, protected bool $disabled, protected string|int|NULL $id, protected int|NULL $maxLength, protected string|NULL $name,
    protected string|NULL $onClick, protected string|NULL $placeholder, protected bool $readOnly, protected bool|NULL $required, protected int|NULL $rows, protected int|NULL $size,
    protected string|NULL $suffix, protected string|NULL $type, protected array|string|int|NULL $value, protected string|NULL $wrap, protected string|NULL $import = NULL,
    protected bool|NULL $noValidate = false) {
    parent::__construct(debug: $debug, class: $class, disabled: $disabled, id: $id . (self::TYPE_INPUT_RESET == $type ? "Button" : ""), name: $name . (self::TYPE_INPUT_RESET == $type ? "Button" : ""),
      suffix: $suffix, value: $value);
  }
  public function getAccessKey(): string|NULL {
    return $this->accessKey;
  }
  public function getAutoComplete(): string|NULL {
    return $this->autoComplete;
  }
  public function getCols(): int|NULL {
    return $this->cols;
  }
  public function getHtml(): string {
    $temp = "";
    if (isset($this->onClick)) {
      $temp = "<script type=\"module\">\n" . "  import { dataTable, display, input } from \"./scripts/import.js\";\n" . $this->import . "  document.querySelector(\"#" . $this->getId() .
        "\").addEventListener(\"click\", (evt) => { " . $this->onClick . " });\n" . "</script>\n";
    }
    return $temp .
      (self::TYPE_INPUT_TEXTAREA == $this->type ? "<textarea" : (self::TYPE_INPUT_BUTTON == $this->type || self::TYPE_INPUT_SUBMIT == $this->type || self::TYPE_INPUT_RESET == $this->type ? "<button" : "<input")) .
      (isset($this->accessKey) ? " accesskey=\"" . $this->accessKey . "\"" : "") . (isset($this->autoComplete) ? " autocomplete=\"" . $this->autoComplete . "\"" : "") .
      (isset($this->autoFocus) && $this->autoFocus ? " autofocus" : "") . (isset($this->checked) && $this->checked ? " checked" : "") .
      ("" != $this->getClassAsString() ? " class=\"" . $this->getClassAsString() . "\"" : "") . (isset($this->cols) ? " cols=\"" . $this->cols . "\"" : "") .
      // data-mask-clearifnotmatch=\"true\"
      // (self::TYPE_INPUT_TELEPHONE == $this->type ? " data-inputmask=\"'mask': '(000) 000-0000'\"" : "") .
      (NULL !== $this->isDisabled() && $this->isDisabled() ? " disabled" : "") . (isset($this->onClick) ? " href=\"#\"" : "") . " id=\"" . $this->getId() . "\"" .
      (isset($this->maxLength) ? " maxlength=\"" . $this->maxLength . "\"" : "") . " name=\"" . $this->getName() . "\"" .
      // (self::TYPE_INPUT_TELEPHONE == $this->type ? " pattern=\"[\(]\d{3}[\)] \d{3}[\-]\d{4}\"" : "") .
      // (isset($this->placeholder) ? " placeholder=\"" . $this->placeholder . "\"" : (self::TYPE_INPUT_TELEPHONE == $this->type ? " placeholder=\"(999) 999-9999\"" : "")) .
      (isset($this->readOnly) && $this->readOnly ? " readonly" : "") .
      // (isset($this->required) ? " required=\"" . $this->required . "\"" : "") .
      ($this->required ? " required" : "") . (isset($this->rows) ? " rows=\"" . $this->rows . "\"" : "") . (isset($this->size) ? " size=\"" . $this->size . "\"" : "") . " type=\"" .
      ((self::TYPE_INPUT_RESET == $this->type) ? self::TYPE_INPUT_BUTTON : $this->type) . "\"" .
      // " type=\"" . (self::TYPE_INPUT_TELEPHONE == $this->type ? self::TYPE_INPUT_TELEPHONE : $this->type) . "\"" .
      (self::TYPE_INPUT_BUTTON !== $this->type && NULL !== $this->getValue() ? " value=\"" . htmlentities((string) $this->getValue(), ENT_NOQUOTES, "UTF-8") . "\"" : "") .
      (isset($this->wrap) ? " wrap=\"" . $this->wrap . "\"" : "") .
      // (isset($this->onClick) ? " onclick=\"" . $this->onClick . "\"" : "") .
      (self::TYPE_INPUT_BUTTON == $this->type || self::TYPE_INPUT_SUBMIT == $this->type || self::TYPE_INPUT_RESET == $this->type || self::TYPE_INPUT_HIDDEN == $this->type ||
      self::TYPE_INPUT_CHECKBOX == $this->type ? "" : " onfocus=\"this.select();\"") .
      // (self::TYPE_INPUT_TELEPHONE == $this->type ? " pattern=\"[0-9]{3}-[0-9]{3}-[0-9]{4}\" placeholder=\"(999) 999-9999\"" : "") .
      // (self::TYPE_INPUT_TELEPHONE == $this->type ? " data-mask=\"1 (000) 000-0000\"" : "") .
      (self::TYPE_INPUT_TEXTAREA == $this->type ? ">" . $this->getValue() . "</textarea>\n" : (self::TYPE_INPUT_DATE_TIME == $this->type ? ">" : (self::TYPE_INPUT_RESET == $this->type ||
      $this->noValidate ? " formnovalidate>" . $this->getValue() . "</button>" : (self::TYPE_INPUT_BUTTON == $this->type || self::TYPE_INPUT_SUBMIT == $this->type ? ">" . $this->getValue() .
      "</button>" : " />\n"))));
  }
  public function getImport(): string|NULL {
    return $this->import;
  }
  public function getMaxLength(): int|NULL {
    return $this->maxLength;
  }
  public function getOnClick(): string|NULL {
    return $this->onClick;
  }
  public function getPlaceholder(): string|NULL {
    return $this->placeholder;
  }
  public function getRows(): int|NULL {
    return $this->rows;
  }
  public function getSize(): int|NULL {
    return $this->size;
  }
  public function getType(): string|NULL {
    return $this->type;
  }
  public function getWrap(): string|NULL {
    return $this->wrap;
  }
  public function isAutoFocus(): bool|NULL {
    return $this->autoFocus;
  }
  public function isChecked(): bool|NULL {
    return $this->checked;
  }
  public function isNoValidate(): bool|NULL {
    return $this->noValidate;
  }
  public function isReadOnly(): bool {
    return $this->readOnly;
  }
  public function isRequired(): bool|NULL {
    return $this->required;
  }
  public function setAccessKey(string|NULL $accessKey) {
    $this->accessKey = $accessKey;
  }
  public function setAutoComplete($autoComplete) {
    $this->autoComplete = $autoComplete;
  }
  public function setAutoFocus(bool $autoFocus) {
    $this->autoFocus = $autoFocus;
  }
  public function setChecked(bool|NULL $checked) {
    $this->checked = $checked;
  }
  public function setCols(int|NULL $cols) {
    $this->cols = $cols;
  }
  public function setImport(string|NULL $import) {
    $this->import = $import;
  }
  public function setMaxLength(int|NULL $maxLength) {
    $this->maxLength = $maxLength;
  }
  public function setNoValidate(bool|NULL $noValidate) {
    $this->noValidate = $noValidate;
  }
  public function setOnClick(string|NULL $onClick) {
    $this->onClick = $onClick;
  }
  public function setPlaceholder(string|NULL $placeholder) {
    $this->placeholder = $placeholder;
  }
  public function setReadOnly(bool $readOnly) {
    $this->readOnly = $readOnly;
  }
  public function setRequired(bool|NULL $required) {
    $this->required = $required;
  }
  public function setRows(int|NULL $rows) {
    $this->rows = $rows;
  }
  public function setSize(int|NULL $size) {
    $this->size = $size;
  }
  public function setType(string|NULL $type) {
    $this->type = $type;
  }
  public function setWrap(string|NULL $wrap) {
    $this->wrap = $wrap;
  }
  public function toString(): string {
    $output = parent::__toString();
    $output .= ", accessKey = '";
    $output .= $this->accessKey;
    $output .= "', autoComplete = '";
    $output .= $this->autoComplete;
    $output .= "', autoFocus = ";
    $output .= var_export(value: $this->autoFocus, return: true);
    $output .= ", checked = ";
    $output .= var_export(value: $this->checked, return: true);
    $output .= ", cols = ";
    $output .= $this->cols;
    $output .= ", import = '";
    $output .= $this->import;
    $output .= "', maxLength = ";
    $output .= $this->maxLength;
    $output .= ", noValidate = ";
    $output .= $this->noValidate;
    $output .= ", onClick = '";
    $output .= $this->onClick;
    $output .= "', placeholder = '";
    $output .= $this->placeholder;
    $output .= "', readOnly = ";
    $output .= var_export(value: $this->readOnly, return: true);
    $output .= ", required = ";
    $output .= var_export(value: $this->required, return: true);
    $output .= ", rows = ";
    $output .= $this->rows;
    $output .= ", size = ";
    $output .= $this->size;
    $output .= ", type = '";
    $output .= $this->type;
    $output .= "', wrap = ";
    $output .= $this->wrap;
    $output .= "', import = ";
    $output .= $this->import;
    $output .= "', noValidate = ";
    $output .= var_export(value: $this->noValidate, return: true);
    return $output;
  }
}
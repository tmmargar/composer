<?php
declare(strict_types = 1);
namespace Poker\Ccp\Model;
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
    public function __construct(protected bool $debug, protected ?string $accessKey, protected ?string $autoComplete, protected bool $autoFocus, protected ?bool $checked, protected ?array $class, protected ?int $cols, protected bool $disabled, protected string|int|NULL $id, protected ?int $maxLength, protected ?string $name, protected ?string $onClick, protected ?string $placeholder, protected bool $readOnly, protected ?bool $required, protected ?int $rows, protected ?int $size, protected ?string $suffix, protected ?string $type, protected array|string|int|NULL $value, protected ?string $wrap, protected ?string $import = NULL, protected ?bool $noValidate = false) {
        parent::__construct(debug: $debug, class: $class, disabled: $disabled, id: $id . (self::TYPE_INPUT_RESET == $type ? "Button" : ""), name: $name . (self::TYPE_INPUT_RESET == $type ? "Button" : ""), suffix: $suffix, value: $value);
    }
    public function getAccessKey(): ?string {
        return $this->accessKey;
    }
    public function getAutoComplete(): ?string {
        return $this->autoComplete;
    }
    public function getCols(): ?int {
        return $this->cols;
    }
    public function getHtml(): string {
        $temp = "";
        if (isset($this->onClick)) {
            $temp = "<script type=\"module\">\n" . "  import { dataTable, display, input } from \"./scripts/import.js\";\n" . $this->import . "  document.querySelector(\"#" . $this->getId() . "\").addEventListener(\"click\", (evt) => { " . $this->onClick . " });\n" . "</script>\n";
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
    public function getImport(): ?string {
        return $this->import;
    }
    public function getMaxLength(): ?int {
        return $this->maxLength;
    }
    public function getOnClick(): ?string {
        return $this->onClick;
    }
    public function getPlaceholder(): ?string {
        return $this->placeholder;
    }
    public function getRows(): ?int {
        return $this->rows;
    }
    public function getSize(): ?int {
        return $this->size;
    }
    public function getType(): ?string {
        return $this->type;
    }
    public function getWrap(): ?string {
        return $this->wrap;
    }
    public function isAutoFocus(): ?bool {
        return $this->autoFocus;
    }
    public function isChecked(): ?bool {
        return $this->checked;
    }
    public function isNoValidate(): ?bool {
        return $this->noValidate;
    }
    public function isReadOnly(): bool {
        return $this->readOnly;
    }
    public function isRequired(): ?bool {
        return $this->required;
    }
    public function setAccessKey(?string $accessKey) {
        $this->accessKey = $accessKey;
        return $this;
    }
    public function setAutoComplete($autoComplete) {
        $this->autoComplete = $autoComplete;
        return $this;
    }
    public function setAutoFocus(bool $autoFocus) {
        $this->autoFocus = $autoFocus;
        return $this;
    }
    public function setChecked(?bool $checked) {
        $this->checked = $checked;
        return $this;
    }
    public function setCols(?int $cols) {
        $this->cols = $cols;
        return $this;
    }
    public function setImport(?string $import) {
        $this->import = $import;
        return $this;
    }
    public function setMaxLength(?int $maxLength) {
        $this->maxLength = $maxLength;
        return $this;
    }
    public function setNoValidate(?bool $noValidate) {
        $this->noValidate = $noValidate;
        return $this;
    }
    public function setOnClick(?string $onClick) {
        $this->onClick = $onClick;
        return $this;
    }
    public function setPlaceholder(?string $placeholder) {
        $this->placeholder = $placeholder;
        return $this;
    }
    public function setReadOnly(bool $readOnly) {
        $this->readOnly = $readOnly;
        return $this;
    }
    public function setRequired(?bool $required) {
        $this->required = $required;
        return $this;
    }
    public function setRows(?int $rows) {
        $this->rows = $rows;
        return $this;
    }
    public function setSize(?int $size) {
        $this->size = $size;
        return $this;
    }
    public function setType(?string $type) {
        $this->type = $type;
        return $this;
    }
    public function setWrap(?string $wrap) {
        $this->wrap = $wrap;
        return $this;
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
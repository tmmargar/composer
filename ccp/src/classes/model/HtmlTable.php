<?php
declare(strict_types = 1);
namespace Poker\Ccp\classes\model;
use DateTime;
use Poker\Ccp\classes\utility\DateTimeUtility;
use Poker\Ccp\classes\utility\HtmlUtility;
class HtmlTable extends HtmlBase {
  /*
   * $caption is additional info about table (default is NULL)
   * $class is style class names (default is NULL)
   * $colSpan is array of arrays (array of names, array of columns to colspan, array of array of columns to ignore) (default is NULL)
   * $columnFormat is delimited string of column formats (default is NULL)
   * $delimiter is delimiter (default is ", ")
   * $foreignKeys is delimited string of foreign key queries (default is NULL)
   * $header is whether to display header row or not (default is true)
   * $hiddenAdditional is array of name and index of value to store (default is NULL)
   * $hiddenId is name prefix of field to store row identifier (default is NULL)
   * $hideColIndexes is array of column indexes to hide that are returned from query (default is NULL)
   * $html is array of arrays (array of html to insert, array of column header values, array of result indexes, array of array of status name/button text value, array of result indexes) (default is NULL)
   * $link is array of arrays (array of index, array of values to build link either string literal or query index (page, mode, id, name) (default is NULL)
   * $note is true to display sorting note, false to hide (default is true)
   * $selectedRow is delimited string of selected rows (default is "")
   * $suffix is suffix of table id (default is NULL)
   * $width is width of table (default is 100%)
   */
  // private ?string $caption; // additional info about table
  // private ?array $class; // array of class names
  // private ?array $colspan; // array of arrays (array of names, array of columns to colspan, array of array of columns to ignore)
  // private ?array $columnFormat; // array of column formats
  // private string $delimiter; // delimiter (default is ", ")
  // private ?array $foreignKeys; // array of foreign key queries
  // private bool $header; // boolean to display header row or not
  // private ?array $hiddenAdditional; // array of name and index of value to store
  // private ?string $hiddenId; // name prefix of field to store row identifier
  // private ?array $hideColumnIndexes; // array of column indexes to hide that are returned from query
  // private ?array $html; // array of arrays (array of html to insert, array of column header values, array of result indexes, array of array of status name/button text value, array of result indexes)
  // private ?array $link; // array of arrays (array of index, array of values to build link either string literal or query index (page, mode, id, name)
  // private bool $note; // boolean true to display sorting note, false to hide
  // private ?string $selectedRow; // array of selected rows
  // private ?string $suffix; // suffix of table id
  // private string $width; // width of table
    public function __construct(protected ?string $caption, protected ?array $class, protected ?array $colspan, protected ?array $columnFormat, protected bool $debug, protected string $delimiter, protected ?array $foreignKeys, protected bool $header, protected ?array $hiddenAdditional, protected ?string $hiddenId, protected ?array $hideColumnIndexes, protected ?array $html, protected string|int|NULL $id, protected ?array $link, protected bool $note, protected ?string $selectedRow, protected ?string $suffix, protected string $width) {
        parent::__construct(accessKey: NULL, class: $class, debug: $debug, id: $id, tabIndex: - 1, title: NULL);
    }
    public function getCaption(): ?string {
        return $this->caption;
    }
    public function getColspan(): ?array {
        return $this->colspan;
    }
    public function getColumnFormat(): ?array {
        return $this->columnFormat;
    }
    public function getDelimiter(): string {
        return $this->delimiter;
    }
    public function getForeignKeys(): ?array {
        return $this->foreignKeys;
    }
    public function getHtml(array $results, array $resultHeaders): string {
        $output = "";
        $numRecords = count($results);
        $hasRecords = 0 < $numRecords;
        if ($hasRecords) {
            $firstRow = true;
            $ary = NULL;
            if ("" != $this->selectedRow) {
                $ary = explode(separator: $this->delimiter, string: $this->selectedRow);
            }
            $output .= "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"display" . (NULL !== $this->getClassAsString() ? " " . $this->getClassAsString() : "") . "\" id=\"" .
            Constant::ID_TABLE_DATA . (isset($this->suffix) ? $this->suffix : "") . "\" style=\"width: " . $this->width . ";\">\n";
            if (isset($this->caption)) {
                $output .= "<caption>" . $this->caption . "</caption>";
            }
            $columnCount = count($results[0]);
            if (isset($this->colspan)) {
                $colSpanIgnoreAllIndexes = array();
                foreach ($this->colspan[2] as $ary) {
                    $colSpanIgnoreAllIndexes = array_merge($ary, $colSpanIgnoreAllIndexes);
                }
                $colSpanAllIndexes = array_merge($this->colspan[1], $colSpanIgnoreAllIndexes);
            }
            $linkIdCounter = 0;
            $rowCounter = 0;
            foreach($results as $row) {
                if ($this->header && $firstRow) {
                    $output .= "     <thead>\n";
                    if (isset($this->colspan)) {
                        $output .= "      <tr>\n";
                        $colSpanIndex = 0;
                        for ($index = 0; $index < $columnCount; $index++) {
                            if ((!isset($this->hideColumnIndexes) || (isset($this->hideColumnIndexes) && FALSE === array_search($index, $this->hideColumnIndexes))) && (!isset($colSpanIgnoreAllIndexes) || (isset($colSpanIgnoreAllIndexes) && FALSE === array_search($index, $colSpanIgnoreAllIndexes)))) {
                                $headers = array_keys($resultHeaders);
                                $output .= "       <th colspan=\"";
                                // if not found in col span indexes
                                if (isset($this->colspan[1]) && FALSE === array_search($index, $this->colspan[1])) {
                                  $output .= "1";
                                } else {
                                  $output .= count(value: $this->colspan[2][$colSpanIndex]) + 1;
                                  $colSpanIndex ++;
                                }
                                $output .= "\" rowspan=\"";
                                // if not found in col span indexes
                                if (isset($this->colspan[1]) && FALSE === array_search($index, $this->colspan[1])) {
                                  $output .= "2";
                                } else {
                                  $output .= "1";
                                }
                                $output .= "\">";
                                // if found in col span indexes
                                if (isset($this->colspan[1]) && FALSE !== array_search($index, $this->colspan[1])) {
                                  $output .= $this->colspan[0][array_search($index, $this->colspan[1])];
                                  // if not found in col span indexes
                                } else if (!isset($colSpanAllIndexes) || (isset($colSpanAllIndexes) && FALSE === array_search($index, $colSpanAllIndexes))) {
                                    $headers = array_keys($resultHeaders[$rowCounter]);
                                    $output .= ucwords(string: $headers[$index]);
                                }
                                $output .= "</th>\n";
                            }
                        }
                        $output .= "      </tr>\n";
                    }
                    $output .= "      <tr>\n";
                    for ($index = 0; $index < $columnCount; $index ++) {
                        if (!isset($this->hideColumnIndexes) || (isset($this->hideColumnIndexes) && FALSE === array_search($index, $this->hideColumnIndexes))) {
                            // if found in all col span indexes
                            if (!isset($colSpanAllIndexes) || (isset($colSpanAllIndexes) && FALSE !== array_search($index, $colSpanAllIndexes))) {
    //                             print_r($resultHeaders[$rowCounter]);
                                $headers = array_keys($resultHeaders[$rowCounter]);
                                $output .= "       <th colspan=\"1\" rowspan=\"1\">" . ucwords(string: $headers[$index]) . "</th>\n";
                            }
                        }
                    }
                    if (isset($this->html)) {
                        for ($idx = 0; $idx < count(value: $this->html[1]); $idx++) {
                            $output .= "     <th colspan=\"1\" rowspan=\"1\">" . $this->html[1][$idx] . "</th>\n";
                        }
                    }
                    if (isset($this->hiddenAdditional)) {
                        $output .= "<th></th>\n";
                    }
                    $output .= "      </tr>\n";
                    $output .= "     </thead>\n";
                    $output .= "     <tbody>\n";
                }
                $firstRow = false;
                $output .= "      <tr>\n";
                $linkCounter = 0;
                for ($index = 0; $index < $columnCount; $index++) {
                    if (!isset($this->hideColumnIndexes) || (isset($this->hideColumnIndexes) && FALSE === array_search($index, $this->hideColumnIndexes))) {
                        $output .= "       <td";
                        $formattedFlag = false;
                        $class = array();
                        $temp = $row[$index];
                        if (!$formattedFlag) {
                            if (isset($this->columnFormat)) {
                                $aryFmt2 = array();
                                for ($idx = 0; $idx < count(value: $this->columnFormat); $idx++) {
                                    if ($this->columnFormat[$idx][0] == $index) {
                                        $fmt = $this->columnFormat[$idx][1];
                                        $aryFmt2 = explode(separator: ",", string: $fmt);
                                        $places = $this->columnFormat[$idx][2];
                                        break;
                                    }
                                }
                                if (count($aryFmt2) == 0) {
                                  //$aryFmt = array($aryFmt["native_type"]);
                                    $aryFmt = array(gettype($row[$index]));
                                } else {
                                  $aryFmt = $aryFmt2;
                                }
                            } else {
                                //$aryFmt = array($aryFmt["native_type"]);
                                $aryFmt = array(gettype($row[$index]));
                            }
                            foreach ($aryFmt as $fmt) {
                                switch (strtolower(string: $fmt)) {
                                    case "date":
                                        $dateTime = new DateTime(datetime: $temp);
                                        $temp = DateTimeUtility::formatDisplayDate(value: $dateTime);
                                        break;
                                    case "datetime":
                                        $dateTime = new DateTime(datetime: $temp);
                                        $temp = DateTimeUtility::formatDisplayDateTime(value: $dateTime);
                                        break;
                                    case "time":
                                        $dateTime = new DateTime(datetime: $temp);
                                        $temp = DateTimeUtility::formatDisplayTime(value: $dateTime);
                                        array_push($class, "time");
                                        break;
                                    case "currency":
                                    case "percentage":
                                    case "number":
                                        if ($fmt != "number") {
                                            array_push($class, "number");
                                        }
                                        $prefix = "";
                                        $suffix = "";
                                        if ("currency" == $fmt) {
                                            $prefix = Constant::SYMBOL_CURRENCY_DEFAULT;
                                        } else if ("percentage" == $fmt) {
                                            $suffix = Constant::SYMBOL_PERCENTAGE_DEFAULT;
                                            $temp *= 100;
                                        }
                                        if (isset($temp) && isset($places) && - 1 != $places) {
                                          $temp = number_format(num: (float) $temp, decimals: $places);
                                        }
                                        if ($temp != "") {
                                          $temp = $prefix . $temp . $suffix;
                                        }
                                        break;
                                    case "string":
                                        switch ($temp) {
                                            case Constant::FLAG_YES:
                                                $temp = Constant::TEXT_YES;
                                                break;
                                            case Constant::FLAG_NO:
                                                $temp = Constant::TEXT_NO;
                                                break;
                                        }
                                        break;
                                    case "left":
                                    case "positive":
                                    case "negative":
                                    case "center":
                                    case "right":
                                        array_push($class, $fmt);
                                        break;
                                }
                            }
                            if (!in_array("negative", $class) && ! in_array("positive", $class) && isset($temp)) {
                                if (0 != preg_match('/^\$-+\d+(,\d+)?(.\d+)?|-+\d+(,\d+)?(.\d+)?%$/', (string) $temp)) {
                                    array_push($class, "negative");
                                } else if (0 != preg_match('/^\$\d+(,\d+)?(.\d+)?|\d+(,\d+)?(.\d+)?%$/', (string) $temp)) {
                                    array_push($class, "positive");
                                }
                            }
                        }
                        $output .= ($class == "" || count($class) == 0 ? "" : " class=\"" . implode(" ", $class) . "\"");
                        $output .= ">";
                        if ($headers[$index] == "map" && $temp != "") {
                            $output .= HtmlUtility::buildMapLink(map: $temp);
                        } elseif (isset($this->link) && in_array($index, $this->link[0])) {
                            $counterValues = 0;
                            $paramValues = array();
                            if (isset($this->link[$linkCounter + 1][2][0])) {
                                $paramValues[$counterValues] = $row[$this->link[$linkCounter + 1][2][0]];
                            }
                            $counterValues ++;
                            if (isset($this->link[$linkCounter + 1][2][1])) {
                                $paramValues[$counterValues] = $this->link[$linkCounter + 1][2][1];
                            }
                            if (sizeof($paramValues) == 0) {
                                $paramValues = NULL;
                            }
                            $link = new HtmlLink(accessKey: NULL, class: NULL, debug: $this->isDebug(), href: $this->link[$linkCounter + 1][0], id: isset($this->link[$linkCounter + 1][4]) ? $this->link[$linkCounter + 1][4] . "_" . ($linkIdCounter + 1) : NULL, paramName: isset($this->link[$linkCounter + 1][1]) ? $this->link[$linkCounter + 1][1] : NULL, paramValue: $paramValues, tabIndex: - 1, text: $row[$this->link[$linkCounter + 1][3]], title: NULL);
                            $output .= $link->getHtml();
                            $linkCounter ++;
                            $linkIdCounter ++;
                        } else {
                            $output .= isset($temp) ? htmlentities(string: (string) $temp, flags: ENT_NOQUOTES, encoding: "UTF-8") : "";
                        }
                        $output .= "</td>\n";
                    }
                }
                if (isset($this->html)) {
                    // 0 is html, 1 is headers, 2 is index, 3 is status name/button text, 4 is player index/rebuy count/fee paid/fee paid for tournament
                    for ($idx = 0; $idx < count(value: $this->html[0]); $idx++) {
                        if (! is_string($this->html[2][$idx])) {
                            for ($idx2 = 0; $idx2 < count(value: $this->html[0]); $idx2++) {
                                if ($row[$this->html[2][$idx]] == $this->html[3][$idx2][0]) {
                                    $temp = $this->html[3][$idx2][1];
                                    break;
                                }
                            }
                        }
                        // opposite status since based on buttons (need to change)
                        if ($temp == "Not paid") {
                            $temp = "checked";
                        } else {
                            $temp = "";
                        }
                        $htmlTemp = str_replace(search: "value=\"?1\"", replace: $temp, subject: $this->html[0][$idx]); // button text
                        $htmlTemp = str_replace(search: "?2", replace: (string) $row[$this->html[4][0]], subject: $htmlTemp); // player id
                        $htmlTemp = str_replace(search: "?3", replace: $temp, subject: $htmlTemp); // status name
                        $htmlTemp = str_replace(search: "?4", replace: (string) $row[$this->html[4][1]], subject: $htmlTemp); // rebuy count
                        if (isset($this->html[4][2])) {
                            $htmlTemp = str_replace(search: "?5", replace: (string) $row[$this->html[4][2]], subject: $htmlTemp); // fee paid
                        }
                        if (isset($this->html[4][3])) {
                            $htmlTemp = str_replace(search: "?6", replace: (string) $row[$this->html[4][3]], subject: $htmlTemp); // fee paid for tournament
                        }
                        $output .= "     <td align=\"center\">" . $htmlTemp . "</td>\n";
                    }
                }
                if (isset($this->hiddenId)) {
                    $output .= "<td>\n";
                    $hiddenTemp = new FormControl(debug: $this->isDebug(), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: array("hide"), cols: NULL, disabled: false, id: $this->hiddenId . "_" . $row[0], maxLength: NULL, name: $this->hiddenId . "_" . $row[0], onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: $row[0], wrap: NULL);
                    $output .= $hiddenTemp->getHtml();
                    $output .= "</td>\n";
                }
                if (isset($this->hiddenAdditional)) {
                    for ($index = 0; $index < count(value: $this->hiddenAdditional); $index++) {
                        $output .= "<td>\n";
                        $hiddenTemp = new FormControl(debug: $this->isDebug(), accessKey: NULL, autoComplete: NULL, autoFocus: false, checked: NULL, class: array("hide"), cols: NULL, disabled: false, id: $this->hiddenAdditional[$index][0] . "_" . $row[$this->hiddenAdditional[$index][1]], maxLength: NULL, name: $this->hiddenAdditional[$index][0] . "_" . $row[$this->hiddenAdditional[$index][1]], onClick: NULL, placeholder: NULL, readOnly: false, required: NULL, rows: NULL, size: NULL, suffix: NULL, type: FormControl::TYPE_INPUT_HIDDEN, value: $row[$this->hiddenAdditional[$index][1]], wrap: NULL);
                        $output .= $hiddenTemp->getHtml();
                        $output .= "</td>\n";
                    }
                }
                $output .= "     </tr>\n";
                $rowCounter++;
            }
            $output .= "     </tbody>\n";
            $output .= "    </table>\n";
        } else {
            $output .= "<div class=\"center\">No data found</div>\n";
        }
        return $output;
    }
    public function getHiddenAdditional(): ?array {
        return $this->hiddenAdditional;
    }
    public function getHiddenId(): ?string {
        return $this->hiddenId;
    }
    public function getHideColumnIndexes(): ?array {
        return $this->hideColumnIndexes;
    }
    public function getLink(): ?array {
        return $this->link;
    }
    public function getSelectedRow(): ?string {
        return $this->selectedRow;
    }
    public function getSuffix(): ?string {
        return $this->suffix;
    }
    public function getWidth(): ?string {
        return $this->width;
    }
    public function isHeader(): bool {
        return $this->header;
    }
    public function isNote(): bool {
        return $this->note;
    }
    public function setCaption(string $caption) {
        $this->caption = $caption;
        return $this;
    }
    public function setColspan(array $colspan) {
        $this->colspan = $colspan;
        return $this;
    }
    public function setColumnFormat(array $columnFormat) {
        $this->columnFormat = $columnFormat;
        return $this;
    }
    public function setDelimiter(string $delimiter) {
        $this->delimiter = $delimiter;
        return $this;
    }
    public function setForeignKeys(array $foreignKeys) {
        $this->foreignKeys = $foreignKeys;
        return $this;
    }
    public function setHiddenId(string $hiddenId) {
        $this->hiddenId = $hiddenId;
        return $this;
    }
    public function setHeader(bool $header) {
        $this->header = $header;
        return $this;
    }
    public function setHiddenAdditional(array $hiddenAdditional) {
        $this->hiddenAdditional = $hiddenAdditional;
        return $this;
    }
    public function setHideColumnIndexes(array $hideColumnIndexes) {
        $this->hideColumnIndexes = $hideColumnIndexes;
        return $this;
    }
    public function setHtml(array $html) {
        $this->html = $html;
        return $this;
    }
    public function setLink(array $link) {
        $this->link = $link;
        return $this;
    }
    public function setNote(bool $note) {
        $this->note = $note;
        return $this;
    }
    public function setSelectedRow(array $selectedRow) {
        $this->selectedRow = $selectedRow;
        return $this;
    }
    public function setSuffix(string $suffix) {
        $this->suffix = $suffix;
        return $this;
    }
    public function setWidth(string $width) {
        $this->width = $width;
        return $this;
    }
    public function __toString(): string {
        $output = parent::__toString();
        $output .= ", caption = '";
        $output .= $this->caption;
        $output .= "', colspan = ";
        $output .= print_r(value: $this->colspan, return: true);
        $output .= ", columnFormat = ";
        $output .= print_r(value: $this->columnFormat, return: true);
        $output .= ", delimiter = '";
        $output .= $this->delimiter;
        $output .= "', foreignKeys = ";
        $output .= print_r(value: $this->foreignKeys, return: true);
        $output .= ", header = "; // boolean
        $output .= var_export(value: $this->header, return: true);
        $output .= ", hiddenAdditional = ";
        $output .= print_r(value: $this->hiddenAdditional, return: true);
        $output .= ", hiddenId = '"; // not array
        $output .= $this->hiddenId;
        $output .= "', hideColumnIndexes = ";
        $output .= print_r(value: $this->hideColumnIndexes, return: true);
        $output .= ", html = ";
        $output .= print_r(value: $this->html, return: true);
        $output .= ", link = ";
        $output .= print_r(value: $this->link, return: true);
        $output .= ", note = ";
        $output .= var_export(value: $this->note, return: true);
        $output .= "', selectedRow = ";
        $output .= print_r(value: $this->selectedRow, return: true);
        $output .= ", suffix = '";
        $output .= $this->suffix;
        $output .= "', width = '";
        $output .= $this->width;
        $output .= "'";
        return $output;
    }
}
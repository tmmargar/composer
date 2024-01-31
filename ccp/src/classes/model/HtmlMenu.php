<?php
declare(strict_types = 1);
namespace Poker\Ccp\classes\model;
use Poker\Ccp\classes\utility\DateTimeUtility;
use Poker\Ccp\classes\utility\SessionUtility;
class HtmlMenu extends HtmlBase {
    public function __construct(protected bool $debug, protected string|int|NULL $id, protected ?array $items, protected ?string $text) {
        parent::__construct(accessKey: NULL, class: NULL, debug: $debug, id: $id, tabIndex: 0, title: NULL);
    }
    public function getHtmlRoot(): string {
        $output = str_repeat(string: " ", times: 1) . "<nav id=\"main-nav\">\n";
        $output .= str_repeat(string: " ", times: 2) . "<input type=\"checkbox\" id=\"menu\" name=\"menu\" class=\"m-menu__checkbox\">\n";
        $output .= str_repeat(string: " ", times: 2) . "<label class=\"m-menu__toggle\" for=\"menu\">\n";
        $output .= str_repeat(string: " ", times: 3) . "<svg width=\"35\" height=\"20\" viewBox=\"0 0 20 20\" fill=\"none\" stroke=\"#fff\" stroke-width=\"2\" stroke-linecap=\"butt\" stroke-linejoin=\"arcs\">\n";
        $output .= str_repeat(string: " ", times: 4) . "<line x1=\"3\" y1=\"6\" x2=\"21\" y2=\"6\"></line>\n";
        $output .= str_repeat(string: " ", times: 4) . "<line x1=\"3\" y1=\"12\" x2=\"21\" y2=\"12\"></line>\n";
        $output .= str_repeat(string: " ", times: 4) . "<line x1=\"3\" y1=\"18\" x2=\"21\" y2=\"18\"></line>\n";
        $output .= str_repeat(string: " ", times: 3) . "</svg>\n";
        $output .= str_repeat(string: " ", times: 2) . "</label>\n";
        $output .= str_repeat(string: " ", times: 2) . "<label class=\"m-menu__overlay\" for=\"menu\"></label>\n";
        $output .= str_repeat(string: " ", times: 2) . "<div class=\"m-menu\">\n";
        $output .= str_repeat(string: " ", times: 3) . "<div class=\"m-menu__header\">\n";
        $output .= str_repeat(string: " ", times: 4) . "<label class=\"m-menu__toggle\" for=\"menu\">\n";
        $output .= str_repeat(string: " ", times: 5) . "<svg width=\"35\" height=\"20\" viewBox=\"0 0 20 20\" fill=\"none\" stroke=\"#000000\" stroke-width=\"2\" stroke-linecap=\"butt\" stroke-linejoin=\"arcs\">\n";
        $output .= str_repeat(string: " ", times: 6) . "<line x1=\"18\" y1=\"6\" x2=\"6\" y2=\"18\"></line>\n";
        $output .= str_repeat(string: " ", times: 6) . "<line x1=\"6\" y1=\"6\" x2=\"18\" y2=\"18\"></line>\n";
        $output .= str_repeat(string: " ", times: 5) . "</svg>\n";
        $output .= str_repeat(string: " ", times: 4) . "</label>\n";
        $output .= str_repeat(string: " ", times: 4) . "<span>MENU</span>\n";
        $output .= str_repeat(string: " ", times: 3) . "</div>\n";
        $output .= str_repeat(string: " ", times: 3) . "<ul>\n";
        $output .= $this->getHtml(parent: true, counter: 3);
        $output .= str_repeat(string: " ", times: 3) . "</ul>\n";
        $output .= str_repeat(string: " ", times: 2) . "</div>\n";
        $output .= str_repeat(string: " ", times: 2) . "<span style=\"color: white; font-size: 1em;\">\n";
        if ("" == SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_START_DATE)) {
            $output .= "None";
        } else {
            $output .= DateTimeUtility::formatDisplayDate(value: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_START_DATE));
            $output .= "&nbsp;-&nbsp;";
            $output .= DateTimeUtility::formatDisplayDate(value: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_END_DATE));
            $output .= "&nbsp;(";
            $output .= SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_CHAMPIONSHIP_QUALIFY) . " to qualify)";
        }
        $output .= str_repeat(string: " ", times: 2) . "</span>\n";
        $output .= str_repeat(string: " ", times: 1) . "</nav>\n";
        return $output;
    }
    public function getHtml(bool $parent, int $counter): string {
        $output = "";
        foreach ($this->items as $item) { // $menu is HtmlLink or HtmlMenu object
            if (get_class(object: $item) == "Poker\Ccp\classes\model\HtmlLink") {
                $output .= str_repeat(string: " ", times: $counter + 1) . "<li><span>" . $item->getHtml() . "</span></li>\n";
            } else {
                $output .= str_repeat(string: " ", times: $counter + 1) . "<li>\n";
                $output .= str_repeat(string: " ", times: $counter + 2) . "<label class=\"a-label__chevron\" for=\"item-" . $item->getText() . "\">" . $item->getText() . "</label>\n";
                $output .= str_repeat(string: " ", times: $counter + 2) . "<input type=\"checkbox\" id=\"item-" . $item->getText() . "\" name=\"item-" . $item->getText() . "\" class=\"m-menu__checkbox\">\n";
                $output .= str_repeat(string: " ", times: $counter + 2) . "<div class=\"m-menu\">\n";
                $output .= str_repeat(string: " ", times: $counter + 3) . "<div class=\"m-menu__header\">\n";
                $output .= str_repeat(string: " ", times: $counter + 4) . "<label class=\"m-menu__toggle\" for=\"item-" . $item->getText() . "\">\n";
                $output .= str_repeat(string: " ", times: $counter + 5) . "<svg width=\"35\" height=\"20\" viewBox=\"0 0 15 22\" fill=\"none\" stroke=\"#000000\" stroke-width=\"2\" stroke-linecap=\"butt\" stroke-linejoin=\"arcs\">\n";
                $output .= str_repeat(string: " ", times: $counter + 6) . "<path d=\"M19 12H6M12 5l-7 7 7 7\"/>\n";
                $output .= str_repeat(string: " ", times: $counter + 5) . "</svg>\n";
                $output .= str_repeat(string: " ", times: $counter + 4) . "</label>\n";
                $output .= str_repeat(string: " ", times: $counter + 4) . "<span>" . $item->getText() . "</span>\n";
                $output .= str_repeat(string: " ", times: $counter + 3) . "</div>\n";
                $output .= str_repeat(string: " ", times: $counter + 3) . "<ul>\n";
                $output .= $item->getHtml(parent: false, counter: $counter + 3);
                $output .= str_repeat(string: " ", times: $counter + 3) . "</ul>\n";
                $output .= str_repeat(string: " ", times: $counter + 2) . "</div>\n";
                $output .= str_repeat(string: " ", times: $counter + 1) . "</li>\n";
            }
        }
        return $output;
    }
    public function getItems(): ?array {
        return $this->items;
    }
    public function getText(): ?string {
        return $this->text;
    }
    public function setItems(array $items) {
        $this->items = $items;
        return $this;
    }
    public function setText(string $text) {
        $this->text = $text;
        return $this;
    }
    public function __toString(): string {
        $output = parent::__toString();
        $output .= ", items = ";
        $output .= print_r(value: $this->items, return: true);
        $output .= ", text = '";
        $output .= $this->text;
        $output .= "'";
        return $output;
    }
}
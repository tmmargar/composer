<?php
namespace Poker\Ccp\Utility;
use Poker\Ccp\Model\Constant;
use Poker\Ccp\Model\HtmlLink;
use Poker\Ccp\Model\Location;
use Poker\Ccp\Model\Player;
use Poker\Ccp\Model\Result;
use Poker\Ccp\Model\Tournament;
abstract class HtmlUtility {
  public static function buildClasses(array $aryClasses, string|int|float $value): string {
    $class = "";
    for ($idx = 0; $idx < count(value: $aryClasses); $idx ++) {
      if ($class != "") {
        $class .= " ";
      }
      switch ($aryClasses[$idx]) {
        case "currency":
        case "percentage":
        case "number":
          $class .= "number";
          if ($aryClasses[$idx] == "currency" && isset($value)) {
            if (0 > $value) {
              $class .= " negative";
            } else if (0 < $value) {
              $class .= " positive";
            }
          }
          break;
        default:
          $class .= $aryClasses[$idx];
          break;
      }
    }
    return $class;
  }
  // $format is array of formats (index, type and places)
  // $value is value to format
  // returns formatted value
  // TODO: move to FormBase once change class to array
  public static function formatData(array $format, string|int|float $value): string {
    if (isset($format)) {
      $temp = "";
      switch ($format[1]) {
        case "date":
          $temp = $value->getDisplayFormat();
          break;
        case "time":
          $temp .= $value->getDisplayAmPmFormat();
          break;
        case "currency":
        case "percentage":
        case "number":
          $prefix = "";
          $suffix = "";
          if ("currency" == $format[1]) {
            $prefix = Constant::SYMBOL_CURRENCY_DEFAULT;
          } else if ("percentage" == $format[1]) {
            $suffix = Constant::SYMBOL_PERCENTAGE_DEFAULT;
            $temp .= $value * 100;
          }
          if (- 1 != $format[2]) {
            $temp .= number_format(num: $value, decimals: $format[2]);
          }
          if ($temp != "") {
            $temp = $prefix . $temp . $suffix;
          }
          break;
      }
    } else {
      $temp = $value;
    }
    return $temp;
  }

  public static function getLink(bool $debug, string|int $id, string $text, string $class): string {
//       href: "reports.php", id: NULL, paramName: array("reportId","tournamentId"), paramValue: array("results",$tournaments->getId()), tabIndex: - 1, text: $description . " (" . $tournaments->getDate()->getDisplayFormat() . ")", title: NULL);
      switch ($class) {
          case Location::class:
              $url = "manageLocation.php";
              $paramNames = array("playerId", "mode");
              $paramValues = array($id . "modify");
          case Player::class:
              $url = "managePlayer.php";
              $paramNames = array("id", "mode");
              $paramValues = array($id . "modify");
              break;
          case "Reports":
              $url = "reports.php";
              $paramNames = array("reportId", "tournamentId");
              $paramValues = array("results", $id);
              break;
          case Result::class:
              $url =  "manageResult.php";
              $paramNames = array("id", "mode");
              $paramValues = array($id . "modify");
          case Tournament::class:
              $url = "manageTournament.php";
              $paramNames = array("id", "mode");
              $paramValues = array($id . "modify");
      }
      $link = new HtmlLink(accessKey: NULL, class: NULL, debug: $debug, href: $url, id: NULL, paramName: $paramNames, paramValue: $paramValues, tabIndex: -1, text: $text, title: NULL);
      return $link->getHtml();
  }

  public static function buildMapLink(string $map): string {
      return "<a href =\"" . Constant::PATH_MAP() . "/" . $map . "\">View</a>\n";
  }
}
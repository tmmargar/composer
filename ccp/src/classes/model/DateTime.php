<?php
declare(strict_types = 1);
namespace Poker\Ccp\classes\model;
use DateTimeZone;
class DateTime extends Base {
  public const YEAR_FIRST_SEASON = 2005;
  private const DATE_FORMAT_YEAR = "Y";
  private const DATE_FORMAT_DATABASE_DEFAULT = "Y-m-d";
  private const DATE_FORMAT_DATABASE_DATE_TIME_DEFAULT = "Y-m-d H:i:s";
  private const DATE_FORMAT_DISPLAY_DEFAULT = "m/d/Y";
  private const DATE_FORMAT_DISPLAY_LONG = "D, M j, Y";
  private const DATE_FORMAT_DISPLAY_REGISTRATION_NOT_OPEN = "M d";
  private const DATE_FORMAT_PICKER_TIME_DISPLAY_DEFAULT = "Y-m-d\TH:i";
  private const DATE_FORMAT_TIME_DISPLAY_DEFAULT = "M d, Y h:i A";
  private const TIME_FORMAT_DATABASE_DEFAULT = "H:i:s";
  private const TIME_FORMAT_DISPLAY_AMPM = "h:i A";
  private const TIME_FORMAT_NOW = "Ymd H:i:s";
  private \DateTime|NULL $time;
  private DateTimeZone $timeZone;
  public function __construct(protected bool $debug, protected string|int|NULL $id, string|NULL $time) {
    parent::__construct(debug: $debug, id: $id);
    $this->timeZone = new DateTimeZone(timezone: date_default_timezone_get());
    if ("now" == $time) {
      $temp = new \DateTime();
      $time = $temp->format(format: self::TIME_FORMAT_NOW);
    }
    $this->time = NULL == $time ? NULL : new \DateTime($time, $this->timeZone);
  }
  public function getDatabaseFormat(): string|NULL {
    return NULL == $this->time ? NULL : $this->time->format(format: self::DATE_FORMAT_DATABASE_DEFAULT);
  }
  public function getDatabaseDateTimeFormat(): string|NULL {
    return NULL == $this->time ? NULL : $this->time->format(format: self::DATE_FORMAT_DATABASE_DATE_TIME_DEFAULT);
  }
  public function getDatabaseTimeFormat(): string|NULL {
    return NULL == $this->time ? NULL : $this->time->format(format: self::TIME_FORMAT_DATABASE_DEFAULT);
  }
  public function getDisplayAmPmFormat(): string|NULL {
    return NULL == $this->time ? NULL : $this->time->format(format: self::TIME_FORMAT_DISPLAY_AMPM);
  }
  public function getDisplayDateTimePickerFormat(): string |NULL{
    return NULL == $this->time ? NULL : $this->time->format(format: self::DATE_FORMAT_PICKER_TIME_DISPLAY_DEFAULT);
  }
  public function getDisplayFormat(): string|NULL {
    return NULL == $this->time ? NULL : $this->time->format(format: self::DATE_FORMAT_DISPLAY_DEFAULT);
  }
  public function getDisplayLongFormat(): string|NULL {
    return NULL == $this->time ? NULL : $this->time->format(format: self::DATE_FORMAT_DISPLAY_LONG);
  }
  public function getDisplayLongTimeFormat(): string|NULL {
    return NULL == $this->time ? NULL : $this->time->format(format: self::DATE_FORMAT_DISPLAY_LONG . " " . self::TIME_FORMAT_DISPLAY_AMPM);
  }
  public function getDisplayRegistrationNotOpenFormat(): string |NULL{
    return NULL == $this->time ? NULL : $this->time->format(format: self::DATE_FORMAT_DISPLAY_REGISTRATION_NOT_OPEN);
  }
  public function getDisplayTimeFormat(): string|NULL {
    return NULL == $this->time ? NULL : $this->time->format(format: self::DATE_FORMAT_TIME_DISPLAY_DEFAULT);
  }
  public function getTime(): \DateTime {
    return $this->time;
  }
  public function getTimeZone(): DateTimeZone {
    return $this->timeZone;
  }
  public function getYearFormat(): string|NULL {
    return NULL == $this->time ? NULL : $this->time->format(format: self::DATE_FORMAT_YEAR);
  }
  public function setTime(int $time) {
    $this->time = $time;
    return $this;
  }
  public function setTimeZone(DateTimeZone $timeZone) {
    $this->timeZone = $timeZone;
    return $this;
  }
  public function __toString(): string {
    $output = parent::__toString();
    $output .= ", time = ";
    $output .= NULL == $this->time ? NULL : $this->time->format(format: self::DATE_FORMAT_PICKER_TIME_DISPLAY_DEFAULT);
    $output .= ", timeZone = ";
    $output .= NULL == $this->timeZone ? NULL : $this->timeZone->getName();
    return $output;
  }
}
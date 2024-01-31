<?php
namespace Poker\Ccp\classes\utility;
use DateTime;
abstract class DateTimeUtility {
    private const DATE_FORMAT_DATABASE_DATE = "Y-m-d";
    private const DATE_FORMAT_DATABASE_DATE_TIME = "Y-m-d h:i:s";
    private const DATE_FORMAT_DATABASE_TIME = "H:i";
    private const DATE_FORMAT_DISPLAY_DATE = "m/d/Y";
    private const DATE_FORMAT_DISPLAY_DATE_TIME = "m/d/Y h:i:s A";
    private const DATE_FORMAT_DISPLAY_DATE_TIME_MILLISECONDS = "m/d/Y h:i:s v";
    private const DATE_FORMAT_DISPLAY_REGISTRATION_NOT_OPEN = "M d";
    private const DATE_FORMAT_DISPLAY_TIME = "h:i:s A";
    private const DATE_FORMAT_SECONDS_SINCE_EPOCH = "U";
    private const DATE_FORMAT_DISPLAY_LONG = "D, M j, Y h:i A";
    private const DATE_FORMAT_PICKER_DATE_TIME = "Y-m-d\TH:i";
    private const DATE_FORMAT_PICKER_TIME = "\TH:i";
    private const DATE_FORMAT_YEAR = "Y";
    public static function formatDatabaseDate(DateTime $value) {
        return $value->format(DateTimeUtility::DATE_FORMAT_DATABASE_DATE);
    }
    public static function formatDatabaseDateTime(DateTime $value) {
        return $value->format(DateTimeUtility::DATE_FORMAT_DATABASE_DATE_TIME);
    }
    public static function formatDatabaseTime(DateTime $value) {
        return $value->format(DateTimeUtility::DATE_FORMAT_DATABASE_TIME);
    }
    public static function formatDisplayDate(DateTime $value) {
        return $value->format(DateTimeUtility::DATE_FORMAT_DISPLAY_DATE);
    }
    public static function formatDisplayDateTime(DateTime $value) {
        return $value->format(DateTimeUtility::DATE_FORMAT_DISPLAY_DATE_TIME);
    }
    public static function formatDisplayDateTimeMilliseconds(DateTime $value) {
        return $value->format(DateTimeUtility::DATE_FORMAT_DISPLAY_DATE_TIME_MILLISECONDS);
    }
    public static function formatDisplayLong(DateTime $value) {
        return $value->format(DateTimeUtility::DATE_FORMAT_DISPLAY_LONG);
    }
    public static function formatDisplayPickerDateTime(DateTime $value) {
        return $value->format(DateTimeUtility::DATE_FORMAT_PICKER_DATE_TIME);
    }
    public static function formatDisplayPickerTime(DateTime $value) {
        return $value->format(DateTimeUtility::DATE_FORMAT_PICKER_TIME);
    }
    public static function formatDisplayRegistrationNotOpen(DateTime $value) {
        return $value->format(DateTimeUtility::DATE_FORMAT_DISPLAY_REGISTRATION_NOT_OPEN);
    }
    public static function formatDisplayTime(DateTime $value) {
        return $value->format(DateTimeUtility::DATE_FORMAT_DISPLAY_TIME);
    }
    public static function formatSecondsSinceEpoch(DateTime $value) {
        return $value->format(DateTimeUtility::DATE_FORMAT_SECONDS_SINCE_EPOCH);
    }
    public static function formatYear(DateTime $value) {
        return $value->format(DateTimeUtility::DATE_FORMAT_YEAR);
    }
}
<?php
namespace Poker\Ccp\classes\utility;
use Poker\Ccp\classes\model\Constant;
use Poker\Ccp\classes\model\DateTime;
use Poker\Ccp\classes\model\Season;
use Exception;
abstract class SessionUtility {
  public const OBJECT_NAME_ADMINISTRATOR = "administrator";
  public const OBJECT_NAME_DEBUG = "debug";
  public const OBJECT_NAME_ID = "id";
  public const OBJECT_NAME_NAME = "name";
  public const OBJECT_NAME_USERID = "userid";
  public const OBJECT_NAME_USERNAME = "username";
  public const OBJECT_NAME_SECURITY = "securityObject";
  public const OBJECT_NAME_SEASON = "seasonObject";
  public const OBJECT_NAME_START_DATE = "startDate";
  public const OBJECT_NAME_END_DATE = "endDate";
  public const OBJECT_NAME_CHAMPIONSHIP_QUALIFY = "championshipQualify";
  public const OBJECT_NAME_FEE = "fee";
  public static function destroy() {
    self::startSession();
    $_SESSION = array();
    session_destroy();
  }
  public static function destroyAllSessions() {
    $files = glob(pattern: session_save_path() . '/*'); // get all file names
    foreach ($files as $file) {
      if (is_file(filename: $file)) {
        unlink(filename: $file);
      }
    }
  }
  public static function existsSeason(): bool {
    return ! empty($_SESSION[self::OBJECT_NAME_SEASON]);
  }
  public static function existsSecurity(): bool {
    return ! empty($_SESSION[self::OBJECT_NAME_SECURITY]);
  }
  public static function getValue(string $name): string|int|bool|DateTime {
    $value = $name == self::OBJECT_NAME_DEBUG ? false : "";
    if (self::existsSecurity()) {
      $security = unserialize(data: $_SESSION[self::OBJECT_NAME_SECURITY]);
      switch ($name) {
        case self::OBJECT_NAME_ADMINISTRATOR:
          $value = $security->getUser()->getAdministrator();
          break;
        case self::OBJECT_NAME_DEBUG:
          $value = false; // $security->isDebug();
          break;
        case self::OBJECT_NAME_NAME:
          $value = $security->getUser()->getName();
          break;
        case self::OBJECT_NAME_USERID:
          $value = $security->getUser()->getId();
          break;
        case self::OBJECT_NAME_USERNAME:
          $value = $security->getUser()->getUsername();
          break;
      }
    }
    if (self::existsSeason()) {
      $season = unserialize(data: $_SESSION[self::OBJECT_NAME_SEASON]);
      switch ($name) {
        case self::OBJECT_NAME_ID:
          $value = $season->getId();
          break;
        case self::OBJECT_NAME_START_DATE:
          $value = $season->getStartDate();
          break;
        case self::OBJECT_NAME_END_DATE:
          $value = $season->getEndDate();
          break;
        case self::OBJECT_NAME_CHAMPIONSHIP_QUALIFY:
          $value = $season->getChampionshipQualify();
          break;
        case self::OBJECT_NAME_FEE:
          $value = $season->getFee();
          break;
      }
    }
    return $value;
  }
  public static function print(): string|bool {
    return print_r(value: $_SESSION, return: true);
  }
  public static function regenerateAllSessions(Season $seasonNew) {
    $sessionCurrentId = session_id(); // get current session id
    $ctr = - 1;
    $files = glob(pattern: session_save_path() . "/*"); // get all session files
    foreach ($files as $file) {
      $ctr ++;
      // echo "<br>file is " . $file;
      // if (is_file($file) && ("sessions/sess_" . $sessionCurrentId) != $file) { // if file and not current session
      if (is_file(filename: $file)) {
        // echo "<BR>backing up current session " . $sessionCurrentId;
        $temp = array();
        $temp['sessionId'] = $sessionCurrentId;
        foreach ($_SESSION as $key => $val) {
          $temp[$key] = $val;
        }
        session_write_close();
        // echo "<BR>updating other session " . $file;
        $fileAry = explode(separator: "_", string: $file);
        session_id(id: $fileAry[1]);
        session_start();
        // update session here
        // $_SESSION[self::OBJECT_NAME_SEASON] = serialize($seasonNew);
        self::setValue(name: self::OBJECT_NAME_SEASON, value: $seasonNew);
        session_write_close();
        // echo "<BR>restoring current session " . $temp['sessionId'];
        session_id(id: $temp['sessionId']); // restart local sesh
        session_start();
        foreach ($temp as $key => $val) {
          $_SESSION[$key] = $val;
        }
        // echo "<BR>restoring current session season " . $seasonNew;
        // update session here
        // $_SESSION[self::OBJECT_NAME_SEASON] = serialize($seasonNew);
        self::setValue(name: self::OBJECT_NAME_SEASON, value: $seasonNew);
      }
    }
  }
  public static function startSession() {
    if (Constant::PATH_SESSION() != session_save_path()) {
      session_save_path(path: Constant::PATH_SESSION());
    }
    session_start();
    // session_regenerate_id(true);
  }
  public static function setValue(string $name, mixed $value) {
    $_SESSION[$name] = serialize(value: $value);
  }
  public static function unserialize($session_data): array {
    $method = ini_get(option: "session.serialize_handler");
    switch ($method) {
      case "php":
        return self::unserialize_php(data: $session_data);
        break;
      case "php_binary":
        return self::unserialize_phpbinary(data: $session_data);
        break;
      default:
        throw new Exception(message: "Unsupported session.serialize_handler: " . $method . ". Supported: php, php_binary");
    }
  }
  private static function unserialize_php($session_data): array {
    $return_data = array();
    $offset = 0;
    while ($offset < strlen(string: $session_data)) {
      if (! strstr(haystack: substr(string: $session_data, offset: $offset), needle: "|")) {
        throw new Exception(message: "invalid data, remaining: " . substr($session_data, $offset));
      }
      $pos = strpos(haystack: $session_data, needle: "|", offset: $offset);
      $num = $pos - $offset;
      $varname = substr(string: $session_data, offset: $offset, length: $num);
      $offset += $num + 1;
      $data = unserialize(substr(string: $session_data, offset: $offset));
      $return_data[$varname] = $data;
      $offset += strlen(string: serialize(value: $data));
    }
    return $return_data;
  }
  private static function unserialize_phpbinary($session_data): array {
    $return_data = array();
    $offset = 0;
    while ($offset < strlen(string: $session_data)) {
      $num = ord(character: $session_data[$offset]);
      $offset += 1;
      $varname = substr(string: $session_data, offset: $offset, length: $num);
      $offset += $num;
      $data = unserialize(data: substr(string: $session_data, offset: $offset));
      $return_data[$varname] = $data;
      $offset += strlen(serialize(value: $data));
    }
    return $return_data;
  }
}
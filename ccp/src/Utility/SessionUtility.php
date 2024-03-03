<?php
namespace Poker\Ccp\Utility;
use Poker\Ccp\Model\Constant;
use Poker\Ccp\Model\Season;
use DateTime;
abstract class SessionUtility {
    public const OBJECT_NAME_ADMINISTRATOR = "administrator";
    public const OBJECT_NAME_DEBUG = "debug";
    public const OBJECT_NAME_ID = "id";
    public const OBJECT_NAME_NAME = "name";
    public const OBJECT_NAME_PLAYERID = "userid";
    public const OBJECT_NAME_PLAYERNAME = "username";
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
        return !empty($_SESSION[self::OBJECT_NAME_SEASON]);
    }
    public static function existsSecurity(): bool {
        return !empty($_SESSION[self::OBJECT_NAME_SECURITY]);
    }
    public static function getValue(string $name): string|int|bool|DateTime {
        $value = $name == self::OBJECT_NAME_DEBUG ? false : "";
        if (self::existsSecurity()) {
            $security = unserialize(data: $_SESSION[self::OBJECT_NAME_SECURITY]);
            switch ($name) {
                case self::OBJECT_NAME_ADMINISTRATOR:
                    $value = $security->getPlayer()->getAdministrator();
                    break;
                case self::OBJECT_NAME_DEBUG:
                    $value = false; // $security->isDebug();
                    break;
                case self::OBJECT_NAME_NAME:
                    $value = $security->getPlayer()->getName();
                    break;
                case self::OBJECT_NAME_PLAYERID:
                    $value = $security->getPlayer()->getId();
                    break;
                case self::OBJECT_NAME_PLAYERNAME:
                    $value = $security->getPlayer()->getUsername();
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
        $ctr = -1;
        $files = glob(pattern: session_save_path() . "/*"); // get all session files
        foreach ($files as $file) {
            $ctr++;
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
}
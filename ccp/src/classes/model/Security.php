<?php
declare(strict_types = 1);
namespace Poker\Ccp\classes\model;
use Poker\Ccp\classes\utility\SessionUtility;
class Security extends Base {
  private Season $season;
  public function __construct(protected bool $debug, protected string|int|NULL $id, protected Login $login, protected User $user) {
    parent::__construct(debug: $debug, id: $id);
  }
  public function getLogin(): Login {
    return $this->login;
  }
  public function getSeason(): Season {
    return $this->season;
  }
  public function getUser(): User {
    return $this->user;
  }
  public function login(): bool {
    if ($this->validatePassword()) {
      $this->loginSuccess();
      return true;
    } else {
      return false;
    }
  }
  private function loginSuccess() {
    $databaseResult = new DatabaseResult(debug: $this->isDebug());
    $params = array($this->login->getUsername());
    $resultList = $databaseResult->getUserByUsername(params: $params);
    if (0 < count(value: $resultList)) {
      $this->setUser(user: $resultList[0]);
      SessionUtility::setValue(name: SessionUtility::OBJECT_NAME_SECURITY, value: $this);
    }
    $params = array(Constant::FLAG_YES_DATABASE);
    $resultList = $databaseResult->getSeasonByActive(params: $params);
    if (0 < count(value: $resultList)) {
      $this->setSeason(season: $resultList[0]);
      SessionUtility::setValue(name: SessionUtility::OBJECT_NAME_SEASON, value: $resultList[0]);
    }
  }
  /*
   * public static function rememberMe($userName, $password, $rememberMe, $url) {
   * $current_time = time();
   * $current_date = date("Y-m-d H:i:s", $current_time);
   * // Set Cookie expiration for 1 month
   * // $cookie_expiration_time = $current_time + (30 * 24 * 60 * 60); // for 1 month
   * // $isLoggedIn = false;
   * // Check if loggedin session and redirect if session exists
   * echo "<Br>session user id -> " . $_SESSION["userid"];
   * echo "<Br>cookie user id -> " . $_COOKIE["remember_username"];
   * echo "<Br>cookie selector -> " . $_COOKIE["remember_selector"];
   * echo "<Br>cookie token -> " . $_COOKIE["remember_token"];
   * if (! empty(self::getValue("userid"))) {
   * self::redirect();
   * } else if (! empty($_COOKIE["remember_username"]) && ! empty($_COOKIE["remember_selector"]) && ! empty($_COOKIE["remember_token"])) { // Check if loggedin session exists
   * // Initiate auth token verification directive to false
   * $isPasswordVerified = false;
   * $isSelectorVerified = false;
   * $isExpiryDateVerified = false;
   * // Get token for username
   * // $userToken = $auth->getTokenByUsername($_COOKIE["remember_username"], 0);
   * $databaseResult = new DatabaseResult();
   * $databaseResult->setDebug(SessionUtility::getValue(SessionUtility::OBJECT_NAME_DEBUG));
   * $params = array(
   * $_COOKIE["remember_username"]
   * );
   * $resultList = $databaseResult->getUserByUsername($params);
   * if (0 < count($resultList)) {
   * // Validate random password cookie with database
   * if (password_verify($_COOKIE["remember_selector"], $resultList[0]->getRememberSelector())) {
   * $isPasswordVerified = true;
   * }
   * echo "<br> token -> " . $_COOKIE["remember_token"] . " == " . $resultList[0]->getRememberToken();
   * // Validate random selector cookie with database
   * if (password_verify($_COOKIE["remember_token"], $resultList[0]->getRememberToken())) {
   * $isSelectorVerified = true;
   * }
   * echo "<br> date -> " . $resultList[0]->getRememberExpires() . " >= " . $current_date;
   * // check cookie expiration by date
   * if ($resultList[0]->getRememberExpires() >= $current_date) {
   * $isExpiryDateVerified = true;
   * }
   * // Redirect if all cookie based validation returns true
   * // Else, mark the token as expired and clear cookies
   * if (! empty($resultList[0]->getId()) && $isPasswordVerified && $isSelectorVerified && $isExpiryDateVerified) {
   * self::redirect();
   * } else {
   * if (! empty($resultList[0]->getId())) {
   * // $auth->markAsExpired($resultList[0]->getId());
   * }
   * // clear cookies
   * // $util->clearAuthCookie();
   * }
   * }
   * } else {
   * self::login($userName, $password, $rememberMe, $url);
   * }
   * }
   */
  public function setLogin($login) {
    $this->login = $login;
  }
  public function setSeason(Season $season) {
    $this->season = $season;
  }
  public function setUser(User $user) {
    $this->user = $user;
  }
  public function __toString(): string {
    $output = parent::__toString();
    $output .= ", login = [" . $this->login;
    $output .= "], user = [" . $this->user . "]";
    return $output;
  }
  private function validatePassword(): bool {
    $found = false;
    $databaseResult = new DatabaseResult(debug: $this->isDebug());
    $resultList = $databaseResult->getLogin(userName: $this->login->getUsername());
    if (0 < count(value: $resultList)) {
      if (password_verify(password: $this->login->getPassword(), hash: $resultList[0])) {
        $found = true;
      }
    }
    return $found;
  }
}
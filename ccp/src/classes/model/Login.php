<?php
declare(strict_types = 1);
namespace Poker\Ccp\classes\model;
class Login extends Base {
  public function __construct(protected bool $debug, protected string|int|NULL $id, protected string $username, protected string $password) {
    parent::__construct(debug: $debug, id: $id);
  }
  public function getPassword(): string {
    return $this->password;
  }
  public function getUsername(): string {
    return $this->username;
  }
  public function setPassword($password) {
    $this->password = $password;
    return $this;
  }
  public function setUsername($username) {
    $this->username = $username;
    return $this;
  }
  public function __toString(): string {
    $output = parent::__toString();
    $output .= ", username = '" . $this->username;
    $output .= "', password = " . $this->password;
    return $output;
  }
}
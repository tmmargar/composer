<?php
declare(strict_types = 1);
namespace Poker\Ccp\classes\model;
class Database extends Root {
  public function __construct(protected bool $debug, protected string $hostName, protected string $userid, protected string $password, protected string $databaseName, protected int $port): Database {
    parent::__construct(debug: $debug);
    return $this;
  }
  public function getDsn(): string {
    return 'mysql:dbname=' . $this->getDatabaseName() . ';host=' . $this->getHostName() . ';port=' . $this->port;
  }
  public function getHostName(): string {
    return $this->hostName;
  }
  public function getUserid(): string {
    return $this->userid;
  }
  public function getPassword(): string {
    return $this->password;
  }
  public function getDatabaseName(): string {
    return $this->databaseName;
  }
  public function getPort(): int {
    return $this->port;
  }
  public function setHostName(string $hostName): Database {
    $this->hostName = $hostName;
    return $this;
  }
  public function setUserid(string $userid): Database {
    $this->userid = $userid;
    return $this;
  }
  public function setPassword(string $password): Database {
    $this->password = $password;
    return $this;
  }
  public function setDatabaseName(string $databaseName): Database {
    $this->databaseName = $databaseName;
    return $this;
  }
  public function setPort(int $port): Database {
    $this->port = $port;
    return $this;
  }
  public function __toString(): string {
    $output = parent::__toString();
    $output .= ", hostName = '";
    $output .= $this->getHostName();
    $output .= "', userid = '";
    $output .= $this->getUserid();
    $output .= "', password = '";
    $output .= $this->getPassword();
    $output .= "', databaseName = '";
    $output .= $this->getDatabaseName();
    $output .= "', port = ";
    $output .= $this->getPort();
    return $output;
  }
}
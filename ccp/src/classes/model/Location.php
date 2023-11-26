<?php
declare(strict_types = 1);
namespace Poker\Ccp\classes\model;
class Location extends Base {
  public function __construct(protected bool $debug, protected string|int|NULL $id, protected string $name, protected User $user, protected int $count, protected int $active, protected $map,
    protected string|NULL $mapName, protected int $tournamentCount) {
    parent::__construct(debug: $debug, id: $id);
  }
  public function buildMapUrl(): string {
    return "<a href =\"" . Constant::PATH_MAP() . "/" . $this->getMapName() . "\">Map</a>\n";
  }
  public function getName(): string {
    return $this->name;
  }
  public function getUser(): User {
    return $this->user;
  }
  public function getCount(): int {
    return $this->count;
  }
  public function getActive(): int {
    return $this->active;
  }
  public function getMap() {
    return $this->map;
  }
  public function getMapName(): string {
    return $this->mapName;
  }
  public function getTournamentCount(): int {
    return $this->tournamentCount;
  }
  public function setName(string $name) {
    $this->name = $name;
  }
  public function setUser(User $user) {
    $this->user = $user;
  }
  public function setCount(int $count) {
    $this->count = $count;
  }
  public function setActive(int $active) {
    $this->active = $active;
  }
  public function setMap($map) {
    $this->map = $map;
  }
  public function setMapName(string $mapName) {
    $this->mapName = $mapName;
  }
  public function setTournamentCount(int $tournamentCount) {
    $this->tournamentCount = $tournamentCount;
  }
  public function getLink(): string {
    $link = new HtmlLink(accessKey: NULL, class: NULL, debug: $this->isDebug(), href: "manageLocation.php", id: NULL, paramName: array("userId","mode"), paramValue: array($this->getId() . "modify"),
      tabIndex: - 1, text: $this->getName(), title: NULL);
    return $link->getHtml();
  }
  public function __toString(): string {
    $output = parent::__toString();
    $output .= ", name = '";
    $output .= $this->name;
    $output .= "', user = [";
    $output .= $this->user;
    $output .= "], count = ";
    $output .= $this->count;
    $output .= ", active = ";
    $output .= var_export(value: $this->active, return: true);
    $output .= ", map = '";
    $output .= $this->map;
    $output .= "', mapName = '";
    $output .= $this->mapName;
    $output .= "', tournamentCount = ";
    $output .= $this->tournamentCount;
    return $output;
  }
}
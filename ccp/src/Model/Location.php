<?php
declare(strict_types = 1);
namespace Poker\Ccp\Model;
use DateTime;
use Poker\Ccp\Entity\Locations;
class Location extends Base {
    private Locations $locations;
    public function createFromEntity(bool $debug, Locations $locations): Location {
        $this->locations = $locations;
        $player = new Player(debug: false, id: 0, name: "", username: "", password: "", email: "", phone: NULL, administrator: false, registrationDate: new DateTime(), approvalDate: NULL, approvalUserid: NULL, approvalName: NULL, rejectionDate: NULL, rejectionUserid: NULL, rejectionName: NULL, active: false, resetSelector: NULL, resetToken: NULL, resetExpires: NULL, rememberSelector: NULL, rememberToken: NULL, rememberExpires: NULL);
        $player->createFromEntity(debug: $debug, players: $locations->getPlayers());
        return $this->create(debug: $debug, id: $locations->getLocationId(), name: $locations->getLocationName(), address: $locations->getLocationAddress(), city: $locations->getLocationCity(), state: $locations->getLocationState(), zipCode: $locations->getLocationZipCode(), player: $player, count: count($locations->getTournaments()), active: $locations->getPlayers()->getPlayerActiveFlag(), map: $locations->getLocationMap(), mapName: $locations->getLocationMapLink(), tournamentCount: 0);
    }
    public function __construct(protected bool $debug, protected string|int|NULL $id, protected string $name, protected string $address, protected string $city, protected string $state, protected int $zipCode, protected ?Player $player, protected int $count, protected bool $active, protected $map, protected ?string $mapName, protected int $tournamentCount) {
        parent::__construct(debug: $debug, id: $id);
    }
    private function create(bool $debug, string|int|NULL $id, string $name, string $address, string $city, string $state, int $zipCode, ?Player $player, int $count, bool $active, $map, ?string $mapName, int $tournamentCount): Location {
        parent::__construct(debug: $debug, id: $id);
        $this->name = $name;
        $this->address = $address;
        $this->city = $city;
        $this->state = $state;
        $this->zipCode = $zipCode;
        $this->player = $player;
        $this->count = $count;
        $this->active = $active;
        $this->map = $map;
        $this->mapName = $mapName;
        $this->tournamentCount = $tournamentCount;
        return $this;
    }
    public function buildMapUrl(): string {
        return "<a href =\"" . Constant::PATH_MAP() . "/" . $this->getMapName() . "\">Map</a>\n";
    }
    public function getName(): string {
        return $this->name;
    }
    public function getAddress(): string {
        return $this->address;
    }
    public function getCity(): string {
        return $this->city;
    }
    public function getState(): string {
        return $this->state;
    }
    public function getZipCode(): int {
        return $this->zipCode;
    }
    public function getPlayer(): ?Player {
    return $this->player;
    }
    public function getCount(): int {
        return $this->count;
    }
    public function getActive(): string {
        return $this->active;
    }
    public function getMap() {
        return $this->map;
    }
    public function getMapName(): ?string {
        return $this->mapName;
    }
    public function getTournamentCount(): int {
        return $this->tournamentCount;
    }
    public function getLocations(): Locations {
        return $this->locations;
    }
    public function setName(string $name) {
        $this->name = $name;
        return $this;
    }
    public function setAddress(string $address): Location {
          $this->address = $address;
          return $this;
    }
    public function setCity(string $city): Location {
          $this->city = $city;
          return $this;
    }
    public function setState(string $state): Location {
          $this->state = $state;
          return $this;
    }
    public function setZipCode(int $zipCode): Location {
          $this->zipCode = $zipCode;
          return $this;
    }
    public function setPlayer(?Player $player): Location {
        $this->player = $player;
        return $this;
    }
    public function setCount(int $count): Location {
        $this->count = $count;
        return $this;
    }
    public function setActive(string $active): Location {
        $this->active = $active;
        return $this;
    }
    public function setMap($map): Location {
        $this->map = $map;
        return $this;
    }
    public function setMapName(?string $mapName): Location {
        $this->mapName = $mapName;
        return $this;
    }
    public function setTournamentCount(int $tournamentCount): Location {
        $this->tournamentCount = $tournamentCount;
        return $this;
    }
    public function setLocations(Locations $locations): Location {
        $this->locations = $locations;
        return $this;
    }
    public function __toString(): string {
        $output = parent::__toString();
        $output .= ", name = '";
        $output .= $this->name;
        $output .= ", address = '";
        $output .= $this->address;
        $output .= ", city = '";
        $output .= $this->city;
        $output .= ", state = '";
        $output .= $this->state;
        $output .= ", zipCode = ";
        $output .= $this->zipCode;
        $output .= ", player = [";
        $output .= $this->player;
        $output .= "], count = ";
        $output .= $this->count;
        $output .= ", active = '";
        $output .= $this->active;
        $output .= "', map = '";
        $output .= $this->map;
        $output .= "', mapName = '";
        $output .= $this->mapName;
        $output .= "', tournamentCount = ";
        $output .= $this->tournamentCount;
    return $output;
}
}
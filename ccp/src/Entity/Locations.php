<?php
declare(strict_types=1);
namespace Poker\Ccp\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\CustomIdGenerator;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Index;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

#[Table(name: "poker_locations")]
#[Index(name: "FK_LOCATIONS_PLAYERS", columns: ["player_id"])]
#[Entity(repositoryClass: LocationsRepository::class)]
class Locations
{
    #[Column(name: "location_id", nullable: false)]
    #[Id]
    #[GeneratedValue(strategy: "CUSTOM")]
    #[CustomIdGenerator(class: LocationsIdGenerator::class)]
    private int $locationId;

    #[Column(name: "location_name", length: 50, nullable: false)]
    private string $locationName;

    #[Column(name: "location_city", length: 50, nullable: false)]
    private string $locationCity;

    #[Column(name: "location_address", length: 50, nullable: false)]
    private string $locationAddress;

    #[Column(name: "location_state", length: 2, nullable: false)]
    private string $locationState;

    #[Column(name: "location_zip_code", nullable: false)]
    private int $locationZipCode;

    #[Column(name: "location_map", type: "blob", length: 0, nullable: true)]
    private $locationMap;

    #[Column(name: "location_map_link", length: 50, nullable: true)]
    private ?string $locationMapLink;

    #[ManyToOne(inversedBy: "locations", cascade: ["persist"])]
    #[JoinColumn(name: "player_id", referencedColumnName: "player_id")]
    private Players $players;

    #[OneToMany(targetEntity: Tournaments::class, mappedBy: "locations")]
    #[JoinColumn(name: "location_id", referencedColumnName: "location_id")]
    private Collection $tournaments;

    public function __construct()
    {
        $this->tournaments = new ArrayCollection();
    }

    /**
     * @return number
     */
    public function getLocationId(): int {
        return $this->locationId;
    }

    /**
     * @return string
     */
    public function getLocationName(): string {
        return $this->locationName;
    }

    /**
     * @return string
     */
    public function getLocationCity(): string {
        return $this->locationCity;
    }

    /**
     * @return string
     */
    public function getLocationAddress(): string {
        return $this->locationAddress;
    }

    /**
     * @return string
     */
    public function getLocationState(): string {
        return $this->locationState;
    }

    /**
     * @return number
     */
    public function getLocationZipCode(): int {
        return $this->locationZipCode;
    }

    /**
     * @return mixed
     */
    public function getLocationMap() {
        return $this->locationMap;
    }

    /**
     * @return string
     */
    public function getLocationMapLink(): ?string {
        return $this->locationMapLink;
    }

    /**
     * @return \Poker\Ccp\Entity\Players
     */
    public function getPlayers(): Players {
        return $this->players;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTournaments(): Collection {
        return $this->tournaments;
    }

    /**
     * @param number $locationId
     */
    public function setLocationId(int $locationId): self {
        $this->locationId = $locationId;
        return $this;
    }

    /**
     * @param string $locationName
     */
    public function setLocationName(string $locationName): self {
        $this->locationName = $locationName;
        return $this;
    }

    /**
     * @param string $locationCity
     */
    public function setLocationCity(string $locationCity): self {
        $this->locationCity = $locationCity;
        return $this;
    }

    /**
     * @param string $locationAddress
     */
    public function setLocationAddress(string $locationAddress): self {
        $this->locationAddress = $locationAddress;
        return $this;
    }

    /**
     * @param string $locationState
     */
    public function setLocationState(string $locationState): self {
        $this->locationState = $locationState;
        return $this;
    }

    /**
     * @param number $locationZipCode
     */
    public function setLocationZipCode(int $locationZipCode): self {
        $this->locationZipCode = $locationZipCode;
        return $this;
    }

    /**
     * @param mixed $locationMap
     */
    public function setLocationMap($locationMap): self {
        $this->locationMap = $locationMap;
        return $this;
    }

    /**
     * @param string $locationMapLink
     */
    public function setLocationMapLink(?string $locationMapLink): self {
        $this->locationMapLink = $locationMapLink;
        return $this;
    }

    /**
     * @param \Poker\Ccp\Entity\Players $player
     */
    public function setPlayers(Players $players): self {
        $this->players = $players;
        return $this;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $tournaments
     */
    public function setTournaments(Collection $tournaments): self {
        $this->tournaments = $tournaments;
        return $this;
    }

    public function addTournament(Tournaments $tournaments): self
    {
        $this->tournaments[] = $tournaments;
        return $this;
    }

    public function removeTournament(Tournaments $tournaments): self
    {
        $this->tournaments->removeElement($tournaments);
        return $this;
    }
}
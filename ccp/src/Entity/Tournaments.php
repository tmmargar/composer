<?php
declare(strict_types=1);
namespace Poker\Ccp\Entity;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\CustomIdGenerator;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Index;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\ManyToOne;

#[Table(name: "poker_tournaments")]
#[Index(name: "FK_TOURNAMENTS_LOCATIONS", columns: ["location_id"])]
#[Index(name: "FK_TOURNAMENTS_GROUPS", columns: ["group_id"])]
#[Index(name: "FK_TOURNAMENTS_LIMIT_TYPES", columns: ["limit_type_id"])]
#[Index(name: "FK_TOURNAMENTS_SPECIAL_TYPES", columns: ["special_type_id"])]
#[Index(name: "FK_TOURNAMENTS_GAME_TYPES", columns: ["game_type_id"])]
#[Entity(repositoryClass: TournamentsRepository::class)]
class Tournaments
{
    #[Column(name: "tournament_id", nullable: false)]
    #[Id]
    #[GeneratedValue(strategy: "CUSTOM")]
    #[CustomIdGenerator(class: TournamentsIdGenerator::class)]
    private int $tournamentId;

    #[Column(name: "tournament_description", length: 200, nullable: false)]
    private string $tournamentDescription;

    #[Column(name: "tournament_comment", length: 200, nullable: true)]
    private ?string $tournamentComment;

    #[Column(name: "tournament_chip_count", nullable: false)]
    private int $tournamentChipCount;

    #[Column(name: "tournament_date", type: "date", nullable: false)]
    private DateTime $tournamentDate;

    #[Column(name: "tournament_start_time", type: "time", nullable: false)]
    private DateTime $tournamentStartTime;

    #[Column(name: "tournament_buyin_amount", nullable: false)]
    private int $tournamentBuyinAmount;

    #[Column(name: "tournament_max_players", nullable: false)]
    private int $tournamentMaxPlayers;

    #[Column(name: "tournament_max_rebuys", nullable: false)]
    private int $tournamentMaxRebuys;

    #[Column(name: "tournament_rebuy_amount", nullable: false)]
    private int $tournamentRebuyAmount;

    #[Column(name: "tournament_addon_amount", nullable: false)]
    private int $tournamentAddonAmount;

    #[Column(name: "tournament_addon_chip_count", nullable: false)]
    private int $tournamentAddonChipCount;

    #[Column(name: "tournament_rake", type: "decimal", precision: 2, scale: 2, nullable: false)]
    private string $tournamentRake; // per documentation must be string not float

    #[Column(name: "tournament_map", length: 255, nullable: true)]
    private ?string $tournamentMap;

    #[ManyToOne(inversedBy: "tournaments")]
    #[JoinColumn(name: "group_id", referencedColumnName: "group_id")]
    private Groups $groups;

    #[ManyToOne(inversedBy: "tournaments")]
    #[JoinColumn(name: "special_type_id", referencedColumnName: "special_type_id")]
    private ?SpecialTypes $specialTypes;

    #[ManyToOne(inversedBy: "tournaments")]
    #[JoinColumn(name: "limit_type_id", referencedColumnName: "limit_type_id")]
    private LimitTypes $limitTypes;

    #[ManyToOne(inversedBy: "tournaments")]
    #[JoinColumn(name: "game_type_id", referencedColumnName: "game_type_id")]
    private GameTypes $gameTypes;

    #[ManyToOne(inversedBy: "tournaments")]
    #[JoinColumn(name: "location_id", referencedColumnName: "location_id")]
    private Locations $locations;

    #[OneToMany(targetEntity: Fees::class, mappedBy: "tournaments")]
    #[JoinColumn(name: "tournament_id", referencedColumnName: "tournament_id")]
    private Collection $fees;

    #[OneToMany(targetEntity: TournamentAbsences::class, mappedBy: "tournaments")]
    #[JoinColumn(name: "tournament_id", referencedColumnName: "tournament_id")]
    private Collection $tournamentAbsences;

    #[OneToMany(targetEntity: Results::class, mappedBy: "tournaments")]
    #[JoinColumn(name: "tournament_id", referencedColumnName: "tournament_id")]
    private Collection $results;

    public function __construct()
    {
        $this->fees = new ArrayCollection();
        $this->tournamentAbsences = new ArrayCollection();
        $this->results = new ArrayCollection();
    }

    /**
     * @return number
     */
    public function getTournamentId(): int {
        return $this->tournamentId;
    }

    /**
     * @return string
     */
    public function getTournamentDescription(): string {
        return $this->tournamentDescription;
    }

    /**
     * @return string
     */
    public function getTournamentComment(): ?string {
        return $this->tournamentComment;
    }

    /**
     * @return number
     */
    public function getTournamentChipCount(): int {
        return $this->tournamentChipCount;
    }

    /**
     * @return DateTime
     */
    public function getTournamentDate(): DateTime {
        return $this->tournamentDate;
    }

    /**
     * @return DateTime
     */
    public function getTournamentStartTime(): DateTime {
        return $this->tournamentStartTime;
    }

    /**
     * @return number
     */
    public function getTournamentBuyinAmount(): int {
        return $this->tournamentBuyinAmount;
    }

    /**
     * @return number
     */
    public function getTournamentMaxPlayers(): int {
        return $this->tournamentMaxPlayers;
    }

    /**
     * @return number
     */
    public function getTournamentMaxRebuys(): int {
        return $this->tournamentMaxRebuys;
    }

    /**
     * @return number
     */
    public function getTournamentRebuyAmount(): int {
        return $this->tournamentRebuyAmount;
    }

    /**
     * @return number
     */
    public function getTournamentAddonAmount(): int {
        return $this->tournamentAddonAmount;
    }

    /**
     * @return number
     */
    public function getTournamentAddonChipCount(): int {
        return $this->tournamentAddonChipCount;
    }

    /**
     * @return number
     */
    public function getTournamentRake(): string {
        return $this->tournamentRake;
    }

    /**
     * @return string
     */
    public function getTournamentMap(): ?string {
        return $this->tournamentMap;
    }

    /**
     * @return \Poker\Ccp\Entity\Groups
     */
    public function getGroups(): Groups {
        return $this->groups;
    }

    /**
     * @return \Poker\Ccp\Entity\SpecialTypes
     */
    public function getSpecialTypes(): ?SpecialTypes {
        return $this->specialTypes;
    }

    /**
     * @return \Poker\Ccp\Entity\LimitTypes
     */
    public function getLimitTypes(): LimitTypes {
        return $this->limitTypes;
    }

    /**
     * @return \Poker\Ccp\Entity\GameTypes
     */
    public function getGameTypes(): GameTypes {
        return $this->gameTypes;
    }

    /**
     * @return \Poker\Ccp\Entity\Locations
     */
    public function getLocations(): Locations {
        return $this->locations;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFees(): Collection {
        return $this->fees;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTournamentAbsences(): Collection {
        return $this->tournamentAbsences;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getResults(): Collection {
        return $this->results;
    }

    /**
     * @param number $tournamentId
     */
    public function setTournamentId(int $tournamentId): self {
        $this->tournamentId = $tournamentId;
        return $this;
    }

    /**
     * @param string $tournamentDescription
     */
    public function setTournamentDescription(string $tournamentDescription): self {
        $this->tournamentDescription = $tournamentDescription;
        return $this;
    }

    /**
     * @param string $tournamentComment
     */
    public function setTournamentComment(?string $tournamentComment): self {
        $this->tournamentComment = $tournamentComment;
        return $this;
    }

    /**
     * @param number $tournamentChipCount
     */
    public function setTournamentChipCount(int $tournamentChipCount): self {
        $this->tournamentChipCount = $tournamentChipCount;
        return $this;
    }

    /**
     * @param DateTime $tournamentDate
     */
    public function setTournamentDate(DateTime $tournamentDate): self {
        $this->tournamentDate = $tournamentDate;
        return $this;
    }

    /**
     * @param DateTime $tournamentStartTime
     */
    public function setTournamentStartTime(DateTime $tournamentStartTime): self {
        $this->tournamentStartTime = $tournamentStartTime;
        return $this;
    }

    /**
     * @param number $tournamentBuyinAmount
     */
    public function setTournamentBuyinAmount(int $tournamentBuyinAmount): self {
        $this->tournamentBuyinAmount = $tournamentBuyinAmount;
        return $this;
    }

    /**
     * @param number $tournamentMaxPlayers
     */
    public function setTournamentMaxPlayers(int $tournamentMaxPlayers): self {
        $this->tournamentMaxPlayers = $tournamentMaxPlayers;
        return $this;
    }

    /**
     * @param number $tournamentMaxRebuys
     */
    public function setTournamentMaxRebuys(int $tournamentMaxRebuys): self {
        $this->tournamentMaxRebuys = $tournamentMaxRebuys;
        return $this;
    }

    /**
     * @param number $tournamentRebuyAmount
     */
    public function setTournamentRebuyAmount(int $tournamentRebuyAmount): self {
        $this->tournamentRebuyAmount = $tournamentRebuyAmount;
        return $this;
    }

    /**
     * @param number $tournamentAddonAmount
     */
    public function setTournamentAddonAmount(int $tournamentAddonAmount): self {
        $this->tournamentAddonAmount = $tournamentAddonAmount;
        return $this;
    }

    /**
     * @param number $tournamentAddonChipCount
     */
    public function setTournamentAddonChipCount(int $tournamentAddonChipCount): self {
        $this->tournamentAddonChipCount = $tournamentAddonChipCount;
        return $this;
    }

    /**
     * @param number $tournamentRake
     */
    public function setTournamentRake(string $tournamentRake): self {
        $this->tournamentRake = $tournamentRake;
        return $this;
    }

    /**
     * @param string $tournamentMap
     */
    public function setTournamentMap(?string $tournamentMap): self {
        $this->tournamentMap = $tournamentMap;
        return $this;
    }

    /**
     * @param \Poker\Ccp\Entity\Groups $groups
     */
    public function setGroups(Groups $groups): self {
        $this->groups = $groups;
        return $this;
    }

    /**
     * @param \Poker\Ccp\Entity\SpecialTypes $specialTypes
     */
    public function setSpecialTypes(?SpecialTypes $specialTypes): self {
        $this->specialTypes = $specialTypes;
        return $this;
    }

    /**
     * @param \Poker\Ccp\Entity\LimitTypes $limitTypes
     */
    public function setLimitTypes(LimitTypes $limitTypes): self {
        $this->limitTypes = $limitTypes;
        return $this;
    }

    /**
     * @param \Poker\Ccp\Entity\GameTypes $gameTypes
     */
    public function setGameTypes(GameTypes $gameTypes): self {
        $this->gameTypes = $gameTypes;
        return $this;
    }

    /**
     * @param \Poker\Ccp\Entity\Locations $locations
     */
    public function setLocations(Locations $locations): self {
        $this->locations = $locations;
        return $this;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $fees
     */
    public function setFees(Collection $fees): self {
        $this->fees = $fees;
        return $this;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $tournamentAbsences
     */
    public function setTournamentAbsences(Collection $tournamentAbsences): self {
        $this->tournamentAbsences = $tournamentAbsences;
        return $this;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $results
     */
    public function setResults(Collection $results): self {
        $this->results = $results;
        return $this;
    }

    public function addFee(Fees $fees): self
    {
        $this->fees[] = $fees;
        return $this;
    }

    public function removeFee(Fees $fees): self
    {
        $this->fees->removeElement($fees);
        return $this;
    }

    public function addTournamentAbsence(TournamentAbsences $tournamentAbsences): self
    {
        $this->tournamentAbsences[] = $tournamentAbsences;
        return $this;
    }

    public function removeTournamentAbsence(TournamentAbsences $tournamentAbsences): self
    {
        $this->tournamentAbsences->removeElement($tournamentAbsences);
        return $this;
    }

    public function addResult(Fees $results): self
    {
        $this->results[] = $results;
        return $this;
    }

    public function removeResult(Fees $results): self
    {
        $this->results->removeElement($results);
        return $this;
    }
}
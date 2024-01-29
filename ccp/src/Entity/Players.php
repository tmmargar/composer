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
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\OneToOne;

#[Table(name: "poker_players")]
#[Entity(repositoryClass: PlayersRepository::class)]
class Players
{
    #[Column(name: "player_id", nullable: false)]
    #[Id]
    #[GeneratedValue(strategy: "CUSTOM")]
    #[CustomIdGenerator(class: PlayersIdGenerator::class)]
    private int $playerId;

    #[Column(name: "player_id_previous", nullable: true)]
    private ?int $playerIdPrevious;

    #[Column(name: "player_first_name", length: 30, nullable: false)]
    private string $playerFirstName;

    #[Column(name: "player_last_name", length: 30, nullable: false)]
    private string $playerLastName;

    #[Column(name: "player_username", length: 30, nullable: false)]
    private string $playerUsername;

    #[Column(name: "player_password", length: 100, nullable: false)]
    private string $playerPassword;

    #[Column(name: "player_email", length: 50, nullable: false)]
    private string $playerEmail;

    #[Column(name: "player_phone", type: "decimal", precision: 10, scale: 0, nullable: false)]
    private string $playerPhone; // per documentation must be string not float

    #[Column(name: "player_administrator_flag", length: 1, nullable: false)]
    private string $playerAdministratorFlag;

    #[Column(name: "player_registration_date", type: "date", nullable: false)]
    private DateTime $playerRegistrationDate;

    #[Column(name: "player_approval_date", type: "date", nullable: true)]
    private ?DateTime $playerApprovalDate;

    #[OneToOne(targetEntity: Players::class)]
    #[JoinColumn(name: "player_approval_player_id", referencedColumnName: "player_id")]
    private ?Players $playerApproval;

    #[Column(name: "player_rejection_date", type: "date", nullable: true)]
    private ?DateTime $playerRejectionDate;

    #[OneToOne(targetEntity: Players::class)]
    #[JoinColumn(name: "player_rejection_player_id", referencedColumnName: "player_id")]
    private ?Players $playerRejection;

    #[Column(name: "player_active_flag", length: 1, nullable: false)]
    private string $playerActiveFlag;

    #[Column(name: "player_selector", length: 16, nullable: true)]
    private ?string $playerSelector;

    #[Column(name: "player_token", length: 64, nullable: true)]
    private ?string $playerToken;

    #[Column(name: "player_expires", type: "bigint", nullable: true)]
    private ?string $playerExpires;

    #[OneToMany(targetEntity: Fees::class, mappedBy: "players")]
    #[JoinColumn(name: "player_id", referencedColumnName: "player_id")]
    private Collection $fees;

    #[OneToMany(targetEntity: TournamentAbsences::class, mappedBy: "players")]
    #[JoinColumn(name: "player_id", referencedColumnName: "player_id")]
    private Collection $tournamentAbsences;

    #[OneToMany(targetEntity: Locations::class, mappedBy: "players")]
    #[JoinColumn(name: "player_id", referencedColumnName: "player_id")]
    private Collection $locations;

    #[OneToMany(targetEntity: Results::class, mappedBy: "players")]
    #[JoinColumn(name: "player_id", referencedColumnName: "player_id")]
    private Collection $results;

    #[OneToMany(targetEntity: Results::class, mappedBy: "playerKos")]
    #[JoinColumn(name: "player_id", referencedColumnName: "player_id_ko")]
    private ?Collection $resultsKos;

    public function __construct()
    {
        $this->fees = new ArrayCollection();
        $this->tournamentAbsences = new ArrayCollection();
        $this->locations = new ArrayCollection();
        $this->results = new ArrayCollection();
        $this->resultsKos = new ArrayCollection();
    }

    /**
     * @return number
     */
    public function getPlayerId(): int {
        return $this->playerId;
    }

    /**
     * @return number
     */
    public function getPlayerIdPrevious(): ?int {
        return $this->playerIdPrevious;
    }

    /**
     * @return string
     */
    public function getPlayerFirstName(): string {
        return $this->playerFirstName;
    }

    /**
     * @return string
     */
    public function getPlayerLastName(): string {
        return $this->playerLastName;
    }

    /**
     * @return string
     */
    public function getPlayerUsername(): string {
        return $this->playerUsername;
    }

    /**
     * @return string
     */
    public function getPlayerPassword(): string {
        return $this->playerPassword;
    }

    /**
     * @return string
     */
    public function getPlayerEmail(): string {
        return $this->playerEmail;
    }

    /**
     * @return number
     */
    public function getPlayerPhone(): string {
        return $this->playerPhone;
    }

    /**
     * @return string
     */
    public function getPlayerAdministratorFlag(): string {
        return $this->playerAdministratorFlag;
    }

    /**
     * @return DateTime
     */
    public function getPlayerRegistrationDate(): DateTime {
        return $this->playerRegistrationDate;
    }

    /**
     * @return DateTime
     */
    public function getPlayerApprovalDate(): ?DateTime {
        return $this->playerApprovalDate;
    }

    public function getPlayerApproval(): ?Players {
        return $this->playerApproval;
    }

    /**
     * @return DateTime
     */
    public function getPlayerRejectionDate(): ?DateTime {
        return $this->playerRejectionDate;
    }

    public function getPlayerRejection(): ?Players {
        return $this->playerRejection;
    }
    /**
     * @return string
     */
    public function getPlayerActiveFlag(): string {
        return $this->playerActiveFlag;
    }

    /**
     * @return string
     */
    public function getPlayerSelector(): ?string {
        return $this->playerSelector;
    }

    /**
     * @return string
     */
    public function getPlayerToken(): ?string {
        return $this->playerToken;
    }

    /**
     * @return number
     */
    public function getPlayerExpires(): ?string {
        return $this->playerExpires;
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
    public function getLocations(): Collection {
        return $this->locations;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getResults(): Collection {
        return $this->results;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getResultsKos(): ?Collection {
        return $this->resultsKos;
    }

    /**
     * @param number $playerId
     */
    public function setPlayerId(int $playerId): self {
        $this->playerId = $playerId;
        return $this;
    }

    /**
     * @param number $playerIdPrevious
     */
    public function setPlayerIdPrevious(?int $playerIdPrevious): self {
        $this->playerIdPrevious = $playerIdPrevious;
        return $this;
    }

    /**
     * @param string $playerFirstName
     */
    public function setPlayerFirstName(string $playerFirstName): self {
        $this->playerFirstName = $playerFirstName;
        return $this;
    }

    /**
     * @param string $playerLastName
     */
    public function setPlayerLastName(string $playerLastName): self {
        $this->playerLastName = $playerLastName;
        return $this;
    }

    /**
     * @param string $playerUsername
     */
    public function setPlayerUsername(string $playerUsername): self {
        $this->playerUsername = $playerUsername;
        return $this;
    }

    /**
     * @param string $playerPassword
     */
    public function setPlayerPassword(string $playerPassword): self {
        $this->playerPassword = $playerPassword;
        return $this;
    }

    /**
     * @param string $playerEmail
     */
    public function setPlayerEmail(string $playerEmail): self {
        $this->playerEmail = $playerEmail;
        return $this;
    }

    /**
     * @param number $playerPhone
     */
    public function setPlayerPhone(string $playerPhone): self {
        $this->playerPhone = $playerPhone;
        return $this;
    }

    /**
     * @param string $playerAdministratorFlag
     */
    public function setPlayerAdministratorFlag(string $playerAdministratorFlag): self {
        $this->playerAdministratorFlag = $playerAdministratorFlag;
        return $this;
    }

    /**
     * @param DateTime $playerRegistrationDate
     */
    public function setPlayerRegistrationDate(DateTime $playerRegistrationDate): self {
        $this->playerRegistrationDate = $playerRegistrationDate;
        return $this;
    }

    /**
     * @param DateTime $playerApprovalDate
     */
    public function setPlayerApprovalDate(?DateTime $playerApprovalDate): self {
        $this->playerApprovalDate = $playerApprovalDate;
        return $this;
    }

    public function setPlayerApproval(?Players $playerApproval): self {
        $this->playerApproval = $playerApproval;
        return $this;
    }

    /**
     * @param DateTime $playerRejectionDate
     */
    public function setPlayerRejectionDate(?DateTime $playerRejectionDate): self {
        $this->playerRejectionDate = $playerRejectionDate;
        return $this;
    }


    public function setPlayerRejection(?Players $playerRejection): self {
        $this->playerRejection = $playerRejection;
        return $this;
    }
    /**
     * @param string $playerActiveFlag
     */
    public function setPlayerActiveFlag(string $playerActiveFlag): self {
        $this->playerActiveFlag = $playerActiveFlag;
        return $this;
    }

    /**
     * @param string $playerSelector
     */
    public function setPlayerSelector(?string $playerSelector): self {
        $this->playerSelector = $playerSelector;
        return $this;
    }

    /**
     * @param string $playerToken
     */
    public function setPlayerToken(?string $playerToken): self {
        $this->playerToken = $playerToken;
        return $this;
    }

    /**
     * @param number $playerExpires
     */
    public function setPlayerExpires(?string $playerExpires): self {
        $this->playerExpires = $playerExpires;
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
     * @param \Doctrine\Common\Collections\Collection $locations
     */
    public function setLocations(Collection $locations): self {
        $this->locations = $locations;
        return $this;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $results
     */
    public function setResults(Collection $results): self {
        $this->results = $results;
        return $this;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $resultsKos
     */
    public function setResultsKos(?Collection $resultsKos): self {
        $this->resultsKos = $resultsKos;
        return $this;
    }

    public function getPlayerName(): string {
        return $this->playerFirstName . " " . $this->playerLastName;
    }

    public function addFees(Fees $fees): self
    {
        $this->fees[] = fees;
        return $this;
    }

    public function removeFees(Fees $fees): self
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

    public function addLocation(Locations $locations): self
    {
        $this->locations[] = $locations;
        return $this;
    }

    public function removeLocation(Locations $locations): self
    {
        $this->locations->removeElement($locations);
        return $this;
    }

    public function addResults(Results $results): self
    {
        $this->results[] = $results;
        return $this;
    }

    public function removeResults(Results $results): self
    {
        $this->results->removeElement($results);
        return $this;
    }

    public function addResultsKo(Results $resultsKos): self
    {
        $this->resultsKos[] = $resultsKos;
        return $this;
    }

    public function removeResultsKo(Results $resultsKos): self
    {
        $this->resultsKos->removeElement($resultsKos);
        return $this;
    }
}
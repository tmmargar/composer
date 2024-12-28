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

#[Table(name: "poker_seasons")]
#[Entity(repositoryClass: SeasonsRepository::class)]
class Seasons
{
    #[Column(name: "season_id", nullable: false)]
    #[Id]
    #[GeneratedValue(strategy: "CUSTOM")]
    #[CustomIdGenerator(class: SeasonsIdGenerator::class)]
    private int $seasonId;

    #[Column(name: "season_description", length: 50, nullable: false)]
    private string $seasonDescription;

    #[Column(name: "season_start_date", type: "date", nullable: false)]
    private DateTime $seasonStartDate;

    #[Column(name: "season_end_date", type: "date", nullable: false)]
    private DateTime $seasonEndDate;

    #[Column(name: "season_championship_qualification_count", nullable: false)]
    private int $seasonChampionshipQualificationCount;

    #[Column(name: "season_final_table_players", nullable: false)]
    private int $seasonFinalTablePlayers;

    #[Column(name: "season_final_table_bonus_points", nullable: false)]
    private int $seasonFinalTableBonusPoints;

    #[Column(name: "season_fee", nullable: false)]
    private int $seasonFee;

    #[Column(name: "season_active_flag", nullable: false)]
    private bool $seasonActiveFlag;

    #[OneToMany(targetEntity: Fees::class, mappedBy: "seasons")]
    #[JoinColumn(name: "season_id", referencedColumnName: "season_id")]
    private Collection $fees;

    public function __construct()
    {
      $this->fees = new ArrayCollection();
    }

    /**
     * @return number
     */
    public function getSeasonId(): int {
        return $this->seasonId;
    }

    /**
     * @return string
     */
    public function getSeasonDescription(): string {
        return $this->seasonDescription;
    }

    /**
     * @return DateTime
     */
    public function getSeasonStartDate(): DateTime {
        return $this->seasonStartDate;
    }

    /**
     * @return DateTime
     */
    public function getSeasonEndDate(): DateTime {
        return $this->seasonEndDate;
    }

    /**
     * @return number
     */
    public function getSeasonChampionshipQualificationCount(): int {
        return $this->seasonChampionshipQualificationCount;
    }

    /**
     * @return number
     */
    public function getSeasonFinalTablePlayers(): int {
        return $this->seasonFinalTablePlayers;
    }

    /**
     * @return number
     */
    public function getSeasonFinalTableBonusPoints(): int {
        return $this->seasonFinalTableBonusPoints;
    }

    /**
     * @return number
     */
    public function getSeasonFee(): int {
        return $this->seasonFee;
    }

    /**
     * @return bool
     */
    public function getSeasonActiveFlag(): bool {
        return $this->seasonActiveFlag;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFees(): Collection {
        return $this->fees;
    }

    /**
     * @param number $seasonId
     */
    public function setSeasonId(int $seasonId): self {
        $this->seasonId = $seasonId;
        return $this;
    }

    /**
     * @param string $seasonDescription
     */
    public function setSeasonDescription(string $seasonDescription): self {
        $this->seasonDescription = $seasonDescription;
        return $this;
    }

    /**
     * @param DateTime $seasonStartDate
     */
    public function setSeasonStartDate(DateTime $seasonStartDate): self {
        $this->seasonStartDate = $seasonStartDate;
        return $this;
    }

    /**
     * @param DateTime $seasonEndDate
     */
    public function setSeasonEndDate(DateTime $seasonEndDate): self {
        $this->seasonEndDate = $seasonEndDate;
        return $this;
    }

    /**
     * @param number $seasonChampionshipQualificationCount
     */
    public function setSeasonChampionshipQualificationCount(int $seasonChampionshipQualificationCount): self {
        $this->seasonChampionshipQualificationCount = $seasonChampionshipQualificationCount;
        return $this;
    }

    /**
     * @param number $seasonFinalTablePlayers
     */
    public function setSeasonFinalTablePlayers(int $seasonFinalTablePlayers): self {
        $this->seasonFinalTablePlayers = $seasonFinalTablePlayers;
        return $this;
    }

    /**
     * @param number $seasonFinalTableBonusPoints
     */
    public function setSeasonFinalTableBonusPoints(int $seasonFinalTableBonusPoints): self {
        $this->seasonFinalTableBonusPoints = $seasonFinalTableBonusPoints;
        return $this;
    }

    /**
     * @param number $seasonFee
     */
    public function setSeasonFee(int $seasonFee): self {
        $this->seasonFee = $seasonFee;
        return $this;
    }

    /**
     * @param bool $seasonActiveFlag
     */
    public function setSeasonActiveFlag(bool $seasonActiveFlag): self {
        $this->seasonActiveFlag = $seasonActiveFlag;
        return $this;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $fees
     */
    public function setFees(Collection $fees): self {
        $this->fees = $fees;
        return $this;
    }

    public function addFees(Fees $fees): self
    {
        $this->fees[] = $fees;
        return $this;
    }

    public function removeFees(Fees $fees): self
    {
        $this->fees->removeElement($fees);
        return $this;
    }
}
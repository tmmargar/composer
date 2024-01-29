<?php
declare(strict_types = 1);
namespace Poker\Ccp\classes\model;

use \DateTime;
use Poker\Ccp\Entity\Seasons;

class Season extends Base {
    private Seasons $seasons;
    public function createFromEntity(bool $debug, Seasons $seasons): Season {
        $this->seasons = $seasons;
        return $this->create(debug: $debug, id: $seasons->getSeasonId(), description: $seasons->getSeasonDescription(), startDate: $seasons->getSeasonStartDate(), endDate: $seasons->getSeasonEndDate(), championshipQualify: $seasons->getSeasonChampionshipQualificationCount(), finalTablePlayers: $seasons->getSeasonFinalTablePlayers(), finalTableBonusPoints: $seasons->getSeasonFinalTableBonusPoints(), fee: $seasons->getSeasonFee(), active: $seasons->getSeasonActiveFlag());
    }
    public function __construct(protected bool $debug, protected string|int|NULL $id, protected string $description, protected ?DateTime $startDate, protected ?DateTime $endDate, protected int $championshipQualify, protected int $finalTablePlayers, protected int $finalTableBonusPoints, protected int $fee, protected string $active) {
        parent::__construct(debug: $debug, id: $id);
    }
    private function create(bool $debug, string|int|NULL $id, string $description, ?DateTime $startDate, ?DateTime $endDate, int $championshipQualify, int $finalTablePlayers, int $finalTableBonusPoints, int $fee, string $active): Season {
        parent::__construct(debug: $debug, id: $id);
        $this->description = $description;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->championshipQualify = $championshipQualify;
        $this->finalTablePlayers = $finalTablePlayers;
        $this->finalTableBonusPoints = $finalTableBonusPoints;
        $this->fee = $fee;
        $this->active = $active;
        return $this;
    }
    public function getActive(): string {
        return $this->active;
    }
    public function getDescription(): string {
        return $this->description;
    }
    public function getEndDate(): ?DateTime {
        return $this->endDate;
    }
    public function getFee(): int {
        return $this->fee;
    }
    public function getFinalTableBonusPoints(): int {
        return $this->finalTableBonusPoints;
    }
    public function getFinalTablePlayers(): int {
        return $this->finalTablePlayers;
    }
    public function getStartDate(): ?DateTime {
        return $this->startDate;
    }
    public function getChampionshipQualify(): int {
        return $this->championshipQualify;
    }
    public function getSeasons(): Seasons {
        return $this->seasons;
    }
    public function setActive(string $active): Season {
        $this->active = $active;
        return $this;
    }
    public function setDescription(string $description): Season {
        $this->description = $description;
        return $this;
    }
    public function setEndDate(?DateTime $endDate): Season {
        $this->endDate = $endDate;
        return $this;
    }
    public function setFee(int $fee): Season {
        $this->fee = $fee;
        return $this;
    }
    public function setFinalTableBonusPoints(int $finalTableBonusPoints): Season {
        $this->finalTableBonusPoints = $finalTableBonusPoints;
        return $this;
    }
    public function setFinalTablePlayers(int $finalTablePlayers): Season {
        $this->finalTablePlayers = $finalTablePlayers;
        return $this;
    }
    public function setStartDate(?DateTime $startDate): Season {
        $this->startDate = $startDate;
        return $this;
    }
    public function setChampionshipQualify(int $championshipQualify): Season {
        $this->championshipQualify = $championshipQualify;
        return $this;
    }
    public function setSeasons(Seasons $seasons): Season {
        $this->seasons = $seasons;
        return $this;
    }
    public function __toString(): string {
        $output = parent::__toString();
        $output .= "', description = '";
        $output .= $this->description;
        $output .= "', startDate = '";
        $output .= $this->startDate->getDisplayFormat();
        $output .= "', endDate = '";
        $output .= $this->endDate->getDisplayFormat();
        $output .= "', championshipQualify = ";
        $output .= $this->championshipQualify;
        $output .= "', finalTableBonusPoints = ";
        $output .= $this->finalTableBonusPoints;
        $output .= "', finalTablePlayers = ";
        $output .= $this->finalTablePlayers;
        $output .= ", fee = ";
        $output .= $this->fee;
        $output .= ", active = '";
        $output .= $this->active;
        $output .= "'";
        return $output;
    }
}
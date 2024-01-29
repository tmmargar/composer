<?php
declare(strict_types=1);
namespace Poker\Ccp\Entity;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Index;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Table(name: "poker_fees")]
#[Index(name: "FK_FEES_PLAYERS", columns: ["player_id"])]
#[Index(name: "FK_FEES_TOURNAMENTS", columns: ["tournament_id"])]
#[Entity(repositoryClass: FeesRepository::class)]
class Fees
{
    #[Column(name: "fee_amount", nullable: false)]
    private int $feeAmount;

    #[Id]
    #[ManyToOne(targetEntity: Seasons::class, inversedBy: "fees")]
    #[JoinColumn(name: "season_id", referencedColumnName: "season_id")]
    private Seasons $seasons;

    #[Id]
    #[ManyToOne(targetEntity: Players::class, inversedBy: "fees")]
    #[JoinColumn(name: "player_id", referencedColumnName:"player_id")]
    private Players $players;

    #[Id]
    #[ManyToOne(targetEntity: Tournaments::class, inversedBy: "fees")]
    #[JoinColumn(name: "tournament_id", referencedColumnName: "tournament_id")]
    private Tournaments $tournaments;

    /**
     * @return number
     */
    public function getFeeAmount(): int {
        return $this->feeAmount;
    }

    /**
     * @return \Poker\Ccp\Entity\Players
     */
    public function getPlayers(): Players {
        return $this->players;
    }

    /**
     * @return \Poker\Ccp\Entity\Seasons
     */
    public function getSeasons(): Seasons {
        return $this->seasons;
    }

    /**
     * @return \Poker\Ccp\Entity\Tournaments
     */
    public function getTournaments(): Tournaments {
        return $this->tournaments;
    }

    /**
     * @param number $feeAmount
     */
    public function setFeeAmount(int $feeAmount): self {
        $this->feeAmount = $feeAmount;
        return $this;
    }

    /**
     * @param \Poker\Ccp\Entity\Players $players
     */
    public function setPlayers(Players $players): self {
        $this->players = $players;
        return $this;
    }

    /**
     * @param \Poker\Ccp\Entity\Seasons $seasons
     */
    public function setSeasons(Seasons $seasons): self {
        $this->seasons = $seasons;
        return $this;
    }

    /**
     * @param \Poker\Ccp\Entity\Tournaments $tournaments
     */
    public function setTournaments(Tournaments $tournaments): self {
        $this->tournaments = $tournaments;
        return $this;
    }

}
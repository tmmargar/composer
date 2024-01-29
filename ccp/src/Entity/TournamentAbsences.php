<?php
declare(strict_types=1);
namespace Poker\Ccp\Entity;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Table(name: "poker_tournament_absences")]
#[Entity(repositoryClass: TournamentAbsencesRepository::class)]
class TournamentAbsences
{

    #[Id]
    #[ManyToOne(targetEntity: Tournaments::class, inversedBy: "tournaments")]
    #[JoinColumn(name: "tournament_id", referencedColumnName:"tournament_id")]
    private Tournaments $tournaments;

    #[Id]
    #[ManyToOne(targetEntity: Players::Class, inversedBy: "players")]
    #[JoinColumn(name: "player_id", referencedColumnName:"player_id")]
    private Players $players;

    public function getTournaments(): Tournaments {
        return $this->tournaments;
    }

    public function setTournaments(Tournaments $tournaments): self {
        $this->tournaments = $tournaments;
        return $this;
    }

    public function getPlayers(): Players {
        return $this->players;
    }

    public function setPlayers(Players $players): self {
        $this->players = $players;
        return $this;
    }
}
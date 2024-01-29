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
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

#[Table(name: "poker_game_types")]
#[Entity(repositoryClass: GameTypesRepository::class)]
class GameTypes
{
  #[Column(name: "game_type_id", nullable: false)]
  #[Id]
  #[GeneratedValue(strategy: "CUSTOM")]
  #[CustomIdGenerator(class: GameTypesIdGenerator::class)]
  private int $gameTypeId;

  #[Column(name: "game_type_name", length: 30, nullable: false)]
  private string $gameTypeName;

  #[OneToMany(targetEntity: Tournaments::class, mappedBy: "gameTypes")]
  #[JoinColumn(name: "game_type_id", referencedColumnName: "game_type_id")]
  private Collection $tournaments;

    public function __construct()
    {
      $this->tournaments = new ArrayCollection();
    }

    /**
     * @return number
     */
    public function getGameTypeId(): int {
        return $this->gameTypeId;
    }

    /**
     * @return string
     */
    public function getGameTypeName(): string {
        return $this->gameTypeName;
    }

    /**
     * @param number $gameTypeId
     */
    public function setGameTypeId(int $gameTypeId): self {
        $this->gameTypeId = $gameTypeId;
        return $this;
    }

    /**
     * @param string $gameTypeName
     */
    public function setGameTypeName(string $gameTypeName): self {
        $this->gameTypeName = $gameTypeName;
        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTournaments(): Collection {
        return $this->tournaments;
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
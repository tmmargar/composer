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

#[Table(name: "poker_special_types")]
#[Entity(repositoryClass: SpecialTypesRepository::class)]
class SpecialTypes
{
    #[Column(name: "special_type_id", nullable: false)]
    #[Id]
    #[GeneratedValue(strategy: "CUSTOM")]
    #[CustomIdGenerator(class: SpecialTypesIdGenerator::class)]
    private int $specialTypeId;

    #[Column(name: "special_type_description", length: 50, nullable: false)]
    private string $specialTypeDescription;

    #[Column(name: "special_type_multiplier", nullable: false)]
    private int $specialTypeMultiplier;

    #[OneToMany(targetEntity: Tournaments::class, mappedBy: "specialTypes")]
    #[JoinColumn(name: "special_type_id", referencedColumnName: "special_type_id")]
    private Collection $tournaments;

    public function __construct()
    {
        $this->tournaments = new ArrayCollection();
    }

    /**
     * @return number
     */
    public function getSpecialTypeId(): int {
        return $this->specialTypeId;
    }

    /**
     * @return string
     */
    public function getSpecialTypeDescription(): string {
        return $this->specialTypeDescription;
    }

    /**
     * @return number
     */
    public function getSpecialTypeMultiplier(): int {
        return $this->specialTypeMultiplier;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTournaments(): Collection {
        return $this->tournaments;
    }

    /**
     * @param number $specialTypeId
     */
    public function setSpecialTypeId(int $specialTypeId): self {
        $this->specialTypeId = $specialTypeId;
        return $this;
    }

    /**
     * @param string $specialTypeDescription
     */
    public function setSpecialTypeDescription(string $specialTypeDescription): self {
        $this->specialTypeDescription = $specialTypeDescription;
        return $this;
    }

    /**
     * @param number $specialTypeMultiplier
     */
    public function setSpecialTypeMultiplier(int $specialTypeMultiplier): self {
        $this->specialTypeMultiplier = $specialTypeMultiplier;
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
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

#[Table(name: "poker_limit_types")]
#[Entity(repositoryClass: LimitTypesRepository::class)]
class LimitTypes
{
    #[Column(name: "limit_type_id", nullable: false)]
    #[Id]
    #[GeneratedValue(strategy: "CUSTOM")]
    #[CustomIdGenerator(class: LimitTypesIdGenerator::class)]
    private int $limitTypeId;

    #[Column(name: "limit_type_name", length: 20, nullable: false)]
    private string $limitTypeName;

    #[OneToMany(targetEntity: Tournaments::class, mappedBy: "limitTypes")]
    #[JoinColumn(name: "limit_type_id", referencedColumnName: "limit_type_id")]
    private Collection $tournaments;

    public function __construct()
    {
        $this->tournaments = new ArrayCollection();
    }

    /**
     * @return number
     */
    public function getLimitTypeId(): int {
        return $this->limitTypeId;
    }

    /**
     * @return string
     */
    public function getLimitTypeName(): string {
        return $this->limitTypeName;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTournaments(): Collection {
        return $this->tournaments;
    }

    /**
     * @param number $limitTypeId
     */
    public function setLimitTypeId(int $limitTypeId): self {
        $this->limitTypeId = $limitTypeId;
        return $this;
    }

    /**
     * @param string $limitTypeName
     */
    public function setLimitTypeName(string $limitTypeName): self {
        $this->limitTypeName = $limitTypeName;
        return $this;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $tournaments
     */
    public function setTournaments(Collection $tournaments): self {
        $this->tournaments = $tournaments;
        return $this;
    }

}
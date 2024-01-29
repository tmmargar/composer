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

#[Table(name: "poker_structures")]
// #[Index(name: "IDX_6E51381EC6D61B7F", columns: ["payout_id"])]
#[Index(columns: ["payout_id"])]
#[Entity(repositoryClass: StructuresRepository::class)]
class Structures
{

    #[Column(name: "structure_place", nullable: false)]
    #[Id]
    private int $structurePlace;

    #[Column(name: "structure_percentage", type: "decimal", precision: 4, scale: 3, nullable: false)]
    private string $structurePercentage; // per documentation must be string not float

    #[Id]
    #[ManyToOne(inversedBy: "structures", cascade: ["persist"])]
    #[JoinColumn(name: "payout_id", referencedColumnName: "payout_id")]
    private Payouts $payouts;

    /**
     * @return number
     */
    public function getStructurePlace(): int {
        return $this->structurePlace;
    }

    /**
     * @return number
     */
    public function getStructurePercentage(): string {
        return $this->structurePercentage;
    }

    /**
     * @return \Poker\Ccp\Entity\Payouts
     */
    public function getPayouts(): Payouts {
        return $this->payouts;
    }

    /**
     * @param number $payoutId
     */
    public function setPayoutId(int $payoutId): self {
        $this->payoutId = $payoutId;
        return $this;
    }

    /**
     * @param number $structurePlace
     */
    public function setStructurePlace(int $structurePlace): self {
        $this->structurePlace = $structurePlace;
        return $this;
    }

    /**
     * @param number $structurePercentage
     */
    public function setStructurePercentage(string $structurePercentage): self {
        $this->structurePercentage = $structurePercentage;
        return $this;
    }

    /**
     * @param \Poker\Ccp\Entity\Payouts $payouts
     */
    public function setPayouts(Payouts $payouts): self {
        $this->payouts = $payouts;
        return $this;
    }

}
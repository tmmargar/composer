<?php
declare(strict_types = 1);
namespace Poker\Ccp\Entity;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\CustomIdGenerator;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Table(name: "poker_inventories")]
#[Entity(repositoryClass: InventoriesRepository::class)]
class Inventories
{
    #[Column(name: "inventory_id", nullable: false)]
    #[Id]
    #[GeneratedValue(strategy: "CUSTOM")]
    #[CustomIdGenerator(class: InventoriesIdGenerator::class)]
    private int $inventoryId;

    #[ManyToOne(inversedBy: "inventories")]
    #[JoinColumn(name: "inventory_type_id", referencedColumnName: "inventory_type_id")]
    private InventoryTypes $inventoryTypes;

    #[Column(name: "inventory_current_amount", nullable: false)]
    private int $inventoryCurrentAmount;

    #[Column(name: "inventory_warning_amount", nullable: false)]
    private int $inventoryWarningAmount;

    #[Column(name: "inventory_order_amount", nullable: false)]
    private int $inventoryOrderAmount;

    #[Column(name: "inventory_comment", length: 200, nullable: true)]
    private ?string $inventoryComment;

    /**
     * @return number
     */
    public function getInventoryId(): int {
        return $this->inventoryId;
    }

    /**
     * @return \Poker\Ccp\Entity\InventoryTypes
     */
    public function getInventoryTypes(): InventoryTypes {
        return $this->inventoryTypes;
    }
    /**
     * @return number
     */
    public function getInventoryCurrentAmount(): int {
        return $this->inventoryCurrentAmount;
    }

    /**
     * @return number
     */
    public function getInventoryWarningAmount(): int {
        return $this->inventoryWarningAmount;
    }

    /**
     * @return number
     */
    public function getInventoryOrderAmount(): int {
        return $this->inventoryOrderAmount;
    }

    /**
     * @return string|NULL
     */
    public function getInventoryComment(): ?string {
        return $this->inventoryComment;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getInventories(): Collection {
        return $this->inventories;
    }

    /**
     * @param number $inventoryId
     */
    public function setInventoryId(int $inventoryId): self {
        $this->inventoryId = $inventoryId;
        return $this;
    }

    /**
     * @param \Poker\Ccp\Entity\InventoryTypes $inventoryTypes
     */
    public function setInventoryTypes(InventoryTypes $inventoryTypes): self {
        $this->inventoryTypes = $inventoryTypes;
        return $this;
    }

    /**
     * @param number $inventoryCurrentAmount
     */
    public function setInventoryCurrentAmount(int $inventoryCurrentAmount): self {
        $this->inventoryCurrentAmount = $inventoryCurrentAmount;
        return $this;
    }

    /**
     * @param number $inventoryWarningAmount
     */
    public function setInventoryWarningAmount(int $inventoryWarningAmount): self {
        $this->inventoryWarningAmount = $inventoryWarningAmount;
        return $this;
    }

    /**
     * @param number $inventoryOrderAmount
     */
    public function setInventoryOrderAmount(int $inventoryOrderAmount): self {
        $this->inventoryOrderAmount = $inventoryOrderAmount;
        return $this;
    }

    /**
     * @param string $inventoryComment
     * @return self
     */
    public function setInventoryComment(?string $inventoryComment): self {
        $this->inventoryComment = $inventoryComment;
        return $this;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $inventories
     */
    public function setInventories(Collection $inventories): self {
        $this->inventories = $inventories;
        return $this;
    }
}
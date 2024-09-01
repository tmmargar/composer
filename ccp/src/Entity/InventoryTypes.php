<?php
declare(strict_types = 1);
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

#[Table(name: "poker_inventory_types")]
#[Entity(repositoryClass: InventoryTypesRepository::class)]
class InventoryTypes
{
    #[Column(name: "inventory_type_id", nullable: false)]
    #[Id]
    #[GeneratedValue(strategy: "CUSTOM")]
    #[CustomIdGenerator(class: InventoryTypesIdGenerator::class)]
    private int $inventoryTypeId;

    #[Column(name: "inventory_type_name", length: 30, nullable: false)]
    private string $inventoryTypeName;

    #[Column(name: "inventory_type_min_amount", nullable: false)]
    private int $inventoryTypeMinAmount;

    #[Column(name: "inventory_type_max_amount", nullable: false)]
    private int $inventoryTypeMaxAmount;

    #[Column(name: "inventory_type_comment", length: 200, nullable: true)]
    private ?string $inventoryTypeComment;

    #[OneToMany(targetEntity: Inventories::class, mappedBy: "inventoryTypes")]
    #[JoinColumn(name: "inventory_type_id", referencedColumnName: "inventory_type_id")]
    private Collection $inventories;

    public function __construct()
    {
        $this->inventories = new ArrayCollection();
    }

    /**
     * @return number
     */
    public function getInventoryTypeId(): int {
        return $this->inventoryTypeId;
    }

    /**
     * @return string
     */
    public function getInventoryTypeName(): string {
        return $this->inventoryTypeName;
    }

    /**
     * @return number
     */
    public function getInventoryTypeMinAmount(): int {
        return $this->inventoryTypeMinAmount;
    }

    /**
     * @return number
     */
    public function getInventoryTypeMaxAmount(): int {
        return $this->inventoryTypeMaxAmount;
    }

    /**
     * @return string|NULL
     */
    public function getInventoryTypeComment(): ?string {
        return $this->inventoryTypeComment;
    }

    /**
     * @param number $inventoryTypeId
     */
    public function setInventoryTypeId(int $inventoryTypeId): self {
        $this->inventoryTypeId = $inventoryTypeId;
        return $this;
    }

    /**
     * @param string $inventoryTypeName
     */
    public function setInventoryTypeName(string $inventoryTypeName): self {
        $this->inventoryTypeName = $inventoryTypeName;
        return $this;
    }

    /**
     * @param number $inventoryTypeMinAmount
     */
    public function setInventoryTypeMinAmount(int $inventoryTypeMinAmount): self {
        $this->inventoryTypeMinAmount = $inventoryTypeMinAmount;
        return $this;
    }

    /**
     * @param number $inventoryTypeMaxAmount
     */
    public function setInventoryTypeMaxAmount(int $inventoryTypeMaxAmount): self {
        $this->inventoryTypeMaxAmount = $inventoryTypeMaxAmount;
        return $this;
    }

    /**
     * @param string $inventoryTypeComment
     * @return self
     */
    public function setInventoryTypeComment(?string $inventoryTypeComment): self {
        $this->inventoryTypeComment = $inventoryTypeComment;
        return $this;
    }

}
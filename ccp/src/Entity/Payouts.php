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

#[Table(name: "poker_payouts")]
#[Entity(repositoryClass: PayoutsRepository::class)]
class Payouts
{
    #[Column(name: "payout_id", nullable: false)]
    #[Id]
    #[GeneratedValue(strategy: "CUSTOM")]
    #[CustomIdGenerator(class: PayoutsIdGenerator::class)]
    private int $payoutId;

    #[Column(name: "payout_name", length: 30, nullable: false)]
    private string $payoutName;

    #[Column(name: "payout_min_players", nullable: false)]
    private int $payoutMinPlayers;

    #[Column(name: "payout_max_players", nullable: false)]
    private int $payoutMaxPlayers;

    #[OneToMany(targetEntity: GroupPayouts::class, mappedBy: "payouts")]
    #[JoinColumn(name: "payout_id", referencedColumnName: "payout_id")]
    private Collection $groupPayouts;

    #[OneToMany(targetEntity: Structures::class, mappedBy: "payouts")]
    #[JoinColumn(name: "payout_id", referencedColumnName: "payout_id")]
    private Collection $structures;

    public function __construct()
    {
        $this->groupPayouts = new ArrayCollection();
        $this->structures = new ArrayCollection();
    }

    /**
     * @return number
     */
    public function getPayoutId(): int {
        return $this->payoutId;
    }

    /**
     * @return string
     */
    public function getPayoutName(): string {
        return $this->payoutName;
    }

    /**
     * @return number
     */
    public function getPayoutMinPlayers(): int {
        return $this->payoutMinPlayers;
    }

    /**
     * @return number
     */
    public function getPayoutMaxPlayers(): int {
        return $this->payoutMaxPlayers;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGroupPayouts(): Collection {
        return $this->groupPayouts;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStructures(): Collection {
        return $this->structures;
    }

    /**
     * @param number $payoutId
     */
    public function setPayoutId(int $payoutId): self {
        $this->payoutId = $payoutId;
        return $this;
    }

    /**
     * @param string $payoutName
     */
    public function setPayoutName(string $payoutName): self {
        $this->payoutName = $payoutName;
        return $this;
    }

    /**
     * @param number $payoutMinPlayers
     */
    public function setPayoutMinPlayers(int $payoutMinPlayers): self {
        $this->payoutMinPlayers = $payoutMinPlayers;
        return $this;
    }

    /**
     * @param number $payoutMaxPlayers
     */
    public function setPayoutMaxPlayers(int $payoutMaxPlayers): self {
        $this->payoutMaxPlayers = $payoutMaxPlayers;
        return $this;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $groupPayouts
     */
    public function setGroupPayouts(Collection $groupPayouts): self {
        $this->groupPayouts = $groupPayouts;
        return $this;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $structure
     */
    public function setStructures(Collection $structure): self {
        $this->structures = $structure;
        return $this;
    }

    public function addGroupPayout(GroupPayouts $groupPayouts): self
    {
        $this->groupPayouts[] = $groupPayouts;
        return $this;
    }

    public function removeGroupPayout(GroupPayouts $groupPayouts): self
    {
        $this->groupPayouts->removeElement($groupPayouts);
        return $this;
    }

    public function addStructure(Structures $structures): self
    {
        $this->structures[] = $structures;
        return $this;
    }

    public function removeStructure(Structures $structures): self
    {
        $this->structures->removeElement($structures);
        return $this;
    }
}
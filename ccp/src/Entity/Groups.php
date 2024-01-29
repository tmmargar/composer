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

#[Table(name: "poker_groups")]
#[Entity(repositoryClass: GroupsRepository::class)]
class Groups
{
    #[Column(name: "group_id", nullable: false)]
    #[Id]
    #[GeneratedValue(strategy: "CUSTOM")]
    #[CustomIdGenerator(class: GroupsIdGenerator::class)]
    private int $groupId;

    #[Column(name: "group_name", length: 30, nullable: false)]
    private string $groupName;

    #[OneToMany(targetEntity: GroupPayouts::class, mappedBy: "groups")]
    #[JoinColumn(name: "group_id", referencedColumnName: "group_id")]
    private Collection $groupPayouts;

    #[OneToMany(targetEntity: Tournaments::class, mappedBy: "groups")]
    #[JoinColumn(name: "group_id", referencedColumnName: "group_id")]
    private Collection $tournaments;

    public function __construct()
    {
        $this->groupPayouts = new ArrayCollection();
        $this->tournaments = new ArrayCollection();
    }

    /**
     * @return number
     */
    public function getGroupId(): int {
        return $this->groupId;
    }

    /**
     * @return string
     */
    public function getGroupName(): string {
        return $this->groupName;
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
    public function getTournaments(): Collection {
        return $this->tournaments;
    }

    /**
     * @param number $groupId
     */
    public function setGroupId(int $groupId): self {
        $this->groupId = $groupId;
        return $this;
    }

    /**
     * @param string $groupName
     */
    public function setGroupName(string $groupName): self {
        $this->groupName = $groupName;
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
     * @param \Doctrine\Common\Collections\Collection $tournaments
     */
    public function setTournaments(Collection $tournaments): self {
        $this->tournaments = $tournaments;
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
<?php
declare(strict_types=1);
namespace Poker\Ccp\Entity;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Table(name: "poker_group_payouts")]
#[Entity(repositoryClass: GroupPayoutsRepository::class)]
class GroupPayouts
{
    #[Id]
    #[ManyToOne(targetEntity: Groups::class, inversedBy: "groupPayouts")]
    #[JoinColumn(name: "group_id", referencedColumnName: "group_id")]
    private Groups $groups;

    #[Id]
    #[ManyToOne(targetEntity: Payouts::class, inversedBy: "groupPayouts")]
    #[JoinColumn(name: "payout_id", referencedColumnName: "payout_id")]
    private Payouts $payouts;

    /**
     * @return \Poker\Ccp\Entity\Groups
     */
    public function getGroups(): Groups {
        return $this->groups;
    }

    /**
     * @return \Poker\Ccp\Entity\Payouts
     */
    public function getPayouts(): Payouts {
        return $this->payouts;
    }

    /**
     * @param \Poker\Ccp\Entity\Groups $groups
     */
    public function setGroups(Groups $groups): self {
        $this->groups = $groups;
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
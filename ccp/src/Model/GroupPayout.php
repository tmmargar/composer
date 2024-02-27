<?php
declare(strict_types = 1);
namespace Poker\Ccp\Model;
use Poker\Ccp\Entity\GroupPayouts;
class GroupPayout extends Base {
    private GroupPayouts $groupPayouts;
    public function createFromEntity(bool $debug, GroupPayouts $groupPayouts): GroupPayout {
        $this->groupPayouts = $groupPayouts;
        $group = new Group(debug: false, id: NULL, name: "");
        $group->createFromEntity(debug: $debug, groups: $groupPayouts->getGroups());
        $payout = new Payout(debug: false, id: NULL, name: "", minPlayers: 0, maxPlayers: 0, structures: array());
        $payout->createFromEntity(debug: $debug, payouts: $groupPayouts->getPayouts());
        return $this->create(debug: $debug, id: NULL, group: $group, payouts: array($payout));
    }
    public function __construct(protected bool $debug, protected string|int|NULL $id, protected ?Group $group, protected array $payouts) {
        parent::__construct(debug: $debug, id: $id);
    }
    private function create(bool $debug, string|int|NULL $id, ?Group $group, array $payouts): GroupPayout {
        parent::__construct(debug: $debug, id: $id);
        $this->group = $group;
        $this->payouts = $payouts;
        return $this;
    }
    public function getGroup(): ?Group {
        return $this->group;
    }
    public function getPayouts(): array {
        return $this->payouts;
    }
    public function getGroupPayouts(): GroupPayouts {
        return $this->groupPayouts;
    }
    public function setGroup(?Group $group): GroupPayout {
        $this->group = $group;
        return $this;
    }
    public function setPayouts(array $payouts): GroupPayout {
        $this->payouts = $payouts;
        return $this;
    }
    public function setGroupPayouts(GroupPayouts $groupPayouts): GroupPayout {
        $this->groupPayouts = $groupPayouts;
        return $this;
    }
    public function __toString(): string {
        $output = parent::__toString();
        $output .= "group = [";
        $output .= $this->group;
        $output .= "], payouts = [";
        foreach ($this->payouts as $payout) {
            $output .= "[";
            $output .= $payout;
            $output .= "],";
        }
        $output = substr($output, 0, strlen(string: $output) - 1) . "]";
        return $output;
    }
}
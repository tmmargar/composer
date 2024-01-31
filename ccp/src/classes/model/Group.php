<?php
declare(strict_types = 1);
namespace Poker\Ccp\classes\model;
use Poker\Ccp\Entity\Groups;
class Group extends Base {
    private Groups $groups;
    public function createFromEntity(bool $debug, Groups $groups): Group {
        $this->groups = $groups;
        return $this->create(debug: $debug, id: $groups->getGroupId(), name: $groups->getGroupName());
    }
    public function __construct(protected bool $debug, protected string|int|NULL $id, protected string $name) {
        parent::__construct(debug: $debug, id: $id);
    }
    private function create(bool $debug, string|int|NULL $id, string $name): Group {
        parent::__construct(debug: $debug, id: $id);
        $this->name = $name;
        return $this;
    }
    public function getName(): string {
        return $this->name;
    }
    public function getGroups(): Groups {
        return $this->groups;
    }
    public function setName(string $name): Group {
        $this->name = $name;
        return $this;
    }
    public function setGroups(Groups $groups): Group {
        $this->groups = $groups;
        return $this;
    }
    public function __toString(): string {
        $output = parent::__toString();
        $output .= ", name = '";
        $output .= $this->getName();
        $output .= "'";
        return $output;
    }
}
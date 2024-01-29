<?php
declare(strict_types = 1);
namespace Poker\Ccp\classes\model;

use Poker\Ccp\Entity\Structures;

class Structure extends Base {
    private Structures $structures;
    public function createFromEntity(bool $debug, Structures $structures): Structure {
        $this->structures = $structures;
        return $this->create(debug: $debug, id: NULL, place: $structures->getStructurePlace(), percentage: (float) $structures->getStructurePercentage());
    }
    public function __construct(protected bool $debug, protected string|int|NULL $id, protected int $place, protected float $percentage) {
        parent::__construct(debug: $debug, id: $id);
    }
    private function create(bool $debug, string|int|NULL $id, int $place, float $percentage): Structure {
        parent::__construct(debug: $debug, id: $id);
        $this->place = $place;
        $this->percentage = $percentage;
        return $this;
    }
    public function getPlace(): int {
        return $this->place;
    }
    public function getPercentage(): float {
        return $this->percentage;
    }
    public function getStructures(): Structures {
        return $this->structures;
    }
    public function setPlace(int $place): Structure {
        $this->place = $place;
        return $this;
    }
    public function setPercentage(float $percentage): Structure {
        $this->percentage = $percentage;
        return $this;
    }
    public function setStructures(Structures $structures): Structure {
        $this->structures = $structures;
        return $this;
    }
    public function __toString(): string {
        $output = parent::__toString();
        $output .= ", place = ";
        $output .= $this->place;
        $output .= ", percentage = ";
        $output .= $this->percentage;
        return $output;
    }
}
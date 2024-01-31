<?php
declare(strict_types = 1);
namespace Poker\Ccp\classes\model;
use Poker\Ccp\Entity\SpecialTypes;
class SpecialType extends Base {
    private SpecialTypes $specialTypes;
    public function createFromEntity(bool $debug, SpecialTypes $specialTypes): SpecialType {
        $this->specialTypes = $specialTypes;
        return $this->create(debug: $debug, id: $specialTypes->getSpecialTypeId(), description: $specialTypes->getSpecialTypeDescription(), multiplier: $specialTypes->getSpecialTypeMultiplier());
    }
    public function __construct(protected bool $debug, protected string|int|NULL $id, protected ?string $description, protected ?int $multiplier) {
        parent::__construct(debug: $debug, id: $id);
    }
    private function create(bool $debug, string|int|NULL $id, ?string $description, ?int $multiplier): SpecialType {
        parent::__construct(debug: $debug, id: $id);
        $this->description = $description;
        $this->multiplier = $multiplier;
        return $this;
    }
    public function getDescription(): ?string {
        return $this->description;
    }
    public function getMultiplier(): ?int {
        return $this->multiplier;
    }
    public function getSpecialTypes(): SpecialTypes {
        return $this->specialTypes;
    }
    public function setDescription(string $description): SpecialType {
        $this->description = $description;
        return $this;
    }
    public function setMultiplier(int $multiplier): SpecialType {
        $this->multiplier = $multiplier;
        return $this;
    }
    public function setSpecialTypes(SpecialTypes $specialTypes): SpecialType {
        $this->specialTypes = $specialTypes;
        return $this;
    }
    public function __toString(): string {
        $output = parent::__toString();
        $output .= ", description = '";
        $output .= $this->description;
        $output .= "', multiplier = ";
        $output .= $this->multiplier;
        $output .= "";
        return $output;
    }
}
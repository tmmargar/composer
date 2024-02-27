<?php
declare(strict_types = 1);
namespace Poker\Ccp\Model;
use Poker\Ccp\Entity\LimitTypes;
class LimitType extends Base {
    private LimitTypes $limitTypes;
    public function createFromEntity(bool $debug, LimitTypes $limitTypes): LimitType {
        $this->limitTypes = $limitTypes;
        return $this->create(debug: $debug, id: $limitTypes->getLimitTypeId(), name: $limitTypes->getLimitTypeName());
    }
    public function __construct(protected bool $debug, protected string|int $id, protected string $name) {
        parent::__construct(debug: $debug, id: $id);
    }
    private function create(bool $debug, string|int $id, string $name): LimitType {
        parent::__construct(debug: $debug, id: $id);
        $this->name = $name;
        return $this;
    }
    public function getName(): string {
        return $this->name;
    }
    public function getLimitTypes(): LimitTypes {
        return $this->limitTypes;
    }
    public function setName(string $name): LimitType {
        $this->name = $name;
        return $this;
    }
    public function setLimitTypes(LimitTypes $limitTypes): LimitType {
        $this->limitTypes = $limitTypes;
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
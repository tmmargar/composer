<?php
declare(strict_types = 1);
namespace Poker\Ccp\classes\model;
use Poker\Ccp\Entity\GameTypes;
class GameType extends Base {
    private GameTypes $gameTypes;
    public function createFromEntity(bool $debug, GameTypes $gameTypes): GameType {
        $this->gameTypes = $gameTypes;
        return $this->create(debug: $debug, id: $gameTypes->getGameTypeId(), name: $gameTypes->getGameTypeName());
    }
    public function __construct(protected bool $debug, protected string|int $id, protected string $name) {
        parent::__construct(debug: $debug, id: $id);
    }
    private function create(bool $debug, string|int $id, string $name): GameType {
        parent::__construct(debug: $debug, id: $id);
        $this->name = $name;
        return $this;
    }
    public function getName(): string {
        return $this->name;
    }
    public function getGameTypes(): GameTypes {
        return $this->gameTypes;
    }
    public function setName(string $name): GameType {
        $this->name = $name;
        return $this;
    }
    public function setGameTypes(GameTypes $gameTypes): GameType {
        $this->gameTypes = $gameTypes;
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
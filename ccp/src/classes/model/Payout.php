<?php
declare(strict_types = 1);
namespace Poker\Ccp\classes\model;

use Poker\Ccp\Entity\Payouts;

class Payout extends Base {
    private Payouts $payouts;
    public function createFromEntity(bool $debug, Payouts $payouts): Payout {
        $this->payouts = $payouts;
        return $this->create(debug: $debug, id: $payouts->getPayoutId(), name: $payouts->getPayoutName(), minPlayers: $payouts->getPayoutMinPlayers(), maxPlayers: $payouts->getPayoutMaxPlayers(), structures: $payouts->getStructures()->toArray());
    }
    public function __construct(protected bool $debug, protected string|int|NULL $id, protected string $name, protected int $minPlayers, protected int $maxPlayers, protected ?array $structures) {
        parent::__construct(debug: $debug, id: $id);
    }
    private function create(bool $debug, string|int|NULL $id, string $name, int $minPlayers, int $maxPlayers, ?array $structures): Payout {
        parent::__construct(debug: $debug, id: $id);
        $this->minPlayers = $minPlayers;
        $this->maxPlayers = $maxPlayers;
        $this->structures = $structures;
        return $this;
    }
    public function getName(): string {
        return $this->name;
    }
    public function getMinPlayers(): int {
        return $this->minPlayers;
    }
    public function getMaxPlayers(): int {
        return $this->maxPlayers;
    }
    public function getStructures(): array {
        return $this->structures;
    }
    public function getPayouts(): Payouts {
        return $this->payouts;
    }
    public function setName(string $name): Payout {
        $this->name = $name;
        return $this;
    }
    public function setMinPlayers(int $minPlayers): Payout {
        $this->minPlayers = $minPlayers;
        return $this;
    }
    public function setMaxPlayers(int $maxPlayers): Payout {
        $this->maxPlayers = $maxPlayers;
        return $this;
    }
    public function setStructures(?array $structures): Payout {
        $this->structures = $structures;
        return $this;
    }
    public function setPayouts(Payouts $payouts): Payout {
        $this->payouts = $payouts;
        return $this;
    }
    public function __toString(): string {
        $output = parent::__toString();
        $output .= ", name = '";
        $output .= $this->name;
        $output .= "', minPlayers = ";
        $output .= $this->minPlayers;
        $output .= ", maxPlayers = ";
        $output .= $this->maxPlayers;
        $output .= ", structures = [";
        foreach ($this->structures as $structure) {
            $output .= "[";
            $output .= $structure;
            $output .= "],";
        }
        $output = substr(string: $output, offset: 0, length: strlen(string: $output) - 1) . "]";
        return $output;
    }
}
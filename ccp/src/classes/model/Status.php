<?php
declare(strict_types = 1);
namespace Poker\Ccp\classes\model;
use Exception;
use Poker\Ccp\Entity\StatusCodes;
class Status extends Base {
    private const codeList = array("P" => "Paid","R" => "Registered","F" => "Finished");
    private StatusCodes $statusCodes;
    public function createFromEntity(bool $debug, StatusCodes $statusCodes): Status {
        $this->statusCodes = $statusCodes;
        return $this->create(debug: $debug, id: NULL, code: $statusCodes->getStatusCode(), name: $statusCodes->getStatusCodeName());
    }
    public function __construct(protected bool $debug, protected string|int|NULL $id, protected string $code, protected string $name) {
        parent::__construct(debug: $debug, id: $id);
    }
    private function create(bool $debug, string|int|NULL $id, string $code, string $name): Status {
        parent::__construct(debug: $debug, id: $id);
        $this->code = $code;
        $this->name = $name;
        return $this;
    }
    public function getCode(): string {
        return $this->code;
    }
    public function getName(): string {
        return $this->name;
    }
    public function getDescription(): string {
        return self::codeList[$this->code];
    }
    public function getStatusCodes(): StatusCodes {
        return $this->statusCodes;
    }
    public function setCode(string $code): Status {
        if (array_key_exists(key: $code, array: self::codeList)) {
          $this->code = $code;
          return $this;
        } else {
          throw new Exception(message: $code . " is not a valid status code");
        }
    }
    public function setName(string $name): Status {
        $this->name = $name;
        return $this;
    }
    public function setStatusCodes(StatusCodes $statusCodes): Status {
        $this->statusCodes = $statusCodes;
        return $this;
    }
    public function __toString(): string {
        $output = parent::__toString();
        $output .= ", code = '";
        $output .= $this->code;
        $output .= "', name = '";
        $output .= $this->name;
        $output .= "', description = '";
        $output .= $this->description;
        $output .= "'";
        return $output;
    }
}
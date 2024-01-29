<?php
declare(strict_types = 1);
namespace Poker\Ccp\classes\model;

use \DateTime;
use Poker\Ccp\Entity\Players;

class Player extends Base {
    private string $firstName;
    private string $lastName;
    private int $idPrevious;
    private Players $players;
    public function createFromEntity(bool $debug, Players $players): Player {
        $this->idPrevious = $players->getPlayerIdPrevious();
        $this->players = $players;
        $phone = new Phone(debug: $debug, id: NULL, value: $players->getPlayerPhone());
        return $this->create(debug: $debug, id: $players->getPlayerId(), name: $players->getPlayerName(), username: $players->getPlayerUsername(), password: $players->getPlayerPassword(), email: $players->getPlayerEmail(), phone: $phone, administrator: $players->getPlayerAdministratorFlag(), registrationDate: $players->getPlayerRegistrationDate(), approvalDate: $players->getPlayerApprovalDate(), approvalUserid: $players->getPlayerApproval()?->getPlayerId(), approvalName: $players->getPlayerApproval()?->getPlayerName(), rejectionDate: $players->getPlayerRejectionDate(), rejectionUserid: $players->getPlayerRejection()?->getPlayerId(), rejectionName: $players->getPlayerRejection()?->getPlayerName(), active: $players->getPlayerActiveFlag(), resetSelector: NULL, resetToken: NULL, resetExpires: NULL, rememberSelector: NULL, rememberToken: NULL, rememberExpires: NULL);
    }
    public function __construct(protected bool $debug, protected string|int $id, protected string $name, protected ?string $username, protected ?string $password, protected ?string $email, protected ?Phone $phone, protected string $administrator, protected ?DateTime $registrationDate, protected ?DateTime $approvalDate, protected ?int $approvalUserid, protected ?string $approvalName, protected ?DateTime $rejectionDate, protected ?int $rejectionUserid, protected ?string $rejectionName, protected string $active, protected $resetSelector, protected $resetToken, protected $resetExpires, protected $rememberSelector, protected $rememberToken, protected $rememberExpires) {
        return $this->create(debug: $debug, id: $id, name: $name, username: $username, password: $password, email: $email, phone: $phone, administrator: $administrator, registrationDate: $registrationDate, approvalDate: $approvalDate, approvalUserid: $approvalUserid, approvalName: $approvalName, rejectionDate: $rejectionDate, rejectionUserid: $rejectionUserid, rejectionName: $rejectionName, active: $active, resetSelector: $resetSelector, resetToken: $resetToken, resetExpires: $resetExpires, rememberSelector: $rememberSelector, rememberToken: $rememberToken, rememberExpires: $rememberExpires);
    }
    private function create(bool $debug, string|int $id, string $name, ?string $username, ?string $password, ?string $email, ?Phone $phone, string $administrator, DateTime $registrationDate, ?DateTime $approvalDate, ?int $approvalUserid, ?string $approvalName, ?DateTime $rejectionDate, ?int $rejectionUserid, ?string $rejectionName, string $active, $resetSelector, $resetToken, $resetExpires, $rememberSelector, $rememberToken, $rememberExpires): Player {
        parent::__construct(debug: $debug, id: $id);
        $nameFull = explode(separator: " ", string: $name);
        $this->setFirstName(firstName: $nameFull[0]);
        $this->setLastName(lastName: implode(separator: " ", array: array_slice(array: $nameFull, offset: 1)));
        $this->idPrevious = 0;
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
        $this->phone = $phone;
        $this->administrator = $administrator;
        $this->registrationDate = $registrationDate;
        $this->approvalDate = $approvalDate;
        $this->approvalUserid = $approvalUserid;
        $this->approvalName = $approvalName;
        $this->rejectionDate = $rejectionDate;
        $this->rejectionUserid = $rejectionUserid;
        $this->rejectionName = $rejectionName;
        $this->active = $active;
        $this->resetSelector = $resetSelector;
        $this->resetToken = $resetToken;
        $this->resetExpires = $resetExpires;
        $this->rememberSelector = $rememberSelector;
        $this->rememberToken = $rememberToken;
        $this->rememberExpires = $rememberExpires;
        return $this;
    }
    public function getFirstName(): string {
        return $this->firstName;
    }
    public function getLastName(): string {
        return $this->lastName;
    }
    public function getEmail(): ?string {
        return $this->email;
    }
    public function getPhone(): ?Phone {
        return $this->phone;
    }
    public function getUsername(): ?string {
        return $this->username;
    }
    public function getPassword(): ?string {
        return $this->password;
    }
    public function getAdministrator(): string {
        return $this->administrator;
    }
    public function getRegistrationDate(): ?DateTime {
        return $this->registrationDate;
    }
    public function getApprovalDate(): ?DateTime {
        return $this->approvalDate;
    }
    public function getApprovalUserid(): ?int {
        return $this->approvalUserid;
    }
    public function getApprovalName(): ?string {
        return $this->approvalName;
    }
    public function getRejectionDate(): ?DateTime {
        return $this->rejectionDate;
    }
    public function getRejectionUserid(): ?int {
        return $this->rejectionUserid;
    }
    public function getRejectionName(): ?string {
        return $this->rejectionName;
    }
    public function getActive(): string {
        return $this->active;
    }
    public function getResetSelector() {
        return $this->resetSelector;
    }
    public function getResetToken() {
        return $this->resetToken;
    }
    public function getResetExpires() {
        return $this->resetExpires;
    }
    public function getRememberSelector() {
        return $this->rememberSelector;
    }
    public function getRememberToken() {
        return $this->rememberToken;
    }
    public function getRememberExpires() {
        return $this->rememberExpires;
    }
    public function getName(): string {
        return $this->firstName . (isset($this->lastName) ? (" " . $this->lastName) : "");
    }
    public function getIdPrevious(): int {
        return $this->idPrevious;
    }
    public function getPlayers(): Players {
        return $this->players;
    }
    public function setFirstName(string $firstName): Player {
        $this->firstName = $firstName;
        return $this;
    }
    public function setLastName(string $lastName): Player {
        $this->lastName = $lastName;
        return $this;
    }
    public function setEmail(string $email): Player {
        $this->email = $email;
        return $this;
    }
    public function setPhone(?Phone $phone): Player {
        $this->phone = $phone;
        return $this;
    }
    public function setUsername(string $username): Player {
        $this->username = $username;
        return $this;
    }
    public function setPassword(string $password): Player {
        $this->password = $password;
        return $this;
    }
    public function setAdministrator(string $administrator): Player {
        $this->administrator = $administrator;
        return $this;
    }
    public function setRegistrationDate(DateTime $registrationDate): Player {
        $this->registrationDate = $registrationDate;
        return $this;
    }
    public function setApprovalDate(?DateTime $approvalDate): Player {
        $this->approvalDate = $approvalDate;
        return $this;
    }
    public function setApprovalUserid(?int $approvalUserid): Player {
        $this->approvalUserid = $approvalUserid;
        return $this;
    }
    public function setApprovalName(?string $approvalName): Player {
        $this->approvalName = $approvalName;
        return $this;
    }
    public function setRejectionDate(?DateTime $rejectionDate): Player {
        $this->rejectionDate = $rejectionDate;
        return $this;
    }
    public function setRejectionUserid(?int $rejectionUserid): Player {
        $this->rejectionUserid = $rejectionUserid;
        return $this;
    }
    public function setRejectionName(?string $rejectionName): Player {
        $this->rejectionName = $rejectionName;
        return $this;
    }
    public function setActive(string $active): Player {
        $this->active = $active;
        return $this;
    }
    public function setResetSelector($resetSelector): Player {
        $this->resetSelector = $resetSelector;
        return $this;
    }
    public function setResetToken($resetToken): Player {
        $this->resetToken = $resetToken;
        return $this;
    }
    public function setResetExpires($resetExpires): Player {
        $this->resetExpires = $resetExpires;
        return $this;
    }
    public function setRememberSelector($rememberSelector): Player {
        $this->rememberSelector = $rememberSelector;
        return $this;
    }
    public function setRememberToken($rememberToken): Player {
        $this->rememberToken = $rememberToken;
        return $this;
    }
    public function setRememberExpires($rememberExpires): Player {
        $this->rememberExpires = $rememberExpires;
        return $this;
    }
    public function setIdPrevious(int $idPrevious): Player {
        $this->idPrevious = $idPrevious;
        return $this;
    }
    public function setName(string $name): Player {
        $names = explode(separator: " ", string: $name);
        $this->firstName = $names[0];
        if (1 < count(value: $names)) {
            $this->lastName = implode(separator: " ", array: array_slice(array: $names, offset: 1));
        }
        return $this;
    }
    public function setPlayers(Players $players): Player {
        $this->players = $players;
        return $this;
    }
    public function __toString(): string {
        $output = parent::__toString();
        $output .= ", firstName = '";
        $output .= $this->firstName;
        $output .= "', lastName = '";
        $output .= $this->lastName;
        $output .= "', email = '";
        $output .= $this->email;
        $output .= "', phone = [";
        $output .= NULL !== $this->phone ? $this->phone->__toString() : "";
        $output .= "], username = '";
        $output .= $this->username;
        $output .= "', password = '";
        $output .= $this->password;
        $output .= "', administrator = '";
        $output .= $this->administrator;
        $output .= "', registrationDate = '";
        $output .= NULL !== $this->registrationDate ? $this->registrationDate->format("m/d/Y") : "";
        $output .= "', approvalDate = '";
        $output .= NULL !== $this->approvalDate ? $this->approvalDate->format("m/d/Y") : "";
        $output .= "', approvalUserId = '";
        $output .= $this->approvalUserid;
        $output .= "', approvalName = '";
        $output .= $this->approvalName;
        $output .= "', rejectionDate = '";
        $output .= NULL !== $this->rejectionDate ? $this->rejectionDate->format("m/d/Y") : "";
        $output .= "', rejectionUserId = '";
        $output .= $this->rejectionUserid;
        $output .= "', rejectionName = '";
        $output .= $this->rejectionName;
        $output .= "', active = '";
        $output .= $this->active;
        $output .= "', idPrevious = ";
        $output .= $this->idPrevious;
        $output .= ", resetSelector = '";
        $output .= $this->resetSelector;
        $output .= "', resetToken = '";
        $output .= $this->resetToken;
        $output .= "', resetExpires = '";
        $output .= $this->resetExpires;
        $output .= "', rememberSelector = '";
        $output .= $this->rememberSelector;
        $output .= "', rememberToken = '";
        $output .= $this->rememberToken;
        $output .= "', rememberExpires = '";
        $output .= $this->rememberExpires;
        $output .= "'";
        return $output;
    }
}
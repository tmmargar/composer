<?php
declare(strict_types = 1);
namespace Poker\Ccp\classes\model;
class Player extends Base {
  private string $firstName;
  private string $lastName;
  private int $idPrevious;
  public function __construct(protected bool $debug, protected string|int|NULL $id, protected string $name, protected string|NULL $username, protected string|NULL $password,
    protected string|NULL $email, protected Phone|NULL $phone, protected int $administrator, protected string|NULL $registrationDate, protected string|NULL $approvalDate,
    protected int|NULL $approvalUserid, protected string|NULL $approvalName, protected string|NULL $rejectionDate, protected int|NULL $rejectionUserid, protected string|NULL $rejectionName,
    protected int $active, protected Address|NULL $address, protected $resetSelector, protected $resetToken, protected $resetExpires, protected $rememberSelector, protected $rememberToken,
    protected $rememberExpires) {
    parent::__construct(debug: $debug, id: $id);
    $nameFull = explode(separator: " ", string: $name);
    $this->setFirstName(firstName: $nameFull[0]);
    $this->setLastName(lastName: implode(separator: " ", array: array_slice(array: $nameFull, offset: 1)));
    $this->idPrevious = 0;
  }
  public function getFirstName(): string {
    return $this->firstName;
  }
  public function getLastName(): string {
    return $this->lastName;
  }
  public function getEmail(): string|NULL {
    return $this->email;
  }
  public function getPhone(): Phone|NULL {
    return $this->phone;
  }
  public function getUsername(): string|NULL {
    return $this->username;
  }
  public function getPassword(): string|NULL {
    return $this->password;
  }
  public function getAdministrator(): int {
    return $this->administrator;
  }
  public function getRegistrationDate(): string|NULL {
    return $this->registrationDate;
  }
  public function getApprovalDate(): string|NULL {
    return $this->approvalDate;
  }
  public function getApprovalUserid(): int|NULL {
    return $this->approvalUserid;
  }
  public function getApprovalName(): string|NULL {
    return $this->approvalName;
  }
  public function getRejectionDate(): string|NULL {
    return $this->rejectionDate;
  }
  public function getRejectionUserid(): int|NULL {
    return $this->rejectionUserid;
  }
  public function getRejectionName(): string|NULL {
    return $this->rejectionName;
  }
  public function getActive(): int {
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
  public function getAddress(): Address|NULL {
    return $this->address;
  }
  public function getName(): string {
    return $this->firstName . (isset($this->lastName) ? (" " . $this->lastName) : "");
  }
  public function getIdPrevious(): int {
    return $this->idPrevious;
  }
  public function setFirstName(string $firstName) {
    $this->firstName = $firstName;
    return $this;
  }
  public function setLastName(string $lastName) {
    $this->lastName = $lastName;
    return $this;
  }
  public function setEmail(string $email) {
    $this->email = $email;
    return $this;
  }
  public function setPhone(Phone|NULL $phone) {
    $this->phone = $phone;
    return $this;
  }
  public function setUsername(string $username) {
    $this->username = $username;
    return $this;
  }
  public function setPassword(string $password) {
    $this->password = $password;
    return $this;
  }
  public function setAdministrator(int $administrator) {
    $this->administrator = $administrator;
    return $this;
  }
  public function setRegistrationDate(string $registrationDate) {
    $this->registrationDate = $registrationDate;
    return $this;
  }
  public function setApprovalDate(string $approvalDate) {
    $this->approvalDate = $approvalDate;
    return $this;
  }
  public function setApprovalUserid(int $approvalUserid) {
    $this->approvalUserid = $approvalUserid;
    return $this;
  }
  public function setApprovalName(string $approvalName) {
    $this->approvalName = $approvalName;
    return $this;
  }
  public function setRejectionDate(string $rejectionDate) {
    $this->rejectionDate = $rejectionDate;
    return $this;
  }
  public function setRejectionUserid(int $rejectionUserid) {
    $this->rejectionUserid = $rejectionUserid;
    return $this;
  }
  public function setRejectionName(string $rejectionName) {
    $this->rejectionName = $rejectionName;
    return $this;
  }
  public function setActive(int $active) {
    $this->active = $active;
    return $this;
  }
  public function setResetSelector($resetSelector) {
    $this->resetSelector = $resetSelector;
    return $this;
  }
  public function setResetToken($resetToken) {
    $this->resetToken = $resetToken;
    return $this;
  }
  public function setResetExpires($resetExpires) {
    $this->resetExpires = $resetExpires;
    return $this;
  }
  public function setRememberSelector($rememberSelector) {
    $this->rememberSelector = $rememberSelector;
    return $this;
  }
  public function setRememberToken($rememberToken) {
    $this->rememberToken = $rememberToken;
    return $this;
  }
  public function setRememberExpires($rememberExpires) {
    $this->rememberExpires = $rememberExpires;
    return $this;
  }
  public function setAddress(Address $address) {
    $this->address = $address;
    return $this;
  }
  public function setIdPrevious(int $idPrevious) {
    $this->idPrevious = $idPrevious;
    return $this;
  }
  public function setName($name) {
    $names = explode(separator: " ", string: $name);
    $this->firstName = $names[0];
    if (1 < count(value: $names)) {
      $this->lastName = implode(separator: " ", array: array_slice(array: $names, offset: 1));
    }
    return $this;
  }
  public function getLink(): string {
    $link = new HtmlLink(accessKey: NULL, class: NULL, debugP: $this->isDebug(), href: "managePlayer.php", id: NULL, paramName: array("id","mode"), paramValue: array($this->getId() . "modify"),
      tabIndex: - 1, text: $this->getName(), title: NULL);
    return $link->getHtml();
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
    $output .= $this->registrationDate;
    $output .= "', approvalDate = '";
    $output .= $this->approvalDate;
    $output .= "', approvalUserId = '";
    $output .= $this->approvalUserid;
    $output .= "', approvalName = '";
    $output .= $this->approvalName;
    $output .= "', rejectionDate = '";
    $output .= $this->rejectionDate;
    $output .= "', rejectionUserId = '";
    $output .= $this->rejectionUserid;
    $output .= "', rejectionName = '";
    $output .= $this->rejectionName;
    $output .= "', active = '";
    $output .= $this->active;
    $output .= "', address = [";
    $output .= NULL !== $this->address ? $this->address->__toString() : "";
    $output .= "], idPrevious = ";
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
<?php
declare(strict_types = 1);
namespace Poker\Ccp\classes\model;
class Result extends Base {
  public function __construct(protected bool $debug, protected string|int|NULL $id, protected Tournament $tournament, protected User $user, protected Status $status, protected int $registerOrder,
    protected bool $buyinPaid, protected bool $rebuyPaid, protected bool $addonPaid, protected int $rebuyCount, protected bool $addonFlag, protected int $place, protected User $knockedOutBy,
    protected string|NULL $food, protected string|NULL $feeStatus) {
    parent::__construct(debug: $debug, id: $id);
  }
  public function getTournament(): Tournament {
    return $this->tournament;
  }
  public function getUser(): User {
    return $this->user;
  }
  public function getStatus(): Status {
    return $this->status;
  }
  public function getRegisterOrder(): int {
    return $this->registerOrder;
  }
  public function isBuyinPaid(): bool {
    return $this->buyinPaid;
  }
  public function isRebuyPaid(): bool {
    return $this->rebuyPaid;
  }
  public function isAddonPaid(): bool {
    return $this->addonPaid;
  }
  public function getRebuyCount(): int {
    return $this->rebuyCount;
  }
  public function isAddonFlag(): bool {
    return $this->addonFlag;
  }
  public function getPlace(): int {
    return $this->place;
  }
  public function getKnockedOutBy(): User {
    return $this->knockedOutBy;
  }
  public function getFood(): string {
    return $this->food;
  }
  public function getFeeStatus(): string {
    return $this->feeStatus;
  }
  public function setTournament(Tournament $tournament) {
    $this->tournament = $tournament;
  }
  public function setUser(User $user) {
    $this->user = $user;
  }
  public function setStatus(Status $status) {
    $this->status = $status;
  }
  public function setRegisterOrder(int $registerOrder) {
    $this->registerOrder = $registerOrder;
  }
  public function setBuyinPaid(bool $buyinPaid) {
    $this->buyinPaid = $buyinPaid;
  }
  public function setRebuyPaid(bool $rebuyPaid) {
    $this->rebuyPaid = $rebuyPaid;
  }
  public function setAddonPaid(bool $addonPaid) {
    $this->addonPaid = $addonPaid;
  }
  public function setRebuyCount(int $rebuyCount) {
    $this->rebuyCount = $rebuyCount;
  }
  public function setAddonFlag(bool $addonFlag) {
    $this->addonFlag = $addonFlag;
  }
  public function setPlace(int $place) {
    $this->place = $place;
  }
  public function setKnockedOutBy(User $knockedOutBy) {
    $this->knockedOutBy = $knockedOutBy;
  }
  public function setFood(string $food) {
    $this->food = $food;
  }
  public function setFeeStatus(string $feeStatus) {
    $this->feeStatus = $feeStatus;
  }
  public function getLink(): string {
    $link = new HtmlLink(accessKey: NULL, class: NULL, debug: $this->isDebug(), href: "manageResult.php", id: NULL, paramName: array("id","mode"), paramValue: array($this->getId() . "modify"),
      tabIndex: - 1, text: $this->getTournament()->getDescription(), title: NULL);
    return $link->getHtml();
  }
  public function __toString(): string {
    $output = parent::__toString();
    $output .= "tournament = [";
    $output .= $this->tournament;
    $output .= "], user = [";
    $output .= $this->user;
    $output .= "], status = [";
    $output .= $this->status;
    $output .= "], registerOrder = ";
    $output .= $this->registerOrder;
    $output .= ", buyinPaid = ";
    $output .= var_export(value: $this->buyinPaid, return: true);
    $output .= ", rebuyPaid = ";
    $output .= var_export(value: $this->rebuyPaid, return: true);
    $output .= ", addonPaid = ";
    $output .= var_export(value: $this->addonPaid, return: true);
    $output .= ", rebuyCount = ";
    $output .= $this->rebuyCount;
    $output .= ", addonFlag = ";
    $output .= var_export(value: $this->addonFlag, return: true);
    $output .= ", place = ";
    $output .= $this->place;
    $output .= ", knockedOutBy = [";
    $output .= $this->knockedOutBy;
    $output .= "], food = '";
    $output .= $this->food;
    $output .= "', feeStatus = '";
    $output .= $this->feeStatus;
    $output .= "'";
    return $output;
  }
}
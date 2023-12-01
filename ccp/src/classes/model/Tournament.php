<?php
declare(strict_types = 1);
namespace Poker\Ccp\classes\model;
class Tournament extends Base {
  public function __construct(protected bool $debug, protected string|int|NULL $id, protected string|NULL $description, protected string|NULL $comment, protected LimitType|NULL $limitType,
    protected GameType|NULL $gameType, protected SpecialType|NULL $specialType, protected int $chipCount, protected Location|NULL $location, protected DateTime|NULL $date,
    protected DateTime|NULL $startTime, protected int $buyinAmount, protected int $maxPlayers, protected int $maxRebuys, protected int $rebuyAmount,
    protected int $addonAmount, protected int $addonChipCount, protected GroupPayout|NULL $groupPayout, protected float $rake, protected int $registeredCount, protected int $buyinsPaid,
    protected int $rebuysPaid, protected int $rebuysCount, protected int $addonsPaid, protected int $enteredCount, protected int $earnings = 0) {
    parent::__construct(debug: $debug, id: $id);
  }
  public function getDescription(): string|NULL {
    return $this->description;
  }
  public function getComment(): string|NULL {
    return $this->comment;
  }
  public function getLimitType(): LimitType|NULL {
    return $this->limitType;
  }
  public function getGameType(): GameType|NULL {
    return $this->gameType;
  }
  public function getSpecialType(): SpecialType|NULL {
    return $this->specialType;
  }
  public function getChipCount(): int {
    return $this->chipCount;
  }
  public function getLocation(): Location|NULL {
    return $this->location;
  }
  public function getDate(): DateTime|NULL {
    return $this->date;
  }
  public function getStartTime(): DateTime|NULL {
    return $this->startTime;
  }
  public function getBuyinAmount(): int {
    return $this->buyinAmount;
  }
  public function getMaxPlayers(): int {
    return $this->maxPlayers;
  }
  public function getMaxRebuys(): int {
    return $this->maxRebuys;
  }
  public function getRebuyAmount(): int {
    return $this->rebuyAmount;
  }
  public function getAddonAmount(): int {
    return $this->addonAmount;
  }
  public function getAddonChipCount(): int {
    return $this->addonChipCount;
  }
  public function getGroupPayout(): GroupPayout|NULL {
    return $this->groupPayout;
  }
  public function getRake(): float {
    return $this->rake;
  }
  public function getRakeForCalculation(): float {
    return $this->rake / 100;
  }
  public function getRegistrationClose(): DateTime {
    $close = "";
    if (isset($this->startTime)) {
      $close = clone $this->startTime;
      $interval = new \DateInterval(duration: "PT2H"); // 2 hours
      $close->getTime()->sub($interval);
    }
    return $close;
  }
  public function getRegistrationOpen(): DateTime {
    return new DateTime(debug: $this->isDebug(), id: NULL, time: "12:00");
  }
  public function getRegisteredCount(): int {
    return $this->registeredCount;
  }
  public function getBuyinsPaid(): int {
    return $this->buyinsPaid;
  }
  public function getRebuysPaid(): int {
    return $this->rebuysPaid;
  }
  public function getRebuysCount(): int {
    return $this->rebuysCount;
  }
  public function getAddonsPaid(): int {
    return $this->addonsPaid;
  }
  public function getEnteredCount(): int {
    return $this->enteredCount;
  }
  public function getEarnings(): int {
    return $this->earnings;
  }
  public function setDescription(string $description) {
    $this->description = $description;
    return $this;
  }
  public function setComment(string $comment) {
    $this->comment = $comment;
    return $this;
  }
  public function setLimitType(LimitType $limitType) {
    $this->limitType = $limitType;
    return $this;
  }
  public function setGameType(GameType $gameType) {
    $this->gameType = $gameType;
    return $this;
  }
  public function setSpecialType(SpecialType $specialType) {
    $this->specialType = $specialType;
    return $this;
  }
  public function setChipCount(int $chipCount) {
    $this->chipCount = $chipCount;
    return $this;
  }
  public function setLocation(Location $location) {
    $this->location = $location;
    return $this;
  }
  public function setDate(DateTime $date) {
    $this->date = $date;
    return $this;
  }
  public function setStartTime(DateTime $startTime) {
    $this->startTime = $startTime;
    return $this;
  }
  public function setBuyinAmount(int $buyinAmount) {
    $this->buyinAmount = $buyinAmount;
    return $this;
  }
  public function setMaxPlayers(int $maxPlayers) {
    $this->maxPlayers = $maxPlayers;
    return $this;
  }
  public function setMaxRebuys(int $maxRebuys) {
    $this->maxRebuys = $maxRebuys;
    return $this;
  }
  public function setRebuyAmount(int $rebuyAmount) {
    $this->rebuyAmount = $rebuyAmount;
    return $this;
  }
  public function setAddonAmount(int $addonAmount) {
    $this->addonAmount = $addonAmount;
    return $this;
  }
  public function setAddonChipCount(int $addonChipCount) {
    $this->addonChipCount = $addonChipCount;
    return $this;
  }
  public function setGroupPayout(GroupPayout $groupPayout) {
    $this->groupPayout = $groupPayout;
    return $this;
  }
  public function setRake(float $rake) {
    $this->rake = $rake;
    return $this;
  }
  public function setRegisteredCount(int $registeredCount) {
    $this->registeredCount = $registeredCount;
    return $this;
  }
  public function setEnteredCount(int $enteredCount) {
    $this->enteredCount = $enteredCount;
    return $this;
  }
  public function setBuyinsPaid(int $buyinsPaid) {
    $this->buyinsPaid = $buyinsPaid;
    return $this;
  }
  public function setRebuysPaid(int $rebuysPaid) {
    $this->rebuysPaid = $rebuysPaid;
    return $this;
  }
  public function setRebuysCount(int $rebuysCount) {
    $this->rebuysCount = $rebuysCount;
    return $this;
  }
  public function setAddonsPaid(int $addonsPaid) {
    $this->addonsPaid = $addonsPaid;
    return $this;
  }
  public function setEarnings(int $earnings) {
    $this->earnings = $earnings;
    return $this;
  }
  public function getLink() {
    $link = new HtmlLink(accessKey: NULL, class: NULL, debug: $this->isDebug(), href: "manageTournament.php", id: NULL, paramName: array("id","mode"), paramValue: array($this->getId() . "modify"),
      tabIndex: - 1, text: $this->getDescription(), title: NULL);
    return $link->getHtml();
  }
  public function getDateAndTime() {
    return new DateTime(debug: $this->isDebug(), id: NULL, time: $this->getDate()->getDatabaseFormat() . " " . $this->getStartTime()->getDatabaseTimeFormat());
  }
  public function getDisplayDetails() {
    $optionText = $this->getDate()->getDisplayFormat();
    $optionText .= "@" . $this->getStartTime()->getDisplayAmPmFormat();
    $optionText .= " (" . $this->getLocation()->getName() . ")";
    $optionText .= " " . $this->getLimitType()->getName();
    $optionText .= " " . $this->getGameType()->getName();
    $optionText .= " " . $this->getMaxRebuys() . "r" . (0 != $this->getAddonAmount() ? "+a" : "");
    $waitListCnt = $this->getRegisteredCount() - $this->getMaxPlayers();
    $optionText .= " (" . ($waitListCnt > 0 ? $this->getMaxPlayers() : $this->getRegisteredCount()) . ($waitListCnt > 0 ? "+" . $waitListCnt . "wl" : "") . "np/" . $this->getBuyinsPaid() . "p";
    $optionText .= "+" . $this->getRebuysPaid() . "rp";
    $optionText .= "+" . $this->getAddonsPaid() . "ap";
    $optionText .= "/" . $this->getEnteredCount() . "ent)";
    return $optionText;
  }
  public function __toString(): string {
    $output = parent::__toString();
    $output .= ", description = '";
    $output .= $this->description;
    $output .= "', comment = '";
    $output .= $this->comment;
    $output .= "', limitType = [";
    $output .= $this->limitType;
    $output .= "], gameType = [";
    $output .= $this->gameType;
    $output .= "], specialType = [";
    $output .= $this->specialType;
    $output .= "], chipCount = ";
    $output .= $this->chipCount;
    $output .= ", location = [";
    $output .= $this->location;
    $output .= "], date = ";
    $output .= $this->date->getDisplayFormat();
    $output .= ", startTime = ";
    $output .= $this->startTime->getDisplayAmPmFormat();
    $output .= ", buyinAmount = ";
    $output .= $this->buyinAmount;
    $output .= ", maxPlayers = ";
    $output .= $this->maxPlayers;
    $output .= ", maxRebuys = ";
    $output .= $this->maxRebuys;
    $output .= ", rebuyAmount = ";
    $output .= $this->rebuyAmount;
    $output .= ", addonAmount = ";
    $output .= $this->addonAmount;
    $output .= ", addonChipCount = ";
    $output .= $this->addonChipCount;
    $output .= ", groupPayout = [";
    $output .= $this->groupPayout;
    $output .= "], rake = ";
    $output .= $this->rake;
    $output .= ", registrationClose = ";
    $output .= $this->getRegistrationClose()->getDisplayAmPmFormat();
    $output .= "', registeredCount = ";
    $output .= $this->registeredCount;
    $output .= ", enteredCount = ";
    $output .= $this->enteredCount;
    $output .= ", buyinsPaid = ";
    $output .= $this->buyinsPaid;
    $output .= ", rebuysPaid = ";
    $output .= $this->rebuysPaid;
    $output .= ", rebuysCount = ";
    $output .= $this->rebuysCount;
    $output .= ", addonsPaid = ";
    $output .= $this->addonsPaid;
    $output .= ", earnings = ";
    $output .= $this->earnings;
    return $output;
  }
}
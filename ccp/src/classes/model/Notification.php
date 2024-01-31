<?php
declare(strict_types = 1);
namespace Poker\Ccp\classes\model;
use Poker\Ccp\Entity\Notifications;
class Notification extends Base {
    private Notifications $notifications;
    public function createFromEntity(bool $debug, Notifications $notifications): Notification {
        $this->notifications = $notifications;
        return $this->create(debug: $debug, id: $notifications->getNotificationId(), description: $notifications->getNotificationDescription(), startDate: $notifications->getNotificationStartDate(), endDate: $notifications->getNotificationEndDate());
    }
    public function __construct(protected bool $debug, protected string|int $id, protected string $description, protected DateTime $startDate, protected DateTime $endDate) {
        parent::__construct(debug: $debug, id: $id);
    }
    private function create(bool $debug, string|int $id, string $description, DateTime $startDate, DateTime $endDate): Notification {
        parent::__construct(debug: $debug, id: $id);
        $this->description = $description;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        return $this;
    }
    public function getDescription(): string {
        return $this->description;
    }
    public function getEndDate(): DateTime {
        return $this->endDate;
    }
    public function getStartDate(): DateTime {
        return $this->startDate;
    }
    public function getNotifications(): Notification {
        return $this->notifications;
    }
    public function setDescription(string $description): Notification {
        $this->description = $description;
        return $this;
    }
    public function setEndDate(DateTime $endDate): Notification {
        $this->endDate = $endDate;
        return $this;
    }
    public function setStartDate(DateTime $startDate): Notification {
        $this->startDate = $startDate;
        return $this;
    }
    public function setNotifications(Notifications $notifications): Notification {
        $this->notifications = $notifications;
        return $this;
    }
    public function __toString(): string {
        $output = parent::__toString();
        $output .= "', description = '";
        $output .= $this->description;
        $output .= "', startDate = '";
        $output .= $this->startDate->getDisplayFormat();
        $output .= "', endDate = '";
        $output .= $this->endDate->getDisplayFormat();
        $output .= "'";
        return $output;
    }
}
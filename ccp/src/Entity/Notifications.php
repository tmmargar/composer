<?php
declare(strict_types=1);
namespace Poker\Ccp\Entity;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\CustomIdGenerator;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use DateTime;

#[Table(name: "poker_notifications")]
#[Entity(repositoryClass: NotificationsRepository::class)]
class Notifications
{
    #[Column(name: "notification_id", nullable: false)]
    #[Id]
    #[GeneratedValue(strategy: "CUSTOM")]
    #[CustomIdGenerator(class: NotificationsIdGenerator::class)]
    private int $notificationId;

    #[Column(name: "notification_description", length: 500, nullable: false)]
    private string $notificationDescription;

    #[Column(name: "notification_start_date", nullable: false)]
    private DateTime $notificationStartDate;

    #[Column(name: "notification_end_date", nullable: false)]
    private DateTime $notificationEndDate;
    /**
     * @return number
     */
    public function getNotificationId(): int {
        return $this->notificationId;
    }

    /**
     * @return string
     */
    public function getNotificationDescription(): string {
        return $this->notificationDescription;
    }

    /**
     * @return DateTime
     */
    public function getNotificationStartDate(): DateTime  {
        return $this->notificationStartDate;
    }

    /**
     * @return DateTime
     */
    public function getNotificationEndDate(): DateTime  {
        return $this->notificationEndDate;
    }

    /**
     * @param number $notificationId
     */
    public function setNotificationId(int $notificationId): self {
        $this->notificationId = $notificationId;
        return $this;
    }

    /**
     * @param string $notificationDescription
     */
    public function setNotificationDescription(string $notificationDescription): self {
        $this->notificationDescription = $notificationDescription;
        return $this;
    }

    /**
     * @param DateTime $notificationStartDate
     */
    public function setNotificationStartDate(DateTime $notificationStartDate): self {
        $this->notificationStartDate = $notificationStartDate;
        return $this;
    }

    /**
     * @param DateTime $notificationEndDate
     */
    public function setNotificationEndDate(DateTime $notificationEndDate): self {
        $this->notificationEndDate = $notificationEndDate;
        return $this;
    }

}
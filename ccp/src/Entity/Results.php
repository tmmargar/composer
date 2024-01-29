<?php
declare(strict_types=1);
namespace Poker\Ccp\Entity;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Index;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Table(name: "poker_results")]
#[Index(name: "FK_RESULTS_STATUS_CODES", columns: ["status_code"])]
#[Index(name: "FK_RESULTS_PLAYERS_2", columns: ["player_id_ko"])]
#[Index(name: "FK_RESULTS_PLAYERS", columns: ["player_id"])]
#[Index(columns: ["tournament_id"])]
#[Entity(repositoryClass: ResultsRepository::class)]
class Results
{
    #[Column(name: "result_registration_order", nullable: false)]
    private int $resultRegistrationOrder;

    #[Column(name: "result_paid_buyin_flag", length: 1, nullable: false)]
    private string $resultPaidBuyinFlag;

    #[Column(name: "result_paid_rebuy_flag", length: 1, nullable: false)]
    private string $resultPaidRebuyFlag;

    #[Column(name: "result_paid_addon_flag", length: 1, nullable: false)]
    private string $resultPaidAddonFlag;

    #[Column(name: "result_rebuy_count", nullable: false)]
    private int $resultRebuyCount;

    #[Column(name: "result_addon_flag", length: 1, nullable: false)]
    private string $resultAddonFlag;

    #[Column(name: "result_place_finished", nullable: false)]
    private int $resultPlaceFinished;

    #[Column(name: "result_registration_food", length: 100, nullable: true)]
    private ?string $resultRegistrationFood;

    #[Id]
    #[ManyToOne(targetEntity: Tournaments::class, inversedBy: "tournaments")]
    #[JoinColumn(name: "tournament_id", referencedColumnName: "tournament_id")]
    private Tournaments $tournaments;

    #[Id]
    #[ManyToOne(targetEntity: Players::class, inversedBy: "players")]
    #[JoinColumn(name: "player_id", referencedColumnName: "player_id")]
    private Players $players;

    #[ManyToOne(targetEntity: Players::class, inversedBy: "playerKos")]
    #[JoinColumn(name: "player_id_ko", referencedColumnName: "player_id")]
    private ?Players $playerKos;

    #[ManyToOne(targetEntity: StatusCodes::class, inversedBy: "statusCodes")]
    #[JoinColumn(name: "status_code", referencedColumnName: "status_code")]
    private StatusCodes $statusCodes;
    /**
     * @return number
     */
    public function getResultRegistrationOrder(): int {
        return $this->resultRegistrationOrder;
    }

    /**
     * @return string
     */
    public function getResultPaidBuyinFlag(): string {
        return $this->resultPaidBuyinFlag;
    }

    /**
     * @return string
     */
    public function getResultPaidRebuyFlag(): string {
        return $this->resultPaidRebuyFlag;
    }

    /**
     * @return string
     */
    public function getResultPaidAddonFlag(): string {
        return $this->resultPaidAddonFlag;
    }

    /**
     * @return number
     */
    public function getResultRebuyCount(): int {
        return $this->resultRebuyCount;
    }

    /**
     * @return string
     */
    public function getResultAddonFlag(): string {
        return $this->resultAddonFlag;
    }

    /**
     * @return number
     */
    public function getResultPlaceFinished(): int {
        return $this->resultPlaceFinished;
    }

    /**
     * @return string
     */
    public function getResultRegistrationFood(): ?string {
        return $this->resultRegistrationFood;
    }

    /**
     * @return \Poker\Ccp\Entity\Tournaments
     */
    public function getTournaments(): Tournaments {
        return $this->tournaments;
    }

    /**
     * @return \Poker\Ccp\Entity\Players
     */
    public function getPlayers(): Players {
        return $this->players;
    }

    /**
     * @return \Poker\Ccp\Entity\Players
     */
    public function getPlayerKos(): ?Players {
        return $this->playerKos;
    }

    /**
     * @return \Poker\Ccp\Entity\StatusCodes
     */
    public function getStatusCodes(): StatusCodes {
        return $this->statusCodes;
    }

    /**
     * @param number $resultRegistrationOrder
     */
    public function setResultRegistrationOrder(int $resultRegistrationOrder): self {
        $this->resultRegistrationOrder = $resultRegistrationOrder;
        return $this;
    }

    /**
     * @param string $resultPaidBuyinFlag
     */
    public function setResultPaidBuyinFlag(string $resultPaidBuyinFlag): self {
        $this->resultPaidBuyinFlag = $resultPaidBuyinFlag;
        return $this;
    }

    /**
     * @param string $resultPaidRebuyFlag
     */
    public function setResultPaidRebuyFlag(string $resultPaidRebuyFlag): self {
        $this->resultPaidRebuyFlag = $resultPaidRebuyFlag;
        return $this;
    }

    /**
     * @param string $resultPaidAddonFlag
     */
    public function setResultPaidAddonFlag(string $resultPaidAddonFlag): self {
        $this->resultPaidAddonFlag = $resultPaidAddonFlag;
        return $this;
    }

    /**
     * @param number $resultRebuyCount
     */
    public function setResultRebuyCount(int $resultRebuyCount): self {
        $this->resultRebuyCount = $resultRebuyCount;
        return $this;
    }

    /**
     * @param string $resultAddonFlag
     */
    public function setResultAddonFlag(string $resultAddonFlag): self {
        $this->resultAddonFlag = $resultAddonFlag;
        return $this;
    }

    /**
     * @param number $resultPlaceFinished
     */
    public function setResultPlaceFinished(int $resultPlaceFinished): self {
        $this->resultPlaceFinished = $resultPlaceFinished;
        return $this;
    }

    /**
     * @param string $resultRegistrationFood
     */
    public function setResultRegistrationFood(?string $resultRegistrationFood): self {
        $this->resultRegistrationFood = $resultRegistrationFood;
        return $this;
    }

    /**
     * @param \Poker\Ccp\Entity\Tournaments $tournaments
     */
    public function setTournaments(Tournaments $tournaments): self {
        $this->tournaments = $tournaments;
        return $this;
    }

    /**
     * @param \Poker\Ccp\Entity\Players $players
     */
    public function setPlayers(Players $players): self {
        $this->players = $players;
        return $this;
    }

    /**
     * @param \Poker\Ccp\Entity\Players $playerKos
     */
    public function setPlayerKos(?Players $playerKos): self {
        $this->playerKos = $playerKos;
        return $this;
    }

    /**
     * @param \Poker\Ccp\Entity\StatusCodes $statusCodes
     */
    public function setStatusCodes(StatusCodes $statusCodes): self {
        $this->statusCodes = $statusCodes;
        return $this;
    }

}
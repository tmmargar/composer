<?php
declare(strict_types = 1);
namespace Poker\Ccp\Model;
use DateTime;
use Poker\Ccp\Utility\SessionUtility;
class Security extends Base
{
    private Season $season;

    public function __construct(protected bool $debug, protected string|int|NULL $id, protected Login $login, protected Player $player) {
        parent::__construct(debug: $debug, id: $id);
    }

    public function getLogin(): Login {
        return $this->login;
    }

    public function getSeason(): Season {
        return $this->season;
    }

    public function getPlayer(): Player {
        return $this->player;
    }

    public function login(): bool {
        if ($this->validatePassword()) {
            $this->loginSuccess();
            return true;
        } else {
            return false;
        }
    }

    private function loginSuccess() {
        $entityManager = getEntityManager();
        $players = $entityManager->getRepository(entityName: Constant::ENTITY_PLAYERS)->getByUsername(username: $this->login->getUsername());
        $player = new Player(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), id: 0, name: "", username: "", password: "", email: "", phone: NULL, administrator: "0", registrationDate: new DateTime(), approvalDate: NULL, approvalUserid: NULL, approvalName: NULL, rejectionDate: NULL, rejectionUserid: NULL, rejectionName: NULL, active: "0", resetSelector: NULL, resetToken: NULL, resetExpires: NULL, rememberSelector: NULL, rememberToken: NULL, rememberExpires: NULL);
        $player->createFromEntity(debug: SessionUtility::getValue(name: SessionUtility::OBJECT_NAME_DEBUG), players: $players[0]);
        $this->setPlayer(player: $player);
        SessionUtility::setValue(name: SessionUtility::OBJECT_NAME_SECURITY, value: $this);
        $seasons = $entityManager->getRepository(entityName: Constant::ENTITY_SEASONS)->getActives();
        $season = new Season(debug: $this->debug, id: NULL, description: "", startDate: NULL, endDate: NULL, championshipQualify: 0, finalTablePlayers: 0, finalTableBonusPoints: 0, fee: 0, active: "0");
        $season->createFromEntity($this->debug, $seasons);
        $this->setSeason(season: $season);
        SessionUtility::setValue(name: SessionUtility::OBJECT_NAME_SEASON, value: $season);
    }

    public function setLogin(Login $login) {
        $this->login = $login;
        return $this;
    }

    public function setSeason(Season $season) {
        $this->season = $season;
        return $this;
    }

    public function setPlayer(Player $player) {
        $this->player = $player;
        return $this;
    }

    public function __toString(): string {
        $output = parent::__toString();
        $output .= ", login = [" . $this->login;
        $output .= "], player = [" . $this->player . "]";
        return $output;
    }

    private function validatePassword(): bool {
        $found = false;
        $entityManager = getEntityManager();
        $player = $entityManager->getRepository(entityName: Constant::ENTITY_PLAYERS)->getByUsername(username: $this->login->getUsername());
        if (password_verify(password: $this->login->getPassword(), hash: $player[0]->getPlayerPassword())) {
            $found = true;
        }
        return $found;
    }
}
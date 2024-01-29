<?php
namespace Poker\Ccp\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use PDO;
class GameTypesRepository extends BaseRepository {
    public function getById(?int $gameTypeId) {
//         case "gameTypeSelectAll":
//         case "gameTypeSelectOneById":
        $qb = $this->createQueryBuilder("gt");
        if (isset($gameTypeId)) {
            $qb = $qb->where("gt.gameTypeId = :gameTypeId");
            $qb->setParameters(new ArrayCollection(array(new Parameter("gameTypeId", $gameTypeId))));
        }
        $qb = $qb->addOrderBy("gt.gameTypeName", "ASC");
        return $qb->getQuery()->getResult();
    }

    public function getTableOutput(?int $gameTypeId, bool $indexed) {
        $sql =
            "SELECT game_type_id AS id, game_type_name AS name " .
            "FROM poker_game_types ";
        if (isset($gameTypeId)) {
            $sql .= "WHERE game_type_id = :gameTypeId";
        }
        $statement = $this->getEntityManager()->getConnection()->prepare($sql);
        if (isset($gameTypeId)) {
            $statement->bindValue("gameTypeId", $gameTypeId, PDO::PARAM_INT);
        }
        if ($indexed) {
            return $statement->executeQuery()->fetchAllNumeric();
        } else {
            return $statement->executeQuery()->fetchAllAssociative();
        }
    }
}
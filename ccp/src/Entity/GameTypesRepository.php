<?php
namespace Poker\Ccp\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use PDO;
class GameTypesRepository extends BaseRepository {
    public function getById(?int $gameTypeId) {
        $qb = $this->createQueryBuilder(alias: "gt");
        if (isset($gameTypeId)) {
            $qb = $qb->where(predicates: "gt.gameTypeId = :gameTypeId");
            $qb->setParameters(parameters: new ArrayCollection(elements: array(new Parameter(name: "gameTypeId", value: $gameTypeId))));
        }
        $qb = $qb->addOrderBy(sort: "gt.gameTypeName", order: "ASC");
        return $qb->getQuery()->getResult();
    }

    public function getByName(string $gameTypeName) {
        $qb = $this->createQueryBuilder(alias: "gt");
        if (isset($gameTypeName)) {
            $qb = $qb->where(predicates: "gt.gameTypeName = :gameTypeName");
            $qb->setParameters(parameters: new ArrayCollection(elements: array(new Parameter(name: "gameTypeName", value: $gameTypeName))));
        }
        return $qb->getQuery()->getResult();
    }

    public function getTableOutput(?int $gameTypeId, bool $indexed) {
        $sql =
            "SELECT game_type_id AS id, game_type_name AS name " .
            "FROM poker_game_types ";
        if (isset($gameTypeId)) {
            $sql .= "WHERE game_type_id = :gameTypeId";
        }
        $statement = $this->getEntityManager()->getConnection()->prepare(sql: $sql);
        if (isset($gameTypeId)) {
            $statement->bindValue(param: "gameTypeId", value: $gameTypeId, type: PDO::PARAM_INT);
        }
        if ($indexed) {
            return $statement->executeQuery()->fetchAllNumeric();
        } else {
            return $statement->executeQuery()->fetchAllAssociative();
        }
    }
}
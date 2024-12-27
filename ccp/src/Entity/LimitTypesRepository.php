<?php
namespace Poker\Ccp\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use PDO;
class LimitTypesRepository extends BaseRepository {
    public function getById(?int $limitTypeId) {
        $qb = $this->createQueryBuilder(alias: "lt");
        if (isset($limitTypeId)) {
            $qb = $qb->where(predicates: "lt.limitTypeId = :limitTypeId");
            $qb->setParameters(parameters: new ArrayCollection(elements: array(new Parameter(name: "limitTypeId", value: $limitTypeId))));
        }
        $qb = $qb->addOrderBy(sort: "lt.limitTypeName", order: "ASC");
        return $qb->getQuery()->getResult();
    }

    public function getByName(string $limitTypeName) {
        $qb = $this->createQueryBuilder(alias: "lt");
        if (isset($limitTypeName)) {
            $qb = $qb->where(predicates: "lt.limitTypeName = :limitTypeName");
            $qb->setParameters(parameters: new ArrayCollection(elements: array(new Parameter(name: "limitTypeName", value: $limitTypeName))));
        }
        return $qb->getQuery()->getResult();
    }

    public function getTableOutput(?int $limitTypeId, bool $indexed) {
        $sql =
            "SELECT limit_type_id AS id, limit_type_name AS name " .
            "FROM poker_limit_types ";
        if (isset($limitTypeId)) {
            $sql .= "WHERE limit_type_id = :limitTypeId";
        }
        $statement = $this->getEntityManager()->getConnection()->prepare(sql: $sql);
        if (isset($limitTypeId)) {
            $statement->bindValue(param: "limitTypeId", value: $limitTypeId, type: PDO::PARAM_INT);
        }
        if ($indexed) {
            return $statement->executeQuery()->fetchAllNumeric();
        } else {
            return $statement->executeQuery()->fetchAllAssociative();
        }
    }
}
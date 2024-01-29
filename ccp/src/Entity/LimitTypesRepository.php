<?php
namespace Poker\Ccp\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use PDO;
class LimitTypesRepository extends BaseRepository {
    public function getById(?int $limitTypeId) {
        $qb = $this->createQueryBuilder("lt");
        if (isset($limitTypeId)) {
            $qb = $qb->where("lt.limitTypeId = :limitTypeId");
            $qb->setParameters(new ArrayCollection(array(new Parameter("limitTypeId", $limitTypeId))));
        }
        $qb = $qb->addOrderBy("lt.limitTypeName", "ASC");
        return $qb->getQuery()->getResult();
    }

    public function getTableOutput(?int $limitTypeId, bool $indexed) {
        $sql =
        "SELECT limit_type_id AS id, limit_type_name AS name " .
        "FROM poker_limit_types ";
        if (isset($limitTypeId)) {
            $sql .= "WHERE limit_type_id = :limitTypeId";
        }
        $statement = $this->getEntityManager()->getConnection()->prepare($sql);
        if (isset($limitTypeId)) {
            $statement->bindValue("limitTypeId", $limitTypeId, PDO::PARAM_INT);
        }
        if ($indexed) {
            return $statement->executeQuery()->fetchAllNumeric();
        } else {
            return $statement->executeQuery()->fetchAllAssociative();
        }
    }
}
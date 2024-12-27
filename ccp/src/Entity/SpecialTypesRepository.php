<?php
namespace Poker\Ccp\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use PDO;
class SpecialTypesRepository extends BaseRepository {
    public function getById(?int $specialTypeId) {
        $qb = $this->createQueryBuilder(alias: "st");
        if (isset($specialTypeId)) {
            $qb = $qb->where("st.specialTypeId = :specialTypeId");
            $qb->setParameters(parameters: new ArrayCollection(elements: array(new Parameter(name: "specialTypeId", value: $specialTypeId))));
        }
        return $qb->addOrderBy(sort: "st.specialTypeDescription", order: "ASC")
                  ->getQuery()->getResult();
    }

    public function getByName(string $specialTypeDescription) {
        $qb = $this->createQueryBuilder(alias: "st");
        if (isset($specialTypeDescription)) {
            $qb = $qb->where("st.specialTypeDescription = :specialTypeDescription");
            $qb->setParameters(parameters: new ArrayCollection(elements: array(new Parameter(name: "specialTypeDescription", value: $specialTypeDescription))));
        }
        return $qb->getQuery()->getResult();
    }

    public function getTableOutput(?int $specialTypeId, bool $indexed) {
        $sql =
            "SELECT special_type_id AS id, special_type_description AS description, special_type_multiplier as multiplier " .
            "FROM poker_special_types ";
        if (isset($specialTypeId)) {
            $sql .= "WHERE special_type_id = :specialTypeId";
        }
        $statement = $this->getEntityManager()->getConnection()->prepare(sql: $sql);
        if (isset($specialTypeId)) {
            $statement->bindValue(param: "specialTypeId", value: $specialTypeId, type: PDO::PARAM_INT);
        }
        if ($indexed) {
            return $statement->executeQuery()->fetchAllNumeric();
        } else {
            return $statement->executeQuery()->fetchAllAssociative();
        }
    }
}
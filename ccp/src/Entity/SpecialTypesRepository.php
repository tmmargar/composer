<?php
namespace Poker\Ccp\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use PDO;
class SpecialTypesRepository extends BaseRepository {
    public function getById(?int $specialTypeId) {
        $qb = $this->createQueryBuilder("st");
        if (isset($specialTypeId)) {
            $qb = $qb->where("st.specialTypeId = :specialTypeId");
            $qb->setParameters(new ArrayCollection(array(new Parameter("specialTypeId", $specialTypeId))));
        }
        $qb = $qb->addOrderBy("st.specialTypeDescription", "ASC");
        return $qb->getQuery()->getResult();
    }

    public function getTableOutput(?int $specialTypeId, bool $indexed) {
        $sql =
        "SELECT special_type_id AS id, special_type_description AS description, special_type_multiplier as multiplier " .
        "FROM poker_special_types ";
        if (isset($specialTypeId)) {
            $sql .= "WHERE special_type_id = :specialTypeId";
        }
        $statement = $this->getEntityManager()->getConnection()->prepare($sql);
        if (isset($specialTypeId)) {
            $statement->bindValue("specialTypeId", $specialTypeId, PDO::PARAM_INT);
        }
        if ($indexed) {
            return $statement->executeQuery()->fetchAllNumeric();
        } else {
            return $statement->executeQuery()->fetchAllAssociative();
        }
    }
}
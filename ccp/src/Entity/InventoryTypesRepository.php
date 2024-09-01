<?php
namespace Poker\Ccp\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use PDO;
class InventoryTypesRepository extends BaseRepository {
    public function getById(?int $inventoryTypeId) {
        $qb = $this->createQueryBuilder(alias: "it");
        if (isset($inventoryTypeId)) {
            $qb = $qb->where(predicates: "it.inventoryTypeId = :inventoryTypeId");
            $qb->setParameters(new ArrayCollection(array(new Parameter("inventoryTypeId", $inventoryTypeId))));
        }
        return $qb->getQuery()->getResult();
    }

    public function getNotConfigured(?int $inventoryTypeId) {
        $qb = $this->createQueryBuilder(alias: "it")
                   ->leftJoin(join: "it.inventories", alias: "i");
       if (isset($inventoryTypeId)) {
           $qb->where(predicates: "i.inventoryTypes IS NULL OR it.inventoryTypeId = :inventoryTypeId");
           $qb->setParameters(new ArrayCollection(array(new Parameter("inventoryTypeId", $inventoryTypeId))));
       } else {
           $qb->where(predicates: "i.inventoryTypes IS NULL");
       }
        return $qb->getQuery()->getResult();
    }

    public function getMaxId() {
        $qb = $this->createQueryBuilder(alias: "it");
        return $qb->select(select: "MAX(it.inventoryTypeId)")->getQuery()->getSingleResult();
    }

    public function getTableOutput(?int $inventoryTypeId, bool $indexed) {
        $sql =
            "SELECT it.inventory_type_id AS id, it.inventory_type_name AS name, it.inventory_type_min_amount AS 'min amount', it.inventory_type_max_amount AS 'max amount', it.inventory_type_comment AS comment " .
            "FROM poker_inventory_types it ";
        if (isset($inventoryTypeId)) {
            $sql .= "WHERE it.inventory_type_id = :inventoryTypeId";
        }
        $statement = $this->getEntityManager()->getConnection()->prepare(sql: $sql);
        if (isset($inventoryTypeId)) {
            $statement->bindValue(param: "inventoryTypeId", value: $inventoryTypeId, type: PDO::PARAM_INT);
        }
        if ($indexed) {
            return $statement->executeQuery()->fetchAllNumeric();
        } else {
            return $statement->executeQuery()->fetchAllAssociative();
        }
    }
}
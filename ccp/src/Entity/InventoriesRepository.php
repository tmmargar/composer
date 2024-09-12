<?php
namespace Poker\Ccp\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use PDO;
class InventoriesRepository extends BaseRepository {
    public function getById(?int $inventoryId) {
        $qb = $this->createQueryBuilder(alias: "i")
                    ->addSelect(select: "it")
                    ->innerJoin(join: "i.inventoryTypes", alias: "it");
        if (isset($inventoryId)) {
            $qb = $qb->where(predicates: "i.inventoryId = :inventoryId");
            $qb->setParameters(parameters: new ArrayCollection(elements: array(new Parameter(name: "inventoryId", value: $inventoryId))));
        }
        return $qb->getQuery()->getResult();
    }

    public function getMaxId() {
        $qb = $this->createQueryBuilder(alias: "i");
        return $qb->select(select: "MAX(i.inventoryId)")->getQuery()->getSingleResult();
    }

    public function getTableOutput(?int $inventoryId, bool $indexed) {
        $sql =
            "SELECT i.inventory_id AS id, it.inventory_type_name AS name, i.inventory_current_amount AS 'current amount', i.inventory_warning_amount AS 'warning amount', i.inventory_order_amount AS 'order amount', i.inventory_comment AS comment " .
            "FROM poker_inventories i INNER JOIN poker_inventory_types it ON i.inventory_type_id = it.inventory_type_id ";
        if (isset($inventoryId)) {
            $sql .= "WHERE i.inventory_id = :inventoryId";
        }
        $statement = $this->getEntityManager()->getConnection()->prepare(sql: $sql);
        if (isset($inventoryId)) {
            $statement->bindValue(param: "inventoryId", value: $inventoryId, type: PDO::PARAM_INT);
        }
        if ($indexed) {
            return $statement->executeQuery()->fetchAllNumeric();
        } else {
            return $statement->executeQuery()->fetchAllAssociative();
        }
    }
}
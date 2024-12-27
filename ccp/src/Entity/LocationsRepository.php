<?php
namespace Poker\Ccp\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use PDO;
class LocationsRepository extends BaseRepository {
    public function getById(?int $locationId) {
        $qb = $this->createQueryBuilder(alias: "l");
        if (isset($locationId)) {
            $qb = $qb->where(predicates: "l.locationId = :locationId");
            $qb->setParameters(parameters: new ArrayCollection(elements: array(new Parameter(name: "locationId", value: $locationId))));
        }
        $qb = $qb->addOrderBy(sort: "l.locationName", order: "ASC");
        return $qb->getQuery()->getResult();
    }

    public function getByPlayerId(int $playerId) {
        $qb = $this->createQueryBuilder(alias: "l")
                   ->innerJoin(join: "l.players", alias: "p");
        if (isset($playerId)) {
            $qb = $qb->where(predicates: "p.playerId = :playerId");
            $qb->setParameters(parameters: new ArrayCollection(elements: array(new Parameter(name: "playerId", value: $playerId))));
        }
        return $qb->getQuery()->getResult();
    }

    public function getTableOutput(?int $locationId, bool $indexed) {
        $sql =
            "SELECT l.location_id AS id, l.location_name AS name, l.player_id AS playerId, CONCAT(p.player_first_name, ' ', p.player_last_name) AS host, l.location_address AS address, l.location_city AS city, UPPER(l.location_state) AS state, l.location_zip_code AS zip, l.location_map_link AS map, p.player_active_flag AS active, (SELECT COUNT(*) FROM poker_tournaments t WHERE t.location_id = l.location_id) AS trnys " .
            "FROM poker_locations l INNER JOIN poker_players p ON l.player_id = p.player_id ";
        if (isset($locationId)) {
            $sql .= "WHERE l.location_id = :locationId";
        }
        $statement = $this->getEntityManager()->getConnection()->prepare(sql: $sql);
        if (isset($locationId)) {
            $statement->bindValue(param: "locationId", value: $locationId, type: PDO::PARAM_INT);
        }
        if ($indexed) {
            return $statement->executeQuery()->fetchAllNumeric();
        } else {
            return $statement->executeQuery()->fetchAllAssociative();
        }
    }
}
<?php
namespace Poker\Ccp\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
class StatusCodesRepository extends BaseRepository {

    public function getById(?int $statusCode) {
        $qb = $this->createQueryBuilder(alias: "s");
        if (isset($statusCode)) {
            $qb = $qb->where(predicates: "s.statusCode = :statusCode")
                     ->setParameters(parameters: new ArrayCollection(elements: array(new Parameter(name: "statusCode", value: $statusCode))));
        }
        return $qb->getQuery()->getResult();
    }
}
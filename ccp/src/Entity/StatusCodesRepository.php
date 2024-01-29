<?php
namespace Poker\Ccp\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
class StatusCodesRepository extends BaseRepository {

    public function getById(?int $statusCode) {
        $qb = $this->createQueryBuilder("s");
        if (isset($statusCode)) {
            $qb = $qb->where("s.statusCode = :statusCode");
            $qb->setParameters(new ArrayCollection(array(new Parameter("statusCode", $statusCode))));
        }
        return $qb->getQuery()->getResult();
    }
}
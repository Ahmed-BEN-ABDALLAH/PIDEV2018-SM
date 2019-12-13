<?php

namespace BabyBundle\Repository;

/**
 * BabyRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BabyRepository extends \Doctrine\ORM\EntityRepository
{  public function findAjax($search)
{

    return $this->createQueryBuilder('e')
        ->andWhere('e.nom LIKE :nom')
        ->setParameter('nom','%' .$search . '%')
        ->getQuery()
        ->getResult();
}
}
<?php

namespace Plugin\PayJP\Repository;

use Plugin\PayJP\Entity\Plan;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Eccube\Repository\AbstractRepository;

/**
* @method Plan|null find($id, $lockMode = null, $lockVersion = null)
* @method Plan|null findOneBy(array $criteria, array $orderBy = null)
* @method Plan[]    findAll()
* @method Plan[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
*/
class PlanRepository extends AbstractRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Plan::class);
    }

    // /**
    //  * @return Plan[] Returns an array of Plan objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
        ->andWhere('p.exampleField = :val')
        ->setParameter('val', $value)
        ->orderBy('p.id', 'ASC')
        ->setMaxResults(10)
        ->getQuery()
        ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Plan
    {
        return $this->createQueryBuilder('p')
        ->andWhere('p.exampleField = :val')
        ->setParameter('val', $value)
        ->getQuery()
        ->getOneOrNullResult()
        ;
    }
    */
}
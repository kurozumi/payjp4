<?php

namespace Plugin\PayJP\Repository;

use Plugin\PayJP\Entity\Subscription;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Eccube\Repository\AbstractRepository;

/**
* @method Subscription|null find($id, $lockMode = null, $lockVersion = null)
* @method Subscription|null findOneBy(array $criteria, array $orderBy = null)
* @method Subscription[]    findAll()
* @method Subscription[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
*/
class SubscriptionRepository extends AbstractRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Subscription::class);
    }

    // /**
    //  * @return Subscription[] Returns an array of Subscription objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
        ->andWhere('s.exampleField = :val')
        ->setParameter('val', $value)
        ->orderBy('s.id', 'ASC')
        ->setMaxResults(10)
        ->getQuery()
        ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Subscription
    {
        return $this->createQueryBuilder('s')
        ->andWhere('s.exampleField = :val')
        ->setParameter('val', $value)
        ->getQuery()
        ->getOneOrNullResult()
        ;
    }
    */
}
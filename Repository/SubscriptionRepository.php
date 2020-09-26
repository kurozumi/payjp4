<?php
/**
 * This file is part of payjp4
 *
 * Copyright(c) Akira Kurozumi <info@a-zumi.net>
 *
 *  https://a-zumi.net
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\payjp4\Repository;

use Plugin\payjp4\Entity\Subscription;
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

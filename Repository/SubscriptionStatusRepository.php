<?php


namespace Plugin\PayJP\Repository;


use Eccube\Repository\AbstractRepository;
use Plugin\PayJP\Entity\SubscriptionStatus;
use Symfony\Bridge\Doctrine\RegistryInterface;

class SubscriptionStatusRepository extends AbstractRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SubscriptionStatus::class);
    }

}
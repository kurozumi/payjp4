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


use Eccube\Repository\AbstractRepository;
use Plugin\payjp4\Entity\SubscriptionStatus;
use Symfony\Bridge\Doctrine\RegistryInterface;

class SubscriptionStatusRepository extends AbstractRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SubscriptionStatus::class);
    }

}

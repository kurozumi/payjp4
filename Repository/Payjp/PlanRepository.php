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

namespace Plugin\payjp4\Repository\Payjp;

use Plugin\payjp4\Entity\Payjp\Plan;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Eccube\Repository\AbstractRepository;

/**
 * Class PlanRepository
 * @package Plugin\payjp4\Repository\Payjp
 */
class PlanRepository extends AbstractRepository
{
    /**
     * PlanRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Plan::class);
    }
}

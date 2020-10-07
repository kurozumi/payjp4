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

use Eccube\Repository\AbstractRepository;
use Plugin\payjp4\Entity\Payjp\CreditCard;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class CreditCardRepository
 * @package Plugin\payjp4\Repository\Payjp
 */
class CreditCardRepository extends AbstractRepository
{
    /**
     * CreditCardRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CreditCard::class);
    }
}

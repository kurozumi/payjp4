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

namespace Plugin\payjp4\Entity;


use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\Master\AbstractMasterEntity;

/**
 * Class SubscriptionStatus
 * @package Plugin\payjp4\Entity
 *
 * @ORM\Table(name="plg_payjp_subscription_status")
 * @ORM\Entity(repositoryClass="Plugin\payjp4\Repository\SubscriptionStatusRepository")
 */
class SubscriptionStatus extends AbstractMasterEntity
{
    /**
     *
     */
    const TRIAL = 'trial';

    /**
     *
     */
    const ACTIVE = 'active';

    /**
     * キャンセル
     */
    const CANCELED = 'canceled';

    /**
     * 停止
     */
    const PAUSED = 'paused';
}

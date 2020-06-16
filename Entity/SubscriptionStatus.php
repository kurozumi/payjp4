<?php


namespace Plugin\PayJP\Entity;


use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\Master\AbstractMasterEntity;

/**
 * Class SubscriptionStatus
 * @package Plugin\PayJP\Entity
 *
 * @ORM\Table(name="plg_payjp_subscription_status")
 * @ORM\Entity(repositoryClass="Plugin\PayJP\Repository\SubscriptionStatusRepository")
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
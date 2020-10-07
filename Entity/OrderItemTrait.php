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
use Eccube\Annotation\EntityExtension;

/**
 * Trait OrderItemTrait
 * @package Plugin\payjp4\Entity
 *
 * @EntityExtension("Eccube\Entity\OrderItem")
 */
trait OrderItemTrait
{
    /**
     * @ORM\OneToOne(targetEntity="Plugin\payjp4\Entity\Payjp\Subscription", inversedBy="OrderItem")
     * @ORM\JoinColumn(name="payjp_subscription_id", referencedColumnName="id")
     */
    private $Subscription;

    /**
     * @return Subscription|null
     */
    public function getSubscription(): ?Subscription
    {
        return $this->Subscription;
    }

    /**
     * @param Subscription|null $subscription
     * @return $this
     */
    public function setSubscription(?Subscription $subscription): self
    {
        $this->Subscription = $subscription;

        return $this;
    }
}

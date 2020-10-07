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

namespace Plugin\payjp4\Entity\Payjp;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\Customer;
use Eccube\Entity\OrderItem;

/**
 * Class Subscription
 * @package Plugin\payjp4\Entity\Payjp
 *
 * @ORM\Table(name="plg_payjp_subscription")
 * @ORM\Entity(repositoryClass="Plugin\payjp4\Repository\Payjp\SubscriptionRepository")
 */
class Subscription extends \Eccube\Entity\AbstractEntity
{
    /**
     * @ORM\Column(name="id", type="integer", options={"unsigned":true})
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $payjp_id;

    /**
     * @ORM\ManyToOne(targetEntity="Eccube\Entity\Customer", inversedBy="Subscriptions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Customer;

    /**
     * @ORM\OneToOne(targetEntity="Eccube\Entity\OrderItem", mappedBy="Subscription")
     */
    private $OrderItem;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getPayjpId(): string
    {
        return $this->payjp_id;
    }

    /**
     * @param string $payjp_id
     * @return $this
     */
    public function setPayjpId(string $payjp_id): self
    {
        $this->payjp_id = $payjp_id;

        return $this;
    }

    /**
     * @return Customer
     */
    public function getCustomer(): Customer
    {
        return $this->Customer;
    }

    /**
     * @param Customer $Customer
     * @return $this
     */
    public function setCustomer(Customer $Customer): self
    {
        $this->Customer = $Customer;

        return $this;
    }

    /**
     * @return OrderItem|null
     */
    public function getOrderItem(): ?OrderItem
    {
        return $this->OrderItem;
    }

    /**
     * @param OrderItem $orderItem
     * @return $this
     */
    public function setOrderItem(OrderItem $orderItem): self
    {
        $this->OrderItem = $orderItem;

        return $this;
    }
}

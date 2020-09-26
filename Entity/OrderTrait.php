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
 * Trait OrderTrait
 * @package Plugin\payjp4\Entity
 *
 * @EntityExtension("Eccube\Entity\Order")
 */
trait OrderTrait
{
    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $payjp_token;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $payjp_charge_id;

    /**
     * @var PaymentStatus
     * @ORM\ManyToOne(targetEntity="Plugin\payjp4\Entity\PaymentStatus")
     * @ORM\JoinColumn(name="payjp_payment_status_id", referencedColumnName="id")
     */
    private $PayJpPaymentStatus;

    /**
     * @return string|null
     */
    public function getPayjpToken(): ?string
    {
        return $this->payjp_token;
    }

    /**
     * @param string|null $payjp_token
     * @return $this
     */
    public function setPayjpToken(?string $payjp_token): self
    {
        $this->payjp_token = $payjp_token;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPayjpChargeId(): ?string
    {
        return $this->payjp_charge_id;
    }

    /**
     * @param string|null $payjp_charge_id
     * @return $this
     */
    public function setPayjpChargeId(?string $payjp_charge_id): self
    {
        $this->payjp_charge_id = $payjp_charge_id;

        return $this;
    }

    /**
     * @return PaymentStatus|null
     */
    public function getPayJpPaymentStatus(): ?PaymentStatus
    {
        return $this->PayJpPaymentStatus;
    }

    /**
     * @param PaymentStatus|null $paymentStatus
     * @return $this
     */
    public function setPayJpPaymentStatus(?PaymentStatus $paymentStatus): self
    {
        $this->PayJpPaymentStatus = $paymentStatus;

        return $this;
    }
}

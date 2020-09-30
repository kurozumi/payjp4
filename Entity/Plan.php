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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\ProductClass;

/**
 * @ORM\Table(name="plg_payjp_plan")
 * @ORM\Entity(repositoryClass="Plugin\payjp4\Repository\PlanRepository")
*/
class Plan extends \Eccube\Entity\AbstractEntity
{
    /**
    * @ORM\Column(name="id", type="integer", options={"unsigned":true})
    * @ORM\Id()
    * @ORM\GeneratedValue(strategy="IDENTITY")
    */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $plan_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $amount;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $object = 'plan';

    /**
     * @ORM\Column(type="boolean")
     */
    private $livemode = false;

    /**
     * @ORM\Column(type="datetimetz")
     */
    private $created;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $currency = 'jpy';

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $charge_interval;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $trial_days = 0;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $billing_day;

    /**
     * @ORM\OneToOne(targetEntity="Eccube\Entity\ProductClass", mappedBy="Plan")
     */
    private $ProductClass;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlanId(): ?string
    {
        return $this->plan_id;
    }

    public function setPlanId(string $plan_id): self
    {
        $this->plan_id = $plan_id;

        return $this;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getObject(): ?string
    {
        return $this->object;
    }

    public function setObject(string $object): self
    {
        $this->object = $object;

        return $this;
    }

    public function getLivemode(): ?bool
    {
        return $this->livemode;
    }

    public function setLivemode(bool $livemode): self
    {
        $this->livemode = $livemode;

        return $this;
    }

    public function getCreated(): ?\DateTime
    {
        return $this->created;
    }

    public function setCreated(\DateTime $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getChargeInterval(): ?string
    {
        return $this->charge_interval;
    }

    public function setChargeInterval(string $charge_interval): self
    {
        $this->charge_interval = $charge_interval;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getTrialDays(): ?int
    {
        return $this->trial_days;
    }

    public function setTrialDays(int $trial_days): self
    {
        $this->trial_days = $trial_days;

        return $this;
    }

    public function getBillingDay(): ?int
    {
        return $this->billing_day;
    }

    public function setBillingDay(?int $billing_day): self
    {
        $this->billing_day = $billing_day;

        return $this;
    }

    /**
     * @return ProductClass|null
     */
    public function getProductClass(): ?ProductClass
    {
        return $this->ProductClass;
    }

    /**
     * @param ProductClass $productClass
     * @return $this
     */
    public function setProductClass(ProductClass $productClass): self
    {
        $this->ProductClass = $productClass;

        return $this;
    }
}

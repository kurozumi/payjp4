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
use Eccube\Entity\Product;

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
     * @ORM\Column(type="integer")
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
     * @ORM\OneToMany(targetEntity="Plugin\payjp4\Entity\Subscription", mappedBy="Plan")
     */
    private $subscriptions;

    /**
     * @ORM\OneToMany(targetEntity="Eccube\Entity\Product", mappedBy="Plan", cascade={"persist","remove"})
     */
    private $products;

    public function __construct()
    {
        $this->subscriptions = new ArrayCollection();
    }

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

    public function getCreated(): ?int
    {
        return $this->created;
    }

    public function setCreated(int $created): self
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
     * @return Collection|Subscription[]
     */
    public function getSubscriptions(): Collection
    {
        return $this->subscriptions;
    }

    public function addSubscription(Subscription $subscription): self
    {
        if (!$this->subscriptions->contains($subscription)) {
            $this->subscriptions[] = $subscription;
            $subscription->setPlan($this);
        }

        return $this;
    }

    public function removeSubscription(Subscription $subscription): self
    {
        if ($this->subscriptions->contains($subscription)) {
            $this->subscriptions->removeElement($subscription);
            // set the owning side to null (unless already changed)
            if ($subscription->getPlan() === $this) {
                $subscription->setPlan(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    /**
     * @param Product $product
     * @return $this
     */
    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setPayjpPlan($this);
        }

        return $this;
    }

    /**
     * @param Product $product
     * @return $this
     */
    public function removeProduct(Product $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            // set the owning side to null (unless already changed)
            if ($product->getPayjpPlan() === $this) {
                $product->setPayjpPlan(null);
            }
        }

        return $this;
    }
}

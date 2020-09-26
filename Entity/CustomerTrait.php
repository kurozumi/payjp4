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
use Eccube\Annotation\EntityExtension;

/**
 * Trait CutomerTrait
 * @package Plugin\payjp4\Entity
 *
 * @EntityExtension("Eccube\Entity\Customer")
 */
trait CustomerTrait
{

    /**
     * @ORM\OneToMany(targetEntity="Plugin\payjp4\Entity\Subscription", mappedBy="Customer")
     */
    private $payjpSubscriptions;

    /**
     * @ORM\ManyToOne(targetEntity="Plugin\payjp4\Entity\SubscriptionStatus", inversedBy="subscriptions")
     * @ORM\JoinColumn(nullable=true)
     */
    private $payjpSubscriptionStatus;

    /**
     * @return Collection|Subscription[]
     */
    public function getPayjpSubscriptions(): Collection
    {
        if (null == $this->payjpSubscriptions) {
            $this->payjpSubscriptions = new ArrayCollection();
        }

        return $this->payjpSubscriptions;
    }

    public function addPayjpSubscription(Subscription $subscription): self
    {
        if (null == $this->payjpSubscriptions) {
            $this->payjpSubscriptions = new ArrayCollection();
        }

        if (!$this->payjpSubscriptions->contains($subscription)) {
            $this->payjpSubscriptions[] = $subscription;
            $subscription->setCustomer($this);
        }

        return $this;
    }

    public function removePayjpSubscription(Subscription $subscription): self
    {
        if (null == $this->payjpSubscriptions) {
            $this->payjpSubscriptions = new ArrayCollection();
        }

        if ($this->payjpSubscriptions->contains($subscription)) {
            $this->payjpSubscriptions->removeElement($subscription);
            // set the owning side to null (unless already changed)
            if ($subscription->getCustomer() === $this) {
                $subscription->setCustomer(null);
            }
        }

        return $this;
    }

    public function getPayjpSubscriptionStatus(): ?SubscriptionStatus
    {
        return $this->payjpSubscriptionStatus;
    }

    public function setPayjpSubscriptionStatus(?SubscriptionStatus $subscriptionStatus)
    {
        $this->payjpSubscriptionStatus = $subscriptionStatus;

        return $this;
    }
}

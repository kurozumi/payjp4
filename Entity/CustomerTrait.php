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
use Plugin\payjp4\Entity\Payjp\CreditCard;

/**
 * Trait CutomerTrait
 * @package Plugin\payjp4\Entity
 *
 * @EntityExtension("Eccube\Entity\Customer")
 */
trait CustomerTrait
{
    /**
     * @ORM\OneToMany(targetEntity="Plugin\payjp4\Entity\Payjp\CreditCard", mappedBy="Customer", cascade={"persist","remove"})
     */
    private $CreditCards;

    /**
     * @ORM\OneToMany(targetEntity="Plugin\payjp4\Entity\Payjp\Subscription", mappedBy="Customer")
     */
    private $Subscriptions;

    /**
     * @return Collection|Subscription[]
     */
    public function getCreditCards(): Collection
    {
        if (null == $this->CreditCards) {
            $this->CreditCards = new ArrayCollection();
        }

        return $this->CreditCards;
    }

    /**
     * @param CreditCard $creditCard
     * @return $this
     */
    public function addCreditCard(CreditCard $creditCard): self
    {
        if (null == $this->CreditCards) {
            $this->CreditCards = new ArrayCollection();
        }

        if (!$this->CreditCards->contains($creditCard)) {
            $this->CreditCards[] = $creditCard;
            $creditCard->setCustomer($this);
        }

        return $this;
    }

    /**
     * @param CreditCard $creditCard
     * @return $this
     */
    public function removeCreditCard(CreditCard $creditCard): self
    {
        if (null == $this->CreditCards) {
            $this->CreditCards = new ArrayCollection();
        }

        if ($this->CreditCards->contains($creditCard)) {
            $this->CreditCards->removeElement($creditCard);
            // set the owning side to null (unless already changed)
            if ($creditCard->getCustomer() === $this) {
                $creditCard->setCustomer(null);
            }
        }

        return $this;
    }
}

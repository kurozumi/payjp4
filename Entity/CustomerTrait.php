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
use Eccube\Entity\Customer;

/**
 * Trait CutomerTrait
 * @package Plugin\payjp4\Entity
 *
 * @EntityExtension("Eccube\Entity\Customer")
 */
trait CustomerTrait
{
    /**
     * @ORM\OneToMany(targetEntity="Plugin\payjp4\Entity\PayjpCustomer", mappedBy="Customer")
     */
    private $payjpCustomers;

    /**
     * @return Collection|Subscription[]
     */
    public function getPayjpCustomers(): Collection
    {
        if (null == $this->payjpCustomers) {
            $this->payjpCustomers = new ArrayCollection();
        }

        return $this->payjpCustomers;
    }

    public function addPayjpCustomer(PayjpCustomer $payjpCustomer): self
    {
        if (null == $this->payjpCustomers) {
            $this->payjpCustomers = new ArrayCollection();
        }

        if (!$this->payjpCustomers->contains($payjpCustomer)) {
            $this->payjpCustomers[] = $payjpCustomer;
            $payjpCustomer->setCustomer($this);
        }

        return $this;
    }

    public function removePayjpCustomer(PayjpCustomer $payjpCustomer): self
    {
        if (null == $this->payjpCustomers) {
            $this->payjpCustomers = new ArrayCollection();
        }

        if ($this->payjpCustomers->contains($payjpCustomer)) {
            $this->payjpCustomers->removeElement($payjpCustomer);
            // set the owning side to null (unless already changed)
            if ($payjpCustomer->getCustomer() === $this) {
                $payjpCustomer->setCustomer(null);
            }
        }

        return $this;
    }
}

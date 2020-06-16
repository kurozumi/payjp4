<?php

namespace Plugin\PayJP\Entity;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\Customer;

/**
* @ORM\Entity(repositoryClass="Plugin\PayJP\Repository\SubscriptionRepository")
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
     * @ORM\ManyToOne(targetEntity="Eccube\Entity\Customer", inversedBy="subscriptions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Customer;

    /**
     * @ORM\ManyToOne(targetEntity="Plugin\PayJP\Entity\Plan", inversedBy="subscriptions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Plan;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCustomer(): ?Customer
    {
        return $this->Customer;
    }

    public function setCustomer(?Customer $Customer): self
    {
        $this->Customer = $Customer;

        return $this;
    }

    public function getPlan(): ?Plan
    {
        return $this->Plan;
    }

    public function setPlan(?Plan $Plan): self
    {
        $this->Plan = $Plan;

        return $this;
    }
}
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
use Eccube\Entity\Customer;

/**
 * Class PayjpCustomer
 * @package Plugin\payjp4\Entity
 *
 * @ORM\Table(name="plg_payjp_customer")
 * @ORM\Entity(repositoryClass="Plugin\payjp4\Repository\PayjpCustomerRepository")
 */
class PayjpCustomer
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer", options={"unsigned": true})
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $payjp_customer_id;

    /**
     * @var
     *
     * @ORM\ManyToOne(targetEntity="Eccube\Entity\Customer", inversedBy="payjpCustomers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Customer;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getPayjpCustomerId(): string
    {
        return $this->payjp_customer_id;
    }

    public function setPayjpCustomerId(string $payjp_customer_id): self
    {
        $this->payjp_customer_id = $payjp_customer_id;

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
     * @param Customer $customer
     * @return $this
     */
    public function setCustomer(Customer $customer): self
    {
        $this->Customer = $customer;

        return $this;
    }
}

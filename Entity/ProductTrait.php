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
 * Class ProductTrait
 * @package Plugin\payjp4\Entity
 *
 * @EntityExtension("Eccube\Entity\Product")
 */
trait ProductTrait
{
    /**
     * @ORM\ManyToOne(targetEntity="Plugin\payjp4\Entity\Plan", inversedBy="products")
     */
    private $payjp_plan;

    /**
     * @return Plan|null
     */
    public function getPayjpPlan(): ?Plan
    {
        return $this->payjp_plan;
    }

    /**
     * @param Plan|null $plan
     * @return $this
     */
    public function setPayjpPlan(?Plan $plan): self
    {
        $this->payjp_plan = $plan;

        return $this;
    }
}

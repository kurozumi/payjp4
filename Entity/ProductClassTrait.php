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
use Plugin\payjp4\Entity\Payjp\Plan;

/**
 * Class ProductClassTrait
 * @package Plugin\payjp4\Entity
 *
 * @EntityExtension("Eccube\Entity\ProductClass")
 */
trait ProductClassTrait
{
    /**
     * @ORM\OneToOne(targetEntity="Plugin\payjp4\Entity\Payjp\Plan", inversedBy="ProductClass")
     * @ORM\JoinColumn(name="payjp_plan_id", referencedColumnName="id")
     */
    private $Plan;

    /**
     * @return Plan|null
     */
    public function getPlan(): ?Plan
    {
        return $this->Plan;
    }

    /**
     * @param Plan|null $plan
     * @return $this
     */
    public function setPlan(?Plan $plan): self
    {
        $this->Plan = $plan;

        return $this;
    }
}

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

namespace Plugin\payjp4\Entity\Payjp;


use Doctrine\ORM\Mapping as ORM;


/**
 * Class Event
 * @package Plugin\payjp4\Entity\Payjp
 *
 * @ORM\Table(name="plg_payjp_event")
 * @ORM\Entity(repositoryClass="Plugin\payjp4\Repository\Payjp\EventRepository")
 */
class Event
{
    /**
     * @var integer
     *
     * @ORM\Column(type="integer", options={"unsigned":true})
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $payjp_id;

    /**
     * @ORM\Column(type="json")
     */
    private $data;

    /**
     * @ORM\Column(type="string")
     */
    private $type;

    /**
     * @return int
     */
    private function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getPayjpId(): string
    {
        return $this->payjp_id;
    }

    /**
     * @param string $payjp_id
     * @return $this
     */
    public function setPayjpId(string $payjp_id): self
    {
        $this->payjp_id = $payjp_id;

        return $this;
    }

    /**
     * @return string
     */
    public function getData(): string
    {
        return $this->data;
    }

    /**
     * @param string $data
     * @return $this
     */
    public function setData(string $data): self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return $this
     */
    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }
}

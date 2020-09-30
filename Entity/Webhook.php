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

/**
 * Class Webhook
 * @package Plugin\payjp4\Entity
 *
 * @ORM\Table(name="plg_payjp_webhook")
 * @ORM\Entity(repositoryClass="Plugin\payjp4\Repository\WebhookRepository")
 */
class Webhook
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
    private $event_id;

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
    public function getEventId(): string
    {
        return $this->event_id;
    }

    /**
     * @param string $event_id
     * @return $this
     */
    public function setEventId(string $event_id): self
    {
        $this->event_id = $event_id;

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

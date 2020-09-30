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
 * Config
 *
 * @ORM\Table(name="plg_payjp_config")
 * @ORM\Entity(repositoryClass="Plugin\payjp4\Repository\ConfigRepository")
 */
class Config
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer", options={"unsigned":true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $public_key;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $secret_key;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $webhook_token;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getPublicKey(): ?string
    {
        return $this->public_key;
    }

    /**
     * @param string $public_key
     * @return $this
     */
    public function setPublicKey(string $public_key): self
    {
        $this->public_key = $public_key;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSecretKey(): ?string
    {
        return $this->secret_key;
    }

    /**
     * @param string $secret_key
     * @return $this
     */
    public function setSecretKey(string $secret_key): self
    {
        $this->secret_key = $secret_key;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getWebhookToken(): ?string
    {
        return $this->webhook_token;
    }

    /**
     * @param string $webhook_token
     * @return $this
     */
    public function setWebhookToken(string $webhook_token): self
    {
        $this->webhook_token = $webhook_token;

        return $this;
    }
}

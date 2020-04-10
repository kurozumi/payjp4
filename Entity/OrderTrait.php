<?php


namespace Plugin\PayJP\Entity;


use Doctrine\ORM\Mapping as ORM;
use Eccube\Annotation\EntityExtension;

/**
 * Trait OrderTrait
 * @package Plugin\PayJP\Entity
 *
 * @EntityExtension("Eccube\Entity\Order")
 */
trait OrderTrait
{
    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $payjp_token;

    /**
     * @var PaymentStatus
     * @ORM\ManyToOne(targetEntity="Plugin\PayJP\Entity\PaymentStatus")
     * @ORM\JoinColumn(name="payjp_payment_status_id", referencedColumnName="id")
     */
    private $PayJpPaymentStatus;

    /**
     * @return string
     */
    public function getPayjpToken(): ?string
    {
        return $this->payjp_token;
    }

    /**
     * @param string $payjp_token
     * @return $this
     */
    public function setPayjpToken(?string $payjp_token): self
    {
        $this->payjp_token = $payjp_token;

        return $this;
    }

    /**
     * @return PaymentStatus
     */
    public function getPayJpPaymentStatus(): ?PaymentStatus
    {
        return $this->PayJpPaymentStatus;
    }

    /**
     * @param PaymentStatus|null $paymentStatus
     * @return $this
     */
    public function setPayJpPaymentStatus(?PaymentStatus $paymentStatus): self
    {
        $this->PayJpPaymentStatus = $paymentStatus;

        return $this;
    }
}
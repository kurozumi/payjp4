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

namespace Plugin\payjp4\Service\PurchaseFlow\Validator;


use Eccube\Annotation\ShoppingFlow;
use Eccube\Entity\ItemHolderInterface;
use Eccube\Entity\Order;
use Eccube\Entity\Payment;
use Eccube\Repository\PaymentRepository;
use Eccube\Service\PurchaseFlow\ItemHolderPostValidator;
use Eccube\Service\PurchaseFlow\PurchaseContext;
use Plugin\payjp4\Entity\PaymentStatus;
use Plugin\payjp4\Repository\PaymentStatusRepository;
use Plugin\payjp4\Service\Method\CreditCard;
use Plugin\payjp4\Service\Method\Subscription;

/**
 * Class PayjpTokenValidator
 * @package Plugin\payjp4\Service\Method\PurchaseFlow\Processor
 *
 * @ShoppingFlow()
 */
class PayjpTokenValidator extends ItemHolderPostValidator
{
    /**
     * @var PaymentStatusRepository
     */
    private $paymentStatusRepository;

    public function __construct(
        PaymentStatusRepository $paymentStatusRepository
    )
    {
        $this->paymentStatusRepository = $paymentStatusRepository;
    }

    /**
     * @inheritDoc
     */
    protected function validate(ItemHolderInterface $itemHolder, PurchaseContext $context)
    {
        // TODO: Implement validate() method.
        if (!$itemHolder instanceof Order) {
            return;
        }

        if (
            $itemHolder->getPayment()->getMethodClass() === CreditCard::class ||
            $itemHolder->getPayment()->getMethodClass() === Subscription::class
        ) {
            $PaymentStatus = $this->paymentStatusRepository->find(PaymentStatus::ENABLED);
            $itemHolder->setPayJpPaymentStatus($PaymentStatus);
        } else {
            $itemHolder->setPayJpPaymentStatus(null);
            $itemHolder->setPayjpToken(null);
        }
    }
}

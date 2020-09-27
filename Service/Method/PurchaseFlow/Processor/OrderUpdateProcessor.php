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

namespace Plugin\payjp4\Service\Method\PurchaseFlow\Processor;


use Eccube\Annotation\ShoppingFlow;
use Eccube\Entity\ItemHolderInterface;
use Eccube\Entity\Master\OrderStatus;
use Eccube\Entity\Order;
use Eccube\Entity\Payment;
use Eccube\Repository\Master\OrderStatusRepository;
use Eccube\Repository\PaymentRepository;
use Eccube\Service\PurchaseFlow\Processor\AbstractPurchaseProcessor;
use Eccube\Service\PurchaseFlow\PurchaseContext;
use Plugin\payjp4\Entity\PaymentStatus;
use Plugin\payjp4\Repository\PaymentStatusRepository;
use Plugin\payjp4\Service\Method\CreditCard;

/**
 * Class OrderUpdateProcessor
 * @package Plugin\payjp4\Service\Method\PurchaseFlow\Processor
 *
 * @ShoppingFlow()
 */
class OrderUpdateProcessor extends AbstractPurchaseProcessor
{
    /**
     * @var OrderStatusRepository
     */
    private $orderStatusRepository;

    /**
     * @var PaymentStatusRepository
     */
    private $paymentStatusRepository;

    /**
     * @var Payment
     */
    private $payment;

    public function __construct(
        OrderStatusRepository $orderStatusRepository,
        PaymentStatusRepository $paymentStatusRepository,
        PaymentRepository $paymentRepository
    )
    {
        $this->orderStatusRepository = $orderStatusRepository;
        $this->paymentStatusRepository = $paymentStatusRepository;
        $this->payment = $paymentRepository->findOneBy([
            'method_class' => CreditCard::class
        ]);
    }

    /**
     * 決済の前処理。
     *
     * @param ItemHolderInterface $target
     * @param PurchaseContext $context
     */
    public function prepare(ItemHolderInterface $target, PurchaseContext $context): void
    {
        if (!$target instanceof Order) {
            return;
        }

        if($target->getPaymentMethod() === $this->payment->getMethod()) {
            // 受注ステーテスを決済処理中へ変更
            $OrderStatus = $this->orderStatusRepository->find(OrderStatus::PENDING);
            $target->setOrderStatus($OrderStatus);

            // 決済ステータスを未決済へ変更
            $PaymentStatus = $this->paymentStatusRepository->find(PaymentStatus::OUTSTANDING);
            $target->setPayJpPaymentStatus($PaymentStatus);
        }
    }

    /**
     * 決済処理
     *
     * @param ItemHolderInterface $target
     * @param PurchaseContext $context
     */
    public function commit(ItemHolderInterface $target, PurchaseContext $context): void
    {
        if (!$target instanceof Order) {
            return;
        }

        if($target->getPaymentMethod() === $this->payment->getMethod()) {
            // 受注ステータスを新規受付へ変更
            $OrderStatus = $this->orderStatusRepository->find(OrderStatus::NEW);
            $target->setOrderStatus($OrderStatus);

            // 決済ステータスを実売上へ変更
            $PaymentStatus = $this->paymentStatusRepository->find(PaymentStatus::ACTUAL_SALES);
            $target->setPayJpPaymentStatus($PaymentStatus);
        }
    }

    /**
     * 決済失敗処理
     *
     * @param ItemHolderInterface $itemHolder
     * @param PurchaseContext $context
     */
    public function rollback(ItemHolderInterface $itemHolder, PurchaseContext $context): void
    {
        if (!$itemHolder instanceof Order) {
            return;
        }

        if($itemHolder->getPaymentMethod() === $this->payment->getMethod()) {
            // 受注ステータスを購入処理中へ変更
            $OrderStatus = $this->orderStatusRepository->find(OrderStatus::PROCESSING);
            $itemHolder->setOrderStatus($OrderStatus);

            // 決済ステータスを未決済へ変更
            $PaymentStatus = $this->paymentStatusRepository->find(PaymentStatus::OUTSTANDING);
            $itemHolder->setPayJpPaymentStatus($PaymentStatus);
        }
    }
}

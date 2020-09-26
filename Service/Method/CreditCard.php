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

namespace Plugin\payjp4\Service\Method;


use Eccube\Common\EccubeConfig;
use Eccube\Entity\Master\OrderStatus;
use Eccube\Entity\Order;
use Eccube\Repository\Master\OrderStatusRepository;
use Eccube\Service\Payment\PaymentMethod;
use Eccube\Service\Payment\PaymentMethodInterface;
use Eccube\Service\Payment\PaymentResult;
use Eccube\Service\PurchaseFlow\PurchaseContext;
use Eccube\Service\PurchaseFlow\PurchaseFlow;
use Payjp\Charge;
use Payjp\Payjp;
use Plugin\payjp4\Entity\PaymentStatus;
use Plugin\payjp4\Repository\PaymentStatusRepository;
use Symfony\Component\Form\FormInterface;

class CreditCard implements PaymentMethodInterface
{
    /**
     * @var Order
     */
    protected $Order;

    /**
     * @var FormInterface
     */
    protected $form;

    /**
     * @var OrderStatusRepository
     */
    private $orderStatusRepository;

    /**
     * @var PaymentStatusRepository
     */
    private $paymentStatusRepository;

    /**
     * @var PurchaseFlow
     */
    private $purchaseFlow;

    /**
     * @var EccubeConfig
     */
    private $eccubeConfig;

    public function __construct(
        OrderStatusRepository $orderStatusRepository,
        PaymentStatusRepository $paymentStatusRepository,
        PurchaseFlow $purchaseFlow,
        EccubeConfig $eccubeConfig
    )
    {
        $this->orderStatusRepository = $orderStatusRepository;
        $this->paymentStatusRepository = $paymentStatusRepository;
        $this->purchaseFlow = $purchaseFlow;
        $this->eccubeConfig = $eccubeConfig;
    }

    /**
     * @inheritDoc
     */
    public function verify()
    {
        // TODO: Implement verify() method.
        $result = new PaymentResult();
        $result->setSuccess(true);

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function checkout()
    {
        // TODO: Implement checkout() method.
        $token = $this->Order->getPayjpToken();

        Payjp::setApiKey($this->eccubeConfig['payjp_secret_key']);

        $charge = Charge::create([
            'card' => $token,
            'currency' => $this->eccubeConfig['currency'],
            'amount' => $this->Order->getPaymentTotal(),
        ]);

        if (!isset($charge["error"])) {
            // 受注ステータスを新規受付へ変更
            $OrderStatus = $this->orderStatusRepository->find(OrderStatus::NEW);
            $this->Order->setOrderStatus($OrderStatus);

            // 決済ステータスを実売上へ変更
            $PaymentStatus = $this->paymentStatusRepository->find(PaymentStatus::ACTUAL_SALES);
            $this->Order->setPayJpPaymentStatus($PaymentStatus);

            // PAY.JPの課金IDを保存
            $this->Order->setPayjpChargeId($charge['id']);

            // purchaseFlow::commitを呼び出し、購入処理をさせる
            $this->purchaseFlow->commit($this->Order, new PurchaseContext());

            $result = new PaymentResult();
            $result->setSuccess(true);
        }else{
            // 受注ステータスを購入処理中へ変更
            $OrderStatus = $this->orderStatusRepository->find(OrderStatus::PROCESSING);
            $this->Order->setOrderStatus($OrderStatus);

            // 決済ステータスを未決済へ変更
            $PaymentStatus = $this->paymentStatusRepository->find(PaymentStatus::OUTSTANDING);
            $this->Order->setPayJpPaymentStatus($PaymentStatus);

            $this->purchaseFlow->rollback($this->Order, new PurchaseContext());

            $result = new PaymentResult();
            $result->setSuccess(false);
            $result->setErrors([$charge['error']['message']]);
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function apply()
    {
        // TODO: Implement apply() method.
        // 受注ステーテスを決済処理中へ変更
        $OrderStatus = $this->orderStatusRepository->find(OrderStatus::PENDING);
        $this->Order->setOrderStatus($OrderStatus);

        // 決済ステータスを未決済へ変更
        $PaymentStatus = $this->paymentStatusRepository->find(PaymentStatus::OUTSTANDING);
        $this->Order->setPayJpPaymentStatus($PaymentStatus);

        $this->purchaseFlow->prepare($this->Order, new PurchaseContext());
    }

    /**
     * @inheritDoc
     */
    public function setFormType(FormInterface $form)
    {
        // TODO: Implement setFormType() method.
        $this->form = $form;
    }

    /**
     * @inheritDoc
     */
    public function setOrder(Order $Order)
    {
        // TODO: Implement setOrder() method.
        $this->Order = $Order;
    }
}

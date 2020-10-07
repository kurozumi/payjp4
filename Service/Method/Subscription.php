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


use Doctrine\ORM\EntityManagerInterface;
use Eccube\Common\EccubeConfig;
use Eccube\Entity\Order;
use Eccube\Entity\OrderItem;
use Eccube\Repository\Master\OrderStatusRepository;
use Eccube\Service\Payment\PaymentMethod;
use Eccube\Service\Payment\PaymentMethodInterface;
use Eccube\Service\Payment\PaymentResult;
use Eccube\Service\PurchaseFlow\PurchaseContext;
use Eccube\Service\PurchaseFlow\PurchaseFlow;
use Payjp\Customer;
use Payjp\Payjp;
use Plugin\payjp4\Entity\Payjp\CreditCard;
use Plugin\payjp4\Repository\PaymentStatusRepository;
use Symfony\Component\Form\FormInterface;

class Subscription implements PaymentMethodInterface
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

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(
        OrderStatusRepository $orderStatusRepository,
        PaymentStatusRepository $paymentStatusRepository,
        PurchaseFlow $shoppingPurchaseFlow,
        EccubeConfig $eccubeConfig,
        EntityManagerInterface $entityManager
    )
    {
        $this->orderStatusRepository = $orderStatusRepository;
        $this->paymentStatusRepository = $paymentStatusRepository;
        $this->purchaseFlow = $shoppingPurchaseFlow;
        $this->eccubeConfig = $eccubeConfig;
        $this->entityManager = $entityManager;
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

        if (!$token) {
            $this->purchaseFlow->rollback($this->Order, new PurchaseContext());

            $result = new PaymentResult();
            $result->setSuccess(false);
        }

        Payjp::setApiKey($this->eccubeConfig['payjp_secret_key']);

        try {
            $Customer = $this->Order->getCustomer();

            /** @var CreditCard $CreditCard */
            $CreditCard = $Customer->getCreditCards()->filter(function (CreditCard $CreditCard) {
                $c = Customer::retrieve($CreditCard->getPayjpId());
                return !isset($c["error"]);
            })->first();

            // カードが登録されていなかったら新規作成
            if (false === $CreditCard) {
                $c = Customer::create([
                    'email' => $this->Order->getCustomer()->getEmail(),
                    'card' => $token
                ]);
                $CreditCard = new CreditCard();
                $CreditCard->setPayjpId($c->id);
                $Customer->addCreditCard($CreditCard);
                $this->entityManager->persist($Customer);
            }

            /** @var OrderItem $OrderItem */
            $OrderItem = $this->Order->getOrderItems()->filter(function (OrderItem $OrderItem) {
                return $OrderItem->isProduct();
            })->first();

            $subscription = \Payjp\Subscription::create([
                'customer' => $CreditCard->getPayjpId(),
                'plan' => $OrderItem->getProductClass()->getPlan()->getPlanId()
            ]);

            if (!isset($subscription['error'])) {
                $Subscription = new \Plugin\payjp4\Entity\Payjp\Subscription();
                $Subscription->setPayjpId($subscription->id);
                $Subscription->setCustomer($Customer);
                $Subscription->setOrderItem($OrderItem);
                $this->entityManager->persist($Subscription);

                $OrderItem->setSubscription($Subscription);
                $this->entityManager->persist($OrderItem);

                // purchaseFlow::commitを呼び出し、購入処理をさせる
                $this->purchaseFlow->commit($this->Order, new PurchaseContext());

                $result = new PaymentResult();
                $result->setSuccess(true);
            } else {
                $this->purchaseFlow->rollback($this->Order, new PurchaseContext());

                $result = new PaymentResult();
                $result->setSuccess(false);
                $result->setErrors([$subscription['error']['message']]);
            }
        } catch (\Exception $e) {
            $this->purchaseFlow->rollback($this->Order, new PurchaseContext());

            $result = new PaymentResult();
            $result->setSuccess(false);
            $result->setErrors([$e->getMessage()]);
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function apply()
    {
        // TODO: Implement apply() method.
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

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

namespace Plugin\payjp4\Tests\EventSubscriber;


use Eccube\Entity\Master\SaleType;
use Eccube\Entity\OrderItem;
use Eccube\Entity\ProductClass;
use Eccube\Event\EventArgs;
use Eccube\Service\CartService;
use Eccube\Service\PurchaseFlow\PurchaseContext;
use Eccube\Service\PurchaseFlow\PurchaseFlow;
use Eccube\Tests\EccubeTestCase;
use Plugin\payjp4\EventSubscriber\AddCartEventSubscriber;

class AddCartEventSubscriberTest extends EccubeTestCase
{
    /**
     * @var AddCartEventSubscriber
     */
    protected $subscriber;

    /**
     * @var CartService
     */
    protected $cartService;

    /**
     * @var SaleType
     */
    protected $saleType;

    /**
     * @var PurchaseFlow
     */
    protected $purchaseFlow;

    public function setUp()
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        $container = self::$kernel->getContainer();

        $this->cartService = $container->get(CartService::class);
        $this->subscriber = new AddCartEventSubscriber(
            $this->cartService
        );

        $this->saleType = $this->entityManager->getRepository(SaleType::class)
            ->findOneBy([
                'name' => trans('plugin.payjp.admin.sale_type.name')
            ]);

        $this->purchaseFlow = $this->container->get('eccube.purchase.flow.cart');
    }

    public function tearDown()
    {
        parent::tearDown(); // TODO: Change the autogenerated stub
    }

    public function test定期購入商品をカートに追加した場合カートがクリアされるか()
    {
        $Product = $this->createProduct(null, 0);

        $eventArgs = $this->createMock(EventArgs::class);
        $eventArgs->expects($this->once())
            ->method('getArgument')
            ->willReturn($Product);

        /** @var ProductClass $ProductClass */
        $ProductClass = $Product->getProductClasses()->first();
        $ProductClass->setSaleType($this->saleType);

        $this->cartService->addProduct($ProductClass);

        $Carts = $this->cartService->getCarts();
        foreach($Carts as $cart) {
            $this->purchaseFlow->validate($cart, new PurchaseContext());
        }
        $this->cartService->save();

        $this->subscriber->onFrontProductCartAddInitialize($eventArgs);

        self::assertEquals([], $this->cartService->getCarts());
    }

    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function test定期購入商品を再注文したら例外発生()
    {
        $Customer = $this->createCustomer();
        $Order = $this->createOrder($Customer);

        $Order->getOrderItems()->first()->getProductClass()->setSaleType($this->saleType);

        $eventArgs = $this->createMock(EventArgs::class);
        $eventArgs->expects($this->once())
            ->method('getArgument')
            ->willReturn($Order);

        $this->subscriber->onFrontMypageMypageOrderInitialize($eventArgs);
    }
}
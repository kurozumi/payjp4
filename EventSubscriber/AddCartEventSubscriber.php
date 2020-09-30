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

namespace Plugin\payjp4\EventSubscriber;


use Eccube\Entity\Order;
use Eccube\Entity\Product;
use Eccube\Entity\ProductClass;
use Eccube\Event\EccubeEvents;
use Eccube\Event\EventArgs;
use Eccube\Service\CartService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AddCartEventSubscriber implements EventSubscriberInterface
{
    /**
     * @var CartService
     */
    private $cartService;

    public function __construct(
        CartService $cartService
    )
    {
        $this->cartService = $cartService;
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        // TODO: Implement getSubscribedEvents() method.
        return [
            EccubeEvents::FRONT_PRODUCT_CART_ADD_INITIALIZE => 'onAddCart',
            EccubeEvents::FRONT_MYPAGE_MYPAGE_ORDER_INITIALIZE => 'onAddCart'
        ];
    }

    public function onFrontProductCartAddInitialize(EventArgs $args)
    {
        /** @var Product $Product */
        $Product = $args->getArgument('Product');

        /** @var ProductClass $productClass */
        foreach($Product->getProductClasses() as $productClass)
        {
            if($productClass->getSaleType()->getName() === trans('plugin.payjp.admin.sale_type.name')) {
                $this->cartService->clear();
            }
        }
    }

    public function onFrontMypageMypageOrderInitialize(EventArgs $args)
    {
        /** @var Order $Order */
        $Order = $args->getArgument('Order');

        foreach($Order->getSaleTypes() as $saleType) {
            if($saleType === trans('plugin.payjp.admin.sale_type.name')) {
                $this->cartService->clear();
            }
        }
    }
}

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
use Eccube\Exception\CartException;
use Eccube\Service\CartService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

    /**
     * 定期購入商品をカートに追加したときは追加前にカートクリア
     *
     * @param EventArgs $args
     */
    public function onFrontProductCartAddInitialize(EventArgs $args): void
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

    /**
     * 定期購入商品は再注文できない
     *
     * @param EventArgs $args
     * @throws CartException
     */
    public function onFrontMypageMypageOrderInitialize(EventArgs $args): void
    {
        /** @var Order $Order */
        $Order = $args->getArgument('Order');

        if(!$Order) {
            return;
        }

        foreach($Order->getSaleTypes() as $saleType) {
            if($saleType->getName() === trans('plugin.payjp.admin.sale_type.name')) {
                log_info('定期購入商品は再注文できません');
                throw new NotFoundHttpException();
            }
        }
    }
}

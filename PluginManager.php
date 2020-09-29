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

namespace Plugin\payjp4;


use Doctrine\ORM\EntityManagerInterface;
use Eccube\Entity\Master\SaleType;
use Eccube\Entity\Payment;
use Eccube\Plugin\AbstractPluginManager;
use Eccube\Util\StringUtil;
use Plugin\payjp4\Entity\Config;
use Plugin\payjp4\Entity\PaymentStatus;
use Plugin\payjp4\Service\Method\CreditCard;
use Symfony\Component\DependencyInjection\ContainerInterface;

class PluginManager extends AbstractPluginManager
{
    public function enable(array $meta, ContainerInterface $container)
    {
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $container->get('doctrine.orm.entity_manager');

        $Config = $entityManager->getRepository(Config::class)->get();
        if (!$Config) {
            $Config = new Config();
            $entityManager->persist($Config);
            $entityManager->flush();
        }

        $this->addEnv($container);
        $this->createPayment($container);
        $this->createSaleType($container);
        $this->createPaymentStatuses($container);
    }

    public function disable(array $meta, ContainerInterface $container)
    {
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $container->get('doctrine.orm.entity_manager');
        $paymentRepository = $entityManager->getRepository(Payment::class);

        $Payment = $paymentRepository->findOneBy(['method_class' => CreditCard::class]);
        if ($Payment) {
            $entityManager->remove($Payment);
            $entityManager->flush();
        }
    }

    private function addEnv(ContainerInterface $container)
    {
        $envFile = $container->getParameter('kernel.project_dir') . '/.env';
        $env = file_get_contents($envFile);
        $env = StringUtil::replaceOrAddEnv($env, [
            'PAYJP_PUBLIC_KEY' => '',
            'PAYJP_SECRET_KEY' => ''
        ]);
        file_put_contents($envFile, $env);

    }

    private function createPayment(ContainerInterface $container)
    {
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $container->get('doctrine.orm.entity_manager');
        $paymentRepository = $entityManager->getRepository(Payment::class);

        $Payment = $paymentRepository->findOneBy([], ['sort_no' => 'DESC']);
        $sortNo = $Payment ? $Payment->getSortNo() + 1 : 1;

        $Payment = $paymentRepository->findOneBy(['method_class' => CreditCard::class]);
        if ($Payment) {
            return;
        }

        $Payment = new Payment();
        $Payment->setCharge(0);
        $Payment->setSortNo($sortNo);
        $Payment->setVisible(true);
        $Payment->setMethod('クレジットカード');
        $Payment->setMethodClass(CreditCard::class);

        $entityManager->persist($Payment);
        $entityManager->flush();
    }

    private function createSaleType(ContainerInterface $container)
    {
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $container->get('doctrine.orm.entity_manager');
        $saleTypeRepository = $entityManager->getRepository(SaleType::class);

        $SaleType = $saleTypeRepository->findOneBy([], ['sort_no' => 'DESC']);
        $id = $SaleType ? $SaleType->getId() + 1 : 1;
        $sortNo = $SaleType ? $SaleType->getSortNo() + 1 : 1;

        $SaleType = $saleTypeRepository->findOneBy(['name' => '定期購入']);
        if ($SaleType) {
            return;
        }

        $SaleType = new SaleType();
        $SaleType->setId($id);
        $SaleType->setName('定期購入');
        $SaleType->setSortNo($sortNo);
        $entityManager->persist($SaleType);
        $entityManager->flush();
    }

    private function createMasterData(ContainerInterface $container, array $statuses, $class)
    {
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $container->get('doctrine.orm.entity_manager');
        $i = 0;
        foreach ($statuses as $id => $name) {
            $PaymentStatus = $entityManager->find($class, $id);
            if (!$PaymentStatus) {
                $PaymentStatus = new $class;
            }
            $PaymentStatus->setId($id);
            $PaymentStatus->setName($name);
            $PaymentStatus->setSortNo($i++);
            $entityManager->persist($PaymentStatus);
        }
        $entityManager->flush();
    }

    private function createPaymentStatuses(ContainerInterface $container)
    {
        $statuses = [
            PaymentStatus::OUTSTANDING => '未決済',
            PaymentStatus::ENABLED => '有効性チェック済',
            PaymentStatus::PROVISIONAL_SALES => '決済完了',
            PaymentStatus::ACTUAL_SALES => '実売上',
            PaymentStatus::CANCEL => 'キャンセル'
        ];
        $this->createMasterData($container, $statuses, PaymentStatus::class);
    }
}

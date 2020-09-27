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

namespace Plugin\payjp4\Form\Extension;


use Eccube\Entity\Order;
use Eccube\Form\Type\Shopping\OrderType;
use Plugin\payjp4\Service\Method\CreditCard;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class CreditCardExtension extends AbstractTypeExtension
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['skip_add_form']) {
            return;
        }

        $builder
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $form = $event->getForm();

                $form->add('payjp_token', HiddenType::class, [
                    'error_bubbling' => false,
                    'mapped' => true
                ]);
            });

        $builder
            ->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
                $form = $event->getForm();
                $data = $event->getData();

                if (!$data instanceof Order) {
                    return;
                }

                if ($data->getPayment()->getMethodClass() === CreditCard::class) {
                    if(!$form->get('payjp_token')->getData()) {
                        $form->get('payjp_token')->addError(new FormError("クレジットカード情報を入力してください"));
                    }
                }
            });
    }

    /**
     * @inheritDoc
     */
    public function getExtendedType()
    {
        // TODO: Implement getExtendedType() method.
        return OrderType::class;
    }

    /**
     * @return iterable
     */
    public function getExtendedTypes(): iterable
    {
        return [OrderType::class];
    }
}

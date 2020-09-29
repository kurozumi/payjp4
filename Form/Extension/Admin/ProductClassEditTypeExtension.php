<?php
/**
 * This file is part of SocialLogin4
 *
 * Copyright(c) Akira Kurozumi <info@a-zumi.net>
 *
 *  https://a-zumi.net
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\payjp4\Form\Extension\Admin;


use Eccube\Entity\ProductClass;
use Eccube\Form\Type\Admin\ProductClassEditType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class ProductClassEditTypeExtension extends AbstractTypeExtension
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
                $form = $event->getForm();
                /** @var ProductClass $data */
                $data = $event->getData();

                if($data->getSaleType()->getName() === trans('plugin.payjp.admin.sale_type.name')) {
                    $form->get('sale_type')->addError(new FormError(trans('plugin.payjp.admin.sale_type.error')));
                }
            });
    }

    /**
     * @inheritDoc
     */
    public function getExtendedType()
    {
        // TODO: Implement getExtendedType() method.
        return ProductClassEditType::class;
    }

    /**
     * @return iterable
     */
    public function getExtendedTypes(): iterable
    {
        return [ProductClassEditType::class];
    }
}

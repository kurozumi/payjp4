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

namespace Plugin\payjp4\Form\Extension\Admin;


use Eccube\Form\Type\Admin\ProductType;
use Plugin\payjp4\Entity\Plan;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;

class ProductTypeExtension extends AbstractTypeExtension
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('payjp_plan', EntityType::class, [
                'label' => '定期購入プラン',
                'class' => Plan::class,
                'choice_label' => 'name',
                'placeholder' => 'common.select__unspecified',
                'eccube_form_options' => [
                    'auto_render' => true
                ]
            ]);
    }

    /**
     * @return string
     */
    public function getExtendedType(): string
    {
        // TODO: Implement getExtendedType() method.
        return ProductType::class;
    }

    /**
     * @return iterable
     */
    public function getExtendedTypes(): iterable
    {
        return [ProductType::class];
    }
}

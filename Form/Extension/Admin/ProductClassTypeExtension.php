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


use Doctrine\ORM\EntityManagerInterface;
use Eccube\Common\EccubeConfig;
use Eccube\Entity\ProductClass;
use Eccube\Form\Type\Admin\ProductClassType;
use Eccube\Form\Type\PriceType;
use Payjp\Payjp;
use Payjp\Plan;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ProductClassTypeExtension extends AbstractTypeExtension
{
    /**
     * @var EccubeConfig
     */
    private $eccubeConfig;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(
        EccubeConfig $eccubeConfig,
        EntityManagerInterface $entityManager,
        SessionInterface $session
    )
    {
        $this->eccubeConfig = $eccubeConfig;
        $this->entityManager = $entityManager;
        $this->session = $session;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('interval', ChoiceType::class, [
                'label' => 'plugin.payjp.admin.product_class_type.interval.label',
                'choices' => [
                    'plugin.payjp.admin.product_class_type.interval.choices.month' => 'month',
                    'plugin.payjp.admin.product_class_type.interval.choices.year' => 'year'
                ],
                'expanded' => false,
                'mapped' => false,
                'placeholder' => 'common.select__unspecified',
                'eccube_form_options' => [
                    'auto_render' => true
                ]
            ])
            ->add('billing_day', ChoiceType::class, [
                'label' => 'plugin.payjp.admin.product_class_type.billing_day.label',
                'choices' => array_combine(range(1, 31), range(1, 31)),
                'mapped' => false,
                'placeholder' => 'common.select__unspecified',
                'eccube_form_options' => [
                    'auto_render' => true
                ]
            ]);

        $builder
            ->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
                $form = $event->getForm();
                /** @var ProductClass $data */
                $data = $event->getData();

                if ($data instanceof ProductClass) {
                    if ($data->getSaleType()->getName() !== trans('plugin.payjp.admin.sale_type.name')) {
                        return;
                    }

                    if ($Plan = $data->getPlan()) {
                        $form
                            ->add('price02', PriceType::class, [
                                'attr' => [
                                    'readonly' => 'readonly'
                                ]
                            ])
                            ->add('interval', TextType::class, [
                                'data' =>
                                    $Plan->getChargeInterval() === 'month' ?
                                        trans('plugin.payjp.admin.product_class_type.interval.choices.month') :
                                        trans('plugin.payjp.admin.product_class_type.interval.choices.year'),
                                'mapped' => false,
                                'attr' => [
                                    'readonly' => 'readonly'
                                ],
                                'eccube_form_options' => [
                                    'auto_render' => true
                                ]
                            ])
                            ->add('billing_day', TextType::class, [
                                'data' => $Plan->getBillingDay() ? sprintf("%s日", $Plan->getBillingDay()) : trans('common.select__unspecified'),
                                'mapped' => false,
                                'attr' => [
                                    'readonly' => 'readonly'
                                ],
                                'eccube_form_options' => [
                                    'auto_render' => true
                                ]
                            ]);
                    }
                }
            });

        $builder
            ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
                $form = $event->getForm();
                /** @var ProductClass $data */
                $data = $event->getData();

                if (!$form->isValid()) {
                    return;
                }

                Payjp::setApiKey($this->eccubeConfig['payjp_secret_key']);

                // 定期購入以外の販売種別を登録したらプランを削除
                if ($data->getSaleType()->getName() !== trans('plugin.payjp.admin.sale_type.name')) {
                    if ($Plan = $data->getPlan()) {
                        $data->setPlan(null);
                        $this->entityManager->remove($Plan);

                        // PAY.JPからプランを削除
                        // TODO: 一度でも課金されたらPAY.JPから削除できない。
                        Plan::retrieve($Plan->getPlanId())->delete();
                    }
                    return;
                }

                if (!$form->get('interval')->getData()) {
                    $form->get('interval')->addError(new FormError(trans('plugin.payjp.admin.interval.error')));
                    return;
                }

                if ($form->get('sale_limit')->getData() != 1) {
                    $form->get('sale_limit')->addError(new FormError(trans('plugin.payjp.admin.sale_limit.error')));
                    return;
                }

                try {
                    if ($data->getPlan()) {
                        $p = Plan::retrieve($data->getPlan()->getPlanId());
                        $p->name = $data->formattedProductName();
                        $p->save();
                    } else {
                        $p = Plan::create([
                            'amount' => $data->getPrice02IncTax() + $data->getDeliveryFee(),
                            'currency' => $this->eccubeConfig['currency'],
                            'interval' => $form->get('interval')->getData(),
                            'name' => $data->formattedProductName(),
                            'billing_day' => $form->get('billing_day')->getData()
                        ]);

                        $Plan = new \Plugin\payjp4\Entity\Payjp\Plan();
                        $Plan
                            ->setPlanId($p->id)
                            ->setName($p->name)
                            ->setAmount($p->amount)
                            ->setCurrency($p->currency)
                            ->setChargeInterval($p->interval)
                            ->setBillingDay($p->billing_day)
                            ->setTrialDays($p->trial_days)
                            ->setCreated(new \DateTime('@' . $p->created));
                        $this->entityManager->persist($Plan);
                        $this->entityManager->flush();

                        $data->setPlan($Plan);
                    }
                } catch (\Exception $e) {
                    // 新規作成時にエラーが発生したらPAY.JPのプラン削除
                    if (!$data->getPlan() && isset($p)) {
                        Plan::retrieve($p->id)->delete();
                    }

                    $this->session->getFlashBag()->add('eccube.admin.error', $e->getMessage());
                    $form->addError(new FormError($e->getMessage()));
                }
            });
    }

    /**
     * @return string
     */
    public function getExtendedType(): string
    {
        // TODO: Implement getExtendedType() method.
        return ProductClassType::class;
    }

    /**
     * @return iterable
     */
    public function getExtendedTypes(): iterable
    {
        return [ProductClassType::class];
    }
}

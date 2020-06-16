<?php

namespace Plugin\PayJP\Form\Type;

use Eccube\Common\EccubeConfig;
use Payjp\Payjp;
use Plugin\PayJP\Entity\Plan;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class PlanType extends AbstractType
{
    /**
     * @var EccubeConfig
     */
    private $eccubeConfig;

    public function __construct(
        EccubeConfig $eccubeConfig
    )
    {
        $this->eccubeConfig = $eccubeConfig;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('amount', IntegerType::class, [
                'label' => '金額',
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('charge_interval', ChoiceType::class, [
                'label' => '課金間隔',
                'choices' => [
                    '月次課金' => 'month',
                    '年次課金' => 'year'
                ],
                'expanded' => false
            ])
            ->add('name', TextType::class, [
                'label' => 'プラン名',
            ])
            ->add('trial_days', ChoiceType::class, [
                'label' => 'トライアル日数',
                'choices' => array_combine(range(0, 365), range(0, 365)),
                'expanded' => false
            ])
            ->add('billing_day', ChoiceType::class, [
                'label' => '課金日',
                'required' => false,
                'choices' => array_combine(range(1, 31), range(1, 31))
            ]);

        $builder
            ->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
                $form = $event->getForm();
                $data = $event->getData();

                if ($form->get('charge_interval')->getData() == 'month') {
                    if (!$form->get('billing_day')->getData()) {
                        $form->get('billing_day')->addError(new FormError('不正な課金日です。'));
                    }
                }
            });

        $builder
            ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
                $data = $event->getData();
                $form = $event->getForm();

                if (!$data instanceof Plan) {
                    return;
                }

                if (!$form->isValid()) {
                    return;
                }

                try {
                    Payjp::setApiKey($this->eccubeConfig['payjp_secret_key']);
                    $plan = \Payjp\Plan::create([
                        'amount' => $data->getAmount(),
                        'currency' => $data->getCurrency(),
                        'interval' => $data->getChargeInterval(),
                        'plan_id' => $data->getPlanId(),
                        'name' => $data->getName(),
                        'trial_days' => $data->getTrialDays(),
                    ]);

                    $data->setPlanId($plan["id"]);
                    $data->setCreated(strtotime("now"));

                } catch (\Exception $e) {
                    $form->get('amount')->addError(new FormError($e->getMessage()));
                }
            });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Plan::class,
        ]);
    }
}

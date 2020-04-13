<?php


namespace Plugin\PayJP\Form\Extension;


use Eccube\Entity\Order;
use Eccube\Form\Type\Shopping\OrderType;
use Plugin\PayJP\Service\Method\CreditCard;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;

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
}
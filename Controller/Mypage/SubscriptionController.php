<?php


namespace Plugin\PayJP\Controller\Mypage;


use Eccube\Controller\AbstractController;
use Eccube\Entity\Customer;
use Payjp\Payjp;
use Payjp\Subscription;
use Plugin\PayJP\Entity\Plan;
use Plugin\PayJP\Entity\SubscriptionStatus;
use Plugin\PayJP\Repository\PlanRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SubscriptionController extends AbstractController
{
    /**
     * @var PlanRepository
     */
    private $planRepository;

    public function __construct(
        PlanRepository $planRepository
    ) {
        Payjp::setApiKey($this->eccubeConfig['payjp_secret_key']);

        $this->planRepository = $planRepository;
    }

    /**
     * @Route("/mypage/subscription", name="payjp_mypage_subscription")
     * @Template("@PayJP/Mypage/subscription.twig")
     */
    public function index()
    {
        $Plans = $this->planRepository->findAll();

        return [
            'Plans' => $Plans
        ];
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/mypage/subscription/{id}/join", name="payjp_mypage_subscription_join", methods={"GET|POST"})
     * @Template("@PayJP/Mypage/subscription_join.twig")
     */
    public function join(Request $request, Plan $plan)
    {
        /** @var Customer $Customer */
        $Customer = $this->getUser();

        if($Customer->getPayjpSubscriptionStatus() === SubscriptionStatus::ACTIVE) {
            return $this->redirectToRoute("payjp_mypage_subscription");
        }

        $builder = $this->createFormBuilder();
        $builder->add('payjp_token', HiddenType::class, [
            'error_bubbling' => true
        ]);

        $form = $builder->getForm();

        if($form->isSubmitted() && $form->isValid()) {
            $token = $form->get('payjp_token')->getData();

            try{
                $PayJPCustomer = \Payjp\Customer::retrieve($Customer->getId());
            } catch (\Exception $e) {
                $PayJPCustomer = \Payjp\Customer::create([
                    "id" => $Customer->getId(),
                    "email" => $Customer->getEmail(),
                    "card" => $token
                ]);
            }

            try {
                $plan = \Payjp\Plan::retrieve($plan);
                $sub = Subscription::create([
                    "customer" => $PayJPCustomer["id"],
                    "plan" => $plan
                ]);
            } catch (\Exception $e) {
                throw new $e;
            }

            $Customer->setPayjpSubId($sub['id']);
            $Customer->setPayjpSubStatus($sub['status']);
            $this->entityManager->persist($Customer);
            $this->entityManager->flush();

            return $this->redirectToRoute('payjp_mypage_subscription');
        }

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/mypage/subscription/cancel", name="payjp_mypage_subscription_cancel", methods={"GET|POST"})
     *
     */
    public function cancel(Request $request)
    {
        /** @var Customer $Customer */
        $Customer = $this->getUser();

        if($Customer->getPayjpSubStatus() === SubscriptionStatus::CANCELED) {
            return $this->redirectToRoute("payjp_mypage_subscription");
        }

        $form = $this->createFormBuilder()->getForm();
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            if($Customer->getPayjpSubId()) {
                try {
                    $sub = Subscription::retrieve($Customer->getPayjpSubId());
                    $cancel = $sub->cancel();
                } catch (\Exception $e) {
                    throw new $e;
                }

                $Customer->setPayjpSubStatus($cancel['status']);
                $this->entityManager->persist($Customer);
                $this->entityManager->flush();
            }

            return $this->redirectToRoute("payjp_mypage_subscription");
        }

        return [
            'form' => $form->createView()
        ];
    }

}
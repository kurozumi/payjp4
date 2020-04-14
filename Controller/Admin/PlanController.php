<?php

namespace Plugin\PayJP\Controller\Admin;

use Eccube\Controller\AbstractController;
use Payjp\Payjp;
use Plugin\PayJP\Entity\Plan;
use Plugin\PayJP\Form\Type\PlanType;
use Plugin\PayJP\Repository\PlanRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class PlanController extends AbstractController
{
    /**
     * @var PlanRepository
     */
    private $planRepository;

    public function __construct(
        PlanRepository $planRepository
    )
    {
        $this->planRepository = $planRepository;
    }

    /**
     * @param Request $request
     *
     * @Route("/%eccube_admin_route%/payjp/plan", name="admin_payjp_plan")
     * @Template("@PayJP/admin/Plan/index.twig")
     */
    public function index(Request $request)
    {
        $Plans = $this->planRepository->findAll();

        return [
            'Plans' => $Plans
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/payjp/plan/create", name="admin_payjp_plan_create")
     * @Template("@PayJP/admin/Plan/create.twig")
     */
    public function create(Request $request)
    {
        $Plan = new Plan();

        $form = $this->createForm(PlanType::class, $Plan);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $Plan = $form->getData();
            $this->entityManager->persist($Plan);
            $this->entityManager->flush();
            $this->addSuccess('admin.common.save_complete', 'admin');

            return $this->redirectToRoute('admin_payjp_plan_edit', ["id" => $Plan->getId()]);
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/payjp/plan/{id}/edit", requirements={"id" = "\d+"}, name="admin_payjp_plan_edit")
     * @Template("@PayJP/admin/Plan/edit.twig")
     */
    public function edit(Request $request, Plan $Plan)
    {
        if (!$Plan) {
            throw new NotFoundHttpException();
        }

        $builder = $this->createFormBuilder();
        $builder->add('name', TextType::class, [
            'label' => 'プラン名',
            'data' => $Plan->getName()
        ]);

        $form = $builder->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $name = $form->get('name')->getData();

            try {
                Payjp::setApiKey($this->eccubeConfig['payjp_secret_key']);
                $p = \Payjp\Plan::retrieve($Plan->getPlanId());
                $p->name = $name;
                $p->save();
            } catch (\Exception $e) {
                $this->addError($e->getMessage(), 'admin');
                return $this->redirectToRoute('admin_payjp_plan_edit', ["id" => $Plan->getId()]);
            }

            $Plan->setName($name);
            $this->entityManager->persist($Plan);
            $this->entityManager->flush();
            $this->addSuccess('admin.common.save_complete', 'admin');

            return $this->redirectToRoute('admin_payjp_plan_edit', ["id" => $Plan->getId()]);
        }

        return [
            'form' => $form->createView(),
            'Plan' => $Plan
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/payjp/plan/{id}/delete", requirements={"id" = "\d+"}, name="admin_payjp_plan_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Plan $Plan)
    {
        $this->isTokenValid();

        if(!$Plan) {
            throw new NotFoundHttpException();
        }

        try {
            Payjp::setApiKey($this->eccubeConfig['payjp_secret_key']);
            $p = \Payjp\Plan::retrieve($Plan->getPlanId());
            $p->delete();
        }catch(\Exception $e) {
            $this->addError($e->getMessage(), 'admin');
            return $this->redirectToRoute('admin_payjp_plan_edit', ["id" => $Plan->getId()]);
        }

        $this->entityManager->remove($Plan);
        $this->entityManager->flush();

        $this->addSuccess('admin.common.delete_complete', 'admin');
        return $this->redirectToRoute('admin_payjp_plan');
    }
}

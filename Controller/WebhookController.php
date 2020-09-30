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

namespace Plugin\payjp4\Controller;


use Eccube\Controller\AbstractController;
use Plugin\payjp4\Entity\Webhook;
use Plugin\payjp4\Repository\ConfigRepository;
use Plugin\payjp4\Repository\WebhookRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class WebhookController
 * @package Plugin\payjp4\Controller
 *
 * @Route("/payjp")
 */
class WebhookController extends AbstractController
{
    /**
     * @var ConfigRepository
     */
    private $configRepository;

    /**
     * @var WebhookRepository
     */
    private $webhookRepository;

    public function __construct(
        ConfigRepository $configRepository,
        WebhookRepository $webhookRepository
    )
    {
        $this->configRepository = $configRepository;
        $this->webhookRepository = $webhookRepository;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @Route("/webhook", name="payjp_webhook")
     */
    public function index(Request $request)
    {
        $token = $request->headers->get('X-Payjp-Webhook-Token');
        if ($token !== $this->configRepository->get()->getWebhookToken()) {
            throw new NotFoundHttpException();
        }

        $data = json_decode($request->getContent(), true);

        $Webhook = $this->webhookRepository->findOneBy([
            'event_id' => $data['id']
        ]);

        if($Webhook) {
            return new Response();
        }

        $Webhook = new Webhook();
        $Webhook->setEventId($data['id']);
        $Webhook->setType($data['type']);
        $Webhook->setData($request->getContent());
        $this->entityManager->persist($Webhook);
        $this->entityManager->flush();

        return new Response();
    }
}

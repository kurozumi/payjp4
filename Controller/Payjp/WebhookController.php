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

namespace Plugin\payjp4\Controller\Payjp;


use Eccube\Controller\AbstractController;
use Plugin\payjp4\Entity\Payjp\Event;
use Plugin\payjp4\Repository\ConfigRepository;
use Plugin\payjp4\Repository\Payjp\EventRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class WebhookController
 * @package Plugin\payjp4\Controller\Payjp
 */
class WebhookController extends AbstractController
{
    /**
     * @var ConfigRepository
     */
    private $configRepository;

    /**
     * @var EventRepository
     */
    private $eventRepository;

    public function __construct(
        ConfigRepository $configRepository,
        EventRepository $eventRepository
    )
    {
        $this->configRepository = $configRepository;
        $this->eventRepository = $eventRepository;
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

        $Event = $this->eventRepository->findOneBy([
            'payjp_id' => $data['id']
        ]);

        if($Event) {
            return new Response();
        }

        $Event = new Event();
        $Event->setEventId($data['id']);
        $Event->setType($data['type']);
        $Event->setData($request->getContent());
        $this->entityManager->persist($Event);
        $this->entityManager->flush();

        return new Response();
    }
}

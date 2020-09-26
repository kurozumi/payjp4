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

namespace Plugin\payjp4\Controller\Admin;

use Eccube\Controller\AbstractController;
use Eccube\Util\CacheUtil;
use Eccube\Util\StringUtil;
use Plugin\payjp4\Entity\Config;
use Plugin\payjp4\Form\Type\Admin\ConfigType;
use Plugin\payjp4\Repository\ConfigRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ConfigController extends AbstractController
{
    /**
     * @var ConfigRepository
     */
    protected $configRepository;

    /**
     * ConfigController constructor.
     *
     * @param ConfigRepository $configRepository
     */
    public function __construct(ConfigRepository $configRepository)
    {
        $this->configRepository = $configRepository;
    }

    /**
     * @Route("/%eccube_admin_route%/payjp/config", name="payjp_admin_config")
     * @Template("@payjp4/admin/config.twig")
     */
    public function index(Request $request, CacheUtil $cacheUtil)
    {
        $Config = $this->configRepository->get();
        $form = $this->createForm(ConfigType::class, $Config);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Config $Config */
            $Config = $form->getData();
            $this->entityManager->persist($Config);
            $this->entityManager->flush();

            $envFile = $this->getParameter('kernel.project_dir').'/.env';
            $env = file_get_contents($envFile);

            $env = StringUtil::replaceOrAddEnv($env, [
                'PAYJP_PUBLIC_KEY' => $Config->getPublicKey(),
                'PAYJP_SECRET_KEY' => $Config->getSecretKey()
            ]);

            file_put_contents($envFile, $env);

            $cacheUtil->clearCache();

            $this->addSuccess('登録しました。', 'admin');

            return $this->redirectToRoute('payjp_admin_config');
        }

        return [
            'form' => $form->createView(),
        ];
    }
}

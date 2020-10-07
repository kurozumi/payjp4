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

namespace Plugin\payjp4\Command\Payjp\Subscription;

use Plugin\payjp4\Repository\Payjp\EventRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Renewed
 * @package Plugin\payjp4\Command\Payjp\Subscription
 */
class Renewed extends Command
{
    protected static $defaultName = 'payjp:subscription:renewed';

    /**
     * @var EventRepository
     */
    private $eventRepository;

    public function __construct(
        EventRepository $eventRepository
    )
    {
        parent::__construct();
        $this->eventRepository = $eventRepository;
    }

    protected function configure()
    {
        $this
            ->setDescription('update subscription.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $Events = $this->eventRepository->findBy([
            'type' => 'subscription.renewed'
        ]);

    }
}

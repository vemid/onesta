<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Console\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Vemid\ProjectOne\Common\Mailer\MailManagerInterface;

/**
 * Class TestEmail
 * @package Vemid\ProjectOne\Common\Console\Command
 */
class TestEmailCommand extends CommandBase
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('test:email')
            ->setDescription('Sending Test Email');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $mailer = $this->container->get(MailManagerInterface::class);

        $message = $mailer->createMessage();
        $message->setBody('test');
        $message->setTo('darko.vesic@arbor-education.com', 'darko');
        $message->setSubject('cron');
        $message->send();
    }
}

<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Admin\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Vemid\ProjectOne\Admin\Form\Filter\ClientFilterForm;
use Vemid\ProjectOne\Admin\Form\Filter\CodeFilterForm;
use Vemid\ProjectOne\Common\Form\FormBuilderInterface;
use Vemid\ProjectOne\Common\Message\Builder;
use Vemid\ProjectOne\Common\Route\AbstractHandler;
use \Vemid\ProjectOne\Entity\Entity\Code as EntityCode;

/**
 * Class Client
 * @package Vemid\ProjectOne\Admin\Handler
 */
class Client extends AbstractHandler
{
    public function list(ClientFilterForm $clientFilterForm): void
    {
        $this->view->setTemplate('client::list.html.twig', [
            'form' => $clientFilterForm->generate()
        ]);
    }

}

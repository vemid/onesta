<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Main\Handler;

use Vemid\ProjectOne\Common\Route\AbstractHandler;
use Vemid\ProjectOne\Entity\Entity\User as EntityUser;

/**
 * Class Index
 * @package Vemid\ProjectOne\Main\Handler
 */
class Index extends AbstractHandler
{
    /**
     * {@inheritDoc}
     */
    public function index()
    {
        $this->view->setTemplate('index::index.html.twig');
    }
}

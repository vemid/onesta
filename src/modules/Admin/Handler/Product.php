<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Admin\Handler;

use Vemid\ProjectOne\Common\Route\AbstractHandler;

/**
 * Class Product
 * @package Vemid\ProjectOne\Admin\Handler
 */
class Product extends AbstractHandler
{
    public function list()
    {
        $this->view->setTemplate('product::list.html.twig', []);
    }
}
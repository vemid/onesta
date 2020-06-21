<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Admin\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Vemid\ProjectOne\Admin\Form\Filter\CodeFilterForm;
use Vemid\ProjectOne\Common\Form\FormBuilderInterface;
use Vemid\ProjectOne\Common\Message\Builder;
use Vemid\ProjectOne\Common\Route\AbstractHandler;
use \Vemid\ProjectOne\Entity\Entity\Code as EntityCode;

/**
 * Class PurchaseWrite
 * @package Vemid\ProjectOne\Admin\Handler
 */
class PurchaseWrite extends AbstractHandler
{
    public function create(FormBuilderInterface $formBuilder, EntityManagerInterface $entityManager): void
    {

    }
}

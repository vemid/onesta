<?php


namespace Vemid\ProjectOne\Admin\Handler;


use Doctrine\ORM\EntityManagerInterface;
use Vemid\ProjectOne\Common\Form\FormBuilderInterface;
use Vemid\ProjectOne\Common\Route\AbstractHandler;

/**
 * Class PaymentInstallment
 * @package Vemid\ProjectOne\Admin\Handler
 */
class PaymentInstallment extends AbstractHandler
{
    public function update($id, EntityManagerInterface $entityManager, FormBuilderInterface $formBuilder)
    {
    }
}
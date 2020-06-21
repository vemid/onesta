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
    public function list(CodeFilterForm $codeFilterForm): void
    {
        $this->view->setTemplate('purchase::list.html.twig', [
            'form' => $codeFilterForm->generate()
        ]);
    }

    public function create(FormBuilderInterface $formBuilder): void
    {
        $this->view->setTemplate('code::create.html.twig', [
            'form' => $formBuilder->build(new EntityCode())
        ]);
    }

    public function update($id, EntityManagerInterface $entityManager, FormBuilderInterface $formBuilder): void
    {
        /** @var $code EntityCode */
        if (!$code = $entityManager->find(EntityCode::class, (int)$id)) {
            $this->messageBag->pushFlashMessage($this->translator->_('Hm, izgleda da ne postoji traženi dobavljač'), null, Builder::WARNING);
        }

        $this->view->setTemplate('code::update.html.twig', [
            'form' => $formBuilder->build($code)
        ]);
    }
}

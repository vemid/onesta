<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Admin\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Vemid\ProjectOne\Admin\Form\Filter\CodeFilterForm;
use Vemid\ProjectOne\Common\Form\FormBuilderInterface;
use Vemid\ProjectOne\Common\Message\Builder;
use Vemid\ProjectOne\Common\Route\AbstractHandler;
use \Vemid\ProjectOne\Entity\Entity\Purchase as EntityPurchase;
use \Vemid\ProjectOne\Entity\Entity\Client as EntityClient;

/**
 * Class Purchase
 * @package Vemid\ProjectOne\Admin\Handler
 */
class Purchase extends AbstractHandler
{
    public function list(CodeFilterForm $codeFilterForm): void
    {
        $this->view->setTemplate('purchase::list.html.twig', [
            'form' => $codeFilterForm->generate()
        ]);
    }

    public function create(FormBuilderInterface $formBuilder): void
    {
        $clientForm = $formBuilder->build(new EntityClient());
        $form = $formBuilder->build(new EntityPurchase());

        $clientForm->addHidden('id');

        foreach ($form->getComponents() as $component) {
            $type = $component->getControl()->getAttribute('type');
            $name = $component->getControl()->getAttribute('name');
            if ($type === 'hidden' && $name !== 'id') {
                continue;
            }

            $form->removeComponent($component);
            $clientForm->addComponent($component, $component->getName());
        }

        $this->view->setTemplate('code::create.html.twig', [
            'form' => $clientForm
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

<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Admin\Handler;

use Vemid\ProjectOne\Admin\Form\Filter\CodeFilterForm;
use Vemid\ProjectOne\Common\Form\FormBuilderInterface;
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

        foreach ($form->getComponents() as $component) {
            $type = $component->getControl()->getAttribute('type');
            $name = $component->getControl()->getAttribute('name');
            if ($type === 'hidden' && $name !== 'client') {
                continue;
            }

            $form->removeComponent($component);
            $clientForm->addComponent($component, $component->getName());
        }

        $guarantorComponent = $clientForm->getComponent('guarantor');
        $clientForm->removeComponent($guarantorComponent);
        $clientForm->addComponent($guarantorComponent, 'guarantorId', 'type');
        $clientForm->addHidden('guarantor')->setHtmlAttribute('id', 'guarantor');

        $this->view->setTemplate('code::create.html.twig', [
            'form' => $clientForm
        ]);
    }
}

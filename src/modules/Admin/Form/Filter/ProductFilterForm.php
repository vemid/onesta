<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Admin\Form\Filter;

use Nette\Forms\Form;
use Vemid\ProjectOne\Common\Form\AbstractForm;
use Vemid\ProjectOne\Entity\Entity\Product;
use Vemid\ProjectOne\Entity\EntityInterface;

/**
 * Class ProductFilter
 * @package Vemid\ProjectOne\Admin\Form\Filter
 */
class ProductFilterForm extends AbstractForm
{

    /**
     * @inheritDoc
     */
    public function generate(EntityInterface $entity = null): Form
    {
        /** @var $entity Product */

        $codes = $this->entityManager->getRepository(Product::class)->findByUniqueCode();

        $options = ['' => '-- Izaberite --'];
        foreach ($codes as $code) {
            $options[$code->getId()] = (string)$code;
        }

        $this->form
            ->addSelect('code', $this->translator->_('Kategorija'))
            ->setItems($options)
            ->setHtmlAttribute('class', 'form-control chosen-search');

        $this->form
            ->addText('name', $this->translator->_('Ime'))
            ->setHtmlAttribute('class', 'form-control');

        $this->form
            ->addText('description', $this->translator->_('Opis'))
            ->setHtmlAttribute('class', 'form-control');

        return $this->form;
    }
}
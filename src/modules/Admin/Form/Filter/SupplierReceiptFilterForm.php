<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Admin\Form\Filter;

use Nette\Forms\Form;
use Vemid\ProjectOne\Common\Form\AbstractForm;
use Vemid\ProjectOne\Entity\Entity\Product;
use Vemid\ProjectOne\Entity\Entity\Supplier;
use Vemid\ProjectOne\Entity\EntityInterface;

/**
 * Class ProductFilter
 * @package Vemid\ProjectOne\Admin\Form\Filter
 */
class SupplierReceiptFilterForm extends AbstractForm
{

    /**
     * @inheritDoc
     */
    public function generate(EntityInterface $entity = null): Form
    {
        /** @var $entity Product */

        $suppliers = $this->entityManager->getRepository(Supplier::class)->findAll();

        $options = ['' => '-- Izaberite --'];
        foreach ($suppliers as $supplier) {
            $options[$supplier->getId()] = (string)$supplier;
        }

        $this->form
            ->addSelect('supplier', $this->translator->_('Dobavljači'))
            ->setItems($options)
            ->setHtmlAttribute('class', 'form-control chosen-search');

        $this->form
            ->addText('fromDate', $this->translator->_('Od datuma'))
            ->setHtmlAttribute('class', 'form-control datepicker')
            ->setHtmlAttribute('placeholder', $this->translator->_('Od datuma'));

        $this->form
            ->addText('toDate', $this->translator->_('Do datuma'))
            ->setHtmlAttribute('class', 'form-control datepicker')
            ->setHtmlAttribute('placeholder', $this->translator->_('Do datuma'));

        return $this->form;
    }
}
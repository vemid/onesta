<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Admin\Form\Filter;

use Nette\Forms\Form;
use Vemid\ProjectOne\Common\Form\AbstractForm;
use Vemid\ProjectOne\Entity\Entity\Code;
use Vemid\ProjectOne\Entity\Entity\CodeType;
use Vemid\ProjectOne\Entity\Entity\Product;
use Vemid\ProjectOne\Entity\EntityInterface;

/**
 * Class SupplierFilterForm
 * @package Vemid\ProjectOne\Admin\Form\Filter
 */
class CodeFilterForm extends AbstractForm
{
    /**
     * @inheritDoc
     */
    public function generate(EntityInterface $entity = null): Form
    {
        /** @var Code $entity */

        $parentCodes = $this->entityManager->getRepository(Code::class)->findParentByUniqueCode();
        $codeTypes = $this->entityManager->getRepository(Code::class)->findByUniqueCodeType();

        $options = ['' => '-- Izaberite --'];
        foreach ($codeTypes as $codeType) {
            $options[$codeType->getId()] = (string)$codeType;
        }

        $parentOptions = ['' => '-- Izaberite --'];
        foreach ($parentCodes as $parentCode) {
            $parentOptions[$parentCode->getId()] = (string)$parentCode;
        }

        $this->form
            ->addSelect('type', $this->translator->_('Tip'))
            ->setItems($options)
            ->setHtmlAttribute('class', 'form-control chosen-search');

        $this->form
            ->addSelect('parent', $this->translator->_('Pod kategorije'))
            ->setItems($parentOptions)
            ->setHtmlAttribute('class', 'form-control chosen-search');

        $this->form
            ->addText('name', $this->translator->_('Ime'))
            ->setHtmlAttribute('class', 'form-control');

        $this->form
            ->addText('code', $this->translator->_('Kod'))
            ->setHtmlAttribute('class', 'form-control');

        return $this->form;
    }
}
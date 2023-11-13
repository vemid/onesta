<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Form;

use Doctrine\ORM\EntityManagerInterface;
use Nette\Forms\Form;
use Vemid\ProjectOne\Common\Translator\TranslationInterface;
use Vemid\ProjectOne\Entity\EntityInterface;

/**
 * Class AbstractForm
 * @package Vemid\ProjectOne\Common\Form
 */
abstract class AbstractForm
{
    /** @var Form */
    protected $form;

    /** @var TranslationInterface  */
    protected $translator;

    /** @var EntityManagerInterface */
    protected $entityManager;

    /**
     * AbstractForm constructor.
     * @param EntityManagerInterface $entityManager
     * @param TranslationInterface $translator
     */
    public function __construct(EntityManagerInterface $entityManager, TranslationInterface $translator)
    {
        $this->translator = $translator;
        $this->entityManager = $entityManager;

        $this->form = new Form(sha1(static::class));
    }

    /**
     * @return Form
     */
    public function getForm()
    {
        if ($this->form->isSubmitted()) {
            return $this->form;
        }

        if (count($this->form->getComponents()) > 2) {
            return $this->form;
        }

        return $this->generate();
    }

    /**
     * @param Form $form
     */
    public function mergeForm(Form $form)
    {
        foreach($form->getComponents(true) as $item => $object)
        {
            $this->form[$item] = $object->setParent($this->form->getParent(), $this->form->getName());
        }
    }

    /**
     * @param EntityInterface|null $entity
     * @return Form
     */
    abstract public function generate(EntityInterface $entity = null): Form;
}

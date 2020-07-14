<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Form\Builder;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\EntityManagerInterface;
use Nette\Forms\Controls\BaseControl;
use Nette\Forms\Controls\MultiSelectBox;
use Nette\Forms\Controls\SelectBox;
use Nette\Forms\Form;
use Vemid\ProjectOne\Common\Annotation\FormElement;
use Vemid\ProjectOne\Common\Filter\Humanize;
use Vemid\ProjectOne\Common\Form\FormBuilderInterface;
use Vemid\ProjectOne\Common\Translator\TranslationInterface;
use Vemid\ProjectOne\Entity\EntityInterface;

/**
 * Class Annotations
 * @package Vemid\ProjectOne\Common\Form
 */
class EntityAnnotationReader implements FormBuilderInterface
{

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var TranslationInterface */
    private $translator;

    /**
     * EntityAnnotationReader constructor.
     * @param EntityManagerInterface $entityManager
     * @param TranslationInterface $translator
     */
    public function __construct(EntityManagerInterface $entityManager, TranslationInterface $translator)
    {
        $this->entityManager = $entityManager;
        $this->translator = $translator;
    }

    /**
     * {@inheritDoc}
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \ReflectionException
     */
    public function build(EntityInterface $entity, array $exclude = [], $enableCsrf = true, $inline = false): Form
    {
        $form = new Form(sha1(get_class($entity)));

        if ($enableCsrf) {
            $form->addProtection('Security token has expired, please submit the form again');
        }

        $form->setHtmlAttribute('novalidate');

        $filter = new Humanize();
        $reader = new AnnotationReader();

        $reflect = new \ReflectionClass($entity);
        $properties = $reflect->getProperties(\ReflectionProperty::IS_PRIVATE);
        $properties = array_merge($properties, $reflect->getProperties(\ReflectionProperty::IS_PROTECTED));

        foreach ($properties as $propertyName) {
            if (!$formPropertyAnnotation = $reader->getPropertyAnnotation($propertyName, FormElement::class)) {
                continue;
            }

            if (in_array($propertyName->name, $exclude, false)) {
                continue;
            }

            $value = $entity->{'get' . ucfirst($propertyName->name)}();
            if ($value instanceof EntityInterface) {
                $value = $value->getEntityId();
            }

            $label = $this->translator->_($formPropertyAnnotation->name ?: ucwords($filter->filter($propertyName->name)));
            $type = $formPropertyAnnotation->type !== 'Date' || $formPropertyAnnotation->type !== 'DateTime' ? $formPropertyAnnotation->type : 'Text';
            $methodToCall = in_array($type, ['Date', 'DateTime', 'Number'], false) ? 'Text' : $type;

            /** @var BaseControl|SelectBox|MultiSelectBox $element */
            $element = $form->{'add' . $methodToCall}($propertyName->name, !$inline ? $label : '');

            $cssClass =  sprintf('form-control%s', $formPropertyAnnotation->hidden ? ' hidden' : '');
            if ($formPropertyAnnotation->required) {
                $element->setRequired($this->translator->_(sprintf('Please fill your %s.', $filter->filter($label))));
                $cssClass .= ' required';
            }

            switch ($formPropertyAnnotation->type) {
                case 'Date':
                    $cssClass .= ' datepicker';
                    break;
                case 'DateTime':
                    $cssClass .= ' dateTimePicker';
                    break;
                case 'Number':
                    $cssClass .= ' touchSpin';
                    break;
                default:
                    $cssClass .= '';
                    break;
            }

            if ($inline) {
                $element->setHtmlAttribute('placeHolder', $label);
            }

            if (in_array($formPropertyAnnotation->type, ['Select', 'MultiSelect'])) {
                $cssClass .= ' chosen-search';
                $options = [];
                if ($formPropertyAnnotation->type === 'Select') {
                    $options[''] = $this->translator->_('Please Select');
                }

                if ($formPropertyAnnotation->options) {
                    $options += $formPropertyAnnotation->options;
                    $translator = $this->translator;

                    array_walk($options, static function (&$value) use ($translator) {
                        $value = $translator->_($value);
                        return $value;
                    });
                } elseif ($formPropertyAnnotation->relation) {
                    /** @var EntityInterface[] $relation */
                    $relationClass = sprintf('%s\\%s', $reflect->getNamespaceName(), $formPropertyAnnotation->relation);
                    $relation = $this->entityManager->getRepository($relationClass)->findAll();

                    foreach ($relation as $obj) {
                        if (!$obj->getDisplayName()) {
                            continue;
                        }

                        $options[$obj->getEntityId()] = $obj->getDisplayName();
                    }
                }

                if ($formPropertyAnnotation->disabled) {
                    $element->setDisabled($formPropertyAnnotation->disabled);
                }

                $element->setItems($options);

                if (!array_key_exists($value, $options)) {
                    $value = '';
                }
            }

            $element->setValue($value instanceof \DateTime ? $value->format('Y-m-d') : ($value ?: ''));
            $element->setHtmlAttribute('class', $cssClass);
        }

        return $form;
    }
}

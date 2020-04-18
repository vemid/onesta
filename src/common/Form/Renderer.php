<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Form;

use Nette\Forms\Controls\BaseControl;
use Nette\Forms\Controls\MultiSelectBox;
use Nette\Forms\Controls\SelectBox;
use Vemid\ProjectOne\Entity\EntityInterface;

/**
 * Class Renderer
 * @package Vemid\Form
 */
abstract class Renderer
{

    /**
     * @param BaseControl $element
     * @return array|string
     */
    protected function renderElementDefaultValue(BaseControl $element)
    {
        $defaultValue = $this->filterValue($element->getValue());

        if ($defaultValue && !\is_array($defaultValue)) {
            $defaultValue = htmlentities((string)$defaultValue);
        }

        return $defaultValue ?? '';
    }

    /**
     * @param BaseControl $element
     * @return string
     */
    protected function renderElementName(BaseControl $element): string
    {
        return $element->getName() ?: $element->getHtmlName();
    }

    /**
     * @param BaseControl $element
     * @return array|string
     */
    protected function renderElementOptions(BaseControl $element)
    {
        if ($element instanceof SelectBox || $element instanceof MultiSelectBox) {
            $options = $element->getItems();
            if (\is_array($options) || $options instanceof \ArrayObject) {
                return $this->filterValue($options);
            }

            return $this->filterValue([$options]);
        }

        return [];
    }

    /**
     * @param BaseControl $element
     * @return string
     */
    protected function renderElementLabel(BaseControl $element): string
    {
        return $element->getLabel() ? $element->getLabel()->getText() : (string)$element->getCaption();
    }

    /**
     * @param BaseControl $element
     * @return string
     */
    protected function renderElementAttributes(BaseControl $element): string
    {
        return $element->getControl()->attributes();
    }

    /**
     * @param BaseControl $element
     * @return array
     */
    protected function renderElementAttributesAsArray(BaseControl $element): array
    {
        $htmlAttributes = trim($this->renderElementAttributes($element));
        $groupAttribute = explode('" ', $htmlAttributes);
        $attributes = [];
        foreach ($groupAttribute as $key => $attribute) {
            if (!$key && !$attribute) {
                continue;
            }

            if (strpos($attribute, '=') === false) {
                $attributes[$attribute] = true;
                continue;
            }

            list($attr, $value) = array_values(explode('=', $attribute, 2));
            $attributes[$attr] = trim($value, '"');
        }

        if (count($attributes) === 0) {
            $attributes['id'] = $element->getHtmlId();
            $attributes['name'] = $element->getHtmlName();
        }

        return $attributes;
    }

    /**
     * @param BaseControl $element
     * @return array
     */
    protected function renderElementMessages(BaseControl $element): array
    {
        $messages = [];
        foreach ($element->getErrors() as $field => $message) {
            if ($field === $element->getName()) {
                $messages[] = $message->getMessage();
                break;
            }
        }

        return $this->filterValue($messages);
    }

    /**
     * @param BaseControl $element
     * @return string
     */
    protected function renderElementType(BaseControl $element): string
    {
        $className = \get_class($element);
        $classNameArray = explode('\\', $className);
        $typeByClassName = lcfirst(end($classNameArray));

        return $this->getElementType($typeByClassName);
    }

    /**
     * @param BaseControl $element
     * @return bool
     */
    protected function isElementRequired(BaseControl $element): bool
    {
        if ($element->isRequired()) {
            return true;
        }

        if ($elementClass = $element->getControl()->getAttribute('class')) {
            if (false !== strpos($elementClass, 'required')) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param mixed $value
     * @return array|string
     */
    protected function filterValue($value)
    {
        if ($value === null) {
            return $value;
        }

        if ($value instanceof EntityInterface) {
            return $value->getObjectId();
        }

        if ($value instanceof \DateTime) {
            return $value->getUnixFormat();
        }

        if ($value instanceof \ArrayObject) {
            $array = [];
            foreach ($value as $key => $val) {
                if ($val instanceof EntityInterface) {
                    $array[$val->getEntityId()] = $val->getDisplayName();
                }
            }

            return $array;
        }

        if (\is_array($value)) {
            foreach ($value as $key => $val) {
                if ($val instanceof EntityInterface) {
                    $value[$val->getEntityId()] = $val->getDisplayName();
                    unset($value[$key]);
                } else {
                    $value[$key] = $this->filterValue($val);
                }
            }
        }

        return $value;
    }

    /**
     * @param BaseControl $element
     * @return array
     */
    protected function renderDisabledOptions(BaseControl $element): array
    {
        $control = $element->getControl();
        $disabledOptions = [];

        if ($control->getHtml() && strpos($control->getHtml(), 'option') !== false) {
            $options = explode('<option', $control->getHtml());
            foreach ($options as $option) {
                if (strpos($option, 'disabled') === false) {
                    continue;
                }

                if (preg_match('/"(.*?)"/', $option, $matches)) {
                    $disabledOptions[] = $matches[1];
                }
            }
        }

        return $disabledOptions;
    }

    /**
     * @param $className
     * @return string
     */
    private function getElementType($className): string
    {
        switch ($className) {
            case 'selectBox':
            case 'multiSelectBox':
                return 'select';
            case 'hiddenField':
                return 'hidden';
            case 'textInput':
                return 'text';
            default:
                return $className;
        }
    }
}

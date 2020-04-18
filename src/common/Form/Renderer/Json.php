<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Form\Renderer;

use Nette\Forms\Form;
use Nette\Utils\Html;
use Vemid\ProjectOne\Common\Form\Renderer;
use Vemid\ProjectOne\Common\Form\RendererInterface;

/**
 * Class Json
 * @package Vemid\ProjectOne\Common\Form
 */
class Json extends Renderer implements RendererInterface
{
    /**
     * {@inheritdoc}
     */
    public function render(Form $form): array
    {
        $formArray = [];
        foreach ($form->getComponents() as $key => $element) {
            /** @var Html $control */
            $control = $element->getControl();
            $formArray[$key]['name'] = $control->getAttribute('name') ?: $key;
            $formArray[$key]['label'] = $this->renderElementLabel($element);
            $formArray[$key]['default'] = $this->renderElementDefaultValue($element);
            $formArray[$key]['options'] = $this->renderElementOptions($element);
            $formArray[$key]['attributes'] = $this->renderElementAttributesAsArray($element);
            $formArray[$key]['messages'] = $this->renderElementMessages($element);
            $formArray[$key]['required'] = $this->isElementRequired($element);
            $formArray[$key]['disabledOptions'] = $this->renderDisabledOptions($element);
            $formArray[$key]['type'] = $this->renderElementType($element);
        }

        return $formArray;
    }
}

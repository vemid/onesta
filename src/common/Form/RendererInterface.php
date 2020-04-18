<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Form;

use Nette\Forms\Form;

/**
 * Interface RendererInterface
 * @package Vemid\Form
 */
interface RendererInterface
{

    /**
     * @param Form $form
     * @return mixed
     */
    public function render(Form $form);
}

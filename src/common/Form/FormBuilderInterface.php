<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Form;

use Nette\Forms\Form;
use Vemid\ProjectOne\Entity\EntityInterface;

/**
 * Interface BuilderInterface
 * @package Vemid\ProjectOne\Common\Form
 */
interface FormBuilderInterface
{

    /**
     * @param EntityInterface $entity
     * @param array $exclude
     * @param bool $enableCsrf
     * @param bool $inline
     * @return Form
     */
    public function build(EntityInterface $entity, array $exclude = [], $enableCsrf = true, $inline = false): Form;
}

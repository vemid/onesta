<?php

declare(strict_types=1);

namespace Vemid\ProjectOne\Common\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target({"PROPERTY","ANNOTATION"})
 */
final class FormElement
{
    /**
     * @var string
     */
    public $name = '';

    /**
     * The type of Id generator.
     *
     * @var string
     *
     * @Enum({"Text", "Select", "TextArea", "Checkbox", "Hidden", "Date", "DateTime", "Email", "Password", "Upload", "MultiSelect"})
     */
    public $type = 'Text';

    /**
     * @var boolean
     */
    public $required = false;

    /**
     * @var boolean
     */
    public $nullable = false;

    /** @var bool */
    public $relation  = false;

    /** @var array */
    public $disabled = [];

    /**
     * @var array
     */
    public $options = [];
}

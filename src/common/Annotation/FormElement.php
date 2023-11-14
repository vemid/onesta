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
    public string $name = '';

    /**
     * @Enum({"Text", "Select", "TextArea", "Checkbox", "Hidden", "Date", "DateTime", "Email", "Password", "Upload", "MultiSelect", "Number"})
     */
    public string $type = 'Text';

    public bool $required = false;

    public bool $nullable = false;

    public string $relation  = '';

    public array $disabled = [];

    public bool $hidden = false;

    public array $options = [];
}

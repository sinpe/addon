<?php

namespace Sinpe\Addon\FieldType;

/**
 * Class FieldTypeInput.
 */
class FieldTypeInput
{
    /**
     * The parent field type.
     *
     * @var FieldType
     */
    protected $fieldType;

    /**
     * Create a new FieldTypeInput instance.
     *
     * @param FieldType $fieldType
     */
    public function __construct(FieldType $fieldType)
    {
        $this->fieldType = $fieldType;
    }
}

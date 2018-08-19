<?php

namespace Sinpe\Addon\FieldType\Event;

use Sinpe\Addon\FieldType\FieldType;

/**
 * Class FieldTypeWasRegistered.
 */
class FieldTypeWasRegistered
{
    /**
     * The fieldType object.
     *
     * @var FieldType
     */
    protected $fieldType;

    /**
     * Create a new FieldTypeWasRegistered instance.
     *
     * @param FieldType $fieldType
     */
    public function __construct(FieldType $fieldType)
    {
        $this->fieldType = $fieldType;
    }

    /**
     * Get the fieldType object.
     *
     * @return FieldType
     */
    public function getFieldType()
    {
        return $this->fieldType;
    }
}

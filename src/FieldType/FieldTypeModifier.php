<?php

namespace Sinpe\Addon\FieldType;

/**
 * Class FieldTypeModifier.
 */
class FieldTypeModifier
{
    /**
     * The parent field type.
     *
     * @var FieldType
     */
    protected $fieldType;

    /**
     * Modify the value for database storage.
     *
     * @param  $value
     *
     * @return mixed
     */
    public function modify($value)
    {
        return $value;
    }

    /**
     * Restore the value from storage format.
     *
     * @param  $value
     *
     * @return mixed
     */
    public function restore($value)
    {
        return $value;
    }

    /**
     * Get the field type.
     *
     * @return FieldType
     */
    public function getFieldType()
    {
        return $this->fieldType;
    }

    /**
     * Set the field type.
     *
     * @param FieldType $fieldType
     *
     * @return $this
     */
    public function setFieldType(FieldType $fieldType)
    {
        $this->fieldType = $fieldType;

        return $this;
    }
}

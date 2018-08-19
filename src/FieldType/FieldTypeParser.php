<?php

namespace Sinpe\Addon\FieldType;

// TODO

/**
 * Class FieldTypeParser.
 */
class FieldTypeParser
{
    /**
     * Return the parsed relation.
     *
     * @return string
     */
    public function relation(AssignmentInterface $assignment)
    {
        $fieldSlug = $assignment->getFieldSlug();
        $fieldName = str_humanize($fieldSlug);
        $method = camel_case($fieldSlug);

        return "
    /**
     * The {$fieldName} relation
     *
     * @return Relation
     */
    public function {$method}()
    {
        return \$this->getFieldType('{$fieldSlug}')->getRelation();
    }
";
    }
}

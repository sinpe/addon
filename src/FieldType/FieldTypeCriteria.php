<?php

namespace Sinpe\Addon\FieldType;

use Anomaly\Streams\Platform\Entry\EntryQueryBuilder;
use Illuminate\Foundation\Bus\DispatchesJobs;

/**
 * Class FieldTypeCriteria.
 */
class FieldTypeCriteria
{
    use DispatchesJobs;

    /**
     * The field type instance.
     *
     * @var FieldType
     */
    protected $fieldType;

    /**
     * The query builder.
     *
     * @var EntryQueryBuilder
     */
    protected $query;

    /**
     * Create a new FieldTypeCriteria instance.
     *
     * @param FieldType         $fieldType
     * @param EntryQueryBuilder $query
     */
    public function __construct(FieldType $fieldType, EntryQueryBuilder $query)
    {
        $this->query = $query;
        $this->fieldType = $fieldType;
    }
}

<?php

declare (strict_types = 1);

namespace Dumplie\Metadata\Application;

use Dumplie\Metadata\Application\Schema\TypeSchema;

final class Association
{
    /**
     * @var string
     */
    private $schema;

    /**
     * @var TypeSchema
     */
    private $type;

    /**
     * @var array
     */
    private $criteria;

    /**
     * Association constructor.
     *
     * @param string     $schema
     * @param TypeSchema $type
     * @param array      $criteria
     */
    public function __construct(string $schema, TypeSchema $type, array $criteria)
    {
        $this->schema = $schema;
        $this->type = $type;
        $this->criteria = $criteria;
    }

    /**
     * @return string
     */
    public function schema() : string
    {
        return $this->schema;
    }

    /**
     * @return TypeSchema
     */
    public function type() : TypeSchema
    {
        return $this->type;
    }

    /**
     * @return array
     */
    public function criteria() : array
    {
        return $this->criteria;
    }
}

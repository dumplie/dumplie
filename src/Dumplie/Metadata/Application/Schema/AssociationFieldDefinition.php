<?php

declare (strict_types = 1);

namespace Dumplie\Metadata\Application\Schema;

interface AssociationFieldDefinition extends FieldDefinition
{
    /**
     * @return string
     */
    public function schema() : string;

    /**
     * @return TypeSchema
     */
    public function typeSchema() : TypeSchema;
}

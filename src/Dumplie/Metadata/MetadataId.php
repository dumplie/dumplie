<?php

declare (strict_types = 1);

namespace Dumplie\Metadata;

use Dumplie\SharedKernel\Domain\Identity\UUID;
use Ramsey\Uuid\Uuid as BaseUUID;

final class MetadataId extends UUID
{
    /**
     * @return MetadataId
     */
    public static function generate() : MetadataId
    {
        return new self((string) BaseUUID::uuid4());
    }
}

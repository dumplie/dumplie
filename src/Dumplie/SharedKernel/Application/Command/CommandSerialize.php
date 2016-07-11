<?php

declare (strict_types = 1);

namespace Dumplie\SharedKernel\Application\Command;

trait CommandSerialize
{
    /**
     * @return string
     */
    final public function serialize() : string
    {
        return serialize(get_object_vars($this));
    }

    /**
     * @param $serialized
     * @return $this
     */
    final public function unserialize($serialized)
    {
        $data = unserialize($serialized);

        foreach ($data as $field => $value) {
            $this->$field = $value;
        }

        return $this;
    }
}
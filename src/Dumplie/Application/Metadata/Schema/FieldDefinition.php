<?php

declare (strict_types = 1);

namespace Dumplie\Application\Metadata\Schema;

interface FieldDefinition
{
    /**
     * @return string
     */
    public function name() : string;

    /**
     * @return Type
     */
    public function type() : Type;

    /**
     * @return array
     */
    public function options() : array;

    /**
     * @return mixed
     */
    public function defaultValue();

    /**
     * @return bool
     */
    public function isNullable() : bool;

    /**
     * @param $value
     * @return bool
     */
    public function isValid($value) : bool;

    /**
     * @param $value
     * @return string
     */
    public function serialize($value) : string;

    /**
     * @param $serializedValue
     * @return mixed
     */
    public function deserialize($serializedValue);
}

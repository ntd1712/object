<?php

namespace Chaos\Support\Object;

/**
 * Interface ArraySerializableInterface.
 */
interface ArraySerializableInterface
{
    /**
     * Exchanges internal values from provided array.
     *
     * @param array $array An array of key/value pairs to exchange.
     *
     * @return $this
     */
    public function exchangeArray(array $array);

    /**
     * Returns an array representation of the object.
     *
     * @return array
     */
    public function getArrayCopy();
}

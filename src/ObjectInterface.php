<?php

namespace Chaos\Support\Object;

/**
 * Interface ObjectInterface.
 */
interface ObjectInterface
{
    /**
     * Indicates whether other object is "equal to" this one.
     *
     * @param object $other The reference object with which to compare.
     *
     * @return bool
     */
    public function equals($other);

    /**
     * Returns a hash code value for the object.
     *
     * @return string
     */
    public function getHashCode();

    /**
     * Gets the short class name of the object, e.g. ObjectInterface.
     *
     * @return string
     */
    public function getClassName();

    /**
     * Factory method for easy instantiation.
     *
     * @return static
     */
    public static function make();
}

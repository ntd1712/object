<?php

namespace Chaos\Support\Object;

/**
 * Trait ObjectTrait.
 */
trait ObjectTrait
{
    /**
     * Returns a string consisting of the name of the class of which the object is an instance,
     * the at-sign character `@', and the unsigned hexadecimal representation of the hash code of the object.
     *
     * @return string
     */
    public function __toString()
    {
        return static::class . '@' . bin2hex($this->getHashCode());
    }

    /**
     * {@inheritDoc}
     *
     * @param object $other The reference object with which to compare.
     *
     * @return bool
     */
    public function equals($other)
    {
        return $this === $other;
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getHashCode()
    {
        return spl_object_hash($this);
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getClassName()
    {
        $name = static::class;

        if (false !== ($string = strrchr($name, '\\'))) {
            return substr($string, 1);
        }

        return $name;
    }

    /**
     * {@inheritDoc}
     *
     * @return static
     */
    public static function make()
    {
        return new static();
    }
}

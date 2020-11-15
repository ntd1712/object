<?php

namespace Chaos\Support\Object;

use BadMethodCallException;
use InvalidArgumentException;

/**
 * Trait ModelTrait.
 */
trait ModelTrait
{
    /**
     * {@inheritDoc}
     *
     * @param string $key The name of the property being interacted with.
     * @param mixed $value The value the $key'ed property should be set to.
     *
     * @throws BadMethodCallException
     *
     * @return void
     */
    public function __set($key, $value)
    {
        $setter = 'set' . ucfirst($key);

        if (is_callable([$this, $setter])) {
            $this->{$setter}($value);

            return;
        }

        if (property_exists($this, $key)) {
            $this->{$key} = $value;

            return;
        }

        throw new BadMethodCallException(
            sprintf(
                '"%s" does not have a callable "%s" ("%s") setter method which must be defined',
                $key,
                'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $key))),
                $setter
            )
        );
    }

    /**
     * {@inheritDoc}
     *
     * @param string $key The name of the property being interacted with.
     *
     * @throws BadMethodCallException
     *
     * @return mixed
     */
    public function __get($key)
    {
        $getter = 'get' . ucfirst($key);

        if (is_callable([$this, $getter])) {
            return $this->{$getter}();
        }

        if (property_exists($this, $key)) {
            return @$this->{$key};
        }

        throw new BadMethodCallException(
            sprintf(
                '"%s" does not have a callable "%s" getter method which must be defined',
                $key,
                'get' . str_replace(' ', '', ucwords(str_replace('_', ' ', $key)))
            )
        );
    }

    /**
     * {@inheritDoc}
     *
     * @param string $key The name of the property being interacted with.
     *
     * @return bool
     */
    public function __isset($key)
    {
        try {
            return null !== $this->__get($key);
        } catch (BadMethodCallException $e) {
            return false;
        }
    }

    /**
     * {@inheritDoc}
     *
     * @param string $key The name of the property being interacted with.
     *
     * @throws InvalidArgumentException
     *
     * @return void
     */
    public function __unset($key)
    {
        try {
            $this->__set($key, null);
        } catch (BadMethodCallException $e) {
            throw new InvalidArgumentException(
                'The class property $' . $key . ' cannot be unset as NULL is an invalid value for it',
                0,
                $e
            );
        }
    }
}

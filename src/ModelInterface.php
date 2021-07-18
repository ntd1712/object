<?php

namespace Chaos\Support\Object;

/**
 * Interface ModelInterface.
 */
interface ModelInterface extends ObjectInterface, ArraySerializableInterface
{
    /**
     * @param string $key The name of the property being interacted with.
     * @param mixed $value The value the $key'ed property should be set to.
     *
     * @return void
     */
    public function __set($key, $value);

    /**
     * @param string $key The name of the property being interacted with.
     *
     * @return mixed
     */
    public function __get($key);

    /**
     * @param string $key The name of the property being interacted with.
     *
     * @return bool
     */
    public function __isset($key);

    /**
     * @param string $key The name of the property being interacted with.
     *
     * @return void
     */
    public function __unset($key);
}

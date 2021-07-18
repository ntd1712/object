<?php

namespace Chaos\Support\Object;

use JsonSerializable;
use Serializable;
use stdClass;

/**
 * Class Model.
 *
 * Model is used to transport Data between Layers.
 */
class Model extends stdClass implements ModelInterface, JsonSerializable, Serializable
{
    use ObjectTrait;
    use ModelTrait;

    // <editor-fold defaultstate="collapsed" desc="Serializable methods">

    /**
     * {@inheritDoc}
     *
     * @param array $array An array of key/value pairs to exchange.
     *
     * @return $this
     */
    public function exchangeArray(array $array)
    {
        foreach ($this as $key => $value) {
            if (isset($array[$key]) || array_key_exists($key, $array)) {
                $this->{$key} = $array[$key];
            }
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     *
     * @return array
     */
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    /**
     * {@inheritDoc}
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->getArrayCopy();
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function serialize()
    {
        return serialize($this->getArrayCopy());
    }

    /**
     * {@inheritDoc}
     *
     * @param string $serialized The string representation of the object.
     *
     * @return void
     */
    public function unserialize($serialized)
    {
        $array = $this->getArrayCopy();

        foreach (unserialize("{$serialized}") as $key => $value) {
            if (isset($array[$key]) || array_key_exists($key, $array)) {
                $this->{$key} = $value;
            }
        }
    }

    // </editor-fold>
}

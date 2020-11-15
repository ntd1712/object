<?php

namespace Chaos\Support\Object;

use ArrayAccess;
use Countable;
use InvalidArgumentException;
use IteratorAggregate;
use Serializable;

/**
 * Class ArrayObject.
 *
 * @see https://github.com/laminas/laminas-stdlib/blob/master/src/ArrayObject.php
 */
class ArrayObject implements IteratorAggregate, ArrayAccess, Serializable, Countable
{
    /**
     * Properties of the object have their normal functionality
     * when accessed as list (var_dump, foreach, etc.).
     */
    public const STD_PROP_LIST = 1;

    /**
     * Entries can be accessed as properties (read and write).
     */
    public const ARRAY_AS_PROPS = 2;

    /**
     * @var array
     */
    protected $storage;

    /**
     * @var int
     */
    protected $flag;

    /**
     * @var string
     */
    protected $iteratorClass;

    /**
     * @var array
     */
    protected $protectedProperties;

    /**
     * Constructor.
     *
     * @param array $input
     * @param int $flags
     * @param string $iteratorClass
     */
    public function __construct($input = [], $flags = self::STD_PROP_LIST, $iteratorClass = 'ArrayIterator')
    {
        $this->setFlags($flags);
        $this->storage = $input;
        $this->setIteratorClass($iteratorClass);
        $this->protectedProperties = array_keys(get_object_vars($this));
    }

    /**
     * Returns whether the requested key exists.
     *
     * @param mixed $key
     *
     * @return bool
     */
    public function __isset($key)
    {
        if (self::ARRAY_AS_PROPS == $this->flag) {
            return $this->offsetExists($key);
        }

        if (in_array($key, $this->protectedProperties)) {
            throw new InvalidArgumentException('$key is a protected property, use a different key');
        }

        return isset($this->{$key});
    }

    /**
     * Sets the value at the specified key to value.
     *
     * @param mixed $key
     * @param mixed $value
     *
     * @return void
     */
    public function __set($key, $value)
    {
        if (self::ARRAY_AS_PROPS == $this->flag) {
            $this->offsetSet($key, $value);

            return;
        }

        if (in_array($key, $this->protectedProperties)) {
            throw new InvalidArgumentException('$key is a protected property, use a different key');
        }

        $this->{$key} = $value;
    }

    /**
     * Un-sets the value at the specified key.
     *
     * @param mixed $key
     *
     * @return void
     */
    public function __unset($key)
    {
        if (self::ARRAY_AS_PROPS == $this->flag) {
            $this->offsetUnset($key);

            return;
        }

        if (in_array($key, $this->protectedProperties)) {
            throw new InvalidArgumentException('$key is a protected property, use a different key');
        }

        unset($this->{$key});
    }

    /**
     * Returns the value at the specified key by reference.
     *
     * @param mixed $key
     *
     * @return mixed
     */
    public function &__get($key)
    {
        $ret = null;

        if (self::ARRAY_AS_PROPS == $this->flag) {
            $ret = &$this->offsetGet($key);

            return $ret;
        }

        if (in_array($key, $this->protectedProperties)) {
            throw new InvalidArgumentException('$key is a protected property, use a different key');
        }

        return $this->{$key};
    }

    /**
     * Appends the value.
     *
     * @param mixed $value
     *
     * @return void
     */
    public function append($value)
    {
        $this->storage[] = $value;
    }

    /**
     * Sorts the entries by value.
     *
     * @return void
     */
    public function asort()
    {
        asort($this->storage);
    }

    /**
     * Exchanges the array for another one.
     *
     * @param array|ArrayObject $data
     *
     * @return array
     */
    public function exchangeArray($data)
    {
        if (!is_array($data) && !is_object($data)) {
            throw new InvalidArgumentException(
                'Passed variable is not an array or object, using empty array instead'
            );
        }

        if (is_object($data) && ($data instanceof self || $data instanceof \ArrayObject)) {
            $data = $data->getArrayCopy();
        }

        if (!is_array($data)) {
            $data = (array) $data;
        }

        $storage = $this->storage;
        $this->storage = $data;

        return $storage;
    }

    /**
     * Creates a copy of the ArrayObject.
     *
     * @return array
     */
    public function getArrayCopy()
    {
        return $this->storage;
    }

    /**
     * Gets the behavior flags.
     *
     * @return int
     */
    public function getFlags()
    {
        return $this->flag;
    }

    /**
     * Gets the iterator classname for the ArrayObject.
     *
     * @return string
     */
    public function getIteratorClass()
    {
        return $this->iteratorClass;
    }

    /**
     * Sorts the entries by key.
     *
     * @return void
     */
    public function ksort()
    {
        ksort($this->storage);
    }

    /**
     * Sorts an array using a case insensitive "natural order" algorithm.
     *
     * @return void
     */
    public function natcasesort()
    {
        natcasesort($this->storage);
    }

    /**
     * Sorts entries using a "natural order" algorithm.
     *
     * @return void
     */
    public function natsort()
    {
        natsort($this->storage);
    }

    /**
     * Sets the behavior flags.
     *
     * @param int $flags
     *
     * @return void
     */
    public function setFlags($flags)
    {
        $this->flag = $flags;
    }

    /**
     * Sets the iterator classname for the ArrayObject.
     *
     * @param string $class
     *
     * @return void
     */
    public function setIteratorClass($class)
    {
        if (class_exists($class)) {
            $this->iteratorClass = $class;

            return;
        }

        if (0 === strpos($class, '\\')) {
            $class = '\\' . $class;

            if (class_exists($class)) {
                $this->iteratorClass = $class;

                return;
            }
        }

        throw new InvalidArgumentException('The iterator class does not exist');
    }

    /**
     * Sorts the entries with a user-defined comparison function and maintain key association.
     *
     * @param callable $function
     *
     * @return void
     */
    public function uasort($function)
    {
        if (is_callable($function)) {
            uasort($this->storage, $function);
        }
    }

    /**
     * Sorts the entries by keys using a user-defined comparison function.
     *
     * @param callable $function
     *
     * @return void
     */
    public function uksort($function)
    {
        if (is_callable($function)) {
            uksort($this->storage, $function);
        }
    }

    // <editor-fold defaultstate="collapsed" desc="IteratorAggregate methods">

    /**
     * {@inheritDoc}
     *
     * @return \Iterator
     */
    public function getIterator()
    {
        $class = $this->iteratorClass;

        return new $class($this->storage);
    }

    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="ArrayAccess methods">

    /**
     * {@inheritDoc}
     *
     * @param mixed $offset An offset to check for.
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->storage[$offset]);
    }

    /**
     * {@inheritDoc}
     *
     * @param mixed $offset The offset to retrieve.
     *
     * @return mixed
     */
    public function &offsetGet($offset)
    {
        $ret = null;

        if (!$this->offsetExists($offset)) {
            return $ret;
        }

        $ret = &$this->storage[$offset];

        return $ret;
    }

    /**
     * {@inheritDoc}
     *
     * @param mixed $offset The offset to assign the value to.
     * @param mixed $value The value to set.
     *
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->storage[$offset] = $value;
    }

    /**
     * {@inheritDoc}
     *
     * @param mixed $offset The offset to unset.
     *
     * @return void
     */
    public function offsetUnset($offset)
    {
        if ($this->offsetExists($offset)) {
            unset($this->storage[$offset]);
        }
    }

    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Serializable methods">

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function serialize()
    {
        return serialize(get_object_vars($this));
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
        $array = unserialize($serialized);
        $this->protectedProperties = array_keys(get_object_vars($this));

        $this->setFlags($array['flag']);
        $this->exchangeArray($array['storage']);
        $this->setIteratorClass($array['iteratorClass']);

        foreach ($array as $k => $v) {
            switch ($k) {
                case 'flag':
                    $this->setFlags($v);
                    break;
                case 'storage':
                    $this->exchangeArray($v);
                    break;
                case 'iteratorClass':
                    $this->setIteratorClass($v);
                    break;
                case 'protectedProperties':
                    break;
                default:
                    $this->__set($k, $v);
            }
        }
    }

    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Countable methods">

    /**
     * {@inheritDoc}
     *
     * @return int
     */
    public function count()
    {
        return count($this->storage);
    }

    // </editor-fold>
}

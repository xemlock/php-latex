<?php

class PhpLatex_Utils_PeekableArrayIterator
    implements Iterator, Countable, ArrayAccess, PhpLatex_Utils_PeekableIterator
{
    /**
     * @var array
     */
    protected $_array;

    public function __construct(array $array = array())
    {
        $this->_array = $array;

        // reset internal array pointer, otherwise current position will
        // be copied from the original array!
        reset($this->_array);
    }

    public function current()
    {
        return current($this->_array);
    }

    public function key()
    {
        return key($this->_array);
    }

    public function next()
    {
        return next($this->_array);
    }

    public function rewind()
    {
        return reset($this->_array);
    }

    public function valid()
    {
        return key($this->_array) !== null;
    }

    public function count()
    {
        return count($this->_array);
    }

    public function offsetExists($offset)
    {
        return isset($this->_array[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->_array[$offset]) ? $this->_array[$offset] : null;
    }

    public function offsetSet($offset, $value) {
        if (is_null($offset)) {
            $this->_array[] = $value;
        } else {
            $this->_array[$offset] = $value;
        }
    }

    public function offsetUnset($offset)
    {
        unset($this->_array[$offset]);
    }

    public function __isset($offset)
    {
        return $this->offsetExists($offset);
    }

    public function __unset($offset)
    {
        $this->_offsetUnset($offset);
    }

    public function peek()
    {
        if ($this->valid()) {
            $value = next($this->_array);
            prev($this->_array);
            return $value;
        }
        return false;
    }

    public function hasNext()
    {
        if ($this->valid()) {
            next($this->_array);
            $result = $this->valid();
            prev($this->_array);
            return $result;
        }
        return false;
    }
}

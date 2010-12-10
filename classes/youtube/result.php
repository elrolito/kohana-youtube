<?php defined('SYSPATH') or die('No direct script access.');

abstract class YouTube_Result implements Countable, Iterator, ArrayAccess {
    
    /**
     * Store all the items found.
     *
     * @var array items
     */
    protected $_items;
    
    // Implementation Functions
    
    /**
     * Implements [Countable::count], returns the total number of items.
     *
     *     echo count($result);
     *
     * @return  integer
     */
    public function count()
    {
        return count($this->_items);
    }
    
    /**
     * the current position index in the array of items.
     *
     * @var int index
     */
    private $_position;
    
    /**
     * Implements [Iterator::current], returns the current item.
     *
     *     echo current($result);
     *
     * @return  integer
     */
    public function current()
    {
        return $this->_items[$this->_position];
    }

    /**
     * Implements [Iterator::key], returns the current item number.
     *
     *     echo key($result);
     *
     * @return  integer
     */
    public function  key()
    {
        return $this->_position;
    }

    /**
     * Implements [Iterator::next], moves to the next item.
     *
     *     next($result);
     *
     * @return  $this
     */
    public function next()
    {
        ++$this->_position;
        return $this;
    }

    /**
     * Implements [Iterator::rewind], sets the current item to zero.
     *
     *     rewind($result);
     *
     * @return  $this
     */
    public function rewind()
    {
        $this->_position = 0;
        return $this;
    }

    /**
     * Implements [Iterator::valid], checks if the current item exists.
     *
     * [!!] This method is only used internally.
     *
     * @return  boolean
     */
    public function  valid()
    {
        return isset($this->_items[$this->_position]);
    }
    
    /**
     * Implements [ArrayAccess::offsetExists], determines if item exists.
     *
     * @return  boolean
     */
    public function offsetExists($offset)
    {
        return isset($this->_items[$offset]);
    }
    
    /**
     * Implements [ArrayAccess::offsetGet], gets a given item.
     *
     * @return  mixed
     */
    public function offsetGet($offset)
    {
        return isset($this->_items[$offset]) ? $this->_items[$offset] : null;
    }
    
    /**
     * Implements [ArrayAccess::offsetSet], throws an error.
     *
     * [!!] You cannot modify a result.
     *
     * @return  void
     * @throws  Kohana_Exception
     */
    final public function offsetSet($offset, $value)
    {
        throw new Kohana_Exception('YouTube results are read-only');
    }
    
    /**
     * Implements [ArrayAccess::offsetUnset], throws an error.
     *
     * [!!] You cannot modify a result.
     *
     * @return  void
     * @throws  Kohana_Exception
     */
    final public function offsetUnset($offset)
    {
        throw new Kohana_Exception('YouTube results are read-only');
    }
}
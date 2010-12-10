<?php defined('SYSPATH') or die('No direct script access.');

class YouTube_Result implements Countable, Iterator, ArrayAccess {
    
    protected $_items;
    
    // Implementation Functions
    
    public function count()
    {
        return count($this->_items);
    }
    
    private $_position;

    public function current()
    {
        return $this->_items[$this->_position];
    }

    public function  key()
    {
        return $this->_position;
    }

    public function next()
    {
        ++$this->_position;
    }

    public function rewind()
    {
        $this->_position = 0;
    }

    public function  valid()
    {
        return isset($this->_items[$this->_position]);
    }
    
    public function items()
    {
        return $this->_items;
    }
    
    public function offsetSet($offset, $value)
    {
        if (is_null($offset))
        {
            $this->_items[] = $value;
        }
        else 
        {
            $this->_items[$offset] = $value;
        }
    }
    
    public function offsetExists($offset)
    {
        return isset($this->_items[$offset]);
    }
    
    public function offsetUnset($offset)
    {
        unset($this->_items[$offset]);
    }
    
    public function offsetGet($offset)
    {
        return isset($this->_items[$offset]) ? $this->_items[$offset] : null;
    }
}
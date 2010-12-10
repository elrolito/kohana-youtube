<?php defined('SYSPATH') or die('No direct script access.');

/**
 * YouTube base item class
 *
 * @package    YouTube
 * @category   base/item
 * @author     Rolando Henry
 * @copyright (c) 2010 Rolando Henry
 * @license    http://creativecommons.org/licenses/BSD/
 */
abstract class YouTube_Item {
    
    protected $_meta = array();
    
    public function __construct($data)
    {
        // init properties
        $this->_meta = array(
                'id' => $data->id,
                'title' => $data->title,
                'description' => $data->description
            );
    }
    
    /**
     * Handles retrieval of metadata.
     *
     * @param   string  meta name
     * @return  mixed
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->_meta))
        {
            return $this->_meta[$name];
        }
    }
}
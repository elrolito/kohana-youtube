<?php defined('SYSPATH') or die('No direct script access.');

/**
 * YouTube base data class
 *
 * @package    YouTube
 * @category   base
 * @author     Rolando Henry
 * @copyright (c) 2010 Rolando Henry
 * @license    http://creativecommons.org/licenses/BSD/
 */
abstract class YouTube_Data {
    
    // Object info
    protected $_cache_key;
    protected $_url;
    protected $_data;
    
    // pagination info
    protected $_pagination = array();
    
    /**
     * What kind of data will be returned,
     * either YouTube::USER_PLAYLISTS, or YouTube::PLAYLIST_VIDEOS
     *
     * @var string data type to return
     */
    protected $_result_type = '';
    
    public function __construct($name)
    {
        // set the cache key based on passed name
        $this->_cache_key = $name;
        
        // Initialize data
        $this->_initialize();
    }
    
    protected function _initialize()
    {
         // check if a result type is set
         if ($this->_result_type === NULL)
         {
             throw new Exception('No result type set.');
         }
 
         // check to see if data is cached first
        $feed = Kohana::cache($this->_cache_key);

        // Get fresh feed
        if ($feed === NULL OR ! YouTube::$use_cache)
        {
            try {
                // Remote call the feed url
                $feed = Remote::get($this->_url);

                // Cache the response
                Kohana::cache($this->_cache_key, $feed, 300);
            } catch (Exception $e) {
                // Do nothing for now
                // @todo some error checking
                return FALSE;
            }
        }

        $json = json_decode($feed);

        if ($json !== NULL)
        {
            $data = $json->data;

            // store a copy of the decoded data locally
            $this->_data = serialize($data);

            // set meta properties
            $this->_pagination = array(
                    'total_items'    => $data->totalItems,
                    'items_per_page' => $data->itemsPerPage,
                    'offset'         => $data->startIndex - 1,
                    'limit'          => $data->totalItems
                );
        }
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
    
    public function __sleep()
    {
        return array('_pagination', '_url', '_cache_key', '_result_type');
    }
    
    public function __wakeup()
    {
        $this->_initialize();
    }
    
    public function offset($num)
    {
        $this->_pagination['offset'] = $num;
        
        return $this;
    }
    
    public function limit($count)
    {        
        $this->_pagination['limit'] = $count;
        
        return $this;
    }
    
    /**
     * Find all related items
     *
     * @return YouTube_Result class (depends on result type of this class)
     */
    public function find_all()
    {
        $result_type = $this->_result_type;
        
        $data = unserialize($this->_data);
        
        // paginate items
        $items = array_slice(
                $data->items,
                $this->_pagination['offset'],
                $this->_pagination['limit']
            );
        
        return new $result_type($items);
    }
    
    /**
     * Find a specific item
     *
     * @param string $id 
     * @return YouTube_Item (depends on result type of this class)
     */
    public function find($id)
    {
        $search_ids = array();
        
        $data = unserialize($this->_data);
        
        foreach ($data->items as $item)
        {
            $search_ids[] = ($this->_result_type === YouTube::PLAYLIST_RESULT) ?
                $item->video->id : $item->id;
        }
        
        $index = array_search($id, $search_ids);
        
        $result_type = $this->_result_type;
        
        $results = new $result_type($data->items);
        
        return ($index !== FALSE) ? $results[$index] : $index;
    }
}
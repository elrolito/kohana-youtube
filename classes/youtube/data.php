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
class YouTube_Data {
    
    protected $_url;
    
    protected $_data;
    
    protected $_meta = array();
    
    /**
     * What kind of data will be returned,
     * either YouTube::USER_PLAYLISTS, or YouTube::PLAYLIST_VIDEOS
     *
     * @var string data type to return
     */
    protected $_result_type = '';
    
    private $_result_class;
    
    public function __construct($name, $url)
    {
        // set the class for the items being returned
        switch ($this->_result_type)
        {
            case YouTube::USER_PLAYLISTS:
                $this->_result_class = 'YouTube_Playlist_Result';
            break;
            case YouTube::PLAYLIST_VIDEOS:
                $this->_result_class = 'YouTube_Video_Result';
            break;
            default:
                throw new Exception('No result type set.');
        }
        
        // check to see if data is cached first
        $feed = Kohana::cache($name);
        
        // Get fresh feed
        if ($feed === NULL OR ! YouTube::$use_cache)
        {
            try {
                // Remote call the feed url
                $feed = Remote::get($url);
                
                // Cache the response
                Kohana::cache($name, $feed, 300);
            } catch (Exception $e) {
                // Do nothing for now
                // @todo some error checking
                return FALSE;
            }
        }
        
        $json = json_decode($feed);
        $data = $json->data;
        
        if ($data !== NULL)
        {
            // store a copy of the decoded data locally
            $this->_data = serialize($data);
            
            // set meta properties
            $this->_meta = array(
                    'total_items'    => $data->totalItems,
                    'items_per_page' => $data->itemsPerPage,
                    'offset'         => $data->startIndex
                );
                
            $this->_limit = $data->totalItems;
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
    
    public function find_all()
    {
        $class_name = $this->_result_class;
        
        $data = unserialize($this->_data);
        
        $items = array_slice($data->items, $this->_offset, $this->_limit);
        
        return new $class_name($items);
    }
    
    public function find($id)
    {
        $search_ids = array();
        
        $data = unserialize($this->_data);
        
        //return $data;
        foreach ($data->items as $item)
        {
            $search_ids[] = ($this->_result_type === YouTube::PLAYLIST_VIDEOS) ?
                $item->video->id : $item->id;
        }
        
        $index = array_search($id, $search_ids);
        
        $class_name = $this->_result_class;
        
        $results = new $class_name($data->items);
        
        return ($index !== FALSE) ? $results[$index] : $index;
    }
    
    protected $_offset = 0;
    
    public function offset($num)
    {
        $this->_offset = $num;
        
        return $this;
    }
    
    protected $_limit;
    
    public function limit($count)
    {        
        $this->_limit = $count;
        
        return $this;
    }
}
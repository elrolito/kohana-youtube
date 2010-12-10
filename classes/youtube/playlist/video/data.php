<?php defined('SYSPATH') or die('No direct script access.');

class YouTube_Playlist_Video_Data extends YouTube_Data {
    
    protected $_playlist_id;
    
    protected $_meta;
    
    public function __construct($id, $order_by = 'published')
    {
        $this->_playlist_id = $id;
        
        $this->_url = YouTube::API_URL.'playlists/'.$id.'?v=2&alt=jsonc&orderby='.$order_by;
        
        $this->_result_type = YouTube::VIDEO_RESULT;
        
        parent::__construct($id);
    }
    
    public function __sleep()
    {
        return Arr::merge(array('_playlist_id', '_meta'), parent::__sleep());
    }
    
    protected function _initialize()
    {
        $data = parent::_initialize();
        
        $this->_meta = array(
                'id' => $data->id,
                'title' => $data->title,
                'description' => $data->description
            );
            
        return $data;
    }
}
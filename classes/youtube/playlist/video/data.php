<?php defined('SYSPATH') or die('No direct script access.');

class YouTube_Playlist_Video_Data extends YouTube_Data {
    
    protected $_playlist_id;
    
    public function __construct($id, $order_by = 'published')
    {
        $this->_playlist_id = $id;
        
        $this->_url = YouTube::API_URL.'playlists/'.$id.'?v=2&alt=jsonc&orderby='.$order_by;
        
        $this->_result_type = YouTube::PLAYLIST_VIDEOS;
        
        parent::__construct($id, $this->_url);
        
        $data = unserialize($this->_data);
        
        $meta = array(
                'id' => $data->id,
                'title' => $data->title,
                'description' => $data->description
            );
            
        $this->_meta = Arr::merge($meta, $this->_meta);
    }
}
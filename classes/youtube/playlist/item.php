<?php defined('SYSPATH') or die('No direct script access.');

class YouTube_Playlist_Item extends YouTube_Item {
    
    protected $_videos;
    
    public function __construct($data)
    {
        parent::__construct($data);
        
        $this->_videos = new YouTube_Playlist_Video_Data($this->id);
    }
    
    public function videos()
    {
        return $this->_videos;
    }
}
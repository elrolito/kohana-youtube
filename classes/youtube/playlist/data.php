<?php defined('SYSPATH') or die('No direct script access.');

class YouTube_Playlist_Data extends YouTube_Data {
    
    public function __construct($user)
    {
        $this->_url = YouTube::API_URL.'users/'.$user.'/playlists?v=2&alt=jsonc';
        
        $this->_result_type = YouTube::PLAYLIST_RESULT;
        
        parent::__construct('yt_user_'.$user);
    }
}
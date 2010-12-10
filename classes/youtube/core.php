<?php defined('SYSPATH') or die('No direct script access.');

/**
 * YouTube API Helper class extension
 *
 * @package    YouTube
 * @category   base
 * @author     Rolando Henry
 * @copyright (c) 2010 Rolando Henry
 * @license    http://creativecommons.org/licenses/BSD/
 */
class YouTube_Core {
    
    // YouTube Data types
    const USER_PLAYLISTS = 'user playlists';
    const PLAYLIST_VIDEOS = 'playlist videos';
    
    // YouTube API base URL
    const API_URL = 'http://gdata.youtube.com/feeds/api/';
    
    // Toggle feed caching
    public static $use_cache = true;
    
    public static function playlists($username)
    {
        return new YouTube_Playlist($username);
    }
    
    public static function playlist_videos($playlist_id, $order_by = 'published')
    {
        return new YouTube_Playlist_Video($playlist_id, $order_by);
    }
}
<?php defined('SYSPATH') or die('No direct script access.');

class YouTube_Video_Item extends YouTube_Item {
    
    protected $_thumbnails = array();
    
    private $_timestamp;
    
    public function __construct($data)
    {
        parent::__construct($data);
        
        // get count
        try {
            $view_count = $data->viewCount;
        } catch (Exception $e) {
            $view_count = 0;
        }
        
        // initialize video properties
        $meta = array(
                'duration' => $this->sec2hms($data->duration, true),
                'views' => $view_count
            );
        
        // merge extended meta properties with base meta
        $this->_meta = Arr::merge($this->_meta, $meta);
        
        $this->_thumbnails = array(
                'small' => $data->thumbnail->sqDefault, 
                'large' => $data->thumbnail->hqDefault
            );
        $this->_timestamp = strtotime($data->uploaded);
    }
    
    /**
     * Get thumbnail for video
     * 
     * @param string thumb size (small|large)
     * @return string thumb url
     */
    public function thumb($size = 'large')
    {
        if ($size != 'large')
        {
            // default to small thumb
            $size = 'small';
        }
        
        return $this->_thumbnails[$size];
    }
    
    /**
     * Get link for video player
     *
     * @param string type of link: short or embed
     * @return string url to video
     **/
    public function link($type = false)
    {
        switch ($type)
        {
            case 'short':
                return 'http://youtu.be/'.$this->id;
            break;
            case 'embed':
                return 'http://www.youtube.com/embed/'.$this->id;
            break;
            default:
                return 'http://www.youtube.com/watch?v='.$this->id;
        }
    }
    
    /**
     * Format the uploaded timestamp
     *
     * @return string date
     **/
    public function uploaded($format = false)
    {
        if ( ! $format)
        {
            $format = 'F jS, Y g:i a T';
        }
        
        return date($format, $this->_timestamp);
    }
    
    /**
     * Convert time in seconds to hh:mm:ss
     *
     * @return string time
     **/
    private function sec2hms ($sec, $padHours = false) 
    {
        $hms = "";
        
        $hours = intval(intval($sec) / 3600); 
        
        if ($hours > 0)
        {
            $hms .= ($padHours) 
                  ? str_pad($hours, 2, "0", STR_PAD_LEFT). ":"
                  : $hours. ":";
        }
        
        $minutes = intval(($sec / 60) % 60); 

        $hms .= str_pad($minutes, 2, "0", STR_PAD_LEFT). ":";

        $seconds = intval($sec % 60); 

        $hms .= str_pad($seconds, 2, "0", STR_PAD_LEFT);

        return $hms;
    }
}
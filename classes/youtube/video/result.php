<?php defined('SYSPATH') or die('No direct script access.');

class YouTube_Video_Result extends YouTube_Result {
    
    public function __construct($items)
    {
        foreach ($items as $item)
        {
            $this->_items[] = new YouTube_Video($item->video);
        }
    }
}
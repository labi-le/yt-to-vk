<?php

class YoutubeParse
{


    private $length_id = 11;
    private $youtube_link;


    function __construct($youtube_link = null)
    {
        $this->youtube_link = $youtube_link;
    }

    public function get_id()
    {
        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $this->youtube_link, $match)) {
            return $match[1] ?? false;
        }
    }
    public function get_data()
    {
        $youtube = "http://www.youtube.com/oembed?url=https://www.youtube.com/watch?v=" . $this->youtube_link . "&format=json";
        $return = json_decode(file_get_contents($youtube), true);
        $return['id'] = $this->youtube_link;
        $return['preview'] = $return['thumbnail_url'];
        return $return;
    }
}

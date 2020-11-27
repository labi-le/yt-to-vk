<?php

class YoutubeDB
{
    private const second_column = 'video_id = ?';
    private const TAB = 'video';

    public static function add_video($video_id, $vk_url, $author)
    {
        $video = R::Dispense(self::TAB);
        $video->video_id = $video_id;
        $video->vk_url = $vk_url;
        $video->author = $author;

        R::Store($video);
    }

    private static function getter($id, $where)
    {
        return R::findOne(self::TAB, self::second_column, [$id])->$where ?? false;
    }

    public static function get_video($video_id)
    {
        return self::getter($video_id, 'vk_url');
    }
}

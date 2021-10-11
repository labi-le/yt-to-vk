<?php
declare(strict_types=1);

namespace App\Service\Video;

class RegexEnum
{
    public const REGEX_YOUTUBE = '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i';
    public const REGEX_TIKTOK = '/vm\.tiktok\.com\/(.*)\//si';
    public const REGEX_PORNHUB = '/rt\.pornhub\.com\/view_video\.php\?viewkey=(.*)/si';
}
<?php
declare(strict_types=1);

namespace App\Service\Video;

class ServiceEnum
{
    public const TIKTOK = "TikTok";
    public const YOUTUBE = "YouTube";

    public const REGEX_YOUTUBE = '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i';
    public const REGEX_TIKTOK = '/vm\.tiktok\.com\/(.*)\//si';
}
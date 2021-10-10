<?php

declare(strict_types=1);

namespace App\Service\Video;

use App\Entity\YouTubeVideo;
use JetBrains\PhpStorm\ArrayShape;

class VideoService
{
    /**
     * @param YouTubeVideo $video
     * @return YouTubeVideo
     * @throws YouTubeForbiddenException
     */
    public static function get(YouTubeVideo $video): VideoInterface
    {
        $data = self::legacyParser($video);
        $video
            ->setAuthor($data["author"])
            ->setTitle($data["title"])
            ->setPreview($data["preview"]);

        return $video;
    }

    /**
     * @throws YouTubeForbiddenException
     */
    #[ArrayShape(["author" => "?string", "preview" => "string", "title" => "string"])]
    private static function legacyParser(YouTubeVideo $video): array
    {
        $query = "https://www.youtube.com/oembed?url=https://www.youtube.com/watch?v={$video->getId()}&format=json";

        $content = @file_get_contents($query) ?: throw new YouTubeForbiddenException("Не удалось получить данные о видео");

        $response = @json_decode($content, true);

        return
            [
                "author" => $response["author_name"],
                "title" => $response["title"],
                "preview" => $response["thumbnail_url"]
            ];
    }
}
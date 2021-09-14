<?php

declare(strict_types=1);

namespace App\Service\Video;

use App\Entity\Video;
use JetBrains\PhpStorm\ArrayShape;

class VideoService
{
    /**
     * @param string $id
     * @return Video
     */
    public static function get(string $id): Video
    {
        $data = self::legacyParser($id);
        $video = new Video();
        $video
            ->setId($id)
            ->setAuthor($data["author"])
            ->setTitle($data["title"])
            ->setPreview($data["preview"]);

        return $video;
    }

    #[ArrayShape(["author" => "?string", "preview" => "?string", "title" => "?string"])]
    private static function legacyParser(string $id): array
    {
        $query = "https://www.youtube.com/oembed?url=https://www.youtube.com/watch?v=$id&format=json";
        $response = @json_decode(@file_get_contents($query), true);

        return
            [
                "author" => $response["author_name"] ?? null,
                "title" => $response["title"] ?? null,
                "preview" => $response["thumbnail_url"] ?? null
            ];
    }
}
<?php

declare(strict_types=1);

namespace App\Service\Video;

use App\Entity\YouTubeVideo;
use Astaroth\Support\Facades\Entity;

class Cache
{
    /**
     * @param VideoInterface $video
     * @return ?VideoInterface
     */
    public static function get(VideoInterface $video): ?VideoInterface
    {
        return (new Entity())->find($video->getType(), $video->getId());
    }

    public static function cache(VideoInterface $video): VideoInterface
    {
        $entity = new Entity();
        $entity->persist($video);
        $entity->flush();

        return $video;
    }
}
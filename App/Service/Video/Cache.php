<?php

declare(strict_types=1);

namespace App\Service\Video;

use App\Entity\Video;
use Astaroth\Support\Facades\Entity;

class Cache
{
    /**
     * @param string $id
     * @return Video|null
     */
    public static function get(string $id): ?Video
    {
        return (new Entity())->find(Video::class, $id);
    }

    public static function cache(Video $video): Video
    {
        $entity = new Entity();
        $entity->persist($video);
        $entity->flush();

        return $video;
    }
}